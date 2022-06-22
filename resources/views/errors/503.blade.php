@extends('adminlte::page')

@section('title', trans('adminlte::adminlte.serviceunavailable') )

@section('content_header', trans('adminlte::adminlte.serviceunavailable') )

@section('content')
<div class="error-page">
    <h2 class="headline text-yellow"> 503</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> Oops! {{ trans('adminlte::adminlte.503error') }}.</h3>
        <p>
            {{ trans('adminlte::adminlte.serviceunavailable') }}
        </p>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection