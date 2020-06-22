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
  global $usuario;
  $table      = 'cosechas';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'c.id',                    'dt' => 'id',              'field' => 'id' ),
              array( 'db' => 'p.nombre',                'dt' => 'producto',        'field' => 'producto',       'as' => 'producto' ),
              array( 'db' => 'f.nombre',                'dt' => 'finca',           'field' => 'finca',          'as' => 'finca'),
              array( 'db' => 'c.volumen_total',         'dt' => 'volumen_total',   'field' => 'volumen_total' ),
              array( 'db' => 'c.precio',                'dt' => 'precio',          'field' => 'precio'),
              array( 'db' => 'c.fecha_inicio',          'dt' => 'fecha_inicio',    'field' => 'fecha_inicio'),
              array( 'db' => 'c.fecha_final',           'dt' => 'fecha_final',     'field' => 'fecha_final'),
              array( 'db' => 'c.fecha_creacion',        'dt' => 'fecha_creacion',  'field' => 'fecha_creacion')
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS c INNER JOIN productos AS p ON c.fk_producto = p.id INNER JOIN fincas AS f ON c.fk_finca = f.id";
  $extraWhere= "c.estado = 1 AND c.fk_creador = " . $usuario["id"];
  $groupBy = "";
  $having = "";
  return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
}

