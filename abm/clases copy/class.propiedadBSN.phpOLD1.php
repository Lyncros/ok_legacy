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
include_once("clases/class.auxiliaresPGDAO.php");


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

    public function cargaColeccionFiltro($zona,$localidad,$tipo_prop,$operacion,$publicadas,$sucursal,$pagina=1) {
//		$localclass=$this->getClase().'PGDAO';
        $config= new CargaConfiguracion();
        $registros=$config->leeParametro('regprod_adm');
        $array=array();
        $prop=new Propiedad();
        $prop->setId_zona($zona);
        $prop->setId_loca($localidad);
        $prop->setId_tipo_prop($tipo_prop);
        $prop->setOperacion($operacion);
        $prop->setId_sucursal($sucursal);
        switch ($publicadas) {
            case 'Todas':
                $valor=-1;
                break;
            case 'Publicadas':
                $valor=1;
                break;
            default:
                $valor=0;
                break;
        }
        $prop->setActiva($valor);
        $arrayTabla=$this->mapa->objTOtabla($prop);
        $datoDB = new PropiedadPGDAO($arrayTabla);
        $array = $this->leeDBArray($datoDB->coleccionByFiltro($zona,$localidad,$tipo_prop,$operacion,$publicadas,$sucursal,$pagina,$registros));
        $arrayform=array();
        foreach ($array as $registro) {
            $arrayform[]=$this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    public function cargaColeccionFiltroBuscador($zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina=1,$campo='',$orden=0) {
//		$localclass=$this->getClase().'PGDAO';
        $config= new CargaConfiguracion();
        $registros=$config->leeParametro('regprod_adm');
        $array=array();
        $prop=new Propiedad();
        $prop->setId_zona($zona);
        $prop->setId_loca($localidad);
        $prop->setId_tipo_prop($tipo_prop);
        $prop->setOperacion($operacion);
        $prop->setId_emp($id_emp);
        $arrayTabla=$this->mapa->objTOtabla($prop);
        $datoDB = new PropiedadPGDAO($arrayTabla);
        $array = $this->leeDBArray($datoDB->coleccionByFiltroBuscador($zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina,$registros,$campo,$orden));
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
        return $retorno;
    }

    /**
     * Desactiva la evento para ser publicada
     *
     * @return estado de la finalizacion de la operacion
     */
    public function quitarPropiedad() {
        $retorno=false;
        $arrayTabla=$this->getArrayTabla();
        $propDB=new PropiedadPGDAO($arrayTabla);
        $retorno=$propDB->desactivar();
        return $retorno;
    }

    public function controlDuplicado() {
        $retorno=false;
        $localclass=$this->getClase().'PGDAO';
        $datoDB=new $localclass($this->getArrayTabla());
        $arrayDB=$this->leeDBArray($datoDB->findByClave());
        //print_r($arrayDB);
//		print sizeof($arrayDB)." - ".sizeof($arrayDB[0]);
        if(sizeof($arrayDB[0])>0) {
//			die(sizeof($arrayDB[0]));
            $retorno=true;
        }
//		$clase = $this->getClase()."BSN";
//		$objaux=$this->mapa->tablaTOobj($arrayDB[0]);
//		$obj=new $clase($objaux);
//		$retorno=$obj->getId();		
        return $retorno;
    }

    public function cantidadRegistrosFiltroBuscador($zona,$localidad,$tipo_prop,$operacion,$id_emp,$in) {
        $retorno=0;
        $propPGDAO=new PropiedadPGDAO();
        $reg = $propPGDAO->cantRegistrosFiltroBuscador($zona,$localidad,$tipo_prop,$operacion,$id_emp,$in);
        $array = $this->leeDBArray($reg);
        if(sizeof($array)==0) {
            $cant=0;
        }else {
            $cant=$array[0]['id_prop'];
        }
        return $cant;
    }

    public function cantidadRegistrosFiltro($aux_zona,$aux_loca,$aux_prop,$aux_operacion,$aux_publicadas,$aux_sucursal) {
        $retorno=0;
        switch ($aux_publicadas) {
            case 'Todas':
                $valor=-1;
                break;
            case 'Publicadas':
                $valor=1;
                break;
            default:
                $valor=0;
                break;
        }
        $propPGDAO=new PropiedadPGDAO();
        $reg = $propPGDAO->cantidadRegistrosFiltro($aux_zona,$aux_loca,$aux_prop,$aux_operacion,$valor,$aux_sucursal);
        $array = $this->leeDBArray($reg);
        if(sizeof($array)==0) {
            $cant=0;
        }else {
            $cant=$array[0]['id_prop'];
        }
        return $cant;
    }

}

?>