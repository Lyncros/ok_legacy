<?php
include_once("generic_class/class.cargaConfiguracion.php");

class Maper{

	private $mapa;
	private $clase;

	public function __construct($clase){
		Maper::seteaMapa($clase);
	}

	// Setea el mapa interno en funcion de la clase que se le paso como parametro al constructor
	protected function seteaMapa($clase){
		$conf=CargaConfiguracion::getInstance();
		$pathmapa=$conf->leeParametro("mapas");
		$archmapa=$pathmapa."/mapa".$clase.".php";
		include($archmapa);
		$this->setMapa($mapaBase);
		$this->clase=$clase;
	}

	public function setMapa($_mapa){
		$this->mapa=$_mapa;
	}

	public function getMapa(){
		echo $this->clase;
		return $this->mapa;
	}

	public function verMapa(){
		print_r($this->mapa);
	}


	// Arma el path y nombre de la clase base en funcion de la propiedad del objeto pasado
	protected function armaClase(){
		$conf=CargaConfiguracion::getInstance();
		$path=$conf->leeParametro("clases");
		$nombre=strtolower($this->clase);
		$arch=$path."/class.".$nombre.".php";
		return $arch;
	}

	// Arma el seter de la clase base en funcion de la propiedad del objeto pasado
	protected function armaSeter($propiedad){
		$nombre=strtoupper(substr($propiedad,0,1)).substr($propiedad,1);
		return "set".$nombre;
	}

	// Arma el geter de la clase base en funcion de la propiedad del objeto pasado
	protected function armaGeter($propiedad){
		$nombre=strtoupper(substr($propiedad,0,1)).substr($propiedad,1);
		return "get".$nombre;
	}

	/**
 * Transforma el array de POST o un array con claves de campo del form y sus valores en un objeto 
 * de la clase definida en eel constructor y sus valores segun campo - prop en el mapa.
 *
 * @param array $campos -> array formado como clave, valor ( campo, valor) del form
 * @return objeto de la clase definida con valores cargados
 */
	public function formTOobj($campos){
		include_once($this->armaClase());
		$objeto= new $this->clase();
		$cant=sizeof($campos);
		$camposForm=array_keys($campos);
		foreach($camposForm as $campo){
			$prop=$this->campoFormTOpropObj(strtolower($campo));
			if($prop!=""){
				$seter=$this->armaSeter($prop);
				$objeto->{$seter}($campos[$campo]);
			}
		}
		return $objeto;
	}

	/**
 * Transforma el objeto en un array con claves de campo del form y sus valores, tomando las propiedades
 * de la clase definida en el constructor y sus valores segun campo - prop en el mapa.
 *
 * @param objeto $objeto -> objeto de la clase definida con valores cargados en el constructor
 * @return array $campos -> array formado como clave, valor ( campo, valor) del form
 */
	public function objTOform($_objeto){
		$objeto = $_objeto;
		$camposForm=array();
		$this->seteaMapa($this->clase);
		foreach ($this->mapa as $prop){
			$geter=$this->armaGeter($prop['objeto']);
			$camposForm[$prop['form']]=$objeto->{$geter}();
		}
		return $camposForm;
	}

	/**
 * Transforma el array de la tabla o un array con claves de campo de la tabla y sus valores en un objeto 
 * de la clase definida en eel constructor y sus valores segun campo - prop en el mapa.
 *
 * @param array $campos -> array formado como clave, valor ( campo, valor) de la tabla
 * @return objeto de la clase definida con valores cargados
 */
	public function tablaTOobj($campos){
		include_once($this->armaClase());
		$objeto= new $this->clase();
		$cant=sizeof($campos);
		if($cant > 0){
			$camposTabla=array_keys($campos);
			foreach($camposTabla as $campo){
				$prop=$this->campoTablaTOpropObj($campo);
				if($prop!=""){
					$seter=$this->armaSeter($prop);
					$objeto->{$seter}($campos[$campo]);
				}
			}
		}

		return $objeto;
	}

	/**
 * Transforma el objeto en un array con claves de campo de la tabla y sus valores, tomando las propiedades
 * de la clase definida en el constructor y sus valores segun campo - prop en el mapa.
 *
 * @param objeto $objeto -> objeto de la clase definida con valores cargados en el constructor
 * @return array $campos -> array formado como clave, valor ( campo, valor) de la tabla
 */
	public function objTOtabla($_objeto){
		include_once($this->armaClase());
		$objeto = new $this->clase();
		$objeto = $_objeto;
		$camposTabla=array();
		foreach ($this->mapa as $prop){
			$geter=$this->armaGeter($prop['objeto']);
			$camposTabla[$prop['tabla']]=$objeto->{$geter}();
		}
		return $camposTabla;
	}

