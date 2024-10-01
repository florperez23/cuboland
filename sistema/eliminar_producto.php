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

if (isset($_POST['enviar']) and $_POST['enviar'] == 1) {
    echo $_POST['enviar'].'valor de varios';
    require("../conexion.php");
    echo "entro a eliminar varios";

        $selected = '';
        $num_prod = count($_POST['seleccionados']);
        echo $num_prod.'num produc';
        $current = 0;
        foreach ($_POST['seleccionados'] as $key => $value) {
            if ($current != $num_prod){
                echo "eliminando ".$value;
                $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = '$value'");
            }
            $current++;
        }

        if ($current == $num_prod){
            mensajeicono('Se han eliminado con éxito los productos!', 'lista_productos.php','','exito');
        }
       // mensajeicono('Debes seleccionar algun producto!', 'lista_productos.php','','info');

}  

if (isset($_POST['eliminarTodo']) and $_POST['eliminarTodo'] <> 0) {
    echo $_POST['eliminarTodo'].'valor de eliminar todo';
    require("../conexion.php");
    echo "entro a eliminar todos";
    $cubo = $_POST['eliminarTodo'];
    $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codcubo = '$cubo'");
    historia('Se eliminaron los productos del cubo '.$cubo);
    mysqli_close($conexion);
    mensajeicono('Se han eliminado con éxito los productos!', 'lista_productos.php','','exito');
    
}  
?>
<?php include_once "includes/footer.php"; ?>