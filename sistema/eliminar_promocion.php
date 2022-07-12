<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM promociones WHERE idpromocion = $id");
    historia('Se elimino una promocion '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito la promocion!', 'lista_promociones.php','','exito');
    //header("location: lista_cliente.php");
}
?>
<?php include_once "includes/footer.php"; ?>