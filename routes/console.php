<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use App\Models\DonationTransaction;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('snippe:sync-pending {--limit=50}', function () {
    $apiKey = config('services.snippe.api_key');
    if (!$apiKey) {
        $this->error('Snippe API key is not configured.');
        return 1;
    }

    $baseUrl = config('services.snippe.base_url', 'https://api.snippe.sh');
    $limit = (int) ($this->option('limit') ?? 50);
    if ($limit < 1) {
        $limit = 1;
    }

    $pending = DonationTransaction::query()
        ->where('status', 'pending')
        ->orderByDesc('created_at')
        ->limit($limit)
        ->get(['id', 'reference', 'status', 'paid_at', 'raw_payload', 'created_at']);

    $updated = 0;
    foreach ($pending as $t) {
        if (!$t->reference) {
            continue;
        }

        $res = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->get(rtrim($baseUrl, '/') . '/api/v1/sessions/' . urlencode($t->reference));

        if (!$res->successful()) {
            $this->warn('Failed to fetch session: ' . $t->reference . ' (HTTP ' . $res->status() . ')');
            continue;
        }

        $body = $res->json();
        $sess = $body['data'] ?? null;
        if (!is_array($sess)) {
            continue;
        }

        $status = $sess['status'] ?? null;
        if ($status && $status !== $t->status) {
            $t->status = $status;
        }

        if (($status === 'completed') && !$t->paid_at) {
            $t->paid_at = now();
        }

        $t->raw_payload = $sess;
        $t->save();
        $updated++;
    }

    $this->info('Synced ' . $pending->count() . ' pending sessions. Updated: ' . $updated);
    return 0;
})->purpose('Sync pending donation transactions with Snippe session status');
