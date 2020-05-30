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

function departamentos(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $departamentos = $db->consulta("SELECT * FROM departamentos");

  if ($departamentos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $departamentos;
  } else {
    $resp["msj"] = "No se han encontrado datos";
  }
  
  $db->desconectar();

  return json_encode($resp);
}

function municipios(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $municipios = $db->consulta("SELECT * FROM municipios WHERE fk_departamento = :fk_departamento", array(":fk_departamento" => $_POST["departamento"]));

  if ($municipios["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $municipios;
  } else {
    $resp["msj"] = "No se han encontrado datos";
  }
  
  $db->desconectar();

  return json_encode($resp);
}

function lista(){
  global $usuario;
  $table      = 'cosechas';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => '`c`.`id`',                    'dt' => 'id',              'field' => 'id' ),
              array( 'db' => '`p`.`nombre`',                'dt' => 'producto',        'field' => 'producto',       'as' => 'producto' ),
              array( 'db' => '`f`.`nombre`',                'dt' => 'finca',           'field' => 'finca',          'as' => 'finca'),
              array( 'db' => '`c`.`volumen_total`',         'dt' => 'volumen_total',   'field' => 'volumen_total' ),
              array( 'db' => '`c`.`precio`',                'dt' => 'precio',          'field' => 'precio'),
              array( 'db' => '`c`.`fecha_inicio`',          'dt' => 'fecha_inicio',    'field' => 'fecha_inicio'),
              array( 'db' => '`c`.`fecha_final`',           'dt' => 'fecha_final',     'field' => 'fecha_final'),
              array( 'db' => '`c`.`fecha_creacion`',        'dt' => 'fecha_creacion',  'field' => 'fecha_creacion')
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM `{$table}` AS `c` INNER JOIN `productos` AS `p` ON `c`.`fk_producto` = `p`.id INNER JOIN `fincas` AS `f` ON `c`.`fk_finca` = `f`.`id`";
  $extraWhere= "`c`.`estado` = 1 AND `c`.`fk_creador` = " . $usuario["id"];
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

/*****************************************/

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

if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}