@extends('adminlte::page')

@section('title', trans('adminlte::adminlte.pagenotaccess'))

@section('content_header', trans('adminlte::adminlte.pagenotaccess'))

@section('content')
<div class="error-page">
    <h2 class="headline text-danger"> 403</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-danger"></i> Oops! {{ trans('adminlte::adminlte.403error') }}.</h3>
        <p>
            {{ trans('adminlte::adminlte.notaccesspage') }}
        </p>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection
