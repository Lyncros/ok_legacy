<?php

include_once("clases/class.perfilesBSN.php");

class Sucursal {

	private $id_sucursal;
	private $sucursal;
	private $lista;
	private $SUCURSALES='Sucursales.xml';

	public function __construct($id_sucursal=0,	$sucursal=''){

		Sucursal::leeSucursal();
		Sucursal::setId_sucursal($id_sucursal);
		Sucursal::setId_sucursal($sucursal);
	}


	public function seteaSucursal($_suc){
		$this->setId_sucursal($_suc->getId_sucursal());
		$this->setSucursal($_suc->getSucursal());
	}


	public function setId_sucursal($id_sucursal){
		$this->id_sucursal = $id_sucursal;
	}

	public function setSucursal($sucursal){
		$this->sucursal = $sucursal;
	}

	public function getId_sucursal(){
		return $this->id_sucursal;
	}

	public function getSucursal(){
		return $this->sucursal;
	}

	public function comboSucursal($valor=0,$campo="id_sucursal",$opcion=0,$class="campos_btn"){
		//		$this->leeSucursal();
		$perf= new PerfilesBSN();
		$auxsuc = $perf->sucursalPerfil($_SESSION['Userrole']);
		$largo=sizeof($this->lista);
		print "<select name='".$campo."' id='".$campo."' class='".$class."'>\n";
        if($auxsuc=='Todas'){
			if($opcion==1) {
				print "<option value='Todas'";
				if ($valor=='Todas'){
					print " SELECTED ";
				}
				print ">Todas</option>\n";
			}
			for ($pos=0;$pos<$largo;$pos++){
				print "<option value='".$this->lista[$pos]['id']."'";
				if ($this->lista[$pos]['id']==$valor){
					print " SELECTED ";
				}
				print ">".$this->lista[$pos]['nombre']."</option>\n";
			}
        }else{
			print "<option value='$auxsuc' SELECTED >".$this->nombreSucursal($auxsuc)."</option>\n";
			        	
        }
		print "</select>\n";
	}

/**
 * Lee las sucursales desde un XML y las carga en un array definido con indices
 * como id y nombre
 *
 */
	private function leeSucursal(){
		$xml_file= $this->SUCURSALES;
		$aux=array();

		if(!is_file($xml_file)) {
			die("Error opening xml file");
		}

		$dom = new DOMDocument("1.0");

		$dom = new DomDocument;
		$dom->validateOnParse = true;

		$dom->load($xml_file);
		$params = $dom->getElementsByTagName('sucursal');

		foreach ($params as $param) {
			$aux[]=array('id'=>$param->getAttribute('id'),'nombre'=>$param->nodeValue);
		}
		$this->lista=$aux;
	}

	public function nombreSucursal($id){
		$this->leeSucursal();
		$largo=sizeof($this->lista);
		//		print_r($this->lista);
		$retorno='';
		for ($pos=0;$pos<$largo;$pos++){
			if ($this->lista[$pos]['id']==$id){
				$retorno=$this->lista[$pos]['nombre'];
				//				break;
			}
		}
		return $retorno;
	}

}

?>