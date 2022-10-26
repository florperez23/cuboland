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
if(isset($_POST['tipoventa'])){
    $tipoventa = $_POST['tipoventa'];
}else{
    $tipoventa = "";
}
if(isset($_POST['tipopago'])){
    $tipopago = $_POST['tipopago'];
}else{
    $tipopago = "";
}

$desde = date("Y-m-d",strtotime($desde."- 1 day"));

$hasta =  date("Y-m-d",strtotime($hasta."+ 1 day"));

$suma = 0;
echo 'tipopago '.$tipopago.' tipodeventa '.$tipoventa;
if($tipopago == 0 and $tipoventa == 0 and $desde <> '' and $hasta <> ''){

    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE f.fecha BETWEEN "'.$desde.'" and "'.$hasta.'" and idtipoventa in (1,2,3) ';
}else if($tipopago <> 0 and $tipoventa == 0 and $desde <> '' and $hasta <> ''){
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE f.fecha BETWEEN "'.$desde.'" and "'.$hasta.'" and idtipopago = "'.$tipopago.'" and idtipoventa in (1,2,3) ';
}else if($tipopago == 0 and $tipoventa <> 0 and $desde <> '' and $hasta <> ''){
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE f.fecha BETWEEN "'.$desde.'" and "'.$hasta.'" and idtipoventa = "'.$tipoventa.'" and idtipoventa in (1,2,3) ';
}else if($tipopago <> 0 and $tipoventa <> 0 and $desde <> '' and $hasta <> ''){
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE f.fecha BETWEEN "'.$desde.'" and "'.$hasta.'" and idtipopago = "'.$tipopago.'" and idtipoventa = "'.$tipoventa.'"';
}else if($tipopago <> 0 and $tipoventa <> 0 and $desde == '' and $hasta == ''){
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE idtipopago = "'.$tipopago.'" and idtipoventa = "'.$tipoventa.'"';
}else if($desde == '' and $hasta == '' and $tipoventa <> 0 and $tipopago == 0){
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE idtipoventa = "'.$tipoventa.'"';

}else if($desde == '' and $hasta == '' and $tipoventa == 0 and $tipopago <> 0){
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE idtipopago = "'.$tipopago.'" and idtipoventa in (1,2,3) ';

}else{
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA","DEPOSITO"))) as tipopago
    from factura f
    inner join usuario u on f.usuario = u.idusuario
    WHERE f.fecha and idtipoventa in (1,2,3) ';
}
echo $sql;
$r = $conexion -> query($sql);
$tabla = "";
$vuelta = 1;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8">';
    $tabla = $tabla.'<th ><b>No.</b></th>';
    $tabla = $tabla.'<th ><b>ID VENTA</b></th>';
    $tabla = $tabla.'<th ><b>FECHA CAPTURA</b></th>';
    $tabla = $tabla."<th><b>USUARIO</b></th>";
    $tabla = $tabla.'<th ><b>TOTAL</b></th>';
    $tabla = $tabla.'<th ><b>TIPO PAGO</b></th>';
    $tabla = $tabla.'<th ><b>TIPO VENTA</b></th>';
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
        $tabla = $tabla.'<td>'.date("d/m/Y H:i:s", strtotime($f['fecha'])).'</td>';
        $tabla = $tabla.'<td>'.$f['nomusuario'].'</td>';
        $suma = $suma += $f['totalfactura'];
        $tabla = $tabla.'<td>$'.number_format($f['totalfactura'], 2, '.', ',').'</td>';
        $tabla = $tabla.'<td>'.$f['tipopago'].'</td>';
        $tabla = $tabla.'<td>'.$f['tipoventa'].'</td>';
        $tabla = $tabla."</tr>";  
        $vuelta++;               
    }
    $tabla = $tabla.'</table>';
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
}else{
    $tabla = $tabla.'<br><br><br>
    <table  align = "center" >
        <tr>
            <td  bgcolor="#D7E9F0">
                NO SE ENCONTRARON RESULTADOS PARA ESTA CONSULTA
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
$pdf->SetHeaderData('Imagen1.jpg', '28', 'LISTA DE VENTAS', "Impreso: ".$fecha."");
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