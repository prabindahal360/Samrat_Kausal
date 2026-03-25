@extends('adminlte::page')

@section('title', 'Import Customers')

@section('content_header')
    <h1>Import Dataset</h1>
@stop

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">

            <a href="{{ route('customers.download.format') }}" class="btn btn-info mb-3">
                Download Sample Format
            </a>

            <!-- 🔥 ADD HERE -->
            <div class="alert alert-primary">
                <strong>Upload Instructions:</strong><br>
                Please upload a CSV, XLS, or XLSX file with these exact column headers:<br><br>

                <code>
                    InvoiceNo,FirstName,LastName,Email,StockCode,Description,Quantity,InvoiceDate,UnitPrice,CustomerID,Country
                </code>
                <br><br>
                <strong>Example Date format:</strong> 12/1/2010 8:26
            </div>

            <!-- FORM -->
            <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Select Dataset File</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success mt-3">
                    Upload Dataset
                </button>
            </form>

        </div>
    </div>
@stop
