@extends('adminlte::page')

@section('title', 'Customers')
@section('plugins.Datatables', true)

@section('content_header')
    <h1>Customers</h1>
@stop

@section('css')
<style>
    table.dataTable {
        width: 100% !important;
    }

    #customersTable th,
    #customersTable td {
        vertical-align: middle;
    }

    #customersTable td:last-child {
        white-space: nowrap;
    }

    #customersTable input[type="text"] {
        min-width: 100px;
    }
</style>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.sendBulkEmail') }}" method="POST" id="bulkEmailForm">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Select Campaign</strong></label>
                        <select name="campaign_id" id="campaignSelect" class="form-control">
                            <option value="">Auto Select by Customer Category</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}"
                                        data-category="{{ $campaign->target_category }}">
                                    {{ $campaign->name }} ({{ $campaign->target_category }} - {{ $campaign->discount_percentage }}%)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Leave blank to automatically match each customer’s category with a campaign.
                        </small>
                    </div>

                    <div class="col-md-8 d-flex align-items-end">
                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm('Send email to selected customers?')">
                            Send to Selected Customers
                        </button>

                        <button type="button" id="toggleFilters" class="btn btn-info ml-2">
                            Toggle Filters
                        </button>

                        <button type="button" id="clearCampaignFilter" class="btn btn-secondary ml-2">
                            Clear Campaign Filter
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="customersTable" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>SN</th>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Total Spend</th>
                                <th>Category</th>
                                <th>Discount</th>
                                <th>Suggested Campaign</th>
                                <th>Action</th>
                            </tr>

                            <tr id="filterRow" style="display:none;">
                                <th></th>
                                <th></th>
                                <th>
                                    <input type="text" class="form-control form-control-sm" placeholder="Customer ID">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm" placeholder="Name">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm" placeholder="Email">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm" placeholder="Country">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm" placeholder="Spend">
                                </th>
                                <th>
                                    <input type="text" id="categoryFilterInput" class="form-control form-control-sm" placeholder="Category">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm" placeholder="Discount">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm" placeholder="Campaign">
                                </th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($customers as $customer)
                                @php
                                    $matchedCampaign = $campaigns->firstWhere('target_category', $customer->customer_category);
                                @endphp

                                <tr>
                                    <td>
                                        <input type="checkbox"
                                               name="customer_ids[]"
                                               value="{{ $customer->id }}"
                                               class="customer-checkbox">
                                    </td>
                                    <td></td>
                                    <td>{{ $customer->customer_id }}</td>
                                    <td>{{ $customer->full_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->country }}</td>
                                    <td>${{ number_format($customer->total_spend, 2) }}</td>
                                    <td>{{ $customer->customer_category }}</td>
                                    <td>{{ $customer->discount_percentage }}%</td>
                                    <td>{{ $matchedCampaign?->name ?? 'No auto campaign found' }}</td>
                                    <td>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                           class="btn btn-warning btn-sm mb-1">
                                            Edit
                                        </a>

                                        <form action="{{ route('customers.sendEmail', $customer->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Send recommendation email to this customer?')">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm mb-1">
                                                Send Email
                                            </button>
                                        </form>

                                        <form action="{{ route('customers.destroy', $customer->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mb-1">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function () {
    let table = $('#customersTable').DataTable({
        pageLength: 25,
        order: [[6, 'desc']],
        autoWidth: false,
        responsive: true,
        columnDefs: [
            {
                targets: 0,
                searchable: false,
                orderable: false
            },
            {
                targets: 1,
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                targets: -1,
                orderable: false,
                searchable: false
            }
        ]
    });

    table.columns.adjust().draw();

    $('#toggleFilters').on('click', function () {
        $('#filterRow').toggle();
        table.columns.adjust().draw();
    });

    // Column filters
    $('#customersTable thead tr:eq(1) th').each(function (i) {
        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table.column(i).search(this.value).draw();
            }
        });
    });

    // Select all
    $('#selectAll').on('click', function () {
        $('.customer-checkbox').prop('checked', this.checked);
    });

    // Auto filter by selected campaign category
    $('#campaignSelect').on('change', function () {
        let selectedOption = $(this).find(':selected');
        let category = selectedOption.data('category') || '';

        if ($('#filterRow').is(':hidden')) {
            $('#filterRow').show();
        }

        // Category column index = 7
        table.column(7).search(category).draw();
        $('#categoryFilterInput').val(category);

        table.columns.adjust().draw();
    });

    // Clear campaign filter
    $('#clearCampaignFilter').on('click', function () {
        $('#campaignSelect').val('');
        $('#categoryFilterInput').val('');
        table.column(7).search('').draw();
        table.columns.adjust().draw();
    });
});
</script>
@stop