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
      echo $lib->chosen();
      echo $lib->proyecto();
      echo $lib->infiniteScroll();
    ?>
    <style>

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
        cursor: pointer;
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

      /* estilos boton filtros mobile */
      .btnFiltrosMobile{
        position: sticky;
        top: 0px;
        z-index: 999;
      }
      
      .punticos{
        text-align:left;
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis;
      }

    </style>
  </head>
  <body class="content-fruturo">
    
  <!-- Content Header (Page header) -->
    <div div class="content-header">
      <div class="container-fluid">
        <button type="button" class="btn btn-secondary mb-1" onclick="back()">
          <i class="fas fa-arrow-left"></i>
          Volver
        </button>
        <div class="row mb-2 mt-2">
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
        <div class="row">
          <div class="col-12 col-md-3 col-xl-2 d-none d-md-block">
            <div class="filtros">
              <div class="form-group" >
                <label for="selectDepartamento">Departamento</label>
                <select class="chosen-select selectDepartamento">
                  <option value="-1" selected>Todos</option>
                </select>
              </div>
              <div class="form-group" >
                <label for="selectMunicipio">Municipio</label>
                <select class="chosen-select selectMunicipio">
                  <option value="-1" selected>Todos</option>
                </select>
              </div>
              <div class="form-group">
                <label for="selectTipo">Tipo</label>
                <select class="chosen-select selectTipo">
                  <option value="-1" selected>Todos</option>
                </select>
              </div>
              <div class="form-group divFruta">
                <label for="selectFruta">Producto</label>
                <select class="chosen-select selectFruta">
                  <option value="-1" selected>Todos</option>
                </select>
              </div>
              <div class="form-group divDerivado">
                <label for="selectDerivado">Derivado</label>
                <select class="form-control selectDerivado">
                  <option value="-1" selected>Todos</option>
                </select>
              </div>
              <div class="form-group" >
                <label for="selectOrden">Precio</label>
                <select class="form-control selectOrden">
                  <option value="1" selected>Ascendente</option>
                  <option value="2">Descendente</option>
                </select>
              </div>
              <button type="button" onClick="resetFiltros()" class="btn btn-primary btn-block">  <i class="fas fa-undo-alt"></i> Reiniciar Filtros</button>
            </div>
          </div>
          <button class="btn btn-secondary col-12 d-block d-md-none btnFiltrosMobile mb-3 mb-md-0"  data-toggle="modal" data-target="#modalFiltros">
            <i class="fas fa-filter"></i> Filtros 
          </button>
          <div class="col-12 col-md-9 col-xl-10">

            <div class="input-group mb-3">
              <input id="buscador" type="text" class="form-control" placeholder="Buscar producto..." aria-label="btn-search" aria-describedby="btn-search">
              <div class="input-group-append">
                <button class="btn btn-secondary" type="button" id="btn-search"><i class="fas fa-search"></i> Buscar</button>
              </div>
            </div>

            <div id="contenedorOfertas" class="row row-cols-1 card-deck row-cols-md-3 row-cols-xl-4"></div>
    
            <div id="spinner-scroll" class="w-100" style="height: 130px; position: fixed; bottom: 0px; background-image: linear-gradient( rgba(0, 0, 0, -96.5), rgba(0, 0, 0, 0.5) );">
              <div  class="spinner" >
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->

    <!-- Modal FIltros Mobile -->
    <div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Filtrar Busqueda</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group" >
              <label for="selectDepartamento">Departamento</label>
              <select class="chosen-select selectDepartamento">
                <option value="-1" selected>Todos</option>
              </select>
            </div>
            <div class="form-group" >
              <label for="selectMunicipio">Municipio</label>
              <select class="chosen-select selectMunicipio">
                <option value="-1" selected>Todos</option>
              </select>
            </div>
            <div class="form-group">
              <label for="selectTipo">Tipo</label>
              <select class="chosen-select selectTipo">
                <option value="-1" selected>Todos</option>
              </select>
            </div>
            <div class="form-group divFruta" >
              <label for="selectFruta">Producto</label>
              <select class="chosen-select selectFruta">
                <option value="-1" selected>Todos</option>
              </select>
            </div>
            <div class="form-group divDerivado" >
              <label for="selectDerivado">Derivado</label>
              <select class="form-control selectDerivado">
                <option value="-1" selected>Todos</option>
              </select>
            </div>
            <div class="form-group" >
              <label for="selectOrden">Precio</label>
              <select class="form-control selectOrden">
                <option value="1" selected>Ascendente</option>
                <option value="2">Descendente</option>
              </select>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" id="btnReiniciarFiltros" class="btn btn-primary">  <i class="fas fa-undo-alt"></i> Reiniciar Filtros</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-times"></i> Cerrar </button>
          </div>
        </div>
      </div>
    </div>
  </body>

  <?php 
    echo $lib->cambioPantalla();
  ?>
  <script>

    // contadores Inciales 
    var inicio = 0;
    var cantidad = 5;
    var terminado = false;

    var orden = $('.selectOrden').val();
    var tipo = $('.selectTipo').val();
    var departamento = $('.selectDepartamento').val();
    var municipio = $('.selectMunicipio').val();
    var fruta =  $(".selectFruta").val();
    var derivado = $(".selectDerivado").val();
    var buscar = $("#btn-search").val();

    $(function(){

      $("#btn-search").on("click", function(){
        reset();
        listarOfertas();
      })
      
      $('#spinner-scroll').hide();
      $(".divFruta").hide();
      $(".divDerivado").hide();

      $(".selectDepartamento").change(function () {
        departamento = $(this).val();
        
        if($(this).val() != -1){
          
          $(".selectMunicipio").attr('disabled',false).trigger("chosen:updated");;
          traerMunicipio($(this).val());
        }else{
          $(".selectMunicipio").val(-1).attr('disabled',true).trigger("chosen:updated");;
          municipio = $(".selectMunicipio").val();

        }
        reset();
        listarOfertas();
      });

      $(".selectOrden").change(function(){
        orden = $(this).val();
        reset();
        listarOfertas();
      });

      $(".selectTipo").change(function(){
        tipo = $(this).val();
        if(tipo == 1){
          $(".divFruta").show();
          $(".selectFruta").val(-1);
          fruta = $(".selectFruta").val();
        }else if( tipo == 2){
          $(".divFruta").hide();
          $(".selectFruta").val(-1);
          fruta = -1;
        }else{
          $(".divFruta").hide();
          $(".selectFruta").val(-1);
          $(".selectDerivado").val(-1);
          derivado = -1;
          fruta = -1;
        }
        reset();
        listarOfertas();
      });

      $(".selectMunicipio").change(function(){
        municipio = $(this).val();
        reset();
        listarOfertas();
      });

      $(".selectFruta").change(function(){
        fruta = $(this).val();
        if(fruta == -1 ){
          $('.selectDerivado').val(-1);
          derivado = $('.selectDerivado').val();
          $('.divDerivado').hide();
          reset();
          listarOfertas();
        }else{
          $('.divDerivado').show();
          $('.selectDerivado').empty();
          $(".selectDerivado").append(`
            <option value="-1" selected>Todos</option>
          `);
          
          listarderivados();
          reset();
          listarOfertas();
        }
        
      });
      
      $(".selectDerivado").change(function(){
        derivado = $(this).val();
        reset();
        listarOfertas();  
      });

      $("#btnReiniciarFiltros").click(function(){
        resetFiltros();
      });

      /* $(".selectOrden, .selectTipo, .selectDepartamento, .selectMunicipio, .selectFruta").change(function () {
        reset();
        listarOfertas();
      }); */

      listarFrutas();
      listarDepartamentos();
      listarOfertas();
      tipoProducto();
      iniciarScroll();
    });

    function listarOfertas(){
      $.ajax({
        url: "acciones",
        type: "POST",
        dataType: "json",
        async: false,
        data: {
          accion: "listaOfertas",
          estado: 1,
          inicio,
          cantidad,
          orden,
          tipo,
          departamento,
          municipio,
          fruta,
          derivado,
          buscar: $("#buscador").val()
        },
        success: function(data){ 
          if (data.success) {
            $('#contenedorOfertas').removeClass('justify-content-center');
            // se guardan ofertas con la data ordenada
            if(inicio > 0){
              $('#spinner-scroll').show();
            }else{
              top.$("#cargando").modal("show");
            }
            var ofertas = ordenarData(data.msj);
            ofertas.forEach(oferta => { 
              inicio++;
              $('#contenedorOfertas').append(`
                <div class="col p-0 text-center mb-0 mb-md-4" id="${'oferta-'+oferta.id}" onclick="verOferta(${oferta.id})">
                  <div class="card">
                    <div class="row no-gutters">
                      <div class="col-4 col-md-12 m-0 text-left m-auto">
                        <img class="card-img-top imagen-oferta" src="<?= $ruta_raiz ?>${oferta.foto_cosecha ?  oferta.foto_cosecha : oferta.foto_producto }" alt="Card image cap">
                      </div>
                      <div class="col-8 col-md-12 px-2">
                        <div class="py-2 oferta">
                          <div class="row">
                            <div class=" col-12 d-flex justify-content-between">
                              <span>${oferta.producto}</span>
                              <span>${oferta.volumen_total ? oferta.volumen_total+' Kg' : (oferta.capacidad_produccion+' '+oferta.unidad_medida) }</span>
                            </div>

                            <div class="col-12 d-flex justify-content-between">
                              ${oferta.presentacion ? oferta.presentacion : '' }
                              ${oferta.producto_derivado ? '<small>' + oferta.producto_derivado + '</small>' : ''}
                              <span>$ ${oferta.precio}</span>
                            </div>
                            <div class="col-12 punticos">
                              <small>${oferta.nombre}</small>
                            </div>                            

                          </div>
                        </div>
                        <div class="text-muted p-1">
                          <div class="d-flex justify-content-between">
                            <div style="font-size: 12px;" class="d-flex flex-column text-left">
                              <span>${oferta.departamento}</span>
                              <span>${oferta.municipio}<span>
                            </div>
                            <div style="font-size: 12px;">${moment(oferta.fecha_creacion).locale('es').format('D [de] MMMM')}</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              `);
            }); 
            setTimeout(() => {
              $('#spinner-scroll').hide();
            }, 1500);

          }else{

            if (inicio == 0) {
              $('#contenedorOfertas').addClass('justify-content-center');
              $('#contenedorOfertas').html('<div class="col-12 text-center w-100">No se ha necontrado resultados</div>');
            }
            
            if(inicio > 0 && !terminado){
              // fin de resultados de la consulta, no se vuelven a traer datos
              terminado = true;
            }
            
          } 
        },
        error: function(data){
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

    function ordenarData(arrayOfertas){
      const ofertas = []
      $.each(arrayOfertas, function(item, valor){
        if(valor.id){
          ofertas.push(valor);
        }
      });
      return ofertas;
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

    function listarDepartamentos(){
      $.ajax({
        url: "acciones",
        type: "GET",
        dataType: "json",
        async: false,
        data: {
          accion: "listarDepartamentos"
        },
        success: function(data){
          if (data.success) {
            for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $(".selectDepartamento").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);}
            if($('.selectDepartamento').val() == -1){
              $('.selectMunicipio').attr('disabled',true).trigger("chosen:updated");
            }

            $(".selectDepartamento, selectDepartamentoModal").trigger("chosen:updated");

          }else{
            console.log('fail: ', data);
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

    function traerMunicipio(idDepto){
      $.ajax({
        url: "acciones",
        type: "GET",
        dataType: "json",
        async: false,
        data: {
          accion: "listarMunicipios",
          idDepto
        },
        success: function(data){
          if (data.success) {
            $(".selectMunicipio").empty();
            $(".selectMunicipio").append(`
              <option value="-1" selected>Todos</option>
            `);

            for (let i = 0; i < data.msj.cantidad_registros; i++) {
              $(".selectMunicipio").append(`
                <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
              `);
            }

            $(".selectMunicipio").trigger("chosen:updated");

          }else{
            console.log('fail: ', data);
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

    // funcion para resetear ofertas cargadas y contador
    function reset(){
      inicio = 0; terminado = false;
      $('#contenedorOfertas').empty();
    }

    // resetear filtros
    function resetFiltros(){
      $('.selectDepartamento').val(-1);
      departamento = -1;
      $('.selectMunicipio').val(-1);
      municipio = -1;
      $('.selectFruta').val(-1);
      fruta = -1;
      $('.selectTipo').val(-1);
      tipo = -1;
      $('.selectOrden').val(1);
      orden = 1; 
      $('#buscador').val('');
      reset();
      listarOfertas();
      
    }
    
    // traer frutas
    function listarFrutas(){
      $.ajax({
        url: "acciones",
        type: "GET",
        dataType: "json",
        async: false,
        data: {
          accion: "listarFrutas"
        },
        success: function(data){
          if (data.success) {
            for (let i = 0; i < data.msj.cantidad_registros; i++) {
              $(".selectFruta").append(`
                <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
              `);
            }

            $(".selectFruta").trigger("chosen:updated");
          }else{
            console.log('fail: ', data);
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

    function listarderivados(){
      $.ajax({
        url: "acciones",
        type: "GET",
        dataType: "json",
        async: false,
        data: {
          accion: "traerDerivados",
          fruta
        },
        success: function(data){
          if (data.success) {
            for (let i = 0; i < data.msj.cantidad_registros; i++) {
              $(".selectDerivado").append(`
                <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
              `);
            }

            $(".selectDerivado").trigger("chosen:updated");
          }else{
            console.log('fail: ', data);
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

    function tipoProducto(){
      $.ajax({
        url: "acciones",
        type: "GET",
        dataType: "json",
        async: false,
        data: {
          accion: "traerTipoProducto",
          fruta
        },
        success: function(data){
          if (data.success) {
            for (let i = 0; i < data.msj.cantidad_registros; i++) {
              $(".selectTipo").append(`
                <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
              `);
            }

            $(".selectTipo").trigger("chosen:updated");
          }else{
            console.log('fail: ', data);
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

    function verOferta(idOferta){
      var url = "<?php echo($ruta_raiz); ?>modulos/detallesOferta?id="+idOferta;
      location.href = url;
    }

    function back(){
      var url = "<?php echo($ruta_raiz); ?>modulos/ofertar";
      location.href = url;
    }
    
  </script>
</html>