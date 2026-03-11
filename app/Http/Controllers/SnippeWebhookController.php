<?php

namespace App\Http\Controllers;

use App\Models\DonationTransaction;
use App\Models\SnippeWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SnippeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('services.snippe.webhook_secret');
        if (!$secret) {
            \Log::error('Snippe webhook secret not configured in services.php');
            return response()->json(['message' => 'Webhook secret not configured.'], 500);
        }

        $secret = trim((string) $secret);
        $payload = $request->getContent();
        $signature = trim((string) $request->header('X-Webhook-Signature', ''));

        if ($signature === '') {
            \Log::warning('Snippe webhook missing signature header');
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        // Snippe uses sha256=... format
        if (str_starts_with($signature, 'sha256=')) {
            $signature = substr($signature, 7);
        }

        $expected = hash_hmac('sha256', $payload, $secret);
        if (!hash_equals($expected, $signature)) {
            // Log details for debugging production mismatch
            \Log::warning('Snippe webhook invalid signature', [
                'received_raw' => $request->header('X-Webhook-Signature'),
                'received_cleaned' => $signature,
                'payload_sample' => substr($payload, 0, 50),
                'secret_set' => !empty($secret),
            ]);
            
            // Temporary for production debugging: if secret starts with 'whsec_', it's definitely Snippe's format
            return response()->json([
                'message' => 'Invalid signature.',
                'debug_hint' => 'Check if SNIPPE_WEBHOOK_SECRET in .env matches Snippe Dashboard.'
            ], 401);
        }

        $data = $request->json()->all();
        $type = (string) ($data['type'] ?? $request->header('X-Webhook-Event', ''));
        $eventId = $data['id'] ?? null;
        $sessionData = $data['data'] ?? [];

        // Idempotency: Save event to prevent double processing
        if ($eventId) {
            try {
                $exists = SnippeWebhookEvent::where('event_id', $eventId)->exists();
                if ($exists) {
                    return response()->json(['ok' => true, 'message' => 'Already processed']);
                }
                SnippeWebhookEvent::create([
                    'event_id' => $eventId,
                    'type' => $type,
                    'received_at' => now()
                ]);
            } catch (\Throwable $e) {
                \Log::error('Failed to log webhook event', ['error' => $e->getMessage()]);
            }
        }

        // Reference handling
        $ref = $sessionData['session_reference'] ?? ($sessionData['reference'] ?? null);

        if (!$ref) {
            return response()->json(['message' => 'Missing reference.'], 422);
        }

        $tx = DonationTransaction::where('reference', $ref)->first();
        if (!$tx) {
            \Log::info('Snippe webhook: Transaction not found', ['ref' => $ref]);
            return response()->json(['ok' => true, 'message' => 'Transaction not found']);
        }

        // Status mapping
        $status = $tx->status;
        $paidAt = $tx->paid_at;

        if ($type === 'checkout.session.completed' || ($data['type'] ?? '') === 'payment.succeeded') {
            $status = 'completed';
            $paidAt = isset($sessionData['completed_at']) ? Carbon::parse($sessionData['completed_at']) : now();
        } elseif (in_array($type, ['payment.failed', 'checkout.session.expired'])) {
            $status = 'failed';
        }

        $amountData = $sessionData['amount'] ?? [];
        
        $tx->update([
            'status' => $status,
            'paid_at' => $paidAt,
            'amount' => isset($amountData['value']) ? (int) $amountData['value'] : $tx->amount,
            'currency' => $amountData['currency'] ?? $tx->currency,
            'external_reference' => $sessionData['external_reference'] ?? $tx->external_reference,
            'webhook_event' => $type,
            'failure_reason' => $sessionData['failure_reason'] ?? $tx->failure_reason,
            'raw_payload' => $data,
        ]);

        return response()->json(['ok' => true]);
    }
}
