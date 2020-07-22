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

  $datos = $db->consulta("SELECT cosechas.id AS id_cosecha , cosechas.precio, cosechas.volumen_total, 
    cosechas.fecha_inicio, cosechas.fecha_final, cosechas.estado, productos.nombre AS producto, fincas.nombre AS finca, 
    fincas.direccion AS direccion, departamentos.nombre AS departamento, municipios.nombre AS municipio, 
    usuarios.id AS id_vendedor, usuarios.correo AS correo_vendedor, CONCAT(usuarios.nombres,' ',usuarios.apellidos) as nombre_vendedor, usuarios.telefono, 
    documentos.ruta FROM COSECHAS INNER JOIN productos on cosechas.fk_producto = productos.id 
    INNER JOIN fincas ON fincas.id = cosechas.fk_finca INNER JOIN usuarios ON 
    cosechas.fk_creador = usuarios.id inner join cosechas_productos_documentos AS documentos
    on documentos.fk_cosecha = cosechas.id INNER JOIN municipios 
    on fincas.fk_municipio = municipios.id INNER JOIN departamentos 
    ON departamentos.id = municipios.fk_departamento where cosechas.id = :id ", array(":id" => $_GET["id"]));

  
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

function enviarMensaje(){
  $db = new Bd();
  $db->conectar();
  global $usuario;
  $resp["success"] = false;

  //Enviamos un correo con la información
  /* $correo = enviarCorreo($_REQUEST["correo"], $_REQUEST["mensaje"], $_REQUEST["nombre_usuario"]);
  if ($correo === true) { */
    $datos = array(
      ":fk_cosecha" => $_REQUEST["idCosecha"], 
      ":mensaje" => cadena_db_insertar($_REQUEST["mensaje"]), 
      ":oferta" => 0, 
      ":fk_creador" => $usuario["id"], 
      ":fecha_creacion" => date("Y-m-d H:i:s")
    );

    $id_registro = $db->sentencia("INSERT INTO cosecha_oferta (fk_cosecha, mensaje, oferta, fk_creador, fecha_creacion) VALUES (:fk_cosecha, :mensaje, :oferta, :fk_creador, :fecha_creacion)", $datos);

    if ($id_registro > 0) {
      if ($usuario["perfil"] == 1 && $_REQUEST["cosechaEstado"] == 1) {
        $db->sentencia("UPDATE cosechas SET estado = 2 WHERE id = :id", array(":id" => $_POST["idCosecha"]));
      }

      $db->insertLogs("cosecha_oferta", $id_registro, "Se crea un oferta o mensaje de la consecha {$_POST['idCosecha']}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = 'Se ha enviado correctamente.';
    } else {
      $resp['msj'] = 'Error al realizar el registro.';
    }
  /* } else {
    $resp['msj'] = $correo;
  } */

  $db->desconectar();

  return json_encode($resp);
}

function traerMensajes(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $mensaje = $db->consulta("SELECT 
                            co.*,
                            u.nombres AS nombres_usu,
                            u.apellidos AS apellidos_usu
                          FROM cosecha_oferta AS co 
                            INNER JOIN usuarios AS u ON u.id = co.fk_creador 
                          WHERE fk_cosecha = :fk_cosecha", 
                          array(":fk_cosecha" => $_REQUEST["idCosecha"]));

  if ($mensaje["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $mensaje;
  }else{
    $resp["msj"] = "No hay mensajes";
  }

  $db->desconectar();

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

if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}