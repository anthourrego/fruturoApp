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

  require_once($ruta_raiz . 'clases/librerias.php');
  require_once($ruta_raiz . 'clases/sessionActiva.php');
  
  $usuario = $session->get("usuario");
  
  $lib = new Libreria;

  $ruta_inical = 'modulos/';

  if ($usuario["perfil"] != 1) {
    $ruta_inical = 'modulos/ofertar';
  }
?>

<!doctype html>
<html lang="es">
<head>
  <?php  
    echo $lib->metaTagsRequired();
    echo $lib->iconoPag();
  ?>  
  <title>Consumer Electronics Group S.A.S</title>

  <?php  
    echo $lib->jquery();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->alertify();
    echo $lib->proyecto();
    echo $lib->sweetAlert2();
    echo $lib->overlayScrollbars();
    
  ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <?php 
    if($usuario["perfil"] == 1){ 
      include_once($ruta_raiz . 'sideNav.php');
    }
  ?>

  <div class="wrapper">
    <!-- Navbar -->
    <nav class="<?php if($usuario["perfil"] == 1){ echo('main-header');}?> navbar navbar-expand navbar-white navbar-light elevation-1"> 
      <?php 
        if($usuario["perfil"] != 1){
      ?>
      <div class="container">
        <a class="navbar-brand brand-link" href="<?php echo RUTA_RAIZ ?>modulos/ofertar">
          <img src="<?php echo $ruta_raiz; ?>assets/img/logo.png" class="brand-image">
        </a>
      <?php
        }else{
      ?>
      <!-- Left navbar links -->
      <ul class="navbar-nav d-block d-lg-none">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <?php
        }
      ?>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="rounded-circle" width="30px" src="<?php echo(RUTA_ALMACENAMIENTO . "usuarios/0.png"); ?>">
          </a>  
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <span class="dropdown-item-text text-center"><?php echo $usuario['nombre'] ?></span>
            <a class="dropdown-item modal-link" href="<?php echo RUTA_RAIZ ?>modulos/configuracion/usuarios/editar_perfil"><i class="fas fa-user-edit"></i> Perfil</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" onclick="top.cerrarSesion();"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
          </div>
        </li>
      </ul>
      <?php 
        if($usuario["perfil"] != 1){
      ?>
        </div>
      <?php
        }
      ?>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="<?php if($usuario["perfil"] == 1){ echo('content-wrapper');}?>" style="height: calc(100vh - 57px);">
      <object type="text/html" id="object-contenido" name="object-contenido" data="" style="height: calc(100vh - 57px);" class="w-100 border-0"></object>
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- ./wrapper -->

  <!-- Modal de Cargando -->
  <div class="modal fade modal-cargando" id="cargando" tabindex="1" role="dialog" aria-labelledby="cargandoTitle" aria-hidden="true" data-keyboard="false" data-focus="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="box-loading">
        <div class="loader">
          <div class="loader-1">
            <div class="loader-2">
            </div>
          </div>
        </div>
        <div>
          <img class="w-50" src="<?php echo($ruta_raiz); ?>assets/img/logo.png" alt="">
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Sesion Cerrada -->
  <div class="modal fade" id="cerrarSession" tabindex="-1" role="dialog" aria-labelledby="cerrarSessionTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body text-center">
          <i class="fas fa-exclamation fa-7x text-warning mt-3 mb-3"></i>
          <h2>Lo sentimos, la sesión ha caducado</h2>
          Favor ingresar nuevamente, Gracias.
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <a class="btn btn-primary" href="<?php echo $ruta_raiz; ?>">Cerrar <i class="fas fa-sign-out-alt"></i></a>
        </div>
      </div>
    </div>
  </div>
</body>

