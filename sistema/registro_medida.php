<?php
include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['medida']) || empty($_POST['nombrecorto']) ) {
        mensajeicono('El campo medida y nombre corto son obligatorios.', 'registro_medida.php','','info');

    } else {
        $medida = $_POST['medida'];
        $nombrecorto = $_POST['nombrecorto'];

        $query_insert = mysqli_query($conexion, "INSERT INTO cat_unidadmedida(unidadmedida,activo, nombrecorto) values ('$medida', '1','$nombrecorto' )");
        if ($query_insert) {
            historia('Se registro la nueva medida '.$medida);
            mensajeicono('Se ha registrado con éxito la nueva medida!', 'lista_medida.php','','exito');

        } else {
            historia('Error al intentar registrar la nueva medida '.$medida);
            mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_medida.php','','error');

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
                Registro de Unidad de Medida
            </div>
            <div class="card_div">
                <form action="" autocomplete="off" method="post" class="card-body p-2">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="mmeida">Nombre</label>
                        <input type="text" placeholder="Ingrese nombre" name="medida" id="medida" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombrecorto">Nombre Corto</label>
                        <input type="text" placeholder="Ingrese nombre un nombre corto" name="nombrecorto" id="nombrecorto" class="form-control" onkeypress="mayus(this);">
                    </div>
                   
                    <input type="submit" value="Guardar Medida" class="btn btn-primary">
                    <a href="lista_medida.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>