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
  $table      = 'fincas';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'f.id',                    'dt' => 'id',                  'field' => 'id' ),
              array( 'db' => 'f.nombre',                'dt' => 'nombre',              'field' => 'nombre' ),
              array( 'db' => 'f.fk_municipio',          'dt' => 'fk_municipio',        'field' => 'fk_municipio' ),
              array( 'db' => 'm.nombre',                'dt' => 'municipio',           'field' => 'municipio', 'as' => 'municipio' ),
              array( 'db' => 'm.fk_departamento',       'dt' => 'fk_departamento',     'field' => 'fk_departamento' ),
              array( 'db' => 'f.direccion',             'dt' => 'direccion',           'field' => 'direccion' ),
              array( 'db' => 'f.hectareas',             'dt' => 'hectareas',           'field' => 'hectareas' ),
              array( 'db' => 'f.registro_ica',          'dt' => 'registro_ica',        'field' => 'registro_ica' ),
              array( 'db' => 'ft.nombre',               'dt' => 'finca_tipo',          'field' => 'finca_tipo',   'as' => 'finca_tipo'),
              array( 'db' => 'f.fk_finca_tipo',         'dt' => 'fk_finca_tipo',       'field' => 'fk_finca_tipo'),
              array( 'db' => 'f.fecha_creacion',        'dt' => 'fecha_creacion',      'field' => 'fecha_creacion'),
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS f INNER JOIN municipios AS m ON f.fk_municipio = m.id INNER JOIN fincas_tipos AS ft ON ft.id = f.fk_finca_tipo";
  $extraWhere= "f.estado = 1 AND f.fk_usuario = " . $usuario["id"];
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

  if (validarNombre(cadena_db_insertar($_POST["nombre"])) == 0) {

    $datos = array(
      ":nombre" => cadena_db_insertar($_POST["nombre"]),
      ":fk_municipio" => $_POST['municipio'],
      ":direccion" => cadena_db_insertar($_POST["direccion"]),
      ":hectareas" => @$_POST['hectareas'],
      ":registro_ica" => @$_POST['registro_ica'],
      ":fk_usuario" => $usuario["id"],
      ":fecha_creacion" => date('Y-m-d H:i:s'),
      ":estado" => 1,
      ":fk_finca_tipo" => $_REQUEST['tipo_predio'],
    );

    $id_registro = $db->sentencia("INSERT INTO fincas (nombre, fk_municipio, direccion, hectareas, fk_usuario, fecha_creacion, estado, registro_ica, fk_finca_tipo) VALUES (:nombre, :fk_municipio, :direccion, :hectareas, :fk_usuario, :fecha_creacion, :estado, :registro_ica, :fk_finca_tipo)", $datos);

    if ($id_registro > 0) {
      $db->insertLogs("fincas", $id_registro, "Se crea la finca {$_POST['nombre']}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = 'Se ha creado correctamente.';
    } else {
      $resp['msj'] = 'Error al realizar el registro.';
    }
  }else{
    $resp['msj'] = 'El nombre <b>' . $_REQUEST["nombre"] . '</b> ya se encuentra en uso.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  global $usuario;
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM fincas WHERE nombre = :nombre AND estado = 1 AND fk_usuario = :fk_usuario", array(":nombre" => $nombre, ":fk_usuario" => $usuario["id"]));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM fincas WHERE nombre = :nombre AND id != :id AND estado = 1 AND fk_usuario = :fk_usuario", array(":nombre" => $nombre, ':id' => $id, ":fk_usuario" => $usuario["id"]));
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

  $db->sentencia("UPDATE fincas SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("fincas", $_POST["id"], "Se inhabilita la finca {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function tusPredios(){
  $db = new Bd();
  $db->conectar();
  global $usuario;
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * FROM fincas WHERE fk_usuario = :fk_usuario AND estado = 1", array(":fk_usuario" => $usuario["id"]));

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  } else {
    $resp["msj"] = "No tienes ninguna finca registrada, debes de registrar una finca por poder realizar una oferta";
  }
  
  $db->desconectar();

  return json_encode($resp);
}

/*****************************************/


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