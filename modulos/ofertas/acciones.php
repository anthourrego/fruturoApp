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

function listaOfertas(){
  global $usuario;
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;
  $inicio = (int)$_POST['inicio'];
  $cantidad = (int)$_POST['cantidad'];
  $buscar = $_REQUEST['buscar'];
  
  // ---------------- filtro de buscar -------------
  $filtrarBuscar = $_REQUEST['buscar'] == '' ? '' : "AND productos.nombre LIKE '%$buscar%'";

  // ---------------- filtros -------------------
  $filtroOrden = $_POST['orden'] == 1 ? 'asc' : 'desc'; 
  $filtroDepartamento = $_POST['departamento'] == -1 ? '' : "departamentos.id = ".$_POST['departamento'];
  $filtroMunicipio = $_POST['municipio'] == -1 ? '' : "municipios.id =".$_POST['municipio'];
  $filtroTipo = $_POST['tipo'] == -1 ? '' : 'fincas.fk_finca_tipo ='.$_POST['tipo'];
  $filtroFruta = $_POST['fruta'] == -1 ? '' : 'cosechas.fk_producto ='.$_POST['fruta'];
  $filtroDerivado = $_POST['derivado'] == -1 ? '' : 'AND cosechas.fk_productos_derivados ='.$_POST['derivado'];

  // ----------------- Conector And ---------------------
  $andMunicipio = $filtroMunicipio == '' ? '' : 'and'; 
  $andFruta = ($filtroFruta != '' && $filtroDepartamento != '') ? 'and' : '';
  $andTipo = ($filtroTipo != '' && ($filtroDepartamento != '' || $filtroMunicipio != '' || $filtroFruta != '' )) ? 'and' : '';

  // ----------------- Conector Where ---------------------

  $and = '';



  if($filtroDepartamento != '' || $filtroMunicipio != '' || $filtroFruta != '' || $filtroTipo!= ''){
    $and = 'AND';
  }



  $datos = $db->consulta("SELECT
  CONCAT( usuarios.nombres ,' ', usuarios.apellidos ) AS nombre,
  productos.nombre AS producto,
  productos_derivados.nombre AS producto_derivado,
  productos.presentacion AS presentacion,
  cosechas.id, 
  cosechas.volumen_total,
  cosechas.unidad_medida,
  cosechas.precio, 
  cosechas.fecha_creacion, 
  cosechas.fecha_inicio, 
  cosechas.fecha_final, 
  cosechas.capacidad_produccion,
  fincas.nombre        AS nombreFinca, 
  departamentos.nombre AS departamento, 
  municipios.nombre    AS municipio,
  (SELECT ruta FROM cosechas_productos_documentos WHERE fk_cosecha = cosechas.id ORDER BY id ASC limit 1) AS foto_cosecha,
  (SELECT ruta FROM cosechas_productos_documentos WHERE fk_producto = productos.id ORDER BY id ASC limit 1) AS foto_producto,
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
          ON municipios.fk_departamento = departamentos.id WHERE cosechas.estado != 0 ".$and." ".$filtroDepartamento." ".$andMunicipio ." ".$filtroMunicipio." ".$andFruta." ".$filtroFruta." ".$andTipo." ".$filtroTipo." " . $filtroDerivado ." " . $filtrarBuscar . "  GROUP BY id ORDER BY cosechas.precio ".$filtroOrden."
  LIMIT ".$inicio.",".$cantidad);

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

function listarDepartamentos(){

  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * from departamentos");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos; 
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
  
}

function listarMunicipios(){

  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $query = "SELECT * from municipios";

  if($_GET['idDepto'] != -1){
    $query .= " where fk_departamento =".$_GET['idDepto'];
  }


  $datos = $db->consulta($query);

  
  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos; 
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
  
}

function listarFrutas(){

  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * from productos WHERE fk_creador = 1 AND estado = 1 AND fk_finca IS NULL");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos; 
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
  
}

function traerDerivados(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * from productos_derivados WHERE fk_producto = ".$_GET['fruta']);

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos; 
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
}

function traerTipoProducto(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * from fincas_tipos WHERE estado = 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos; 
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
}



/*****************************************/

/* function crear(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;
  $resp['success'] = false;

  $datos = array(
    ":fk_producto" => $_POST["producto"],
    ":fk_finca" => $_POST['terreno'],
    ":volumen_total" => $_POST["volumen_total"],
    ":precio" => $_POST["precio"],
    ":fecha_inicio" => date("Y-m-d", strtotime($_POST["fecha_inicio"])),
    ":fecha_final" => date("Y-m-d", strtotime($_POST["fecha_fin"])),
    ":estado" => 1,
    ":fecha_creacion" => date('Y-m-d H:i:s'),
    ":fk_creador" => $usuario['id']

  );

  $id_registro = $db->sentencia("INSERT INTO cosechas (fk_producto, fk_finca, volumen_total, precio, fecha_inicio, fecha_final, estado, fecha_creacion, fk_creador) VALUES (:fk_producto, :fk_finca, :volumen_total, :precio, :fecha_inicio, :fecha_final, :estado, :fecha_creacion, :fk_creador)", $datos);

  if ($id_registro > 0) {
    $db->insertLogs("cosechas", $id_registro, "Se crea la cosecha", $usuario["id"]);

    if (@$_POST["certificado"]) {
      
      foreach ($_POST["certificado"] as $certificado) {
        $datos_certi = array(
          ":fk_cosecha" => $id_registro, 
          ":fk_certificacion" => $certificado, 
          ":fecha_creacion" => date("Y-m-d"), 
          ":fk_creador" => $usuario["id"]
        );
        $id_registro_certi = $db->sentencia("INSERT INTO cosechas_certificaciones (fk_cosecha, fk_certificacion, fecha_creacion, fk_creador) VALUES (:fk_cosecha, :fk_certificacion, :fecha_creacion, :fk_creador)", $datos_certi);

        $db->insertLogs("cosechas_certificaciones", $id_registro_certi, "Se crea la cosecha con el certificado", $usuario["id"]);
      }

    }

    $resp['success'] = true;
    $resp['msj'] = 'Se ha creado correctamente.';
  } else {
    $resp['msj'] = 'Error al realizar el registro.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function eliminar(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE cosechas SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("cosechas", $_POST["id"], "Se inhabilita la oferta", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  global $usuario;
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM terrenos WHERE nombre = :nombre AND estado = 1 AND fk_usuario = :fk_usuario", array(":nombre" => $nombre, ":fk_usuario" => $usuario["id"]));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM terrenos WHERE nombre = :nombre AND id != :id AND estado = 1 AND fk_usuario = :fk_usuario", array(":nombre" => $nombre, ':id' => $id, ":fk_usuario" => $usuario["id"]));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function datos($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $datos = $db->consulta("SELECT * FROM certificaciones WHERE id = :id", array(":id" => $id));

  if ($datos["cantidad_registros"] == 1) {
    $resp = $datos[0];
  }

  $db->desconectar();
  return $resp;
} */


if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}