<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\SnippeWebhookController;
use App\Models\DonationTransaction;
use App\Models\FundraiserExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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
Route::get('/donate/session', function () {
    return redirect('/');
});
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

    Route::post('/admin/transactions/sync', function (Request $request) {
        $data = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $limit = (int) ($data['limit'] ?? 50);
        Artisan::call('snippe:sync-pending', [
            '--limit' => $limit,
        ]);

        return redirect()->route('admin.transactions')->with('status', 'synced');
    })->name('admin.transactions.sync');

    Route::get('/admin/transactions/manual', function () {
        $currency = 'TZS';
        if (Schema::hasTable('fundraiser_settings')) {
            $row = DB::table('fundraiser_settings')->orderBy('id')->first();
            if ($row && $row->currency) {
                $currency = (string) $row->currency;
            }
        }

        $recent = DonationTransaction::query()
            ->where('webhook_event', 'manual')
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get([
                'reference',
                'paid_at',
                'amount',
                'currency',
                'customer_name',
                'customer_phone',
                'created_at',
            ]);

        return view('admin.manual-donations', [
            'currency' => $currency,
            'recent' => $recent,
        ]);
    })->name('admin.transactions.manual');

    Route::post('/admin/transactions/manual', function (Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        $currency = 'TZS';
        if (Schema::hasTable('fundraiser_settings')) {
            $row = DB::table('fundraiser_settings')->orderBy('id')->first();
            if ($row && $row->currency) {
                $currency = (string) $row->currency;
            }
        }

        $ref = 'MAN' . now()->format('YmdHis') . strtoupper(Str::random(6));

        DonationTransaction::create([
            'reference' => $ref,
            'status' => 'completed',
            'paid_at' => now(),
            'amount' => (int) $data['amount'],
            'currency' => $currency,
            'customer_name' => $data['name'],
            'customer_phone' => $data['phone'],
            'customer_email' => null,
            'checkout_url' => null,
            'payment_link_url' => null,
            'external_reference' => null,
            'webhook_event' => 'manual',
            'failure_reason' => null,
            'raw_payload' => [
                'source' => 'manual',
            ],
        ]);

        return redirect()->route('admin.transactions.manual')->with('status', 'saved');
    })->name('admin.transactions.manual.store');

    Route::get('/admin/users', function () {
        $currency = 'TZS';
        if (Schema::hasTable('fundraiser_settings')) {
            $row = DB::table('fundraiser_settings')->orderBy('id')->first();
            if ($row && $row->currency) {
                $currency = (string) $row->currency;
            }
        }

        $users = DB::table('donation_transactions')
            ->select([
                'customer_name',
                'customer_phone',
                'customer_email',
                DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_completed"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count"),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count"),
                DB::raw("SUM(CASE WHEN status NOT IN ('pending','completed') THEN 1 ELSE 0 END) as failed_count"),
                DB::raw("MAX(paid_at) as last_paid_at"),
                DB::raw("MAX(created_at) as last_seen_at"),
            ])
            ->groupBy('customer_name', 'customer_phone', 'customer_email')
            ->orderByDesc('total_completed')
            ->limit(2000)
            ->get();

        return view('admin.users', [
            'users' => $users,
            'currency' => $currency,
        ]);
    })->name('admin.users');

    Route::get('/admin/expenses', function () {
        $currency = 'TZS';
        $settingsRow = null;
        if (Schema::hasTable('fundraiser_settings')) {
            $settingsRow = DB::table('fundraiser_settings')->orderBy('id')->first();
            if ($settingsRow && $settingsRow->currency) {
                $currency = (string) $settingsRow->currency;
            }
        }

        $expenses = FundraiserExpense::query()
            ->orderByDesc('spent_at')
            ->orderByDesc('created_at')
            ->limit(300)
            ->get();

        $total = (int) FundraiserExpense::query()->sum('amount');

        return view('admin.expenses', [
            'expenses' => $expenses,
            'currency' => $currency,
            'total' => $total,
            'settings' => $settingsRow,
        ]);
    })->name('admin.expenses');

    Route::post('/admin/expenses', function (Request $request) {
        $currency = 'TZS';
        $settingsRow = null;
        if (Schema::hasTable('fundraiser_settings')) {
            $settingsRow = DB::table('fundraiser_settings')->orderBy('id')->first();
            if ($settingsRow && $settingsRow->currency) {
                $currency = (string) $settingsRow->currency;
            }
        }

        $data = $request->validate([
            'spent_at' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'receipt' => ['nullable', 'file', 'max:5120'],
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        FundraiserExpense::create([
            'spent_at' => $data['spent_at'],
            'description' => $data['description'],
            'amount' => (int) $data['amount'],
            'currency' => $currency,
            'receipt_path' => $receiptPath,
        ]);

        if ($settingsRow) {
            $newTotal = (int) FundraiserExpense::query()->sum('amount');
            DB::table('fundraiser_settings')->where('id', $settingsRow->id)->update([
                'expenses_amount' => $newTotal,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.expenses')->with('status', 'saved');
    })->name('admin.expenses.store');

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
