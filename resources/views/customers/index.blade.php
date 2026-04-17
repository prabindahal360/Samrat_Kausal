@extends('adminlte::page')

@section('title', 'Customers')
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Customers</h1>
        <div>
            <a href="{{ route('customers.import.form') }}" class="btn btn-info">Import</a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
        </div>
    </div>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body table-responsive">
            <table id="customersTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>Total Spend</th>
                        <th>Category</th>
                        <th>Discount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td></td>
                            <td>{{ $customer->customer_id }}</td>
                            <td>{{ trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->country }}</td>
                            <td>${{ number_format((float) $customer->total_spend, 2) }}</td>
                            <td>
                                @php
                                    $badgeClass = match($customer->customer_category) {
                                        'VIP' => 'bg-danger',
                                        'High Value' => 'bg-warning',
                                        'Medium Value' => 'bg-info',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $customer->customer_category ?: 'Low Value' }}
                                </span>
                            </td>
                            <td>{{ $customer->discount_percentage }}%</td>
                            <td>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this customer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function () {
    $('#customersTable').DataTable({
        pageLength: 25,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            {
                targets: 0,
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                targets: -1,
                searchable: false,
                orderable: false
            }
        ]
    });
});
</script>
@stop