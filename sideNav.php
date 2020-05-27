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

require_once($ruta_raiz . "clases/funciones_generales.php");
require_once($ruta_raiz . "clases/Conectar.php");
require_once($ruta_raiz . 'clases/Permisos.php');

$session = new Session();

$usuario = $session->get("usuario");

?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
  <!-- Brand Logo -->
  <div class="d-flex justify-content-between">
    <a target="object-contenido" href="<?php echo RUTA_RAIZ ?>modulos/" class="brand-link">
      <img src="assets/img/logo.png" alt="Fruturo Logo" class="brand-image">
      <span class="brand-text font-weight-light">Fruturo</span>
    </a>
    <a class="brand-link text-right mr-3" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" id="modulos" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>