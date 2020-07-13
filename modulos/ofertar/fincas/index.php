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
    echo $lib->bootstrapSelect();
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
          <h1 class="m-0 text-dark"><i class="fas fa-mountain"></i> Fincas</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container">
      <div class="alert alert-warning" role="alert">
        <ol class="mb-0">
          <li>Haz click en <b>Crear Finca</b> e ingresa la información que allí solicitamos y cuando la hayas completado haz click en <b>Enviar</b>.</li>
          <li>Si necesitas regresar o ya terminaste haz click en <b>Inicio</b>, arriba en esta pagina.</li>
        </ol>
      </div>
      <div class="card">
        <div class="card-header d-flex justify-content-end">
          <button class="btn btn-success btnCrear"><i class="fas fa-plus"></i> Crear finca</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Municipio</th>
                <th scope="col">Dirección</th>
                <th scope="col">Hectareas</th>
                <th scope="col">Fecha creación</th>
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
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear finca</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre">Nombre del terreno o finca <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del terreno o finca" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="departamento">Departamento <span class="text-danger">*</span></label>
              <select class="selectpicker form-control" name="departamento" required data-live-search="true" data-size="5" title="Seleccione un departamento"></select>
            </div>
            <div class="form-group">
              <label for="municipio">Municipio <span class="text-danger">*</span></label>
              <select class="selectpicker form-control" name="municipio" disabled required  data-live-search="true" data-size="5" title="Seleccione un municipio"></select>
            </div>
            <div class="form-group">
              <label for="hectareas">Hectáreas Sembradas <span class="text-danger">*</span></label>
              <input type="number" min="1" name="hectareas" class="form-control" placeholder="Número de hectáreas sembradas" required autocomplete="off" onKeyPress="return soloNumeros(event)">
            </div>
            <div class="form-group">
              <label for="direccion">Predio exportador <span class="text-danger">*</span></label> <br>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="predio_exportador" name="predio_exportador" value="1" required class="custom-control-input">
                <label class="custom-control-label" for="predio_exportador">Si</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="predio_exportador2" name="predio_exportador" value="0" required class="custom-control-input">
                <label class="custom-control-label" for="predio_exportador2">No</label>
              </div>
            </div>
            <div class="form-group d-none" id="registro_ica">
              <label for="nombre">Registro ICA <span class="text-danger">*</span></label>
              <input type="text" name="registro_ica" class="form-control" placeholder="Escriba el registro del ICA" disabled required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="direccion">Dirección <span class="text-danger">*</span></label>
              <textarea class="form-control" required name="direccion" rows="3" placeholder="Escriba una dirección"></textarea>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button id="btnCerrar" type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
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
    //Se abre la modal para crear productos
    $('.btnCrear').on("click", function(){
      $("#formCrear :input").removeAttr("disabled", true);
      $("#formCrear")[0].reset();
      $("#formCrear :input[name='departamento']")[0].selectedIndex = 0;
      $("#formCrear :input[name='municipio']")[0].selectedIndex = 0;
      $("#registro_ica").addClass("d-none");
      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear finca`);
      $("#btnCrear").removeClass("d-none");
      $("#formCrear :input[name='accion']").val('crear');
      $("#modalCrear").modal("show");
    });

    //Validamos el check del predio exportador para habilitar el campo del registro
    $("#formCrear :input[name='predio_exportador']").on("click", function(){
      let predio_exportado = $(this).val();
      if (predio_exportado == 1) {
        $("#registro_ica").removeClass("d-none");
        $("#formCrear :input[name='registro_ica']").removeAttr("disabled");
      } else {
        $("#registro_ica").addClass("d-none");
        $("#formCrear :input[name='registro_ica']").attr("disabled", true);
      }
    });

    //Se cargan los departamentos
    $.ajax({
      url: 'acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "departamentos"
      },
      success: function(data){
        if (data.success) {
          $("#formCrear :input[name='departamento']").empty();
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formCrear :input[name='departamento']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
          $("#formCrear :input[name='departamento']").selectpicker('refresh');
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

    $(document).on("change", "#formCrear :input[name='departamento']", function(){
      $("#formCrear :input[name='municipio']").prop("disabled", false);
      $("#formCrear :input[name='municipio']").empty();
      if ($(this).val() != 0) {  
        $.ajax({
          async:false,
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "municipios",
            departamento: $(this).val()
          },
          success: function(data){
            if (data.success) {
              for (let i = 0; i < data.msj.cantidad_registros; i++) {
                $("#formCrear :input[name='municipio']").append(`
                  <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
                `);
              }
              $("#formCrear :input[name='municipio']").selectpicker('refresh');
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
    });

    $("#formCrear").submit(function(event){
      event.preventDefault();
      if($("#formCrear").valid() && $("#formCrear :input[name='accion']").val() != 0){
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
            if (data.success) {
              $("#tabla").DataTable().ajax.reload();
              $("#formCrear")[0].reset();
              $("#formCrear :input[name='departamento']").selectpicker('render');
              $("#formCrear :input[name='municipio']").selectpicker('render');
              $("#registro_ica").addClass("d-none");
              $("#formCrear :input[name='registro_ica']").attr("disabled", true);
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

    listar();
  });

  function listar(){

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
            accion: 'lista'
          },
          complete: function(){
            cerrarCargando();
          }
      },
      columns: [
        { data: "nombre" },
        { data: "municipio" },
        { data: "direccion" },
        { data: "hectareas" },
        { data: "fecha_creacion" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            return `<div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-primary btn-sm mx-1" onClick='ver(${JSON.stringify(oData)})'><i class="far fa-eye"></i> Ver</button>
                      <button type="button" class="btn btn-danger btn-sm mx-1" onClick='eliminar(${JSON.stringify(oData)})'><i class="fas fa-trash-alt"></i> Eliminar</button>
                    </div>`;
          }
        }
      ],
      lengthMenu: [
        [ 10, 25, 50, -1 ],
        [ '10', '25', '50', 'Todo' ]
      ],
    });
  };

  function eliminar(datos){
    Swal.fire({
      title: "¿Estas seguro de eliminar la finca " + datos['nombre'] + "?",
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
            id: datos['id'],
            nombre: datos['nombre']
          },
          success: function(data){
            if (data == 1) {
              $("#tabla").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "Se ha eliminado la finca " + datos['nombre'],
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al eliminar la finca " + datos['nombre']
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

  function ver(datos){
    $("#formCrear :input").removeAttr("disabled", true);
    $("#tituloModal").html(`<i class="far fa-eye"></i> Ver | ${datos.nombre}`);
    $("#formCrear :input[name='accion']").val(0);
    $("#formCrear :input[name='nombre']").val(datos.nombre);
    $("#formCrear :input[name='departamento']").val(datos.fk_departamento);
    $("#formCrear :input[name='departamento']").change();
    $("#formCrear :input[name='municipio']").val(datos.fk_municipio);
    $("#formCrear :input[name='municipio']").change();
    $("#formCrear :input[name='hectareas']").val(datos.hectareas);
    $("#formCrear :input[name='registro_ica']").val(datos.registro_ica);
    $("#formCrear :input[name='direccion']").val(datos.direccion);
    if (datos.predio_exportador == 1) {
      $("#predio_exportador").click();
    } else {
      $("#predio_exportador2").click();
    }

    $("#formCrear :input").attr("disabled", true);
    $("#btnCerrar").removeAttr("disabled");
    $("#btnCrear").addClass("d-none");
    $("#modalCrear").modal("show");
  }
</script>
</html>