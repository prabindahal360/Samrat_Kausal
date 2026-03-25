@extends('adminlte::page')

@section('title', 'Edit Campaign')

@section('content_header')
    <h1>Edit Campaign</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('campaigns.update', $campaign->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <label>Campaign Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $campaign->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>Target Category</label>
                        <select name="target_category" class="form-control" required>
                            <option value="Low Value" {{ old('target_category', $campaign->target_category) == 'Low Value' ? 'selected' : '' }}>Low Value</option>
                            <option value="Medium Value" {{ old('target_category', $campaign->target_category) == 'Medium Value' ? 'selected' : '' }}>Medium Value</option>
                            <option value="High Value" {{ old('target_category', $campaign->target_category) == 'High Value' ? 'selected' : '' }}>High Value</option>
                            <option value="VIP" {{ old('target_category', $campaign->target_category) == 'VIP' ? 'selected' : '' }}>VIP</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Discount Percentage</label>
                        <input type="number" name="discount_percentage" class="form-control"
                               value="{{ old('discount_percentage', $campaign->discount_percentage) }}" min="0" max="100" required>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label>Email Subject</label>
                        <input type="text" name="email_subject" class="form-control"
                               value="{{ old('email_subject', $campaign->email_subject) }}" required>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label>Email Body</label>
                        <textarea name="email_body" class="form-control" rows="8" required>{{ old('email_body', $campaign->email_body) }}</textarea>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                   {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-4">Update Campaign</button>
            </form>
        </div>
    </div>
@stop