<?php

include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.foto.php");
include_once("clases/class.fotoPGDAO.php");

class FotoBSN extends BSN {

	protected $clase="Foto";
	protected $nombreId="id_foto";
	protected $foto;

	public function __construct($_id_foto=0,$_foto='',$_id_prop=0,$_posicion=0){
		FotoBSN::seteaMapa();
		if ($_id_foto  instanceof Foto ){
			FotoBSN::creaObjeto();
			FotoBSN::seteaBSN($_id_foto);
		} else {
			if (is_numeric($_id_foto)){
				FotoBSN::creaObjeto();
				if($_id_foto!=0){
					FotoBSN::cargaById($_id_foto);
				}
			}
		}
	}

	public function getId(){
		return $this->foto->getId_foto();
	}

	public function setId($id){
		$this->foto->setId_foto($id);
	}


	public function cargaFotoByPropiedad($_id){
		$fotoaux=new FotoBSN();
		$fotoaux->foto->setId_prop($_id);

		$fotoDB = new FotoPGDAO($fotoaux->getArrayTabla());
		$this->seteaArray($fotoDB->ColeccionByProp());
	}

	public function cargaColeccionFormByPropiedad($_id){
		$this->cargaFotoByPropiedad($_id);
		$array=$this->{$this->getNombreObjeto()};
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}

	public function cargaColeccionPublicasFormByPropiedad($_id){
		$fotoaux=new FotoBSN();
		$fotoaux->foto->setId_prop($_id);
		$fotoDB = new FotoPGDAO($fotoaux->getArrayTabla());
		$this->seteaArray($fotoDB->ColeccionPublicasByProp());
		
		$array=$this->{$this->getNombreObjeto()};
		$arrayform=array();
		foreach ($array as $registro){
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}

	public function getFoto(){
		return $this->foto;
	}

	public function subirFoto(){
		$orden=$this->foto->getPosicion();
		if($orden>1){
			$fotoDB=new FotoPGDAO($this->getArrayTabla());
			$arrayOrden=$this->armaArrayOrden($orden-1);
			foreach ($arrayOrden as $id){
				$foto=new Foto($id);
				$arrayTabla=$this->mapa->objTOtabla($foto);
				$fotoDB2=new FotoPGDAO($arrayTabla);

				$fotoDB2->bajarFoto();
			}
			$fotoDB->subirFoto();
		}
	}

	public function bajarFoto(){
		$orden=$this->foto->getPosicion();
		$fotoDB=new FotoPGDAO($this->getArrayTabla());
		$arrayOrden=$this->armaArrayOrden($orden+1);
		if(sizeof($arrayOrden)!=0){
			foreach ($arrayOrden as $id){
				$foto=new Foto($id);
				$arrayTabla=$this->mapa->objTOtabla($foto);
				$fotoDB2=new FotoPGDAO($arrayTabla);
				$fotoDB2->subirFoto();
			}
			$fotoDB->bajarFoto($this->foto->getId_foto());
		}
	}

	public function mostrarFoto(){
		$fotoDB=new FotoPGDAO($this->getArrayTabla());
		$fotoDB->mostrarFoto($this->foto->getId_foto());
	}

	public function ocultarFoto(){
		$fotoDB=new FotoPGDAO($this->getArrayTabla());
		$fotoDB->ocultarFoto($this->foto->getId_foto());
	}

	protected function armaArrayOrden($_orden){
		$array=array();
		$foto=new Foto();
		$foto->setPosicion($_orden);
		$foto->setId_prop($this->foto->getId_prop());
		$arrayTabla=$this->mapa->objTOtabla($foto);
		$fotoDB=new FotoPGDAO($arrayTabla);
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

	public function borraFoto(){
		$conf=CargaConfiguracion::getInstance();
		$nombre=$conf->leeParametro('path_fotos')."/".$this->foto->getFoto();
		if(file_exists($nombre)){
			if(unlink($nombre)){
				$this->borraDB();
			}
		}
	}


	public function clonaFotosByPropiedad($propOrig=0,$propDest=0){
		$retorno=true;
		$fotoObj=new Foto();
		$fotoBSN= new FotoBSN();
		$datosDB = new FotoPGDAO();
		$conf = CargaConfiguracion::getInstance();
		$path = $conf->leeParametro('path_fotos');
		$pathC = $conf->leeParametro('path_fotos_chicas');
		$arrayFotos=$this->cargaColeccionFormByPropiedad($propOrig);
		foreach ($arrayFotos as $foto) {
			$fotoObj->setId_prop($propDest);
			$fotoObj->setPosicion($foto['posicion']);
			$fotoObj->setFoto($this->duplicaFoto($foto['hfoto'],$propDest,$path,$pathC));
			$fotoBSN->seteaBSN($fotoObj);
			$fotoBSN->insertaDB();
		}
		return $retorno;
	}


	// Utilizadas en la clonacion de imagenes y/o planos
	protected function duplicaFoto($nombre,$actual,$path='',$pathC=''){
		if($nombre!=''){
			$nombreMod=$this->renombraFoto($nombre,$actual);
			if($path!=''){
				copy($path."/".$nombre,$path."/".$nombreMod);
			}
			if($pathC!=''){
				copy($pathC."/".$nombre,$pathC."/".$nombreMod);
			}
		}else{
			$nombreMod=false;
		}
		return $nombreMod;
	}

	protected function renombraFoto($nomb,$actual){
		$posg=strpos($nomb,'_');
		$posg2=strpos($nomb,'_',$posg+1)+1;
		$nomMod =substr($nomb,0,$posg).'_'.$actual.'_'.substr($nomb,$posg2);
		return $nomMod;
	}

}

?>