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
  


    $idarrendatario = $_POST['idarrendatario'];
    $token = md5($_SESSION['idUser']);
    $usuario = $_SESSION['idUser'];
    $tipoventa = $_POST['tipoven'];
    $fechaven = $_POST['fechaven'];
    $numcredito=$_POST['numcredito'];
    $tipopago = $_POST['tipopago'];
    $newDate = date("Y/m/d", strtotime($fechaven));
    $totalrenta=$_POST['totalmodal'];

    if(isset($_POST['pago'])){
      $pagocon = $_POST['pago'];
    }else{
      $pagocon = "";
    }
 
    
    if(isset($_POST['referencia'])){
      $referencia = $_POST['referencia'];
    }else{
      $referencia = "";
    }

    $fecha=date("Y-m-d"); 
   
    $fechaproximopago=date("Y-m-d",strtotime($fecha."+ 1 month")); // le sumamos un mes  la fecha actual

    $fechaproximopago = strtotime($fechaproximopago);
     $anio = date("Y", $fechaproximopago);//obtenemos el año actual
     $mes = date("m", $fechaproximopago);//obtenemos el mes actual
    $fechaprox=$anio."-".$mes."-06";
 
   $fproximopago=FechaProximoPago($fechaprox);

 
    $sql = "INSERT INTO rentas(idarrendatario,idcubo,fechacontrato,fechaultimopago,fechaproximopago,usuario) values ( '$idarrendatario', '$codcubo', '$fecha', '$fecha', '$fproximopago', '".$usuario."')";
    echo $sql;	
     $query_insert = mysqli_query($conexion, $sql);
     if ($query_insert) {

    //     //HACER el insert el factura para tomarlo en cuenta en los reportes
        $sql = "INSERT INTO factura(fecha,usuario,codcliente,totalfactura,idtipoventa,idtipopago,	cancelado,totalventa,referencia,pagocon,numcredito,	saldo,fechacancelacion,usuario_id_mod,subtotal,iva) 
        values ( now(), '$usuario','$idarrendatario', '$totalrenta','5','1',0,'$totalrenta','Renta Cubo".$codcubo."','','','','','','$totalrenta', '$totalrenta')";
        echo $sql;	
        $query_insert = mysqli_query($conexion, $sql);
        if ($query_insert) {

            historia('Se registro una nueva renta ');
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