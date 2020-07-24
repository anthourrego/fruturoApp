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
  </style>
</head>
<body>

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container">
      <div class="row mb-2">
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
          <div class="col-4 border-right overflow-auto" style="height: 80vh; min-height: 80vh; max-height: 80vh;">
            <div class="list-group" id="listaChats"></div>
          </div>
          <div class="col-8 d-flex flex-column" style="height: 80vh; min-height: 80vh; max-height: 80vh;">
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

            $("#listaChats").append(`
              <a href="#" class="list-group-item list-group-item-action d-flex px-1" onClick='mensajes(${JSON.stringify(data.msj[i])}, "${nombreUsuario}", "${correo}", "${imagen}")'>
                <img class="rounded-circle" width="50px" height="50px" src="<?= $ruta_raiz ?>${imagen}" alt="">
                <div class="ml-2 w-75">
                  <h6 class="mb-1 punticos">${nombreUsuario}</h6>
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
    console.log(datos);
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
        idOferta: datos.idOferta
      },
      success: function(data){
        $("#mensajes").empty();
        if (data.success) {
          for (let i = 0; i < data.msj["cantidad_registros"]; i++) {
            if (data.msj[i].fk_creador == <?php echo($usuario['id']); ?>) {
              $("#mensajes").append(`
                <div class="ml-auto alert alert-warning w-90" role="alert">
                  <p class="font-weight-bold pb-1 border-bottom border-warning text-right">
                    ${data.msj[i].nombre} | <small>${moment(data.msj[i].fecha_creacion).format('DD/MM/YYYY hh:mm a')}</small>
                  </p>
                  ${data.msj[i].mensaje}
                </div>`);
            }else{
              $("#mensajes").append(`
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
            $("#mensajes").scrollTop($("#mensajes")[0].scrollHeight);
          }, 200);

        }else{
          $("#mensajes").append(`<p class="text-center">No hay mensajes</p>`);
        }
        $("#formMensaje :input[name='accion']").val('enviarMensaje');
        if(correo){
          $("#formMensaje :input[name='correo']").val(correo);
        }
        $("#formMensaje :input[name='idCosecha']").val(datos.idOferta);
        $("#formMensaje :input").prop("disabled", false);
        // $("#formMensaje :input[name='asunto']").val(datos.producto+' - '+datos.finca);
       
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