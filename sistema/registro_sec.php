<?php
include_once "includes/header.php";
include "../conexion.php";
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['seccion'])){
        mensajeicono('El campo seccion es obligatorio.', 'registro_sec.php','','info');

    } else {
        $seccion = $_POST['seccion'];
        $idcat = $_POST['idcat'];
        

        $query_insert = mysqli_query($conexion, "INSERT INTO cat_secciones(seccion, iddepartamento) values ('$seccion', '$idcat')");
        if ($query_insert) {
            historia('Se registro la nueva sección '.$seccion);
            mensajeicono('Se ha registrado con éxito la nueva sección!', 'lista_sec.php','','exito');

        } else {
            historia('Error al intentar registrar la nueva sección '.$seccion);
            mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_sec.php','','error');

        }
        
    }
    
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card-header bg-primary text-white">
                Registro de Sección
            </div>
            <div class="card">
                <form action="registro_sec.php" autocomplete="off" method="post" class="card-body p-2">
                    <?php echo isset($alert) ? $alert : ''; ?>

                    <label for="idcat">A que departamento se le agregará sección</label>
                    <?php $query = mysqli_query($conexion, "SELECT * FROM cat_departamento ORDER BY departamento ASC");
                    $resultado = mysqli_num_rows($query);
                    
                    ?>
                    <select id="idcat" name="idcat" class="form-control">
                        <?php
                        if ($resultado > 0) {
                        while ($f = mysqli_fetch_array($query)) {
                            
                        ?>                    
                            <option value="<?php echo $f['iddepartamento']; ?>"><?php echo $f['departamento']; ?></option>
                        <?php
                            }
                        }
                        
                        ?>
                    </select>





                    <div class="form-group">
                        <label for="nombre">NOMBRE SECCION</label>
                        <input type="text" placeholder="Ingrese nombre" name="seccion" id="seccion" class="form-control">
                    </div>
                    
                    <input type="submit" value="Guardar Categoria" class="btn btn-primary">
                    <a href="lista_cat.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>