<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .title { color: #2b59c3; font-weight: bold; font-size: 16px; }
        .subtitle { font-size: 11px; text-transform: uppercase; color: #555; font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { padding: 8px; text-align: left; }
        .total-box { background: #f4f7ff; padding: 10px; margin-top: 20px; border-radius: 4px; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

    @php
        $companyName = \App\Models\CompanySetting::where('user_id', auth()->id())->value('company_name') ?? 'Sublime Logics';
    @endphp

    <table width="100%">
        <tr>
            <td class="title">{{ $companyName }}</td>
            <td class="text-right" style="color: #666; font-size: 10px;">{{ $fromDate }} - {{ $toDate }}</td>
        </tr>
    </table>

    <div class="subtitle" style="margin-top: 10px;">
        @if($tab == 'sales') SALES REPORT: BY CUSTOMER
        @elseif($tab == 'profit_loss') PROFIT & LOSS REPORT
        @elseif($tab == 'expenses') EXPENSES REPORT
        @elseif($tab == 'taxes') TAX REPORT
        @endif
    </div>

    @if($tab == 'sales')
        @php $grandTotal = 0; @endphp
        @foreach($data as $customerId => $invoices)
            <p class="fw-bold" style="margin-top: 15px;">{{ $invoices->first()->customer->name ?? 'Customer' }}</p>
            <table class="table">
                @foreach($invoices as $inv)
                @php $grandTotal += $inv->total_amount; @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d-m-Y') }} ({{ $inv->invoice_number }})</td>
                    <td class="text-right">Rs {{ number_format($inv->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </table>
        @endforeach
        <div class="total-box">
            <table width="100%">
                <tr class="fw-bold" style="color: #2b59c3;">
                    <td>TOTAL SALES</td>
                    <td class="text-right">Rs {{ number_format($grandTotal, 2) }}</td>
                </tr>
            </table>
        </div>

    @elseif($tab == 'profit_loss')
        <table class="table" style="margin-top: 20px;">
            <tr>
                <td class="fw-bold">Income</td>
                <td class="text-right fw-bold">Rs {{ number_format($data['income'], 2) }}</td>
            </tr>
            <tr>
                <td class="fw-bold" style="padding-top: 15px;">Expenses</td>
                <td class="text-right"></td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">Operating Costs</td>
                <td class="text-right">Rs {{ number_format($data['expenses'], 2) }}</td>
            </tr>
        </table>
        <div class="total-box">
            <table width="100%">
                <tr class="fw-bold" style="color: #2b59c3;">
                    <td>NET PROFIT</td>
                    <td class="text-right">Rs {{ number_format($data['net_profit'], 2) }}</td>
                </tr>
            </table>
        </div>

    @elseif($tab == 'expenses')
        @php $totalExpense = 0; @endphp
        <table class="table">
            @foreach($data as $categoryId => $expenses)
                @foreach($expenses as $expense)
                @php $totalExpense += $expense->amount; @endphp
                <tr>
                    <td>{{ $expense->category->name ?? 'General' }}</td>
                    <td class="text-right">Rs {{ number_format($expense->amount, 2) }}</td>
                </tr>
                @endforeach
            @endforeach
        </table>
        <div class="total-box">
            <table width="100%">
                <tr class="fw-bold" style="color: #2b59c3;">
                    <td>TOTAL EXPENSE</td>
                    <td class="text-right">Rs {{ number_format($totalExpense, 2) }}</td>
                </tr>
            </table>
        </div>

    @elseif($tab == 'taxes')
        <table class="table" style="margin-top: 20px;">
            <tr>
                <td>Tax Types</td>
                <td class="text-right">Rs {{ number_format($data['total_tax'], 2) }}</td>
            </tr>
        </table>
        <div class="total-box">
            <table width="100%">
                <tr class="fw-bold" style="color: #2b59c3;">
                    <td>TOTAL TAX</td>
                    <td class="text-right">Rs {{ number_format($data['total_tax'], 2) }}</td>
                </tr>
            </table>
        </div>
    @endif

</body>
</html>