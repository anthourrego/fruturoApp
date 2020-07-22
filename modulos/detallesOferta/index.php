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
      echo $lib->infiniteScroll();
    ?>
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
        transition: box-shadow 500ms;
      }

      .card:hover{
        box-shadow: 0 16px 24px 2px rgba(0,0,0,0.14), 0 6px 30px 5px rgba(0,0,0,0.12), 0 8px 10px -5px rgba(0,0,0,0.3);
        color:black;
      }

      .card-img-top{
        height: 250px;
      }

      .spinner {
        margin: 60px auto;
        width: 200px;
        text-align: center;
      }

      .spinner > div {
        width: 30px;
        height: 30px;
        background-color: #333;

        border-radius: 100%;
        display: inline-block;
        -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
        animation: sk-bouncedelay 1.4s infinite ease-in-out both;
      }

      .spinner .bounce1 {
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
      }

      .spinner .bounce2 {
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
      }

      @-webkit-keyframes sk-bouncedelay {
        0%, 80%, 100% { -webkit-transform: scale(0) }
        40% { -webkit-transform: scale(1.0) }
      }

      @keyframes sk-bouncedelay {
        0%, 80%, 100% { 
          -webkit-transform: scale(0);
          transform: scale(0);
        } 40% { 
          -webkit-transform: scale(1.0);
          transform: scale(1.0);
        }
      }

      .filtros{
        position: sticky;
        top: 70px;
      }

      .form-group{
        margin-bottom: 5px;
      }

      /* se oculta scroll horizontal */
      body{
        overflow-x: hidden;
      }

      /* estilos boton filtros mobile */
      .btnFiltrosMobile{
        position: sticky;
        top: 0px;
        z-index: 1;
      }

      .info{
        border: 0.1px solid #ddd;
      }

      .content-header{
        padding: 10px;
      }

      .section{
        margin-bottom: 20px !important;
      }

      .nombreProducto{
        font-size: 35px;
        font-family: inherit;
      }

      .cantidad{
        color: #7b7b80;
      }

      .precio{
        font-size: 30px;
      }

      hr{
        width: 90%;
        border-top: 1px solid gray;
      }

      .row > button{
        height: 50px;
      } 

    </style>
  </head>
  <body class="container-fluid">

    <div class="row no-gutter">
      <!-- fotos de oferta -->
      <div class="text-center col-md-7 col-12">

        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" id="carrousel">
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

      </div>
      <!-- informacion de oferta -->
      <div class="text-center col-md-5 col-12">
        <div  class="content-header col-12 text-center">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-12">
                <h1 class="m-0 text-dark"><i class="fas fa-award"></i> Detalles De Oferta</h1>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- product section -->
        <h4 class="text-left">Producto: </h4>
        <div class="row">
          <div class="col m-auto">
            <div class="row text-left">
              <div class="col-12 " id="producto"></div>
            </div>
            <div class="row text-left">
              <div class="col-12 cantidad"><span id="volumen_total"></span> Kg</div>
            </div>
          </div>
          <div class="col m-auto">
            <div class="row text-left">
              <div class="col-12 ">Precio</div>
            </div>
            <div class="row text-left">
              <div class="col-12">$<span id="precio"></span></div>
            </div>
          </div>     
        </div>
        <hr>
        <!-- location  -->
        <h4 class="text-left">Ubicación: </h4>
        <div class="row">
          <div class="col-6 m-auto">
            <div class="row text-left">
              <span class="col" id="finca"></span>
            </div>
            <div class="row text-left">
              <span class="col" id="direccion"></span>
            </div>
          </div>
          <div class="col-6 m-auto">
            <div class="row text-left">
              <span class="col" id="municipio"></span>
            </div>
            <div class="row text-left">
              <span class="col" id="departamento"></span>
            </div>
          </div>      
      
        </div>
        <hr>
        <!-- vendedor  -->
        <h4 class="text-left">Vendedor: </h4>
        <div class="row">
          <div class="col-12 text-left" id="nombre_vendedor">
          </div>
          <div class="col-12 text-left" id="telefono">
          </div>
        </div>
        <hr>
        <div >
          <button class="btn btn-verdeOscuro w-100">
            Chatear con el vendedor
          </button>
        </div>
        
      </div>
    </div>

  </body>

  <?php 
    echo $lib->cambioPantalla();
  ?>
  <script>

    $(function(){
      traerDatosOferta(getUrl('id'));
      cerrarCargando();

    });

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
  
    function iniciarScroll(){
   
      $(window).on("scroll", function() {
        var scrollHeight = $(document).height();
        var scrollPos = $(window).height() + $(window).scrollTop();
        if ((scrollHeight - scrollPos) / scrollHeight == 0) {             
          listarOfertas();
        }
      });


    }

    function traerDatosOferta(id){
      $.ajax({
        url: "acciones",
        type: "GET",
        dataType: "json",
        async: false,
        data: {
          accion: "traerDatosOferta",
          id
        },
        success: function(data){ 
          if (data.success) {

            const datos = ordenarData(data.msj);
            // console.log(datos['imagenes']);
            const cont = 0;
            $.each(datos['imagenes'], function(key, value){
              $("#carrousel").append(`
                <div class="carousel-item ${cont == 0 ? 'active' : ''}">
                  <img class="d-block w-100" src="<?= $ruta_raiz ?>${value}" alt="">
                </div>
              `)
            })
            // se recorren elementos para setear valor correspondiente :)
            $.each(datos, function(key, value){
              
              if($('#'+key)[0]){
                $('#'+key)[0].innerText = value;
              }
              
            })
            
          }else{
            
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

    function ordenarData(arrayDatos){
      const imagenes = [];
      $.each(arrayDatos, function(key, value){
        if(value.id_cosecha){
          imagenes.push(value.ruta);
        }
      });

      const orderedData = {
        ...arrayDatos[0],
        imagenes
      }

      return orderedData;
    }

  </script>
</html>