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
            return response()->json(['message' => 'Webhook secret not configured.'], 500);
        }

        $secret = trim((string) $secret);

        $payload = $request->getContent();
        $signature = trim((string) $request->header('X-Webhook-Signature', ''));

        if ($signature === '') {
            \Log::warning('Snippe webhook missing signature header', [
                'path' => $request->path(),
                'headers' => $request->headers->all(),
            ]);
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        if (str_starts_with($signature, 'sha256=')) {
            $signature = substr($signature, 7);
        }

        $expected = hash_hmac('sha256', $payload, $secret);
        if (!hash_equals($expected, $signature)) {
            \Log::warning('Snippe webhook invalid signature', [
                'path' => $request->path(),
                'signature_len' => strlen($signature),
                'expected_prefix' => substr($expected, 0, 12),
                'signature_prefix' => substr($signature, 0, 12),
            ]);
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $data = $request->json()->all();
        $eventHeader = (string) $request->header('X-Webhook-Event', '');

        $eventId = $data['id'] ?? null;
        $type = (string) ($data['type'] ?? $eventHeader);
        $sessionData = is_array($data['data'] ?? null) ? $data['data'] : [];

        if ($eventId) {
            try {
                SnippeWebhookEvent::firstOrCreate(
                    ['event_id' => (string) $eventId],
                    ['type' => $type ?: null, 'received_at' => now()]
                );
            } catch (\Throwable $e) {
                // If unique constraint triggers due to duplicates, treat as already processed.
                return response()->json(['ok' => true]);
            }
        }

        // Snippe webhooks include payment reference AND session_reference
        // Our DB stores session reference (PAY...) in donation_transactions.reference
        $sessionRef = $sessionData['session_reference'] ?? null;
        $ref = $sessionRef ?: ($sessionData['reference'] ?? null);

        if (!$ref) {
            return response()->json(['message' => 'Missing reference.'], 422);
        }

        $tx = DonationTransaction::where('reference', $ref)->first();
        if (!$tx) {
            // Unknown transaction; acknowledge to prevent retries storm.
            return response()->json(['ok' => true]);
        }

        $status = (string) ($sessionData['status'] ?? $tx->status);
        $paidAt = $tx->paid_at;

        if ($status === 'completed' && !$tx->paid_at) {
            $completedAt = $sessionData['completed_at'] ?? null;
            $paidAt = $completedAt ? Carbon::parse($completedAt) : now();
        }

        if (in_array($type, ['payment.failed', 'payment.cancelled'], true) && $status === 'pending') {
            $status = $type === 'payment.cancelled' ? 'cancelled' : 'failed';
        }

        $amountData = is_array($sessionData['amount'] ?? null) ? $sessionData['amount'] : [];
        $amountValue = $amountData['value'] ?? null;
        $amountCurrency = $amountData['currency'] ?? null;

        $tx->update([
            'status' => $status,
            'paid_at' => $paidAt,
            'amount' => is_numeric($amountValue) ? (int) $amountValue : $tx->amount,
            'currency' => $amountCurrency ?: $tx->currency,
            'external_reference' => $sessionData['external_reference'] ?? $tx->external_reference,
            'webhook_event' => $type ?: $tx->webhook_event,
            'failure_reason' => $sessionData['failure_reason'] ?? $tx->failure_reason,
            'raw_payload' => $data,
        ]);

        return response()->json(['ok' => true]);
    }
}
