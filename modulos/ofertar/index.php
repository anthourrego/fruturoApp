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
    echo $lib->proyecto();
  ?>
</head>
<body>
  <section class="content mt-5">
    <div class="container">
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
          <a class="small-box bg-success d-flex align-items-center justify-content-center justify-content-md-start" style="min-height: 110px" href="cosechas">
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
    cerrarCargando();
  })
</script>
</html>