
<?php
include "../conexion.php";
if (!isset($_POST["codigo"])) {
    return;
}
if(!isset($_SESSION["carrito"])) $_SESSION["carrito"] = array();
$granTotal = 0;
$id=0;
$codigo = $_POST["codigo"];
$existencia=0;
$precioventa=0;
$descripcion="";
    $sql = "SELECT * FROM producto WHERE codigo =".$codigo." LIMIT 1;";
	$r = $conexion -> query($sql);
	//$r_count = $r -> num_rows;

if ($r -> num_rows >0)
		{
		
		
		while($f = $r -> fetch_array())
			{ // resultado de la busqueda.................                              
                $descripcion=$f['descripcion'];
                $existencia=$f['existencia'];
                $precioventa=$f['precioventa'];
                $id=$f['codproducto'];
                if($existencia<1)
                {
                    header("Location: ./nueva_venta.php?status=5");
                }
			}
		}
		else
		{
			  # Si no existe, salimos y lo indicamos
        header("Location: ./nueva_venta.php?status=4");        
    	}

session_start();

// Buscar producto dentro del carrito
$indice = false;
for ($i = 0; $i < count($_SESSION["carrito"]); $i++) {  
    if ($_SESSION['carrito'][$i]['codigo']== $codigo ) {
        $indice = $i;
        break;
    } 
}

// Si no existe, lo agregamos como nuevo
if ($indice === false and  $existencia >0) {
    //$producto->cantidad = 1;
    //$producto->total = $producto->precioVenta;
   $productoarray= array('id' =>$id,'codigo' =>$codigo,'cantidad' =>1,'descripcion' =>$descripcion,'cantidad' =>1, 'precioventa' => $precioventa,'total' => $precioventa);
   
    array_push($_SESSION["carrito"], $productoarray );
} else {
    // Si ya existe, se agrega la cantidad
    // Pero espera, tal vez ya no haya
    $cantidadExistente = $_SESSION["carrito"][$indice]['cantidad'];
    echo   'cantidad'.$cantidadExistente;
    // si al sumarle uno supera lo que existe, no se agrega
    if ($cantidadExistente + 1 >$existencia) {
        header("Location: ./nueva_venta.php?status=5");
        exit;
    }
 
    $_SESSION["carrito"][$indice]['cantidad']=$cantidadExistente+1;
    $_SESSION["carrito"][$indice]['total'] = $_SESSION["carrito"][$indice]['cantidad'] * $_SESSION["carrito"][$indice]['precioventa'];
}

header("Location: ./nueva_venta.php");   