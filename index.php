<?php  
  @session_start();
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

  require_once($ruta_raiz . 'clases/librerias.php');
  require_once($ruta_raiz . 'clases/Session.php');
  
  $lib = new Libreria;
  $session = new Session();

  if(@$session->exist('usuario')){
    header('location: '. $ruta_raiz . 'central');
    die();
  }
?>    

<!doctype html>
<html lang="es">
  <head>
    <?php  
      echo $lib->metaTagsRequired();
      echo $lib->iconoPag();
    ?>  
    <title>Ingresar | Fruturo</title>
    <?php  
      echo $lib->jquery();
      echo $lib->bootstrap();
      echo $lib->jqueryUI();
      echo $lib->moment();
      echo $lib->jqueryValidate('form-label-group');
      echo $lib->alertify();
      echo $lib->sweetAlert2();
      echo $lib->proyecto();
      echo $lib->fontAwesome();
    ?>

    <style>
      :root {
        --input-padding-x: 1.5rem;
        --input-padding-y: 0.75rem;
      }

      .login {
        min-height: 100vh;
      }

      .btn-login {
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
        border-radius: 2rem;
      }

      .form-label-group {
        position: relative;
        margin-bottom: 1rem;
      }

      .form-label-group>input,
      .form-label-group>label,
      .form-label-group>button,
      .form-label-group>select {
        padding: var(--input-padding-y) var(--input-padding-x);
        height: auto;
        border-radius: 2rem;
      }

      .form-label-group>label {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100%;
        margin-bottom: 0;
        /* Override default `<label>` margin */
        line-height: 1.5;
        color: #495057;
        cursor: text;
        /* Match the input under the label */
        border: 1px solid transparent;
        border-radius: .25rem;
        transition: all .1s ease-in-out;
      }

      .form-label-group input::-webkit-input-placeholder {
        color: transparent;
      }

      .form-label-group input:-ms-input-placeholder {
        color: transparent;
      }

      .form-label-group input::-ms-input-placeholder {
        color: transparent;
      }

      .form-label-group input::-moz-placeholder {
        color: transparent;
      }

      .form-label-group input::placeholder {
        color: transparent;
      }

      .form-label-group input:not(:placeholder-shown) {
        padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
        padding-bottom: calc(var(--input-padding-y) / 3);
      }

      .form-label-group input:not(:placeholder-shown)~label {
        padding-top: calc(var(--input-padding-y) / 3);
        padding-bottom: calc(var(--input-padding-y) / 3);
        font-size: 12px;
        color: #777;
      }

    </style>
  </head>
  <body>
    <div class="container-fluid position-absolute">
      <div class="row no-gutter">
        <div class="d-none d-lg-flex col-lg-6 p-0">
          <img class="w-100 vh-100" src="assets/img/index.png">
        </div>
        <div class="col-12 col-lg-6 overflow-auto" style="max-height: 100vh !important">
          <div class="login d-flex align-items-center py-5">
            <div class="container">
              <div class="row">
                <div id="contentLogin" class="col-12 col-md-6 mx-auto">
                  <div class="text-center">
                    <img class="w-50 mb-5" src="assets/img/logo.svg">
                  </div>
                  <form id="formLogin" autocomplete="off">
                    <input type="hidden" name="accion" value="iniciarSesion">
                    <div class="form-label-group">
                      <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo electrónico" required autocomplete="off">
                      <label for="correo">Correo electrónico</label>
                    </div>

                    <div class="form-label-group">
                      <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required autocomplete="new-password">
                      <label for="password">Contraseña</label>
                    </div>

                    <button class="btn btn-lg btn-verdeOscuro btn-block btn-login text-uppercase font-weight-bold mb-2" id="btn-inciar" type="submit">
                      Ingresar <i class="fas fa-sign-in-alt"></i>
                    </button>

                    <div class="text-center mt-4">
                      ¿Aún no tienes una cuenta? <a href="?reg=1">Registrarse</a> <br><br>
                      <!-- <a href="?reg=1">¿Has olvidado tu contraseña?</a> -->
                    </div>
                  </form>
                  <p class="mt-5 mb-3 text-muted text-center">2020 &copy; Fruturo</p>
                </div>

                <div id="contentRegistro" class="col-12 col-lg-11 col-xl-9 mx-auto">
                  <div class="text-center">
                    <img class="w-30 mb-5" src="assets/img/logo.svg">
                  </div>
                  <form id="formRegistro" class="form-row" autocomplete="off">
                    <input type="hidden" name="accion" value="registrarse">
                    <div class="form-label-group col-12 col-lg-6">
                      <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres" autocomplete="off" required>
                      <label for="nombres">Nombres<span class="text-danger">*</span></label>
                    </div>

                    <div class="form-label-group col-12 col-lg-6">
                      <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Apellidos" autocomplete="off" required>
                      <label for="apellidos">Apellidos<span class="text-danger">*</span></label>
                    </div>
                    
                    <div class="form-label-group col-12 col-lg-6">
                      <input type="text" id="fecha" name="fecha" class="form-control datepicker" placeholder="Fecha Nacimiento" autocomplete="off" required>
                      <label for="fecha">Fecha Nacimiento<span class="text-danger">*</span></label>
                    </div>
                    
                    <div class="form-label-group col-12 col-lg-6">
                      <input type="tel" id="tel" name="tel" class="form-control" placeholder="Teléfono" autocomplete="off" required onKeyPress="return soloNumeros(event)">
                      <label for="tel">Teléfono<span class="text-danger">*</span></label>
                    </div>

                    <div class="form-label-group col-12 col-lg-6">
                      <select class="custom-select" name="perfil" id="perfil" required>
                        <option value="0" selected disabled>Seleccione un tipo de perfil</option>
                        <option value="2">Productor</option>
                        <option value="3">Comercializador</option>
                      </select>
                    </div>

                    <div class="form-label-group col-12 col-lg-6">
                      <input type="email" id="reCorreo" name="reCorreo" class="form-control" placeholder="Correo electrónico" autocomplete="off" required>
                      <label for="reCorreo">Correo electrónico<span class="text-danger">*</span></label>
                    </div>

                    <div class="form-label-group col-12 col-lg-6">
                      <input type="password" id="rePassword" name="rePassword" class="form-control" placeholder="Contraseña" required autocomplete="new-password">
                      <label for="rePassword">Contraseña<span class="text-danger">*</span></label>
                    </div>

                    <div class="form-label-group col-12 col-lg-6">
                      <input type="password" id="rerePassword" name="rerePassword" class="form-control" placeholder="Confirmar Contraseña" required autocomplete="new-password">
                      <label for="rerePassword">Confirmar Contraseña<span class="text-danger">*</span></label>
                    </div>

                    <div class="col-12 col-lg-6 mx-auto text-center">
                      <button class="btn btn-lg btn-verdeOscuro btn-block btn-login text-uppercase font-weight-bold mb-2" id="btn-registro" type="submit">
                        Registrarse <i class="fas fa-sign-in-alt"></i>
                      </button>
                    </div>
                  </form>
                  <div class="text-center mt-4">
                    ¿Ya tienes una cuenta? <a href="?reg=0">Iniciar Sesión</a>
                  </div>
                  <p class="mt-5 mb-3 text-muted text-center">2020 &copy; Fruturo</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

