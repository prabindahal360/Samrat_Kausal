@extends('adminlte::page')

@section('title', 'Campaign Logs')

@section('content_header')
    <h1>Campaign Logs</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-header">
            <strong>{{ $campaign->title }}</strong>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Error</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->email }}</td>
                            <td>
                                <span class="badge bg-{{ $log->delivery_status === 'sent' ? 'success' : 'danger' }}">
                                    {{ ucfirst($log->delivery_status) }}
                                </span>
                            </td>
                            <td>{{ $log->sent_at }}</td>
                            <td>{{ $log->error_message }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@stop