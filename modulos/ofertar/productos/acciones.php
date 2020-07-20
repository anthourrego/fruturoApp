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
  $table      = 'productos';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'p.id',                    'dt' => 'id',                  'field' => 'id' ),
              array( 'db' => 'p.nombre',                'dt' => 'nombre',              'field' => 'nombre'),
              array( 'db' => 'p.presentacion',          'dt' => 'presentacion',        'field' => 'presentacion'),
              array( 'db' => 'p.descripcion',           'dt' => 'descripcion',         'field' => 'descripcion' ),
              array( 'db' => 'p.fecha_creacion',        'dt' => 'fecha_creacion',      'field' => 'fecha_creacion'),
              array( 'db' => 'p.reg_invima',            'dt' => 'reg_invima',          'field' => 'reg_invima' ),
              array( 'db' => 'p.fk_finca',              'dt' => 'fk_finca',            'field' => 'fk_finca' ),
              array( 'db' => 'p.fk_creador',            'dt' => 'fk_creador',          'field' => 'fk_creador' )
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS p";
  $extraWhere= "p.estado = 1 AND p.fk_creador = " . $usuario["id"] . " AND p.fk_finca = " . $_GET["finca"];
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

  if (isset($_POST["nombre"]) && isset($_POST['presentacion']) && isset($_FILES['tabla_nutricional']) && isset($_FILES['foto_frente']) && isset($_FILES['foto_reves'])) {
    if (validarNombre($_POST["nombre"], $_REQUEST["fk_finca"]) == 0) {
      $datos = array(
        ":nombre" => $_POST["nombre"], 
        ":presentacion" => $_POST["presentacion"], 
        ":descripcion" => @$_POST["descripcion"], 
        ":fecha_creacion" => date("Y-m-d H:i:s"), 
        ":estado" => 1, 
        ":reg_invima" => @$_REQUEST["registro_invima"], 
        ":fk_finca" => $_REQUEST["fk_finca"], 
        ":fk_creador" => $usuario["id"]
      );
  
      $id_registro = $db->sentencia("INSERT INTO productos (nombre, presentacion, descripcion, fecha_creacion, estado, reg_invima, fk_finca, fk_creador) VALUES (:nombre, :presentacion, :descripcion, :fecha_creacion, :estado, :reg_invima, :fk_finca, :fk_creador)", $datos);
  
      if ($id_registro > 0) {

        $fail = 0;

        $tabla = subirArchivos($_FILES['tabla_nutricional'], $id_registro, 1);

        if ($tabla) {

          $foto_frente = subirArchivos($_FILES['foto_frente'], $id_registro, 2);

          if ($foto_frente) {

            $foto_reves = subirArchivos($_FILES['foto_reves'], $id_registro, 3);

            if ($foto_reves) {

              $db->insertLogs("productos", $id_registro, "Se crea el producto ", $usuario["id"]);
              $resp['success'] = true;
              $resp['msj'] = 'Se ha creado correctamente.';

            } else {
              $resp['msj'] = "Foto revés: " . $foto_reves;
            }
          }else{
            $resp['msj'] = "Foto frente: " . $foto_frente;
          }
        }else{
          $resp['msj'] = "Tabla nuticional: " . $tabla;
        }

        if ($fail > 0) {

          $db->sentencia("DELETE FROM cosechas_productos_documentos WHERE fk_producto = :fk_producto", array(":fk_producto" => $id_registro));

          $db->sentencia("DELETE FROM productos WHERE id = :id", array(":id" => $id_registro));
        }

      } else {
        $resp['msj'] = 'Error al realizar la creación del producto.';
      }
    }else{
      $resp['msj'] = 'El nombre <b>' . $_REQUEST["nombre"] . '</b> ya se encuentra en uso.';
    }
  } else {
    $resp['msj'] = 'Uno de los campos se encuentra vacio.';
  }
    

  $db->desconectar();
  return json_encode($resp);
}

function validarNombre($nombre, $fk_finca, $id = 0){
  $db = new Bd();
  $db->conectar();
  global $usuario;
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM productos WHERE nombre = :nombre AND estado = 1 AND fk_creador = :fk_creador AND fk_finca = :fk_finca", array(":nombre" => $nombre, ":fk_creador" => $usuario["id"], ":fk_finca" => $fk_finca));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM productos WHERE nombre = :nombre AND id != :id AND estado = 1 AND fk_creador = :fk_creador AND fk_finca = :fk_finca", array(":nombre" => $nombre, ':id' => $id, ":fk_creador" => $usuario["id"], ":fk_finca" => $fk_finca));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function subirArchivos($archivo, $fk_producto, $cont){
  global $ruta_raiz;
  global $usuario;
  $db = new Bd();
  $db->conectar();

  //Obtenemos la extension del archivo para agregarla al a final
  $info = new SplFileInfo($archivo['name']);
  $tamano = $archivo['size'];
  $extension = $info->getExtension();
  //El tamaño maximo es de 10 mb
  if ($tamano <= 3000000) {
    //Validamos el tipo de imagen
    if ($extension == "jpeg" || $extension == "jpg" || $extension == "png") {

      //Declaramos un  variable con la ruta donde guardaremos los archivos
      $directorio = $ruta_raiz . 'almacenamiento/productos/' . $fk_producto . '/';

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
      $target_path = $directorio . $cont . "." . $extension;
                
      //Movemos y validamos que el archivo se haya cargado correctamente
      //El primer campo es el origen y el segundo el destino
      if(move_uploaded_file($archivo['tmp_name'], $target_path)) {
        $datos_foto = array(
          ":tipo" => $extension, 
          ":ruta" => substr($target_path, 9), 
          ":fk_producto" => $fk_producto, 
          ":fecha_creacion" => date("Y-m-d H:i:s"), 
          ":fk_creador" => $usuario['id']
        );

        $id_foto = $db->sentencia("INSERT INTO cosechas_productos_documentos (tipo, ruta, fk_producto, fecha_creacion, fk_creador) VALUES (:tipo, :ruta, :fk_producto, :fecha_creacion, :fk_creador)", $datos_foto);
        
        if ($id_foto > 0) {
          $db->insertLogs("cosechas_productos_documentos", $id_foto, "Creacion de fotos de producto con id: " . $fk_producto, $usuario['id']);
          $mensaje = true;
        }
      } else {
        $mensaje .= "Ha ocurrido un error con ". $archivo['name'] .", por favor inténtelo de nuevo";
      }
      closedir($dir); //Cerramos el directorio de destino
    
    }else{
      $mensaje .= "El tipo de archivo no es permitido";
    }
  }else{
    $mensaje .= "Ha exedido el tamaño permitido de 3MB";
  }

  $db->desconectar();

  return $mensaje;

}

function eliminar(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE productos SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("productos", $_POST["id"], "Se inhabilita el producto {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function fotosProductos(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;

  $datos = $db->consulta("SELECT * FROM cosechas_productos_documentos WHERE fk_producto = :fk_producto", array(":fk_producto" => $_REQUEST["idProducto"]));

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

if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}