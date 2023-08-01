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

//$desde = date("Y-m-d",strtotime($desde."- 1 day"));

//$hasta =  date("Y-m-d",strtotime($hasta."+ 1 day"));

$suma = 0;
//echo 'tipopago '.$tipopago.' tipodeventa '.$tipoventa;
if($tipopago == 0 and $tipoventa == 0 and $desde <> '' and $hasta <> ''){
    echo "caso 1";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    inner join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    left join producto p on df.codproducto = p.codproducto
    WHERE CONVERT(f.fecha,date) BETWEEN "'.$desde.'" and "'.$hasta.'" AND f.idtipoventa IN(1, 2, 3) and f.cancelado = 0
    GROUP BY f.nofactura';
}else if($tipopago <> 0 and $tipoventa == 0 and $desde <> '' and $hasta <> ''){
    echo "caso 2";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    inner join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    left join producto p on df.codproducto = p.codproducto
    WHERE CONVERT(f.fecha,date) BETWEEN "'.$desde.'" and "'.$hasta.'" AND f.idtipoventa IN(1, 2, 3) and f.cancelado = 0 ';
   if($tipopago == 1){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or (f.idtipopago=5 and f.efectivo <> 0 ) )';

    }else if($tipopago == 2){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.tarjeta <> 0)) ';

    }else if($tipopago == 3){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'"  or ( f.idtipopago=5 and f.transferencia <> 0) )';

    }else if($tipopago == 4){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.deposito <> 0))';

    }else if($tipopago == 5){
        $sql = $sql.' and f.idtipopago = "'.$tipopago.'"';
    }


    $sql = $sql.' 
    GROUP BY f.nofactura';
}else if($tipopago == 0 and $tipoventa <> 0 and $desde <> '' and $hasta <> ''){
    echo "caso 3";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    left join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    WHERE CONVERT(f.fecha,date) BETWEEN "'.$desde.'" and "'.$hasta.'" and f.idtipoventa = "'.$tipoventa.'" AND f.idtipoventa IN(1, 2, 3) and f.cancelado = 0
    GROUP BY f.nofactura';
}else if($tipopago <> 0 and $tipoventa <> 0 and $desde <> '' and $hasta <> ''){
    echo "caso 4";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    left join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    left join producto p on df.codproducto = p.codproducto
    WHERE CONVERT(f.fecha,date) BETWEEN "'.$desde.'" and "'.$hasta.'" and f.idtipoventa = "'.$tipoventa.'" and f.cancelado = 0 ';
    
    //and idtipopago = "'.$tipopago.'" or (idtipopago=5 and efectivo <> 0 ) or ( idtipopago=5 and tarjeta <> 0) or ( idtipopago=5 and transferencia <> 0) or ( idtipopago=5 and deposito <> 0)
    if($tipopago == 1){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or (f.idtipopago=5 and f.efectivo <> 0 ))';

    }else if($tipopago == 2){
        $sql = $sql.' and ( f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.tarjeta <> 0)) ';

    }else if($tipopago == 3){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'"  or ( f.idtipopago=5 and f.transferencia <> 0) )';

    }else if($tipopago == 4){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.deposito <> 0))';

    }else if($tipopago == 5){
        $sql = $sql.' and f.idtipopago = "'.$tipopago.'"';
    }


   
    $sql = $sql.' 
    GROUP BY f.nofactura';
}else if($tipopago <> 0 and $tipoventa <> 0 and $desde == '' and $hasta == ''){
    echo "caso 5";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    inner join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    left join producto p on df.codproducto = p.codproducto
    WHERE and idtipoventa = "'.$tipoventa.'"  and f.cancelado = 0 ';
    
    if($tipopago == 1){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or (f.idtipopago=5 and f.efectivo <> 0 )) ';

    }else if($tipopago == 2){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.tarjeta <> 0)) ';

    }else if($tipopago == 3){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'"  or ( f.idtipopago=5 and f.transferencia <> 0) )';

    }else if($tipopago == 4){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.deposito <> 0))';

    }else if($tipopago == 5){
        $sql = $sql.' and f.idtipopago = "'.$tipopago.'"';
    }

    
    //idtipopago = "'.$tipopago.'" or (idtipopago=5 and efectivo <> 0 ) or ( idtipopago=5 and tarjeta <> 0) or ( idtipopago=5 and transferencia <> 0) or ( idtipopago=5 and deposito <> 0)
    $sql = $sql.' 
    GROUP BY f.nofactura';
}else if($desde == '' and $hasta == '' and $tipoventa <> 0 and $tipopago == 0){
    echo "caso 6";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    inner join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    left join producto p on df.codproducto = p.codproducto
    WHERE idtipoventa = "'.$tipoventa.'" and f.cancelado = 0
    GROUP BY f.nofactura';

}else if($desde == '' and $hasta == '' and $tipoventa == 0 and $tipopago <> 0){
    echo "caso 7";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    inner join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    left join producto p on df.codproducto = p.codproducto
    WHERE AND f.idtipoventa IN(1, 2, 3) and f.cancelado = 0 ';

    if($tipopago == 1){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or (f.idtipopago=5 and f.efectivo <> 0 )) ';

    }else if($tipopago == 2){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.tarjeta <> 0) )';

    }else if($tipopago == 3){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'"  or ( f.idtipopago=5 and f.transferencia <> 0)) ';

    }else if($tipopago == 4){
        $sql = $sql.' and (f.idtipopago = "'.$tipopago.'" or ( f.idtipopago=5 and f.deposito <> 0))';

    }else if($tipopago == 5){
        $sql = $sql.' and f.idtipopago = "'.$tipopago.'"';
    }
    //idtipopago = "'.$tipopago.'" or (idtipopago=5 and efectivo <> 0 ) or ( idtipopago=5 and tarjeta <> 0) or ( idtipopago=5 and transferencia <> 0) or ( idtipopago=5 and deposito <> 0)
    $sql = $sql.' 
    GROUP BY f.nofactura';

}else{
    echo "caso 8";
    $sql = 'select *, f.subtotal, u.usuario as nomusuario,	if(idtipoventa = 1, "CONTADO",if(idtipoventa = 2, "CREDITO", if(idtipoventa = 3, "DEVOLUCION", "GASTO"))) as tipoventa,
    if(idtipopago = 1, "EFECTIVO", if(idtipopago=2, "TARJETA",if(idtipopago=3, "TRANSFERENCIA",if(idtipopago=4,"DEPOSITO","MIXTO")))) as tipopago
    ,GROUP_CONCAT(df.codproducto) AS codproducto,GROUP_CONCAT(p.descripcion) AS concepto
    from factura f
    inner join detallefactura df on df.nofactura = f.nofactura 
    inner join usuario u on f.usuario = u.idusuario
    left join producto p on df.codproducto = p.codproducto
    WHERE f.fecha AND f.idtipoventa IN(1, 2, 3) and f.cancelado = 0
    GROUP BY f.nofactura';
}
echo $sql;
$r = $conexion -> query($sql);
$tabla = "";
$vuelta = 1;
if ($r -> num_rows >0){
    $tabla = $tabla.'<table  align = "center">';
    $tabla = $tabla.'<tr border="1" bgcolor="#95C5D8">';
    $tabla = $tabla.'<th width="20px"  ><b>No.</b></th>';
    $tabla = $tabla.'<th ><b>ID VENTA</b></th>';
    $tabla = $tabla.'<th ><b>FECHA CAPTURA</b></th>';
    $tabla = $tabla."<th><b>USUARIO</b></th>";
    $tabla = $tabla.'<th ><b>TOTAL</b></th>';
    $tabla = $tabla.'<th ><b>TIPO PAGO</b></th>';
    $tabla = $tabla.'<th ><b>TIPO VENTA</b></th>';
    
    $tabla = $tabla.'<th ><b>CÓDIGO PRODUCTO</b></th>';
    $tabla = $tabla.'<th width="200px"><b>CONCEPTO</b></th>';
    $tabla = $tabla."</tr>";
    while($f = $r -> fetch_array())
    {                  
        if (($vuelta % 2) == 0) {
            $tabla = $tabla.'<tr bgcolor="#FFFFFF">';
        }else{
            $tabla = $tabla.'<tr bgcolor="#D7E9F0">'; 
        }
        $tabla = $tabla.'<td width="20px" >'.$vuelta.'</td>';
        $tabla = $tabla.'<td>'.$f['nofactura'].'</td>';
        $tabla = $tabla.'<td>'.date("d/m/Y", strtotime($f['fecha'])).'</td>';
        $tabla = $tabla.'<td>'.$f['nomusuario'].'</td>';
	      // echo $suma.'<br>';
        if($tipopago <> 5){
            //if($f['efectivo']<> 0){
            if($f['idtipopago'] == 1){
                $suma = $suma += $f['efectivo'];
                $tabla = $tabla.'<td>$'.number_format($f['efectivo'], 2, '.', ',').'</td>';
            }else if($f['idtipopago']== 2){//($f['tarjeta']<> 0){
              
                $suma = $suma += $f['tarjeta'];
                $tabla = $tabla.'<td>$'.number_format($f['tarjeta'], 2, '.', ',').'</td>';
            }else if($f['idtipopago']== 3){
                $suma = $suma += $f['transferencia'];
                $tabla = $tabla.'<td>$'.number_format($f['transferencia'], 2, '.', ',').'</td>';
            }else if($f['idtipopago']== 4){
                $suma = $suma += $f['deposito'];
                $tabla = $tabla.'<td>$'.number_format($f['deposito'], 2, '.', ',').'</td>';
            }else if($f['idtipopago']== 5){
                if ($tipopago == 1){
                    $suma = $suma += $f['efectivo'];
                    $tabla = $tabla.'<td>$'.number_format($f['efectivo'], 2, '.', ',').'</td>';
                }else if ($tipopago == 2){
                    $suma = $suma += $f['tarjeta'];
                    $tabla = $tabla.'<td>$'.number_format($f['tarjeta'], 2, '.', ',').'</td>';
                }else if($tipopago == 3){
                    $suma = $suma += $f['transferencia'];
                    $tabla = $tabla.'<td>$'.number_format($f['transferencia'], 2, '.', ',').'</td>';
                }else if ($tipopago == 4){
                    $suma = $suma += $f['deposito'];
                    $tabla = $tabla.'<td>$'.number_format($f['deposito'], 2, '.', ',').'</td>';

                }else{
                     $suma = $suma += $f['totalfactura'];
                    $tabla = $tabla.'<td>$'.number_format($f['totalfactura'], 2, '.', ',').'</td>';
                }
                
            }else{
                $suma = $suma += $f['totalfactura'];
                $tabla = $tabla.'<td>$'.number_format($f['totalfactura'], 2, '.', ',').'</td>';
            }
        }else{
	       echo '==========ENTRO ELSEE======<BR>';
            if ($tipopago == 2){
                 $suma = $suma += $f['pagocon'];
            }else{
                 $suma = $suma += $f['totalfactura'];
            }
           
            if($tipopago == 2){
                $tabla = $tabla.'<td>$'.number_format($f['pagocon'], 2, '.', ',').'</td>';
            }else{
                $tabla = $tabla.'<td>$'.number_format($f['totalfactura'], 2, '.', ',').'</td>';
            }

         
        }
        
        $tabla = $tabla.'<td>'.$f['tipopago'].'</td>';
        $tabla = $tabla.'<td>'.$f['tipoventa'].'</td>';
        
        $tabla = $tabla.'<td>'.$f['codproducto'].'</td>';
        $tabla = $tabla.'<td width="200px" >'.$f['concepto'].'</td>';
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