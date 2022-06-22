@extends('adminlte::page')

@section('title', 'Alta de Agenda')

@section('content_header', 'Alta de Agenda')

@section('content')
@include('alerts.success')
@include('alerts.errors')

<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4"></div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Generar turnos: </h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="GenerateForm" name="GenerateForm" class="form-horizontal">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="card-body">
                    <div class="row">
                        <div class='col-md-2'>
                            <label for="delegation_id">Delegación</label>
                            <select id="delegation_id" name="delegation_id"
                                class="form-control input-sm form-control-sm">
                                @foreach($delegations as $key => $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-2'>
                            <label for="number_turns">Cantidad de turnos</label>
                            <input type="text" class="form-control form-control-sm" id="number_turns"
                                name="number_turns" placeholder="Cantidad" value="1">
                        </div>
                        <div class='col-md-2'>
                            <label for="duration">Duración del turno</label>
                            <select id="duration" name="duration" class="form-control input-sm form-control-sm">
                                @foreach($durations as $duration)
                                <option value="{{ $duration }}">
                                    {{ $duration }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class='col-md-2'>
                            <label for="duration">Rango de fecha</label>
                            <input type="text" class="form-control input-sm form-control-sm" id="dates" name="dates"
                                value="" />
                        </div>
                        <div class='col-md-2'>
                            <label for="duration">Rango horario</label>
                            <input type="text" class="form-control input-sm form-control-sm" id="time_start"
                                name="time_start" value="07:00" />
                        </div>
                        <div class='col-md-2'>
                            <label for="duration">
                                <font color="white">.</font>
                            </label>
                            <input type="text" class="form-control input-sm form-control-sm" id="time_end"
                                name="time_end" value="19:00" />
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-4 mt-100'><br><label id="daily"></label>
                        </div>
                        <div class='col-md-4 mt-100'><br><label id="total"></label>
                        </div>
                        <div class='col-md-4 mt-100 text-right'><br>
                            <button id="generateBtn" type="submit" class="btn btn-info btn-sm"><i
                                    class="fas fa-calendar" aria-hidden="true"></i> Generar</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4"></div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div id="results-table">
        </div>
    </div>
    @endsection
    @section('css')
    <style type="text/css">
        .error {
            color: red;
            font-size: 12px;
        }
    </style>
    @endsection
    @section('js')
    <script>
        $(function () {
    var url = "turns";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    calculateAmountTurns();

    // Validacion de formulario
    $("#GenerateForm").validate({
        ignore: "not:hidden",
        errorClass: "error",
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        rules: {
            number_turns: { required: true, digits: true, min: 1 },
            time_start: { required: true, time24: true},
            time_end: { required: true, time24: true}
        },
        messages: {
            number_turns: { required: "Requerido", digits: "Debe ser entero", min: "Debe ser mayor que 0" },
            time_start: {required: "Requerido", time24: "Debe ser formato HH:mm"},
            time_end: {required: "Requerido", time24: "Debe ser formato HH:mm"}
        }
    });

    $('input[name="dates"]').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY',
            cancelLabel: 'Limpiar',
            applyLabel: 'Aplicar'
        }
    });

    $('#time_start').timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        minTime: '7',
        maxTime: '6:00pm',
        defaultTime: '7',
        startTime: '7:00am',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        change: function(time) {
            calculateAmountTurns();
        }
    });

    $('#time_end').timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        minTime: '8',
        maxTime: '7:00pm',
        defaultTime: '19',
        startTime: '8:00am',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        change: function(time) {
            calculateAmountTurns();
        }
    });

    $('#number_turns').change(function(){
      calculateAmountTurns();
    });

    $('#duration').change(function(){
      calculateAmountTurns();
    });

    $('#dates').on('apply.daterangepicker', function(ev, picker) {
        calculateAmountTurns();
    });

    $('#generateBtn').click(function (e) {
        e.preventDefault();

        calculateAmountTurns();
        if ($("#GenerateForm").valid()) {
            $(this).prop("disabled", true);
            // add spinner to button
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Generando...`
            );

            $.ajax({
                data: $('#GenerateForm').serialize(),
                url: 'turns',
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    toastr.success('Turnos generados exitosamente!');
                },
                error: function (data) {
                    var errors = data.responseJSON;

                    toastr.error(errors.message,'Error');
                }
            });
            $('#generateBtn').prop("disabled", false);
            // add spinner to button
            $('#generateBtn').html(
                `<i class="fas fa-calendar" aria-hidden="true"></i> Generar`
            );
        }

    });

});

function calculateAmountTurns(){
    var number_turns = $('#number_turns').val();
    var duration = $('#duration').val();
    var minTime = $('#time_start').val();
    var maxTime = $('#time_end').val();
    var dates = $('input[name="dates"]').val();

    var d = new Date();
    var ddd = "0"+ d.getDate();
    day = ddd.substring(ddd.length-2,ddd.length);
    var fromDaily = new Date(d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ day + " "+minTime);
    var toDaily = new Date(d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ day + " "+maxTime);
    var diffDaily = Math.abs(toDaily - fromDaily);

    var daily = (diffDaily/1000/60/duration)*number_turns+1;
    var total = daily;

    if (dates) {
        var res = dates.split(" - ");
        var fromDate = res[0].split("-");
        var toDate = res[1].split("-");

        var f = new Date(fromDate[2]+"-"+fromDate[1]+"-"+fromDate[0] + " "+minTime);
        var t = new Date(toDate[2]+"-"+toDate[1]+"-"+toDate[0] + " "+maxTime);

        var diff = Math.abs(t - f);

        total = (diff/1000/60/duration)*number_turns+1;
    }

    $("#daily").html("<b>Cantidad de turnos diarios: </b>"+parseInt(daily));
    $("#total").html("<b>Cantidad de turnos totales: </b>"+parseInt(total));
}

$.validator.addMethod("time24", function(value, element) {
    return /^([01]?[0-9]|2[0-3])(:[0-5][0-9])$/.test(value);
}, "Invalid time format.");

    </script>
    @endsection
