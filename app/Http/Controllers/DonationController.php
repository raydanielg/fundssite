<?php

namespace App\Http\Controllers;

use App\Models\DonationTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function createSession(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'amount' => ['nullable', 'integer', 'min:1000'],
        ]);

        $currency = 'TZS';
        $minAmount = 1000;
        $maxAmount = 150000000;

        if (Schema::hasTable('fundraiser_settings')) {
            $row = DB::table('fundraiser_settings')->orderBy('id')->first();
            if ($row) {
                $currency = (string) ($row->currency ?: $currency);
                $maxAmount = (int) ($row->target_amount ?: $maxAmount);
            }
        }

        $apiKey = config('services.snippe.api_key');
        if (!$apiKey) {
            return response()->json([
                'message' => 'Snippe API key is not configured.',
            ], 500);
        }

        $idempotencyKey = (string) Str::uuid();

        $baseUrl = config('services.snippe.base_url', 'https://api.snippe.sh');
        $webhookUrl = config('services.snippe.webhook_url') ?: url('/api/snippe/webhook');

        $payload = [
            'amount' => (int) ($data['amount'] ?? 1000), // Default to 1000 if not provided
            'currency' => $currency,
            'allowed_methods' => ['mobile_money', 'qr', 'card'],
            'allow_custom_amount' => true,
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
            'customer' => [
                'name' => $data['name'] ?: 'Donor',
            ],
            'redirect_url' => url('/donate/return?session_id={session_id}'),
            'webhook_url' => $webhookUrl,
            'description' => 'Donation for Cliff',
            'metadata' => [
                'source' => 'landing',
            ],
            'expires_in' => 3600,
            'display' => [
                'show_description' => true,
                'theme' => 'light',
                'success_message' => 'Thank you for your donation!',
                'button_text' => 'Donate Now'
            ]
        ];

        $res = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Idempotency-Key' => $idempotencyKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post('https://api.snippe.sh/v1/sessions', $payload);

        if (!$res->ok()) {
            \Log::error('Snippe Session Creation Failed', [
                'status' => $res->status(),
                'payload' => $payload,
                'response' => $res->json(),
            ]);
            return response()->json([
                'message' => 'Failed to create donation session.',
                'details' => $res->json(),
            ], 422);
        }

        $body = $res->json();
        $sess = $body['data'] ?? null;

        if (!is_array($sess) || empty($sess['reference']) || empty($sess['checkout_url'])) {
            return response()->json([
                'message' => 'Unexpected Snippe response.',
                'details' => $body,
            ], 422);
        }

        DonationTransaction::create([
            'reference' => $sess['reference'],
            'status' => $sess['status'] ?? 'pending',
            'amount' => (int) ($sess['amount'] ?? ($data['amount'] ?? 0)),
            'currency' => $sess['currency'] ?? 'TZS',
            'customer_name' => $data['name'] ?? 'Donor',
            'customer_phone' => $data['phone'] ?? null,
            'customer_email' => $data['email'] ?? null,
            'checkout_url' => $sess['checkout_url'] ?? null,
            'payment_link_url' => $sess['payment_link_url'] ?? null,
            'raw_payload' => $sess,
        ]);

        return response()->json([
            'reference' => $sess['reference'],
            'checkout_url' => $sess['checkout_url'],
        ]);
    }

    public function returnPage(Request $request)
    {
        $sessionId = $request->query('session_id');
        return redirect('/?status=success&session_id=' . $sessionId . '#main');
    }
}
