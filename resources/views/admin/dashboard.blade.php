@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashibodi')

@push('styles')
<style>
    .small-box { border-radius: 4px !important; position: relative; display: block; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,0.1); }
    .small-box > .inner { padding: 10px; }
    .small-box > .small-box-footer { position: relative; text-align: right; padding: 3px 10px; color: rgba(255,255,255,0.8); display: block; z-index: 10; background: rgba(0,0,0,0.1); text-decoration: none; }
    .small-box > .small-box-footer:hover { color: #fff; background: rgba(0,0,0,0.15); }
    .small-box h2 { font-size: 38px; font-weight: bold; margin: 0 0 10px 0; white-space: nowrap; padding: 0; }
    .small-box p { font-size: 15px; }
    .small-box .icon { position: absolute; top: -10px; right: 10px; z-index: 0; font-size: 90px; color: rgba(0,0,0,0.15); }
</style>
@endpush

@section('content')
    @php
        $cur = (string) (($settings['currency'] ?? null) ?: 'TZS');
        $raised = (int) ($totalRaised ?? 0);
        $fmt = fn ($n) => number_format((int) $n, 0, '.', ',');
    @endphp
    <div class="row g-3 mt-1">
        <!-- All Users (Cyan) -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box shadow-sm mb-0" style="background-color: #17a2b8 !important; min-height: 120px; color: #fff !important;">
                <div class="inner p-3">
                    <h2 id="kpi-total-users" class="fw-bold mb-1" style="font-size: 2.2rem;">{{ (int) ($paidCount ?? 0) + (int) ($pendingCount ?? 0) }}</h2>
                    <p class="mb-0">Total Users</p>
                </div>
                <div class="icon" style="opacity: 0.2;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <a href="{{ url('/admin/transactions') }}" class="small-box-footer py-1">
                    User List <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Transactions (Green) -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box shadow-sm mb-0" style="background-color: #28a745 !important; min-height: 120px; color: #fff !important;">
                <div class="inner p-3">
                    <h2 id="kpi-total-payments" class="fw-bold mb-1" style="font-size: 2.2rem;">{{ (int) ($paidCount ?? 0) + (int) ($pendingCount ?? 0) }}</h2>
                    <p class="mb-0">Total Payments</p>
                </div>
                <div class="icon" style="opacity: 0.2;">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <a href="{{ url('/admin/transactions') }}" class="small-box-footer py-1">
                    Analyze Statistics <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

        <!-- New Today (Yellow) -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box shadow-sm mb-0" style="background-color: #ffc107 !important; min-height: 120px; color: #333 !important;">
                <div class="inner p-3">
                    <h2 id="kpi-paid-today" class="fw-bold mb-1" style="font-size: 2.2rem;">{{ (int) ($paidTodayCount ?? 0) }}</h2>
                    <p class="mb-0">New Today</p>
                </div>
                <div class="icon" style="opacity: 0.2;">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <a href="{{ url('/admin/transactions') }}" class="small-box-footer py-1" style="color: rgba(0,0,0,0.6) !important;">
                    User Report <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Cash Flow (Red) -->
        <div class="col-lg-3 col-sm-6">
            <div class="small-box shadow-sm mb-0" style="background-color: #dc3545 !important; min-height: 120px; color: #fff !important;">
                <div class="inner p-3">
                    <h2 id="kpi-cash-flow" class="fw-bold mb-1" style="font-size: 1.8rem; padding-top: 5px;">{{ $cur }} {{ $fmt($raised) }}</h2>
                    <p class="mb-0">Cash Flow</p>
                </div>
                <div class="icon" style="opacity: 0.2;">
                    <i class="bi bi-wallet-fill"></i>
                </div>
                <a href="{{ url('/admin/transactions') }}" class="small-box-footer py-1">
                    Full Analysis <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12 text-muted small px-3">
            <span class="fw-bold">Admin Panel</span> — {{ now()->timezone('Africa/Dar_es_Salaam')->format('l, d F Y') }}
        </div>
    </div>

    <div class="row mt-2 g-3">
        <!-- Chart Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 0;">
                <div class="card-header bg-white py-2 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0 fw-bold" style="font-size: 14px;"><i class="bi bi-graph-up me-2"></i>System Statistics (This Month)</h6>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="bi bi-dash-lg"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4" style="height: 350px;">
                        <canvas id="mainChart"></canvas>
                    </div>
                    <div class="d-flex justify-content-end gap-4 p-2 bg-light border-top">
                        <span class="small fw-bold text-dark d-flex align-items-center"><i class="bi bi-square-fill me-2" style="color: #343a40;"></i> Income</span>
                        <span class="small fw-bold text-secondary d-flex align-items-center"><i class="bi bi-square-fill me-2" style="color: #6c757d;"></i> Expenses</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Users + Premium) -->
        <div class="col-lg-4">
            <!-- New Users Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 0;">
                <div class="card-header bg-white py-2 border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0 fw-bold" style="font-size: 14px;"><i class="bi bi-person-plus me-2"></i>New Users</h6>
                        <span id="live-pill" class="badge text-bg-light border" style="font-weight:800">Live</span>
                    </div>
                </div>
                <div class="card-body p-4 bg-light bg-opacity-50">
                    <div id="recent-users" class="row g-4 text-center">
                        @forelse(($recentPaid->slice(0, 6) ?? []) as $t)
                            <div class="col-4">
                                <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm border" style="width: 45px; height: 45px; font-weight: 800; font-size: 1.2rem;">
                                    {{ substr($t->customer_name, 0, 1) }}
                                </div>
                                <div class="fw-bold text-truncate" style="font-size: 13px;">{{ explode(' ', $t->customer_name)[0] }}</div>
                                <div class="text-muted" style="font-size: 11px;">since {{ optional($t->paid_at)->diffForHumans(null, true) ?? '1h' }}</div>
                            </div>
                        @empty
                            <div class="col-12 text-muted py-3">No new users yet.</div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-light border-top text-center py-2">
                    <a href="{{ url('/admin/transactions') }}" class="text-primary fw-bold text-decoration-none" style="font-size: 13px;">View All Users</a>
                </div>
            </div>

            <!-- Premium Card -->
            <div class="card border-0 shadow-sm overflow-hidden" style="background-color: #0b1e33; border-radius: 0;">
                <div class="card-body p-4 d-flex align-items-center text-white">
                    <div class="me-3">
                        <i class="bi bi-star-fill text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 small mb-1 fw-bold">Premium Earnings</h6>
                        <h3 class="text-white fw-bold mb-1" style="font-size: 1.8rem;">{{ $cur }} 0.00</h3>
                        <div class="text-white-50" style="font-size: 12px;">Goal: {{ $cur }} 500,000 this month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('mainChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Income',
                        data: [65, 59, 80, 81, 56, 55],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#28a745'
                    }, {
                        label: 'Expenses',
                        data: [28, 48, 40, 19, 86, 27],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#dc3545'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                            ticks: { font: { size: 11 } }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fmt = (n) => (parseInt(n || 0, 10) || 0).toLocaleString('en-TZ');
            const shortName = (n) => (String(n || 'Donor').trim().split(' ')[0] || 'Donor').substring(0, 12);
            const initials = (n) => {
                const parts = String(n || 'D').trim().split(' ').filter(Boolean);
                return (parts[0]?.[0] || 'D').toUpperCase();
            };

            let busy = false;
            let timer = null;

            async function poll() {
                if (busy) return;
                if (document.visibilityState === 'hidden') return;
                busy = true;
                const pill = document.getElementById('live-pill');
                if (pill) pill.textContent = 'Live…';

                try {
                    const res = await fetch('{{ route('admin.api.live') }}', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();

                    const paidCount = parseInt(data.paidCount || 0, 10) || 0;
                    const pendingCount = parseInt(data.pendingCount || 0, 10) || 0;
                    const paidToday = parseInt(data.paidTodayCount || 0, 10) || 0;
                    const totalRaised = parseInt(data.totalRaised || 0, 10) || 0;
                    const cur = String(data?.settings?.currency || 'TZS');

                    const elUsers = document.getElementById('kpi-total-users');
                    if (elUsers) elUsers.textContent = fmt(paidCount + pendingCount);

                    const elPays = document.getElementById('kpi-total-payments');
                    if (elPays) elPays.textContent = fmt(paidCount + pendingCount);

                    const elToday = document.getElementById('kpi-paid-today');
                    if (elToday) elToday.textContent = fmt(paidToday);

                    const elCash = document.getElementById('kpi-cash-flow');
                    if (elCash) elCash.textContent = cur + ' ' + fmt(totalRaised);

                    const wrap = document.getElementById('recent-users');
                    if (wrap) {
                        const items = Array.isArray(data.recentPaid) ? data.recentPaid.slice(0, 6) : [];
                        wrap.innerHTML = items.length ? items.map(t => {
                            const name = t.customer_name || 'Donor';
                            const paidAt = t.paid_at ? new Date(t.paid_at) : null;
                            const minsAgo = paidAt && !Number.isNaN(paidAt.getTime()) ? Math.max(1, Math.round((Date.now() - paidAt.getTime()) / 60000)) : 1;
                            return `
                                <div class="col-4">
                                    <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm border" style="width: 45px; height: 45px; font-weight: 800; font-size: 1.2rem;">
                                        ${initials(name)}
                                    </div>
                                    <div class="fw-bold text-truncate" style="font-size: 13px;">${shortName(name)}</div>
                                    <div class="text-muted" style="font-size: 11px;">since ${minsAgo}m</div>
                                </div>
                            `;
                        }).join('') : '<div class="col-12 text-muted py-3">No new users yet.</div>';
                    }

                    if (pill) pill.textContent = 'Live';
                } catch (e) {
                    if (pill) pill.textContent = 'Offline';
                } finally {
                    busy = false;
                }
            }

            function start() {
                if (timer) return;
                timer = setInterval(poll, 3000);
                poll();
            }

            function stop() {
                if (!timer) return;
                clearInterval(timer);
                timer = null;
            }

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'hidden') stop();
                else start();
            });

            start();
        });
    </script>
    @endpush
@endsection
