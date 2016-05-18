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
include_once("generic_class/class.maper.php");
include_once("clases/class.propiedad.php");
include_once("clases/class.propiedadPGDAO.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.auxiliaresPGDAO.php");
include_once("clases/class.perfilesBSN.php");
include_once ("clases/class.ubicacionpropiedadBSN.php");

class PropiedadBSN extends BSN {

    protected $clase = "Propiedad";
    protected $nombreId = "id_prop";
    protected $propiedad;

    public function __construct($_id_prop = 0, $_propiedad = '') {
        PropiedadBSN::seteaMapa();
        if ($_id_prop instanceof Propiedad) {
            PropiedadBSN::creaObjeto();
            PropiedadBSN::seteaBSN($_id_prop);
        } else {
            if (is_numeric($_id_prop)) {
                PropiedadBSN::creaObjeto();
                if ($_id_prop != 0) {
                    PropiedadBSN::cargaById($_id_prop);
                }
            }
        }
        PropiedadBSN::setTimezone();
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

    public function setOperacion($oper) {
        $this->propiedad->setOperacion($oper);
    }

    public function cargaColeccionFiltroBuscadorMapa($codigo, $calle, $id_ubica, $tipo_prop, $operacion, $id_emp, $in, $pagina = 1, $campo = '', $orden = 0, $aux_vistaestado = 0, $aux_vistazona = 0, $publicadas = -1) {
        $ubiBSN = new UbicacionpropiedadBSN();
        $config = CargaConfiguracion::getInstance();
        $registros = $config->leeParametro('regprod_adm');
        $perf = new PerfilesBSN();
        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

        $vistaestado = '';
        if ($operacion == '') {
            /*
              if($aux_vistaestado==1){
              $vistaestado="'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta','Tasacion'";
              }elseif ($aux_vistaestado==2){
              $vistaestado="'Suspendido','Retirado','Alquilado','Reservado','Vendido'";
              }
             */
            switch ($aux_vistaestado) {
                case 1:
                    $vistaestado = "";
                    break;
                case 2:
                    $vistaestado = "";
                    break;
                case 3:
                    $vistaestado = "'Alquiler','Alquiler Temporario','Alquiler o Venta'";
                    break;
                case 4:
                    $vistaestado = "'Alquilado'";
                    break;
                case 5:
                    $vistaestado = "'Alquilado'";
                    break;
                case 6:
                    $vistaestado = "'Venta','Alquiler o Venta'";
                    break;
                case 7:
                    $vistaestado = "'Vendido'";
                    break;
                case 11:
                    $vistaestado = "'Suspendido','Retirado'";
                    break;
                case 12:
                    $vistaestado = "'Reservado'";
                    break;
                default :
                    $vistaestado = '';
            }
        }

        $vistazona = '';
        if ($aux_vistazona != 0) {
            if ($perfSuc != 'Todas') {
                $vistazona = $perfSuc;
            }
        }
        $array = array();
        if ($id_ubica != 0 || $id_ubica != '') {
            $idsZonas = $id_ubica . ', ' . $ubiBSN->armaListaSeleciones($id_ubica, '');
        } else {
            $idsZonas = 0;
        }
        $prop = new Propiedad();
        $prop->setId_prop($codigo);
        $prop->setCalle($calle);
        $prop->setId_ubica($idsZonas); //$id_ubica);
        $prop->setId_tipo_prop($tipo_prop);
        $prop->setOperacion($operacion);
        $prop->setId_emp($id_emp);
        $arrayTabla = $this->mapa->objTOtabla($prop);
        $datoDB = new PropiedadPGDAO($arrayTabla);

        $array = $this->leeDBArray($datoDB->coleccionByFiltroBuscadorMapa($codigo, $calle, $idsZonas, $tipo_prop, $operacion, 
            $id_emp, $in, $pagina, $registros, $campo, $orden, $vistaestado, $vistazona, $publicadas));
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    /**
     * Carga una coleccion con las propiedades definidas como oportunidad
     * @param type $tipo_prop
     * @param type $operacion
     * @return type 
     */
    public function cargaColeccionOportunidad($tipo_prop, $operacion) {
        if ($operacion == '') {
            $operacion = "'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta','Tasacion'";
        }
        $datoDB = new PropiedadPGDAO();
        $array = $this->leeDBArray($datoDB->coleccionOportunidad($tipo_prop, $operacion));
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    /**
     * Carga una coleccion con las propiedades definidas como destacadas
     * @param type $tipo_prop
     * @param type $operacion
     * @return type 
     */
    public function cargaColeccionDestacado($tipo_prop, $operacion) {
        if ($operacion == '') {
            $operacion = "'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta','Tasacion'";
        }
        $datoDB = new PropiedadPGDAO();
        $array = $this->leeDBArray($datoDB->coleccionDestacado($tipo_prop, $operacion));
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    /**
     * Carga una coleccion con las propiedades definidas como oportunidad
     * @param type $operacion
     * @return type 
     */
    public function cargaColeccionNovedad($operacion, $publicadas=0) {
        $config = CargaConfiguracion::getInstance();
        $novedad = $config->leeParametro('novedad');

        if ($operacion == '') {
            $operacion = "'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta'";
        } else {
            if (strpos($operacion, "'") === false) {
                $operacion = "'" . $operacion . "'";
            }
        }
        $fecha = date("Y-m-d", strtotime(date('Y-m-d') . " -" . $novedad . " day"));
        $datoDB = new PropiedadPGDAO();
        if($publicadas == 0){
        	$array = $this->leeDBArray($datoDB->coleccionNovedades($fecha, $operacion));
        }else{
        	$array = $this->leeDBArray($datoDB->coleccionNovedadesPublicadas($fecha, $operacion));
        }
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    public function cargaSeleccionCRM($in) {
        $array = array();
        $datoDB = new PropiedadPGDAO();
        $array = $this->leeDBArray($datoDB->coleccionByFiltroBuscadorMapa(0, '', 0, 0, 0, '', 0, $in));
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    public function publicarPropiedad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->activar();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Publica Web', 'Habilitado');
        return $retorno;
    }

    /**
     * Desactiva la propiedad para ser publicada
     *
     * @return estado de la finalizacion de la operacion
     */
    public function quitarPropiedad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->desactivar();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Publica Web', 'Retirado');
        return $retorno;
    }

    public function publicarPrecioPropiedad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->publicarPrecio();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Publica Precio Web', 'Habilitado');
        return $retorno;
    }

    /**
     * Desactiva la publicacion del precio en la web
     *
     * @return estado de la finalizacion de la operacion
     */
    public function quitarPrecioPropiedad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->despublicarPrecio();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Publica Precio Web', 'Retirado');
        return $retorno;
    }

    /**
     * Marca una propiedad como destacada
     */
    public function destacaPropiedad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->destacaPropiedad();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Destaca Propiedad', 'Habilitado');
        return $retorno;
    }

    /**
     * Marca una propiedad como normal (quita la marca de destacada)
     */
    public function normalizaPropiedad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->normalizaPropiedad();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Destaca Propiedad', 'Habilitado');
        return $retorno;
    }

