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

    function editarUsuario() {
        global $usuario;
        $db = new Bd();
        $db->conectar();
        $resp = array();
        $datosUsuario = datosUsuario($_POST["id"]);

        if ($datosUsuario != 0) {

            if ($_POST["tipoPersona"] != $datosUsuario['fk_tipo_persona'] || $_POST["nroDocumento"] != $datosUsuario['nro_documento'] || $_POST["correo"] != $datosUsuario['correo'] || $_POST["nombres"] != $datosUsuario['nombres'] || $_POST["apellidos"] != $datosUsuario['apellidos'] || $_POST["telefono"] != $datosUsuario['telefono'] ) {

                $datosSQL = array(
                    ":fk_tipo_persona" => $_POST["tipoPersona"],
                    ":nro_documento" => $_POST["nroDocumento"],
                    ":correo" => $_POST["correo"],
                    ":nombres" => $_POST["nombres"], 
                    ":apellidos" => $_POST["apellidos"],
                    ":telefono" => $_POST["telefono"],
                    ":id" => $_POST["id"],
                );

                $db->sentencia("UPDATE usuarios SET fk_tipo_persona = :fk_tipo_persona, nro_documento = :nro_documento, correo = :correo, nombres = :nombres, apellidos = :apellidos, telefono = :telefono WHERE id = :id", $datosSQL);

                $db->insertLogs("usuarios", $_POST["id"], "Se edita el perfil del usuario {$_POST['correo']}", $usuario["id"]);

                $resp['mensaje'] = 'Datos guardados correctamente.';
                $resp['success'] = true;

            } else {
                $resp['mensaje'] = 'No hay valores a actualizar';
                $resp['success'] = false;
            }

        } else {
            $resp['mensaje'] = 'No existe un usuario';
            $resp['success'] = false;
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