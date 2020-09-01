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
    echo $lib->moment();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->sweetAlert2();
    echo $lib->jqueryValidate();
    echo $lib->datatables();
    echo $lib->lightbox();
    echo $lib->bsCustomFileInput();
    echo $lib->proyecto();
  ?>
</head>
<body class="content-fruturo">

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container-fluid">
      <img class="w-100 mb-4" src="<?php echo($ruta_raiz) ?>assets/img/predios.png" alt="">
      <button type="button" class="btn btn-secondary mb-1" onclick="back()">
        <i class="fas fa-arrow-left"></i>
        Volver
      </button>
      <div class="row mt-3">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="fas fa-box"></i> Productos - <?= @$_GET['nombre'] ?></h1>
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
          <li>Haz click en <b>Crear Producto</b> e ingresa la información que allí solicitamos y cuando la hayas completado haz click en <b>Enviar</b>.</li>
          <li>Si necesitas regresar o ya terminaste haz click en <b>Inicio</b> o en el botón <b>Volver</b>, arriba en esta pagina.</li>
        </ol>
      </div>
      <div class="card">
        <div class="card-header d-flex justify-content-end">
          <button class="btn btn-success btnCrear"><i class="fas fa-plus"></i> Crear producto</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Presentación</th>
                <th scope="col">Frecuencia</th>
                <th scope="col">Descripcion</th>
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
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear producto</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <input type="hidden" name="fk_finca" value="<?= $_GET["fk_finca"] ?>">
          <div class="modal-body ">
            <div class="form-group">
              <label for="nombre">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del producto" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="presentacion">Presentación <span class="text-danger">*</span></label>
              <input type="text" name="presentacion" required class="form-control" placeholder="Ejemplo: Paquete por 50 gramos, Botella por 350 ml" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="frecuencia">Frecuencia <span class="text-danger">*</span></label>
              <select class="custom-select" name="frecuencia">
                <option value="1">Semanal</option>
                <option value="2">Quincenal</option>
                <option value="3">Mensual</option>
              </select>
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" name="descripcion" rows="3" placeholder="Escriba una descripción del producto"></textarea>
            </div>
            <div class="form-group">
              <label for="direccion">Tiene registro invima <span class="text-danger">*</span></label> <br>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="tiene_invima" name="tiene_invima" value="1" required class="custom-control-input">
                <label class="custom-control-label" for="tiene_invima">Si</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="tiene_invima2" name="tiene_invima" value="0" required class="custom-control-input">
                <label class="custom-control-label" for="tiene_invima2">No</label>
              </div>
            </div>
            <div class="form-group d-none" id="registro_invima">
              <label for="nombre">Registro Invima <span class="text-danger">*</span></label>
              <input type="text" name="registro_invima" class="form-control" placeholder="Escriba el registro invima" disabled required autocomplete="off">
            </div>
            <div class="alert alert-warning" role="alert">
              <b>Instructivo foto de tabla nutricional</b>
              <ol>
                <li>Favor acerque su cámara a la tabla nutricional de su producto</li>
                <li>Realice la foto tratando de no dejar espacios, ejemplo: </li>
              </ol>
              <div class="text-center">
                <img style="width: 150px;"  src="<?php echo($ruta_raiz); ?>/assets/img/tabla-nutricional.jpg" alt="tabla nutricional ejemplo">
              </div>
            </div>
            <div class="form-group">
              <label for="tabla_nutricional">Tabla nutricional: <span class="text-danger">*</span></label>
              <div class="custom-file">
                <input required type="file" required class="custom-file-input" id="tabla_nutricional" name="tabla_nutricional" accept="image/png, image/jpg, image/jpeg">
                <label class="custom-file-label d-inline-block text-truncate" for="tabla_nutricional" data-browse="Elegir">Seleccionar Archivo</label>
                <small id="archivosExtensionesSmall" class="form-text text-muted">
                  Archivos tipo png, jpg, máximo de 3MB
                </small>
              </div>
            </div>
            <div class="alert alert-warning" role="alert">
              <b>Instructivo de fotos:</b>
              <ol>
                <li>Revisar el producto que se encuentre limpio</li>
                <li>Ubicarlo sobre un fondo blanco</li>
                <li>Realizar 2 fotos, una de frente y la otra mostrando el revés del producto</li>
              </ol>
            </div>
            <div class="form-group">
              <label for="foto_frente">Foto frente: <span class="text-danger">*</span></label>
              <div class="custom-file">
                <input required type="file" required class="custom-file-input" id="foto_frente" name="foto_frente" accept="image/png, image/jpg, image/jpeg">
                <label class="custom-file-label d-inline-block text-truncate" for="foto_frente" data-browse="Elegir">Seleccionar Archivo</label>
                <small id="archivosExtensionesSmall" class="form-text text-muted">
                  Archivos tipo png, jpg, máximo de 3MB
                </small>
              </div>
            </div>
            <div class="form-group">
              <label for="foto_reves">Foto revés: <span class="text-danger">*</span></label>
              <div class="custom-file">
                <input required type="file" required class="custom-file-input" id="foto_reves" name="foto_reves" accept="image/png, image/jpg, image/jpeg">
                <label class="custom-file-label d-inline-block text-truncate" for="foto_reves" data-browse="Elegir">Seleccionar Archivo</label>
                <small id="archivosExtensionesSmall" class="form-text text-muted">
                  Archivos tipo png, jpg, máximo de 3MB
                </small>
              </div>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button id="btnCerrar" type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCrear" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
          </div>
        </form>
        <div class="progress mt-2" style="height: 25px;">
          <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><div class="percent">0%</div></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Cosecha -->
  <div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalVer"><i class="fas fa-plus"></i> Ver producto</h5>
        </div>
        <div class="modal-body">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a class="nav-item nav-link active" id="nav-datos-tab" data-toggle="tab" href="#nav-datos" role="tab" aria-controls="nav-datos" aria-selected="true">Datos</a>
              <a class="nav-item nav-link" id="nav-fotos-tab" data-toggle="tab" href="#nav-fotos" role="tab" aria-controls="nav-profile" aria-selected="fotos">Fotos</a>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-datos" role="tabpanel" aria-labelledby="nav-datos-tab">
              <form class="mt-3" id="formVer" autocomplete="off">
                <div class="form-group">
                  <label for="nombre">Nombre <span class="text-danger">*</span></label>
                  <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del producto" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="presentacion">Presentación <span class="text-danger">*</span></label>
                  <input type="text" name="presentacion" class="form-control" placeholder="Ejemplo: Paquete por 50 gramos, Botella por 350 ml" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="frecuencia">Frecuencia <span class="text-danger">*</span></label>
                  <select name="frecuencia" class="custom-select">
                    <option value="1">Semanal</option>
                    <option value="2">Quincenal</option>
                    <option value="3">Mensual</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <textarea class="form-control" name="descripcion" rows="3" placeholder="Escriba una descripción del producto"></textarea>
                </div>
                <div class="form-group">
                  <label for="direccion">Tiene registro invima <span class="text-danger">*</span></label> <br>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="ver_tiene_invima" name="tiene_invima" value="1" required class="custom-control-input">
                    <label class="custom-control-label" for="ver_tiene_invima">Si</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="ver_tiene_invima2" name="tiene_invima" value="0" required class="custom-control-input">
                    <label class="custom-control-label" for="ver_tiene_invima2">No</label>
                  </div>
                </div>
                <div class="form-group d-none" id="ver_registro_invima">
                  <label for="nombre">Registro Invima <span class="text-danger">*</span></label>
                  <input type="text" name="registro_invima" class="form-control" placeholder="Escriba el registro invima" required autocomplete="off">
                </div>
              </form> 
            </div>
            <div class="tab-pane fade" id="nav-fotos" role="tabpanel" aria-labelledby="nav-fotos-tab">
              <div class="row mt-3" id="productos_fotos">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
        </div>
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
      $("#formCrear :input").removeClass("is-valid");
      $("#formCrear :input").removeClass("is-invalid");
      $("#formCrear")[0].reset();
      $("#registro_invima").addClass("d-none");
      $("#formCrear :input[name='registro_invima']").prop("disabled", true);

      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear producto`);

      $("#btnCrear").prop("disabled", false).removeClass("d-none");
      $("#formCrear :input[name='accion']").val('crear');
      $("#modalCrear").modal("show"); 
    });

    //Validamos el check si tiene registro invima
    $("#formCrear :input[name='tiene_invima']").on("click", function(){
      let predio_exportado = $(this).val();
      if (predio_exportado == 1) {
        $("#registro_invima").removeClass("d-none");
        $("#formCrear :input[name='registro_invima']").removeAttr("disabled");
      } else {
        $("#registro_invima").addClass("d-none");
        $("#formCrear :input[name='registro_invima']").attr("disabled", true);
      }
    });

    /* ==================================================================== */
    // Variables de barra de progreso
    var bar = $('.progress-bar');
    var percent = $('.percent');

    $("#formCrear").submit(function(event){
      event.preventDefault();
      if ($(this).valid() && $("#formCrear :input[name='accion']").val() != 0) {
        $.ajax({
          xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                var percentComplete = ((evt.loaded / evt.total) * 100);
                $(".progress-bar").width(Math.round(percentComplete) + '%');
                $(".progress-bar").html(Math.round(percentComplete)+'%');
              }
            }, false);
            return xhr;
          },
          url: "acciones",
          type: "POST",
          dataType: "json",
          cache: false,
          contentType: false,
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrear')[0].reset();
            $('#formCrear :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrear').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
            $("#btnCrear").attr("disabled" , true);

            $(".progress-bar").width('0%');
            $(".progress-bar").html('0%');
          },
          success: function(data){
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

            setTimeout(function(){
              bar.width('0%');
              percent.html('0%');
            }, 1000);
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
            accion: 'lista',
            finca: <?= $_GET['fk_finca'] ?>
          },
          complete: function(){
            cerrarCargando();
          }
      },
      columns: [
        { data: "nombre" },
        { data: "presentacion"},
        {  
          "render": function (nTd, sData, oData, iRow, iCol) {
            switch (oData.frecuencia) {
              case '1':
                return "Semanal";
                break;
              case '2':
                return "Quincenal";
                break;
              case '3':
                return "Mensual";
                break;
              default:
                return "N/A"
                break;
            }
          }
        },
        { data: "descripcion" },
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
      title: "¿Estas seguro de eliminar el producto " + datos['nombre'] + "?",
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
                title: "Se ha eliminado el producto " + datos['nombre'],
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al eliminar el producto " + datos['nombre']
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
    cargaDatos = 0;
    $("#formVer :input").prop("disabled", false);
    
    $("#tituloModalVer").html(`<i class="far fa-eye"></i> Ver producto | ${datos.nombre}`);

    $("#formVer :input[name='accion']").val('0');
    $("#formVer :input[name='nombre']").val(datos.nombre);
    $("#formVer :input[name='presentacion']").val(datos.presentacion);
    $("#formVer :input[name='descripcion']").val(datos.descripcion);
    $("#formVer :input[name='registro_invima']").val(datos.reg_invima);
    $("#formVer :input[name='frecuencia']").val(datos.frecuencia);
    if (datos.reg_invima == null) {
      $("#ver_tiene_invima2").click();
      $("#ver_registro_invima").addClass("d-none");
    }else{
      $("#ver_tiene_invima").click();
      $("#ver_registro_invima").removeClass("d-none");
    }

    $("#formVer :input").prop("disabled", true);

    //Se traen las fotos de la cosecha
    $.ajax({
      url: "acciones",
      type: "POST",
      dataType: "json",
      async: false,
      data: {
        accion: "fotosProductos",
        idProducto: datos['id']
      },
      success: function(data){
        cargaDatos++;
        $('#productos_fotos').empty();
        if (data.success) {
          for (let i = 0; i < data.msj['cantidad_registros']; i++) {
            $('#productos_fotos').append(`
              <div class="col-6 mt-4">
                <a href="<?php echo($ruta_raiz); ?>${data.msj[i].ruta}" data-lightbox="galeria"><img class="img-thumbnail" src="<?php echo($ruta_raiz); ?>${data.msj[i].ruta}"></a>
              </div>
            `);
          }
        }else{
          $('#productos_fotos').append(`
            <p>No hay fotos</p>
          `);
        }
      },
      error: function(data){
        Swal.fire({
          icon: 'error',
          html: 'No se han enviado los datos'
        })
      }
    });

    if (cargaDatos == 1) {
      $("#modalVer").modal("show");
    }
  }

  function back(){
    var url = "<?php echo($ruta_raiz); ?>modulos/ofertar/predios";
    location.href = url;
  }
</script>
</html>