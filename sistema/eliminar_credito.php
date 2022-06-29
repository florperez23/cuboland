<?php include_once "includes/header.php"; ?>
<?php
if (!empty($_GET['id'])) {
    require("../conexion.php");
    $id = $_GET['id'];
    $usuario = $_SESSION['idUser'];
    //$query_del = mysqli_query($conexion, "UPDATE creditos set estado=2 ,fechacancelacion=NOW(), usuario_id_mod='".$usuario."' WHERE numcredito = '$id'");
  
    $sql="UPDATE creditos set estado=2 ,fechacancelacion=NOW(), usuario_id_mod='".$usuario."' WHERE numcredito = '$id'";

    $query = mysqli_query($conexion, $sql);
    if ($query) {

        $sql1 = "CALL cancelar_credito('".$id."','".$usuario."') ";
		echo $sql1;	
		$query1 = mysqli_query($conexion, $sql1);
        echo $query1;
		if ($query1) {
            historia('Se cancelo el credito numero #'.$id); 
            $link=obtenerDatosFacturaNva($id); 
            mensajeicono('Se ha cancelado con éxito el credito!', 'lista_creditos.php','','exito');
		}else {
            mensajeicono('Ocurrio un error al cancelar el credito!', 'lista_creditos.php','','error');
		}
		    

      
    }else {
        mensajeicono('Ocurrio un error al cancelar la venta!', 'lista_creditos.php','','error');
    }
}
?>
<?php include_once "includes/footer.php"; ?>