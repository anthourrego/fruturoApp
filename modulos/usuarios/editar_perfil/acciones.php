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

  $session = new Session();

  $usuario = $session->get("usuario");

  function editarUsuario() {
    global $usuario;
    $db = new Bd();
    $db->conectar();
    $resp = array();
    $datosUsuario = datosUsuario($_POST["id"]);
    $resp['success'] = false;

    if ($datosUsuario != 0) {

      if ($_POST["tipoPersona"] != $datosUsuario['fk_tipo_persona'] || $_POST["correo"] != $datosUsuario['correo'] || $_POST["nombres"] != $datosUsuario['nombres'] || $_POST["apellidos"] != $datosUsuario['apellidos'] || $_POST["telefono"] != $datosUsuario['telefono'] ) {

        $datosSQL = array(
          ":fk_tipo_persona" => $_POST["tipoPersona"],
          ":correo" => $_POST["correo"],
          ":nombres" => $_POST["nombres"], 
          ":apellidos" => $_POST["apellidos"],
          ":telefono" => $_POST["telefono"],
          ":perfil" => $_POST["perfil"],
          ":id" => $_POST["id"],
        );

        $db->sentencia("UPDATE usuarios SET fk_tipo_persona = :fk_tipo_persona, correo = :correo, nombres = :nombres, apellidos = :apellidos, telefono = :telefono, fk_perfil = :perfil WHERE id = :id", $datosSQL);

        $db->insertLogs("usuarios", $_POST["id"], "Se edita el perfil del usuario {$_POST['correo']}", $usuario["id"]);

        $resp['msj'] = 'Datos guardados correctamente.';
        $resp['success'] = true;

        $array_session_usuario = array();
        $array_session_usuario["id"] = $usuario['id'];
        $array_session_usuario["nombre"] = $_POST["nombres"] . ' ' . $_POST["apellidos"];
        $array_session_usuario["fecha_nacimiento"] = $usuario['fecha_nacimiento'];
        $array_session_usuario["telefono"] = $_POST["telefono"];
        $array_session_usuario["perfil"] = $_POST["perfil"];

        $session = new Session();

        $session->set('usuario', $array_session_usuario);

      } else {
        $resp['msj'] = 'Realice algún cambio'; 
      }
    } else {
      $resp['msj'] = 'No existe un usuario';
    }

    $db->desconectar();
    return json_encode($resp);
  }

  function obtenerDatosUsuario () {
    $datos = json_encode(datosUsuario($_GET['id']));
    return $datos;
  }

  function datosUsuario($id) {
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

?>