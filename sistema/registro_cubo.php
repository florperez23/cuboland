<?php
include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) ) {
        mensajeicono('El campo nombre es requerido.', 'lista_cubos.php','','info');

    } else {
        $cubo = $_POST['nombre'];
        $renta = $_POST['renta'];
        $idarr = $_POST['rentero'];
        $usuario_id = $_SESSION['idUser'];
        $query = mysqli_query($conexion, "SELECT * FROM cubos where cubo = '$cubo'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            mensajeicono('Ya existe un cubo con este mismo nombre.', 'lista_cubos.php','','info');
        }else{
        
            if($idarr == 0){
                $sql = "INSERT INTO cubos(cubo,renta, disponible, idarrendatario) values ('$cubo', '$renta',0, 0)";
            }else{
                $sql = "INSERT INTO cubos(cubo,renta, disponible, idarrendatario) values ('$cubo', '$renta',1, '$idarr')";
            }
            $query_insert = mysqli_query($conexion, $sql);
            if ($query_insert) {
                historia('Se registro el nuevo cubo '.$cubo);
                mensajeicono('Se ha registrado con éxito el cubo!', 'lista_cubos.php','','exito');
            } else {
                historia('Error al intentar registrar el nuevo cubo '.$cubo);
                mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_cubos.php','','error');   
            }
        }
    }
}
//mysqli_close($conexion);
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card-header bg-primary text-white">
                Registro de Cubos
            </div>
            <div class="card_div">
                <form action="" autocomplete="off" method="post" class="card-body p-2">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">NOMBRE</label>
                        <input type="text" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nombre">RENTA</label>
                        <input type="text" placeholder="Ingrese renta" name="renta" id="renta" class="form-control">
                    </div>

                    <div class="form-group">
                    <label>Arrendatario asociado</label>
                    <?php
                        $query = mysqli_query($conexion, "select * from arrendatarios");
                        $res = mysqli_num_rows($query);
                    ?>

                    <select id="rentero" name="rentero" class="form-control">
                    <option value="0">Sin especificar</option>
                        <?php
                        if ($res > 0) {
                            while ($f = mysqli_fetch_array($query)) {
                        ?>
                            <option value="<?php echo $f['idarrendatario']; ?>"><?php echo $f['nombre']; ?></option>
                        <?php
                            }
                        }
                        
                        ?>
                    </select>
                    </div>
                   
                    <input type="submit" value="Guardar Cubo" class="btn btn-primary">
                    <a href="lista_cubos.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>