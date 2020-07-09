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

  if ($permisos->validarPermiso($usuario['id'], 'productos') == 0) {
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
    echo $lib->moment();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->sweetAlert2();
    echo $lib->jqueryValidate();
    echo $lib->datatables();
    echo $lib->bootstrapTreeView();
    echo $lib->proyecto();
  ?>
</head>
<body>

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark"> Derivados </h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="d-flex">
            <div class="input-group w-md-25 w-100 mr-2">
              <select id="filtroEstado" class="custom-select" name="estado" required data-live-search="true" data-size="5" title="Seleccione un estado">
                <option selected value="1">Activos</option>
                <option value="0">Inactivos</option>
              </select>
            </div>
            <div class="input-group w-md-25 w-100 mr-2">
              <select id="filtroProducto" class="custom-select" name="producto" required data-live-search="true" data-size="5" title="Seleccione un producto">
              </select>
            </div>
            <button class="btn btn-success btnCrear ml-auto" data-toggle="tooltip" title="Crear"><i class="fas fa-plus"></i></button>
          </div>
        </div>  
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Descripción</th>
                <th scope="col">Producto</th>
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
  <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear producto</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="producto"> Producto <span class="text-danger">*</span></label>
              <select class="selectpicker form-control" name="producto" required data-live-search="true" data-size="5" title="Seleccione un departamento"></select>
            </div>
            <div class="form-group">
              <label for="producto">Nombre derivado <span class="text-danger">*</span></label>
              <input type="text" name="derivado" class="form-control" placeholder="Escriba el nombre del derivado" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="descripcion"> Descripción<span class="text-danger">*</span></label>
              <textarea type="text" name="descripcion" class="form-control" placeholder="Escriba una descripción" required autocomplete="off"></textarea>
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

  $(function(){
    $('[data-toggle="tooltip"]').tooltip();

    //Se abre la modal para crear productos
    $('.btnCrear').on("click", function(){
      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear Derivado`);
      $("#formCrear :input[name='accion']").val('crear');
      $("#modalCrear").modal("show");
      $("#formCrear :input[name='producto']").focus();
    });

    //Editar derivado
    $(document).on("click", ".btnEditar", function(){
      let derivado = $(this).data("producto");
      console.log(derivado);
      $("#tituloModal").html(`<i class="fas fa-edit"></i> Editar Derivado | ` + derivado['nombre']);
      $("#formCrear :input").removeClass("is-valid");
      $("#formCrear :input").removeClass("is-invalid");

      $("#formCrear :input[name='accion']").val('editar');
      $("#formCrear :input[name='id']").val(derivado['id']);
      $("#formCrear :input[name='derivado']").val(derivado['nombre']);
      $("#formCrear :input[name='descripcion']").val(derivado['descripcion']);
      $("#formCrear :input[name='producto']").val(derivado['fk_producto']);

      $("#modalCrear").modal("show");
    });

    //Acciones al cerrar la modal
    $('#modalCrear').on('hidden.bs.modal', function (e) {
      if($("#formCrear :input[name='accion']").val() == 'editar'){
        $("#formCrear")[0].reset();
      }
    });

    $("#formCrear").submit(function(event){
      event.preventDefault();
      if($("#formCrear").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrear :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrear').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
            $("#btnCrear").attr("disabled" , true);
          },
          success: function(data){
            console.log({data});
            if (data.success) {
              $("#tabla").DataTable().ajax.reload();
              $("#formCrear")[0].reset();
              $("#formCrear :input").removeClass("is-valid");
              $("#formCrear :input").removeClass("is-invalid");
              $("#modalCrear").modal("hide");
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
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            Swal.fire({
              icon: 'error',
              html: 'Error al registrar.'
            });
            //Habilitamos el botón
            $('#formCrear :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrear").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCrear :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrear").attr("disabled", false);
          }
        });
      }
    });

    function listarTabla(){
      // -1 = todos 
      var producto = $("#filtroProducto").val() ? $("#filtroProducto").val() : -1;
      // 1 = activos
      var estado   = $("#filtroEstado").val()   ? $("#filtroEstado").val()   : 1;

      $("#tabla").DataTable({
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
              accion: 'lista',
              estado,
              producto 
            },
            complete: function(){
              $('[data-toggle="tooltip"]').tooltip('hide');
              $('[data-toggle="tooltip"]').tooltip();
              cerrarCargando();
            }
        },
        columns: [
          { data: "nombre" },
          { data: "descripcion" },
          { data: "nombre_producto" },
          {
            "render": function (nTd, sData, oData, iRow, iCol) {
              return `<div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary btn-sm mx-1 btnEditar" data-toggle="tooltip" title="Editar" data-producto='${JSON.stringify(oData)}'><i class="far fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm mx-1" onClick='eliminar(${JSON.stringify(oData)})' data-toggle="tooltip" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
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

    $("#filtroProducto").change(function () {
      top.$('#cargando').modal('show');
      $('#tabla').dataTable().fnDestroy();
      listarTabla();
    });

    $("#filtroEstado").change(function () {
      top.$('#cargando').modal('show');
      $('#tabla').dataTable().fnDestroy();
      listarTabla();
    });

    listarProductos();
    listarTabla();
  });

  function listarProductos(){
    $.ajax({
      url: 'acciones',
      type: 'GET',
      dataType: 'json',
      data: {
        accion: "listaProdcutos",
      },
      success: function(data){
        if (data.success) {
          $("#formCrear :input[name='producto']").empty();          
          $("#filtroProducto").empty();
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formCrear :input[name='producto']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);

            $("#filtroProducto").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }

          $("#filtroProducto").append(`
              <option value="${-1}">${'Todos'}</option>
          `);

          $("#filtroProducto").val(-1);
          $("#formCrear :input[name='producto']").selectpicker('refresh');
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

  function eliminar(derivado){
    console.log(derivado);
    Swal.fire({
      title: "¿Estas seguro de eliminar el derivado " + derivado['nombre'] + "?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<i class="far fa-trash-alt"></i> Si',
      cancelButtonText: '<i class="fa fa-times"></i> No'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "eliminar", 
            id: derivado['id'],
            nombre: derivado['nombre']
          },
          success: function(data){
            if (data == 1) {
              $("#tabla").DataTable().ajax.reload();
              listarProductos();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "Se ha eliminado el producto " + producto['nombre'],
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al eliminar el producto " + producto['nombre']
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
</script>
</html>