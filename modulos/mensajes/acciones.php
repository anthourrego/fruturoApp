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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$session = new Session();

$usuario = $session->get("usuario");

function listaChats(){
  $db = new Bd();
  $db->conectar();
  $resp["success"] = false;
  global $usuario;

  $sql = $db->consulta("SELECT 
                          co.id AS idOferta,
                          comprador.id AS idComprador,
                          CONCAT(comprador.nombres, ' ', comprador.apellidos) AS nombreComprador,
                          comprador.correo AS correoComprador,
                          vendedor.id AS idVendedor,
                          CONCAT(vendedor.nombres, ' ', vendedor.apellidos) AS nombreVendedor,
                          vendedor.correo AS correoVendedor,
                          p.nombre AS producto,
                          pd.nombre AS producto_derivado,
                          (SELECT ruta FROM cosechas_productos_documentos WHERE fk_producto = c.fk_producto ORDER BY id ASC LIMIT 1) AS foto_producto,
                          (SELECT ruta FROM cosechas_productos_documentos WHERE fk_cosecha = c.id ORDER BY id ASC LIMIT 1) AS foto_cosecha
                        FROM cosecha_oferta AS co 
                          INNER JOIN cosechas AS c ON c.id = co.fk_cosecha 
                          INNER JOIN productos AS p ON c.fk_producto = p.id
                          LEFT JOIN productos_derivados AS pd ON pd.id = c.fk_productos_derivados
                          INNER JOIN usuarios AS comprador ON co.fk_comprador = comprador.id
                          INNER JOIN usuarios AS vendedor ON co.fk_vendedor = vendedor.id
                        WHERE 
                          co.fk_vendedor = :vendedor OR co.fk_comprador = :comprador", array(":vendedor" => $usuario["id"], ":comprador" => $usuario["id"]));

  if ($sql["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $sql;
  }else{
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
}

function traerMensajes(){
  $db = new Bd();
  $db->conectar();

  $mensaje = $db->consulta("SELECT
                          com.mensaje,
                          com.fecha_creacion,
                          CONCAT(u.nombres, ' ', u.apellidos) AS nombre,
                          com.fk_creador
                        FROM cosecha_oferta_mensajes AS com
                          INNER JOIN usuarios AS u ON com.fk_creador = u.id
                        WHERE com.fk_cosecha_oferta = :oferta", array(":oferta" => $_REQUEST["idOferta"]));
  
  if ($mensaje["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $mensaje;
  }else{
    $resp["msj"] = "No hay mensajes";
  }

  $db->desconectar();

  return json_encode($resp);
}

function enviarMensaje(){
  $db = new Bd();
  $db->conectar();
  global $usuario;
  $resp["success"] = false;
  $asunto = 'Respuesta Oferta Fruturo | '.$_POST['asunto'];
  $mensaje = $_POST['mensaje'];
 
  $datos = array(
            ":fk_cosecha_oferta" => $_REQUEST["idCosecha"], 
            ":mensaje" => $_REQUEST["mensaje"], 
            ":fk_creador" => $usuario["id"], 
            ":fecha_creacion" => date("Y-m-d H:i:s")
          );

  $id_registro = $db->sentencia("INSERT INTO cosecha_oferta_mensajes (fk_cosecha_oferta, mensaje, fk_creador, fecha_creacion) VALUES (:fk_cosecha_oferta, :mensaje, :fk_creador, :fecha_creacion)", $datos);

  if ($id_registro > 0) {
    $db->insertLogs("cosecha_oferta_mensaje", $id_registro, "Se crea mensaje de la oferta {$_POST['idCosecha']}", $usuario["id"]);

    if (enviarCorrreo($_REQUEST["correo"], $asunto, $mensaje) == true) {
      $resp['success'] = true;
      $resp['msj'] = 'Se ha enviado correctamente.';
    }else{
      $resp['msj'] = 'No se ha enviado correo.';
    }
  } else {
    $resp['msj'] = 'Error al realizar el registro.';
  }

  $db->desconectar();

  return json_encode($resp);

}

// función para enviar correo con pin de recuperación
function enviarCorrreo($correo, $asunto, $mensaje){
  global $ruta_raiz;
  require($ruta_raiz."librerias/phpmailer/src/PHPMailer.php");
  require($ruta_raiz."librerias/phpmailer/src/SMTP.php");
  require($ruta_raiz."librerias/phpmailer/src/Exception.php");
  $mail = new PHPMailer(true); // Passing `true` enables exceptions
  
  try {		
		//Create a new PHPMailer instance
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    //$mail->isSMTP();
    $mail->IsSMTP();
    $mail->SMTPDebug = false;
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
    $mail->Subject = $asunto;
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML(
      "<div>
        <p> ' ".$mensaje."'</p>
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

if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}