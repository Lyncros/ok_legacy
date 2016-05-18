<?php
	$this->SetFillColor(120,120,120);
	$this->SetTextColor(200);
	$this->SetFontSize(12);
	$this->Cell(160,6,"www.asgpropiedades.com.ar / info@asgpropiedades.com.ar",'LR',0,'C',1);
	$this->Ln();
	$this->SetFontSize(10);
	$this->Cell(53,6,"Suc. Barrio Norte",'L',0,'C',1);
	$this->Cell(54,6,"Suc. Flores",'',0,'C',1);
	$this->Cell(53,6,"Suc. Alto Palermo",'R',0,'C',1);
	$this->Ln();
	$this->Cell(53,6,"Anchorena 1373",'L',0,'C',1);
	$this->Cell(54,6,"J.B. Alberdi 2036",'',0,'C',1);
	$this->Cell(53,6,"Vidt 1907",'R',0,'C',1);
	$this->Ln();
	$this->Cell(53,6,"5778-6666 y rot.",'L',0,'C',1);
	$this->Cell(54,6,"4633-2500 y rot.",'',0,'C',1);
	$this->Cell(53,6,"4827-5256 y rot.",'R',0,'C',1);
	$this->Ln();
	$this->SetFontSize(10);

	$pdf->Output();
?>