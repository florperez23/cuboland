<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../conexion.php";
	include("includes/functions.php");
	

	require_once 'factura/fpdf/fpdf.php';

    $codigo = $_GET['codigo'];

    $pdf = new FPDF('L', 'mm', array(54, 10));
    $pdf->AddPage();
	//$pdf->SetMargins(1, 0, 0);
	$pdf->SetTitle("Codgio de barras");
	$pdf->SetFont('Arial', '', 2);
    // Image example with resizing
    //$img = file_get_contents('codigosGenerados/'.$codigo.'.png');
    //$pdf->Image('@' . $img, 10, 20, 27, 13, '', '', 'center', false, 0, '', false, false, 0, false, false, false);
   //izquierda
	 //$pdf->image('codigosGenerados/'.$codigo.'.png', 0, 1, 27, 13, 'PNG');
	 //$pdf->AddPage();
	//derecha
	 //$pdf->image('codigosGenerados/'.$codigo.'.png', 30, 1, 27, 13, 'PNG');
	 //$pdf->AddPage();

	 //precio y descripcion
	// $descripcion = descripcion_producto($codigo);
	 //$precio = precio_producto($codigo);

	
	
	//$pdf->SetXY(0, 0);
	/*$pdf->Cell(10,2,'Columna1',1,0,'C');
	$pdf->Cell(10,2,'palabras2',1,1,'C');
	//$pdf->SetY(2); /* Set 20 Eje Y */
	/*$pdf->Cell(10,2,'Columna3',1,0,'C');
	$pdf->Cell(10,2,'Columna4',1,1,'C');*/

/*
	
	$pdf->Cell(25, 1, $descripcion, 1, 0, 'C');
	$pdf->MultiCell(25,1,$descripcion,1,0,'C');
	//$pdf->Cell(25, 1, $descripcion, 0, 0, 'C');

	$pdf->SetXY(0, 2);
	$pdf->Cell(25, 1, '$'.$precio, 0, 0, 'C');
	$pdf->MultiCell(25,1,'$'.$precio,1,0,'C');
	//$pdf->Cell(25, 1, '$'.$precio, 0, 0, 'C');
	//dos
	$pdf->SetXY(2, 4);
	

	/*$pdf->SetXY(30, 0);
	

	$pdf->SetXY(30, 2);
	 */

	/* $pdf->SetXY(34, 4);*/
	$pdf->image('codigosGenerados/'.$codigo.'.png', 0, 0,25, 11, 'PNG');
	$pdf->image('codigosGenerados/'.$codigo.'.png', 34, 0, 25, 11, 'PNG');

    //Close and output PDF document
    $pdf->Output('codigobarra.pdf', 'I');

?>