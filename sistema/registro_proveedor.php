<?php
include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['proveedor']) ) {
        mensajeicono('El campo nombre es requerido.', 'lista_proveedor.php','','info');

    } else {
        $proveedor = $_POST['proveedor'];
        $usuario_id = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "SELECT * FROM proveedor where proveedor = '$proveedor'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            mensajeicono('Ya existe un cubo con este mismo nombre.', 'lista_proveedor.php','','info');
        }else{
        

        $query_insert = mysqli_query($conexion, "INSERT INTO proveedor(proveedor,usuario_id) values ('$proveedor', '$usuario_id')");
        if ($query_insert) {
            historia('Se registro el nuevo proveedor '.$proveedor);
            mensajeicono('Se ha registrado con éxito el proveedor!', 'lista_proveedor.php','','exito');

        } else {
            historia('Error al intentar registrar el nuevo proveedor '.$proveedor);
            mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_proveedor.php','','error');
            
        }
        }
    }
}
mysqli_close($conexion);
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card-header bg-primary text-white">
                Registro de Proveedor
            </div>
            <div class="card_div">
                <form action="" autocomplete="off" method="post" class="card-body p-2">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">NOMBRE</label>
                        <input type="text" placeholder="Ingrese nombre" name="proveedor" id="nombre" class="form-control">
                    </div>
                   
                    <input type="submit" value="Guardar Proveedor" class="btn btn-primary">
                    <a href="lista_proveedor.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>