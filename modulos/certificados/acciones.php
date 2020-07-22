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
  $table      = 'certificaciones';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => '`c`.`id`',               'dt' => 'id',             'field' => 'id' ),
              array( 'db' => '`c`.`nombre`',           'dt' => 'nombre',         'field' => 'nombre' ),
              array( 'db' => '`c`.`descripcion`',      'dt' => 'descripcion',    'field' => 'descripcion' ),
              array( 'db' => '`c`.`fecha_creacion`',   'dt' => 'fecha_creacion', 'field' => 'fecha_creacion' ),
              array( 'db' => '`u`.`nombres`',          'dt' => 'creador',        'field' => 'creador',        'as' => 'creador')
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM `{$table}` AS `c` INNER JOIN `usuarios` AS `u` ON `c`.`fk_creador` = `u`.`id`";
  $extraWhere= "`c`.`estado` = ".$_GET['estado'];
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

  if (validarNombre(cadena_db_insertar($_POST["certificado"])) == 0) {
    $datos = array(
      ":nombre" => cadena_db_insertar($_POST["certificado"]),
      ":descripcion" => cadena_db_insertar(@$_POST['descripcion']),
      ":fecha_creacion" => date('Y-m-d H:i:s'),
      ":fk_creador" => $usuario["id"],
      ":estado" => 1
    );

    $id_registro = $db->sentencia("INSERT INTO certificaciones (nombre, descripcion, fecha_creacion, fk_creador, estado) VALUES (:nombre, :descripcion, :fecha_creacion, :fk_creador, :estado)", $datos);

    if ($id_registro > 0) {
      $db->insertLogs("certificaciones", $id_registro, "Se crea el cretificado {$_POST['certificado']}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = 'Se ha creado correctamente.';
    } else {
      $resp['msj'] = 'Error al realizar el registro.';
    }

  }else{
    $resp['msj'] = 'El nombre <b>' . $_REQUEST["certificado"] . '</b> ya se encuentra en uso.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM certificaciones WHERE nombre = :nombre", array(":nombre" => $nombre));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM certificaciones WHERE nombre = :nombre AND id != :id", array(":nombre" => $nombre, ':id' => $id));
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

  $db->sentencia("UPDATE certificaciones SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("certificaciones", $_POST["id"], "Se inhabilita el certificado {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
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
}

function editar(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datos = datos($_POST["id"]);

  if ($datos != 0) {

    if (validarNombre(cadena_db_insertar($_POST['certificado']), $_POST["id"]) == 0) {
      # code...
      if ($_POST["certificado"] != $datos['nombre'] || @$_POST['descripcion'] != $datos['descripcion']) {
  
        $datosSQL = array(
                      ":nombre" => $_POST["certificado"],
                      ":descripcion" => @$_POST['descripcion'],
                      ":id" => $_POST["id"]
                    );
  
        $db->sentencia("UPDATE certificaciones SET nombre = :nombre, descripcion = :descripcion WHERE id = :id", $datosSQL);
    
        $db->insertLogs("certificaciones", $_POST["id"], "Se edita el certificado {$_POST['certificado']}", $usuario["id"]);
    
        $resp["success"] = true;
        $resp["msj"] = "El certificado se ha actualiza correctamente";
      } else {
        $resp["success"] = false;
        $resp["msj"] = "Por favor realize algún cambio";
      }
    }else{
      $resp['msj'] = 'El nombre <b>' . $_REQUEST["certificado"] . '</b> ya se encuentra en uso.';
    }

  }else{
    $resp["success"] = false;
    $resp["msj"] = "El dato no es valido";
  }


  $db->desconectar();
  return json_encode($resp);
}

function listaCertificados(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * FROM certificaciones WHERE estado = 1");

  if ($datos['cantidad_registros'] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  }else{
    $resp['msj'] = "No se han encontrado datos";
  }

  $db->desconectar();
  return json_encode($resp);
}

function certificadosCosechaUsuario(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT c.id, c.nombre FROM cosechas_certificaciones AS cc INNER JOIN certificaciones AS c ON cc.fk_certificacion = c.id WHERE fk_cosecha = :fk_cosecha", array(":fk_cosecha" => $_REQUEST["idCosecha"]));

  if ($datos['cantidad_registros'] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  }else{
    $resp['msj'] = "No se han encontrado datos";
  }  

  $db->desconectar();

  return json_encode($resp);

}

function cambiarEstadoCertificado() {
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $array = array(
    ":id" => $_POST["id"],
    ":estado" => ($_POST["estado"]),
  );

  $db->sentencia("UPDATE certificaciones SET estado = :estado WHERE id = :id", $array);
  $db->insertLogs("certificaciones", $_POST["id"], "Se inhabilita el certificado {$_POST['nombre']}", $usuario["id"]);

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