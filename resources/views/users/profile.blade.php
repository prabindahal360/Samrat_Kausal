@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    <h1>My Profile</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Country:</strong> {{ $customer->country }}</p>
            <p><strong>Total Spend:</strong> ${{ number_format((float) $customer->total_spend, 2) }}</p>
            <p><strong>Category:</strong> {{ $customer->customer_category }}</p>
            <p><strong>Discount:</strong> {{ $customer->discount_percentage }}%</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Purchase History
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->invoiceItems as $item)
                        <tr>
                            <td>{{ $item->invoice_no }}</td>
                            <td>{{ optional($item->product)->name ?? '-' }}</td>
                            <td>{{ optional($item->product)->category ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format((float) $item->unit_price, 2) }}</td>
                            <td>${{ number_format((float) ($item->quantity * $item->unit_price), 2) }}</td>
                            <td>{{ $item->invoice_date ? \Carbon\Carbon::parse($item->invoice_date)->format('Y-m-d') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop