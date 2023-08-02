<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $codproducto = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = '$codproducto'");
    historia('Se elimino un producto '.$codproducto);
    mysqli_close($conexion);
    mensajeicono('Se ha eliminado con éxito el producto!', 'lista_productos.php','','exito');
    //header("location: lista_productos.php");
}
if (isset($_POST['enviar'])) {
    require("../conexion.php");
    echo "entro a eliminar varios";
    if (is_array($_POST['seleccionados'])) {
        $selected = '';
        $num_prod = count($_POST['seleccionados']);
        $current = 0;
        foreach ($_POST['seleccionados'] as $key => $value) {
            if ($current != $num_prod-1){
                echo "eliminando ".$value;
                $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = '$value'");
            }
            $current++;
        }

    }
    else {
        mensajeicono('Debes seleccionar algun producto!', 'lista_productos.php','','info');
    }
    mensajeicono('Se han eliminado con éxito los productos!', 'lista_productos.php','','exito');
    
}  
?>
<?php include_once "includes/footer.php"; ?>