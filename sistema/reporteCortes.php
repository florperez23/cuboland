<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');
require_once('includes/functions.php');

$codcubo = $_POST['cubo'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
//$desde = date("Y-m-d",strtotime($desde."- 1 day"));
$hasta =  date("Y-m-d",strtotime($hasta."+ 1 day"));
$suma = 0;
$sumaef= 0;
$sumata = 0;
$sumatran = 0;
$sumadep = 0;


$sql = 'SELECT f.nofactura, f.fecha, df.*, (df.cantidad * df.precio_promocion) as total, if(f.idtipopago = 1, "EFECTIVO", if(f.idtipopago=2, "TARJETA",if(f.idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago,
f.efectivo,
f.tarjeta,
f.transferencia,
f.deposito
FROM detallefactura df
inner JOIN factura f on f.nofactura = df.nofactura
inner JOIN producto p on p.codproducto = df.codproducto
WHERE p.codcubo = '.$codcubo.' and f.fecha BETWEEN "'.$desde.'" and "'.$hasta.'" order by f.fecha asc';
echo $sql;
$r = $conexion -> query($sql);
$tabla = "";
$vuelta = 1;
if ($r -> num_rows >0){


    $tabla = $tabla.'<center><span style="font-size: 18px; font-family: sans-serif;"><b> Nombre cubo: </b>'.cubo_nombre($codcubo).' <b>Rentero: </b>'.rentero_cubo($codcubo).'</span></center><br><br>';

    $tabla = $tabla.'<table  align = "center"  style="font-size: 9.6px;">';
    $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8" >';
    $tabla = $tabla.'<th ><b>No.</b></th>';
    $tabla = $tabla.'<th ><b>FACTURA</b></th>';
    $tabla = $tabla.'<th ><b>FECHA</b></th>';
    $tabla = $tabla."<th><b>CODIGO PRODUCTO</b></th>";
    $tabla = $tabla.'<th ><b>CANTIDAD</b></th>';
    $tabla = $tabla.'<th ><b>PRECIO VENTA</b></th>';
    $tabla = $tabla.'<th ><b>PRECIO PROMOCION</b></th>';
    $tabla = $tabla.'<th ><b>TOTAL</b></th>';
    $tabla = $tabla.'<th ><b>TIPO PAGO</b></th>';
    $tabla = $tabla.'<th ><b>EF.</b></th>';
    $tabla = $tabla.'<th ><b>TA.</b></th>';
    $tabla = $tabla.'<th ><b>TRAN.</b></th>';
    $tabla = $tabla.'<th ><b>DEP.</b></th>';


    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#D7E9F0">'; 
        }
        $tabla = $tabla.'<td>'.$vuelta.'</td>';
        $tabla = $tabla.'<td>'.$f['nofactura'].'</td>';
      
            $fecha = date("d/m/Y", strtotime($f['fecha']));
            
        $tabla = $tabla.'<td>'.$fecha.'</td>';
        $tabla = $tabla.'<td>'.$f['codproducto'].'</td>';
        $tabla = $tabla.'<td>'.$f['cantidad'].'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['precio_venta'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['precio_promocion'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['total'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>'.$f['tipopago'].'</td>';
        $suma = $suma += $f['total'];
        $tabla = $tabla.'<td>$'.number_format($f['efectivo'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['tarjeta'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['transferencia'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['deposito'], 2, '.', ',').'</td>';

        $sumaef= $sumaef += $f['efectivo'];
        $sumata = $sumata += $f['tarjeta'];
        $sumatran = $sumatran += $f['transferencia'];
        $sumadep = $sumadep += $f['deposito'];

        $tabla = $tabla."</tr>";  
        $vuelta++;               
    }
    $tabla = $tabla.'</table>';
}


$tabla = $tabla.'<br><br><br>
<table  align = "center" >
    <tr>
        <td>
            
        </td>
        <td  bgcolor="#D7E9F0">
            MONTO TOTAL GENERADO $'.number_format($suma, 2, '.', ',').'
        </td>
    </tr>
    
</table>';

echo $tabla;
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('CUBOLAND');
$pdf->SetKeywords('Tienda de cubos');
$pdf->SetHeaderData('Imagen1.jpg', '28', 'CORTE DEL MES', "Impreso: ".$fecha."");
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '');
//$link = "http://".$urlnueva[0]."/md_lista.php";

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 9));
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->SetFooterData("Impreso: ".fecha_larga($fecha).", ".hora12($hora)." por ".nitavu_nombre($nitavu),array(0, 64, 0),array(0, 64, 128));

$pdf->setPrintFooter(true);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'pdf/lang/eng.php')) {
    require_once(dirname(__FILE__).'pdf/lang/eng.php');
    $pdf->setLanguageArray($l);
}
// set font
$pdf->SetFont('helvetica', '', 9);
// add a page
$pdf->AddPage('L', 'LETTER'); //en la tabla de reporte L o P
$html = $tabla;
//echo $html; aqui escribe el contenido de la consulta

$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();
//Close and output PDF document}
ob_end_clean();
$pdf->Output('reporte.pdf', 'I');
?>