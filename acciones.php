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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




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

  if (validarNroIdentificacion($_POST["tipo_documento"], $_POST["nro_documento"]) == 0) {
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
          ":fk_perfil" => 2,
          ":estado" => 1,
          ":fecha_creacion" => date('Y-m-d H:i:s'),
          ":confirmado" => 0,
          ":fk_creador" => 0
        );
  
        $id_registro = $db->sentencia("INSERT INTO usuarios (fk_tipo_documento, nro_documento, fk_tipo_persona, correo, nombres, apellidos, password, fecha_nacimiento, telefono, fk_perfil, estado, fecha_creacion, confirmado, fk_creador) VALUES (:fk_tipo_documento, :nro_documento, :fk_tipo_persona, :correo, :nombres, :apellidos, :password, :fecha_nacimiento, :telefono, :fk_perfil, :estado, :fecha_creacion, :confirmado, :fk_creador)", $datos);
  
        if ($id_registro > 0) {
          $pin = encriptarPass(generarPin());
          setearPinActivacion($_POST["reCorreo"], $pin);
          enviarCorrreo($_POST["reCorreo"], $pin, 'activar');
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
  } else {
    $resp['success'] = false;
    $resp['msj'] = 'El número de documento <b>' . $_REQUEST["nro_documento"] . '</b> ya se encuentra registrado <a href="?reg=0">Iniciar Sesión</a>.';
  }
  

  $db->desconectar();

  return json_encode($resp);
}

function validarNroIdentificacion($tipoIdentificacion, $nroIdentificacion){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $verificar = $db->consulta("SELECT correo FROM usuarios WHERE nro_documento = :nro_documento AND fk_tipo_documento = :fk_tipo_documento", array(":nro_documento" => $nroIdentificacion, ":fk_tipo_documento" => $tipoIdentificacion));
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();
  
  return $resp;
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

// funcion recuperar Clave
function recuperarClave(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  $correo = $_POST['correo'];
  global $ruta_raiz;
  
  if(validarCorreo($correo) > 0){
    $pin = encriptarPass(generarPin());
    setearPinRecuperacion($correo, $pin );
    enviarCorrreo($correo, $pin, 'recuperar');
    $resp['success'] = true;
    $resp['msj'] = 'Se ha enviado el enlace de recuperaación a tu correo';
  }else{
    $resp['success'] = false;
    $resp['msj'] = 'El correo electrónico no está registrado';
  } 

  $db->desconectar();
  return json_encode($resp);
}

// función para enviar correo con pin de recuperación
function enviarCorrreo($correo, $codigo, $metodo){
  global $ruta_raiz;
  require($ruta_raiz."librerias/phpmailer/src/PHPMailer.php");
  require($ruta_raiz."librerias/phpmailer/src/SMTP.php");
  require($ruta_raiz."librerias/phpmailer/src/Exception.php");
  $mail = new PHPMailer(true); // Passing `true` enables exceptions
  
  $enlace = 'http://app.fruturo.us/?'.$metodo.'='.$codigo;
  try {		
		//Create a new PHPMailer instance
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    //$mail->isSMTP();
    $mail->IsSMTP();
    $mail->SMTPDebug = 2;
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
    //Set the hostname of the mail server
    $mail->Host = 'smtp.hostinger.co';
    //Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = 587;
  
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication
    $mail->Username = 'info@fruturo.us';
    //Password to use for SMTP authentication
    $mail->Password = 'Fruturo123*-+';
    //Set who the message is to be sent from
    $mail->setFrom('info@fruturo.us', 'Prueba Fruturo');
    //Set an alternative reply-to address
    //$mail->addReplyTo('lider.servicioalcliente@hyundailatinoamerica.com', 'Alejandro Gaviria');
    //Set who the message is to be sent to
    $mail->addAddress($correo);
    //$mail->addAddress('analistamercadeo@hyundailatinoamerica.com', 'Servicio al Cliente');
    //Set the subject line
    $mail->Subject = "- - F R U T U R O - -";
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML(
      "<div>
        <p> Haz solicitado la {$metodo} de tu cuenta fruturo, <a href='{$enlace}'> Enlace <a> </p>
      </div>"
    );
    
    $mail->CharSet = 'UTF-8';

    if (!$mail->send()) {
      return false; 
    } else {
      return true;
    }
  } catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
  }
}

