<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');

if(isset($_POST['categoria'])){
    $cat = $_POST['categoria'];
}else{
    $cat = "";
}

if(isset($_POST['sec'])){
    $seccion = $_POST['sec'];
}else{
    $seccion = "";
}
//echo $cat.' '.$seccion;
if($cat == 0){
    $sql = 'select p.*, cd.departamento, cs.seccion as nomseccion
    from producto p
    left join cat_departamento cd on cd.iddepartamento = p.categoria
    left join cat_secciones cs on cs.idseccion = p.seccion
    ';
}else if($cat <> '' and $seccion <> ''){

    $sql = 'select p.*, cd.departamento, cs.seccion as nomseccion
    from producto p
    left join cat_departamento cd on cd.iddepartamento = p.categoria
    left join cat_secciones cs on cs.idseccion = p.seccion
    WHERE p.categoria = '.$cat.' and p.seccion = '.$seccion.'';
}else if($cat <> '' and $seccion == 0){
    $sql = 'select p.*, cd.departamento, cs.seccion as nomseccion
    from producto p
    left join cat_departamento cd on cd.iddepartamento = p.categoria
    left join cat_secciones cs on cs.idseccion = p.seccion
    WHERE p.categoria = '.$cat.'';
}
echo $sql;
$r = $conexion -> query($sql);
$tabla = "";
$vuelta = 0;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#FAAC9E">';
    $tabla = $tabla.'<th ><b>CODIGO</b></th>';
    $tabla = $tabla.'<th ><b>DESCRIPCION</b></th>';
    $tabla = $tabla."<th><b>PRECIO COMPRA</b></th>";
    $tabla = $tabla.'<th ><b>PRECIO COSTO</b></th>';
    $tabla = $tabla.'<th ><b>EXISTENCIA</b></th>';
    $tabla = $tabla.'<th ><b>CATEGORÍA</b></th>';
    $tabla = $tabla.'<th ><b>SECCIÓN</b></th>';
    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#FCD2CB">'; 
        }
        $tabla = $tabla.'<td>'.$f['codigo'].'</td>';
        $tabla = $tabla.'<td>'.$f['descripcion'].'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['precio'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>$'.number_format($f['preciocosto'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>'.$f['existencia'].'</td>';
       
            $tabla = $tabla.'<td>'.$f['departamento'].'</td>';
        
        $tabla = $tabla.'<td>'.$f['nomseccion'].'</td>';
        $tabla = $tabla."</tr>";  
        $vuelta++;               
    }
    $tabla = $tabla.'</table>';

    $tabla = $tabla.'<br><br><br>
    <table  align = "center" >
        <tr>
            <td>
                
            </td>
            <td  bgcolor="#FCD2CB">
                TOTAL DE PRODUCTOS MOSTRADOS '.$vuelta.'
            </td>
        </tr>
        
    </table>';
}else{
    $tabla = $tabla.'<br><br><br>
    <table  align = "center" >
        <tr>
            <td  bgcolor="#FCD2CB">
                NO EXISTEN RESULTADOS PARA ESTA CONSULTA
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
$pdf->SetHeaderData('aguira.jpg', '40', 'PRODUCTOS', "Impreso: ".$fecha."");
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