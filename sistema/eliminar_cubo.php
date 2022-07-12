<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM cubos WHERE codcubo = $id");
    historia('Se elimino un cubo '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el cubo!', 'lista_cubos.php','','exito');
    //header("location: lista_proveedor.php");
}
?>
<?php include_once "includes/footer.php"; ?>