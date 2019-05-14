@extends('layouts.panel')

@section('styles')
<link href="{{ asset('admin-lte/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="app">
    <admin-laporan-spareparts-terlaris></admin-laporan-spareparts-terlaris>
</div>
@endsection

@section('scripts')
@endsection

@section('page-script')
@endsection