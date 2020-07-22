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
  $table      = 'fincas_tipos';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'ft.id',               'dt' => 'id',             'field' => 'id' ),
              array( 'db' => 'ft.nombre',           'dt' => 'nombre',         'field' => 'nombre' ),
              array( 'db' => 'ft.estado',           'dt' => 'estado',         'field' => 'estado' ),
              array( 'db' => 'ft.fecha_creacion',   'dt' => 'fecha_creacion', 'field' => 'fecha_creacion' ),
              array( 'db' => 'u.nombres',          'dt' => 'creador',        'field' => 'creador',        'as' => 'creador')
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS ft INNER JOIN usuarios AS u ON ft.fk_creador = u.id";
  $extraWhere= "ft.estado = " . $_GET['estado'];
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

  if (validarNombre(trim($_POST["nombre"])) == 0) {
    $datos = array(
      ":nombre" => trim($_POST["nombre"]), 
      ":fecha_creacion" => date('Y-m-d H:i:s'), 
      ":estado" => 1, 
      ":fk_creador" => $usuario["id"]
    );

    $id_registro = $db->sentencia("INSERT INTO fincas_tipos (nombre, fecha_creacion, estado, fk_creador) VALUES (:nombre, :fecha_creacion, :estado, :fk_creador)", $datos);

    if ($id_registro > 0) {
      $db->insertLogs("fincas_tipos", $id_registro, "Se crea el predio tipo {$_POST['nombre']}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = "Se ha creado correctamente {$_POST['nombre']}.";
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
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM fincas_tipos WHERE nombre = :nombre", array(":nombre" => $nombre));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM fincas_tipos WHERE nombre = :nombre AND id != :id", array(":nombre" => $nombre, ':id' => $id));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function editar(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datosActuales = datos($_POST["id"]);

  if ($datosActuales != 0) {
    if (validarNombre(cadena_db_insertar($_POST['nombre']), $_POST["id"]) == 0) {
      if ($_POST["nombre"] != $datosActuales['nombre']) {
  
        $datosSQL = array(
            ":nombre" => $_POST["nombre"],
            ":id" => $_POST["id"]
          );
  
        $db->sentencia("UPDATE fincas_tipos SET nombre = :nombre WHERE id = :id", $datosSQL);
    
        $db->insertLogs("fincas_tipos", $_POST["id"], "Se edita el tipo predios {$_POST['nombre']}", $usuario["id"]);
    
        $resp["success"] = true;
        $resp["msj"] = "El tipo de predio {$_POST['nombre']} se ha actualizado correctamente";
      } else {
        $resp["success"] = false;
        $resp["msj"] = "Por favor realize algún cambio";
      }
    }else{
      $resp['msj'] = 'El nombre <b>' . $_REQUEST["nombre"] . '</b> ya se encuentra en uso.';
    }
  }else{
    $resp["success"] = false;
    $resp["msj"] = "El tipo de predio no es válido";
  }


  $db->desconectar();
  return json_encode($resp);
}

function datos($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $datos = $db->consulta("SELECT * FROM fincas_tipos WHERE id = :id", array(":id" => $id));

  if ($datos["cantidad_registros"] == 1) {
    $resp = $datos[0];
  }

  $db->desconectar();
  return $resp;
}

function cambiarEstado(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $estado = $_POST["estado"] == 1 ? 'habilita' : 'inhabilita';

  $db->sentencia("UPDATE fincas_tipos SET estado = :estado WHERE id = :id", array(":id" => $_POST["id"], ":estado" => $_POST["estado"]));
  $db->insertLogs("fincas_tipos", $_POST["id"], "Se {$estado} el fincas_tipos {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function listaPredios(){
    $db = new Bd();
    $db->conectar();
    $msj["success"] = false;

    $sql = $db->consulta("SELECT id, nombre FROM fincas_tipos WHERE estado = 1");

    if ($sql["cantidad_registros"] > 0) {
      $msj["success"] = true;
      $msj['msj'] = $sql;
    }else{
      $msj['msj'] = "No se han econtrado datos.";
    }

    $db->desconectar();

    return json_encode($msj);
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