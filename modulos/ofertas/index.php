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

  if ($permisos->validarPermiso($usuario['id'], 'ofertas') == 0) {
    header('Location: ' . $ruta_raiz . 'modulos/');
  }


?>

<!DOCTYPE html>
<html>
  <style>
    hr {
        border-top: 1px solid #007bff;
        width:70%;
    }

    a {color: #000;}

    .card{
        background-color: #FFFFFF;
        padding:0;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius:4px;
        box-shadow: 0 4px 5px 0 rgba(0,0,0,0.14), 0 1px 10px 0 rgba(0,0,0,0.12), 0 2px 4px -1px rgba(0,0,0,0.3);
    }

    .card:hover{
        box-shadow: 0 16px 24px 2px rgba(0,0,0,0.14), 0 6px 30px 5px rgba(0,0,0,0.12), 0 8px 10px -5px rgba(0,0,0,0.3);
        color:black;
    }

    address{
      margin-bottom: 0px;
    }

    #author a{
      color: #fff;
      text-decoration: none;
        
    }
  </style>
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
      echo $lib->lightbox();
      echo $lib->proyecto();
    ?>
  </head>
  <body class="overflow-hidden h-100">

    <!-- Content Header (Page header) -->
    <div div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <h1 class="m-0 text-dark"><i class="fas fa-award"></i> Ofertas</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <!-- <div class="card-header d-flex justify-content-end">
            <button class="btn btn-success btnCrear"><i class="fas fa-plus"></i> Crear finca</button>
          </div> -->
          <!-- /.card-header -->
          <div class="card-body">
            <!-- <table id="tabla" class="table table-bordered table-hover table-sm w-100">
              <div class="input-group mb-3 w-md-25 w-100">
                <select class="custom-select" id="selectEstado" name="status">
                  <option value="1" selected>Activo</option>
                  <option value="2">En Proceso</option>
                  <option value="3">Finalizado</option>
                  <option value="0">Cancelado</option>
                </select>
                <div class="input-group-append">
                  <label class="input-group-text" for="inputGroupSelect02">Estado</label>
                </div>
              </div>
              <thead class="thead-light">
                <tr>
                  <th scope="col">Departamento</th>
                  <th scope="col">Municipio</th>
                  <th scope="col">Persona</th>
                  <th scope="col">Producto</th>
                  <th scope="col">Volumen total</th>
                  <th scope="col">Precio KG</th>
                  <th scope="col">Inicio cosecha</th>
                  <th scope="col">Fin cosecha</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table> -->

            <div class="input-group mb-3 w-md-25 w-100">
              <select class="custom-select" id="selectEstado" name="status">
                <option value="1" selected>Activo</option>
                <option value="2">En Proceso</option>
                <option value="3">Finalizado</option>
                <option value="0">Cancelado</option>
              </select>
              <div class="input-group-append">
                <label class="input-group-text" for="inputGroupSelect02">Estado</label>
              </div>
            </div>
            
            <div id="contenedorOfertas" style="max-height: 415px;" class="cards-container w-100 d-flex flex-wrap overflow-auto">
              <!-- <div class="w-25" style="padding:5px;">
                <div class="card text-center">
                  <img class="card-img-top" src="https://picsum.photos/1900/1080?image=327" alt="Card image cap">
                  <div class="card-body" id="card">
                    <h5 class="card-title">Title</h5>
                  </div>
                  <div class="card-footer text-muted">
                    <div class="row">
                      <div class="col">
                        <a href=""><i class="fas fa-map"></i></a>
                      </div>
                      <div class="col">
                        <a href="mailto:test@test.com"><i class="fas fa-envelope"></i></a>
                      </div>
                      <div class="col">
                        <a href="tel:+123456789"><i class="fas fa-phone"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    
    <!-- Modal Ver Cosecha -->
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
                <a class="nav-item nav-link" id="nav-fotos-tab" data-toggle="tab" href="#nav-fotos" role="tab" aria-controls="nav-fotos" aria-selected="fotos">Fotos</a>
                <a class="nav-item nav-link" id="nav-usuario-tab" data-toggle="tab" href="#nav-usuario" role="tab" aria-controls="nav-usuario" aria-selected="usuario">Usuario</a>
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
              <div class="tab-pane fade" id="nav-usuario" role="tabpanel" aria-labelledby="nav-usuario-tab">
                <form class="mt-3" id="formUsuario" autocomplete="off">
                  <div class="form-group">
                    <label for="producto">Tipo documento</label>
                    <input class="form-control" type="text" required name="tipo_documento" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="producto">Nro Documento</label>
                    <input class="form-control" type="text" required name="nro_documento" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="terreno">Tipo usuario</label>
                    <input class="form-control" type="text" required name="tipo_usuario" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="producto">Nombres</label>
                    <input class="form-control" type="text" required name="nombres" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="terreno">Apellidos</label>
                    <input class="form-control" type="text" required name="apellidos" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="terreno">Correo</label>
                    <input class="form-control" type="text" required name="correo" disabled autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="terreno">Teléfono</label>
                    <input class="form-control" type="text" required name="telefono" disabled autocomplete="off">
                  </div>
                </form> 
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
            <button data-toggle="tooltip" data-placement="top" title="Cargar mensajes" class="btn btn-primary" onClick="cargarMensajes()"><i class="fas fa-redo-alt"></i></button>
          </div>
          <div id="contenidoMensajes" class="modal-body overflow-auto"></div> 
          <div class="modal-footer">
            <form id="formMensaje" class="w-100" action="">
              <input type="hidden" name="accion" value="enviarMensaje">
              <input type="hidden" name="idCosecha">
              <input type="hidden" name="cosechaEstado">
              <input type="hidden" name="correo">
              <input type="hidden" name="nombre_usuario">
              <div class="form-group">
                <label for="mensaje">Mensaje:</label>
                <textarea class="form-control" required name="mensaje" rows="3"></textarea>
              </div>
              <div class="w-100 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                <button id="btnCrear" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
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
              $('#btnCrear').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
              $("#btnCrear").attr("disabled" , true);
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
              $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
              $("#btnCrear").attr("disabled", false);
            },
            complete: function(){
              //Habilitamos el botón
              $('#formMensaje :input').attr("disabled", false);
              $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
              $("#btnCrear").attr("disabled", false);
            }
          });
        }
      });

      $("#selectEstado").change(function () {
        $('#tabla').dataTable().fnDestroy();
        lista();
      });

      cerrarCargando();
      // lista();
      listarOfertas();
    });

    function lista(){
    
      var estado = $("#selectEstado").val();
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
              estado: estado
            },
            complete: function(){
              $('[data-toggle="tooltip"]').tooltip('hide');
              $('[data-toggle="tooltip"]').tooltip();
              cerrarCargando();
            }
        },
        columns: [
          { data: "departamento" },
          { data: "municipio" },
          { data: "nombre"},
          { data: "producto" },
          { data: "volumen_total" },
          { data: "precio" },
          { data: "fecha_inicio" },
          { data: "fecha_final" },
          {
            "render": function (nTd, sData, oData, iRow, iCol) {
              return `<div class="d-flex justify-content-center">
                        <button class="btn btn-primary mx-1" onClick='verCosecha(${JSON.stringify(oData)})' data-toggle="tooltip" data-placement="top" title="Ver"><i class="far fa-eye"></i></button>
                        <button class="btn btn-info mx-1" onClick='mensajes(${JSON.stringify(oData)})' data-toggle="tooltip" data-placement="top" title="Mensajes"><i class="fas fa-comments"></i></button>
                        <button class="btn btn-success mx-1 px-3" onClick='finalizar(${JSON.stringify(oData)})' data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fas fa-dollar-sign"></i></button>
                        <button type="button" class="btn btn-danger btn-sm mx-1 px-3" onClick='eliminar(${JSON.stringify(oData)})' data-toggle="tooltip" data-placement="top" title="Cancelar"><i class="fas fa-trash-alt"></i></button>
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
          'colvis',
          /* {
            // se agrega botón para filtrar por estados;
            text: 'Activos',
            action: function (e, dt, node, config) {
              node[0].innerText = node[0].innerText == 'Activos' ? 'Inactivos' : 'Activos';
              lista()
            }
          } */
        ]
      });
      const select = "<div class='input-group mb-3'><select class='custom-select' id='selectEstado' name='status'><option value='1' selected>Activo</option><option value='0'>Cancelado</option><option value='2'>En Proceso</option><option value='3'>Finalizado</option></select><div class='input-group-append'><label class='input-group-text' for='inputGroupSelect02'>Estado</label></div></div>";
      $("#tabla_wrapper").prepend(select);
    }

    function listarOfertas(){
      $.ajax({
        url: "acciones",
        type: "GET",
        dataType: "json",
        async: false,
        data: {
          accion: "listaOfertas",
          estado: 1
        },
        success: function(data){
          if (data.success) {
            // se guardan ofertas con la data ordenada
            var ofertas = ordenarData(data.msj);
            console.log(ofertas);
            ofertas.forEach(oferta => { 
              $('#contenedorOfertas').append(`
                <div class="w-25" style="padding:5px;">
                <div class="card text-center">
                  <img class="card-img-top" src="https://picsum.photos/1900/1080?image=327" alt="Card image cap">
                  <div class="card-body" id="card">
                    <h5 class="card-title">${oferta.producto+'/'+oferta.volumen_total+'/ $'+oferta.precio}</h5>
                  </div>
                  <div class="card-footer text-muted">
                    <div class="row">
                      <div class="col">
                        <a href=""><i class="fas fa-map"></i></a>
                      </div>
                      <div class="col">
                        <a href="mailto:test@test.com"><i class="fas fa-envelope"></i></a>
                      </div>
                      <div class="col">
                        <a href="tel:+123456789"><i class="fas fa-phone"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              `);
            });

          }else{
            $('#contenedorOfertas').append(`
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
    }

    function verCosecha(datos){
      top.$("#cargando").modal("show");
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
        url: "<?php echo($ruta_raiz); ?>modulos/ofertar/cosechas/acciones",
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

      //Se traen los datos del usuario
      $.ajax({
        url: "<?php echo($ruta_raiz); ?>modulos/ofertas/acciones",
        type: "POST",
        dataType: "json",
        async: false,
        data: {
          accion: "datosUsuario",
          idUsuario: datos['idUsuario']
        },
        success: function(data){
          if (data.success) {
            cargaDatos++;
            $("#formUsuario :input[name='tipo_documento']").val(data.msj["doc_abreviacion"] + ' - ' + data.msj["documento"]);
            $("#formUsuario :input[name='nro_documento']").val(data.msj["nro_documento"]);
            $("#formUsuario :input[name='tipo_usuario']").val(data.msj["tipo_per"]);
            $("#formUsuario :input[name='nombres']").val(data.msj["nombres"]);
            $("#formUsuario :input[name='apellidos']").val(data.msj["apellidos"]);
            $("#formUsuario :input[name='correo']").val(data.msj["correo"]);
            $("#formUsuario :input[name='telefono']").val(data.msj["telefono"]);
          }else{
            $("#formUsuario :input[name='tipo_documento']").val('');
            $("#formUsuario :input[name='nro_documento']").val('');
            $("#formUsuario :input[name='tipo_usuario']").val('');
            $("#formUsuario :input[name='nombres']").val('');
            $("#formUsuario :input[name='apellidos']").val('');
            $("#formUsuario :input[name='correo']").val('');
            $("#formUsuario :input[name='telefono']").val('');
          }
        },
        error: function(data){
          Swal.fire({
            icon: 'error',
            html: 'No se han enviado los datos'
          })
        }
      });

      if (cargaDatos == 3) {
        cerrarCargando();
        $("#modalVer").modal("show");
        
      }
    }

    function mensajes(datos){
      console.log(datos);
      $("#formMensaje :input[name='idCosecha']").val(datos["id"]);
      $("#formMensaje :input[name='cosechaEstado']").val(datos["cosecha_estado"]);
      $("#formMensaje :input[name='correo']").val(datos["correo"]);
      $("#formMensaje :input[name='nombre_usuario']").val(datos["nombre"]);
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

    function finalizar(datos){
      Swal.fire({
        title: "¿Estas seguro de terminar esta compra?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-dollar-sign"></i> Si',
        cancelButtonText: '<i class="fa fa-times"></i> No'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: 'acciones',
            type: 'POST',
            dataType: 'json',
            data: {
              accion: "finalizar", 
              id: datos['id']
            },
            success: function(data){
              if (data == 1) {
                $("#tabla").DataTable().ajax.reload();
                Swal.fire({
                  toast: true,
                  position: 'bottom-end',
                  icon: 'success',
                  title: "Se ha finalizado",
                  showConfirmButton: false,
                  timer: 5000
                });
              }else{
                Swal.fire({
                  icon: 'warning',
                  html: "Error al finalizar la compra"
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

    function ordenarData(arrayOfertas){
      const ofertas = []
      $.each(arrayOfertas, function(item, valor){
        if(valor.id){
          const posicion = ofertas.findIndex( function(el){return el.id == valor.id;});
          if(posicion != -1){
            ofertas[posicion].imagenes.push(valor.ruta)
          }else{
            const objTmp = {
              ...valor,
              imagenes : []
            }
            delete objTmp.ruta;
            objTmp.imagenes.push(valor.ruta);
            ofertas.push(objTmp);
          }
        }
      });
      return ofertas;
    }
  </script>
</html>