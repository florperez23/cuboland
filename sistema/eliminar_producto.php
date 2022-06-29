<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $codproducto = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = $codproducto");
    historia('Se elimino un producto '.$codproducto);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el producto!', 'lista_productos.php','','exito');
    //header("location: lista_productos.php");
}
?>
<?php include_once "includes/footer.php"; ?>