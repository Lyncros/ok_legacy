<?php 
class Dato {

	public function getClase(){
		return $this->clase;	
	}
	
	public function muestra(){
		$var='muestra'.$this->getClase();
		$this->{$var}();
	}
}

class DatoA extends Dato{
	
	protected $clase="dato";
	
	public function muestradato(){
		echo "Muestra los Dato de la otra Clase";
	}
}


$a="DatoA";
$p= new $a();
//$p=new DatoA();
$p->muestra();
?>