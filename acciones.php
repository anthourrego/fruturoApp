<?php
header("Access-Control-Allow-Origin:*");
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

require($ruta_raiz . "clases/funciones_generales.php");
require($ruta_raiz . "clases/Conectar.php");
require($ruta_raiz . "clases/Session.php");


function iniciarSesion(){
  $db = new Bd();
  $db->conectar();
  
  

  $db->desconectar();
}

if(@$_REQUEST['accion']){
	if(function_exists($_REQUEST['accion'])){
		echo($_REQUEST['accion']());
	}
}