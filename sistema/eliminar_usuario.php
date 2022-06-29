<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM usuario WHERE idusuario = $id");
    historia('Se elimino un usuario '.$id);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el usuario!', 'lista_usuarios.php','','exito');
    //header("location: lista_usuarios.php");
}
?>
<?php include_once "includes/footer.php"; ?>