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
      echo $lib->lightbox();
      echo $lib->proyecto();
    ?>
    <style>
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
      
      .carrousel{
        max-height: 75vh;
      }

      .carousel-inner{
        max-height: 430px;
        min-height: 430px;
      }

      .carousel-inner img{
        height: 430px;
        max-height: 430px;
      }

    </style>
  </head>
  <body class="container content-fruturo">
    
    <div  class="content-header col-12 text-left">
      <div class="container">
        <button type="button" class="btn btn-secondary mb-1" onclick="back()">
          <i class="fas fa-arrow-left"></i>
          Volver
        </button>
        <div class="row mb-2">
          <div class="col-12">
            <h1 class="m-0 text-dark"><i class="fas fa-award"></i> Detalles de oferta</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="row no-gutter">
      <!-- fotos de oferta -->
      <div class="text-center col-md-7 col-12 ">

        <div id="carouselControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" id="carrousel">
        </div>
        <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

      </div>
      <!-- informacion de oferta -->
      <div class="text-center col-md-5 col-12 mt-md-0 mt-2">
        <!-- product section -->
        
        <h4 class="text-left">Producto: <small id="tipoProducto"></small></h4>
        <div class="row">
          <div class="col-6 ">
            <div class="row text-left">
              <div class="col" id="producto"></div>
            </div>
            <div class="row text-left">
              <div class="col" id="derivado"></div>
            </div>
            <div class="row text-left">
              <div class="col-12 cantidad d-flex">
                <span id="volumen_total"></span>
                <p class="ml-1"id="unidad_medida"></p>
              </div>
            </div>
            <div class="row text-left" id="frecuencia">
            </div>
          </div>
          <div class="col-6">
            <div class="row text-left">
              <div class="col-12 ">Precio</div>
            </div>
            <div class="row text-left">
              <div class="col-12">$<span id="precio"></span></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 text-left">
            <span id="registro"></span>
          </div>
        </div>
        <div class="row">
          <div class="col-12 text-left">
            <span id="desc"></span>
          </div>
        </div>

        <div class="row mt-2 containerCertificaciones">
          <div class="col text-left">
            Certificaciones:
            <ul id="certificados">
            <ul>
          </div>
        </div>

        <hr>
        <!-- location  -->
        <h4 class="text-left">Ubicación: </h4>
        <div class="row">
          <div class="col-6 m-auto">
            <div class="row text-left">
              <div class="col">
                <div class="row">
                  <small class="col">
                    <b>
                    Predio:
                    </b>
                    <span class=" text-right" id="finca"></span> </small>
                </div>
              </div>
            </div>
            <div class="row text-left">
              <div class="col">
                <div class="row">
                  <small class="col">
                    <b>
                      Dirección:
                    </b>
                  <span class="text-right" id="direccion"></span></small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 m-auto">
            <div class="row text-left">
              <div class="col">
                <div class="row">
                  <small class="col">
                    <b>
                      Municipio:
                    </b>
                    <span class="text-right" id="municipio"></span></small>
                </div>
              </div>
            </div>
            <div class="row text-left">
              <div class="col">
                <div class="row">
                  <small class="col" >
                    <b>
                      Departamento:
                    </b>
                  <span class="text-right" id="departamento"></span></small>
                </div>
              </div>
            </div>
          </div>      
      
        </div>
        <hr>
        <!-- vendedor  -->
        <h4 class="text-left">
          <b>
            Vendedor:
          </b>
        </h4>
        <div class="row">
          <div class="col-12 text-left" id="nombre_vendedor">
          </div>
        </div>
        <hr>
        <div >
          
          <p>
            <button class="btn btn-lg btn-verdeOscuro btn-block" id="btnChat" type="button" data-toggle="collapse" data-target="#collapseContact" aria-expanded="false">
              Contactar con vendedor
            </button>
          </p>
          <div class="collapse row " id="collapseContact" >
            
            <div class="col-12 mb-2">
              <div class="row">
                <small class="col text-left" >
                  <b>
                    Telefono:
                  </b>
                  <span class="text-right" id="telefono"></span>
                </small>
              </div>
            </div>
             
            <div class="col-12 col-md-6 mb-2 mb-md-0">
              <button class="btn px-2 btn-lg text-white btn-block" style="background-color: #6c757d;" data-toggle="modal" data-target="#modalMensajes" >
                <i class="far fa-comments"></i> Chat 
              </button>
            </div>
            
            <div class="col-12 col-md-6" >
              <button class="btn px-2 btn-lg text-white btn-block" style="background-color: #128c7e;" onclick="whatsapp()">
                <i class="fab fa-whatsapp"></i> WhatsApp
              </button>
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
                <form id="formMensaje" class="w-100">
                  <input type="hidden" name="accion" value="enviarMensaje">
                  <input type="hidden" name="idCosecha">
                  <input type="hidden" name="cosecha">
                  <input type="hidden" name="correo">
                  <input type="hidden" name="asunto">
                  <input type="hidden" name="idVendedor">


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

      </div>
    </div>

  </body>

  <?php 
    echo $lib->cambioPantalla();
  ?>
  <script>

    datos = {}         
    $(function(){
      traerDatosOferta(getUrl('id'));
      $('.containerCertificaciones').hide();
      trarCertificados();
      cerrarCargando();

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
              $('#btnCrear').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
              $("#btnCrear").attr("disabled" , true);
            },
            success: function(data){
              if (data.success) {
                if(data['idCosecha']){
                  $("#formMensaje :input[name='idCosecha']").val(data['idCosecha']);
                }
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

    });

    function cargarMensajes(){
      //Se cargan las lista de mnesajes sobre la cosecha
      $.ajax({
        url: "<?php echo($ruta_raiz) ?>modulos/mensajes/acciones",
        type: "POST",
        dataType: "json",
        async: false,
        data: {
          accion: "traerMensajes",
          idOferta: $("#formMensaje :input[name='idCosecha']").val()// getUrl('id')
        },
        success: function(data){
          $("#contenidoMensajes").empty();
          if (data.success) {
            for (let i = 0; i < data.msj["cantidad_registros"]; i++) {
              if (data.msj[i].fk_creador == <?php echo($usuario['id']); ?>) {
                $("#contenidoMensajes").append(`
                  <div class="ml-auto alert alert-warning w-90 text-left" role="alert">
                    <p class="font-weight-bold pb-1 border-bottom border-warning text-right">
                      ${data.msj[i].nombre} | <small>${moment(data.msj[i].fecha_creacion).format('DD/MM/YYYY hh:mm a')}</small>
                    </p>
                    ${data.msj[i].mensaje}
                  </div>`);
              }else{
                $("#contenidoMensajes").append(`
                  <div class="alert alert-info w-90 text-right" role="alert">
                    <p class="font-weight-bold pb-1 border-bottom border-info">
                    ${data.msj[i].nombre} | <small>${moment(data.msj[i].fecha_creacion).format('DD/MM/YYYY hh:mm a')}</small>
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
            
            datos = ordenarData(data.msj);

            // se reemplaza el volumen total por la capacidad de producción para mostrarse en productos procesados
            $("#tipoProducto")[0].innerText = datos.tipoFinca == 1 ? 'Fresco' : 'Procesado ';

            // setear presentacion  de producto
            datos.producto = datos.presentacion ? (datos.producto+' - '+datos.presentacion) : datos.producto;
            // setear Unidad de medida
            datos.unidad_medida = datos.unidad_medida ? datos.unidad_medida : 'Kg';
            // setear Unidad de precio
            datos.precio = datos.precio+' X '+datos.unidad_medida;

            // se setean resgistros
            let registro ;

            if(datos.invima){
              registro = '<b>Registro Invima: </b>'+ datos.invima;
            }else if( datos.ica ){
              registro = '<b>Registro ICA : </b>'+ datos.ica;
            }else{
              registro = '';
            }
            
            $("#registro")[0].innerHTML = registro;
          
            // se setea capacidad de produccion o volumen total
            if(datos.capacidad_produccion){
              datos.volumen_total = datos.capacidad_produccion;
            }


            
            configMensajes(datos);
            validarUsuario(datos.id_vendedor);
            let id = datos.tipoFinca == 1 ? datos.id_cosecha : datos.id_producto;
            
            trerFotos(id,datos.tipoFinca);

            // se recorren elementos para setear valor correspondiente :)
            $.each(datos, function(key, value){
              if($('#'+key)[0]){
                $('#'+key)[0].innerText = value;
              }
            })

            // se setea Frecuencia
            if(datos.frecuencia){
              const obj_frec = {1 : 'Semanal', 2: 'Quincenal', 3: 'Mensual'};

              $("#frecuencia")[0].innerHTML = `
                <span class="col-12">
                  <b>Frecuencia:</b> ${obj_frec[datos.frecuencia]}
                </span>
              `;
            }

            // se setea descripción
            if(datos.descripcion){
              const desc ='<b>Descripción: </b>'+ datos.descripcion;
              $("#desc").append(desc);
            }

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

    function configMensajes(datos){
      $("#formMensaje :input[name='idCosecha']").val(datos["id_cosecha_oferta"]);
      $("#formMensaje :input[name='cosechaEstado']").val(datos["estado"]);
      $("#formMensaje :input[name='correo']").val(datos["correo_vendedor"]);
      $("#formMensaje :input[name='nombre_usuario']").val(datos["nombre_vendedor"]);
      $("#formMensaje :input[name='asunto']").val(datos.producto+' - '+datos.finca);
      $("#formMensaje :input[name='idVendedor']").val(datos.id_vendedor);
      $("#formMensaje :input[name='cosecha']").val(datos.id_cosecha);


      if(datos["id_cosecha_oferta"]){
        cargarMensajes();
      }
    }

    function trerFotos(id, tipoFinca){
      $.ajax({
        url: "acciones",
        type: "POST",
        dataType: "json",
        async: false,
        data: {
          accion: "fotosCosechas",
          idCosecha: id,
          tipo: tipoFinca
        },
        success: function(data){
          if (data.success) {
            let cont = 0;
            $.each(data['msj'], function(key, value){
              if(value.ruta){
                $("#carrousel").append(`
                  <div class="carousel-item ${cont == 0 ? 'active' : ''}">
                    <img class="d-block w-100" src="<?= $ruta_raiz ?>${value.ruta}" alt="">
                  </div>
                `);
                cont ++;
              }
            })

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
    }

    function validarUsuario(idUsuario){
      $.ajax({
        url: "acciones",
        type: "POST",
        dataType: "json",
        async: false,
        data: {
          accion: "validarUsuario",
          idUsuario
        },
        success: function(data){
          if(data.success){
            $('#btnChat').attr('disabled', true);
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

    function trarCertificados(){
      $.ajax({
        url: "<?php echo($ruta_raiz); ?>modulos/certificados/acciones",
        type: "POST",
        dataType: "json",
        async: false,
        data: {
          accion: "certificadosCosechaUsuario",
          idCosecha: getUrl('id')
        },
        success: function(data){
          if (data.success) {
            $('.containerCertificaciones').show();

            for (let i = 0; i < data.msj['cantidad_registros']; i++) {
              $('#certificados').append(`
                <li>${data.msj[i].nombre}</li>
              `);
            }
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
      var url = "<?php echo($ruta_raiz); ?>modulos/ofertas";
      location.href = url;
    }

    function whatsapp(){
      window.open("https://api.whatsapp.com/send?phone=+57"+datos.telefono,'_blank');
    }

  </script>
</html>