<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM arrendatarios WHERE idarrendatario = $id");
    historia('Se elimino un arrendatario '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el arrendatario!', 'lista_arrendatarios.php','','exito');
    //header("location: lista_cliente.php");
}
?>
<?php include_once "includes/footer.php"; ?>