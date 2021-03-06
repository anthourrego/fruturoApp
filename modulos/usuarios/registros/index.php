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

  if ($permisos->validarPermiso($usuario['id'], 'usuarios') == 0) {
    header('Location: ' . $ruta_raiz . 'modulos/');
  }

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <?php  
    echo $lib->jquery();
    echo $lib->jqueryUI();
    echo $lib->moment();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->sweetAlert2();
    echo $lib->jqueryValidate(0);
    echo $lib->datatables();
    echo $lib->bootstrapTreeView();
    echo $lib->proyecto();
  ?>
</head>
<body class="content-fruturo">

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container-fluid">
      <button type="button" class="btn btn-secondary mb-1" onclick="back()">
        <i class="fas fa-arrow-left"></i>
        Volver
      </button>
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark">Usuarios</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header d-flex ">
          <div class="input-group w-md-25 w-100 mr-2">
            <select id="filtroEstado" class="custom-select" name="estado " required data-live-search="true" data-size="5" title="Seleccione un estado">
              <option selected value="1">Activos</option>
              <option value="0">Inactivos</option>
            </select>
            <div class="input-group-append">
              <label class="input-group-text" for="filtroEstado">Estado</label>
            </div>
          </div>
          <div class="input-group w-md-25 w-100 mr-2">
            <select id="filtroTipoDocumento" class="custom-select " name="fk_tipo_documento" required data-live-search="true" data-size="5" title="Seleccione un estado">
            </select>
            <div class="input-group-append">
              <label class="input-group-text" for="filtroTipoDocumento">Tipo Doc.</label>
            </div>
          </div>

          <div class="input-group w-md-25 w-100 mr-2">
            <select id="filtroTipoPersona" class="custom-select " name="fk_tipo_persona" required data-live-search="true" data-size="5" title="Seleccione un estado">
            </select>
            <div class="input-group-append">
              <label class="input-group-text" for="filtroTipoPersona">Tipo Pers.</label>
            </div>
          </div>

          <button class="btn btn-success btnCrearUsuario ml-auto" data-toggle="tooltip" title="Crear usuario"><i class="fas fa-user-plus"></i></button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tablaUsuarios" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Tipo documento</th>
                <th scope="col">Nro documento</th>
                <th scope="col">Tipo persona</th>
                <th scope="col">Correo</th>
                <th scope="col">Nombres</th>
                <th scope="col">Apellidos</th>
                <th scope="col">Fecha Nacimineto</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Tipo usuario</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <!-- Modal Crear Usuario -->
  <div class="modal fade" id="modalCrearUsuario" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-user-plus"></i> Crear usuario</h5>
        </div>
        <form id="formCrearUsuario" autocomplete="off">
          <input type="hidden" name="accion" value="crearUsuario">
          <div class="modal-body">
            <div class="form-group">
              <label for="tipo_persona">Tipo persona <span class="text-danger">*</span></label>
              <select name="tipo_persona" required class="custom-select">
                <option value="0" selected disabled>Seleccione una opción</option>
              </select>
            </div>
            <div class="form-group">
              <label for="tipo_documento">Tipo documento <span class="text-danger">*</span></label>
              <select name="tipo_documento" required class="custom-select">
                <option value="0" selected disabled>Seleccione una opción</option>
              </select>
            </div>
            <div class="form-group">
              <label for="nro_documento">Nro documento <span class="text-danger">*</span></label>
              <input type="text" minlength="7" name="nro_documento" class="form-control" placeholder="Escriba un número de documento" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="correo">Correo <span class="text-danger">*</span></label>
              <input type="email" name="correo" class="form-control" placeholder="Escriba un correo" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="nombre">Nombres <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba los nombres" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
              <input type="text" name="apellidos" class="form-control" placeholder="Escriba los apellidos" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="fecha_nacimiento">Fecha Nacimiento <span class="text-danger">*</span></label>
              <input type="text" name="fecha_nacimiento" class="form-control datepicker" placeholder="Fecha Nacimiento" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono <span class="text-danger">*</span></label>
              <input type="tel" name="telefono" class="form-control" placeholder="Escriba un teléfono" required autocomplete="off" onKeyPress="return soloNumeros(event)">
            </div>
            <div class="form-group">
              <label for="perfil">Perfil <span class="text-danger">*</span></label>
              <select name="perfil" required class="custom-select">
                <option value="0" selected disabled>Seleccione una opción</option>
              </select>
            </div>
            
            <label for="pass">Contraseña <span class="text-danger">*</span></label>
            <div class="form-group input-group mb-3">
              <input type="password" id="pass" name="pass" class="form-control" placeholder="Escriba una contraseña" required autocomplete="off">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary btnEye" type="button" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
            <label for="rePass">Confirmar contraseña <span class="text-danger">*</span></label>
            <div class="form-group input-group rounded">
              <input type="password" id="rePass" name="rePass" class="form-control" placeholder="Confirma la contraseña" required autocomplete="off">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary btnEye" type="button" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCrearUsuario" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Crear</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Editar Usuario -->
  <div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-user-edit"></i> Editar usuario</h5>
        </div>
        <form id="formEditarUsuario" autocomplete="off">
          <input type="hidden" name="accion" value="editarUsuario">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="tipo_persona">Tipo persona <span class="text-danger">*</span></label>
              <select name="tipo_persona" required class="custom-select">
                <option value="0" selected disabled>Seleccione una opción</option>
              </select>
            </div>
            <div class="form-group">
              <label for="tipo_documento">Tipo documento <span class="text-danger">*</span></label>
              <select name="tipo_documento" required class="custom-select">
                <option value="0" selected disabled>Seleccione una opción</option>
              </select>
            </div>
            <div class="form-group">
              <label for="nro_documento">Nro documento <span class="text-danger">*</span></label>
              <input type="text" minlength="7" name="nro_documento" class="form-control" placeholder="Escriba un número de documento" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="correo">Correo <span class="text-danger">*</span></label>
              <input type="text" name="correo" class="form-control" placeholder="Escriba un correo" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="nombre">Nombres <span class="text-danger">*</span></label>
              <input type="text" name="nombres" class="form-control" placeholder="Escriba los nombres" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
              <input type="text" name="apellidos" class="form-control" placeholder="Escriba los apellidos" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="fecha_nacimiento">Fecha nacimiento <span class="text-danger">*</span></label>
              <input type="text" name="fecha_nacimiento" class="form-control datepicker" placeholder="Fecha Nacimiento" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono <span class="text-danger">*</span></label>
              <input type="tel" name="telefono" class="form-control" placeholder="Escriba un número de teléfono" onKeyPress="return soloNumeros(event)" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="perfil">Perfil</label>
              <select class="custom-select" name="perfil" required></select>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnEditarUsuario" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Editar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Cambiar Contraseña -->
  <div class="modal fade" id="modalCambiarPass" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-key"></i> Cambiar contraseña</h5>
        </div>
        <form id="formCambiarPass" autocomplete="off">
          <input type="hidden" name="accion" value="cambiarPass">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="correo">Correo</label>
              <input type="text" name="correo" class="form-control" value="Correo" readonly>
            </div>
            <label for="pass">Contraseña <span class="text-danger">*</span></label>
            <div class="form-group input-group mb-3">
              <input type="password" id="cambioPass" name="cambioPass" class="form-control" placeholder="Escriba una contraseña nueva" required autocomplete="off">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary btnEye" type="button" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
            <label for="rePass">Confirmar contraseña <span class="text-danger">*</span></label>
            <div class="form-group input-group rounded">
              <input type="password" id="cambioRePass" name="cambioRePass" class="form-control" placeholder="Confirma la contraseña" required autocomplete="off">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary btnEye" type="button" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCambiarPass" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Crear</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Modal -->
  <div class="modal fade" id="modalPermisoUsuario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-user-lock"></i> Permisos | <span id="modalPermisoUsuarioTitulo">N/A</span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="min-height: 50vh !important;">
          <div class="form-group">
            <input type="input" class="form-control" id="input-search" placeholder="Buscar una permiso" value="" autocomplete="off">
          </div>
          <div id="tree"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  $(function(){
    $(".datepicker").datepicker({ dateFormat: "yy-mm-dd", maxDate: "-18Y" });
    $("#fecha").val(moment().subtract(18, 'years').format("YYYY-MM-DD"));
    $('[data-toggle="tooltip"]').tooltip();

    //Se abre la modal para crear usuarios
    $('.btnCrearUsuario').on("click", function(){
      $("#modalCrearUsuario").modal("show");
    });

    //Editar Usuario
    $(document).on("click", ".btnEditarUsuario", function(){
      let usuario = $(this).data("usuario");
      $("#formEditarUsuario :input").removeClass("is-valid");
      $("#formEditarUsuario :input").removeClass("is-invalid");
      $("#formEditarUsuario :input[name='id']").val(usuario['id']);
      $("#formEditarUsuario :input[name='tipo_persona']").val(usuario['fk_tipo_persona']);
      $("#formEditarUsuario :input[name='nro_documento']").val(usuario['nro_documento']);
      $("#formEditarUsuario :input[name='tipo_documento']").val(usuario['fk_tipo_documento']);
      $("#formEditarUsuario :input[name='correo']").val(usuario['correo']);
      $("#formEditarUsuario :input[name='nombres']").val(usuario['nombres']);
      $("#formEditarUsuario :input[name='apellidos']").val(usuario['apellidos']);
      $("#formEditarUsuario :input[name='fecha_nacimiento']").val(usuario['fecha_nacimiento']);
      $("#formEditarUsuario :input[name='telefono']").val(usuario['telefono']);
      $("#formEditarUsuario :input[name='perfil']").val(usuario['fk_perfil']);
      $("#modalEditarUsuario").modal("show");
    });

    //Validamos el formulario
    $("#formEditarUsuario").validate();

    $("#formEditarUsuario").submit(function(event){
      event.preventDefault();
      if($("#formEditarUsuario").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formEditarUsuario :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnEditarUsuario').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Editando...`);
            $("#btnEditarUsuario").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tablaUsuarios").DataTable().ajax.reload();
              $("#formEditarUsuario")[0].reset();
              $("#formEditarUsuario :input").removeClass("is-valid");
              $("#formEditarUsuario :input").removeClass("is-invalid");
              $("#modalEditarUsuario").modal("hide");
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: data.msj,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            Swal.fire({
              icon: 'error',
              html: 'Error al enviar los datos.'
            });
            //Habilitamos el botón
            $('#formEditarUsuario :input').attr("disabled", false);
            $('#btnEditarUsuario').html(`<i class="fas fa-paper-plane"></i> Editar`);
            $("#btnEditarUsuario").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formEditarUsuario :input').attr("disabled", false);
            $('#btnEditarUsuario').html(`<i class="fas fa-paper-plane"></i> Editar`);
            $("#btnEditarUsuario").attr("disabled", false);
          }
        });
      }
    });

    //Cambiar Contraseña
    $(document).on("click", ".btnCambioPass", function(){
      let usuario = $(this).data("usuario");
      $("#formCambiarPass :input").removeClass("is-valid");
      $("#formCambiarPass :input").removeClass("is-invalid");
      $("#formCambiarPass :input[name='id']").val(usuario['id']);
      $("#formCambiarPass :input[name='correo']").val(usuario['correo']);
      $("#formCambiarPass :input[name='cambioPass']").val();
      $("#formCambiarPass :input[name='cambioRePass']").val();
      $("#modalCambiarPass").modal("show");
    });

    //Validamos el formulario de cambiar contraseña
    $("#formCambiarPass").validate({
      rules: {
        cambioPass: "required",
        cambioRePass: {
          equalTo: "#cambioPass"
        }
      }
    });

    $("#formCambiarPass").submit(function(event){
      event.preventDefault();
      if($("#formCambiarPass").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCambiarPass :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCambiarPass').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cambiando...`);
            $("#btnCambiarPass").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tablaUsuarios").DataTable().ajax.reload();
              $("#formCambiarPass")[0].reset();
              $("#formCambiarPass :input").removeClass("is-valid");
              $("#formCambiarPass :input").removeClass("is-invalid");
              $("#modalCambiarPass").modal("hide");
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: data.msj,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            Swal.fire({
              icon: 'error',
              html: 'Error al enviar los datos.'
            });
            //Habilitamos el botón
            $('#formCambiarPass :input').attr("disabled", false);
            $('#btnCambiarPass').html(`<i class="fas fa-paper-plane"></i> Cambiar`);
            $("#btnCambiarPass").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCambiarPass :input').attr("disabled", false);
            $('#btnCambiarPass').html(`<i class="fas fa-paper-plane"></i> Cambiar`);
            $("#btnCambiarPass").attr("disabled", false);
          }
        });
      }
    });

    //Botón para ver las contraseñas
    $(".btnEye").on("click", function(){
      let input = $(this).parents('.input-group-append').siblings('input');
      let icono = $(this).find('i');
      if ($(this).attr("aria-pressed") == "false") {
        icono.removeClass("fa-eye");
        icono.addClass("fa-eye-slash");
        input.attr("type", "text");
      }else if ($(this).attr("aria-pressed") == "true") {
        icono.removeClass("fa-eye-slash");
        icono.addClass("fa-eye");
        input.attr("type", "password");
      }
    });

    //Validamos el formulario de crear usuario
    $("#formCrearUsuario").validate({
      rules: {
        correo: {
          required: true,
          email: true
        },
        pass: "required",
        rePass: {
          equalTo: "#pass"
        }
      }
    });

    $("#formCrearUsuario").submit(function(event){
      event.preventDefault();
      if($("#formCrearUsuario").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrearUsuario :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrearUsuario').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creando...`);
            $("#btnCrearUsuario").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tablaUsuarios").DataTable().ajax.reload();
              $("#formCrearUsuario")[0].reset();
              $("#formCrearUsuario :input").removeClass("is-valid");
              $("#formCrearUsuario :input").removeClass("is-invalid");
              $("#modalCrearUsuario").modal("hide");
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: data.msj,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            Swal.fire({
              icon: 'error',
              html: 'Error al registrar.'
            });
            //Habilitamos el botón
            $('#formCrearUsuario :input').attr("disabled", false);
            $('#btnCrearUsuario').html(`<i class="fas fa-paper-plane"></i> Crear`);
            $("#btnCrearUsuario").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCrearUsuario :input').attr("disabled", false);
            $('#btnCrearUsuario').html(`<i class="fas fa-paper-plane"></i> Crear`);
            $("#btnCrearUsuario").attr("disabled", false);
          }
        });
      }
    });

    $("#filtroEstado").change(function () {
      top.$('#cargando').modal('show');
      $('#tablaUsuarios').dataTable().fnDestroy();
      lista();
    });

    $('#filtroTipoDocumento').change( function(){
      top.$('#cargando').modal('show');
      $('#tablaUsuarios').dataTable().fnDestroy();
      lista();
    })


    $('#filtroTipoPersona').change( function(){
      top.$('#cargando').modal('show');
      $('#tablaUsuarios').dataTable().fnDestroy();
      lista();
    })





    //Se cargan todas las listas
    lista();
    TiposPerfiles();
    TipoPersonas();
    TiposDocumentos();

    //Permisos de usuario
    // accion boton para abrir arbol y asiganar permisos
    $(document).on("click",".btnPermisos",function(){
      var usuario = $(this).data("usuario");
      $("#modalPermisoUsuarioTitulo").html(usuario['correo']);
      cargarArbol(usuario['id']);
    });
  });

  function cargarArbol(idUsuario){
    top.$("#cargando").modal("show");
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/modulos/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "arbolModulosUsuario",
        idUsuario: idUsuario
      },
      success: function(datos){
        var initSelectableTree = function() {
          return $('#tree').treeview({
            data: datos,
            color: "#428bca",
            showIcon:true,
            showCheckbox:true,
            expanded: true,
            
            //eventos del checked asigna permiso
            onNodeChecked: function(event, node) {
              actualizarPermiso(node.idModulo, idUsuario, 1);
            },

            //eventos cuando se quita el checked quita permiso
            onNodeUnchecked: function (event, node) {
              actualizarPermiso(node.idModulo, idUsuario, 0);
            }
          });
        }

        var $selectableTree = initSelectableTree();

        var findSelectableNodes = function() {
          return $selectableTree.treeview('search', [ $('#input-search').val(), { ignoreCase: true, exactMatch: false } ])
        };
        var selectableNodes = findSelectableNodes();

        $('#input-search').on('keyup', function (e) {
          //$('#tree').treeview('collapseAll', { silent:true });
          selectableNodes = findSelectableNodes();
        });

        $("#modalPermisoUsuario").modal("show");
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han encontrado datos'
        })
      },
      complete: function(){
        cerrarCargando();
      }
    });
  }

  function actualizarPermiso(idPermiso, idUsuario, accionPermiso){
    $.ajax({
      url: '<?php echo($ruta_raiz); ?>modulos/modulos/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion:"actualizarPermiso",
        idUsuario: idUsuario,
        idPermiso: idPermiso,
        accionPermiso: accionPermiso
      },
      success: function(data){
        if (data.success) {
          Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: data.msj,
            showConfirmButton: false,
            timer: 5000
          });
        }else{
          Swal.fire({
            icon: 'warning',
            html: data.msj
          })
        }
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han enviado los datos'
        })
      }
    });
  }

  function elminarUsuario(usuario){
    Swal.fire({
      title: "¿Estas seguro de eliminar el usuario " + usuario['nombres'] + " " + usuario['apellidos'] + "?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<i class="far fa-trash-alt"></i> Si',
      cancelButtonText: '<i class="fa fa-times"></i> No'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "inHabilitarUsuario", 
            id: usuario['id'],
            usuario: usuario['nombres'] + ' ' + usuario['apellidos']
          },
          success: function(data){
            if (data == 1) {
              $("#tablaUsuarios").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "Se ha eliminado el usuario " + usuario['nombres'] + ' ' + usuario['apellidos'],
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al eliminar el usuario " + usuario['nombres'] + ' ' + usuario['apellidos']
              })
            }
          },
          error: function(){
            Swal.fire({
              icon: 'error',
              html: 'No se han enviado los datos'
            })
          }
        });
      }
    });
  }

  function lista(){


    $("#tablaUsuarios").DataTable({
      stateSave: false,
      responsive: true,
      processing: true,
      serverSide: true,
      lengthChange: true,
      pageLength: 10,
      language: {
        url: "<?php echo($ruta_raiz); ?>librerias/dataTables/Spanish.json"
      },
      ajax: {
          url: "acciones",
          type: "GET",
          dataType: "json",
          data: {
            accion: 'listaUsuarios',
            estado: $('#filtroEstado').val(),
            fk_tipo_documento :  $("#filtroTipoDocumento").val() ? $("#filtroTipoDocumento").val() : -1,
            fk_tipo_persona: $("#filtroTipoPersona").val() ? $("#filtroTipoPersona").val() : -1
          },
          complete: function(){
            $('[data-toggle="tooltip"]').tooltip('hide');
            $('[data-toggle="tooltip"]').tooltip();
            cerrarCargando();
          }
      },
      columns: [
        { data: "tipo_documento" },
        { data: "nro_documento" },
        { data: "tipo_persona" },
        { data: "correo" },
        { data: "nombres" },
        { data: "apellidos" },
        { data: "fecha_nacimiento" },
        { data: "telefono" },
        { data: "perfil" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            return `<div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-primary btn-sm mx-1 btnEditarUsuario" data-toggle="tooltip" title="Editar" data-usuario='${JSON.stringify(oData)}'><i class="fas fa-user-edit"></i></button>
                      <button type="button" class="btn btn-info btn-sm mx-1 btnCambioPass" data-toggle="tooltip" data-usuario='${JSON.stringify(oData)}' title="Cambiar contraseña"><i class="fas fa-key"></i></button>
                      <button type="button" class="btn btn-secondary btn-sm mx-1 btnPermisos" data-toggle="tooltip" data-usuario='${JSON.stringify(oData)}' title="Permisos"><i class="fas fa-user-lock"></i></button>
                      <button type="button" data-toggle="tooltip" title="${$("#filtroEstado").val() == 1 ? 'Desactivar' : 'Activar'}" class="btn ${$("#filtroEstado").val() == 1 ? 'fas fa-user-minus btn-danger' : 'fas fa-user-plus btn-primary'} btn-sm mx-1" onClick='cambiarEstado(${JSON.stringify(oData)})' data-toggle="tooltip" title="${$("#filtroEstado").val() == 1 ? 'Inactivar' : 'Activar'}"></button>
                    </div>`;
          }
        
        }
      ],
      dom: 'Bfrtip',
      lengthMenu: [
        [ 10, 25, 50, -1 ],
        [ '10 registros', '25 registros', '50 registros', 'Mostrar todo' ]
      ],
      buttons: [
        'pageLength',
        {
          extend: 'excelHtml5',
          autoFilter: true,
        },
        'pdf',
        'colvis'
      ]
    });
  }

  function TiposPerfiles(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/perfiles/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaPerfiles",
        admin: 1
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

  function TipoPersonas(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/tipo_persona/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaTipoPersona",
      },
      success: function(datos){
        $('#formCrearUsuario :input[name="tipo_persona"], #formEditarUsuario :input[name="tipo_persona"]').empty();
        $('#formCrearUsuario :input[name="tipo_persona"], #formEditarUsuario :input[name="tipo_persona"]').append(`<option value="0" selected disabled>Seleccione una opción</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $('#formCrearUsuario :input[name="tipo_persona"], #formEditarUsuario :input[name="tipo_persona"]').append(`<option value="${datos.msj[i].id}">${datos.msj[i].nombre}</option>`);
          }

          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $("#filtroTipoPersona").append(`<option value="${datos.msj[i].id}">${datos.msj[i].nombre}</option>`);
          }

          $("#filtroTipoPersona").append(`<option value="-1">${'Todos'}</option>`);
          $("#filtroTipoPersona").val(-1)
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


        $('#formCrearUsuario :input[name="tipo_documento"], #formEditarUsuario :input[name="tipo_documento"]').empty();
        $('#formCrearUsuario :input[name="tipo_documento"], #formEditarUsuario :input[name="tipo_documento"]').append(`<option value="0" selected disabled>Seleccione una opción</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $('#formCrearUsuario :input[name="tipo_documento"], #formEditarUsuario :input[name="tipo_documento"]').append(`<option value="${datos.msj[i].id}">${datos.msj[i].abreviacion} - ${datos.msj[i].nombre}</option>`);
          }

          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $("#filtroTipoDocumento").append(`<option value="${datos.msj[i].id}">${datos.msj[i].abreviacion} - ${datos.msj[i].nombre}</option>`);
          }
        }
        $("#filtroTipoDocumento").append(`<option value="-1">${'Todos'}</option>`);
        $("#filtroTipoDocumento").val(-1)
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

  function cambiarEstado(datos){
    Swal.fire({
    title: `¿Estas seguro de ${ $("#filtroEstado").val() == 1 ? 'inhabilitar' : 'habilitar'} ${datos['nombres']}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: `<i class="fas ${ $("#filtroEstado").val() == 1 ? 'fa-trash-alt' : 'fa-check'}"></i> Si`,
    cancelButtonText: '<i class="fa fa-times"></i> No'
    }).then((result) => {
      if (result.value) {

        $.ajax({
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "cambiarEstado", 
            id: datos['id'],
            nombre: datos['nombres'],
            // Nuevo estado
            estado: $("#filtroEstado").val() == 1 ? 0 : 1
          },
          success: function(data){
            if (data == 1) {
              $("#tablaUsuarios").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: `Se ha ${ $("#filtroEstado").val() == 1 ? 'inhabilitado' : 'habilitado'} el municipio ${datos['nombres']}`,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: `Error al ${$("#filtroEstado").val() == 1 ? 'inhabilitar' : 'habilitar'} el municipio ${datos['nombres']}`
              })
            }
          },
          error: function(){
            Swal.fire({
              icon: 'error',
              html: 'No se han enviado los datos'
            })
          }
        });
      }
    });
  }

  function back(){
    var url = "<?php echo($ruta_raiz); ?>modulos/ofertar";
    location.href = url;
  }

</script>
</html>