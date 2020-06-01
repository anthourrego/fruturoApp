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
    echo $lib->jqueryValidate();
    echo $lib->datatables();
    echo $lib->bootstrapTreeView();
    echo $lib->proyecto();
  ?>
</head>
<body>

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="far fa-lemon"></i> Ofertar Cosechas</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header d-flex justify-content-end">
          <button class="btn btn-success btnCrear" data-toggle="tooltip" title="Crear"><i class="fas fa-plus"></i> Crear oferta</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Producto</th>
                <th scope="col">Finca</th>
                <th scope="col">Volumen total</th>
                <th scope="col">Precio</th>
                <th scope="col">Inicio cosecha</th>
                <th scope="col">Fin cosecha</th>
                <th scope="col">Fecha creación</th>
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

  <!-- Modal Producto -->
  <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal"><i class="fas fa-plus"></i> Crear oferta cosecha</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="producto">Producto <span class="text-danger">*</span></label>
              <select class="custom-select" required name="producto">
                <option value="0" disabled selected>Seleccion un opción</option>
              </select>
            </div>
            <div class="form-group">
              <label for="terreno">Finca <span class="text-danger">*</span></label>
              <select class="custom-select" required name="terreno">
                <option value="0" disabled selected>Seleccion un opción</option>
              </select>
            </div>
            <div class="form-group">
              <label for="fecha_inicio">Fecha de inicio de la cosacha <span class="text-danger">*</span></label>
              <input type="text" name="fecha_inicio" class="form-control datepicker" placeholder="Escriba una fecha aproximada del incio de la cosecha" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="fecha_fin">Fecha de fin de la cosacha <span class="text-danger">*</span></label>
              <input type="text" name="fecha_fin" class="form-control datepicker" placeholder="Escriba una fecha aproximada del final de la cosecha" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="volumen_total">Volumen total en Kilogramos <span class="text-danger">*</span></label>
              <input type="tel" name="volumen_total" class="form-control" placeholder="Escriba el número de kilogramos" onKeyPress="return soloNumeros(event)" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="precio">Precio por kilogramos <span class="text-danger">*</span></label>
              <input type="tel" name="precio" class="form-control" placeholder="Escriba el precio de la cosecha por kilogramos" onKeyPress="return soloNumeros(event)" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="certificados">Certificados:</label>
              <div id="certificados" class="row"></div>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCrear" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  $(function(){
    $(".datepicker").datepicker({ dateFormat: "yy-mm-dd"});
    $("#formCrear :input[name='fecha_inicio']").val(moment().format("YYYY-MM-DD"));
    $("#formCrear :input[name='fecha_fin']").val(moment().format("YYYY-MM-DD"));

    //Se abre la modal para crear productos
    $('.btnCrear').on("click", function(){
      $("#tituloModal").html(`<i class="fas fa-plus"></i> Crear oferta cosecha`);
      $("#formCrear :input[name='accion']").val('crear');
      $("#modalCrear").modal("show");
    });

    $("#formCrear").submit(function(event){
      event.preventDefault();
      if($("#formCrear").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrear :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrear').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...`);
            $("#btnCrear").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tabla").DataTable().ajax.reload();
              $("#formCrear")[0].reset();
              $("#formCrear :input").removeClass("is-valid");
              $("#formCrear :input").removeClass("is-invalid");
              $("#modalCrear").modal("hide");
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
            $('#formCrear :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrear").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCrear :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Enviar`);
            $("#btnCrear").attr("disabled", false);
          }
        });
      }
    });

    lista();
    listaTerrenos();
    listaProductos();
    checkCertificaciones();
  });

  function lista(){
    $("#tabla").DataTable({
      stateSave: true,
      responsive: true,
      processing: true,
      serverSide: true,
      pageLength: 25,
      language: {
        url: "<?php echo($ruta_raiz); ?>librerias/dataTables/Spanish.json"
      },
      ajax: {
          url: "acciones",
          type: "GET",
          dataType: "json",
          data: {
            accion: 'lista'
          },
          complete: function(){
            cerrarCargando();
          }
      },
      columns: [
          { data: "id" },
          { data: "producto" },
          { data: "finca" },
          { data: "volumen_total" },
          { data: "precio" },
          { data: "fecha_inicio" },
          { data: "fecha_final" },
          { data: "fecha_creacion" },
          {
            "render": function (nTd, sData, oData, iRow, iCol) {
              return `<div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm mx-1" onClick='eliminar(${JSON.stringify(oData)})'><i class="fas fa-trash-alt"></i> Cancelar</button>
                      </div>`;
            }
          }
      ],
      columnDefs: [
        {
          className: "dt-center",
          targets: "_all"
        },
        {
          targets: [0],
          visible: false
        }
      ],
      lengthChange: true,
      order: [
        [0, "asc"]
      ], //Ordenar (columna,orden)
    });
  }

  function listaTerrenos(){
    //Se cargan los terrenos
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/ofertar/fincas/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "tusTerrenos"
      },
      success: function(data){
        if (data.success) {
          $("#formCrear :input[name='terreno']").empty();
          $("#formCrear :input[name='terreno']").append(`<option value="0" selected disabled>Seleccione un opción</option>`);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formCrear :input[name='terreno']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
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

  function listaProductos(){
    //Se cargan los productos
    $.ajax({
      url: '<?php echo($ruta_raiz) ?>modulos/productos/acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "listaProdcutos"
      },
      success: function(data){
        if (data.success) {
          $("#formCrear :input[name='producto']").empty();
          $("#formCrear :input[name='producto']").append(`<option value="0" selected disabled>Seleccione un opción</option>`);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $("#formCrear :input[name='producto']").append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
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

  function eliminar(datos){
    Swal.fire({
      title: "¿Estas seguro de cancelar la oferta?",
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
            accion: "eliminar", 
            id: datos['id']
          },
          success: function(data){
            if (data == 1) {
              $("#tabla").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "Se ha cancelado",
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al cancelar"
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

  function checkCertificaciones(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/certificados/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaCertificados"
      },
      success: function(data){
        if (data.success) {
          console.log(data);
          $('#certificados').empty();
          for (let i = 0; i < data.msj['cantidad_registros']; i++) {
            $('#certificados').append(`
              <div class="col-12 col-lg-6">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="certificado[]" class="custom-control-input" value="${data.msj[i].id}" id="certificado${data.msj[i].id}">
                  <label class="custom-control-label" for="certificado${data.msj[i].id}">${data.msj[i].nombre}</label>
                </div>
              </div>
            `);
          }
        }else{
          Swal.fire({
            icon: 'warning',
            html: data.msj
          })
        }
      },
      error: function(data){
        Swal.fire({
          icon: 'error',
          html: 'No se han enviado los datos'
        })
      }
    });
  }
</script>
</html>