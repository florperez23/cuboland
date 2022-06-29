<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');


$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$suma = 0;

$sql = 'select *
from cortecaja c
WHERE c.FechaApertura BETWEEN  "'.$desde.'" and "'.$hasta.'"';
//echo $sql;
$r = $conexion -> query($sql);
$tabla = "";
$vuelta = 1;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#FAAC9E">';
    $tabla = $tabla.'<th ><b>No.</b></th>';
    $tabla = $tabla.'<th ><b>MONTO INICIAL</b></th>';
    $tabla = $tabla."<th><b>MONTO FINAL</b></th>";
    $tabla = $tabla.'<th ><b>FECHA APERTURA</b></th>';
    $tabla = $tabla.'<th ><b>FECHA CIERRE</b></th>';
    $tabla = $tabla.'<th ><b>TOTAL VENTAS</b></th>';
    $tabla = $tabla.'<th ><b>MONTO TOTAL</b></th>';
    $tabla = $tabla.'<th ><b>ESTADO</b></th>';
    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#FCD2CB">'; 
        }
        $tabla = $tabla.'<td>'.$vuelta.'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['MontoInicial'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['MontoFinal'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>'.$f['FechaApertura'].'</td>';
        $tabla = $tabla.'<td>'.$f['FechaCierre'].'</td>';
        $tabla = $tabla.'<td>'.$f['TotalVentas'].'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['MontoTotal'], 2, '.', ',').'</td>';
        if($f['Estado']==1){
            $tabla = $tabla.'<td>Terminado</td>';
        }else{
            $tabla = $tabla.'<td>En proceso</td>';
        }
        
        $suma = $suma += $f['MontoTotal'];
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
        <td  bgcolor="#FCD2CB">
            MONTO TOTAL GASTADO $'.number_format($suma, 2, '.', ',').'
        </td>
    </tr>
    
</table>';

echo $tabla;
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('PUNTO DE VENTA');
$pdf->SetKeywords('Punto de Venta');
//$pdf->SetHeaderData('pdf_logo.jpg', '40','', '');
$pdf->SetHeaderData('aguira.jpg', '40', 'Listado de Cortes de Caja', "Impreso: ".$fecha."");
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