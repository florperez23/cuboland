<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');

if(isset($_POST['desde'])){
    $desde = $_POST['desde'];
}else{
    $desde = "";
}

if(isset($_POST['hasta'])){
    $hasta = $_POST['hasta'];
}else{
    $hasta = "";
}
if(isset($_POST['estatus'])){
    $estatus1 = $_POST['estatus'];
}else{
    $estatus1 = "0";
}

 $suma = 0;

$sql1="SELECT numcredito, creditos.fecha,totalventa as total,totalventa-(select SUM(totalfactura) from factura where numcredito=creditos.numcredito GROUP BY NUMCREDITO) AS  adeudo,fechavencimiento,estado,nombre 
                        FROM creditos inner join cliente on cliente.idcliente=creditos.idcliente ";
                        if($estatus1!=0)
                        {
                            $sql1=$sql1." WHERE creditos.estado=".$estatus1."";
                        }
                       // WHERE creditos.estado" ;
						$query = mysqli_query($conexion,$sql1);

echo $sql1;
$r = $conexion -> query($sql1);
$tabla = "";
$vuelta = 1;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8">';
    $tabla = $tabla.'<th width="20"><b>No.</b></th>';
    $tabla = $tabla.'<th ><b>ID CREDITO</b></th>';
    $tabla = $tabla.'<th width="200"><b>FECHA CAPTURA</b></th>';
    $tabla = $tabla."<th><b>CLIENTE</b></th>";
    $tabla = $tabla.'<th ><b>TOTAL</b></th>';
    $tabla = $tabla.'<th ><b>ADEUDO</b></th>';
    $tabla = $tabla.'<th ><b>FECHA VENCIMIENTO</b></th>';
    $tabla = $tabla.'<th ><b>Estatus</b></th>';
  
    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#D7E9F0">'; 
        }
        $tabla = $tabla.'<td width="20">'.$vuelta.'</td>';
        $tabla = $tabla.'<td>'.$f['numcredito'].'</td>';
        $tabla = $tabla.'<td width="200">'.date_format( date_create($f['fecha']), 'd/m/Y h:i:s A').'</td>';
        $tabla = $tabla.'<td>'.$f['nombre'].'</td>';
        $suma = $suma += $f['total'];
        $tabla = $tabla.'<td>$'.number_format($f['total'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>'.$f['adeudo'].'</td>';
        $tabla = $tabla.'<td>'.date_format( date_create($f['fechavencimiento']), 'd/m/Y').'</td>';
        if( $f['estado'] =='1')
        {
            $tabla = $tabla.'<td>ACTIVO</td>';
        }
        else if( $f['estado'] =='0')
        {
            $tabla = $tabla.'<td>LIQUIDADO</td>';
        }else
        {
            $tabla = $tabla.'<td>CANCELADO</td>';
        }
        
        $tabla = $tabla."</tr>";  
        $vuelta++;               
    }
    $tabla = $tabla.'</table>';
    $tabla = $tabla.'<br><br><br>
    <table  align = "center" >
        <tr>
            <td width="20"></td>
            <td ></td>
            <td width="200"></td>
            <td ></td>
            <td bgcolor="#95C5D8">$'.number_format($suma, 2, '.', ',').'</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        
    </table>';
}else{
    $tabla = $tabla.'<br><br><br>
    <table  align = "center" >
        <tr>
            <td  bgcolor="#95C5D8">
                NO SE ENCONTRARON RESULTADOS PARA ESTA CONSULTA
            </td>
        </tr>
        
    </table>';
}




echo $tabla;
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('PUNTO DE VENTA');
$pdf->SetKeywords('Punto de Venta');
//$pdf->SetHeaderData('pdf_logo.jpg', '40','', '');
$pdf->SetHeaderData('aguira.jpg', '40', 'Listado de creditos', "Impreso: ".$fecha."");
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
echo $html;// aqui escribe el contenido de la consulta

$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();
//Close and output PDF document}
ob_end_clean();
$pdf->Output('reporte.pdf', 'I');
?>