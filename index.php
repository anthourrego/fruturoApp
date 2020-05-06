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
    <title>Ingresar | Consumer Electronics Group S.A.S</title>
    <?php  
      echo $lib->jquery();
      echo $lib->bootstrap();
      echo $lib->jqueryValidate();
      echo $lib->alertify();
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
      .form-label-group>button {
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

      #video{
        /*filter: blur(3px);*/
        position: absolute;
        bottom: 0px;
        right: 0px;
        min-width: 100%;
        min-height: 100%;
        width: 100%;
        height: auto;
        z-index: -1000;
        overflow: hidden;
      }

    </style>
  </head>
  <body>
    <div class="container-fluid position-absolute">
      <div class="row no-gutter">
        <div class="d-none d-md-flex col-md-6 p-0">
          <img class="w-100 vh-100" src="assets/img/index.png">
        </div>
        <div class="col-md-6">
          <div class="login d-flex align-items-center py-5">
            <div class="container">
              <div class="row">
                <div class="col-md-9 col-lg-6 mx-auto">
                  <div class="text-center">
                    <img class="w-50 mb-5" src="assets/img/logo.svg">
                  </div>
                  <form id="formLogin" autocomplete="off">
                    <input type="hidden" name="accion" value="iniciarSession">
                    <div class="form-label-group">
                      <input type="text" name="correo" class="form-control" placeholder="Correo electrónico" required autofocus>
                      <label for="correo">Correo electrónico</label>
                    </div>

                    <div class="form-label-group input-group">
                      <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
                      <label for="password">Contraseña</label>
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary btn-login" type="button" id="btnEye" data-toggle="button" aria-pressed="false" autocomplete="off"><i id="passicon" class="fas fa-eye"></i></button>
                      </div>
                    </div>

                    <button class="btn btn-lg btn-verdeOscuro btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Ingresar <i class="fas fa-sign-in-alt"></i></button>

                    <div class="text-center mt-4">
                      ¿Aún no tienes una cuenta? <a href="">Registrarse</a>
                    </div>

                  </form>
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
    $("#formLogin").submit(function(event){
      event.preventDefault();
      if($("#formLogin").valid()){
        //Desabilitamos el botón
        $("#btn-login").attr("Disabled" , true);
        
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          success: function(data){
            if (data.resp == 1) {
              $.ajax({
                url: '<?php echo($ruta_raiz); ?>ajax/login.php',
                type: 'POST',
                dataType: 'html',
                data: {array: data},
                success: function(funca){
                  if (funca == "Ok") {
                    window.location.href = '<?php echo($ruta_raiz); ?>central';
                  }else{
                    //Habilitamos el botón
                    $("#btn-login").attr("Disabled", false);
                    alertify.error(funca);
                  }
                },
                error: function(){
                  alertify.error("No se ha podido validar la sesion.");
                }
              });
            }else{
              alertify.error(data.resp);
            }
          },
          error: function(){
            //Habilitamos el botón
            $("#btn-login").attr("Disabled", false);
            alertify.error("Error al inicar sesion.");
          } 
        });
      }
    });

    $("#btnEye").on("click", function(){
      if ($("#btnEye").attr("aria-pressed") == "false") {
        $("#passicon").removeClass("fa-eye");
        $("#passicon").addClass("fa-eye-slash");
        $("#formLogin :input[name='password']").attr("type", "text");
      }else if ($("#btnEye").attr("aria-pressed") == "true") {
        $("#passicon").removeClass("fa-eye-slash");
        $("#passicon").addClass("fa-eye");
        $("#formLogin :input[name='password']").attr("type", "password");
      }
    });
  });
</script>
</html>