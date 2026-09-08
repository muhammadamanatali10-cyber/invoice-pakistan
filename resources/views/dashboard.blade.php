@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .card-stat { border: none; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .icon-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .text-sm-custom { font-size: 13px; color: #6c757d; }
    .stat-number { font-size: 1.25rem; font-weight: 700; color: #111827; }
    .chart-container { position: relative; height: 320px; width: 100%; }
    .summary-title { font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 600; margin-bottom: 2px; }
    .summary-value { font-size: 15px; font-weight: 700; }
    .table-custom th { font-size: 11px; font-weight: 600; color: #6c757d; border-bottom-width: 1px; text-transform: uppercase; }
    .table-custom td { font-size: 13px; color: #374151; vertical-align: middle; }
    .badge-status { font-size: 10px; padding: 4px 8px; border-radius: 4px; font-weight: 600; text-transform: uppercase; }
    .btn-view-all { font-size: 11px; padding: 2px 10px; color: #374151; border-color: #d1d5db; }
</style>

<div class="container-fluid px-3 py-2">

    
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">Rs {{ number_format($totalAmountDue, 2) }}</div>
                        <div class="text-sm-custom">Amount Due</div>
                    </div>
                    <div class="icon-circle" style="background-color: #fee2e2; color: #ef4444;">
                        <i class="bi bi-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $totalCustomers }}</div>
                        <div class="text-sm-custom">Customers</div>
                    </div>
                    <div class="icon-circle" style="background-color: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $totalInvoices }}</div>
                        <div class="text-sm-custom">Invoices</div>
                    </div>
                    <div class="icon-circle" style="background-color: #dbeafe; color: #2563eb;">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number">{{ $totalEstimates }}</div>
                        <div class="text-sm-custom">Estimates</div>
                    </div>
                    <div class="icon-circle" style="background-color: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-file-earmark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <div class="row g-3 mb-4">
        <div class="col-md-10">
            <div class="card card-stat p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Sales & Expenses</h6>
                    <select class="form-select form-select-sm w-auto border-0 bg-light text-muted" style="font-size: 12px;">
                        <option>This year</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card card-stat p-3 bg-white h-100 d-flex flex-column justify-content-around text-end">
                <div>
                    <div class="summary-title">Sales</div>
                    <div class="summary-value text-dark">Rs {{ number_format($totalSales, 2) }}</div>
                </div>
                <hr class="my-1 border-light">
                <div>
                    <div class="summary-title">Receipts</div>
                    <div class="summary-value text-success">Rs {{ number_format($totalReceipts, 2) }}</div>
                </div>
                <hr class="my-1 border-light">
                <div>
                    <div class="summary-title">Expenses</div>
                    <div class="summary-value text-danger">Rs {{ number_format($totalExpenses, 2) }}</div>
                </div>
                <hr class="my-1 border-light">
                <div>
                    <div class="summary-title">Net Income</div>
                    <div class="summary-value text-primary">Rs {{ number_format($netIncome, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3">
        
        <div class="col-md-6">
            <div class="card card-stat p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Due Invoices</h6>
                    <a href="#" class="btn btn-outline-secondary btn-view-all">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>DUE ON</th>
                                <th>CUSTOMER</th>
                                <th>STATUS</th>
                                <th class="text-end">AMOUNT DUE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dueInvoices as $invoice)
                            <tr>
                                <td>{{ $invoice->due_date ?? '-' }}</td>
                                <td>{{ $invoice->customer->name ?? '-' }}</td>
                                <td><span class="badge badge-status bg-warning text-dark">{{ $invoice->status }}</span></td>
                                <td class="text-end fw-bold">Rs {{ number_format($invoice->amount_due, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted" style="font-size: 12px;">There are no matching rows</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       
        <div class="col-md-6">
            <div class="card card-stat p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Recent Estimates</h6>
                    <a href="#" class="btn btn-outline-secondary btn-view-all">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>CUSTOMER</th>
                                <th>STATUS</th>
                                <th class="text-end">AMOUNT DUE</th>
                                <th width="20"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEstimates as $estimate)
                            <tr>
                                <td>{{ $estimate->estimate_date ?? $estimate->created_at->format('d-m-Y') }}</td>
                                <td>{{ $estimate->customer->name ?? '-' }}</td>
                                <td><span class="badge badge-status bg-success-subtle text-success">{{ $estimate->status ?? 'ACCEPTED' }}</span></td>
                                <td class="text-end fw-bold">Rs {{ number_format($estimate->total_amount ?? $estimate->amount, 2) }}</td>
                                <td><i class="bi bi-three-dots text-muted"></i></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted" style="font-size: 12px;">There are no matching rows</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Sales',
                        data: {!! json_encode($salesData) !!},
                        borderColor: '#0f172a',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Receipts',
                        data: {!! json_encode($receiptsData) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Expenses',
                        data: {!! json_encode($expensesData) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { color: '#9ca3af', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endsection