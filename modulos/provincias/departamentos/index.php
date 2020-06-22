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
  ?>
</head>
<body>

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="fas fa-user-tag"></i> Departamentos</h1>
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
          <div class="d-flex justify-content-end">
            <button class="btn btn-success btnCrearDepto" data-toggle="tooltip" title="Crear"><i class="fas fa-plus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tablaDepto" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Fecha</th>
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
  <div class="modal fade" id="modalCrearDepto" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear Departamento</h5>
        </div>
        <form id="formCrearDepto" autocomplete="off">
          <input type="hidden" name="accion" value="crearDepartamento">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre">Nombre Departamento <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del departamento" required autocomplete="off">
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

    //Se abre la modal para crear
    $('.btnCrearDepto').on("click", function(){
      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear Departamento`);
      $("#formCrearDepto :input[name='accion']").val('crearDepartamento');
      $("#modalCrearDepto").modal("show");
      $("#formCrearDepto :input[name='nombre']").val('');
      $("#formCrearDepto :input[name='nombre']").focus();
    });

    //Editar Usuario
    $(document).on("click", ".btnEditarDepto", function(){
      let datos = $(this).data("datos");
      $("#tituloModal").html(`<i class="fas fa-edit"></i> Editar Departamento | ` + datos['nombre']);
      $("#formCrearDepto :input").removeClass("is-valid");
      $("#formCrearDepto :input").removeClass("is-invalid");

      $("#formCrearDepto :input[name='accion']").val('editarDepartamento');
      $("#formCrearDepto :input[name='id']").val(datos['id']);
      $("#formCrearDepto :input[name='nombre']").val(datos['nombre']);

      $("#modalCrearDepto").modal("show");
      $("#formCrearDepto :input[name='nombre']").focus();
    });

    //Acciones al cerrar la modal
    $('#modalCrearDepto').on('hidden.bs.modal', function (e) {
      if($("#formCrearDepto :input[name='accion']").val() == 'editar'){
        $("#formCrearDepto")[0].reset();
      }
    });

    $("#formCrearDepto").submit(function(event){
      event.preventDefault();
      if($("#formCrearDepto").valid()){
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
            $('#btnCrearDepto').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
            $("#btnCrearDepto").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tablaDepto").DataTable().ajax.reload();
              $("#formCrearDepto")[0].reset();
              $("#formCrear :input").removeClass("is-valid");
              $("#formCrear :input").removeClass("is-invalid");
              $("#modalCrearDepto").modal("hide");
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
            $('#btnCrearDepto').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrearDepto").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#btnCrearDepto').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrearDepto").attr("disabled", false);
          }
        });
      }
    });

    listaTabla();
  });

  function inhabilitar(datos){
    Swal.fire({
      title: "¿Estas seguro de inhabilitar el departamento " + datos['nombre'] + "?",
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
            accion: "inhabilitarDepartamento", 
            id: datos['id'],
            nombre: datos['nombre']
          },
          success: function(data){
            if (data == 1) {
              $("#tablaDepto").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "Se ha inhabilitado el departamento " + datos['nombre'],
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al inhabilitar el departamento " + datos['nombre']
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

  function listaTabla(){
    $("#tablaDepto").DataTable({
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
            accion: 'listaDepartamentos'
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
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            return `<div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-primary btn-sm mx-1 btnEditarDepto" data-toggle="tooltip" title="Editar" data-datos='${JSON.stringify(oData)}'><i class="far fa-edit"></i></button>
                      <button type="button" class="btn btn-danger btn-sm mx-1" onClick='inhabilitar(${JSON.stringify(oData)})' data-toggle="tooltip" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
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
</script>
</html>