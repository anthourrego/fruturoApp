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
    echo $lib->datatables();
    echo $lib->chosen();
    echo $lib->proyecto();
  ?>
</head>
<body class="content-fruturo">

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container-fluid">
      <button type="button" class="btn btn-secondary mb-1" onclick="back()">
        <i class="fas fa-arrow-left"></i>
        Volver
      </button>
      <div class="row mb-2 mt-3">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="fas fa-home"></i> Predios</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="alert alert-warning" role="alert">
        <h5 class="text-left">Instrucciones:</h5>
        <ol class="mb-0">
          <li>Haz click en <b>Crear Predio</b> e ingresa la información que allí solicitamos y cuando la hayas completado haz click en <b>Enviar</b>.</li>
          <li>Si necesitas regresar o ya terminaste haz click en <b>Inicio</b>, arriba en esta pagina.</li>
        </ol>
      </div>
      <div class="card">
        <div class="card-header d-flex justify-content-end">
          <button class="btn btn-success btnCrear"><i class="fas fa-plus"></i> Crear predio</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Tipo predio</th>
                <th scope="col">Nombre</th>
                <th scope="col">Municipio</th>
                <th scope="col">Dirección</th>
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
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear Predio</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <div class="modal-body ">
            <div class="form-group">
              <label for="tipo_predio">Tipo de producto</label>
              <select name="tipo_predio" data-placeholder="Seleccion un tipo de predio" required class="chosen-select" tabindex="2"></select>
            </div>
            <div class="form-group">
              <label for="nombre" id="labelTipo">Nombre del terreno o predio<span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del terreno o predio" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="departamento">Departamento <span class="text-danger">*</span></label>
              <select data-placeholder="Selecione un departamento" disabled class="chosen-select" name="departamento" required></select>
            </div>
            <div class="form-group">
              <label for="municipio">Municipio <span class="text-danger">*</span></label>
              <select data-placeholder="Selecione un municipio" class="chosen-select" name="municipio" disabled required ></select>
            </div>
            <div class="form-group" id="hectareas">
              <label for="hectareas">Hectáreas Sembradas <span class="text-danger">*</span></label>
              <input type="number" min="1" name="hectareas" disabled class="form-control" placeholder="Número de hectáreas sembradas" required autocomplete="off" onKeyPress="return soloNumeros(event)">
            </div>
            <div class="form-group" id="contenedor_predio_exportador">
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
            <div class="form-group" id="registro_ica">
              <label for="nombre">Registro ICA <span class="text-danger">*</span></label>
              <input type="text" name="registro_ica" class="form-control" placeholder="Escriba el registro del ICA" disabled required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="direccion">Dirección <span class="text-danger">*</span></label>
              <textarea class="form-control" required name="direccion" rows="3" placeholder="Escriba una dirección"></textarea>
            </div>

            <div class="alert alert-warning" role="alert" id="instruccionProcesados">
              <h5 class="text-center" >Instrucciones - Agregar Productos Procesados</h5>
              
              <ol class="mb-0">
                <li>Crear la <b>Fabrica</b></li>
                <li>Buscar la fabrica creada en la tabla de predios</li>
                <li>Seleccionar el botón  <b>Productos</b>.</li>
                <li>Después de crear un producto procesado debes ir a <b>Inicio</b> y crear una oferta en <b>Ofertar</b>.</li>

              </ol>
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
    $("#instruccionProcesados").hide();

    //Se abre la modal para crear productos
    $('.btnCrear').on("click", function(){
      $("#formCrear :input").removeClass("is-valid");
      $("#formCrear :input").removeClass("is-invalid");
      $("#formCrear")[0].reset();
      $("#formCrear :input[name='tipo_predio']").val(0).prop("disabled", false).trigger("chosen:updated");
      $("#formCrear :input[name='nombre']").prop("disabled", true);
      $("#formCrear :input[name='departamento']").val(0).prop('disabled', true).trigger("chosen:updated");
      $("#formCrear :input[name='municipio']").val(0).prop('disabled', true).trigger("chosen:updated");
      $("#formCrear :input[name='hectareas']").prop("disabled", true);
      $("#formCrear :input[name='predio_exportador']").prop("disabled", true);
      $("#formCrear :input[name='registro_ica']").prop("disabled", true);
      $("#formCrear :input[name='direccion']").prop("disabled", true);
      $("#hectareas").addClass("d-none");
      $("#contenedor_predio_exportador").addClass("d-none");
      $("#registro_ica").addClass("d-none");

      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear predio`);

      $("#btnCrear").prop("disabled", false).removeClass("d-none");
      $("#formCrear :input[name='accion']").val('crear');
      $("#modalCrear").modal("show");
    });

    //Cuando cambie el tipo de predio
    $("#formCrear :input[name='tipo_predio']").on("change", function(){
      // cambio de label
      if($("#formCrear :input[name='tipo_predio']").val() == 2){
        $('#labelTipo')[0].innerHTML = 'Nombre de la fabrica';
        $("#formCrear :input[name='nombre']").attr("placeholder", 'Escriba el nombre de la fabrica');
        $("#instruccionProcesados").show();
        
      }else{
        $('#labelTipo')[0].innerHTML = 'Nombre del terreno o predio';
        $("#formCrear :input[name='nombre']").attr("placeholder", 'Escriba el nombre del terreno o predio');
        $("#instruccionProcesados").hide();

      }

      $("#formCrear :input[name='nombre']").removeAttr("disabled");
      $("#formCrear :input[name='departamento']").removeAttr("disabled").trigger("chosen:updated");
      $("#formCrear :input[name='direccion']").removeAttr("disabled");
      if ($(this).val() == 1) {
        $("#formCrear :input[name='hectareas']").removeAttr("disabled");
        $("#formCrear :input[name='predio_exportador']").removeAttr("disabled");
        $("#contenedor_predio_exportador").removeClass("d-none");
        $("#hectareas").removeClass("d-none");
      } else {
        $("#formCrear :input[name='hectareas']").prop("disabled", true);
        $("#formCrear :input[name='predio_exportador']").prop("disabled", true);
        $("#formCrear :input[name='registro_ica']").prop("disabled", true);
        $("#contenedor_predio_exportador").addClass("d-none");
        $("#hectareas").addClass("d-none");
        $("#registro_ica").addClass("d-none");
      }
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

    //Cargamos los predios
    $.ajax({
      url: '<?= $ruta_raiz ?>modulos/predios_tipos/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "listaPredios"
      },
      success: function(data){
        if (data.success) {
          let select = $("#formCrear :input[name='tipo_predio']");
          select.empty();

          select.append(`
              <option value="0" disabled selected>Seleccione un tipo de producto</option>
            `);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            select.append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }

          select.trigger("chosen:updated");
                    
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
          html: 'No se encontrado datos de tipos predios'
        })
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
          let select = $("#formCrear :input[name='departamento']");

          select.empty();

          select.append(`
              <option value="0" disabled selected>Seleccione un departamento</option>
            `);

          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            select.append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }

          select.trigger("chosen:updated");

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
      let select = $("#formCrear :input[name='municipio']");

      select.prop("disabled", false);
      select.empty();
      if ($(this).val() != 0) {  
        $.ajax({
          async: false,
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "municipios",
            departamento: $(this).val()
          },
          success: function(data){
            if (data.success) {
              select.append(`
                  <option selected disabled value="0">Seleccione un municipio</option>
                `);

              for (let i = 0; i < data.msj.cantidad_registros; i++) {
                select.append(`
                  <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
                `);
              }

              select.trigger("chosen:updated");
              
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
              $("#formCrear :input[name='departamento']").val(0).prop('disabled', true).trigger("chosen:updated");
              $("#formCrear :input[name='municipio']").val(0).prop('disabled', true).trigger("chosen:updated");
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
        { data: "finca_tipo" },
        { data: "nombre" },
        { data: "municipio" },
        { data: "direccion" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            let productos = "";
            if (oData.fk_finca_tipo == 2) {
              productos = `<a type="button" class="btn btn-info btn-sm mx-1" href="<?= $ruta_raiz ?>modulos/ofertar/productos?fk_finca=${oData.id}&nombre=${oData.nombre}"><i class="fas fa-box"></i> Productos</a>`
            }

            return `<div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-primary btn-sm mx-1" onClick='ver(${JSON.stringify(oData)})'><i class="far fa-eye"></i> Ver</button>
                      ${productos}
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
      title: "¿Estas seguro de eliminar el predio " + datos['nombre'] + "?",
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
                title: "Se ha eliminado el predio " + datos['nombre'],
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al eliminar el predio " + datos['nombre']
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
    $("#formCrear :input[name='accion']").val('');
    $("#formCrear :input[name='tipo_predio']").val(datos.fk_finca_tipo).change().prop("disabled", true).trigger("chosen:updated");
    $("#formCrear :input[name='nombre']").val(datos.nombre);
    $("#formCrear :input[name='departamento']").val(datos.fk_departamento).change().prop("disabled", true).trigger("chosen:updated");
    $("#formCrear :input[name='municipio']").val(datos.fk_municipio).change().prop("disabled", true).trigger("chosen:updated");
    $("#formCrear :input[name='hectareas']").val(datos.hectareas);
    $("#formCrear :input[name='registro_ica']").val(datos.registro_ica);
    $("#formCrear :input[name='direccion']").val(datos.direccion);

    if (datos.registro_ica != null) {
      $("#predio_exportador").click();
    } else {
      $("#predio_exportador2").click();
    }

    $("#formCrear :input").attr("disabled", true);
    $("#btnCerrar").removeAttr("disabled");
    $("#btnCrear").addClass("d-none");
    $("#modalCrear").modal("show");
  }

  function back(){
    var url = "<?php echo($ruta_raiz); ?>modulos/ofertar";
    location.href = url;
  }
</script>
</html>