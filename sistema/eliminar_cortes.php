<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM cortecaja WHERE Id = $id");
    historia('Se elimino un corte de caja '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el corrte de caja!', 'lista_cortes.php','','exito');
    //header("location: lista_cortes.php");
}
?>
<?php include_once "includes/footer.php"; ?>