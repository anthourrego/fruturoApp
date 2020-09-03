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

  $session = new Session();
  $lib = new Libreria;

  $usuario = $session->get("usuario");

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <?php  
    echo $lib->jquery();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->sweetAlert2();
    echo $lib->proyecto();
  ?>
</head>
<body class="content-fruturo">
  <section class="content mt-2">
    <div class="container">
      <img class="w-100 mb-4" src="<?php echo($ruta_raiz) ?>assets/img/inicio.png" alt="">
      <div class="alert alert-warning" role="alert">
        <h5 class="text-ledt">Instrucciones:</h5>
        <ol class="mb-0">
          <li>Hola, si quieres empezar a ofrecer tus productos primero debes registrar tu predio, haz click en <b>Registrar Predio</b>.</li>
          <li>Si ya está registrada tu predio, haz click en <b>Ofertar</b> para empezar a ofrecer tus productos.</li>
          <li>Verificar su oferta en <b>Ofertas</b> para revisar que la información sea correcta.</li>
          <li>En el momento en que hayas terminado de ingresar tus predios y cosechas puedes hacer click en <b>Cerrar Sesión</b>.</li>
        </ol>
      </div>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12 col-md-6 col-lg-3 text-center mb-5 mb-lg-0">
          <a class="expandir" href="<?= $ruta_raiz ?>modulos/ofertas">
            <img class="w-90" src="<?php echo($ruta_raiz) ?>assets/img/ofertas.png" alt="">
          </a>
        </div>
        <div class="col-12 col-md-6 col-lg-3 text-center mb-5 mb-lg-0">
          <a class="text-decoration-none expandir" href="<?= $ruta_raiz ?>modulos/ofertar/predios">
            <img class="w-90" src="<?php echo($ruta_raiz) ?>assets/img/registrar_predio.png" alt="">
          </a>
        </div>
        <div class="col-12 col-md-6 col-lg-3 text-center mb-5 mb-lg-0">
          <a id="cosecha" class="text-decoration-none expandir" href="#">
            <img class="w-90" src="<?php echo($ruta_raiz) ?>assets/img/ofertar.png" alt="">
          </a>
        </div>
        <div class="col-12 col-md-6 col-lg-3 text-center mb-5 mb-lg-0">
          <a class="text-decoration-none expandir" href="<?= $ruta_raiz ?>modulos/mensajes">
            <img class="w-90" src="<?php echo($ruta_raiz) ?>assets/img/mensajes.png" alt="">
          </a>
        </div>
        <div class="col-12 text-center">
          <img class="w-100 w-md-80 mb-5" src="<?php echo($ruta_raiz) ?>assets/img/secretaria.png" alt="">
        </div>
        <!-- <div class=" col-12 col-md-6 col-lg-2">
          <a class="small-box text-white d-flex align-items-center justify-content-center justify-content-md-start" style="min-height: 110px; background-color: #6c757d;" href="<?= $ruta_raiz ?>modulos/mensajes">
            <div class="inner">
              <h4 id="mensajes"><i class="far fa-comments d-block d-md-none"></i> Mensajes</h4>
            </div>
            <div class="icon">
              <i class="far fa-comments"></i>
            </div>
          </a>
        </div> -->
      </div>
    </div>
  </section>
</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  $(function(){
    let contFinca = 0;

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
          contFinca = 1
        }else{
          contFinca = 0
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

    //Se cargan los mensajes
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/mensajes/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "contador_mensajes"
      },
      success: function(data){
        if (data.success) {
          if (data.msj[0].mensajes_pendientes > 0) {
            $("#mensajes").append(`<span class="ml-3 badge badge-pill badge-light">${data.msj[0].mensajes_pendientes}</span>`);
          }
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

    $("#cosecha").on("click", function(e){
      e.preventDefault();
      if (contFinca == 1) {
        window.location.href = 'cosechas'; 
      }else{
        Swal.fire({
          icon: 'warning',
          html: 'Para ofertar debes de registrar primero un predio'
        });
      }
    });
  });

</script>
</html>