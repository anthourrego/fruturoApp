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
  require($ruta_raiz . "clases/SSP.php");

  $session = new Session();

  $usuario = $session->get("usuario");

  function crearDepartamento() {
    global $usuario;
    $db = new Bd();
    $db->conectar();
    $resp = array('success' => false);
    $depto = validarDepartamento($_POST['nombre']);

    if ($depto == 0) {

        $datos = array(
            ":nombre" => cadena_db_insertar($_POST["nombre"]), 
            ":fecha_creacion" => date('Y-m-d H:i:s'), 
            ":estado" => 1, 
            ":fk_creador" => $usuario["id"],
        );
    
        $id_registro = $db->sentencia("INSERT INTO departamentos (nombre, fecha_creacion, estado, fk_creador) VALUES (:nombre, :fecha_creacion, :estado, :fk_creador)", $datos);
    
        if ($id_registro > 0) {
            $db->insertLogs("departamentos", $id_registro, "Se crea el departamento {$_POST['nombre']}", $usuario["id"]);
            $resp['success'] = true;
            $resp['msj'] = "Se ha creado correctamente el departamento {$_POST['nombre']}.";
        } else {
            $resp['msj'] = 'Error al realizar el registro.';
        }

    } else {
        $resp['msj'] = 'El departamento ' . $_POST['nombre'] . ' ya existe';
    }

    return json_encode($resp);

  }

  function editarDepartamento() {
    global $usuario;
    $db = new Bd();
    $db->conectar();
    $resp = array('success' => false);
    $depto = validarDepartamento($_POST['nombre']);

    if ($depto == 0) {
        
        $datos = array(
          ":nombre" => cadena_db_insertar($_POST["nombre"]),
          ":id" => $_POST["id"],
        );

        $db->sentencia("UPDATE departamentos SET nombre = :nombre WHERE id = :id", $datos);
    
        $db->insertLogs("perfiles", $_POST["id"], "Se edita el departamento {$_POST['nombre']}", $usuario["id"]);
    
        $resp['success'] = true;
        $resp['msj'] = "Se ha modificado correctamente el departamento {$_POST['nombre']}.";

    } else {
        $resp['msj'] = 'El departamento ' . $_POST['nombre'] . ' ya existe';
    }

    return json_encode($resp);
  }

  function validarDepartamento($nombre){
    $db = new Bd();
    $db->conectar();
    $resp = 0;
  
    $verificar = $db->consulta("SELECT nombre FROM departamentos WHERE nombre = :nombre", array(":nombre" => $nombre));
    
    if ($verificar["cantidad_registros"] > 0) {
      $resp = $verificar["cantidad_registros"];
    }

    $db->desconectar();
  
    return $resp;
  }

  function listaDepartamentos() {
    $table      = 'departamentos';
    // Table's primary key
    $primaryKey = 'id';

    // indexes
    $columns = array(
                array( 'db' => 'id', 'dt' => 'id',                 'field' => 'id' ),
                array( 'db' => 'nombre',   'dt' => 'nombre',  'field' => 'nombre'),
                array( 'db' => 'fecha_creacion',           'dt' => 'fecha_creacion',          'field' => 'fecha_creacion' ),
                );
        
    $sql_details = array(
                    'user' => BDUSER,
                    'pass' => BDPASS,
                    'db'   => BDNAME,
                    'host' => BDSERVER
                    );
        
    $joinQuery = "FROM {$table} WHERE estado = 1";
    $extraWhere= "";
    $groupBy = "";
    $having = "";
    return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
  }

  function inhabilitarDepartamento() {
    global $usuario;
    $db = new Bd();
    $db->conectar();

    $db->sentencia("UPDATE departamentos SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
    $db->insertLogs("departamentos", $_POST["id"], "Se inhabilita el departamento {$_POST['nombre']}", $usuario["id"]);

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

?>