function crear(){
  $mensaje = '';
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;
  global $ruta_raiz;
  $resp['success'] = false;

  if (isset($_FILES['fotos']) && isset($_POST["producto"]) && isset($_POST['terreno']) && isset($_POST["volumen_total"]) && isset($_POST["precio"]) && isset($_POST["fecha_inicio"]) && isset($_POST["fecha_fin"])) {
    
    $datos = array(
      ":fk_producto" => $_POST["producto"],
      ":fk_finca" => $_POST['terreno'],
      ":volumen_total" => $_POST["volumen_total"],
      ":precio" => $_POST["precio"],
      ":fecha_inicio" => date("Y-m-d", strtotime($_POST["fecha_inicio"])),
      ":fecha_final" => date("Y-m-d", strtotime($_POST["fecha_fin"])),
      ":estado" => 1,
      ":fecha_creacion" => date('Y-m-d H:i:s'),
      ":fk_creador" => $usuario['id']
  
    );
  
    $id_registro = $db->sentencia("INSERT INTO cosechas (fk_producto, fk_finca, volumen_total, precio, fecha_inicio, fecha_final, estado, fecha_creacion, fk_creador) VALUES (:fk_producto, :fk_finca, :volumen_total, :precio, :fecha_inicio, :fecha_final, :estado, :fecha_creacion, :fk_creador)", $datos);
  
    if ($id_registro > 0) {
      $cont=-1;
      $cont1 = 0;

      foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
        //Obtenemos la extension del archivo para agregarla al a final
        $info = new SplFileInfo($_FILES['fotos']['name'][$key]);
        $tamano = $_FILES['fotos']['size'][$key];
        $extension = $info->getExtension();
        //El tamaño maximo es de 10 mb
        if ($tamano <= 10000000) {
          //Validamos el tipo de imagen
          if ($extension == "jpeg" || $extension == "jpg" || $extension == "png") {
    
            //Declaramos un  variable con la ruta donde guardaremos los archivos
            $directorio = $ruta_raiz . 'almacenamiento/cosechas/' . $id_registro . '/';
    
            //Validamos si la ruta de destino existe, en caso de no existir la creamos
            if(!file_exists($directorio)){
              // Para crear una estructura anidada se debe especificar
              // el parámetro $recursive en mkdir().
              if(!mkdir($directorio, 0777, true)) {
                die('Fallo al crear las carpetas...');
              }
            }
    
            //Abrimos el directorio de destino
            $dir=opendir($directorio);
  
            //Indicamos la ruta de destino, así como el nombre del archivo
            $target_path = $directorio . $key . "." . $extension;
                      
            //Movemos y validamos que el archivo se haya cargado correctamente
            //El primer campo es el origen y el segundo el destino
            if(move_uploaded_file($_FILES['fotos']['tmp_name'][$key], $target_path)) {
              $datos_foto = array(
                ":tipo" => $extension, 
                ":ruta" => substr($target_path, 9), 
                ":fk_cosecha" => $id_registro, 
                ":fecha_creacion" => date("Y-m-d H:i:s"), 
                ":fk_creador" => $usuario['id']
              );

              $id_foto = $db->sentencia("INSERT INTO cosechas_fotos (tipo, ruta, fk_cosecha, fecha_creacion, fk_creador) VALUES (:tipo, :ruta, :fk_cosecha, :fecha_creacion, :fk_creador)", $datos_foto);
              
              if ($id_foto > 0) {
                $db->insertLogs("cosechas_fotos", $id_foto, "Creacion de fotos de consecha con id: " . $id_registro, $usuario['id']);
                $cont++;
                $cont1 = $key;
              }
            } else {
              $mensaje .= "Ha ocurrido un error con ". $_FILES['fotos']['name'][$key] .", por favor inténtelo de nuevo";
            }
            closedir($dir); //Cerramos el directorio de destino
          
          }else{
            $mensaje .= "El tipo de archivo no es permitido";
          }
        }else{
          $mensaje .= "Ha exedido el tamaño permitido de 10MB";
        }
      }

      //Validamos si subieron todos los archivos si no eliminar los registros haciendo una especie de rollback
      if ($cont == $cont1) {
        $db->insertLogs("cosechas", $id_registro, "Se crea la cosecha", $usuario["id"]);
        
        if (@$_POST["certificado"]) {
          
          foreach ($_POST["certificado"] as $certificado) {
            $datos_certi = array(
              ":fk_cosecha" => $id_registro, 
              ":fk_certificacion" => $certificado, 
              ":fecha_creacion" => date("Y-m-d"), 
              ":fk_creador" => $usuario["id"]
            );
            $id_registro_certi = $db->sentencia("INSERT INTO cosechas_certificaciones (fk_cosecha, fk_certificacion, fecha_creacion, fk_creador) VALUES (:fk_cosecha, :fk_certificacion, :fecha_creacion, :fk_creador)", $datos_certi);
    
            $db->insertLogs("cosechas_certificaciones", $id_registro_certi, "Se crea la cosecha con el certificado", $usuario["id"]);
          }
    
        }
    
        $resp['success'] = true;
        $resp['msj'] = 'Se ha creado correctamente.';
        
      }else{
        $resp['msj'] = "Error al subir los archivos" . '' . $mensaje;

        $db->sentencia("DELETE FROM cosechas_fotos WHERE fk_cosecha = :fk_cosecha", array(":fk_cosecha" => $id_registro));

        $db->sentencia("DELETE FROM cosechas WHERE id = :id", array(":id" => $id_registro));

      }
    } else {
      $resp['msj'] = 'Error al realizar el registro.';
    }
  } else {
    $resp['msj'] = 'Uno de los campos se encuentra vacio.';
  }
  
  $db->desconectar();
  return json_encode($resp);
}

function eliminar(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE cosechas SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("cosechas", $_POST["id"], "Se inhabilita la oferta", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function fotosCosechas(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * FROM cosechas_fotos WHERE fk_cosecha = :fk_cosecha", array(":fk_cosecha" => $_REQUEST["idCosecha"]));

  if ($datos['cantidad_registros'] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  }else{
    $resp['msj'] = "No se han encontrado datos";
  }  

  $db->desconectar();

  return json_encode($resp);
}


/*****************************************/

/* function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  global $usuario;
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM terrenos WHERE nombre = :nombre AND estado = 1 AND fk_usuario = :fk_usuario", array(":nombre" => $nombre, ":fk_usuario" => $usuario["id"]));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM terrenos WHERE nombre = :nombre AND id != :id AND estado = 1 AND fk_usuario = :fk_usuario", array(":nombre" => $nombre, ':id' => $id, ":fk_usuario" => $usuario["id"]));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

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
      # code...
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
} */

if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}