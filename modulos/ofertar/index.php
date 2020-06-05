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
        Para realizar una oferta de una cosecha:
        <ol>
          <li>Ingrear a registrar finca.</li>
          <li>Crear o registrar una finca.</li>
          <li>Ingresar a ofertar cosecha.</li>
          <li>Crear una oferta de cosecha del producto deseado.</li>
          <li>Esperar una respuesta de la oferta realizada.</li>
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

  });

</script>
</html>