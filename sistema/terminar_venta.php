<?php
include "../conexion.php";

if(!isset($_POST["total"])) exit;
session_start();

$total = $_POST["total"];
$idVenta =0;
$fecha = date("Y-m-d H:i:s");
$usuario_id = $_SESSION['idUser'];




// $sentencia = $base_de_datos->prepare("INSERT INTO ventas(fecha, total) VALUES (?, ?);");
// $sentencia->execute([$ahora, $total]);

$query_insert = mysqli_query($conexion, "INSERT INTO ventas(fechacaptura,idusuario,total) values ('$fecha', '$usuario_id', '$total')");

if ($query_insert)
{
    
    $query = mysqli_query($conexion, "SELECT idventa FROM ventas ORDER BY idventa DESC LIMIT 1;");
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        while ($data = mysqli_fetch_assoc($query)) { 
            $idVenta= $data['idventa'];
        }
    }else{

        $idVenta =1;
    }


     foreach($_SESSION["carrito"] as $indice => $producto)
    {     
        $codigoproducto=$producto['id'];
        $cantidadproducto=$producto['cantidad'];

             // //GUARDAMOS EL DETALLE DE LA VENTA
        $sql="INSERT INTO detalle_venta(codproducto, idventa, cantidad) VALUES ($codigoproducto, $idVenta, $cantidadproducto)"; 
        
             $query_insert2 = mysqli_query($conexion, $sql);
             if ($query_insert2) {

           //ACTUALIZAMOS LA EXISTENCIA DEL PRODUCTO
          
           $query_insert3 = mysqli_query($conexion, "UPDATE producto SET existencia = (existencia - ".$cantidadproducto.") WHERE codproducto = '$codigoproducto';");
            if ($query_insert3) {
                // //$base_de_datos->commit();
                unset($_SESSION["carrito"]);
                $_SESSION["carrito"] = [];
                header("Location: ./nueva_venta.php?status=1");
            
             } else {
                 //error al actualizar la existencia
                header("Location: ./nueva_venta.php?status=");
            }

             } else {
                   //error al agregar el detalle de la vena
                header("Location: ./nueva_venta.php?status=");
             }
    }

 

    

}
else {
    header("Location: ./nueva_venta.php?status=");
}


?>