<?php
include "../conexion.php";

   
?>

<?php
$query_medida = mysqli_query($conexion, "SELECT * FROM cubos WHERE disponible = 1 ORDER BY codcubo ASC");
$resultado_medida = mysqli_num_rows($query_medida);
?>

<select id="codigocub" name="codigocub" class="form-control">
    <option value="0">SIN ESPECIFICAR</option>
    <?php
    if ($resultado_medida > 0) {
    while ($medida = mysqli_fetch_array($query_medida)) {
    ?>  
        <option value="<?php echo $medida['codcubo']; ?>"><?php echo $medida['cubo']; ?></option>
    <?php
        }
    }

    ?>
</select>

