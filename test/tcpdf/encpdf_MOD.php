<?php
require_once('config/lang/spa.php');
require_once('tcpdf.php');

class PDF extends TCPDF {
    var $pasada=0;

    public function Header() {
        $this->SetY(0);
        $this->SetFillColor(49,107,178);
        $this->SetTextColor(255);
        $this->Cell(0,20,'',0,2,'L',true);

        $PosY=5;
        $PosX=18;

        $this->Image('images/achaval.gif',$PosX,$PosY,50,0,'','http://www.achavalcornejo.com');
        $this->SetY(30);
    }

    public function Footer() {
        //Go to 1.5 cm from bottom
        $this->SetY(-15);
//	$this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(0, 0, 0)));
//	$this->SetLineWidth(1);
        $this->SetFillColor(49,107,178);
        $this->SetTextColor(255);


        $this->SetFont('helvetica','',8);
//        $this->SetTextColor(0);
        $this->SetFontSize(8);
        $txt = "www.achavalcornejo.com / info@achavalcornejo.com\nCallao 1515 piso 2 -  C.A.B.A. - Tel (05411)4812-1000 / 4814-1600\nPágina ".$this->PageNo()."/{nb}";
        $this->MultiCell(0,4,$txt,'0','C', true);
//        $this->MultiCell(0,4,$txt,'T','C', false);
    }
/*
    public function Foto($foto="", $ancho=0, $alto=0, $columna, $fila) {
	//	echo $ancho ." - " . $alto  ." - " . $columna  ." - " . $fila  ." - " . $this->GetX() ." - " . $this->GetY();
	//	die();
        if($columna == 1) {
            $PosX = 5;
        }else {
            $PosX = $this->GetX()+5+$ancho * $columna ;
        }
        $PosY = $this->GetY()+5;
        $this->Image($foto,30,$PosY,$ancho,$alto,'','');
    }
*/


    public function Titulo2($tituloI,$tituloC,$tituloD) {
//        $this->SetFillColor(49,107,178);
        $this->SetTextColor(49,107,178);
        $this->SetFont('helvetica','B',11);
	       $this->Cell(8);              
		 $this->MultiCell(100,10,$tituloI,'0','L', false,0);
		 $this->MultiCell(50,10,$tituloC,'0','L', false,0);
	  $this->MultiCell(0,10,$tituloD,'0','R', false,0);

        $this->Ln();
    }

    public function BloqueFotoDesc($foto,$precio,$superficie,$descripcion) {
        $this->SetFillColor(49,107,178);
        $this->SetTextColor(0);
        $this->SetFont('helvetica','',11);
	       $this->Cell(8); 
         
//        $this->MuestraFoto($foto);             
//        $this->Image($foto,10, ($this->GetY()+0.5), 50, 0,'GIF','','L',true);
 
    	   $this->Cell(50,10,$precio,'0','L', true,0);
		     $this->Cell(50,10,$superficie,'0','R', true,1);
		     $this->SubTituloSF('Descripcion');
         $this->SetTextColor(0);
         $this->SetFont('helvetica','',11);
	       $this->MultiCell(0,10,$descripcion,'0','L', false,1);

        $this->Ln();
    }


    public function Titulo($titulo) {
        $this->SetFillColor(49,107,178);
        $this->SetTextColor(255);
        $this->SetFont('helvetica','B',11);
	$this->Cell(8);
        $this->Cell(0,5,$titulo,0,2,'L',1);
        $this->Ln();
    }

    function SubTituloSF($subtitulo) {
        $this->SetFillColor(255,255,255);
//        $this->SetFontSize(10);
        $this->SetTextColor(49,107,178);
        $this->SetFont('helvetica','B',10);
	$this->SetX(7);
        $this->Cell(8);
        $this->Cell(90,5, $subtitulo,'',0,'L',1);
        $this->Ln();
//        $this->SetFontSize(10);
    }

    function SubTitulo($subtitulo) {
        $this->SetFillColor(255,255,255);
        $this->Image('images/flechita_pdf.gif',10, ($this->GetY()+0.5), 3.6, 3.6,'GIF','');
//        $this->SetFontSize(10);
        $this->SetTextColor(49,107,178);
        $this->SetFont('helvetica','B',10);
	$this->SetX(7);
        $this->Cell(8);
        $this->Cell(90,5, $subtitulo,'',0,'L',1);
        $this->Ln();
//        $this->SetFontSize(10);
    }