// se genera pin de recuperacion
function generarPin(){
  return date("dHisv");
}

// se setea pin de recuperación a usuario
function setearPinRecuperacion($correo ,$codigo_recuperacion){
  $db = new Bd();
  $db->conectar();
  
  $datosSQL = array(
    ":correo" => $correo,
    ":codigo_recuperacion" => $codigo_recuperacion
  );
  $db->sentencia("UPDATE usuarios SET codigo_recuperacion = :codigo_recuperacion WHERE correo = :correo", $datosSQL);
  
  $db->desconectar();
}

// se setea pin de activacion a usuario
function setearPinActivacion($correo ,$codigo_activacion){
  $db = new Bd();
  $db->conectar();
  
  $datosSQL = array(
    ":correo" => $correo,
    ":codigo_activacion" => $codigo_activacion
  );
  $db->sentencia("UPDATE usuarios SET codigo_activacion = :codigo_activacion WHERE correo = :correo", $datosSQL);
  
  $db->desconectar();
}

// funcion para traer datos de un usuario por token
function findUserByToken(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  $codigo_recuperacion = $_POST['token'];
  $usuario = $db->consulta('SELECT * FROM usuarios WHERE estado = 1 AND codigo_recuperacion = :codigo_recuperacion', array(':codigo_recuperacion' => $codigo_recuperacion));

  if ($usuario['cantidad_registros'] > 0){
    $resp = $usuario;
  }

  $db->desconectar();

  return json_encode($resp);
}

// funcion para traer datos de un usuario por token
function findUserByTokenActivacion(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  $codigo_activacion = $_POST['token'];
  $usuario = $db->consulta('SELECT * FROM usuarios WHERE estado = 1 AND codigo_activacion = :codigo_activacion', array(':codigo_activacion' => $codigo_activacion));

  if ($usuario['cantidad_registros'] > 0){
    $resp = $usuario;
  }

  $db->desconectar();

  return json_encode($resp);
}


function cambiarClave(){

  $db = new Bd();
  $db->conectar();
  $resp = array();

  $datosSQL = array(
    ":correo" => $_REQUEST['correoCambio'],
    ":password" =>  encriptarPass($_REQUEST['recuperacionPassword']),
  );

  $db->sentencia("UPDATE usuarios SET password = :password WHERE correo = :correo", $datosSQL);
  $db->desconectar();
  
  limpiarToken($_REQUEST['correoCambio']);

  $resp['success'] = true;
  $resp['msj'] = 'Nueva clave asignada Correctamente';

  return json_encode($resp);
}

// funcion para borrar codigo de recuperacion y que no se pueda reutilizar
function limpiarToken($correo){
  $db = new Bd();
  $db->conectar();

  $datosSQL = array(
    ":correo" => $_REQUEST['correoCambio'],
    ":codigo_recuperacion" => ''
  );

  $db->sentencia("UPDATE usuarios SET codigo_recuperacion = :codigo_recuperacion WHERE correo = :correo", $datosSQL);
  //$db->insertLogs("usuarios", $_POST["id"], "Se inhabilita la oferta", $usuario["id"]);
  $db->desconectar();
}

function activarCuenta(){

  $db = new Bd();
  $db->conectar();
  $resp = array();

  $datosSQL = array(
    ":id" => $_REQUEST['idActivacion'],
    ":confirmado" => 1,
  );

  $db->sentencia("UPDATE usuarios SET confirmado = :confirmado WHERE id = :id", $datosSQL);
  $db->insertLogs("usuarios", $_REQUEST['idActivacion'], "Se activa usuario", $_REQUEST['idActivacion']);

  $db->desconectar();

  $resp['success'] = true;
  $resp['msj'] = 'Cuenta activada correctamente'; 

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