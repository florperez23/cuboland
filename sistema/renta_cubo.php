<?php include_once "includes/header.php"; ?>
<?php
// if (!empty($_GET['id'])) {
//     require("../conexion.php");
//     $id = $_GET['id'];
//     $query_delete = mysqli_query($conexion, "DELETE FROM cubos WHERE codcubo = $id");
//     historia('Se elimino un cubo '.$id);
//     mysqli_close($conexion);
//     mensajeicono('Se ha eliminado con éxito el cubo!', 'lista_cubos.php','','exito');
//     //header("location: lista_proveedor.php");
// }


    $codcubo=$_GET['codcubo'];
  


    $idarrendatario = $_POST['idarrendatario'.$codcubo];
    $token = md5($_SESSION['idUser']);
    $usuario = $_SESSION['idUser'];
    $tipoventa = $_POST['tipoven'.$codcubo];
    $fechaven = $_POST['fechaven'.$codcubo];
    // $numcredito=$_POST['numcredito'.$codcubo];
    $tipopago = $_POST['tipopago'.$codcubo];
    $newDate = date("Y/m/d", strtotime($fechaven));
    $totalrenta=$_POST['totalmodal'.$codcubo];
    $estado=$_POST['disponible'.$codcubo];
    $pagocon=$_POST['pagar_con'.$codcubo];
    $renta=$_POST['renta'.$codcubo];

    $mesesadelentados=$_POST['adelantar'.$codcubo];

    $pagocon = str_replace("$", "", $pagocon);
    $pagocon = str_replace(",", "", $pagocon);

    $totalrenta = str_replace("$", "", $totalrenta);
    $totalrenta = str_replace(",", "", $totalrenta);



    if(isset($_POST['numreferencia'.$codcubo])){
      $referencia = $_POST['numreferencia'.$codcubo];
    }else{
      $referencia = "";
    }

    $fecha=date("Y-m-d");  

    $fproximopago=null;;

    if($mesesadelentados>0)
    {
      $fecha = date('Y-m-j');
      $fechaultimopago = strtotime ( '+'.$mesesadelentados.' month' , strtotime ( $fecha ) ) ;
      $fechaultimopago = date ( 'Y-m-j' , $fechaultimopago );

    }else{
      $fechaultimopago=$fecha;
    }

    echo $fechaultimopago;
   
    if($estado==0)
    {
      $sql = "INSERT INTO rentas(idarrendatario,idcubo,fechacontrato,fechaultimopago,fechaproximopago,usuario) values ( '$idarrendatario', '$codcubo', '$fecha', '$fechaultimopago', '$fproximopago', '".$usuario."')";
    } 
    else
    {
        $sql="Update rentas set fechaultimopago='".$fecha."' , fechaproximopago='".$fproximopago."' where idcubo='".$codcubo. "'";
     
        
    }
  
  //echo $sql;	
      $query_insert = mysqli_query($conexion, $sql);
      if ($query_insert) {

    // //     //HACER el insert el factura para tomarlo en cuenta en los reportes
       $sql = "INSERT INTO factura(fecha,usuario,codcliente,totalfactura,idtipoventa,idtipopago,	cancelado,totalventa,referencia,pagocon,numcredito,	saldo,fechacancelacion,usuario_id_mod,subtotal,iva,observaciones) 
      values ( now(), '$usuario','$idarrendatario', '$totalrenta','5','$tipopago',0,'$renta','$referencia','$pagocon','','','','','$totalrenta', '$totalrenta','".$codcubo."')";
         //echo $sql;	
         $query_insert = mysqli_query($conexion, $sql);
         if ($query_insert) {
           $nofactura= obtenerUltimoNoFactura();
           $link='factura/generaFactura.php?cl='. $idarrendatario.'&f='. $nofactura.'';	
          
           echo "<script> window.open('".$link."', '_blank'); </script>";
           //  historia('Se registro una nueva renta ');
            mensajeicono('Se ha registrado con éxito el pago de la renta !', 'lista_cubos.php','','exito');
         }else{
             historia('Error al intentar ingresar el pago de la renta ');
             mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_cubos.php','','error');
         }

      } else {
          historia('Error al intentar ingresar la renta ');
          mensajeicono('Hubo un error, favor de intentarlo de nuevo.', 'lista_cubos.php','','error');
     }

        

?>
<?php include_once "includes/footer.php"; ?>