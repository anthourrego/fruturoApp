<?php  
	$max_salida=10; // Previene algun posible ciclo infinito limitando a 10 los ../
  $ruta_raiz=$ruta="";
  while($max_salida>0){
    if(@is_file($ruta.".htaccess")){
      $ruta_raiz=$ruta; //Preserva la ruta superior encontrada
      break;
    }
    $ruta.="../";
    $max_salida--;
  }

  include_once($ruta_raiz . 'clases/librerias.php');
  include_once($ruta_raiz . 'clases/sessionActiva.php');
  include_once($ruta_raiz . 'clases/Permisos.php');

  $session = new Session();
  $lib = new Libreria;
  $permisos = new Permisos();

  $usuario = $session->get("usuario");

  if ($permisos->validarPermiso($usuario['id'], 'usuarios_perfiles') == 0) {
    header('Location: ' . $ruta_raiz . 'modulos/');
  }

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <?php  
    echo $lib->jquery();
    echo $lib->jqueryUI();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->sweetAlert2();
    echo $lib->jqueryValidate();
    echo $lib->datatables();
    echo $lib->proyecto();
    echo $lib->bootstrapSelect();
  ?>
</head>
<body>

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="fas fa-user-tag"></i> Municipios</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <div class="d-flex">
              <form id="formEstadosListar" autocomplete="off" class="mr-3">
                <select class="custom-select" name="estado" required data-live-search="true" data-size="5" title="Seleccione un estado">
                    <option selected value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
              </form>
              <form id="formDepartamentosListar" autocomplete="off">
                <select class="custom-select" name="depto" data-live-search="true" data-size="5" title="Seleccione un departamento"></select>
              </form>
            </div>
            <button class="btn btn-success btnCrearMunicipio" data-toggle="tooltip" title="Crear"><i class="fas fa-plus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tablaMunicipio" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Municipio</th>
                <th scope="col">Fecha</th>
                <th scope="col">Departamento</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <!-- Modal Producto -->
  <div class="modal fade" id="modalCrearMunicipio" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear Municipio</h5>
        </div>
        <form id="formCrearMunicipio" autocomplete="off">
          <input type="hidden" name="accion" value="crearMunicipio">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre">Nombre Municipio <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del Municipio" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="departamento">Departamento <span class="text-danger">*</span></label>
                <select class="selectpicker form-control" name="departamento" required data-live-search="true" data-size="5" title="Seleccione un departamento"></select>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCrear" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  let estadoTabla = 1;

  $(function(){
    $('[data-toggle="tooltip"]').tooltip();

    //Se abre la modal para crear
    $('.btnCrearMunicipio').on("click", function(){
      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear Municipio`);
      $("#formCrearMunicipio :input[name='accion']").val('crearMunicipio');
      $("#modalCrearMunicipio").modal("show");
      $("#formCrearMunicipio :input[name='departamento']").val('');
      $("#formCrearMunicipio :input[name='departamento']").change();
      $("#formCrearMunicipio :input[name='nombre']").val('');
      $("#formCrearMunicipio :input[name='nombre']").focus();
    });

    //Editar Usuario
    $(document).on("click", ".btnEditarMunicipio", function(){
      let datos = $(this).data("datos");
      $("#tituloModal").html(`<i class="fas fa-edit"></i> Editar Municipio | ` + datos['nombre']);
      $("#formCrearMunicipio :input").removeClass("is-valid");
      $("#formCrearMunicipio :input").removeClass("is-invalid");

      $("#formCrearMunicipio :input[name='accion']").val('editarMunicipio');
      $("#formCrearMunicipio :input[name='id']").val(datos['id']);
      $("#formCrearMunicipio :input[name='nombre']").val(datos['nombre']);
      $("#formCrearMunicipio :input[name='departamento']").val(datos['idDepto']);
      $("#formCrearMunicipio :input[name='departamento']").change();

      $("#modalCrearMunicipio").modal("show");
      $("#formCrearMunicipio :input[name='nombre']").focus();
    });

    //Acciones al cerrar la modal
    $('#modalCrearMunicipio').on('hidden.bs.modal', function (e) {
      if($("#formCrearMunicipio :input[name='accion']").val() == 'editar'){
        $("#formCrearMunicipio")[0].reset();
      }
    });

    $("#formCrearMunicipio").submit(function(event){
      event.preventDefault();
      if($("#formCrearMunicipio").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            //Desabilitamos el botón
            $('#btnCrearMunicipio').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
            $("#btnCrearMunicipio").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tablaMunicipio").DataTable().ajax.reload();
              $("#formCrearMunicipio")[0].reset();
              $("#formCrear :input").removeClass("is-valid");
              $("#formCrear :input").removeClass("is-invalid");
              $("#modalCrearMunicipio").modal("hide");
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: data.msj,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            Swal.fire({
              icon: 'error',
              html: 'Error al guardar.'
            });
            //Habilitamos el botón
            $('#btnCrearMunicipio').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrearMunicipio").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#btnCrearMunicipio').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrearMunicipio").attr("disabled", false);
          }
        });
      }
    });

    $("#formEstadosListar :input[name='estado']").change(function () {
      top.$('#cargando').modal('show');
      estadoTabla = $("#formEstadosListar :input[name='estado']").val();
      $('#tablaMunicipio').dataTable().fnDestroy();
   
      listaTabla($("#formDepartamentosListar :input[name='depto']").val());
    });

    $("#formDepartamentosListar :input[name='depto']").change(function () {
      top.$('#cargando').modal('show');
      let deptoTabla = $("#formDepartamentosListar :input[name='depto']").val();
      $('#tablaMunicipio').dataTable().fnDestroy();
      listaTabla(deptoTabla);
    });

    listaTabla();
    departamentos();
    departamentosLista();
  });

  function cambiarEstado(datos){
    Swal.fire({
      title: `¿Estas seguro de ${estadoTabla == 1 ? 'inhabilitar' : 'habilitar'} el municipio  ${datos['nombre']}?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: `<i class="fas ${estadoTabla == 1 ? 'fa-trash-alt' : 'fa-check'}"></i> Si`,
      cancelButtonText: '<i class="fa fa-times"></i> No'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "cambiarEstadoMunicipio", 
            id: datos['id'],
            nombre: datos['nombre'], 
            estado: datos['estado'],
          },
          success: function(data){
            if (data == 1) {
              $("#tablaMunicipio").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: `Se ha ${estadoTabla == 1 ? 'inhabilitado' : 'habilitado'} el municipio ${datos['nombre']}`,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: `Error al ${estadoTabla == 1 ? 'inhabilitar' : 'habilitar'} el municipio ${datos['nombre']}`
              })
            }
          },
          error: function(){
            Swal.fire({
              icon: 'error',
              html: 'No se han enviado los datos'
            })
          }
        });
      }
    });
  }

  function listaTabla(depto){
    $("#tablaMunicipio").DataTable({
      stateSave: false,
      responsive: true,
      processing: true,
      serverSide: true,
      lengthChange: true,
      pageLength: 10,
      language: {
        url: "<?php echo($ruta_raiz); ?>librerias/dataTables/Spanish.json"
      },
      ajax: {
        url: "acciones",
          type: "GET",
          dataType: "json",
          data: {
            accion: 'listaMunicipios',
            estado: estadoTabla,
            departamento: (depto ? depto : -1),
          },
          complete: function(){
            $('[data-toggle="tooltip"]').tooltip('hide');
            $('[data-toggle="tooltip"]').tooltip();
            cerrarCargando();
          }
      },
      columns: [
        { data: "nombre" },
        { data: "fecha_creacion" },
        { data: "departamento" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            return `<div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-primary btn-sm mx-1 btnEditarMunicipio" data-toggle="tooltip" title="Editar" data-datos='${JSON.stringify(oData)}'><i class="far fa-edit"></i></button>
                      <button type="button" class="btn ${estadoTabla == 1 ? 'btn-danger' : 'btn-success'} btn-sm mx-1" onClick='cambiarEstado(${JSON.stringify(oData)})' data-toggle="tooltip" title="${estadoTabla == 1 ? 'Inactivar' : 'Activar'}"><i class="fas ${estadoTabla == 1 ? 'fa-trash-alt' : 'fa-check'}"></i></button>
                    </div>`;
          }
        }
      ],
      dom: 'Bfrtip',
      lengthMenu: [
        [ 10, 25, 50, -1 ],
        [ '10 registros', '25 registros', '50 registros', 'Mostrar todo' ]
      ],
      buttons: [
        'pageLength',
        {
          extend: 'excelHtml5',
          autoFilter: true,
        },
        'pdf',
        'colvis'
      ]
    });
  }

  function departamentos(){
    $.ajax({
      url: 'acciones',
      type: 'GET',
      dataType: 'json',
      data: {
        accion: "departamentos",
        estado: 1
      },
      success: function(data){
        if (data.success) {
            $("#formCrearMunicipio :input[name='departamento']").empty();
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formCrearMunicipio :input[name='departamento']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
          $("#formCrearMunicipio :input[name='departamento']").selectpicker('refresh');
        }else{
          Swal.fire({
            icon: 'warning',
            html: data.msj
          })
        }
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han enviado los datos'
        })
      }
    });
  }

  function departamentosLista(){
    $.ajax({
      url: 'acciones',
      type: 'GET',
      dataType: 'json',
      data: {
        accion: "departamentos",
        estado: 1,
      },
      success: function(data){
        if (data.success) {
          $("#formDepartamentosListar :input[name='depto']").empty();
          $("#formDepartamentosListar :input[name='depto']").append(`<option value="-1">Todos</option>`);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formDepartamentosListar :input[name='depto']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
          $("#formDepartamentosListar :input[name='depto']").val('-1');
        }else{
          Swal.fire({
            icon: 'warning',
            html: data.msj
          })
        }
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han enviado los datos'
        })
      }
    });
  }
</script>
</html>