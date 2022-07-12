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

$sql1="select * from promociones ";
                 
						$query = mysqli_query($conexion,$sql1);

echo $sql1;
$r = $conexion -> query($sql1);
$tabla = "";
$vuelta = 1;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8">';
    $tabla = $tabla.'<th width="20"><b>No.</b></th>';
    $tabla = $tabla.'<th ><b>ID PROMOCION </b></th>';    
    $tabla = $tabla."<th><b>CUBO/PRODUCTO</b></th>";
    $tabla = $tabla.'<th ><b>CODIGO</b></th>';
    $tabla = $tabla.'<th ><b>PORCENTAJE/PRECIO</b></th>';
    $tabla = $tabla.'<th ><b>FECHA INICIO</b></th>';
    $tabla = $tabla.'<th ><b>FECHA TERMINO</b></th>';
    
  
    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#D7E9F0">'; 
        }
        $tabla = $tabla.'<td width="20">'.$vuelta.'</td>';
        $tabla = $tabla.'<td>'.$f['idpromocion'].'</td>';
        if( $f['idclasificacion'] =='1')
        {
            $tabla = $tabla.'<td>Cubo</td>';
        }else
        {
            $tabla = $tabla.'<td>Producto</td>';
        }
        // $tabla = $tabla.'<td width="200">'.date_format( date_create($f['fecha']), 'd/m/Y h:i:s A').'</td>';
        $tabla = $tabla.'<td>'.$f['ididentificador'].'</td>';
        if( $f['idtipo'] =='1')
        { $tabla = $tabla.'<td>'.$f['promocion'].'%</td>';

        }else
        { $tabla = $tabla.'<td>$'.$f['promocion'].'</td>';

        }
        $tabla = $tabla.'<td>'.date_format( date_create($f['fechainicio']), 'd/m/Y').'</td>';
        $tabla = $tabla.'<td>'.date_format( date_create($f['fechatermino']), 'd/m/Y').'</td>';
      
        
        $tabla = $tabla."</tr>";  
        $vuelta++;               
    }
    $tabla = $tabla.'</table>';
    $tabla = $tabla.'<br><br><br>
   ';
}else{
    $tabla = $tabla.'<br><br><br>
    <table  align = "center" >
        <tr>
            <td  bgcolor="#FCD2CB">
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
$pdf->SetTitle('CUBOLAND');
$pdf->SetKeywords('Tienda de cubos');
$pdf->SetHeaderData('Imagen1.jpg', '28', 'LISTA DE PROMOCIONES', "Impreso: ".$fecha."");
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