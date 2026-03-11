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

        if ($row) {
            $settings = [
                'target_amount' => (int) $row->target_amount,
                'expenses_amount' => (int) $row->expenses_amount,
                'currency' => (string) $row->currency,
                'selcom_name' => (string) ($row->selcom_name ?? ''),
                'selcom_number' => (string) ($row->selcom_number ?? ''),
                'tigo_name' => (string) ($row->tigo_name ?? ''),
                'tigo_number' => (string) ($row->tigo_number ?? ''),
                'crdb_name' => (string) ($row->crdb_name ?? ''),
                'crdb_number' => (string) ($row->crdb_number ?? ''),
            ];
        } else {
            // If no settings exist yet, don't auto-insert or show dummy data
            $settings = [
                'target_amount' => 0,
                'expenses_amount' => 0,
                'currency' => 'TZS',
                'selcom_name' => '',
                'selcom_number' => '',
                'tigo_name' => '',
                'tigo_number' => '',
                'crdb_name' => '',
                'crdb_number' => '',
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
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->limit(500)
            ->get();

        return view('admin.transactions', [
            'transactions' => $transactions,
        ]);
    })->name('admin.transactions');

    Route::delete('/admin/transactions/{transaction}', function (DonationTransaction $transaction) {
        $transaction->delete();
        // The total raised is calculated dynamically from completed transactions in the controller/closure
        return redirect()->route('admin.transactions')->with('status', 'deleted');
    })->name('admin.transactions.destroy');

    Route::patch('/admin/transactions/{transaction}', function (Request $request, DonationTransaction $transaction) {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'paid_at' => ['nullable', 'date'],
        ]);

        $status = $transaction->status;
        // If amount is 0 or very small (less than 10), we might want to treat it as pending visually
        // but for database, we keep the status. The user specifically asked for front-end logic.

        $transaction->update([
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'amount' => $data['amount'],
            'paid_at' => $data['paid_at'] ? \Illuminate\Support\Carbon::parse($data['paid_at']) : $transaction->paid_at,
        ]);

        return redirect()->route('admin.transactions')->with('status', 'updated');
    })->name('admin.transactions.update');

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
            $row = DB::table('fundraiser_settings')->orderBy('id', 'asc')->first();
            if ($row && $row->currency) {
                $currency = (string) $row->currency;
            }
        }

        $recent = DonationTransaction::query()
            ->where('webhook_event', 'manual')
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('admin.manual-donations', [
            'currency' => $currency,
            'recent' => $recent,
        ]);
    })->name('admin.transactions.manual');

    Route::get('/admin/transactions/manual/template', function () {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'Full Name');
        $sheet->setCellValue('B1', 'Phone Number');
        $sheet->setCellValue('C1', 'Amount');
        $sheet->setCellValue('D1', 'Date (YYYY-MM-DD)');
        
        // Style header
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);

        // Example row
        $sheet->setCellValue('A2', 'John Doe');
        $sheet->setCellValue('B2', '0700000000');
        $sheet->setCellValue('C2', '50000');
        $sheet->setCellValue('D2', now()->format('Y-m-d'));

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="manual_donations_template.xlsx"');
        $writer->save('php://output');
        exit;
    })->name('admin.transactions.manual.template');

    Route::post('/admin/transactions/manual/import', function (Request $request) {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $importData = [];
        // Skip header row
        for ($i = 1; $i < count($rows); $i++) {
            if (empty($rows[$i][0]) || empty($rows[$i][2])) continue;

            $importData[] = [
                'name' => $rows[$i][0],
                'phone' => $rows[$i][1] ?? '-',
                'amount' => (int) str_replace(',', '', $rows[$i][2]),
                'date' => $rows[$i][3] ?? now()->format('Y-m-d'),
            ];
        }

        if (empty($importData)) {
            return back()->withErrors(['excel_file' => 'No valid data found in the file.']);
        }

        return view('admin.manual-import-review', [
            'importData' => $importData
        ]);
    })->name('admin.transactions.manual.import');

    Route::post('/admin/transactions/manual/import/confirm', function (Request $request) {
        $data = json_decode($request->input('import_json'), true);
        
        if (!$data || !is_array($data)) {
            return redirect()->route('admin.transactions.manual')->with('error', 'Invalid import data.');
        }

        $currency = 'TZS';
        if (Schema::hasTable('fundraiser_settings')) {
            $row = DB::table('fundraiser_settings')->orderBy('id', 'asc')->first();
            if ($row && $row->currency) {
                $currency = (string) $row->currency;
            }
        }

        foreach ($data as $item) {
            $ref = 'MAN' . now()->format('YmdHis') . strtoupper(Str::random(6));
            DonationTransaction::create([
                'reference' => $ref,
                'status' => 'completed',
                'paid_at' => \Illuminate\Support\Carbon::parse($item['date']),
                'amount' => (int) $item['amount'],
                'currency' => $currency,
                'customer_name' => $item['name'],
                'customer_phone' => $item['phone'],
                'webhook_event' => 'manual',
                'raw_payload' => ['source' => 'bulk_import'],
            ]);
        }

        return redirect()->route('admin.transactions.manual')->with('status', 'imported');
    })->name('admin.transactions.manual.confirm');

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

    Route::delete('/admin/expenses/{expense}', function (FundraiserExpense $expense) {
        $expense->delete();
        
        // Recalculate total expenses in settings
        $settingsRow = DB::table('fundraiser_settings')->orderBy('id', 'asc')->first();
        if ($settingsRow) {
            $newTotal = (int) FundraiserExpense::query()->sum('amount');
            DB::table('fundraiser_settings')->where('id', $settingsRow->id)->update([
                'expenses_amount' => $newTotal,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.expenses')->with('status', 'deleted');
    })->name('admin.expenses.destroy');

    Route::patch('/admin/expenses/{expense}', function (Request $request, FundraiserExpense $expense) {
        $data = $request->validate([
            'spent_at' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'receipt' => ['nullable', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
            $expense->receipt_path = $receiptPath;
        }

        $expense->update([
            'spent_at' => $data['spent_at'],
            'description' => $data['description'],
            'amount' => (int) $data['amount'],
        ]);

        // Recalculate total expenses in settings
        $settingsRow = DB::table('fundraiser_settings')->orderBy('id', 'asc')->first();
        if ($settingsRow) {
            $newTotal = (int) FundraiserExpense::query()->sum('amount');
            DB::table('fundraiser_settings')->where('id', $settingsRow->id)->update([
                'expenses_amount' => $newTotal,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.expenses')->with('status', 'updated');
    })->name('admin.expenses.update');

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
            'currency' => ['required', 'string', 'max:3'],
            'selcom_name' => ['nullable', 'string', 'max:255'],
            'selcom_number' => ['nullable', 'string', 'max:255'],
            'tigo_name' => ['nullable', 'string', 'max:255'],
            'tigo_number' => ['nullable', 'string', 'max:255'],
            'crdb_name' => ['nullable', 'string', 'max:255'],
            'crdb_number' => ['nullable', 'string', 'max:255'],
        ]);

        DB::table('fundraiser_settings')->updateOrInsert(
            ['id' => 1],
            array_merge($data, [
                'currency' => strtoupper($data['currency']),
                'updated_at' => now(),
            ])
        );

        return redirect()->route('admin.fundraiser')->with('status', 'settings-updated');
    })->name('admin.fundraiser.update');
});

require __DIR__.'/auth.php';
