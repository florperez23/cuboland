<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM cliente WHERE idcliente = $id");
    historia('Se elimino un cliente '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el cliente!', 'lista_cliente.php','','exito');
    //header("location: lista_cliente.php");
}
?>
<?php include_once "includes/footer.php"; ?>