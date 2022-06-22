@extends('adminlte::page')

@section('title', trans('adminlte::adminlte.toomanyrequests'))

@section('content_header', trans('adminlte::adminlte.toomanyrequests'))

@section('content')
<div class="error-page">
    <h2 class="headline text-danger"> 429</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-danger"></i> Oops! {{ trans('adminlte::adminlte.toomanyrequests') }}.</h3>
        <p>
            {{ trans('adminlte::adminlte.toomanyrequestsd') }}
        </p>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection
