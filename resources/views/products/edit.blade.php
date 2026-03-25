@extends('adminlte::page')

@section('title', 'Edit Product')

@section('content_header')
    <h1>Edit Product</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <label>Stock Code</label>
                        <input type="text" name="stock_code" class="form-control"
                               value="{{ old('stock_code', $product->stock_code) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Unit Price</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control"
                               value="{{ old('unit_price', $product->unit_price) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control"
                               value="{{ old('category', $product->category) }}">
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-4">Update Product</button>
            </form>
        </div>
    </div>
@stop