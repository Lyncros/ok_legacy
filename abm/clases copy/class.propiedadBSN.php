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
include_once("clases/class.propiedad.php");
include_once("clases/class.propiedadPGDAO.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.auxiliaresPGDAO.php");
include_once("clases/class.perfilesBSN.php");

class PropiedadBSN extends BSN {

	protected $clase = "Propiedad";
	protected $nombreId = "id_prop";
	protected $propiedad;


	public function __construct($_id_prop=0,$_propiedad='') {
		PropiedadBSN::seteaMapa();
		if ($_id_prop  instanceof Propiedad ) {
			PropiedadBSN::creaObjeto();
			PropiedadBSN::seteaBSN($_id_prop);
		} else {
			if (is_numeric($_id_prop)) {
				PropiedadBSN::creaObjeto();
				if($_id_prop!=0) {
					PropiedadBSN::cargaById($_id_prop);
				}
			}
		}

	}

	/**
	 * retorna el ID del objeto
	 *
	 * @return id del objeto
	 */
	public function getId() {
		return $this->propiedad->getId_prop();
	}

	/**
	 * Setea e ID del objeto
	 *
	 * @param unknown_type $id
	 */
	public function setId($id) {
		$this->propiedad->setId_prop($id);
	}

	public function setOperacion($oper){
		$this->propiedad->setOperacion($oper)	;
	}


