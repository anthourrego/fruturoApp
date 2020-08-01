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
    echo $lib->proyecto();
  ?>
  <style>
    .punticos{
      white-space: nowrap; 
      overflow: hidden; 
      text-overflow: ellipsis;
    }

    .notify-badge{
     
      position: absolute;
      right: -2px;
      top: -3px;
      background: red;
      text-align: center;
      border-radius: 30px 30px 30px 30px;
      color: white;
      padding: 1px 6px;
      font-size: 14px;

    }
          
  </style>
</head>
<body class="content-fruturo">

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container">
      <button type="button" class="btn btn-secondary mb-1" onclick="back()">
        <i class="fas fa-arrow-left"></i>
        Volver
      </button>
      <div class="row mt-3 mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="far fa-comments"></i> Mensajes</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container">
      <div class="card">
        <!-- /.card-header -->
        <div class="card-body row">
          <div class="col-12 col-md-4 border-right overflow-auto" style="height: 70vh; min-height: 70vh; max-height: 70vh;">
            <div class="list-group" id="listaChats"></div>
          </div>
          <div class="col-12 col-md-8 d-none d-md-flex flex-column" style="height: 70vh; min-height: 70vh; max-height: 70vh;">
            <a href="#" id="urlProducto" class="list-group-item list-group-item-action list-group-item-light d-none px-1">
              <img class="rounded-circle" id="fotoProducto" width="50px" height="50px" src="" alt="">
              <div class="ml-2 w-80">
                <h6 class="mb-1 punticos" id="producto"></h6>
                <p class="mb-1 punticos" id="nombreUsuario"></p>
              </div>
            </a>
            <div id="mensajes" class="overflow-auto w-100 my-1">
            </div>
            <form id="formMensaje" class="w-100 mt-auto" action="">
              <input type="hidden" required name="accion" value="enviarMensaje">
              <input type="hidden" required name="idCosecha">
              <input type="hidden" required name="correo">
              <input type="hidden" required name="asunto">

              <div class="input-group">
                <textarea class="form-control" aria-describedby="btnCrearMensaje" disabled required name="mensaje" placeholder="Escribe un mensaje" rows="3"></textarea>
                <div class="input-group-prepend">
                  <button id="btnCrearMensaje" type="submit" class="btn btn-primary rounded-right" disabled><i class="fas fa-paper-plane"></i> Enviar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <!-- Modal Mensaje -->
  <div class="modal fade" id="modalMensajes" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" style="height: calc(100vh - 60px)">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-comments"></i> Mensajes</h5>
          <button data-toggle="tooltip" data-placement="top" title="Cargar mensajes" class="btn btn-primary" onClick="cargarMensajes()"><i class="fas fa-redo-alt"></i></button>
        </div>
        <div id="contenidoMensajesModal" class="modal-body overflow-auto"></div> 
        <div class="modal-footer">
          <form id="formMensajeModal" class="w-100">
            <input type="hidden" required name="accion" value="enviarMensaje">
            <input type="hidden" required name="idCosecha">
            <input type="hidden" required name="correo">
            <input type="hidden" required name="asunto">

            <div class="form-group text-left">
              <label for="mensaje">Mensaje:</label>
              <textarea class="form-control" placeholder="Escriba un mensaje..." required name="mensaje" rows="3"></textarea>
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
    cargarChats();

    $("#formMensaje").submit(function(event){
      event.preventDefault();
      if($("#formMensaje").valid()){
        $.ajax({
          type: "POST",
          url: "<?php echo($ruta_raiz); ?>modulos/mensajes/acciones",
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
              mensajes({idOferta :$("#formMensaje :input[name='idCosecha']").val()});
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

    $("#formMensajeModal").submit(function(event){
      event.preventDefault();
      if($("#formMensajeModal").valid()){
        $.ajax({
          type: "POST",
          url: "<?php echo($ruta_raiz); ?>modulos/mensajes/acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formMensajeModal :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrear').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
            $("#btnCrear").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              mensajes({idOferta :$("#formMensajeModal :input[name='idCosecha']").val()});
              $("#formMensajeModal :input[name='mensaje']").val('');
              $("#formMensajeModal :input").removeClass("is-valid");
              $("#formMensajeModal :input").removeClass("is-invalid");
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
            $('#formMensajeModal :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrear").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formMensajeModal :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrear").attr("disabled", false);
          }
        });
      }
    });
  });

  function cargarChats(){
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/mensajes/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "listaChats"
      },
      success: function(data){
        if (data.success) {
          $("#listaChats").empty();
          for (let i = 0; i < data.msj["cantidad_registros"]; i++) {
            if (data.msj[i].foto_producto != null) {
              imagen = data.msj[i].foto_producto;
            } else {
              imagen = data.msj[i].foto_cosecha;
            }

            nombreUsuario = "N/A";
            correo = "";
            
            if (data.msj[i].idComprador == <?= $usuario['id'] ?>) {
              nombreUsuario = data.msj[i].nombreVendedor;
              correo = data.msj[i].correoVendedor;
            }else{
              nombreUsuario = data.msj[i].nombreComprador;
              correo = data.msj[i].correoComprador;
            }

            let user_actual = <?= $usuario['id'] ?>;

            $("#listaChats").append(`
              <a href="#" class="list-group-item list-group-item-action d-flex px-1" onClick='mensajes(${JSON.stringify(data.msj[i])}, "${nombreUsuario}", "${correo}", "${imagen}")'>
                <span class="${(data.msj[i].leido == 0 && data.msj[i].ultimo_emisor != user_actual) ? 'notify-badge' : 'd-none' }">•</span>
                <img class="rounded-circle" width="50px" height="50px" src="<?= $ruta_raiz ?>${imagen}" alt="">
                <div class="ml-2 w-75">
                  <h6 class="mb-1 ">${nombreUsuario}</h6>
                  <p class="mb-1 punticos">${data.msj[i].producto}</p>
                </div>
              </a>
            `);
          }
        }else{
          console.log(data.msj);
        }
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han enviado los datos'
        })
      },
      complete: function(){
        cerrarCargando();
      }
    });
  }

  function mensajes(datos, nombre, correo, imagen){
    $("#urlProducto").removeClass("d-none");
    $("#urlProducto").addClass("d-flex");
    if(datos.idOferta){
      $("#urlProducto").attr('href', "<?= $ruta_raiz ?>modulos/detallesOferta?id=" + datos.idOferta);
      $("#fotoProducto").attr('src', '<?= $ruta_raiz ?>' + imagen);
      $("#producto").html(datos.producto);
      $("#nombreUsuario").html(nombre);
    }
    
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/mensajes/acciones",
      type: "POST",
      dataType: "json",
      async: false,
      data: {
        accion: "traerMensajes",
        idOferta: datos.idOferta,
        ultimo_emisor: datos.ultimo_emisor
      },
      success: function(data){
        $("#mensajes, #contenidoMensajesModal").empty();
        cargarChats();
        if (data.success) {
          for (let i = 0; i < data.msj["cantidad_registros"]; i++) {
            if (data.msj[i].fk_creador == <?php echo($usuario['id']); ?>) {
              $("#mensajes, #contenidoMensajesModal").append(`
                <div class="ml-auto alert alert-warning w-90" role="alert">
                  <p class="font-weight-bold pb-1 border-bottom border-warning text-right">
                    ${data.msj[i].nombre} | <small>${moment(data.msj[i].fecha_creacion).format('DD/MM/YYYY hh:mm a')}</small>
                  </p>
                  ${data.msj[i].mensaje}
                </div>`);
            }else{
              $("#mensajes, #contenidoMensajesModal").append(`
                <div class="alert alert-info w-90" role="alert">
                  <p class="font-weight-bold pb-1 border-bottom border-info">
                  ${data.msj[i].nombre} | <small>${moment(data.msj[i].fecha_creacion).format('DD/MM/YYYY hh:mm a')}</small>
                  </p>
                  ${data.msj[i].mensaje}
                </div>
              `);
            }
          }
          setTimeout(() => { 
            $("#mensajes, #contenidoMensajesModal").scrollTop($("#mensajes")[0].scrollHeight);
          }, 200);

        }else{
          $("#mensajes, #contenidoMensajesModal").append(`<p class="text-center">No hay mensajes</p>`);
        }
        $("#formMensaje :input[name='accion'], #formMensajeModal :input[name='accion']").val('enviarMensaje');
        if(correo){
          $("#formMensaje :input[name='correo'], #formMensajeModal :input[name='correo']").val(correo);
        }
        if(datos.producto){
          $("#formMensaje :input[name='asunto'], #formMensajeModal :input[name='asunto']").val(datos.producto+' - '+datos.finca);
        }

        $("#formMensaje :input[name='idCosecha'], #formMensajeModal :input[name='idCosecha']").val(datos.idOferta);
        $("#formMensaje :input, #formMensajeModal :input").prop("disabled", false);
        
        if (screen.width < 768){
          $("#modalMensajes").modal("show");
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

  function back(){
    var url = "<?php echo($ruta_raiz); ?>modulos/ofertar";
    location.href = url;
  }
</script>
</html>