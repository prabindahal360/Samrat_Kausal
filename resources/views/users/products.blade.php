@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <h1>Products</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $product->name }}</h5>
                        <p><strong>Category:</strong> {{ $product->category }}</p>
                        <p><strong>Price:</strong> ${{ number_format((float) $product->unit_price, 2) }}</p>
                        <p>{{ $product->description }}</p>

                        <form action="{{ route('user.products.buy', $product->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                            </div>
                            <button type="submit" class="btn btn-success mt-2">Buy Now</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
@stop