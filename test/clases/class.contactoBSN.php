<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");

include_once("clases/class.contacto.php");
include_once("clases/class.contactoPGDAO.php");
include_once("clases/class.fechas.php");

class ContactoBSN  extends BSN {

	protected $clase = "Contacto";
	protected $nombreId = "Id_cont";
	protected $contacto;
	protected $arrayTipoResponsable=array('Inscripto','No Inscripto','Excento','Monotributo');
	

	public function __construct($_contacto=0){
		ContactoBSN::seteaMapa();
		if ($_contacto  instanceof Contacto ){
			ContactoBSN::creaObjeto();
			ContactoBSN::seteaBSN($_contacto);
		} else {
			if (is_numeric($_contacto)){
				ContactoBSN::creaObjeto();
				if($_contacto!=0){
					ContactoBSN::cargaById($_contacto);
				}
			}
		}
	}


	public function getId(){
		return $this->contacto->getId_cont();
	}

	public function setId($id){
		$this->contacto->setId_cont($id);
	}

	public function cargaColeccionByRazon($razon){
		$contacto=new Contacto();
		$contacto->setRazon($razon);
		$contactoBSN= new ContactoBSN($contacto);
		$datoDB = new ContactoPGDAO($contactoBSN->getArrayTabla());
		$arrayDB=$this->leeDBArray($datoDB->findByRazon());
		return $arrayDB;
	}

	public function controlDuplicado(){
		$retorno=false;
		$datoDB=new ContactoPGDAO($this->getArrayTabla());
		$arrayDB=$this->leeDBArray($datoDB->findByClave());
		if(sizeof($arrayDB[0])>0){
			$retorno=true;
		}
		return $retorno;
	}

	/**
	 * Retorna un array con los datos de los contactos que pertenecen a un rubro determinado
	 * @param int $id_rubro -> identificador del rubro
	 * @return string[] -> array conteniendo los datos de la coleccion de contactos
	 */
	public function cargaColeccionByRubro($id_rubro=0){
		$contacto=new Contacto();
		$contacto->setId_rubro($id_rubro);
		$contactoBSN= new ContactoBSN($contacto);
		$datoDB = new ContactoPGDAO($contactoBSN->getArrayTabla());
		$arrayDB=$this->leeDBArray($datoDB->coleccionByRubro());
		return $arrayDB;
	}

	/**
	 * Retorna un array con los datos de los contactos que pertenecen a un rubro o poseen cierto texto en la razon o nombre
	 * @param string $texto -> texto a buscar dentro de la razon o del nombre
	 * @param int $id_rubro -> identificador del rubro
	 * @return string[] -> array conteniendo los datos de la coleccion de contactos
	 */
	public function cargaColeccionFiltro($texto='',$id_rubro=0){
		$datoDB = new ContactoPGDAO();
		$arrayDB=$this->leeDBArray($datoDB->coleccionFiltro($texto,$id_rubro));
		return $arrayDB;
	}
	
	
	public function comboContactoCarteles($valor='',$campo="id_cont",$class="campos_btn"){
		$perfil=$this->cargaColeccionByRubro(1);
		$this->armaOpcionesCombo($perfil,$valor,$campo,$class);
	}

	public function comboContactoInmobiliaria($valor='',$campo="id_cont",$class="campos_btn"){
		$perfil=$this->cargaColeccionByRubro(2);
		$this->armaOpcionesCombo($perfil,$valor,$campo,$class);
	}

	protected function armaOpcionesCombo($coleccion='',$valor='',$campo="id_cont",$class="campos_btn"){
		print "<select name='".$campo."' id='".$campo."' class='".$class."'>\n";
		print "<option value='0'";
		if ($valor==''){
			print " SELECTED ";
		}
		print ">Seleccione una opcion</option>\n";

		for ($pos=0;$pos<sizeof($coleccion);$pos++){
			print "<option value='".$coleccion[$pos]['id_cont']."'";
			if ($coleccion[$pos]['id_cont']==$valor){
				print " SELECTED ";
			}
			print ">".$coleccion[$pos]['razon']." - ".$coleccion[$pos]['nombre']." ".$coleccion[$pos]['apellido']."</option>\n";
		}
		print "</select>\n";

	}

	public function comboContactos($valor='',$campo="id_cont",$class="campos_btn"){
		$perfil=$this->cargaColeccionForm();
		$this->armaOpcionesCombo($perfil,$valor,$campo,$class);
		
	}

	public function comboContactosByRubro($valor='',$rubro=0,$campo="id_cont",$class="campos_btn"){
		$perfil=$this->cargaColeccionByRubro($rubro);
		$this->armaOpcionesCombo($perfil,$valor,$campo,$class);
		
	}
	
/*
 	public function comboTipoResponsable($valor='',$campo="tipo_responsable",$class="campos_btn"){
		$tipo=$this->arrayTipoResponsable;
		print "<select name='".$campo."' id='".$campo."' class='".$class."'>\n";
		print "<option value='0'";
		if ($valor==''){
			print " SELECTED ";
		}
		print ">Seleccione una opcion</option>\n";

		for ($pos=0;$pos<sizeof($tipo);$pos++){
			print "<option value='".$tipo[$pos]."'";
			if ($tipo[$pos]==$valor){
				print " SELECTED ";
			}
			print ">".$tipo[$pos]."</option>\n";
		}
		print "</select>\n";
	}
*/	
} // Fin clase

?>