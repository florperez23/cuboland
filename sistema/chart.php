
<?php
include("../conexion.php");
if ($_POST['action'] == 'sales') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT descripcion, existencia FROM producto WHERE existencia <= 10 ORDER BY existencia ASC LIMIT 10");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}

if ($_POST['action'] == 'polarChart') {
  
    $query = mysqli_query($conexion, "select r.*, a.nombre, c.cubo from rentas r inner join arrendatarios a on a.idarrendatario = r.idarrendatario inner join cubos c on c.codcubo = r.idcubo where r.cancelado = 0 and r.fechaultimopago <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59')");
    
    echo "<table class='table table-striped table-bordered'>";
    echo "<tr>";
        echo "<th>Cubo</th>";
        echo "<th>Rentero</th>";
        echo "<th>Último pago</th>";
    echo "</tr>";
    while ($data = mysqli_fetch_array($query)) {
        echo "<tr>";
            echo "<td>".$data['cubo']."</td>";
            echo "<td>".$data['nombre']."</td>";
            echo "<td>".$data['fechaultimopago']."</td>";
        echo "</tr>";
    }
    echo "</table>";
  
    
}


/*if ($_POST['action'] == 'polarChart') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT p.codigo, p.descripcion, d.codproducto, d.cantidad, SUM(d.cantidad) as total FROM producto p INNER JOIN detallefactura d WHERE p.codproducto = d.codproducto group by d.codproducto ORDER BY d.cantidad DESC LIMIT 5");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}*/



?>
