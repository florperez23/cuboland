<?php
ob_start();
include "../conexion.php";
require_once('pdf/tcpdf.php');
include("includes/functions.php");
$codigo = $_GET['codigo'];

class MYPDF1 extends TCPDF {

    //Page header
    public function Header() {
    }
    // Page footer
    public function Footer() {
    }
  }

// create new PDF document
$pdf = new MYPDF1('L',  'mm', array(13,54), true, 'UTF-8', false);

//$img = file_get_contents('codigosGenerados/'.$codigo.'.png');
//$pdf->Image('@' . $img, 0, 2, 22, 8, '', '', 'rigth', false, 0, '', false, false, 0, false, false, false);

$descripcion = descripcion_producto($codigo);
$precio = precio_producto($codigo);

// set font
$pdf->SetFont('times', '', 6);
$t = "";
/*$t = $t.'
    <table style="font-size:6;" border="1">
    <tr >
        <td style="width:23mm; ">
            '.$descripcion.'
        </td>
        <td style="width:23mm; ">
            '.$descripcion.'
        </td>
    </tr>
   
    
    </table>
';*/

//$t = '<span style="font-size:4pt;" >'.$descripcion.'</span>';
/* <tr >
        <td style="width:23mm; ">
            '.$precio.'
        </td>
        <td style="width:23mm; ">
            '.$precio.'
        </td>
    </tr><tr width="54" height="11">
        <td>
            <img src="codigosGenerados/'.$codigo.'.png" style="width:23px; height:8px">
        </td>
        <td>
            <img src="codigosGenerados/'.$codigo.'.png" style="width:23px; height:8px" >
        </td>
    </tr>*/

//$pdf->writeHTML($t, true, false, true, false, '');
$pdf->SetXY(0, 0);
$pdf->Cell(0, 0, $descripcion, 1, 0, 'C', 0, '', 0);
$pdf->Cell(0, 0, $descripcion, 1, 1, 'C', 0, '', 0);
ob_end_clean();

//Close and output PDF document
$pdf->Output('codigobarra.pdf', 'I');
?>