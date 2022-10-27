<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        mensajeicono('El campo nombre, teléfono y dirección son obligatorios.', 'registro_cliente.php','','info');

    } else {
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $usuario_id = $_SESSION['idUser'];

        $result = 0;
        if (is_numeric($dni) and $dni != 0) {
            $query = mysqli_query($conexion, "SELECT * FROM cliente where dni = '$dni'");
            $result = mysqli_fetch_array($query);
        }
        if ($result > 0) {
            mensajeicono('El identificador del cliente ya existe.', 'registro_cliente.php','','info');
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO cliente(dni,nombre,telefono,direccion, usuario_id, fecha) values ('$dni', '$nombre', '$telefono', '$direccion', '$usuario_id', now())");
            if ($query_insert) {
                historia('Se registro el nuevo cliente '.$dni);
                mensajeicono('Se ha registrado con éxito la nuevo cliente!', 'lista_cliente.php','','exito');

            } else {
                historia('Error al intentar registrar el nuevo cliente '.$dni);
                mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_cliente.php','','error');
            }
        }
    }
    mysqli_close($conexion);
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
        <a href="lista_cliente.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card_div">
                <div class="card-header bg-primary">
                    Nuevo Cliente
                </div>
                <div class="card-body">
                    <form action="" method="post" autocomplete="off">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <div class="form-group">
                            <label for="dni">Identificador cliente</label>             
                             <input type="number" placeholder="Ingrese dni " name="dni" id="dni" readonly class="form-control"  >                            
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="number" placeholder="Ingrese Teléfono" name="telefono" id="telefono" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control">
                        </div>
                        <input type="submit" value="Guardar Cliente" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>

<script>


    $( document ).ready(function() {
    console.log( "ready!" );

    var action = 'nextIdcliente';
     $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action},
    success: function(response) {
    //console.log(response);
    var data = $.parseJSON(response);
        $('#dni').val(data);
        $('#dni').val(data);
       // $('#dni').attr('disabled','disabled');
        
    },
    error: function(error) {
    }
    });

});

    </script>