	public function cargaColeccionFiltroBuscadorMapa($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina=1,$campo='',$orden=0,$aux_vistaestado=0,$aux_vistazona=0,$publicadas=-1) {
		$config= CargaConfiguracion::getInstance();
		$registros=$config->leeParametro('regprod_adm');
		$perf = new PerfilesBSN();
		$perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
		$perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

		$vistaestado='';
		if($operacion==''){
			if($aux_vistaestado==1){
				$vistaestado="'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta','Tasacion'";
			}elseif ($aux_vistaestado==2){
				$vistaestado="'Suspendido','Retirado','Alquilado','Reservado','Vendido'";
			}
		}

		$vistazona='';
		if($aux_vistazona!=0){
			if ($perfSuc != 'Todas') {
				$vistazona=$perfSuc;
			}
		}
		$array=array();
		$prop=new Propiedad();
		$prop->setId_prop($codigo);
		$prop->setCalle($calle);
		$prop->setId_zona($zona);
		$prop->setId_loca($localidad);
		$prop->setId_tipo_prop($tipo_prop);
		$prop->setOperacion($operacion);
		$prop->setId_emp($id_emp);
		$arrayTabla=$this->mapa->objTOtabla($prop);
		$datoDB = new PropiedadPGDAO($arrayTabla);
		$array = $this->leeDBArray($datoDB->coleccionByFiltroBuscadorMapa($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina,$registros,$campo,$orden,$vistaestado,$vistazona,$publicadas));
		$arrayform=array();
		foreach ($array as $registro) {
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}

	public function cargaSeleccionCRM($in) {
		$array=array();
		$datoDB = new PropiedadPGDAO();
		$array = $this->leeDBArray($datoDB->coleccionByFiltroBuscadorMapa(0,'',0,0,0,'',0,$in));
		$arrayform=array();
		foreach ($array as $registro) {
			$arrayform[]=$this->mapa->tablaTOform($registro);
		}
		return $arrayform;
	}

	public function publicarPropiedad() {
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new PropiedadPGDAO($arrayTabla);
		$retorno=$propDB->activar();
		$this->registrarLog($this->getObjeto()->getId_prop(),'Publica Web','Habilitado');
		return $retorno;
	}

	/**
	 * Desactiva la propiedad para ser publicada
	 *
	 * @return estado de la finalizacion de la operacion
	 */
	public function quitarPropiedad() {
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new PropiedadPGDAO($arrayTabla);
		$retorno=$propDB->desactivar();
		$this->registrarLog($this->getObjeto()->getId_prop(),'Publica Web','Retirado');
		return $retorno;
	}

	public function publicarPrecioPropiedad() {
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new PropiedadPGDAO($arrayTabla);
		$retorno=$propDB->publicarPrecio();
		$this->registrarLog($this->getObjeto()->getId_prop(),'Publica Precio Web','Habilitado');
		return $retorno;
	}

	/**
	 * Desactiva la publicacion del precio en la web
	 *
	 * @return estado de la finalizacion de la operacion
	 */
	public function quitarPrecioPropiedad() {
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new PropiedadPGDAO($arrayTabla);
		$retorno=$propDB->despublicarPrecio();
		$this->registrarLog($this->getObjeto()->getId_prop(),'Publica Precio Web','Retirado');
		return $retorno;
	}



	public function controlDuplicado() {
		$retorno=false;
		$localclass=$this->getClase().'PGDAO';
		$datoDB=new $localclass($this->getArrayTabla());
		$arrayDB=$this->leeDBArray($datoDB->findByClave());
		if(sizeof($arrayDB[0])>0) {
			$retorno=true;
		}
		return $retorno;
	}

	public function cantidadRegistrosFiltroBuscador($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in) {
		$retorno=0;
		$propPGDAO=new PropiedadPGDAO();
		$reg = $propPGDAO->cantRegistrosFiltroBuscador($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in);
		$array = $this->leeDBArray($reg);
		if(sizeof($array)==0) {
			$cant=0;
		}else {
			$cant=$array[0]['id_prop'];
		}
		return $cant;
	}


	/*
	 *  Funcion para el filtrado de informacion de propiedades con el buscador Nuevo
	 */
	public function cargaColeccionFiltroBuscadorAvanzado($arrayFiltro,$pagina=1){
		$codigo=0;
		$calle='';
		$zona=0;
		$localidad=0;
		$tipo_prop=0;
		$operacion='';
		$id_emp=0;
		$aux_vistaestado=1;
		$aux_vistazona=0;
		$campo='';
		$orden=0;
		$in='';
		$publicadas=1;
		foreach ($arrayFiltro as $opcion){
			switch ($opcion[0]) {
				case 'opcTipoOper':
					$operacion="'$opcion[1]'";
					break;
				case 'opcTipoProp':
					$tipo_prop=$opcion[1];
					break;
				case 'opcSubtipoProp':
					$id_carac=161;
					break;
				case 'opcZona':
					$zona=$opcion[1];
					break;
				case 'opcLocalidad':
					$localidad=$opcion[1];
					break;
				default:
					break;
			}
		}


		$datosBSN=new DatospropBSN();
		$in=$datosBSN->armaArrayINAvanzado($arrayFiltro);
		if($in!=-1){
			$retorno=$this->cargaColeccionFiltroBuscadorMapa($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina,$campo,$orden,$aux_vistaestado,$aux_vistazona,$publicadas);
		}else{
			$retorno=0;
		}

		return $retorno;
	}


	public function cantRegistrosColeccionFiltroBuscadorAvanzado($arrayFiltro){
		$codigo=0;
		$calle='';
		$zona=0;
		$localidad=0;
		$tipo_prop=0;
		$operacion='';
		$id_emp=0;
		$aux_vistaestado=1;
		$aux_vistazona=0;
		$campo='';
		$orden=0;
		$in='';
		$publicadas=1;
		foreach ($arrayFiltro as $opcion){
			switch ($opcion[0]) {
				case 'opcTipoOper':
					$operacion="'$opcion[1]'";
					break;
				case 'opcTipoProp':
					$tipo_prop=$opcion[1];
					break;
				case 'opcSubtipoProp':
					$id_carac=161;
					break;
				case 'opcZona':
					$zona=$opcion[1];
					break;
				case 'opcLocalidad':
					$localidad=$opcion[1];
					break;
				default:
					break;
			}
		}
		$datosBSN=new DatospropBSN();
		$in=$datosBSN->armaArrayINAvanzado($arrayFiltro);
		if($in!=-1){
			$retorno=$this->cantidadRegistrosFiltroBuscador($codigo,$calle,$zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$publicadas);
		}else{
			$retorno=0;
		}

		return $retorno;
	}

}

?>