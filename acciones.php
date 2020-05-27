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
require($ruta_raiz . "clases/Session.php");

function sessionActiva(){
  $session = new Session();
  if ($session->exist('usuario') == true) {
    return 1;
  }else{
    return 0;
  }
}


function iniciarSesion(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  $pass = cadena_db_insertar($_POST['password']);

  $usuario = $db->consulta('SELECT * FROM usuarios WHERE estado = 1 AND correo = :correo', array(':correo' => $_POST["correo"]));

  if ($usuario['cantidad_registros'] > 0) {
    if(password_verify($pass, $usuario[0]['password'])){
      if ($usuario[0]['confirmado'] == 1) {
        $session = new Session();
  
        $array_session_usuario = array();
        $array_session_usuario["id"] = $usuario[0]['id'];
        $array_session_usuario["nombre"] = $usuario[0]['nombres'] . ' ' . $usuario[0]['apellidos'];
        $array_session_usuario["fecha_nacimiento"] = $usuario[0]['fecha_nacimiento'];
        $array_session_usuario["telefono"] = $usuario[0]['telefono'];
        $array_session_usuario["perfil"] = $usuario[0]['fk_perfil'];
  
        $session->set('usuario', $array_session_usuario);
  
        $resp['success'] = true;
        $resp['msj'] = 'Iniciar sesión'; 
      }else{
        $resp['success'] = false;
        $resp['msj'] = 'Por favor activa tu usuario'; 
      }
    }else{
      $resp['success'] = false;
      $resp['msj'] = 'Correo y/o Contraseña son incorrectos';
    }
  }else{
    $resp['success'] = false;
    $resp['msj'] = 'Correo y/o Contraseña son incorrectos';
  }

  $db->desconectar();

  return json_encode($resp);
}

function registrarse(){
  $db = new Bd();
  $db->conectar();
  $resp = array();

  if (validarCorreo($_POST["reCorreo"]) == 0) {
    $password = cadena_db_insertar($_POST['rePassword']);
    $repassword = cadena_db_insertar($_POST['rerePassword']);

    if ($password == $repassword) {
      $password = encriptarPass($password);

      $datos = array(
        ":fk_tipo_documento" => $_POST["tipo_documento"],
        ":nro_documento" => $_POST["nro_documento"],
        ":fk_tipo_persona" => $_POST["tipo_persona"],
        ":correo" => $_POST["reCorreo"],
        ":nombres" => $_POST["nombres"],
        ":apellidos" => $_POST["apellidos"],
        ":password" => $password,
        ":fecha_nacimiento" => date("Y-m-d", strtotime($_REQUEST["fecha"])),
        ":telefono" => $_POST["tel"],
        ":fk_perfil" => $_POST["perfil"],
        ":estado" => 1,
        ":fecha_creacion" => date('Y-m-d H:i:s'),
        ":confirmado" => 1,
        ":fk_creador" => 0
      );

      $id_registro = $db->sentencia("INSERT INTO usuarios (fk_tipo_documento, nro_documento, fk_tipo_persona, correo, nombres, apellidos, password, fecha_nacimiento, telefono, fk_perfil, estado, fecha_creacion, confirmado, fk_creador) VALUES (:fk_tipo_documento, :nro_documento, :fk_tipo_persona, :correo, :nombres, :apellidos, :password, :fecha_nacimiento, :telefono, :fk_perfil, :estado, :fecha_creacion, :confirmado, :fk_creador)", $datos);

      if ($id_registro > 0) {
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
    $resp['msj'] = 'El correo <b>' . $_REQUEST["reCorreo"] . '</b> ya se encuentra registrada <a href="?reg=0">Iniciar Sesión</a>.';
  }

  $db->desconectar();

  return json_encode($resp);
}

function validarCorreo($correo){
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

function listaPerfiles(){
  $db = new Bd();
  $db->conectar();
  $resp['success'] = false;

  $datos = $db->consulta("SELECT * FROM perfiles WHERE estado = 1 AND id != 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  } else {
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
}

function listaTipoDocumento(){
  $db = new Bd();
  $db->conectar();
  $resp['success'] = false;

  $datos = $db->consulta("SELECT * FROM tipo_documento WHERE estado = 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  } else {
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
}

function listaTipoPersona(){
  $db = new Bd();
  $db->conectar();
  $resp['success'] = false;

  $datos = $db->consulta("SELECT * FROM tipo_persona WHERE estado = 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  } else {
    $resp["msj"] = "No se han encontrado datos";
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