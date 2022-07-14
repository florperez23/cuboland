<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $query = "UPDATE rentas SET cancelado = 1 WHERE id = $id";
    echo $query;
    $query_delete = mysqli_query($conexion, $query);
    if ($query_delete) {
        $idcubo = idcuboanterior($id);
        $q2 = "UPDATE cubos SET disponible = 0 WHERE codcubo = $idcubo";
        echo $q2;
        $sql = mysqli_query($conexion, $q2);
        if ($sql) {

            //SI ELIMINA UNA RENTA QUIERE DECIR QUE YA SE VA ASI QUE DESACTIVAMOS TODOS SUS PRODUCTOS
            $q2 = "UPDATE productos SET activo = 1 WHERE codcubo = $idcubo";
            echo $q2;
            $sql = mysqli_query($conexion, $q2);
            if ($sql) {
                historia('Se elimino la renta '.$id);
                mensajeicono('Se ha eliminado con éxito la renta!', 'lista_rentas.php','','exito');
            }else{
                historia('Error al eliminar la renta '.$id);
                mensajeicono('Error al momento de eliminar la renta, intente de nuevo!', 'lista_rentas.php','','error');
            }
            
        }else{
            historia('Error al eliminar la renta '.$id);
            mensajeicono('Error al momento de eliminar la renta, intente de nuevo!', 'lista_rentas.php','','error');
        }
      }else{
        historia('Error al eliminar la renta '.$id);
        mensajeicono('Error al momento de eliminar la renta, intente de nuevo!', 'lista_rentas.php','','error');

      }
   
    mysqli_close($conexion);

}
?>
<?php include_once "includes/footer.php"; ?>