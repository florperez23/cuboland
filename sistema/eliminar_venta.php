<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $usuario = $_SESSION['idUser'];
    $sql= "UPDATE factura set cancelado=1 ,fechacancelacion=NOW(), usuario_id_mod='".$usuario."' WHERE nofactura = '$id'";
    $query = mysqli_query($conexion, $sql);
    if ($query) {

        $sql1 = "CALL cancelar_venta('".$id."','".$usuario."') ";
		//echo $sql1;	
		$query1 = mysqli_query($conexion, $sql1);
       // echo $query1;
		if ($query1) {
            historia('Se cancelo la factura #'.$id); 
            $link=obtenerDatosFacturaNva($id);
            //echo "<script> window.open('".$link."', '_blank'); </script>";
            mensajeicono('Se ha cancelado con éxito el la factura!','ventas.php','','exito');
		
        
        }else {
            mensajeicono('Ocurrio un error al cancelar la venta!', 'ventas.php','','error');
		}
		    

      
    }else {
        mensajeicono('Ocurrio un error al cancelar la venta!', 'ventas.php','','error');
    }
      
  

}
?>
<?php include_once "includes/footer.php"; ?>