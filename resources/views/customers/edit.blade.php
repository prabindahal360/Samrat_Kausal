@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Edit Customer</h1>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Customer ID -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer ID</label>
                            <input type="text"
                                   name="customer_id"
                                   value="{{ old('customer_id', $customer->customer_id) }}"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $customer->email) }}"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    <!-- First Name -->
                    <div class="col-md-6 mt-3">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text"
                                   name="first_name"
                                   value="{{ old('first_name', $customer->first_name) }}"
                                   class="form-control">
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6 mt-3">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text"
                                   name="last_name"
                                   value="{{ old('last_name', $customer->last_name) }}"
                                   class="form-control">
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="col-md-6 mt-3">
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text"
                                   name="country"
                                   value="{{ old('country', $customer->country) }}"
                                   class="form-control">
                        </div>
                    </div>

                </div>

                <!-- 🔥 AI INFO (Read-only) -->
                <hr>

                <h5>Customer Insights</h5>

                <div class="row">

                    <div class="col-md-4 mt-2">
                        <label>Total Spend</label>
                        <input type="text"
                               class="form-control"
                               value="${{ number_format($customer->total_spend, 2) }}"
                               readonly>
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Category</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $customer->customer_category }}"
                               readonly>
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Discount</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $customer->discount_percentage }}%"
                               readonly>
                    </div>

                </div>

                <!-- 🔥 Recommended Products -->
@if(!empty($customer->recommended_products))
    <div class="mt-4">
        <label>Recommended Products</label>
        <ul class="mb-0">
            @foreach($customer->recommended_products as $product)
                <li>
                    <strong>{{ $product['name'] ?? '-' }}</strong>
                    @if(!empty($product['category']))
                        — {{ $product['category'] }}
                    @endif
                    @if(isset($product['unit_price']))
                        — ${{ number_format($product['unit_price'], 2) }}
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

                <!-- Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        Update Customer
                    </button>

                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
@stop