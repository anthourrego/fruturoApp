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

  function editarDepartamento() {}

  function listaDepartamentos() {
    $table      = 'departamentos';
    // Table's primary key
    $primaryKey = 'id';

    echo($table);

    // indexes
    $columns = array(
                array( 'db' => 'id', 'dt' => 'id',                 'field' => 'id' ),
                array( 'db' => 'nombre',   'dt' => 'nombre',  'field' => 'nombre'),
                array( 'db' => 'fecha_creacion',           'dt' => 'fecha_creacion',          'field' => 'fecha_creacion' ),
                // array( 'db' => 'estado',              'dt' => 'estado',             'field' => 'estado'),
                );
        
    $sql_details = array(
                    'user' => BDUSER,
                    'pass' => BDPASS,
                    'db'   => BDNAME,
                    'host' => BDSERVER
                    );
        
    // $joinQuery = "FROM {$table} WHERE estado = 1";
    $joinQuery = "FROM {$table}";
    $extraWhere= "";
    $groupBy = "";
    $having = "";
    return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
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