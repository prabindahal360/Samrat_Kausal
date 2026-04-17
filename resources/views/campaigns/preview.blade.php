@extends('adminlte::page')

@section('title', 'Campaign Preview')

@section('content_header')
    <h1>Campaign Preview</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">

            {{-- Customer Selector --}}
            <form method="GET" action="{{ route('campaigns.preview', $campaign) }}" class="mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <label>Select Customer for Preview</label>
                        <select name="customer_id" class="form-control" onchange="this.form.submit()">
                            @foreach($customers as $previewCustomer)
                                <option value="{{ $previewCustomer->id }}"
                                    {{ $customer->id == $previewCustomer->id ? 'selected' : '' }}>
                                    {{ trim(($previewCustomer->first_name ?? '') . ' ' . ($previewCustomer->last_name ?? '')) ?: 'Customer' }}
                                    - {{ $previewCustomer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Total Matching Customers</label>
                        <input type="text" class="form-control" value="{{ $customers->count() }}" readonly>
                    </div>
                </div>
            </form>

            {{-- Preview Customer --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <h5>Preview Customer</h5>
                    <p><strong>Name:</strong>
                        {{ trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) ?: 'Customer' }}
                    </p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Category:</strong> {{ $customer->customer_category ?? '-' }}</p>
                    <p><strong>Total Spend:</strong> ${{ number_format((float) ($customer->total_spend ?? 0), 2) }}</p>
                </div>
            </div>

            <hr>

            {{-- Subject --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <label>Email Subject</label>
                    <div class="border p-3 bg-light">
                        {{ $previewSubject }}
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <label>Email Body</label>
                    <div class="border p-3 bg-light">
                        {!! nl2br(e($previewBody)) !!}
                    </div>
                </div>
            </div>

            {{-- Campaign Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Campaign Name</label>
                    <input type="text" class="form-control" value="{{ $campaign->name }}" readonly>
                </div>

                <div class="col-md-3">
                    <label>Target Category</label>
                    <input type="text" class="form-control" value="{{ $campaign->target_category }}" readonly>
                </div>

                <div class="col-md-3">
                    <label>Discount</label>
                    <input type="text" class="form-control" value="{{ $campaign->discount_percentage }}%" readonly>
                </div>
            </div>

            {{-- Recommended Products --}}
            @if(!empty($recommendedProducts))
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Recommended Products</h5>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recommendedProducts as $product)
                                    <tr>
                                        <td>{{ $product['name'] ?? '-' }}</td>
                                        <td>{{ $product['category'] ?? '-' }}</td>
                                        <td>
                                            @isset($product['unit_price'])
                                                ${{ number_format((float) $product['unit_price'], 2) }}
                                            @endisset
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Purchase History --}}
@if($customer->invoiceItems->count())
    <div class="row mb-3">
        <div class="col-md-12">
            <h5>Purchase History</h5>

            <table class="table table-bordered table-striped">
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
                            <td>
                                ${{ number_format($item->quantity * $item->unit_price, 2) }}
                            </td>
                            <td>
                                {{ $item->invoice_date ? \Carbon\Carbon::parse($item->invoice_date)->format('Y-m-d') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-2">
                <strong>Total Spend:</strong>
                ${{ number_format($customer->invoiceItems->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}
            </div>
        </div>
    </div>
@endif

            {{-- Actions --}}
            <div class="mt-4">
                <form action="{{ route('campaigns.send', $campaign) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        Send Campaign
                    </button>
                </form>

                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-primary">
                    Edit Campaign
                </a>

                <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                    Back
                </a>
            </div>

        </div>
    </div>
@stop