    /**
     * Marca una propiedad como oportunidad
     */
    public function activaOportunidad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->activaOportunidad();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Destaca Propiedad', 'Habilitado');
        return $retorno;
    }

    /**
     * Marca una propiedad como normal (quita la marca de destacada)
     */
    public function desactivaOportunidad() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new PropiedadPGDAO($arrayTabla);
        $retorno = $propDB->desactivaOportunidad();
        $this->registraLog($this->getObjeto()->getId_prop(), 'Destaca Propiedad', 'Habilitado');
        return $retorno;
    }

    public function controlDuplicado() {
        $retorno = false;
        $localclass = $this->getClase() . 'PGDAO';
        $datoDB = new $localclass($this->getArrayTabla());
        $arrayDB = $this->leeDBArray($datoDB->findByClave());
        if (sizeof($arrayDB[0]) > 0) {
            $retorno = true;
        }
        return $retorno;
    }

    public function cantidadRegistrosFiltroBuscador($codigo, $calle, $id_ubica, $tipo_prop, $operacion, 
        $id_emp, $in, $publicadas=-1, $aux_vistaestado='') {
        
        $ubiBSN = new UbicacionpropiedadBSN();
        $retorno = 0;
        $propPGDAO = new PropiedadPGDAO();
        if ($operacion == '') {
            switch ($aux_vistaestado) {
                case 1:
                    $operacion = "";
                    break;
                case 2:
                    $operacion = "";
                    break;
                case 3:
                    $operacion = "'Alquiler','Alquiler Temporario','Alquiler o Venta'";
                    break;
                case 4:
                    $operacion = "'Alquilado'";
                    break;
                case 5:
                    $operacion = "'Alquilado'";
                    break;
                case 6:
                    $operacion = "'Venta','Alquiler o Venta'";
                    break;
                case 7:
                    $operacion = "'Vendido'";
                    break;
                case 11:
                    $operacion = "'Suspendido','Retirado'";
                    break;
                case 12:
                    $operacion = "'Reservado'";
                    break;
                default :
                    $operacion = "'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta'";
            }

//            $operacion = "'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta'";
        }
        if ($id_ubica != 0 || $id_ubica != '') {
            $idsZonas = $id_ubica . ', ' . $ubiBSN->armaListaSeleciones($id_ubica, '');
        } else {
            $idsZonas = 0;
        }

        $reg = $propPGDAO->cantRegistrosFiltroBuscador($codigo, $calle, $idsZonas, $tipo_prop, $operacion, $id_emp, $in,$publicadas);
        $array = $this->leeDBArray($reg);
        if (sizeof($array) == 0) {
            $cant = 0;
        } else {
            $cant = $array[0]['id_prop'];
        }
        return $cant;
    }

    /*
     *  Funcion para el filtrado de informacion de propiedades con el buscador Nuevo
     */

    public function cargaColeccionFiltroBuscadorAvanzado($arrayFiltro, $pagina = 1, $campo='',$orden=0) {
        $codigo = 0;
        $calle = '';
        $ubicacion = 0;
        $tipo_prop = 0;
        $operacion = '';
        $id_emp = 0;
        $aux_vistaestado = 1;
        $aux_vistazona = 0;
        //$campo = '';
        //$orden = 0;
        $in = '';
        $publicadas = 1;
        foreach ($arrayFiltro as $opcion) {
            switch ($opcion[0]) {
                case 'opcTipoOper':
                    $operacion = "'$opcion[1]'";
                    break;
                case 'opcTipoProp':
                    $tipo_prop = $opcion[1];
                    break;
                case 'opcSubtipoProp':
                    $id_carac = 161;
                    break;
                case 'opcUbica':
                    $ubicacion = $opcion[1];
                    break;
                case 'opcEmprendimiento':
                    $id_emp = $opcion[1];
                    break;
                default:
                    break;
            }
        }

        $datosBSN = new DatospropBSN();
        $in = $datosBSN->armaArrayINAvanzado($arrayFiltro);
        if ($in != -1) {
            $retorno = $this->cargaColeccionFiltroBuscadorMapa($codigo, $calle, $ubicacion, $tipo_prop, $operacion, $id_emp, $in, $pagina, $campo, $orden, $aux_vistaestado, $aux_vistazona, $publicadas);
        } else {
            $retorno = 0;
        }

        return $retorno;
    }

    public function cantRegistrosColeccionFiltroBuscadorAvanzado($arrayFiltro) {
        $codigo = 0;
        $calle = '';
        $ubicacion = 0;
        $tipo_prop = 0;
        $operacion = '';
        $id_emp = 0;
        $aux_vistaestado = 1;
        $aux_vistazona = 0;
        $campo = '';
        $orden = 0;
        $in = '';
        $publicadas = 1;
        foreach ($arrayFiltro as $opcion) {
            switch ($opcion[0]) {
                case 'opcTipoOper':
                    $operacion = "'$opcion[1]'";
                    break;
                case 'opcTipoProp':
                    $tipo_prop = $opcion[1];
                    break;
                case 'opcSubtipoProp':
                    $id_carac = 161;
                    break;
                case 'opcUbica':
                    $ubicacion = $opcion[1];
                    break;
                case 'opcEmprendimiento':
                    $id_emp = $opcion[1];
                    break;
                default:
                    break;
            }
        }
        $datosBSN = new DatospropBSN();
        $in = $datosBSN->armaArrayINAvanzado($arrayFiltro);
        if ($in != -1) {
            $retorno = $this->cantidadRegistrosFiltroBuscador($codigo, $calle, $ubicacion, $tipo_prop, $operacion, $id_emp, $in, $publicadas);
        } else {
            $retorno = 0;
        }

        return $retorno;
    }

    /**
     * Carga una coleccion con las zonas activas agrupadas por tipo de propiedad y operacion
     * @return string [][] -> conteniendo 
     */
    public function cargaColeccionZonasActivas($id_padre = 0) {
        $datoDB = new PropiedadPGDAO();
        $operacion = "'Venta','Alquiler','Alquiler Temporario','Alquiler o Venta'";
        $activas = -1;
        $id_ubicaciones = null;

        if ($id_padre) {
            $ubiDAO = new UbicacionpropiedadPGDAO();
            $dataUbicaciones = $this->leeDBArray($ubiDAO->coleccionHijosRecursiva($id_padre));
            $id_ubicaciones = array();
            foreach ($dataUbicaciones as $row) {
                $id_ubicaciones[] = $row['id_ubica'];
            }
            $id_ubicaciones = implode(', ', $id_ubicaciones);
        }
        
        $array = $this->leeDBArray($datoDB->coleccionZonasActivas($operacion, $activas, $id_ubicaciones));
        return $array;
    }

    
    public function buscaDescripcionPropiedad() {
        $desc = '';
        $ubicaBSN = new UbicacionpropiedadBSN($this->propiedad->getId_ubica());
        $desc = "[" . $this->propiedad->getId_prop() . "] " . $this->propiedad->getCalle() . " " . $this->propiedad->getNro() . "<br> (" . $ubicaBSN->armaNombreZona($this->propiedad->getId_ubica()) . ")";

        return $desc;
    }
    
    public function buscaDetallePropiedad() {
        $retorno = '';
        $idTipoprop = $this->propiedad->getId_tipo_prop();
        switch ($idTipoprop) {
            case 1:
                $tipoProp = "<img src='images/tipo_depto.png' border='0' title='Departamento' />";
                break;
            case 2:
                $tipoProp = "<img src='images/tipo_local.png' border='0' title='Local' />";
                break;
            case 3:
                $tipoProp = "<img src='images/tipo_ph.png' border='0' title='PH' />";
                break;
            case 6:
                $tipoProp = "<img src='images/tipo_terreno.png' border='0' title='Terreno' />";
                break;
            case 7:
                $tipoProp = "<img src='images/tipo_terreno.png' border='0' title='Lotes' />";
                break;
            case 9:
                $tipoProp = "<img src='images/tipo_casa.png' border='0' title='Casa' />";
                break;
            case 11:
                $tipoProp = "<img src='images/tipo_oficina.png' border='0' title='Oficina' />";
                break;
            case 15:
                $tipoProp = "<img src='images/tipo_galpon.png' border='0' title='Galp&oacute;n' />";
                break;
        }


        $oper = $this->propiedad->getOperacion();
        $retorno = $tipoProp . ' - ' . $oper;
        return $retorno;
    }
  
    /**
     * Arma un combo con los valores de las propiedades
     * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
     * @param str $nombre -> nombre del campo en el formulario
     * @param str $clase -> clase de la css con la cual se presenta el dato
     * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion 
     */
    public function comboColeccion($valor = 0, $nombre = 'id_prop', $clase = 'campos_btn', $opcion = 0) {
        $arrayDatos = $this->cargaArrayCombo();
        $this->armaCombo($arrayDatos, $valor, $nombre, $clase, $opcion);
    }

    protected function cargaArrayCombo() {
        $objDB = new PropiedadPGDAO($this->getArrayTabla());
        $arrayRet = $this->leeDBArray($objDB->coleccionCombo());
        return $arrayRet;
    }

    
}

?>