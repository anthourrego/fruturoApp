<?php  
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

  include_once($ruta_raiz . 'clases/librerias.php');
  include_once($ruta_raiz . 'clases/sessionActiva.php');
  include_once($ruta_raiz . 'clases/Permisos.php');

  $session = new Session();
  $lib = new Libreria;
  $permisos = new Permisos();

  $usuario = $session->get("usuario");
?>

<!doctype html>
<html lang="es">
<head>
  <title>Editar Perfil</title>
  <?php  
    echo $lib->jquery();
    echo $lib->jqueryUI();
    echo $lib->bootstrap();
    echo $lib->sweetAlert2();
    echo $lib->fontAwesome();
    echo $lib->jqueryValidate(0);
    echo $lib->proyecto();
  ?>
</head>
<body class="content-fruturo">
  <!-- Content Header (Page header) -->
  <div div class="content-header mt-3">
    <div class="container">
      <button type="button" class="btn btn-secondary mb-1" onclick="back()">
        <i class="fas fa-arrow-left"></i>
        Volver
      </button>
      <div class="row mb-2">
        <div class="col-12">
          <h2 class="m-0 text-dark">Editar perfil</h2>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container">
      <hr>
      <form id="formEditarUsuario" autocomplete="off" class="row">
        <input type="hidden" name="accion" value="editarUsuario">
        <input type="hidden" name="id" value="0">
        <div class="form-group col-12 col-sm-6">
          <label for="tipoDocumento">Tipo documento <span class="text-danger">*</span></label>
          <select name="tipoDocumento" required class="custom-select">
            <option value="0" selected disabled>Seleccione una opción</option>
          </select>
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="nroDocumento">Nro documento <span class="text-danger">*</span></label>
          <input type="text" minlength="7" name="nroDocumento" class="form-control" placeholder="Escriba un número de documento" required autocomplete="off">
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="tipoPersona">Tipo persona <span class="text-danger">*</span></label>
          <select name="tipoPersona" required class="custom-select">
            <option value="0" selected disabled>Seleccione una opción</option>
          </select>
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="correo">Correo <span class="text-danger">*</span></label>
          <input type="email" name="correo" class="form-control" placeholder="Escriba un correo" required autocomplete="off">
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="nombres">Nombres <span class="text-danger">*</span></label>
          <input type="text" name="nombres" class="form-control" placeholder="Escriba los nombres" required autocomplete="off">
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
          <input type="text" name="apellidos" class="form-control" placeholder="Escriba los apellidos" required autocomplete="off">
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="fecha_nacimiento">Fecha Nacimiento <span class="text-danger">*</span></label>
          <input type="text" name="fechaNacimiento" class="form-control datepicker" placeholder="Fecha Nacimiento" required autocomplete="off">
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="telefono">Teléfono <span class="text-danger">*</span></label>
          <input type="tel" name="telefono" class="form-control" placeholder="Escriba un teléfono" required autocomplete="off" onKeyPress="return soloNumeros(event)">
        </div>
        <div class="form-group col-12 col-sm-6">
          <label for="perfil">Perfil <span class="text-danger">*</span></label>
          <select name="perfil" required class="custom-select">
            <option value="0" selected disabled>Seleccione una opción</option>
          </select>
        </div>
        <div class="d-flex justify-content-end align-items-center col-12">
          <button id="btnEditarPerfil" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Editar</button>
        </div>
      </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  $(function (){
    TipoPersonas();
    TiposDocumentos();
    TiposPerfiles();
    obtenerUsuario();

    $("#formEditarUsuario").submit(function (event) {
      event.preventDefault();
      if ($("#formEditarUsuario").valid()) {
        $.ajax({
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          data: new FormData(this),
          beforeSend: function () {
            //Deshabilito el boton de editar
            $("#btnEditarPerfil").attr("disabled" , true);
          },
          success: function (data) {
            if (data['success']) {
              Swal.fire({
                icon: 'success',
                html: data['msj']
              });

              setTimeout(function(){ top.window.location.reload(); }, 900);
              
            }else{
              Swal.fire({
                icon: 'warning',
                html: data.msj
              })
            }
            $("#btnEditarPerfil").attr("disabled" , false);
          },
          error : function () {
            Swal.fire({
              icon: 'error',
              html: 'Error editando el perfil'
            })
            $("#btnEditarPerfil").attr("disabled" , false);
          },
          complete: function () {
            $("#btnEditarPerfil").attr("disabled" , false);
          }
        });
      }
    });

  });

  function TipoPersonas(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/tipo_persona/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaTipoPersona",
      },
      success: function(datos){
        $('#formEditarUsuario :input[name="tipoPersona"], #formEditarUsuario :input[name="tipoPersona"]').empty();
        $('#formEditarUsuario :input[name="tipoPersona"], #formEditarUsuario :input[name="tipoPersona"]').append(`<option value="0" selected disabled>Seleccione una opción</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $('#formEditarUsuario :input[name="tipoPersona"], #formEditarUsuario :input[name="tipoPersona"]').append(`<option value="${datos.msj[i].id}">${datos.msj[i].nombre}</option>`);
          }
        }
      },
      error: function(e){
        console.log(e);
        Swal.fire({
          icon: 'error',
          html: 'Error al cargar los datos de tipo persona'
        })
      }
    });
  }

  function TiposDocumentos(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/tipo_documento/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaTipoDocumento",
      },
      success: function(datos){
        $('#formEditarUsuario :input[name="tipoDocumento"]').empty();
        $('#formEditarUsuario :input[name="tipoDocumento"]').append(`<option value="0" selected disabled>Seleccione una opción</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $('#formEditarUsuario :input[name="tipoDocumento"]').append(`<option value="${datos.msj[i].id}">${datos.msj[i].abreviacion} - ${datos.msj[i].nombre}</option>`);
          }
        }
      },
      error: function(e){
        console.log(e);
        Swal.fire({
          icon: 'error',
          html: 'Error al cargar los datos de tipo documento'
        });
      }
    });
  }

  function TiposPerfiles(){

    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/perfiles/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaPerfiles",
        admin: <?php echo($usuario["perfil"]); ?>
      },
      success: function(datos){
        $('#formCrearUsuario :input[name="perfil"], #formEditarUsuario :input[name="perfil"]').empty();
        $('#formCrearUsuario :input[name="perfil"], #formEditarUsuario :input[name="perfil"]').append(`<option value="0" selected disabled>Seleccione una opción</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $('#formCrearUsuario :input[name="perfil"], #formEditarUsuario :input[name="perfil"]').append(`<option value="${datos.msj[i].id}">${datos.msj[i].nombre}</option>`);
          }
        }
      },
      error: function(e){
        console.log(e);
        Swal.fire({
          icon: 'error',
          html: 'Error al cargar los datos de perfiles'
        })
      }
    });
  }

  function obtenerUsuario() {
    $.ajax({
      url: "acciones",
      type: "GET",
      dataType: "json",
      data: {
        accion: "obtenerDatosUsuario",
        id: <?php echo($usuario['id']) ?>,
      },
      success: function (usuario) {
        if (usuario) {
          $("#formEditarUsuario :input[name='id']").val(usuario['id']);
          $("#formEditarUsuario :input[name='tipoPersona']").val(usuario['fk_tipo_persona']);
          $("#formEditarUsuario :input[name='tipoDocumento']").val(usuario['fk_tipo_documento']);
          $("#formEditarUsuario :input[name='nroDocumento']").val(usuario['nro_documento']);
          $("#formEditarUsuario :input[name='correo']").val(usuario['correo']);
          $("#formEditarUsuario :input[name='nombres']").val(usuario['nombres']);
          $("#formEditarUsuario :input[name='apellidos']").val(usuario['apellidos']);
          $("#formEditarUsuario :input[name='fechaNacimiento']").val(usuario['fecha_nacimiento']);
          $("#formEditarUsuario :input[name='telefono']").val(usuario['telefono']);
          $("#formEditarUsuario :input[name='perfil']").val(usuario['fk_perfil']);
          $("#formEditarUsuario :input[name='tipoDocumento']").attr("disabled", true);
          $("#formEditarUsuario :input[name='fechaNacimiento']").attr("disabled", true);
          $("#formEditarUsuario :input[name='nroDocumento']").attr("disabled", true);
          //$("#formEditarUsuario :input[name='perfil']").attr("disabled", true);
        }
      },
      error: function () {
        cerrarCargando();
        Swal.fire({
          icon: 'error',
          html: 'No se encontro usuario'
        })
      },
      complete: function () {
        cerrarCargando();
      }
    });
  }

  function back(){
    var url = "<?php echo($ruta_raiz); ?>modulos/ofertar";
    location.href = url;
  }
  
</script>
</html>