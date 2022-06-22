@extends('adminlte::page')

@section('title', trans('adminlte::adminlte.pagenotfound'))

@section('content_header', trans('adminlte::adminlte.pagenotfound'))

@section('content')
<div class="error-page">
    <h2 class="headline text-danger"> 404</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-danger"></i> Oops! {{ trans('adminlte::adminlte.pagenotfound') }}.</h3>
        <p>
            {{ trans('adminlte::adminlte.notfindpage') }}
            {{ trans('adminlte::adminlte.mainwhile') }} <a href='{{ url('/home') }}'>{{ trans('adminlte::adminlte.returndashboard') }}</a> {{ trans('adminlte::adminlte.usingsearch') }}
        </p>
        <form class='search-form'>
            <div class='input-group'>
                <input type="text" name="search" class='form-control' placeholder="{{ trans('adminlte::adminlte.search') }}"/>
                <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i></button>
                </div>
            </div><!-- /.input-group -->
        </form>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection
