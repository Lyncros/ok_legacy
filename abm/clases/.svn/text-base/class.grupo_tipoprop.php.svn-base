<?php

class Grupo_tipoprop {

	private $id_tipo_prop;
	private $grupo;
	private $lista;
	private $GRUPO='Grupo_tipoprop.xml';

	public function __construct($id_tipo_prop=0,$grupo=''){
		Grupo_tipoprop::leeGrupo();
		Grupo_tipoprop::setId_tipo_prop($id_tipo_prop);
		Grupo_tipoprop::setGrupo($grupo);
	}


	public function seteaGrupo_tipoprop($_suc){
		$this->setId_tipo_prop($_suc->getId_tipo_prop());
		$this->setGrupo($_suc->getGrupo());
	}


	public function setId_tipo_prop($id_tipo_prop){
		$this->id_tipo_prop = $id_tipo_prop;
	}

	public function setGrupo($grupo){
		$this->grupo = $grupo;
	}

	public function getId_tipo_prop(){
		return $this->id_tipo_prop;
	}

	public function getGrupo(){
		return $this->grupo;
	}

	public function comboGrupo($valor=0,$campo="id_tipo_prop",$opcion=0,$class="campos_btn"){
		//		$this->leeGrupo();
		$largo=sizeof($this->lista);
		print "<select name='".$campo."' id='".$campo."' class='".$class."'>\n";
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
		print "</select>\n";
	}

	/**
 * Lee las grupoes desde un XML y las carga en un array definido con indices
 * como id y nombre
 *
 */
	private function leeGrupo(){
		$xml_file= $this->GRUPO;
		$aux=array();

		if(!is_file($xml_file)) {
			die("Error opening xml file");
		}

		$dom = new DOMDocument("1.0");

		$dom = new DomDocument;
		$dom->validateOnParse = true;

		$dom->load($xml_file);
		$params = $dom->getElementsByTagName('grupo_tipoprop');

		foreach ($params as $param) {
			$aux[]=array('grupo'=>$param->getAttribute('grupo'),'id_tipo_prop'=>$param->nodeValue);
		}
		$this->lista=$aux;
//		print_r($this->lista);
	}
	
	public function listaTipopropGrupo($grupo){
		$this->leeGrupo();
		$largo=sizeof($this->lista);
		$retorno='';
		for ($pos=0;$pos<$largo;$pos++){
			if ($this->lista[$pos]['grupo']==$grupo){
				$retorno.=$this->lista[$pos]['id_tipo_prop'].",";
				//				break;
			}
		}
		if(strlen($retorno)>0){
			$retorno=substr($retorno,0,strlen($retorno)-1);
		}
		return $retorno;
	}

	public function perteneceGrupo($id,$grupo){
		$this->leeGrupo();
		$largo=sizeof($this->lista);
//		print_r($this->lista);
		$retorno=false;
		for ($pos=0;$pos<$largo;$pos++){
			if ($this->lista[$pos]['id_tipo_prop']==$id && $this->lista[$pos]['grupo']==$grupo ){
				$retorno=true;
				//				break;
			}
		}
		return $retorno;
	}


}

?>