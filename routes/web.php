<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\SnippeWebhookController;
use App\Models\DonationTransaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $settings = [
        'target_amount' => 150000000,
        'expenses_amount' => 2289225,
        'currency' => 'TZS',
    ];

    if (Schema::hasTable('fundraiser_settings')) {
        $row = DB::table('fundraiser_settings')->orderBy('id')->first();

        if (!$row) {
            DB::table('fundraiser_settings')->insert([
                'target_amount' => $settings['target_amount'],
                'expenses_amount' => $settings['expenses_amount'],
                'currency' => $settings['currency'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $row = DB::table('fundraiser_settings')->orderBy('id')->first();
        }

        if ($row) {
            $settings = [
                'target_amount' => (int) $row->target_amount,
                'expenses_amount' => (int) $row->expenses_amount,
                'currency' => (string) $row->currency,
            ];
        }
    }

    $hasPaidAt = Schema::hasColumn('donation_transactions', 'paid_at');

    $query = DonationTransaction::query()
        ->whereIn('status', ['pending', 'completed']);

    if ($hasPaidAt) {
        $query->orderByDesc('paid_at');
    }

    $transactions = $query
        ->orderByDesc('created_at')
        ->limit(500)
        ->get(array_values(array_filter([
            'reference',
            'status',
            $hasPaidAt ? 'paid_at' : null,
            'amount',
            'currency',
            'customer_name',
            'customer_phone',
            'customer_email',
            'external_reference',
            'created_at',
        ])));

    return view('welcome', [
        'transactions' => $transactions,
        'settings' => $settings,
    ]);
});

Route::post('/donate/session', [DonationController::class, 'createSession'])->name('donate.session');
Route::get('/donate/return', [DonationController::class, 'returnPage'])->name('donate.return');

Route::post('/api/snippe/webhook', [SnippeWebhookController::class, 'handle'])->name('webhooks.snippe');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', function () {
        $settings = [
            'target_amount' => 150000000,
            'expenses_amount' => 2289225,
            'currency' => 'TZS',
        ];

        if (Schema::hasTable('fundraiser_settings')) {
            $row = DB::table('fundraiser_settings')->orderBy('id')->first();
            if ($row) {
                $settings = [
                    'target_amount' => (int) $row->target_amount,
                    'expenses_amount' => (int) $row->expenses_amount,
                    'currency' => (string) $row->currency,
                ];
            }
        }

        $totalRaised = (int) DonationTransaction::query()
            ->where('status', 'completed')
            ->sum('amount');

        $paidCount = (int) DonationTransaction::query()
            ->where('status', 'completed')
            ->count();

        $paidTodayCount = (int) DonationTransaction::query()
            ->where('status', 'completed')
            ->whereDate('paid_at', now()->toDateString())
            ->count();

        $pendingCount = (int) DonationTransaction::query()
            ->where('status', 'pending')
            ->count();

        $balance = $totalRaised - ($settings['expenses_amount'] ?? 0);
        $target = (int) ($settings['target_amount'] ?? 0);
        $pct = $target > 0 ? min(100, ($totalRaised / $target) * 100) : 0;

        $recentPaid = DonationTransaction::query()
            ->where('status', 'completed')
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get([
                'reference',
                'paid_at',
                'amount',
                'currency',
                'customer_name',
                'created_at',
            ]);

        return view('admin.dashboard', [
            'settings' => $settings,
            'totalRaised' => $totalRaised,
            'paidCount' => $paidCount,
            'paidTodayCount' => $paidTodayCount,
            'pendingCount' => $pendingCount,
            'balance' => $balance,
            'pct' => $pct,
            'recentPaid' => $recentPaid,
        ]);
    })->name('admin.dashboard');

    Route::get('/admin/transactions', function () {
        $transactions = DonationTransaction::query()
            ->whereIn('status', ['pending', 'completed'])
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->limit(200)
            ->get([
                'reference',
                'status',
                'paid_at',
                'amount',
                'currency',
                'customer_name',
                'customer_phone',
                'customer_email',
                'created_at',
            ]);

        return view('admin.transactions', [
            'transactions' => $transactions,
        ]);
    })->name('admin.transactions');

    Route::get('/admin/fundraiser', function () {
        $row = DB::table('fundraiser_settings')->orderBy('id')->first();
        return view('admin.fundraiser-settings', [
            'settings' => $row,
        ]);
    })->name('admin.fundraiser');

    Route::post('/admin/fundraiser', function (\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'target_amount' => ['required', 'integer', 'min:1'],
            'expenses_amount' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        $row = DB::table('fundraiser_settings')->orderBy('id')->first();
        if (!$row) {
            DB::table('fundraiser_settings')->insert([
                'target_amount' => (int) $data['target_amount'],
                'expenses_amount' => (int) $data['expenses_amount'],
                'currency' => strtoupper($data['currency']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('fundraiser_settings')->where('id', $row->id)->update([
                'target_amount' => (int) $data['target_amount'],
                'expenses_amount' => (int) $data['expenses_amount'],
                'currency' => strtoupper($data['currency']),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.fundraiser');
    })->name('admin.fundraiser.update');
});

require __DIR__.'/auth.php';
