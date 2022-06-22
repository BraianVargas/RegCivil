@extends('adminlte::page')

@section('title', 'Registrar turno')

@section('content_header', 'Registrar turno')

@section('content')
<tab-form v-bind:types_procedures="{{ $types_procedures }}"
    v-bind:types_documents="{{ $types_documents }}" v-bind:types_proceedings="{{ $types_proceedings }}" v-bind:delegations="{{ $delegations }}" v-bind:offices="{{ $offices }}">
</tab-form>
@endsection
