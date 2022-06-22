@extends('adminlte::page')

@section('title', 'Listado de Roles')

@section('content_header', 'Listado de Roles')

@section('content')
@include('alerts.success')
@include('alerts.errors')

<div class="row">
    <div class="col-md">
        <a class="btn btn-info btn-sm" href="javascript:void(0)" id="createNew"><span title="Nuevo"
                class="fas fa-file-alt"></span> Nuevo</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md">
        <table class="table table-bordered table-striped table-sm table-hover data-table dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th width="10%">Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="RecordForm" name="RecordForm" class="form-horizontal">
                    <div class="container">
                        <div class="row">
                            <input type="hidden" name="Record_id" id="Record_id">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="name"
                                        placeholder="Nombre" value="" maxlength="50" required>

                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="slug" class="control-label">Slug</label>
                                    <input type="text" class="form-control form-control-sm" id="slug" name="slug"
                                        placeholder="Slug" value="" maxlength="50" required>

                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="permissions" class="control-label">Permisos</label>
                                    {!! Form::select('permissions',$permissions,null,['id'=>'permissions', 'class' => 'multiselect
                                    form-control', 'name' =>'permissions[]', 'multiple' => 'multiple', 'required', 'minlength'
                                    => '1', 'title'
                                    => 'Requerido']) !!}

                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-sm btn-info" id="saveBtn" value="create"><i
                                        class="fas fa-save" aria-hidden="true"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
$(function () {
    var url = "roles";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Validacion de formulario
    $("#RecordForm").validate({
        ignore: "not:hidden",
        errorClass: "error",
        errorPlacement: function(error, element) {
            error.insertBefore(element);
        },
        rules: {
            name: { required: true },
            slug: { required: true },

        },
        messages: {
            name: { required: "Requerido" },
            slug: { required: "Requerido" },
        }
    });

    // Cargar datos en datatable
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "",
        language: {
            "url": "js/Spanish.json"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'slug', name: 'slug'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    // Cargar formulario nuevo
    $('#createNew').click(function () {
        $('#saveBtn').val("create-record");
        $('#Record_id').val('');
        $('#RecordForm').trigger("reset");
        $('#modelHeading').html("Nuevo Rol");
        $('#ajaxModel').modal('show');
    });

    // Cargar formulario para editar
    $('body').on('click', '.editRecord', function () {
        var Record_id = $(this).data('id');
        $.get(url +'/' + Record_id +'/edit', function (data) {
            // Cargar permisos asignados
            var data_permission= [];
            var permissions = data[1];
            for (i = 0; i < permissions.length; i++) {
                data_permission.push(permissions[i].id);
            };
            // Asigna al combo los permisos
	        $("#permissions").val(data_permission);
            $("#permissions").trigger("change");

            $('#modelHeading').html("Editar Rol");
            $('#saveBtn').val("edit-record");
            $('#ajaxModel').modal('show');
            $('#Record_id').val(data[0].id);
            $('#name').val(data[0].name);
            $('#slug').val(data[0].slug);
        })
    });

    // Guardar Registro
    $('#saveBtn').click(function (e) {
        e.preventDefault();

        if ($("#RecordForm").valid()) {
            $.ajax({
                data: $('#RecordForm').serialize(),
                url: url,
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#recordForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    toastr.success('Registro guardado!');
                    table.draw();
                },
                error: function (data) {
                    toastr.error('No se pudo guardar el registro!','Error');
                }
            });
        }

    });

    // Eliminar Registro
    $('body').on('click', '.deleteRecord', function () {
        var Record_id = $(this).data("id");
        $.confirm({
            text: "¿Esta seguro?",
            type: 'red',
            closeIcon: false,
            confirm: function(button) {
                $.ajax({
                    type: "DELETE",
                    url: url+'/'+Record_id,
                    success: function (data) {
                        table.draw();
                        toastr.success('Registro eliminado!');
                    },
                    error: function (data) {
                        toastr.error('No se pudo eliminar el registro!','Error');
                    }
                });
            },
            cancel: function(button) {
                // nothing to do
            },
            confirmButton: "Si",
            cancelButton: "No",
            post: true,
            confirmButtonClass: "btn-info",
            cancelButtonClass: "btn-default",
            dialogClass: "modal-dialog modal-sm" // Bootstrap classes for large modal
        });
    });
});
</script>
@endsection
