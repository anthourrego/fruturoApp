<?php
@session_start();
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
require($ruta_raiz . "clases/SSP.php");
require($ruta_raiz . "clases/Session.php");

$session = new Session();

$usuario = $session->get("usuario");

function traerDatosOferta(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("  SELECT cosechas.id AS id_cosecha , cosechas.precio, cosechas.volumen_total, 
  cosechas.fecha_inicio, cosechas.fecha_final, cosechas.estado, productos.id as id_producto, productos.nombre AS producto, fincas.nombre AS finca, 
  fincas.direccion AS direccion, departamentos.nombre AS departamento, municipios.nombre AS municipio, 
  usuarios.id AS id_vendedor, usuarios.correo AS correo_vendedor, CONCAT(usuarios.nombres,' ',usuarios.apellidos) as nombre_vendedor, usuarios.telefono, 
  fincas.fk_finca_tipo as tipoFinca

  FROM   cosechas 
  INNER JOIN productos 
    ON cosechas.fk_producto = productos.id
  LEFT JOIN productos_derivados
    ON cosechas.fk_productos_derivados = productos_derivados.id
  INNER JOIN usuarios 
    ON cosechas.fk_creador = usuarios.id 
  INNER JOIN fincas 
    ON cosechas.fk_finca = fincas.id
  INNER JOIN fincas_tipos
    ON fincas.fk_finca_tipo = fincas_tipos.id 
  INNER JOIN municipios 
    ON fincas.fk_municipio = municipios.id 
  INNER JOIN departamentos 
    ON municipios.fk_departamento = departamentos.id where cosechas.id = :id", array(":id" => $_GET["id"]));

  
  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos; 
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);  
}

function datosUsuario(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT 
                          u.correo AS correo, 
                          u.nombres AS nombres, 
                          u.apellidos AS apellidos, 
                          u.telefono AS telefono, 
                          u.nro_documento AS nro_documento,
                          td.abreviacion AS doc_abreviacion,
                          td.nombre AS documento,
                          tp.nombre AS tipo_per,
                          p.nombre AS perfil
                        FROM usuarios AS u 
                          INNER JOIN tipo_documento AS td ON u.fk_tipo_documento = td.id 
                          INNER JOIN tipo_persona AS tp ON tp.id = u.fk_tipo_persona 
                          INNER JOIN perfiles AS p ON p.id = u.fk_perfil 
                        WHERE u.id = :id", 
                        array(":id" => $_REQUEST["idUsuario"]));

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos[0]; 
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
}

function validarUsuario(){
  global $usuario;
  $usuarioOferta = $_POST['idUsuario'];
  $resp["success"] = false;

  if($usuario["id"] == $usuarioOferta){
    $resp["success"] = true;
    $resp['msj'] = 'deshabilitar boton';
  }else{
    $resp['msj'] = 'habilitar boton';
  }
  return json_encode($resp);
}


function eliminar(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE cosechas SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("cosechas", $_POST["id"], "Se cancela la oferta por parte del comprador", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function finalizar(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE cosechas SET estado = 3 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("cosechas", $_POST["id"], "Se finaliza la oferta por parte del comprador", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function fotosCosechas(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  if ($_REQUEST["tipo"] == 1) {
    $datos = $db->consulta("SELECT * FROM cosechas_productos_documentos WHERE fk_cosecha = :fk_cosecha", array(":fk_cosecha" => $_REQUEST["idCosecha"]));
  }else{
    $datos = $db->consulta("SELECT * FROM cosechas_productos_documentos WHERE fk_producto = :fk_producto", array(":fk_producto" => $_REQUEST["idCosecha"]));
  }

  if ($datos['cantidad_registros'] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  }else{
    $resp['msj'] = "No se han encontrado datos";
  }  

  $db->desconectar();

  return json_encode($resp);
}

function traerMensajes(){
  $db = new Bd();
  $db->conectar();

  $mensaje = $db->consulta("SELECT com.mensaje,
    com.fecha_creacion, CONCAT(u.nombres, ' ', u.apellidos) AS nombre,
    com.fk_creador
  FROM cosecha_oferta_mensajes AS com
    INNER JOIN usuarios AS u ON com.fk_creador = u.id
  WHERE com.fk_cosecha_oferta = :oferta", array(":oferta" => $_POST["idOferta"]));
  
  if ($mensaje["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $mensaje;
  }else{
    $resp["msj"] = "No hay mensajes";
  }

  $db->desconectar();

  return json_encode($resp);
}


if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}