@extends('adminlte::page')

@section('title', 'Invoice Items')

@section('content_header')
    <h1>Invoice Items</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Stock Code</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Invoice Date</th>
                        <th>Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoiceItems as $item)
                        <tr>
                            <td>{{ $item->invoice_no }}</td>
                            <td>{{ $item->customer?->full_name }}</td>
                            <td>{{ $item->stock_code }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->invoice_date }}</td>
                            <td>{{ $item->unit_price }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No invoice items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $invoiceItems->links() }}
            </div>
        </div>
    </div>
@stop