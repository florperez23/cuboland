<?php include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['fecha']) || empty($_POST['cubo'])) {
        mensajeicono('Todos los campos son obligatorios.', 'registro_arrendatario.php','','info');

    } else {
        
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['fecha'];
        $codcubo = $_POST['cubo'];
        $usuario_id = $_SESSION['idUser'];

        $idarrendatario = nrentero(TRUE);
    
       $sql = "INSERT INTO arrendatarios(idarrendatario, nombre,telefono,codcubo, fechaingreso, registro) values ('$idarrendatario','$nombre', '$telefono', '$codcubo', '$fecha','$usuario_id')";
       echo $sql;
        $query_insert = mysqli_query($conexion, $sql );
        if ($query_insert) {
            historia('Se registro el nuevo arrendatario '.$nombre);
            $sql = "UPDATE cubos SET disponible = 1, idarrendatario = '$idarrendatario'  WHERE codcubo = $codcubo";
            echo $sql;
            $sql_update = mysqli_query($conexion, $sql);

            if ($sql_update) {
                historia('Se asocio el cubo '.$codcubo.' con el arrendatario '.$nombre);
                mensajeicono('Se ha registrado con éxito el nuevo arrendatario!', 'lista_arrendatarios.php','','exito');

            }
           
        } else {
            historia('Error al intentar registrar el nuevo arrendatario '.$nombre);
            mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_arrendatarios.php','','error');
        }
        
    }
    mysqli_close($conexion);
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Registro de arrendatarios</h1>
        <a href="lista_arrendatarios.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card_div">
                <div class="card-header bg-primary">
                    Nuevo Arrendador
                </div>
                <div class="card-body">
                    <form action="" method="post" autocomplete="off">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <div class="form-group">
                            <label for="dni">Nombre</label>
                            <input type="text" placeholder="Ingrese nombre " name="nombre" id="nombre" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Fecha de ingreso</label>
                            <input type="date" placeholder="Ingrese Nombre" name="fecha" id="fecha" value="<?php echo $fecha ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="number" placeholder="Ingrese Teléfono" name="telefono" id="telefono" class="form-control">
                        </div>
                        <div class="form-group">
                        <label >Cubo asociado (a rentar)</label>
                        <?php
                        $query = mysqli_query($conexion, "SELECT * FROM cubos WHERE disponible = 0 ORDER BY codcubo ASC");
                        $res = mysqli_num_rows($query);
                        ?>

                        <select id="cubo" name="cubo" class="form-control">
                            <?php
                            if ($res > 0) {
                            while ($f = mysqli_fetch_array($query)) {
                            ?>  
                                <option value="<?php echo $f['codcubo']; ?>"><?php echo $f['cubo']; ?></option>
                            <?php
                                }
                            }

                            ?>
                        </select>
                        </div>
                        <input type="submit" value="Guardar arrendador" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>