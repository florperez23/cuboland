<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];

 
        $idcubo = idcuboanterior($id);
        $q2 = "UPDATE cubos SET disponible = 0, idarrendatario = '' WHERE codcubo = $idcubo";
       // echo $q2;
        $sql = mysqli_query($conexion, $q2);
        if ($sql) {


            //ELIMINAMOS TAMBIEN LAS BAJAS QUE HUBO DURANTE SU ESTANCIA
            $q3 = "DELETE FROM historial_bajas WHERE codcubo = $idcubo";
            //echo $q3;
            $sql1 = mysqli_query($conexion, $q3);
            if ($sql1) {
                
            }


            //SI ELIMINA UNA RENTA QUIERE DECIR QUE YA SE VA, ASI QUE CREAMOS UN PDF DE SALIDA DE PRODUCTOS CON FIRMA
            echo "<div>";
                echo "<iframe id='frameSalida' name='frameSalida' src='salida_definitiva.php?idcubo=".$idcubo."&idrenta=".$id."' style='width:100%; height:800px; border:0; border:none;'>Tu navegador no soporta iframes...</iframe>";
              echo "</div>";

                 
   
           
           // historia('Se elimino la renta '.$id);
            //mensajeicono('Se ha eliminado con éxito la renta!', 'lista_rentas.php','','exito');
            
            
        }else{
            historia('Error al eliminar la renta '.$id);
            mensajeicono('Error al momento de eliminar la renta, intente de nuevo!', 'lista_rentas.php','','error');
        }
    
    mysqli_close($conexion);

}
?>
<?php include_once "includes/footer.php"; ?>