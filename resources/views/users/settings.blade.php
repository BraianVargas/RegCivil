@extends('adminlte::page')

@section('title', 'Cambiar contraseña')

@section('content_header', 'Cambiar contraseña')

@section('content')
@include('alerts.success')
@include('alerts.errors')

<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4"></div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Modificar contraseña</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="{{ route('users.change') }}">
                {{ csrf_field() }}
                <div class="card-body">

                    <div class="form-group {{ $errors->has('current-password') ? ' has-error' : '' }}">
                        <label for="new-password" class="col-md-12 control-label">Contraseña actual:</label>

                        <div class="col-md-12">
                            <input id="current-password" type="password" class="form-control" name="current-password"
                                required>

                            @if ($errors->has('current-password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('current-password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('new-password') ? ' has-error' : '' }}">
                        <label for="new-password" class="col-md-12 control-label">Nueva contraseña:</label>

                        <div class="col-md-12">
                            <input id="new-password" type="password" class="form-control" name="new-password" required>

                            @if ($errors->has('new-password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('new-password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new-password-confirm" class="col-md-12 control-label">Confirmar nueva
                            contraseña:</label>

                        <div class="col-md-12">
                            <input id="new-password-confirm" type="password" class="form-control"
                                name="new-password-confirm" required>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-info">
                        <span class="fa fa-unlock-alt"></span> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
