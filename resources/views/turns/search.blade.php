@extends('adminlte::page')

@section('title', 'Baja de Agenda')

@section('content_header', 'Baja de Agenda')

@section('content')
@include('alerts.success')
@include('alerts.errors')

<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4"></div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Eliminar turnos: </h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="DeleteForm" name="DeleteForm" class="form-horizontal">
                <div class="card-body">
                    <div class="row">
                        <div class='col-md-3'>
                            <label for="delegation_id">Delegación</label>
                            <select id="delegation_id" name="delegation_id"
                                class="form-control input-sm form-control-sm">
                                @foreach($delegations as $key => $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date">Fecha</label>
                            <input type="text" class="form-control input-sm form-control-sm datepicker" id="date"
                                name="date" value="" />
                        </div>
                        <div class='col-md-3 mt-100 text-center'><br>
                            <button id="clearBtn" type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-broom"
                                    aria-hidden="true"></i> Limpiar</button>
                            <button id="deleteBtn" type="submit" class="btn btn-info btn-sm"><i class="fas fa-trash"
                                    aria-hidden="true"></i> Eliminar</button>
                        </div>
                        <div id="delegation_info" class='col-md-4'><br>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4"></div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div id="results-table" style="display:none">
            <table id="example" class="table table-bordered table-striped table-sm table-hover data-table dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nro. documento</th>
                        <th>Edad</th>
                        <th>Telefono</th>
                        <th>Domicilio</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('css')
<style type="text/css">
    .error {
        color: red;
        font-size: 12px;
    }

    .table {
        background: #ffffff;
    }
</style>
@endsection
@section('js')
<script>
$(function () {
    $('#results-table').hide();
    var default_delegation = $("#delegation_id").prop("selectedIndex", 0).val();
    loadDelegation(default_delegation);

    $('#delegation_id').change(function(){
        var delegation_id = $(this).val();

        loadDelegation(delegation_id);
    });

    $('#date').datepicker().datepicker('setDate', 'today');
    $('#date').datepicker({
        autoclose: true,
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Validacion de formulario
    $("#DeleteForm").validate({
        ignore: "not:hidden",
        errorClass: "error",
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        rules: {
            date: { required: true, dateFormat: true },
            },
        messages: {
            date: { required: "Requerido" },
            }
    });
});

$(document).ready(function(){
    var buttonCommon = {
        exportOptions: {
            columns: [ 0, 1, 2, 3, 4 ]
        }
    };

    $('#clearBtn').click(function (e) {
        e.preventDefault();

        $('#results-table').hide();
    });

    $('#deleteBtn').click(function (e) {
        e.preventDefault();

        if ($("#DeleteForm").valid()) {
            $(this).prop("disabled", true);
            // add spinner to button
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Eliminando...`
            );

            results = [];

            $.ajax({
                data: $('#DeleteForm').serialize(),
                url: "/turns/cancel",
                type: "POST",
                success: function (data) {
                    toastr.success('Turnos eliminados exitosamente!');
                    $('#results-table').show();
                    results = data.data;

                    $('#example').DataTable( {
                        bDestroy: true,
                        language: {
                            "url": "/js/Spanish.json",
                            buttons: {
                                pageLength: {
                                    _: '<i class="fa fa-file-text-o"> Ver %d filas</i>',
                                    '-1': '<i class="fa fa-file-text-o"> Ver todas</i>'
                                }
                            }
                        },
                        pageLength: 10,
                        order: [[1, 'desc']],
                        lengthMenu: [
                            [ 5, 10, 25, 50, 100, -1 ],
                            [ '5', '10', '25', '50', '100', 'Todas' ]
                        ],
                        data: results,
                        columns: [
                            { "data": "name" },
                            { "data": "document_number" },
                            { "data": "age" },
                            { "data": "phone" },
                            { "data": "address" },
                        ],
                        dom: 'Bfrtip',
                        buttons: [
                            'pageLength',
                            $.extend( true, {}, buttonCommon, {
                                extend: 'copyHtml5',
                                text: '<i class="fa fa-files-o"> Copiar</i>',
                                titleAttr: 'Copiar'
                            } ),
                            $.extend( true, {}, buttonCommon, {
                                extend: 'excelHtml5',
                                text: '<i class="fa fa-file-excel-o"> Excel</i>',
                                titleAttr: 'Excel'
                            } ),
                            $.extend( true, {}, buttonCommon, {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o"> PDF</i>',
                                titleAttr: 'PDF',
                                customize: function(doc) {
                                    doc.content.forEach(function(item) {
                                        if (item.table) {
                                            // Asignar el ancho para las columnas
                                            item.table.widths = [230, 70, 50, 50, 120] ;
                                        }
                                    });
                                    // [left, top, right, bottom]
                                    doc.pageMargins = [ 20, 20, 20, 20 ];
                                    doc.defaultStyle =
                                    {
                                        fontSize: 6,
                                    };
                                    doc.styles =
                                    {
                                        title: {
                                            fontSize: 14,
                                            bold: true,
                                            alignment: 'center'
                                        },
                                        footer: {
                                            fontSize: 22,
                                            bold: true
                                        },
                                        tableHeader: {
                                            bold: true,
                                            fontSize: 7,
                                            color: 'black',
                                            fillColor: '#135d92',
                                            alignment: 'center'
                                        },
                                        tableFooter: {
                                            bold: true,
                                            fontSize: 7,
                                            color: 'black',
                                            fillColor: '#135d92',
                                            alignment: 'center'
                                        }
                                    };

                                    doc.footer = function(currentPage, pageCount) {
                                        return { text: 'Página '+currentPage.toString()+'  ', alignment: 'right', margin: 10 };
                                    };
                                }
                            } )
                        ]
                    } );

                    $('#results-table').show();
                },
                error: function (data) {
                    var errors = data.responseJSON;

                    toastr.error(errors.message,'Error');
                    $('#results-table').hide();
                }
            });

            $('#deleteBtn').prop("disabled", false);
            // add spinner to button
            $('#deleteBtn').html(
                `<i class="fas fa-trash" aria-hidden="true"></i> Eliminar`
            );
        }
    });

});

function loadDelegation(delegation_id){
    selected = delegation_id ? delegation_id : default_brand;
    $.get('/delegations/'+delegation_id, function(data) {
        $("#delegation_info").html(
            "<label>Dirección: </label> "+data.address+"<br><label>Zona: </label> "+data.zone+"");
    });
}

$.validator.addMethod("dateFormat",
function(value, element) {
    // put your own logic here, this is just a (crappy)
    return value.match(/^\d\d?\-\d\d?\-\d\d\d\d$/);
},
"Debe ser formato dd-mm-aaaa");
</script>
@endsection
