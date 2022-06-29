<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM proveedor WHERE codproveedor = $id");
    historia('Se elimino un proveedor '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el proveedor!', 'lista_proveedor.php','','exito');
    //header("location: lista_proveedor.php");
}
?>
<?php include_once "includes/footer.php"; ?>