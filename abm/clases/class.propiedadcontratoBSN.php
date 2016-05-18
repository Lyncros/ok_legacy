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
 * 		$var='muestra'.$this->getClase();
 * 		$this->{$var}();
 * 	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once ("generic_class/class.cargaParametricos.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.propiedadcontrato.php");
include_once("clases/class.propiedadcontratoPGDAO.php");

class PropiedadcontratoBSN extends BSN {

    protected $clase = "Propiedadcontrato";
    protected $nombreId = "id_contrato";
    protected $propiedadcontrato;
    protected $arrayTipocontrato;

    public function __construct($_id_contrato=0) {
        PropiedadcontratoBSN::seteaMapa();
        if ($_id_contrato instanceof Propiedadcontrato) {
            PropiedadcontratoBSN::creaObjeto();
            PropiedadcontratoBSN::seteaBSN($_id_contrato);
        } else {
            if (is_numeric($_id_contrato)) {
                PropiedadcontratoBSN::creaObjeto();
                if ($_id_contrato != 0) {
                    PropiedadcontratoBSN::cargaById($_id_contrato);
                }
            }
        }
        PropiedadcontratoBSN::cargaTiposContratos();
    }

    /**
     * retorna el ID del objeto 
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->propiedadcontrato->getId_contrato();
    }

    /**
     * Setea el ID del objeto
     *
     * @param entero $id del objeto
     */
    public function setId($id) {
        $this->propiedadcontrato->setId_contrato($id);
    }

    /**
     * Retorna el array con los tipos de contratos 
     * @return string[] : arrayen el cual el ID de la fila contiene el ID del tipo de contato
     *  y el valor es la descripcion del mismo
     */
    public function getArrayTiposContrato(){
        return $this->arrayTipocontrato;
    }
    
    protected function cargaTiposContratos() {
        $params = new CargaParametricos('tiposContrato.xml');
        $this->arrayTipocontrato = $params->getParametros();
    }

    /**
     * Carga la coleccion de contratos activos en un array
     * @param string tipo -> tipo de contrato a buscar, si se omite se retornaran todos los tipos
     * @return string[][] -> array bidimensional conteniendo en cada fila los datos de cada contrato
     * definidos para esa propiedad
     */
    public function cargaPropiedadcontratoActivos($tipo='') {
        $operaux = new PropiedadcontratoBSN();
        $operDB = new PropiedadcontratoPGDAO($operaux->getArrayTabla());
        $coleccion=$this->leeDBArray($operDB->coleccionByTipoEstado($tipo, 'a'));
        return $coleccion;
    }

    /**
     * Carga la coleccion de contratos vencidos en un array
     * @param string tipo -> tipo de contrato a buscar
     * @return string[][] -> array bidimensional conteniendo en cada fila los datos de cada contrato
     * definidos para esa propiedad
     */
    public function cargaPropiedadcontratoVencidos($tipo='') {
        $operaux = new PropiedadcontratoBSN();
        $operDB = new PropiedadcontratoPGDAO($operaux->getArrayTabla());
        $coleccion=$this->leeDBArray($operDB->coleccionByTipoEstado($tipo, 'v'));
        return $coleccion;
    }
    
    /**
     * Arma un string con los ID de las propiedades con contratos activos o vencidos
     * @param string tipo -> tipo de contrato a buscar
     * @param string $estado  -> estado del contrato 'a': Activos    'v': Vencidos
     * @return string conteniendo la lista de ID separados por ,
     */
    public function armaListaIds($tipo='alquiler',$estado='a'){
        $strId='';
        $arrayDatos=array();
        $contDB= new PropiedadcontratoBSN();
        if($estado=='a'){
            $arrayDatos=$contDB->cargaPropiedadcontratoActivos($tipo);
        }else{
            $arrayDatos=$contDB->cargaPropiedadcontratoVencidos($tipo);
        }
        foreach ($arrayDatos as $registro) {
            $strId.=($registro['id_prop'].',');
        }
        if(strlen($strId)>0){
            $strId=substr($strId,0,-1);
        }
        return $strId;
    }
    
    /**
     * Carga la coleccion de contratosdefinidos para una propiedad en un array
     * @param int $_id  -> id de la propiedad
     * @return string[][] -> array bidimensional conteniendo en cada fila los datos de cada contrato
     * definidos para esa propiedad
     */
    public function cargaPropiedadcontratoByPropiedad($_id) {
        $operaux = new PropiedadcontratoBSN();
        $operaux->propiedadcontrato->setId_prop($_id);

        $operDB = new PropiedadcontratoPGDAO($operaux->getArrayTabla());
        $coleccion=$this->leeDBArray($operDB->coleccionByProp());
        return $coleccion;
    }

    
    /**
     * Carga la coleccion de contratosdefinidos para una propiedad en un array
     * @param int $_id  -> id de la propiedad
     * @return string[][] -> array bidimensional conteniendo en cada fila los datos de cada contrato
     * definidos para esa propiedad
     */
    public function cargaColeccionFormByPropiedad($_id) {
        $array = $this->cargaPropiedadcontratoByPropiedad($_id);
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    public function comboTipocontrato($valor='',$campo="tipo_contrato",$class="campos_btn"){
		$tipocont=$this->arrayTipocontrato;
                $clavecont=array_keys($tipocont);
		print "<select name='".$campo."' id='".$campo."' class='".$class."'>\n";
		print "<option value='0'";
		if ($valor==''){
			print " SELECTED ";
		}
		print ">Seleccione una opcion</option>\n";
		
		for ($pos=0;$pos<sizeof($tipocont);$pos++){
			print "<option value='".$clavecont[$pos]."'";
			if ($clavecont[$pos]==$valor){
				print " SELECTED ";
			}
			print ">".$tipocont[$clavecont[$pos]]."</option>\n";
		}
		print "</select>\n";
        
    }
}

?>