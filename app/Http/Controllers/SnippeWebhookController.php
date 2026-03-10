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

        $payload = $request->getContent();
        $signature = (string) $request->header('X-Webhook-Signature', '');

        $expected = hash_hmac('sha256', $payload, $secret);
        if (!hash_equals($expected, $signature)) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $event = (string) $request->header('X-Webhook-Event', '');
        $data = $request->json()->all();

        $type = $data['type'] ?? $event;
        $ref = $data['data']['reference'] ?? null;

        if (!$ref) {
            return response()->json(['message' => 'Missing reference.'], 422);
        }

        $tx = DonationTransaction::where('reference', $ref)->first();
        if ($tx) {
            $status = $data['data']['status'] ?? $tx->status;
            $paidAt = null;
            if ($status === 'completed') {
                $completedAt = $data['data']['completed_at'] ?? null;
                $paidAt = $completedAt ? Carbon::parse($completedAt) : now();
            }

            $amountValue = $data['data']['amount']['value'] ?? null;
            $amountCurrency = $data['data']['amount']['currency'] ?? null;

            $tx->update([
                'status' => $status,
                'paid_at' => $paidAt ?? $tx->paid_at,
                'amount' => is_numeric($amountValue) ? (int) $amountValue : $tx->amount,
                'currency' => $amountCurrency ?: $tx->currency,
                'external_reference' => $data['data']['external_reference'] ?? $tx->external_reference,
                'webhook_event' => $type ?: $tx->webhook_event,
                'failure_reason' => $data['data']['failure_reason'] ?? $tx->failure_reason,
                'raw_payload' => $data,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
