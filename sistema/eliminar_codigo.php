<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM codigobarras WHERE id = $id");
    historia('Se elimino el código '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el código!', 'lista_codigos.php','','exito');
    ////////header("location: lista_codigos.php");
}
?>
<?php include_once "includes/footer.php"; ?>