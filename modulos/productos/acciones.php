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

function lista(){
  $table      = 'productos';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => '`p`.`id`',               'dt' => 'id',             'field' => 'id' ),
              array( 'db' => '`p`.`nombre`',           'dt' => 'nombre',         'field' => 'nombre' ),
              array( 'db' => '`p`.`fecha_creacion`',   'dt' => 'fecha_creacion', 'field' => 'fecha_creacion' ),
              array( 'db' => '`u`.`nombres`',          'dt' => 'creador',        'field' => 'creador',        'as' => 'creador')
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM `{$table}` AS `p` INNER JOIN `usuarios` AS `u` ON `p`.`fk_creador` = `u`.`id`";
  $extraWhere= "`p`.`estado` = 1";
  $groupBy = "";
  $having = "";
  return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
}

function crear(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;
  $resp['success'] = false;

  if (validarNombre($_POST["producto"]) == 0) {
    $datos = array(
      ":nombre" => $_POST["producto"],
      "fecha_creacion" => date('Y-m-d H:i:s'),
      "fk_creador" => $usuario["id"],
      "estado" => 1
    );

    $id_registro = $db->sentencia("INSERT INTO productos (nombre, fecha_creacion, fk_creador, estado) VALUES (:nombre, :fecha_creacion, :fk_creador, :estado)", $datos);

    if ($id_registro > 0) {
      $db->insertLogs("productos", $id_registro, "Se crea el producto {$_POST['producto']}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = 'Se ha creado correctamente.';
    } else {
      $resp['msj'] = 'Error al realizar el registro.';
    }

  }else{
    $resp['msj'] = 'El nombre <b>' . $_REQUEST["producto"] . '</b> ya se encuentra en uso.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM productos WHERE nombre = :nombre", array(":nombre" => $nombre));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM productos WHERE nombre = :nombre AND id != :id", array(":nombre" => $nombre, ':id' => $id));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function eliminar(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE productos SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("productos", $_POST["id"], "Se inhabilita el producto {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function editar(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datosProducto = datosProducto($_POST["id"]);

  if ($datosProducto != 0) {

    if (validarNombre(cadena_db_insertar($_POST['producto']), $_POST["id"]) == 0) {
      # code...
      if ($_POST["producto"] != $datosProducto['nombre']) {
  
        $datosSQL = array(
                      ":nombre" => $_POST["producto"],
                      ":id" => $_POST["id"]
                    );
  
        $db->sentencia("UPDATE productos SET nombre = :nombre WHERE id = :id", $datosSQL);
    
        $db->insertLogs("productos", $_POST["id"], "Se edita el producto {$_POST['producto']}", $usuario["id"]);
    
        $resp["success"] = true;
        $resp["msj"] = "El producto se ha actualizado correctamente";
      } else {
        $resp["success"] = false;
        $resp["msj"] = "Por favor realize algún cambio";
      }
    }else{
      $resp['msj'] = 'El nombre <b>' . $_REQUEST["producto"] . '</b> ya se encuentra en uso.';
    }

  }else{
    $resp["success"] = false;
    $resp["msj"] = "El usuario no es valido";
  }


  $db->desconectar();
  return json_encode($resp);
}

function datosProducto($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $datos = $db->consulta("SELECT * FROM productos WHERE id = :id", array(":id" => $id));

  if ($datos["cantidad_registros"] == 1) {
    $resp = $datos[0];
  }

  $db->desconectar();
  return $resp;
}

function listaProdcutos(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * FROM productos WHERE estado = 1");

  if ($datos['cantidad_registros'] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  }else{
    $resp['msj'] = "No se han encontrado datos";
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