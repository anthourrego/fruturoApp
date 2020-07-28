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
    echo $lib->bsCustomFileInput();
    echo $lib->chosen();
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
          <h1 class="m-0 text-dark"><i class="far fa-lemon"></i> Ofertar</h1>
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
          <li>Haz click en <b>Crear Oferta</b> e ingresa la Información que allí solicitamos y cuando la hayas completado haz click en <b>Enviar</b>.</li>
          <li>Si necesitas regresar o ya terminaste de ingresar tus productos haz click en <b>Inicio</b>, arriba en esta pagina.</li>
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
                <th scope="col">Precio</th>
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
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear oferta</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="terreno"> Predio o fabrica <span class="text-danger">*</span></label>
              <select class="chosen-select" required name="terreno"></select>
            </div>
            <div class="form-group">
              <label for="producto">Producto <span class="text-danger">*</span></label>
              <select class="chosen-select" data-placeholder="Seleccione un producto" required name="producto">
                <option value="0" disabled selected>Selecione un producto</option>
              </select>
            </div>
            <div id="crear_procesado">
              <div class="form-group">
                <label for="capacidad_produccion">Capacidad de producción</label>
                <input type="text" class="form-control" name="capacidad_produccion" placeholder="Escriba la capacidad de produccion" onKeyPress="return soloNumeros(event)" required>
              </div>
              <div class="form-group">
                <label for="unidad_medida">Unidad de medida</label>
                <input type="text" class="form-control" name="unidad_medida" placeholder="Lb, Kg, Paquete ...,etc" required>
              </div>
              <div class="form-group">
                <label for="capacidad_produccion">Precio o valor</label>
                <input type="tel" name="precio_procesado" class="form-control"  placeholder="Escriba el precio" required>
              </div>
            </div>
            <div id="crear_fresco">
              <div class="form-group">
                <label for="producto_derivado">Producto derivado<span class="text-danger">*</span></label>
                <select class="chosen-select" data-placeholder="Seleccione un producto derivado" required name="producto_derivado">
                  <option value="0" disabled selected>Selecione un producto derivado</option>
                </select>
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
                  <label for="terreno">Finca</label>
                  <input class="form-control" type="text" required name="terreno" placeholder="Escriba el terreno" disabled autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="producto">Producto</label>
                  <input class="form-control" type="text" required name="producto" placeholder="Escriba el producto" disabled autocomplete="off">
                </div>
                <div id="ver_procesado">
                  <div class="form-group">
                    <label for="producto">Capacidad de producción</label>
                    <input class="form-control" type="text" required name="capacidad_produccion" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="producto">Unidad de medida</label>
                    <input class="form-control" type="text" required name="unidad_medida" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="producto">Precio o valor</label>
                    <input class="form-control" type="text" required name="precio_procesado" disabled autocomplete="off">
                  </div>
                </div>
                <div id="ver_fresco">
                  <div class="form-group">
                    <label for="producto">Producto derivado</label>
                    <input class="form-control" type="text" required name="producto_derivado" placeholder="Escriba el producto derivado" disabled autocomplete="off">
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

  <!-- Modal Mensaje -->
  <div class="modal fade" id="modalMensajes" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" style="height: calc(100vh - 60px)">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-comments"></i> Mensajes</h5> 
          <button class="btn btn-primary" onClick="cargarMensajes()"><i class="fas fa-redo-alt"></i> Recargar</button>
        </div>
        <div id="contenidoMensajes" class="modal-body overflow-auto"></div> 
        <div class="modal-footer">
          <form id="formMensaje" class="w-100" action="">
            <input type="hidden" name="accion" value="enviarMensaje">
            <input type="hidden" name="idCosecha">
            <div class="form-group">
              <label for="mensaje">Mensaje:</label>
              <textarea class="form-control" required name="mensaje" rows="3"></textarea>
            </div>
            <div class="w-100 d-flex justify-content-between">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
              <button id="btnCrearMensaje" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
            </div>
          </form>
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
      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear oferta`);
      $("#formCrear :input[name='terreno']").val(0).trigger("chosen:updated");
      //$("#formCrear :input[name='accion']").val('crear');
      $("#formCrear :input[name='producto']").val(0).prop("disabled", true).trigger("chosen:updated");
      $("#formCrear :input[name='capacidad_produccion']").prop("disabled", true);
      $("#formCrear :input[name='unidad_medida']").prop("disabled", true);
      $("#formCrear :input[name='precio_procesado']").prop("disabled", true);
      $("#formCrear :input[name='producto_derivado']").val(0).prop("disabled", true).trigger("chosen:updated");
      $("#formCrear :input[name='fecha_fin']").prop("disabled", true);
      $("#formCrear :input[name='fecha_inicio']").prop("disabled", true);
      $("#formCrear :input[name='volumen_total']").prop("disabled", true);
      $("#formCrear :input[name='precio']").prop("disabled", true);
      $("#formCrear :input[name='fotos[]']").prop("disabled", true);
      $("#formCrear :input[name='certificado[]']").prop("disabled", true);
      $("#crear_fresco").removeClass("d-none");
      $("#crear_procesado").removeClass("d-none");

      $("#modalCrear").modal("show");
    });

    $("#formCrear :input[name='terreno']").on("change", function(){      
      tipo = $("#formCrear :input[name='terreno'] option:selected").data("tipo");

      if (tipo == 1) {
        $("#formCrear :input[name='accion']").val('crear_fresco');
        $("#crear_fresco").removeClass("d-none");        
        $("#crear_procesado").addClass("d-none");
        listaProductos();

        $("#formCrear :input[name='fecha_fin']").prop("disabled", false);
        $("#formCrear :input[name='fecha_inicio']").prop("disabled", false);
        $("#formCrear :input[name='volumen_total']").prop("disabled", false);
        $("#formCrear :input[name='precio']").prop("disabled", false);
        $("#formCrear :input[name='fotos[]']").prop("disabled", false);
        $("#formCrear :input[name='certificado[]']").prop("disabled", false);        
      }else{
        $("#formCrear :input[name='accion']").val('crear_procesado');
        $("#crear_procesado").removeClass("d-none");        
        $("#crear_fresco").addClass("d-none");   
        listaProductos($(this).val());
        
        $("#formCrear :input[name='capacidad_produccion']").prop("disabled", false);
        $("#formCrear :input[name='unidad_medida']").prop("disabled", false);
        $("#formCrear :input[name='precio_procesado']").prop("disabled", false);
      }
    
    });

    $("#formCrear :input[name='producto']").on("change", function(){
      tipo = $("#formCrear :input[name='terreno'] option:selected").data("tipo");
      if (tipo == 1) {
        listaDerivados($(this).val())
      }
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
          error: function(data){
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

    $("#formMensaje").submit(function(event){
      event.preventDefault();
      if($("#formMensaje").valid()){
        $.ajax({
          type: "POST",
          url: "<?php echo($ruta_raiz); ?>modulos/ofertas/acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formMensaje :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrearMensaje').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
            $("#btnCrearMensaje").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              cargarMensajes();
              $("#formMensaje :input[name='mensaje']").val('');
              $("#formMensaje :input").removeClass("is-valid");
              $("#formMensaje :input").removeClass("is-invalid");
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
              html: 'Error al enviar los datos.'
            });
            //Habilitamos el botón
            $('#formMensaje :input').attr("disabled", false);
            $('#btnCrearMensaje').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrearMensaje").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formMensaje :input').attr("disabled", false);
            $('#btnCrearMensaje').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrearMensaje").attr("disabled", false);
          }
        });
      }
    });

    lista();
    listaTerrenos();
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
        { data: "precio" },
        { data: "fecha_creacion" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            let mensaje = '';
            if(oData.estado == 2){
              mensaje = `<button class="btn btn-info ml-2" onClick='mensajes(${JSON.stringify(oData)})'><i class="fas fa-comments"></i> Mensajes</button>`;
            }
            return `<div class="d-flex justify-content-center">
                      <button class="btn btn-primary" onClick='verCosecha(${JSON.stringify(oData)})'><i class="far fa-eye"></i> Ver</button>
                      ${mensaje}
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
      url: '<?php echo($ruta_raiz) ?>modulos/ofertar/predios/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "tusPredios"
      },
      success: function(data){
        if (data.success) {
          let select = $("#formCrear :input[name='terreno']"); 
          select.empty();
          select.append(`
              <option value="0" selected disabled>Seleccione un predio</option>
            `);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            select.append(`
              <option data-tipo="${data.msj[i].fk_finca_tipo}" value="${data.msj[i].id}">${data.msj[i].nombre}</option>
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

  function listaProductos(fk_finca = 0){
    //Se cargan los productos
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/productos/productos/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "listaProdcutos",
        fk_finca: fk_finca
      },
      success: function(data){
        if (data.success) {
          let select = $("#formCrear :input[name='producto']");
          select.empty();
          select.append(`
            <option value="0" selected disabled>Seleccione un producto</option>
          `);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            select.append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }

          select.prop("disabled", false).trigger("chosen:updated");
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

  function listaDerivados(fk_producto){
    //Se cargan los productos
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/productos/derivados/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "listaDerivados",
        fk_producto: fk_producto
      },
      success: function(data){
        if (data.success) {
          let select = $("#formCrear :input[name='producto_derivado']");
          select.empty();
          select.append(`
            <option value="0" selected disabled>Seleccione un producto derivado</option>
          `);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            select.append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }

          select.prop("disabled", false).trigger("chosen:updated");
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

  function verCosecha(datos){
    let idFotos = 0;
    cargaDatos = 0;
    $("#formVer :input[name='producto']").val(datos["producto"]);
    $("#formVer :input[name='terreno']").val(datos["finca"]);
    $("#formVer :input[name='fecha_inicio']").val(datos["fecha_inicio"]);
    $("#formVer :input[name='fecha_fin']").val(datos["fecha_final"]);
    $("#formVer :input[name='volumen_total']").val(datos["volumen_total"]);
    $("#formVer :input[name='precio']").val(datos["precio"]);
    $("#formVer :input[name='precio_procesado']").val(datos["precio"]);
    $("#formVer :input[name='capacidad_produccion']").val(datos["capacidad_produccion"]);
    $("#formVer :input[name='unidad_medida']").val(datos["unidad_medida"]);

    if (datos["fk_finca_tipo"] == 1) {
      $("#ver_fresco").removeClass("d-none");
      $("#ver_procesado").addClass("d-none");
      idFotos = datos['id'];
    } else {
      $("#ver_procesado").removeClass("d-none");
      $("#ver_fresco").addClass("d-none");
      idFotos = datos["fk_producto"];
    }

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
        idCosecha: idFotos,
        tipo: datos["fk_finca_tipo"]
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

  function mensajes(datos){
    $("#formMensaje :input[name='idCosecha']").val(datos["id"]);
    cargarMensajes();
    $("#modalMensajes").modal("show");
  }

  function cargarMensajes(){
    //Se cargan las lista de mnesajes sobre la cosecha
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/ofertas/acciones",
      type: "POST",
      dataType: "json",
      async: false,
      data: {
        accion: "traerMensajes",
        idCosecha: $("#formMensaje :input[name='idCosecha']").val()
      },
      success: function(data){
        $("#contenidoMensajes").empty();
        if (data.success) {
          for (let i = 0; i < data.msj["cantidad_registros"]; i++) {
            if (data.msj[i].fk_creador == <?php echo($usuario['id']); ?>) {
              $("#contenidoMensajes").append(`
                <div class="ml-auto alert alert-warning w-90" role="alert">
                  <p class="font-weight-bold pb-1 border-bottom border-warning text-right">
                    ${data.msj[i].nombres_usu} ${data.msj[i].apellidos_usu} | <small>${data.msj[i].fecha_creacion}</small>
                  </p>
                  ${data.msj[i].mensaje}
                </div>`);
            }else{
              $("#contenidoMensajes").append(`
                <div class="alert alert-info w-90" role="alert">
                  <p class="font-weight-bold pb-1 border-bottom border-info">
                  ${data.msj[i].nombres_usu} ${data.msj[i].apellidos_usu} | <small>${data.msj[i].fecha_creacion}</small>
                  </p>
                  ${data.msj[i].mensaje}
                </div>
              `);
            }
          } 
          setTimeout(() => { 
            $("#contenidoMensajes").scrollTop($("#contenidoMensajes")[0].scrollHeight);
          }, 200);
        }else{
          $("#contenidoMensajes").append(`<p class="text-center">No hay mensajes</p>`);
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
</script>
</html>