<script type="text/javascript">
  $(function(){
    $(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });
    $("#fecha").val(moment().format("YYYY-MM-DD"));

    $("#contentRegistro, #contentLogin").hide();
    if (getUrl('reg') == 1) {
      $("#contentRegistro").show(1000);
      $("#re-correo").focus();
    }else{
      $("#contentLogin").show(1000);
      $("#correo").focus();
    }

    $("#formLogin").submit(function(event){
      event.preventDefault();
      if($("#formLogin").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formLogin :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btn-inciar').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iniciando...`);
            $("#btn-inciar").attr("disabled" , true);
          },
          success: function(data){
            if (data.success ) {
              window.location.href = '<?php echo($ruta_raiz); ?>central';
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            alertify.error("Error al inicar sesion.");
          },
          complete: function(){
            //Habilitamos el botón
            $('#formLogin :input').attr("disabled", false);
            $('#btn-inciar').html(`Ingresar <i class="fas fa-sign-in-alt"></i>`);
            $("#btn-inciar").attr("disabled", false);
          } 
        });
      }
    });

    $("#formRegistro").validate({
      rules: {
        tel: {
          required: true,
          number: true
        },
        reCorreo: {
          required: true,
          email: true
        },
        rePassword: "required",
        rerePassword: {
          equalTo: "#rePassword"
        }
      }
    });

    $("#formRegistro").submit(function(event){
      event.preventDefault();
      if($("#formRegistro").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formRegistro :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btn-registro').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registrarse...`);
            $("#btn-registro").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              Swal.fire({
                icon: 'success',
                html: data.msj,
                preConfirm: () => {
                  location.href = '?reg=0'
                }
              })
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            alertify.error("Error al registrar.");
          },
          complete: function(){
            //Habilitamos el botón
            $('#formRegistro :input').attr("disabled", false);
            $('#btn-registro').html(`Registrarse <i class="fas fa-sign-in-alt"></i>`);
            $("#btn-registro").attr("disabled", false);
          }
        });
      }
    });
  });
</script>
</html>