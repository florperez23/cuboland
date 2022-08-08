<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');
include("includes/functions.php");


$idcorte = $_GET['idcorte'];
//BUSCAMOS LA FECHA DEL ULTIMO CORTE ABIERTO
$fechainicio = fechaCorteAperturaId($idcorte);
$fechafin = fechaCorteCierreId($idcorte);

$fechainicio = date("Y-m-d",strtotime($fechainicio."- 1 day"));
$fechafin =  date("Y-m-d",strtotime($fechafin."+ 1 day"));

  $sql = " SELECT f.nofactura, f.fecha, f.usuario, u.usuario as quien, f.codcliente, c.nombre, f.totalfactura, 
  if(f.idtipoventa = 1, 'Contado', if(f.idtipoventa = 2, 'Crédito', if(f.idtipoventa = 3, 'Devolución', 'Gasto'))) as tipoventa, if(f.idtipopago= 1, 'Efectivo',if(f.idtipopago= 2, 'Tarjeta', if(f.idtipopago= 3, 'Transferencia',''))) as tipopago, if(f.cancelado = 0, 'No', 'Si') as cancelado, p.proveedor 
  FROM factura f
  left JOIN usuario u on u.idusuario = f.usuario
  left join cliente c on c.idcliente = f.codcliente
  left join proveedor p on p.codproveedor = f.codcliente
  WHERE f.fecha between '".$fechainicio."' and '".$fechafin."' and idtipoventa in (1,2) ";

echo $sql;
$r = $conexion -> query($sql);
$tabla = "";
$vuelta = 0;
$suma = 0;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8">';
    $tabla = $tabla.'<th ><b>N. VENTA</b></th>';
    $tabla = $tabla.'<th ><b>FECHA CAPTURA</b></th>';
    $tabla = $tabla."<th><b>USUARIO</b></th>";
    $tabla = $tabla."<th><b>CLIENTE</b></th>";
    $tabla = $tabla.'<th ><b>TOTAL</b></th>';
    $tabla = $tabla.'<th ><b>TIPO VENTA</b></th>';
    $tabla = $tabla.'<th ><b>TIPO PAGO</b></th>';
    $tabla = $tabla.'<th ><b>CANCELADO</b></th>';
    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#D7E9F0">'; 
        }
        $tabla = $tabla.'<td>'.$f['nofactura'].'</td>';
        $tabla = $tabla.'<td>'.$f['fecha'].'</td>';
        $tabla = $tabla.'<td>'.$f['quien'].'</td>';
        if($f['nombre'] == ''){
            $tabla = $tabla.'<td>'.$f['proveedor'].'</td>';
        }else{
            $tabla = $tabla.'<td>'.$f['nombre'].'</td>';
        }
       
        $tabla = $tabla.'<td>$'.number_format($f['totalfactura'], 2, '.', ',').'</td>';
        $suma = $suma += $f['totalfactura'];
        $tabla = $tabla.'<td>'.$f['tipoventa'].'</td>';
        $tabla = $tabla.'<td>'.$f['tipopago'].'</td>';
        $tabla = $tabla.'<td>'.$f['cancelado'].'</td>';

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
            MONTO TOTAL RECUPERADO $'.number_format($suma, 2, '.', ',').'
        </td>
    </tr>
    
</table>';

echo $tabla;
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('CUBOLAND');
$pdf->SetKeywords('Tienda de cubos');
$pdf->SetHeaderData('Imagen1.jpg', '28', 'CORTE DE CAJA', "Impreso: ".$fecha."");
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