<?php
define('FPDF_FONTPATH','Clases/font/');
require('Clases/fpdf.php');

class PDF extends FPDF
{
var $pasada=0;

function Header(){
$PosY=10;
$PosX=10;
$pdf->Image($foto1,$PosX,$PosY,200,70,'','');
}


function Titulo($titulo=""){
	$this->SetFontSize(12);
	$this->SetFillColor(120,120,120);
	$this->SetTextColor(200);
	$this->SetFont('');
	$this->Cell(160,6,$titulo,'LR',0,'C',1);
	$this->Ln();
	$this->SetFontSize(10);
}

function Linea($titulo="-",$texto="*"){
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	//Datos
	$fill=0;
	if ($this->pasada == 0){
		$fill=0;
		$this->pasada=1;	
	} else {
		$fill=!$fill;
		$this->pasada=0;	
	}

	$w=$this->GetStringWidth($texto);
	$h=$w/100;
	if ($h < 6){
		$h=6;
	} 

	$this->Cell(60,$h,$titulo,'LR',0,'L',$fill);
	$this->Cell(100,$h,$texto,'LR',0,'L',$fill);

	//$this->MultiCell(160,$h,$titulo,'LR','L',1);

	$this->Ln();
//	$this->Cell(array_sum($w),0,'','T');
}

}

$pdf=new PDF();
//Títulos de las columnas
//Carga de datos
$pdf->SetMargins(10,10,1);
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

?>
