@extends('adminlte::page')

@section('title', 'Campaigns')
@section('plugins.Datatables', true)

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Campaigns</h1>
        <a href="{{ route('campaigns.create') }}" class="btn btn-primary">
            Create Campaign
        </a>
    </div>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body table-responsive">
            <table id="campaignsTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Target Category</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                        <tr>
                            <td></td>
                            <td>{{ $campaign->name }}</td>
                            <td>{{ $campaign->target_category }}</td>
                            <td>{{ $campaign->discount_percentage }}%</td>
                            <td>
                                @if($campaign->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                {{ $campaign->sent_at ? $campaign->sent_at->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="{{ route('campaigns.preview', $campaign->id) }}" class="btn btn-info btn-sm">
                                    Preview
                                </a>

                                <a href="{{ route('campaigns.logs', $campaign->id) }}" class="btn btn-secondary btn-sm">
                                    Logs
                                </a>

                                <form action="{{ route('campaigns.send', $campaign->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Send this campaign now?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Send
                                    </button>
                                </form>

                                <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this campaign?')">
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

@section('css')
<style>
    table.dataTable {
        width: 100% !important;
    }

    #campaignsTable td:last-child {
        white-space: nowrap;
    }

    #campaignsTable .btn {
        margin-right: 4px;
        margin-bottom: 4px;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function () {
    $('#campaignsTable').DataTable({
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