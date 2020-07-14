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
<body>
  <section class="content mt-5">
    <div class="container">
      <div class="alert alert-warning" role="alert">
        <ol class="mb-0">
          <li>Hola, Si quieres empezar a ofrecer tus productos primero debes registrar tu finca, haz click en Registrar Finca.</li>
          <li>Si ya está registrada tu finca, haz click en Ofertar Cosecha para empezar a ofrecer tus productos.</li>
          <li>En el momento en que hayas terminado de ingresar tus fincas y cosechas puedes hacer click en Cerrar Sesión.</li>
        </ol>
      </div>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
          <!-- small box -->
          <a class="small-box bg-info d-flex align-items-center justify-content-center justify-content-md-start" style="min-height: 110px" href="fincas">
            <div class="inner">
              <h4>Registrar finca</h4>
            </div>
            <div class="icon">
              <i class="fas fa-mountain"></i>
            </div>
          </a>
        </div>
        <!-- ./col -->
        <div class=" col-12 col-md-6 col-lg-4">
          <!-- small box -->
          <a id="cosecha" class="small-box bg-success d-flex align-items-center justify-content-center justify-content-md-start" style="min-height: 110px" href="#">
            <div class="inner">
              <h4>Ofertar Cosecha</h4>
            </div>
            <div class="icon">
              <i class="far fa-lemon"></i>
            </div>
          </a>
        </div>
        <!-- ./col -->
        <div class=" col-12 col-md-6 col-lg-4">
          <!-- small box -->
          <a id="ofertas" class="small-box text-white d-flex align-items-center justify-content-center justify-content-md-start" style="min-height: 110px; background-color: #efa90c;" href="#">
            <div class="inner">
              <h4>Ver Ofertas</h4>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-cart"></i>
            </div>
          </a>
        </div>
        <!-- ./col -->
        <div class=" col-12 col-md-6 col-lg-4">
          <!-- small box -->
          <a class="small-box bg-danger d-flex align-items-center justify-content-center justify-content-md-start" style="min-height: 110px" href="javascript:top.cerrarSesion();">
            <div class="inner">
              <h4>Cerrar Sesión</h4>
            </div>
            <div class="icon">
            <i class="fas fa-sign-in-alt"></i>
            </div>
          </a>
        </div>
        <!-- ./col -->
      </div>
    </div>
  </div>
</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  $(function(){
    let contFinca = 0;

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

    $("#cosecha").on("click", function(e){
      e.preventDefault();
      if (contFinca == 1) {
        window.location.href = 'cosechas'; 
      }else{
        Swal.fire({
          icon: 'warning',
          html: 'Para ofertar una cosecha debes de registrar primero un terreno'
        });
      }
    });

    $("#ofertas").on("click", function(evento){
      evento.preventDefault();
      
      window.location = window.location.origin+'/fruturoApp/modulos/ofertas/';
      
    });

  });

</script>
</html>