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

  function crearMunicipio() {
    global $usuario;
    $db = new Bd();
    $db->conectar();
    $resp = array('success' => false);
    $depto = validarMunicipio($_POST['nombre']);

    if ($depto == 0) {

        $datos = array(
            ":nombre" => $_POST["nombre"], 
            ":fk_departamento" => $_POST["departamento"],
            ":fecha_creacion" => date('Y-m-d H:i:s'), 
            ":estado" => 1, 
            ":fk_creador" => $usuario["id"],
        );
    
        $id_registro = $db->sentencia("INSERT INTO municipios (nombre, fk_departamento, fecha_creacion, estado, fk_creador) VALUES (:nombre, :fk_departamento, :fecha_creacion, :estado, :fk_creador)", $datos);
    
        if ($id_registro > 0) {
            $db->insertLogs("departamentos", $id_registro, "Se crea el municipio {$_POST['nombre']}", $usuario["id"]);
            $resp['success'] = true;
            $resp['msj'] = "Se ha creado correctamente el municipio {$_POST['nombre']}.";
        } else {
            $resp['msj'] = 'Error al realizar el registro.';
        }

    } else {
        $resp['msj'] = 'El municipio ' . $_POST['nombre'] . ' ya existe';
    }

    return json_encode($resp);

  }

  function editarMunicipio() {
    global $usuario;
    $db = new Bd();
    $db->conectar();
    $resp = array('success' => false);
    $muni = validarMunicipio($_POST['nombre'], true);

    if ($muni == 0 || isset($muni['fk_departamento'])) {

      $datos = array(
        ":nombre" => $_POST["nombre"],
        ":fk_departamento" => $_POST["departamento"],
        ":id" => $_POST["id"],
      );

      if (isset($muni['fk_departamento'])) {
        if ($_POST["departamento"] != $muni['fk_departamento'] || $_POST["nombre"] != $muni['nombre']) {
      
          $datos = array(
            ":nombre" => $_POST["nombre"],
            ":fk_departamento" => $_POST["departamento"],
            ":id" => $_POST["id"],
          );  

        } else {
          $resp['msj'] = 'Realice algún cambio';
          return json_encode($resp);
        }
      }
      
      $db->sentencia("UPDATE municipios SET nombre = :nombre, fk_departamento = :fk_departamento WHERE id = :id", $datos);
  
      $db->insertLogs("perfiles", $_POST["id"], "Se edita el municipio {$_POST['nombre']}", $usuario["id"]);
  
      $resp['success'] = true;
      $resp['msj'] = "Se ha modificado correctamente el municipio {$_POST['nombre']}.";
      

    } else {
        $resp['msj'] = 'El municipio ' . $_POST['nombre'] . ' ya existe';
    }

    return json_encode($resp);
  }

  function cambiarEstadoMunicipio() {
    global $usuario;
    $db = new Bd();
    $db->conectar();

    $array = array(
      ":id" => $_POST["id"],
      ":estado" => ($_POST["estado"] == 1 ? 0 : 1),
    );

    $db->sentencia("UPDATE municipios SET estado = :estado WHERE id = :id", $array);
    $db->insertLogs("municpios", $_POST["id"], "Se inhabilita el municipio {$_POST['nombre']}", $usuario["id"]);

    $db->desconectar();

    return json_encode(1);
  }

  function validarMunicipio($nombre, $validarDepto = false){
    $db = new Bd();
    $db->conectar();
    $resp = 0;
    
    $verificar = $db->consulta("SELECT nombre, fk_departamento FROM municipios WHERE nombre = :nombre", array(":nombre" => $nombre));

    if ($verificar["cantidad_registros"] == 1 && $validarDepto) {
      $resp = $verificar[0];
    } else if ($verificar["cantidad_registros"] > 0) {
      $resp = $verificar["cantidad_registros"];
    }

    $db->desconectar();
  
    return $resp;
  }

  function listaMunicipios() {
    $table      = 'municipios';
    // Table's primary key
    $primaryKey = 'id';

    // indexes
    $columns = array(
               array( 'db' => '`muni`.`id`',             'dt' => 'id',             'field' => 'id' ),
               array( 'db' => '`muni`.`nombre`',         'dt' => 'nombre',         'field' => 'nombre'),
               array( 'db' => '`muni`.`fecha_creacion`', 'dt' => 'fecha_creacion', 'field' => 'fecha_creacion'),
               array( 'db' => '`depto`.`nombre`',        'dt' => 'departamento',   'field' => 'departamento', 'as' => 'departamento'),
               array( 'db' => '`depto`.`id`',            'dt' => 'idDepto',        'field' => 'idDepto', 'as' => 'idDepto'),
               array( 'db' => '`muni`.`estado`',          'dt' => 'estado',        'field' => 'estado', 'as' => 'estado'),
              );
        
    $sql_details = array(
                    'user' => BDUSER,
                    'pass' => BDPASS,
                    'db'   => BDNAME,
                    'host' => BDSERVER
                    );

    
    $extraWhere;

    if ($_GET['departamento'] != -1) {
      $extraWhere = "muni.estado = " . $_GET['estado'] . " AND muni.fk_departamento = " . $_GET['departamento'];
    } else {
      $extraWhere = "muni.estado = " . $_GET['estado'];
    }
    $joinQuery = "FROM `{$table}` AS `muni` INNER JOIN `departamentos` AS `depto` ON muni.fk_departamento	= depto.id";
    $groupBy = "";
    $having = "";
    return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
  }

  function departamentos(){
    $db = new Bd();
    $db->conectar();
    $resp["success"] = false;
  
    $departamentos;

    if ($_GET['estado'] == 1) {
      $departamentos = $db->consulta("SELECT * FROM departamentos WHERE estado = " . $_GET['estado']);
    } else {
      $departamentos = $db->consulta("SELECT * FROM departamentos");
    }

  
    if ($departamentos["cantidad_registros"] > 0) {
      $resp["success"] = true;
      $resp["msj"] = $departamentos;
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

?>