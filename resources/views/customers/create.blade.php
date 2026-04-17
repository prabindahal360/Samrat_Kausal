@extends('adminlte::page')

@section('title', 'Create Customer')

@section('content_header')
    <h1>Create Customer</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <label>Customer ID</label>
                        <input type="text" name="customer_id" class="form-control" value="{{ old('customer_id') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-4">Save Customer</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-4">Back</a>
            </form>
        </div>
    </div>
@stop