	/**
 * Transforma el array de la tabla o un array con claves de campo de la tabla y sus valores en un array 
 * del formulario definido en el constructor y sus valores segun campo - campo en el mapa.
 *
 * @param array $campos -> array formado como clave, valor ( campo, valor) de la tabla
 * @return array formado como clave, valor ( campo, valor) del formulario
 **/

	public function tablaTOform($campos){
		$camposTabla=array_keys($campos);
		$camposForm=array();
		foreach($camposTabla as $campo){
			$campof=$this->campoTablaTOcampoForm($campo);
			if($campof!=""){
				$camposForm[$campof]=$campos[$campo];
			}
		}
		return $camposForm;
	}

	/**
 * Transforma el array de POST o un array con claves de campo del form y sus valores en un objeto 
 * de la clase definida en eel constructor y sus valores segun campo - prop en el mapa.
 *
 * @param array $campos -> array formado como clave, valor ( campo, valor) del form
 * @return objeto de la clase definida con valores cargados
 */
	public function formTOtabla($campos){
		//		include_once($this->armaClase());
		//		$objeto= new $this->clase();
		$cant=sizeof($campos);
		$camposForm=array_keys($campos);
		$arrayTabla=array();
		foreach($camposForm as $campo){
			$campof=$this->campoFormTOcampoTabla($campo);
			if($campof!=""){
				$arrayTabla[$campof]=$campos[$campo];
			}
		}
		return $objeto;
	}


	/**
 * Retorna el nombre de la propiedad correspondiente al campo del form seg�n el mapa
 *
 * @param string $campo -> nombre del campo en el form
 * @return string nombre de la propiedad del objeto
 */
	protected function campoFormTOpropObj($campo){
		$propiedad="";
		$propiedad=$this->getDestino($campo,"f","o");
		return $propiedad;
	}

	/**
 * Retorna el nombre del campo del form correspondiente a la propiedad  seg�n el mapa
 *
 * @param string $propiedad -> nombre de la propiedad del objeto
 * @return string nombre del campo en el form
 */
	protected function proObjTOcampoForm($propiedad){
		$campo="";
		$campo=$this->getDestino($propiedad,"o","f");
		return $campo;
	}

	/**
 * Retorna el nombre del campo del form correspondiente al campo de la tabla seg�n el mapa
 *
 * @param string $campo -> nombre del campo en la tabla
 * @return string nombre del campo en el form
 */
	protected function campoTablaTOcampoForm($campo){
		$propiedad="";
		$propiedad=$this->getDestino($campo,"t","f");
		//		echo $campo." + ".$propiedad."----<br>";
		return $propiedad;
	}

	protected function campoFormTOcampoTabla($campo){
		$propiedad="";
		$propiedad=$this->getDestino($campo,"f","t");
		return $propiedad;
	}

	/**
 * Retorna el nombre de la propiedad correspondiente al campo de la tabla seg�n el mapa
 *
 * @param string $campo -> nombre del campo en la tabla
 * @return string nombre de la propiedad del objeto
 */
	protected function campoTablaTOpropObj($campo){
		$propiedad="";
		$propiedad=$this->getDestino($campo,"t","o");
		return $propiedad;
	}

	/**
 * Retorna el nombre del campo de la tabla correspondiente a la propiedad  seg�n el mapa
 *
 * @param string $propiedad -> nombre de la propiedad del objeto
 * @return string nombre del campo en la tabla
 */
	protected function propObjTOcampoTabla($propiedad){
		$campo="";
		$campo=$this->getDestino($propiedad,"o","t");
		return $campo;
	}

	/**
 * Retorna el nombre del campo o propiedad del objeto o tabla seg�n sea el nombre de orgen pasado y la identificacion
 * del origen y el destino indicados
 *
 * @param string $dato -> nombre del campo o propiedad
 * @param char $_origen -> origen del dato (form, objeto o tabla)
 * @param char $_destino -> destino  del dato (form, objeto o tabla)
 * @return string identificador del nombre de la propiedad o campo de destino
 */
	protected function getDestino($dato,$_origen,$_destino){
		$retorno="";
		$origen=$this->getKeyMapa($_origen);
		$destino=$this->getKeyMapa($_destino);
		$cant=sizeof($this->mapa);
		for($x=0; $x < $cant; $x++){
			if($this->mapa[$x][$origen]==$dato){
				$retorno = $this->mapa[$x][$destino];
				break;
			}
		}
		return $retorno;
	}

	/**
 * Retorna el nombre de la clave del array de mapeo para cada uno de los tipos
 *
 * @param char $_pos -> identificador de la key del array
 * @return string identificador de la key del array
 */
	protected function getKeyMapa($_pos){
		$retorno="";
		switch ($_pos) {
			case "f":
				$retorno="form";
				break;
			case "o":
				$retorno="objeto";
				break;
			case "t":
				$retorno="tabla";
				break;
			default:
				break;
		}
		return $retorno;
	}

}
?>