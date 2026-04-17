@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
    <h1>Edit Customer</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <label>Customer ID</label>
                        <input type="text" name="customer_id" class="form-control" value="{{ old('customer_id', $customer->customer_id) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $customer->country) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Total Spend</label>
                        <input type="text" class="form-control" value="{{ number_format((float) $customer->total_spend, 2) }}" readonly>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Customer Category</label>
                        <input type="text" class="form-control" value="{{ $customer->customer_category }}" readonly>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Discount Percentage</label>
                        <input type="text" class="form-control" value="{{ $customer->discount_percentage }}%" readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Update Customer</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-4">Back</a>
            </form>
        </div>
    </div>
@stop