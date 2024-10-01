<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');

$tipo = $_POST['tipo'];

//echo $tipo;

$ultimo = date("Y-m-t", strtotime($fecha));
$primero = date('Y-m-01');

$desde = date("Y-m-d",strtotime($primero));//."- 1 day"
$hasta =  date("Y-m-d",strtotime($ultimo));//."+ 1 day"

if($tipo == 0){
    $sql = 'select r.*, a.nombre, c.cubo, c.renta from rentas r
    inner join arrendatarios a on a.idarrendatario = r.idarrendatario
    inner join cubos c on c.codcubo = r.idcubo
    where r.cancelado = 0 ';
}else if($tipo == 1){
    $sql = 'SELECT
	r.*,
	a.nombre,
	c.cubo, c.renta, f.totalfactura, f.idtipopago
FROM
	rentas r
	LEFT JOIN arrendatarios a ON a.idarrendatario = r.idarrendatario
	LEFT JOIN cubos c ON c.codcubo = r.idcubo 
	LEFT JOIN factura f on f.observaciones = r.idcubo  and date(f.fecha) = r.fechaultimopago
WHERE
	f.cancelado = 0 and r.cancelado = 0
	AND r.fechaultimopago BETWEEN  "'.$desde.'" and "'.$hasta.'"' ;
}else if($tipo == 2){
    $sql = 'select r.*, a.nombre, c.cubo, c.renta
    from rentas r
    inner join arrendatarios a on a.idarrendatario = r.idarrendatario
    inner join cubos c on c.codcubo = r.idcubo
 
    where r.cancelado = 0 and (r.fechaultimopago <= "'.$desde.'" or r.fechaultimopago is null)' ;
}

echo $sql;
$r = $conexion -> query($sql);
$tabla = "";
$vuelta = 1;
$suma = 0;
$suma1 = 0;
$suma2 = 0;
$suma3 = 0;
$suma4 = 0;
$suma5 = 0;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8">';
    $tabla = $tabla.'<th width="20px"><b>No.</b></th>';
    $tabla = $tabla.'<th ><b>IDRENTA.</b></th>';
    $tabla = $tabla.'<th ><b>CUBO</b></th>';
    $tabla = $tabla."<th><b>ARRENDATARIO</b></th>";
    $tabla = $tabla."<th><b>PRECIO</b></th>";
    $tabla = $tabla.'<th ><b>FECHA CONTRATO</b></th>';
    $tabla = $tabla.'<th ><b>FECHA ULTIMO PAGO</b></th>';
    if($tipo == 1){
        $tabla = $tabla.'<th ><b>TOTAL PAGADO</b></th>';
        $tabla = $tabla.'<th ><b>TIPO PAGO</b></th>';
    }
   
    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#D7E9F0">'; 
        } 
        $tabla = $tabla.'<td>'.$vuelta.'</td>';
        $tabla = $tabla.'<td>'.$f['id'].'</td>';
        $tabla = $tabla.'<td>'.$f['cubo'].'</td>';
        $tabla = $tabla.'<td>'.$f['nombre'].'</td>';
        $tabla = $tabla.'<td>'.$f['renta'].'</td>';
        $fechaContrato = date("d/m/Y", strtotime($f['fechacontrato']));
        $tabla = $tabla.'<td>'.$fechaContrato.'</td>';

        $fechaultimopago = date("d/m/Y", strtotime($f['fechaultimopago']));
        $tabla = $tabla.'<td>'.$fechaultimopago.'</td>';
        if($tipo == 1){
            $tabla = $tabla.'<td> $'.number_format($f['totalfactura'], 2, '.', ',').'</td>';
            $suma = $suma += $f['totalfactura'];
            echo "tipo pago".$f['idtipopago'].'<br>';
            $tabla = $tabla.'<td>';
            if($f['idtipopago'] == 1){
                $tabla = $tabla.'EFECTIVO';
                $suma1 += $suma1;
            }else if($f['idtipopago'] == 2){
                $tabla = $tabla.'TARJETA';
                $suma2 += $suma2;
            }else if($f['idtipopago'] == 3){
                $tabla = $tabla.'TRANSFERENCIA';
                $suma3 += $suma3;
            }else if($f['idtipopago'] == 4){
                $tabla = $tabla.'DEPOSITO';
                $suma4 += $suma4;
            }else if($f['idtipopago'] == 4){
                $tabla = $tabla.'MIXTO';
                $suma5 += $suma5;
            }
            $tabla = $tabla.'</td>';
        }
        $tabla = $tabla."</tr>";  
        $vuelta++;               
    }
    $tabla = $tabla.'</table>';
}

if($tipo == 1){
    $tabla = $tabla.'<br><br><br>
    <table  align = "center" >
        <tr>
            <td>
                
            </td>
            <td  bgcolor="#D7E9F0">
                MONTO TOTAL GENERADO $'.number_format($suma, 2, '.', ',').'<BR>
                EFECTIVO $'.number_format($suma1, 2, '.', ',').'<BR>
                TARJETA $'.number_format($suma2, 2, '.', ',').'<BR>
                TRANSFERENCIA $'.number_format($suma3, 2, '.', ',').'<BR>
                DEPOSITO $'.number_format($suma4, 2, '.', ',').'<BR>
                MIXTO $'.number_format($suma5, 2, '.', ',').'<BR>
            </td>
        </tr>
        
    </table>';
}


echo $tabla;
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('CUBOLAND');
$pdf->SetKeywords('Tienda de cubos');
$pdf->SetHeaderData('Imagen1.jpg', '28', 'LISTADO DE RENTAS', "Impreso: ".$fecha."");
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