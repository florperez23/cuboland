
<?php
include("../conexion.php");
if ($_POST['action'] == 'sales-chart') {
   /* $arreglo = array();
    $query = mysqli_query($conexion, "SELECT descripcion, existencia FROM producto WHERE existencia <= 10 ORDER BY existencia ASC LIMIT 10");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();*/
    $ultimo = date("Y-m-t", strtotime($fecha));
    $primero = date('Y-m-01');
    
    $desde = date("Y-m-d",strtotime($primero));//."- 1 day"
    $hasta =  date("Y-m-d",strtotime($ultimo));//."+ 1 day"
    $query = mysqli_query($conexion, 'SELECT cr.*, c.nombre,c.telefono, (select saldo from factura where numcredito = cr.numcredito order by nofactura desc limit 1 ) as saldo FROM creditos cr LEFT join cliente c on c.idcliente = cr.idcliente WHERE cr.fechavencimiento BETWEEN  "'.$desde.'" and "'.$hasta.'" and cr.estado = 1');
    
    //echo "<table class='table table-striped table-bordered'>";
    //echo "<tr>";
       // echo "<th>Cubo</th>";
       // echo "<th>Rentero</th>";
      //  echo "<th>Último pago</th>";
    //echo "</tr>";
    while ($data = mysqli_fetch_array($query)) {

        $date = date_create($data['fechavencimiento']);
        $fecha = date_format($date,"Y-m-d");
       // echo "<tr>";
        echo "<div class='card_span'>";
        echo "<table>";
        echo "<td style='width:100px;'>";
            echo '<center><i class="fas fa-user fa-2x text-gray-300"></i></center>';
        echo "</td>";
        echo "<td>";
            echo "<center><span ><b style='color:#CCC8C8'>Crédito: </b>".$data['numcredito']." <b style='color:#CCC8C8'>Cliente: </b>".$data['nombre']." <b style='color:#CCC8C8'>Tel.:</b> ".$data['telefono']." <br><b style='color:#CCC8C8'>Fecha vencimiento: </b>".$fecha ." <b style='color:#CCC8C8'> Saldo: </b> $".$data['saldo']." </span></center>";
        echo "</td>";
        echo "</table>";
        echo "</div>"; 
            //echo "<td>".$data['nombre']."</td>";
            //echo "<td>".$data['fechaultimopago']."</td>";
       // echo "</tr>";
    }
}

if ($_POST['action'] == 'polarChart') {
  
    $query = mysqli_query($conexion, "select r.*, a.nombre, c.cubo from rentas r inner join arrendatarios a on a.idarrendatario = r.idarrendatario inner join cubos c on c.codcubo = r.idcubo where r.cancelado = 0 and r.fechaultimopago <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59')");
    
    //echo "<table class='table table-striped table-bordered'>";
    //echo "<tr>";
       // echo "<th>Cubo</th>";
       // echo "<th>Rentero</th>";
      //  echo "<th>Último pago</th>";
    //echo "</tr>";
    while ($data = mysqli_fetch_array($query)) {
       // echo "<tr>";
        echo "<div class='card_span'>";
        echo "<table>";
        echo "<td style='width:100px;'>";
            echo '<center><i class="fas fa-user fa-2x text-gray-300"></i></center>';
        echo "</td>";
        echo "<td>";
            echo "<center><span ><b style='color:#CCC8C8'>Cubo: </b>".$data['cubo']." <b style='color:#CCC8C8'>Rentero: </b>".$data['nombre']." <br><b style='color:#CCC8C8'>Fecha último pago: </b>".$data['fechaultimopago']." </span></center>";
        echo "</td>";
        echo "</table>";
        echo "</div>"; 
            //echo "<td>".$data['nombre']."</td>";
            //echo "<td>".$data['fechaultimopago']."</td>";
       // echo "</tr>";
    }
   // echo "</table>";
  
    
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
