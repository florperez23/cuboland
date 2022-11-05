<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../conexion.php";
	require_once 'factura/fpdf/fpdf.php';

    $codigo = $_GET['codigo'];

    $pdf = new FPDF('L', 'mm', array(27, 13));
    $pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Codgio de barras");
		$pdf->SetFont('Arial', 'B', 9);
    // Image example with resizing
    //$img = file_get_contents('codigosGenerados/'.$codigo.'.png');
    //$pdf->Image('@' . $img, 10, 20, 27, 13, '', '', 'center', false, 0, '', false, false, 0, false, false, false);
    $pdf->image('codigosGenerados/'.$codigo.'.png', 0, 1, 27, 13, 'PNG');

    //Close and output PDF document
    $pdf->Output('codigobarra.pdf', 'I');

?>