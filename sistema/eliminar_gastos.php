<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM gastos WHERE id = $id");
    historia('Se elimino un gasto(factura) '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el gasto!', 'lista_factura.php','','exito');
    //header("location: lista_factura.php");
}
?>
<?php include_once "includes/footer.php"; ?>