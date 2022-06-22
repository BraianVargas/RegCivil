@extends('adminlte::page')

@section('title', 'Listado de turnos')

@section('content_header', 'Listado de turnos')

@section('content')
<search-form v-bind:delegations="{{ $delegations }}">
</search-form>
@endsection
