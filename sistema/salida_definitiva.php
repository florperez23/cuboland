<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');


    $idcubo = $_GET['idcubo'];


    $sql = 'SELECT
    p.*,
    a.nombre
    FROM
    producto p 
    INNER JOIN rentas r on r.idcubo = p.codcubo
    INNER JOIN arrendatarios a on a.idarrendatario = r.idarrendatario
   where p.codcubo = '.$idcubo.' and p.existencia > 0 GROUP BY p.codproducto';
    echo $sql;
    $r = $conexion -> query($sql);
    $tabla = "";
    
    $vuelta = 1;
    if ($r -> num_rows >0){
        $tabla = $tabla.'<table  align = "center">';
        $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8">';
        $tabla = $tabla.'<th ><b>No.</b></th>';
        $tabla = $tabla.'<th ><b>CODIGO PRODUCTO</b></th>';
        $tabla = $tabla.'<th ><b>DESCRIPCION</b></th>';
        $tabla = $tabla."<th><b>PRECIO</b></th>";  
        $tabla = $tabla."<th><b>CANTIDAD</b></th>";  
        
        $tabla = $tabla."</tr>";
        while($f = $r -> fetch_array())
        {                  
            if (($vuelta % 2) == 0) {
                $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
            }else{
                $tabla = $tabla.'<tr bgcolor="#D7E9F0">'; 
            }
            $tabla = $tabla.'<td>'.$vuelta.'</td>';
            $tabla = $tabla.'<td>'.$f['codproducto'].'</td>';
            $tabla = $tabla.'<td>'.$f['descripcion'].'</td>';
            $tabla = $tabla.'<td>'.$f['precio'].'</td>';
            $tabla = $tabla.'<td>'.$f['existencia'].'</td>';
            $tabla = $tabla."</tr>";  
            $vuelta++;   
            $nombre = $f['nombre'];            
        }
        $tabla = $tabla.'</table>';
    }
    
    
    $tabla = $tabla.'<br><br><br><br><br>
    <table  align = "center" >
        <tr>
           
            <td></td>
            <td></td>
            <td style="font-size:12px; border-top: 1px solid #000;">Arrendatario del Cubo '.$nombre.'</td>
        </tr>
        <tr>
        
            <td></td>
            <td></td>
            <td>Firma de conformidad</td>
        </tr>
        
    </table>';
    
    //SI ENTRO AQUI QUIERE DECIR QUE YA SE HARÁ LA SALIDA DEFINITIVA, HAY QUE ELIMINAR TODO EL INVENTARIO DEL CUBO QUE SE VA
    
       $sql = "DELETE FROM producto WHERE codcubo = ".$idcubo."";
       echo $sql;
       $query_delete = mysqli_query($conexion, $sql);
      mysqli_close($conexion);
    
    echo $tabla;
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('CUBOLAND');
$pdf->SetKeywords('Tienda de cubos');
$pdf->SetHeaderData('Imagen1.jpg', '28', 'SALIDA DEFINITIVA DE PRODUCTOS', "Impreso: ".$fecha."");
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