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
    echo $lib->bootstrapSelect();
    echo $lib->bsCustomFileInput();
    echo $lib->lightbox();
    echo $lib->proyecto();
  ?>
</head>
<body>

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="far fa-lemon"></i> Ofertar Cosechas</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="alert alert-warning" role="alert">
        <ol class="mb-0">
          <li>Haz click en Crear Oferta e ingresa la Información que allí solicitamos y cuando la hayas completado haz click en Enviar.</li>
          <li>Si necesitas regresar o ya terminaste de ingresar tus productos haz click en Inicio, arriba en esta pagina.</li>
        </ol>
      </div>
      <div class="card">
        <div class="card-header d-flex justify-content-end">
          <button class="btn btn-success btnCrear" data-toggle="tooltip" title="Crear"><i class="fas fa-plus"></i> Crear oferta</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Producto</th>
                <th scope="col">Finca</th>
                <th scope="col">Volumen total</th>
                <th scope="col">Precio</th>
                <th scope="col">Inicio cosecha</th>
                <th scope="col">Fin cosecha</th>
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

  <!-- Modal Cosecha -->
  <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear oferta cosecha</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="producto">Producto <span class="text-danger">*</span></label>
              <select class="selectpicker form-control" required name="producto" required data-live-search="true" data-size="5" title="Seleccione un producto"></select>
            </div>
            <div class="form-group">
              <label for="terreno">Finca <span class="text-danger">*</span></label>
              <select class="selectpicker form-control" required name="terreno" required data-live-search="true" data-size="5" title="Seleccione un terreno"></select>
            </div>
            <div class="form-group">
              <label for="fecha_inicio">Fecha de inicio de la cosecha <span class="text-danger">*</span></label>
              <input type="text" name="fecha_inicio" class="form-control datepicker" placeholder="Escriba una fecha aproximada del incio de la cosecha" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="fecha_fin">Fecha de fin de la cosecha <span class="text-danger">*</span></label>
              <input type="text" name="fecha_fin" class="form-control datepicker" placeholder="Escriba una fecha aproximada del final de la cosecha" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="volumen_total">Volumen total en Kilogramos <span class="text-danger">*</span></label>
              <input type="tel" name="volumen_total" class="form-control" placeholder="Escriba el número de kilogramos" onKeyPress="return soloNumeros(event)" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="precio">Precio por kilogramos <span class="text-danger">*</span></label>
              <input type="tel" name="precio" class="form-control" placeholder="Escriba el precio de la cosecha por kilogramos" onKeyPress="return soloNumeros(event)" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="fotos">Fotos de la cosecha: <span class="text-danger">*</span></label>
              <div class="custom-file">
                <input required type="file" class="custom-file-input" id="fotos" name="fotos[]" accept="image/png, image/jpg, image/jpeg" multiple>
                <label class="custom-file-label" for="fotos" data-browse="Elegir">Seleccionar Archivo</label>
                <small id="archivosExtensionesSmall" class="form-text text-muted">
                  Puedes seleccionar una o más fotos
                </small>
              </div>
            </div>
            <div class="form-group">
              <label for="certificados">Certificados:</label>
              <div id="certificados" class="row"></div>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
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
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Ver oferta</h5>
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
                  <label for="producto">Producto</label>
                  <input class="form-control" type="text" required name="producto" placeholder="Escriba el producto" disabled autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="terreno">Finca</label>
                  <input class="form-control" type="text" required name="terreno" placeholder="Escriba el terreno" disabled autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="fecha_inicio">Fecha de inicio de la cosecha</label>
                  <input type="text" name="fecha_inicio" class="form-control"  placeholder="Escriba una fecha aproximada del incio de la cosecha" required autocomplete="off" disabled>
                </div>
                <div class="form-group">
                  <label for="fecha_fin">Fecha de fin de la cosecha</label>
                  <input type="text" name="fecha_fin" class="form-control" placeholder="Escriba una fecha aproximada del final de la cosecha" required autocomplete="off" disabled>
                </div>
                <div class="form-group">
                  <label for="volumen_total">Volumen total en Kilogramos</label>
                  <input type="tel" name="volumen_total" class="form-control" placeholder="Escriba el número de kilogramos" onKeyPress="return soloNumeros(event)" required autocomplete="off" disabled>
                </div>
                <div class="form-group">
                  <label for="precio">Precio por kilogramos</label>
                  <input type="tel" name="precio" class="form-control" placeholder="Escriba el precio de la cosecha por kilogramos" onKeyPress="return soloNumeros(event)" required autocomplete="off" disabled>
                </div>
                <div class="form-group">
                  <label for="certificados">Certificados:</label>
                  <ul id="certificados_cosecha"></ul>
                </div>
              </form> 
            </div>
            <div class="tab-pane fade" id="nav-fotos" role="tabpanel" aria-labelledby="nav-fotos-tab">
              <div class="row mt-3" id="cosechas_fotos">
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
    $(".datepicker").datepicker({ 
      dateFormat: "yy-mm-dd",
      changeMonth: true,
      changeYear: true
    });

    $("#formCrear :input[name='fecha_inicio']").val(moment().format("YYYY-MM-DD"));
    $("#formCrear :input[name='fecha_fin']").val(moment().format("YYYY-MM-DD"));

    //Se define el rango de fecha
    $("#formCrear :input[name='fecha_inicio']").on("change", function (e) {
      $("#formCrear :input[name='fecha_fin']").datepicker("option", 'minDate', getDate(this));
    });
    $("#formCrear :input[name='fecha_fin']").on("change", function (e) {
      $("#formCrear :input[name='fecha_inicio']").datepicker("option", 'maxDate', getDate(this));
    });

    //Se abre la modal para crear productos
    $('.btnCrear').on("click", function(){
      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear oferta cosecha`);
      $("#formCrear :input[name='accion']").val('crear');
      $("#modalCrear").modal("show");
    });

    /* ==================================================================== */
    // Variables de barra de progreso
    var bar = $('.progress-bar');
    var percent = $('.percent');

    $("#formCrear").submit(function(event){
      event.preventDefault();
      if ($(this).valid()) {
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
              $("#formCrear :input[name='terreno']").selectpicker('render');
              $("#formCrear :input[name='producto']").selectpicker('render');
              $("#formCrear :input[name='fecha_inicio']").val(moment().format("YYYY-MM-DD"));
              $("#formCrear :input[name='fecha_fin']").val(moment().format("YYYY-MM-DD"));
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
    lista();
    listaTerrenos();
    listaProductos();
    checkCertificaciones();
  });

  function lista(){
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
        { data: "producto" },
        { data: "finca" },
        { data: "volumen_total" },
        { data: "precio" },
        { data: "fecha_inicio" },
        { data: "fecha_final" },
        { data: "fecha_creacion" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            return `<div class="d-flex justify-content-center">
                      <button class="btn btn-primary" onClick='verCosecha(${JSON.stringify(oData)})'><i class="far fa-eye"></i> Ver</button>
                      <button type="button" class="btn btn-danger btn-sm mx-1" onClick='eliminar(${JSON.stringify(oData)})'><i class="fas fa-trash-alt"></i> Cancelar</button>
                    </div>`;
          }
        }
      ],
      lengthMenu: [
        [ 10, 25, 50, -1 ],
        [ '10', '25', '50', 'Todo' ]
      ],
    });
  }

  function listaTerrenos(){
    //Se cargan los terrenos
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/ofertar/fincas/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "tusTerrenos"
      },
      success: function(data){
        if (data.success) {
          $("#formCrear :input[name='terreno']").empty();
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formCrear :input[name='terreno']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
          $("#formCrear :input[name='terreno']").selectpicker('refresh');
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

  function listaProductos(){
    //Se cargan los productos
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/productos/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "listaProdcutos"
      },
      success: function(data){
        if (data.success) {
          $("#formCrear :input[name='producto']").empty();
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formCrear :input[name='producto']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
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

  function eliminar(datos){
    Swal.fire({
      title: "¿Estas seguro de cancelar la oferta?",
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
            id: datos['id']
          },
          success: function(data){
            if (data == 1) {
              $("#tabla").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "Se ha cancelado",
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al cancelar"
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

  function checkCertificaciones(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/certificados/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaCertificados"
      },
      success: function(data){
        if (data.success) {
          $('#certificados').empty();
          for (let i = 0; i < data.msj['cantidad_registros']; i++) {
            $('#certificados').append(`
              <div class="col-12 col-lg-6">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="certificado[]" class="custom-control-input" value="${data.msj[i].id}" id="certificado${data.msj[i].id}">
                  <label class="custom-control-label" for="certificado${data.msj[i].id}">${data.msj[i].nombre}</label>
                </div>
              </div>
            `);
          }
        }else{
          Swal.fire({
            icon: 'warning',
            html: data.msj
          })
        }
      },
      error: function(data){
        Swal.fire({
          icon: 'error',
          html: 'No se han enviado los datos'
        })
      }
    });
  }

  function verCosecha(datos){
    cargaDatos = 0;
    $("#formVer :input[name='producto']").val(datos["producto"]);
    $("#formVer :input[name='terreno']").val(datos["finca"]);
    $("#formVer :input[name='fecha_inicio']").val(datos["fecha_inicio"]);
    $("#formVer :input[name='fecha_fin']").val(datos["fecha_final"]);
    $("#formVer :input[name='volumen_total']").val(datos["volumen_total"]);
    $("#formVer :input[name='precio']").val(datos["precio"]);

    //Se trae la lista de certificados que tenga
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/certificados/acciones",
      type: "POST",
      dataType: "json",
      async: false,
      data: {
        accion: "certificadosCosechaUsuario",
        idCosecha: datos['id']
      },
      success: function(data){
        cargaDatos++;
        $('#certificados_cosecha').empty();
        if (data.success) {
          for (let i = 0; i < data.msj['cantidad_registros']; i++) {
            $('#certificados_cosecha').append(`
              <li>${data.msj[i].nombre}</li>
            `);
          }
        }else{
          $('#certificados_cosecha').append(`
            <li>No hay certificados</li>
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

    //Se traen las fotos de la cosecha
    $.ajax({
      url: "acciones",
      type: "POST",
      dataType: "json",
      async: false,
      data: {
        accion: "fotosCosechas",
        idCosecha: datos['id']
      },
      success: function(data){
        cargaDatos++;
        $('#cosechas_fotos').empty();
        if (data.success) {
          for (let i = 0; i < data.msj['cantidad_registros']; i++) {
            $('#cosechas_fotos').append(`
              <div class="col-6">
                <a href="<?php echo($ruta_raiz); ?>${data.msj[i].ruta}" data-lightbox="galeria"><img class="img-thumbnail" src="<?php echo($ruta_raiz); ?>${data.msj[i].ruta}"></a>
              </div>
            `);
          }
        }else{
          $('#cosechas_fotos').append(`
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

    if (cargaDatos == 2) {
      $("#modalVer").modal("show");
    }
  }
</script>
</html>