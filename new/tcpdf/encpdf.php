<?php
require_once('config/lang/spa.php');
require_once('tcpdf.php');

class PDF extends TCPDF {
    var $pasada=0;
    protected $nombre = '';
    protected $mail = '';
    protected $telefono = '';
    protected $direccion = '';
        
    public function setNombre($usrNombre){
      $this->nombre = $usrNombre;
    }

    public function setMail($usrMail){
      $this->mail = $usrMail;
    }

    public function setTelefono($usrTel){
      $this->telefono = $usrTel;
    }

    public function setDireccion($usrDir){
      $this->direccion = $usrDir;
    }
    
    public function Header() {
        $PosY=5;
        $PosX=82;
        $this->Image('images/cabeza_pdf.gif',$PosX,$PosY,45,15,'','http://www.okeefe.com.ar');
//        $this->SetY(30);
    }

    public function Footer($mail) {
        $this->SetFontSize(6);
        $this->SetFont('helvetica','',6);
        //Go to 1.5 cm from bottom
        $this->SetTextColor(0);
        $this->SetY(-15);
        $txt = "Toda la información comercial, descripción, precios, planos, imágenes, medidas y superficies se basa en información que consideramos fiable y que es proporcionada por terceros. Representa material preliminar al solo efecto informativo e ilustrativo de las características del inmueble, pudiendo estar sujeta a errores, omisiones y cambios.";
        $this->MultiCell(0,4,$txt,0,'L', false);
//	$this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(0, 0, 0)));
//	$this->SetLineWidth(1);
        $this->SetFontSize(8);
        $this->SetFont('helvetica','',8);
        $this->SetFillColor(241, 98, 33);
        $this->SetTextColor(255);
        $txt = $this->nombre . " - email: " . $this->mail . " - " . $this->direccion . " - Tel. " . $this->telefono;
//        $txt = "www.achavalcornejo.com / info@achavalcornejo.com\nCallao 1515 piso 2 -  C.A.B.A. - Tel (05411)4812-1000 / 4814-1600\nPágina ".$this->PageNo()."/{nb}";
//        $this->MultiCell(0,6,$txt,0,'C', true);
        $this->MultiCell($w=0,$h=6,
                $txt,
                $border = 0,
                $align = 'C',
                $fill = true,
                $ln = 1,
                $x = '',
                $y = '',
                $reseth = true,
                $stretch = 0,
                $ishtml = false,
                $autopadding = true,
                $maxh = 6,
                $valign = 'M',
                $fitcell = false);
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
    public function Titulo($titulo) {
        $this->SetFillColor(0,85,45);
        $this->SetTextColor(255);
        $this->SetFont('helvetica','B',11);
        $this->Cell(8);
        $this->Cell(0,5,$titulo,0,2,'L',1);
        $this->Ln();
    }

    function SubTitulo($subtitulo,$col=1) {
    	if($col==1){
    		$x=10;
    	}else{
    		$x=108;
    	}
        $this->SetFillColor(255,255,255);
        $this->Image('images/flechita_pdf.gif',$x, ($this->GetY()+0.6), 3, 3,'GIF','');
//        $this->SetFontSize(10);
        $this->SetTextColor(0,85,45);
        $this->SetFont('helvetica','B',9);
        $this->SetX($x+4);
        //$this->Cell(8);
        $this->Cell(90,5, $subtitulo,'',0,'L',1);
        $this->Ln();
//        $this->SetFontSize(9);
    }

    public function TextoB($texto) {
        $this->SetFontSize(9);
        //$this->SetFillColor(255,255,255);
        $this->SetTextColor(0,85,45);
        $this->SetFont('helvetica','B',9);
        $this->Cell(0,5,$texto,'',0,'L',1);
        $this->Ln();
    }

    function Linea($titulo="-",$texto="",$tasacion=0) {
        $this->SetX(10);
        $this->SetFillColor(245,245,245);
        $this->SetTextColor(60,60,60);
        $this->SetDrawColor(255,255,255);
        $this->SetLineWidth(1);
        $this->SetFont('helvetica','B',8);
        $fill='true';

        //$titulo = $titulo;
        //$texto = $texto;

        $w=$this->GetStringWidth($texto);
        $h=4.5;
        $this->Cell(6);
        $this->Cell(50,$h,$titulo ,'B',0,'L',$fill);
        if ($w <= 40) {
            $this->SetTextColor(51,51,51);
            $this->SetFont('helvetica','',8);
            if($texto == "on") {
                $this->Cell(35,$h,'','B',0,'',$fill);
                $this->Image('images/tilde.gif',66, ($this->GetY()+1), 3, 3.2,'GIF','');
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

    function LineaTasacion($titulo="-",$texto="",$col) {
        if($col ==1){
        	$this->SetX(10);
        }else{
        	$this->SetX(108);
        }
        $this->SetFillColor(245,245,245);
        $this->SetTextColor(60,60,60);
        $this->SetDrawColor(255,255,255);
        $this->SetLineWidth(1);
        $this->SetFont('helvetica','',8);
        $fill='true';
    	        //Datos
        /*
        if ($this->pasada == 0) {
            $fill=0;
            $this->pasada=1;
        } else {
            $fill=!$fill;
            $this->pasada=0;
        }
*/
        $h=5.5;
        $this->Cell(92,$h,$titulo,'B',0,'L',$fill);
        $this->Ln();
    }


}

?>
