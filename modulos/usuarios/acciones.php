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

function crearUsuario(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;

  if (validarUsuario($_POST["correo"]) == 0) {
    $password = cadena_db_insertar($_POST['pass']);
    $repassword = cadena_db_insertar($_POST['rePass']);

    if ($password == $repassword) {
      $password = encriptarPass($password);

      $id_registro = $db->sentencia("INSERT INTO usuarios (correo, nombres, apellidos, password, fecha_nacimiento, telefono, fk_perfil, estado, fecha_creacion, confirmado, fk_creador) VALUES (:correo, :nombres, :apellidos, :password, :fecha_nacimiento, :telefono, :fk_perfil, :estado, :fecha_creacion, :confirmado, :fk_creador)", 
      array(
        ":correo" => cadena_db_insertar($_POST["correo"]), 
        ":nombres" => cadena_db_insertar($_POST["nombre"]), 
        ":apellidos" => cadena_db_insertar($_POST["apellidos"]), 
        ":password" => $password, 
        ":fecha_nacimiento" => date("Y-m-d", strtotime($_REQUEST["fecha_nacimiento"])), 
        ":telefono" => cadena_db_insertar($_POST["telefono"]), 
        ":fk_perfil" => $_POST["perfil"], 
        ":estado" => 1, 
        ":fecha_creacion" => date('Y-m-d H:i:s'), 
        ":confirmado" => 1, 
        ":fk_creador" => $usuario["id"]
      ));

      if ($id_registro > 0) {
        $db->insertLogs("usuarios", $id_registro, "Se crea el usuario {$_POST['correo']}", $usuario["id"]);
        $resp['success'] = true;
        $resp['msj'] = 'Se ha registrado correctamente.';
      } else {
        $resp['success'] = false;
        $resp['msj'] = 'Error al realizar el registro.';
      }
      
    }else{
      $resp['success'] = false;
      $resp['msj'] = 'Las contraseñas no coinciden.';
    }

  }else{
    $resp['success'] = false;
    $resp['msj'] = 'El usuario <b>' . $_REQUEST["usuario"] . '</b> ya se encuentra en uso.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function validarUsuario($correo){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $verificar = $db->consulta("SELECT correo FROM usuarios WHERE correo = :correo", array(":correo" => $correo));
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function listaUsuarios(){
  $table      = 'usuarios';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'id',                  'dt' => 'id',                 'field' => 'id' ),
              array( 'db' => 'correo',              'dt' => 'correo',             'field' => 'correo' ),
              array( 'db' => 'nombres',             'dt' => 'nombres',            'field' => 'nombres' ),
              array( 'db' => 'apellidos',           'dt' => 'apellidos',          'field' => 'apellidos' ),
              array( 'db' => 'fecha_nacimiento',    'dt' => 'fecha_nacimiento',   'field' => 'fecha_nacimiento' ),
              array( 'db' => 'telefono',            'dt' => 'telefono',           'field' => 'telefono' ),
              array( 'db' => 'fk_perfil',           'dt' => 'fk_perfil',          'field' => 'fk_perfil' )
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM usuarios";
  $extraWhere= "`estado` = 1";
  $groupBy = "";
  $having = "";
  return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
}

function inHabilitarUsuario(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE usuarios SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("usuarios", $_POST["id"], "Se inhabilita el usuario {$_POST['usuario']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function cambiarPass(){
  global $usuario;
  $db = new Bd();
  $db->conectar();
  $password = cadena_db_insertar($_POST['cambioPass']);
  $repassword = cadena_db_insertar($_POST['cambioRePass']);
  $resp = array();

  if ($password == $repassword) {
    $password = encriptarPass($password);

    $db->sentencia("UPDATE usuarios SET password = :password WHERE id = :id", array(":id" => $_POST["id"], ":password" => $password));
    $db->insertLogs("usuarios", $_POST["id"], "Se cambia la contrase del usuario {$_POST['correo']}", $usuario["id"]);

    $resp['success'] = true;
    $resp['msj'] = 'Se ha cambiado la contraseña.';
  }else{
    $resp['success'] = false;
    $resp['msj'] = 'Las contraseñas no coinciden.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function editarUsuario(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datosUsuario = datosUsuario($_POST["id"]);

  if ($datosUsuario != 0) {

    if ($_POST["nombres"] != $datosUsuario['nombres'] || $_POST["apellidos"] != $datosUsuario['apellidos'] || strtotime($_POST["fecha_nacimiento"]) != strtotime($datosUsuario['fecha_nacimiento']) || $_POST["telefono"] != $datosUsuario['telefono'] || $_POST["perfil"] != $datosUsuario['fk_perfil']) {

      $datosSQL = array(
                    ":nombres" => $_POST["nombres"], 
                    ":apellidos" => $_POST["apellidos"],
                    ":fecha_nacimiento" => date("Y-m-d", strtotime($_POST["fecha_nacimiento"])),
                    ":telefono" => $_POST['telefono'],
                    ":fk_perfil" => $_POST['perfil'],
                    ":id" => $_POST["id"]
                  );

      $db->sentencia("UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos, fecha_nacimiento = :fecha_nacimiento, telefono = :telefono, fk_perfil = :fk_perfil WHERE id = :id", $datosSQL);
  
      $db->insertLogs("usuarios", $_POST["id"], "Se edita el usuario {$_POST['correo']}", $usuario["id"]);
  
      $resp["success"] = true;
      $resp["msj"] = "El usuario se ha actualiza correctamente";
    } else {
      $resp["success"] = false;
      $resp["msj"] = "Por favor realize algún cambio";
    }
  }else{
    $resp["success"] = false;
    $resp["msj"] = "El usuario no es valido";
  }


  $db->desconectar();
  return json_encode($resp);
}

function datosUsuario($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $usuario = $db->consulta("SELECT * FROM usuarios WHERE id = :id", array(":id" => $id));

  if ($usuario["cantidad_registros"] == 1) {
    $resp = $usuario[0];
  }

  $db->desconectar();
  return $resp;
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