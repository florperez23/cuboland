<?php
include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['cubo']) || empty($_POST['rentero']) || empty($_POST['fecha']) ) {
        mensajeicono('Todos los campos son requeridos.', 'registro_renta.php','','info');

    } else {
        $idarr = $_POST['rentero'];
        $cubo = $_POST['cubo'];
        $fecha = $_POST['fecha'];
        $usuario_id = $_SESSION['idUser'];
       
        $sql = "INSERT INTO rentas(idarrendatario,idcubo, fechacontrato) values ('$idarr', '$cubo', '$fecha')";
        
        $query_insert = mysqli_query($conexion, $sql);
        if ($query_insert) {

            $sql = "UPDATE cubos SET disponible = 1, idarrendatario = '$idarr' WHERE codcubo = $cubo";
            $sql_update = mysqli_query($conexion, $sql);
            if ($sql_update) {
                historia('Se registro una nueva renta '.$idarr.'_'.$cubo);
                mensajeicono('Se ha registrado con éxito la nueva renta!', 'lista_rentas.php','','exito');
            }else{
                historia('Error al intentar registrar la nueva renta '.$idarr.'_'.$cubo);
                mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_rentas.php','','error'); 
            }
        } else {
            historia('Error al intentar registrar la nueva renta '.$idarr.'_'.$cubo);
            mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_rentas.php','','error');   
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
                Registro de renta
            </div>
            <div class="card_div">
                <form action="" autocomplete="off" method="post" class="card-body p-2">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    

                    <div class="form-group">
                    <label>Arrendatario asociado</label>
                    <?php
                        $query = mysqli_query($conexion, "select * from arrendatarios");
                        $res = mysqli_num_rows($query);
                    ?>

                    <select id="rentero" name="rentero" class="form-control">
                    <option value='0'>Seleccione una opción</option>
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

                    <div class="form-group">
                    <label>Cubo asociado</label>
                    <?php
                        $query = mysqli_query($conexion, "select * from cubos where disponible = 0");
                        $res = mysqli_num_rows($query);
                    ?>

                    <select id="cubo" name="cubo" class="form-control">
                        <option value='0'>Seleccione una opción</option>
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

                    <div class="form-group">
                        <label for="fecha">Fecha Contrato</label>
                        <input type="date" placeholder="Ingrese nombre" name="fecha" id="fecha" value= '<?php echo $fecha ?>' class="form-control">
                    </div>

                    
                   
                    <input type="submit" value="Guardar Cubo" class="btn btn-primary">
                    <a href="lista_rentas.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>