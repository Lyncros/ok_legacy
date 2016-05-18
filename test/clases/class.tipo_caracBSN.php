<?php
/**
 * Clase Propia para la definicion de la logica de negocios.
 * Utiliza dos variables propias de la clase que las hereda llamadas
 * 		"clase" que define la base del nombre, debe tener la Primer letra en Mayuscula y responder a la base de los nombres
 * 					de los metodos propios.
 * 		"objeto" que define el nombre del objeto tipo de la clase, cuyo nombre debe ser igual al de la 
 * 					clase pero todo en minuscula
 * 
 * En la clase derivada se deben definir metodos que ejecuten
 * 		getId		-> Retorna el Id de la clase
 * 		getClave	-> Retorna la Clave de la clase
 * 
 *
 * Ejemplo del uso del retorno de la clase para el armado de un metodo de la clase derivada. 
 * 	public function muestra(){
 *		$var='muestra'.$this->getClase();
 *		$this->{$var}();
 *	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.tipo_carac.php");
include_once("clases/class.tipo_caracPGDAO.php");


class Tipo_caracBSN extends BSN {
	
	protected $clase = "Tipo_carac";
	protected $nombreId = "id_tipo_carac";
	protected $tipo_carac;

	
	public function __construct($_id_tipo_carac=0,$_tipo_carac=''){
		Tipo_caracBSN::seteaMapa();
		if ($_id_tipo_carac  instanceof Tipo_carac ){
			Tipo_caracBSN::creaObjeto();
			Tipo_caracBSN::seteaBSN($_id_tipo_carac);
		} else {
			if (is_numeric($_id_tipo_carac)){
				Tipo_caracBSN::creaObjeto();
				if($_id_tipo_carac!=0){
					Tipo_caracBSN::cargaById($_id_tipo_carac);
				}
			}
		}	

	}
	
/**
 * retorna el ID del objeto 
 *
 * @return id del objeto
 */
	public function getId(){
		return $this->tipo_carac->getId_tipo_carac();
	}
	
/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->tipo_carac->setId_tipo_carac($id);
	}

	public function comboTipoCarac($valor=0,$campo="id_tipo_carac",$destino='',$class="cd_celda_input"){
		$tipoProp=$this->cargaColeccionForm();
		print "<select name='".$campo."' id='".$campo."' class='campos_btn' onchange=\"javascript: comboTipoCarac('".$campo."','".$destino."');\">\n";
		for ($pos=0;$pos<sizeof($tipoProp);$pos++){
			print "<option value='".$tipoProp[$pos]['id_tipo_carac']."'";
			if ($tipoProp[$pos]['id_tipo_carac']==$valor){
				print " SELECTED ";
			}
			print ">".$tipoProp[$pos]['tipo_carac']."</option>\n";
		}
		print "</select>\n";
	}

	public function subirTipoCarac(){
		$orden=$this->tipo_carac->getOrden();
		if($orden>1){
			$caracDB=new Tipo_caracPGDAO($this->getArrayTabla());
			$arrayOrden=$this->armaArrayOrden($orden-1);
			foreach ($arrayOrden as $id){
				$carac=new Tipo_carac($id);
				$arrayTabla=$this->mapa->objTOtabla($carac);
				$caracDB2=new Tipo_caracPGDAO($arrayTabla);
				
				$caracDB2->bajarTipoCarac();
			}
			$caracDB->subirTipoCarac($this->tipo_carac->getId_tipo_carac());
		}
	}
	
	public function bajarTipoCarac(){
		$orden=$this->tipo_carac->getOrden();
		$caracDB=new Tipo_caracPGDAO($this->getArrayTabla());
		$arrayOrden=$this->armaArrayOrden($orden+1);
		if(sizeof($arrayOrden)!=0){
			foreach ($arrayOrden as $id){
				$carac=new Tipo_carac($id);
				$arrayTabla=$this->mapa->objTOtabla($carac);
				$caracDB2=new Tipo_caracPGDAO($arrayTabla);
				$caracDB2->subirTipoCarac();
			}
			$caracDB->bajarTipoCarac($this->tipo_carac->getId_tipo_carac());
		}
	}	
	
	protected function armaArrayOrden($_orden){
		$array=array();
		$carac=new Tipo_carac();
		$carac->setOrden($_orden);
		$arrayTabla=$this->mapa->objTOtabla($carac);
		$caracDB=new Tipo_caracPGDAO($arrayTabla);
		$result=$caracDB->coleccionByPosicion();
		
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
				$array[]=$row["id_tipo_carac"];
			}
		}
		return $array;
	}		
	
}

?>