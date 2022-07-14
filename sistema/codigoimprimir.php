<?php
ob_start();

include "../conexion.php";
require_once('pdf/tcpdf.php');


$codigo = $_GET['codigo'];


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('CUBOLAND');
$pdf->SetAuthor('CUBOLAND');
$pdf->SetTitle('Código de Barras');
$pdf->SetKeywords('CUBOLAND');

// set default header data
//$pdf->SetHeaderData(K_PATH_IMAGES.'logoca.jpg', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'', PDF_HEADER_STRING);
$pdf->SetHeaderData('Imagen1.jpg', '28', 'Código de barras', "Impreso: ".$fecha."");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// -------------------------------------------------------------------

// add a page
$pdf->AddPage();

// set JPEG quality
$pdf->setJPEGQuality(75);


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Image example with resizing
$img = file_get_contents('codigosGenerados/'.$codigo.'.png');
$pdf->Image('@' . $img, 10, 20, 80, 40, '', '', 'rigth', false, 0, '', false, false, 0, false, false, false);

//$pdf->Image('codigosGenerados/'.$codigo.'.jpg', 15, 140, 75, 113, 'JPG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);


//Close and output PDF document
$pdf->Output('codigobarra.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>