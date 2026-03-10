<?php

namespace App\Http\Controllers;

use App\Models\DonationTransaction;
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
        $event = (string) $request->header('X-Webhook-Event', '');
        
        // Snippe webhooks usually have a type or event name
        $type = $data['type'] ?? $event;
        $sessionData = $data['data'] ?? [];
        $ref = $sessionData['reference'] ?? null;

        if (!$ref) {
            return response()->json(['message' => 'Missing reference.'], 422);
        }

        $tx = DonationTransaction::where('reference', $ref)->first();
        if ($tx) {
            $status = $sessionData['status'] ?? $tx->status;
            $paidAt = $tx->paid_at;
            
            if ($status === 'completed' && !$tx->paid_at) {
                $completedAt = $sessionData['completed_at'] ?? null;
                $paidAt = $completedAt ? Carbon::parse($completedAt) : now();
            }

            $amountData = $sessionData['amount'] ?? [];
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
        }

        return response()->json(['ok' => true]);
    }
}
