<?php
include "../conexion.php";

    $idcat = $_POST['idcat'];
?>

<label >Sección del producto</label>
<?php
$query_medida = mysqli_query($conexion, "SELECT * FROM cat_secciones WHERE iddepartamento = ".$idcat." ORDER BY idseccion ASC");
$resultado_medida = mysqli_num_rows($query_medida);
?>

<select id="sec" name="sec" class="form-control">
    <option value="0">SIN ESPECIFICAR</option>
    <?php
    if ($resultado_medida > 0) {
    while ($medida = mysqli_fetch_array($query_medida)) {
    ?>  
        <option value="<?php echo $medida['idseccion']; ?>"><?php echo $medida['seccion']; ?></option>
    <?php
        }
    }

    ?>
</select>

