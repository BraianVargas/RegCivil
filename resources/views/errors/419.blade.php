@extends('adminlte::page')

@section('title', trans('adminlte::adminlte.pagexpired'))

@section('content_header', trans('adminlte::adminlte.pagexpired'))

@section('content')
<div class="error-page">
    <h2 class="headline text-danger"> 419</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-danger"></i> Oops! {{ trans('adminlte::adminlte.pagexpired') }}.</h3>
        <p>
            {{ trans('adminlte::adminlte.pagexpiredd') }}
        </p>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection
