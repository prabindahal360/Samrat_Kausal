@extends('adminlte::page')

@section('title', 'Add Customer')

@section('content_header')
    <h1>Add Customer</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Customer ID</label>
                    <input type="text" name="customer_id" value="{{ old('customer_id') }}" class="form-control">
                </div>

                <div class="form-group mt-3">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                </div>

                <div class="form-group mt-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                </div>

                <div class="form-group mt-3">
                    <label>Country</label>
                    <input type="text" name="country" value="{{ old('country') }}" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    Save Customer
                </button>
            </form>
        </div>
    </div>
@stop