<script type="text/javascript">
  /* $("#cargando").modal("show"); */
  var idleTime = 0; 
  $(function(){
    //Tiempo en que valida la session
    window.idleInterval = setInterval(validarSession, 600000); // 10 minute 

    modulosUsuarios();
    
    if (localStorage.url<?php echo(PROYECTO) ?> == null) {
      $("#object-contenido").attr("data", '<?php echo($ruta_inical); ?>');
    }else{
      $("#object-contenido").attr("data", localStorage.url<?php echo(PROYECTO) ?>);
    }
    
    if (localStorage.moduloActual<?php echo(PROYECTO) ?> != null) {
      $(".link").removeClass("active");
      $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).addClass("active");
      /* $(".modulo" + $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).data("modulopadre")).addClass("active"); */
      $('#tituloPagina').html(localStorage.moduloActual<?php echo(PROYECTO) ?> + ' | LavaSecoExprex');
    }

    $(document).on("click", ".link", function(){
      let moduloPadre = $(this).data('modulopadre');
      $(".link").removeClass("active");
      $(this).addClass("active");
      localStorage.moduloActual<?php echo(PROYECTO) ?> = $(this).data('modulo');
      $('#tituloPagina').html($(this).data('modulo') + ' | LavaSecoExprex');
      if (moduloPadre != 0) {
        $(".modulo" + $(this).data("modulopadre")).addClass("active");
      }
    });
  });

  function validarSession(){
    $.ajax({
      type: 'POST',
      url: "<?php echo $ruta_raiz ?>acciones",
      data: {accion: "sessionActiva"},
      success: function(data){
        if (data == 0) {
          localStorage.removeItem("url<?php echo(PROYECTO) ?>");
          $("#cerrarSession").modal("show");
        }
      },
      error: function(data){
        alertify.error("No se ha podido validar la session");
      }
    });
  }

  function modulosUsuarios(){
    $.ajax({
      type: 'POST',
      url: "<?php echo $ruta_raiz ?>modulos/modulos/acciones",
      data: {
        accion: "modulosUsuario"
      },
      success: function(data){
        data = JSON.parse(data);
        if (data.success) {
          $("#modulos").empty();
          $("#modulos").append(`
            <li class="nav-item has-treeview">
              <a href="<?php $ruta_raiz ?>modulos/ofertar" data-modulopadre="0" data-modulo="Ofertar" target="object-contenido" class="nav-link link moduloOfertar">
                <i class="nav-icon fas fa-receipt"></i>
                <p>Ofertar</p>
              </a>
            </li>
          `);
          cargarMenu(data.msj);
        }
      },
      error: function(data){
        Swal.fire({
          icon: 'error',
          html: 'No se ha podido validar los modulos'
        });
      },
      complete: function(){
        if (localStorage.moduloActual<?php echo(PROYECTO) ?> != null) {
          $(".link").removeClass("active");
          $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).addClass("active");
          /* $(".modulo" + $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).data("modulopadre")).addClass("active"); */
          $('#tituloPagina').html(localStorage.moduloActual<?php echo(PROYECTO) ?> + ' | LavaSecoExprex');
        }
      }
    });
  }

  function cargarMenu(data, nivel = 0, moduloPadre = '0'){
    let modH = '';
    let moduloPadre2 = moduloPadre;
    for (let i = 0; i < data.length; i++) {
      if (typeof data[i].hijos !== 'undefined') {
        if(nivel == 0){
          moduloPadre = data[i].tag;
        }
        modH += `
          <li class="nav-item has-treeview">
            <a href="<?php $ruta_raiz ?>modulos/${data[i].ruta}" data-modulopadre="${moduloPadre}" data-modulo="${data[i].tag}" target="object-contenido" class="nav-link link modulo${data[i].tag}">
              <i class="nav-icon ${data[i].icono}"></i>
              <p>
                ${data[i].tag}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
        `;

        modH += cargarMenu(data[i].hijos, (nivel+1), moduloPadre);

        modH += `
            </ul>
          </li>
        `;
      }else{
        modH +=`
          <li class="nav-item">
            <a href="<?php $ruta_raiz ?>modulos/${data[i].ruta}" data-modulopadre="${moduloPadre2}" data-modulo="${data[i].tag}" target="object-contenido" class="nav-link link modulo${data[i].tag}">
              <i class="nav-icon ${data[i].icono}"></i>
              <p>${data[i].tag}</p>
            </a>
          </li>
        `;
      }

    }
    if(nivel === 0){
      $("#modulos").append(modH);
    }else{
      return modH;
    }
  }

  function cerrarSesion(){
    Swal.fire({
      title: '¿Estas seguro de cerrar sesión?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.value) {
        localStorage.removeItem("url<?php echo(PROYECTO) ?>");
        localStorage.removeItem('moduloActual<?php echo(PROYECTO) ?>');
        window.location.href='<?php echo $ruta_raiz ?>clases/sessionCerrar';
      }
    });
  }
</script>
</html>