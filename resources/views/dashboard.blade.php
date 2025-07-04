@extends('layout.index')
@section('title', 'dashboard')
@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endseciton


@section('content')
<h1 class="mt-1">Dashboard</h1>
<!-- Default box -->
<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Title</h3>

        <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
        </button>
        </div>
    </div>
    <div class="card-body">
        Start creating your amazing application!
    </div>
    <div class="card-footer">
        Footer
    </div>
</div>
<!-- /.card -->
@endsection