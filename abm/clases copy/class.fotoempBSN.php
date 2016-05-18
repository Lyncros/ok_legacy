<?php

include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.fotoemp.php");
include_once("clases/class.fotoempPGDAO.php");

class FotoempBSN extends BSN {
	
	protected $clase="Fotoemp";
	protected $nombreId="id_foto";
	protected $fotoemp;

	public function __construct($_id_foto=0,$_foto='',$_id_emp_carac=0,$_posicion=0){
		FotoempBSN::seteaMapa();
		if ($_id_foto  instanceof Fotoemp ){
			FotoempBSN::creaObjeto();
			FotoempBSN::seteaBSN($_id_foto);
		} else {
			if (is_numeric($_id_foto)){
				FotoempBSN::creaObjeto();
				if($_id_foto!=0){
					FotoempBSN::cargaById($_id_foto);
				}
			}
		}	
	}
	
	public function getId(){
		return $this->fotoemp->getId_foto();
	}
	
	public function setId($id){
		$this->fotoemp->setId_foto($id);
	}
	
	
	public function cargaFotoempByCaracteristica($_id){
		$fotoaux=new FotoempBSN();
		$fotoaux->fotoemp->setId_emp_carac($_id);
		
		$fotoDB = new FotoempPGDAO($fotoaux->getArrayTabla());
		$this->seteaArray($fotoDB->ColeccionByCarac());
	}
	
	public function cargaColeccionFormByCaracteristica($_id){
		$this->cargaFotoempByCaracteristica($_id);
		$array=$this->{$this->getNombreObjeto()};
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}	
	
	public function getFotoemp(){
		return $this->fotoemp;
	}
	
	public function subirFotoemp(){
		$orden=$this->fotoemp->getPosicion();
		$id_emp_carac=$this->fotoemp->getId_emp_carac();
		if($orden>1){
			$fotoDB=new FotoempPGDAO($this->getArrayTabla());
			$arrayOrden=$this->armaArrayOrden($orden-1,$id_emp_carac);
			foreach ($arrayOrden as $id){
				$foto=new Fotoemp($id);
				$arrayTabla=$this->mapa->objTOtabla($foto);
				$fotoDB2=new FotoempPGDAO($arrayTabla);
				
				$fotoDB2->bajarFotoemp();
			}
			$fotoDB->subirFotoemp();
		}
	}
	
	public function bajarFotoemp(){
		$orden=$this->fotoemp->getPosicion();
		$id_emp_carac=$this->fotoemp->getId_emp_carac();
		$fotoDB=new FotoempPGDAO($this->getArrayTabla());
		$arrayOrden=$this->armaArrayOrden($orden+1,$id_emp_carac);
		if(sizeof($arrayOrden)!=0){
			foreach ($arrayOrden as $id){
				$foto=new Fotoemp($id);
				$arrayTabla=$this->mapa->objTOtabla($foto);
				$fotoDB2=new FotoempPGDAO($arrayTabla);
				$fotoDB2->subirFotoemp();
			}
			$fotoDB->bajarFotoemp($this->fotoemp->getId_foto());
		}
	}	
	
	protected function armaArrayOrden($_orden,$id_emp_carac){
		$array=array();
		$foto=new Fotoemp();
		$foto->setPosicion($_orden);
		$foto->setId_emp_carac($id_emp_carac);
		$arrayTabla=$this->mapa->objTOtabla($foto);
		$fotoDB=new FotoempPGDAO($arrayTabla);
		$result=$fotoDB->coleccionByPosicion();
		
		$conf=CargaConfiguracion::getInstance();
		$tipodb=$conf->leeParametro("tipodb");
		if($tipodb=="my"){
			$nrows="mysql_numrows";
			$fetch="mysql_fetch_array";
		} else {
			$nrows="pg_numrows";
			$fetch="pg_fetch_array";
		}

		if($nrows($result) != 0 ){
			while ($row = $fetch($result)){
				$array[]=$row["id_foto"];
			}
		}
		return $array;
	}	
	
	public function borraFotoemp(){
		$conf=CargaConfiguracion::getInstance();
		$nombre=$conf->leeParametro('path_fotos')."/".$this->fotoemp->getFoto();
		if(file_exists($nombre)){
		    unlink($nombre);
//			if(unlink($nombre)){
//				$this->borraDB();
//			}
		}
		$nombre=$conf->leeParametro('path_fotos_chicas')."/".$this->fotoemp->getFoto();
		if(file_exists($nombre)){
		      unlink($nombre);
//			if(unlink($nombre)){
//				$this->borraDB();
//			}
		}
		$this->borraDB();
	}
}

?>