    public function TextoB($texto) {
        $this->SetFontSize(10);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(49,107,178);
        $this->SetFont('helvetica','B',10);
        $this->Cell(0,5,$texto,'',0,'L',1);
        $this->Ln();
    }

    function Linea($titulo="-",$texto="-") {
	$this->SetX(10);
        $this->SetFillColor(245,245,245);
        $this->SetTextColor(60,60,60);
        $this->SetDrawColor(255,255,255);
        $this->SetLineWidth(1);
        $this->SetFont('helvetica','B',10);
        $fill='true';

        $titulo = $titulo;
        $texto = $texto;

        $w=$this->GetStringWidth($texto);
        $h=6;
        $this->Cell(6);
        $this->Cell(60,$h,$titulo ,'B',0,'L',$fill);
        if ($w <= 30) {
            $this->SetTextColor(51,51,51);
            $this->SetFont('helvetica','',10);
            if($texto == "on") {
                $this->Cell(35,$h,'','B',0,'',$fill);
                $this->Image('images/tilde.gif',78, ($this->GetY()+1), 3, 3.2,'GIF','');
            }else {
                $this->Cell(35,$h,$texto,'B',0,'L',$fill);
            }
            $this->Ln();
        } else {
            $largo=strlen($texto);
            $mtexto=$texto;
            $pos=1;
            $posant=0;
            while ($pos < $largo && $pos>0) {
                if (60 > $this->GetStringWidth(substr($mtexto,0,$pos))) {
                    $posant=$pos;
                } else {
                    $ltexto=substr($mtexto,0,$posant);
                    $mtexto=substr($mtexto,$posant+1,$largo-$posant);
                    $posant=0;
                    if ($titulo!='') {
                        $titulo='';
                    } else {
                        $this->Cell(60,$h,1,'LR',0,'L',$fill);
                    }
                    $this->Cell(60,$h,$ltexto,1,'LR',0,'L',$fill);
                    $this->Ln();
                }
                $pos=strpos($mtexto," ",$posant + 1);
            }

            if ($titulo!='') {
                $titulo='';
            } else {
                $this->Cell(60,$h,' ','LR',0,'L',$fill);
            }
            $this->Cell(60,$h,$mtexto,1,'LR',0,'L',$fill);
            $this->Ln();

        }
    }

    function LineaEnc($titulo="-",$texto="*") {
        $this->SetX(85);
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        //Datos
        $fill=0;
        if ($this->pasada == 0) {
            $fill=0;
            $this->pasada=1;
        } else {
            $fill=!$fill;
            $this->pasada=0;
        }

        $w=$this->GetStringWidth($texto);
        $h=$w/80;
        if ($h < 6) {
            $h=6;
        }

        $this->Cell(30,$h,$titulo,'LR',0,'L',$fill);
        $this->Cell(85,$h,$texto,'LR',0,'L',$fill);
        $this->Ln();
    }
    
    function MuestraFoto($foto){
       $inicio = 1;
       $salto_alto = 60;
       $dim_foto = getimagesize($foto);
	     $ancho_cm = $dim_foto[0] / 37.795275591;
	     $coef = 90 / $ancho_cm;
	     $alto_cm = $dim_foto[1] / 37.795275591;
      	if($inicio == 1){
      		$x = 10;
		      $y = $pdf->GetY();
		      $inicio++;
	     }else{
		      $x = 110;
		      $nuevo_y = $y+($alto_cm * $coef) + 5;
		      $pdf->SetY($nuevo_y);
		      $inicio = 1;
        //		$contador++;
	     }
	     switch ($dim_foto['mime']){
		      case 'image/jpeg':
			       $tipo = 'JPG';
			       break;
		      case 'image/gif':
			       $tipo = 'GIF';
			     break;
	     }
	     $pdf->Image($foto, $x, $y , 90, ($alto_cm * $coef),$tipo,'');
    }
}

?>
