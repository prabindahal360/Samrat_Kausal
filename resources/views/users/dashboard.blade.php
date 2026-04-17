@extends('adminlte::page')

@section('title', 'User Dashboard')

@section('content_header')
    <h1>My Dashboard</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>${{ number_format((float) $customer->total_spend, 2) }}</h3>
                    <p>Total Spend</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $customer->customer_category ?? 'Low Value' }}</h3>
                    <p>Customer Category</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $customer->discount_percentage }}%</h3>
                    <p>Discount</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $customer->invoiceItems->count() }}</h3>
                    <p>Purchases</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recommended Products</h3>
        </div>
        <div class="card-body">
            @if(!empty($customer->recommended_products))
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer->recommended_products as $product)
                            <tr>
                                <td>{{ $product['name'] ?? '-' }}</td>
                                <td>{{ $product['category'] ?? '-' }}</td>
                                <td>${{ number_format((float) ($product['unit_price'] ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No recommended products available.</p>
            @endif
        </div>
    </div>
@stop