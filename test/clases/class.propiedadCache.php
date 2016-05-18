<?php

/**
 * Administra el cache de informacion de propiedades para agilizar la busqueda de los datos del mismo.
 * Utiliza patron singleton para asegurar un unico acceso a los datos
 * 
 */
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.ubicacionpropiedadBSN.php");
include_once("clases/class.emprendimientoBSN.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.caracteristicasBuscadorPrincipal.php");
include_once("clases/class.caracteristicaBSN.php");

set_time_limit(0); 

class PropiedadCache {

    private $arrayPropiedad;
    private static $_instance;
    private static $cacheFilename = 'propiedadCache.txt';
    private static $cachePath;
    private static $cacheState = -2;  // registra el estado del cache 0: En carga    1: Activo   -2: Descargado  -1: En creacion

    private $nomUbica='ubicacion';  // indica el nombre de la columna que contiene el nombre de la ubicacion
    private $nomEmp='nomemp';       // indica el nombre de la columna que contiene el nombre del emprendimiento
    private $nomAutocomp='autocomp'; // indica el nombre de la columna que tiene el texto para el autocompletado
    
    // defino el array que especifica los campos que componen el autocompletar
    private $compoAutocomp=array('ubicacion','calle', 'nro', 'piso', 'dpto','nomedif','nomemp' ); 
    
    // defino el array que especifica los campos de la propiedad a incluir en el cache
    private $arrayOrdenProp = array('id_sucursal', 'operacion', 'activa', 'calle', 'nro', 'piso', 'dpto','nomedif', 'id_ubica', 'id_tipo_prop', 'subtipo_prop', 'id_emp',
        'goglat', 'goglong', 'publicaprecio', 'publicadir', 'destacado', 'oportunidad');
    
    private $arrayOrdenCarac = array(); // array que va a contener las caracteristicas a incluir en el cache

    
    private function __construct() {
        self::setStateCreacion();
        self::setCachepath();
        self::setArrayOrdenCarac();
        self::cargaCachePropiedades();
        
    }

    static public function getInstance() {
        if (is_null(self::$_instance) && self::$cacheState == (-2)) {
            self::$_instance = new self();
        }
        while (self::$cacheState != 1) {
            echo "En espera<br>";
            sleep(1); // retardo la respuesta 1 segundo a la espera que se recargue y quede disponible
        }
        return self::$_instance;
    }

    static private function setStateCarga() {
        self::$cacheState = 0;
    }

    static private function setStateCreacion() {
        self::$cacheState = -1;
    }

    static private function setStateActivo() {
        self::$cacheState = 1;
    }

    static private function setCachepath() {
        $conf = CargaConfiguracion::getInstance();
        self::$cachePath = $conf->leeParametro('path_cache') . "/";
    }

    // Arma un array con las caracteristicas que se encuentran en el buscador basico, definido por el XML caracteristicasBuscadorPrincipal 
    // y las compagina con las existentes en el buscador dinamico definido por el usuario
    private function setArrayOrdenCarac(){
        $carBusc=caracteristicasBuscadorPrincipalBSN::getInstance();
        $this->arrayOrdenCarac=$carBusc->getArrayParametros();
        $buscador=new CaracteristicaBSN();
        $arrayBusc=$buscador->coleccionBuscadorCompleto();
        foreach ($arrayBusc as $buscado){
            if(!array_key_exists($buscado['id_carac'],$this->arrayOrdenCarac)){
                $this->arrayOrdenCarac[$buscado['id_carac']]=$buscado['titulo'];
            }
        }
    }
    
    public function getPropiedades() {
        return $this->estructuraArrayRetorno($this->arrayPropiedad);
    }

    /**
     * Retorna un array con las propiedades que cumplen la condicion de filtrado por texto incluido dentro de los campos de la
     * columna de autocompletado. La misma se arma en base al array compoAutocomp
     * @param string $filtro -> texto con el dato a buscar
     * @param char $modo -> indicador del formato de retorno
     * @return string [][] con el fornmato especificado
     */
    public function getPropiedadesByFiltro($filtro, $modo = 'a') {
        return $this->filtraContenido($filtro, $modo);
    }

    //($aux_codigo, $aux_calle, $idsZonas, $aux_prop, $aux_operacion, $aux_emp, $propsIN, $pagina, $aux_campo, $aux_orden, $aux_vistaestado, $aux_vistazona);
    public function getPropiedadesByFiltroDefinido($campo, $filtro, $modo = 'a') {
        return $this->filtraContenidoDefinido($campo, $filtro, $modo);
    }

    public function getPropiedadesById($id, $modo = 'a') {
        return $this->filtraContenidoId($id, $modo);
    }

    protected function estructuraArrayRetorno($array) {
        return $array;
    }

    protected function filtraContenidoId($id, $modo) {
        $arrayRet = array();
        switch ($modo) {
            case 't':
                $arrayRet[$id] = implode(' ', $this->arrayPropiedad[$id]);
                break;
            case 'j':
                $dato = str_replace('|', ' ', $this->arrayPropiedad[$id][$this->nomAutocomp]);
                $items[$id] = $dato;
                break;
            default :
                $arrayRet[$id] = $this->arrayPropiedad[$id];
        }
        // Arma el array de modelo JSON
        if ($modo == 'j') {
            $result = array();
            foreach ($items as $key => $value) {
                array_push($result, array("id" => $key, "label" => $value, "value" => strip_tags($value)));
            }
            $retorno = $result;
        } else {
            $retorno = $arrayRet;
        }
        return $retorno;
    }

    // REVISAR hay que levantar los datos de las columnas segun el NRO DE CAMPO
    protected function filtraContenidoDefinido($campo, $filtro, $modo) {
        $arrayRet = array();
        $arrayKey = array_keys($this->arrayPropiedad);
        for ($i = 0; $i < sizeof($arrayKey); $i++) {
            $arrayCli = split('\|', $this->arrayPropiedad[$arrayKey[$i]]);
            $apel1 = substr(strtolower(trim($arrayCli[1])), 0, 1);
            $fil1 = substr(strtolower($filtro), 0, 1);
            $fil2 = substr(strtolower($filtro), 1, 1);
            if ($apel1 == $fil1 || $apel1 == $fil2) {
                switch ($modo) {
                    case 't':
                        $arrayRet[$arrayKey[$i]] = $this->arrayPropiedad[$arrayKey[$i]];
                        break;
                    case 'j':
                        $dato = str_replace('|', ' ', $this->arrayPropiedad[$arrayKey[$i]]);
                        $items[$arrayKey[$i]] = $dato;
                        break;
                    default :
                        $arrayRet[$arrayKey[$i]] = $arrayCli;
                }
            }
        }
        // Arma el array de modelo JSON
        if ($modo == 'j') {
            $result = array();
            foreach ($items as $key => $value) {
                array_push($result, array("id" => $key, "label" => $value, "value" => strip_tags($value)));
            }
            $retorno = $result;
        } else {
            $retorno = $arrayRet;
        }
//        print_r($retorno);
        return $retorno;
    }

    protected function filtraContenido($filtro, $modo) {
        $arrayRet = array();
        $arrayKey = array_keys($this->arrayPropiedad);
        for ($i = 0; $i < sizeof($arrayKey); $i++) {
            if (strpos(strtolower($this->arrayPropiedad[$arrayKey[$i]][$this->nomAutocomp]), strtolower($filtro)) !== false) {
                switch ($modo) {
                    case 't':
                        $arrayRet[$arrayKey[$i]] = implode(' ', $this->arrayPropiedad[$arrayKey[$i]]);
                        break;
                    case 'j':
                        $dato = str_replace('|', ' ', $this->arrayPropiedad[$arrayKey[$i]][$this->nomAutocomp]);
                        $items[$arrayKey[$i]] = $dato;
                        break;
                    default :
                        $arrayRet[$arrayKey[$i]] = $this->arrayPropiedad[$arrayKey[$i]];
                }
            }
        }
        // Arma el array de modelo JSON
        if ($modo == 'j') {
            $result = array();
            foreach ($items as $key => $value) {
                array_push($result, array("id" => $key, "label" => $key . " ". $value, "value" => $key . " ". strip_tags($value)));
            }
            $retorno = $result;
        } else {
            $retorno = $arrayRet;
        }
        return $retorno;
    }

    /**
     * Carga el cache desde un archivo o desde la base segun exista o no el primero
     */
    protected function cargaCachePropiedades() {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . self::$cachePath . self::$cacheFilename)) {
            $this->setStateCreacion();
            $this->armaNewCache();
            $this->grabaCachefile();
        } else {
            $this->leeCachefile();
        }
        $this->setStateActivo();
    }

    /**
     * Carga el cache desde la clase.
     */
    protected function armaNewCache() {
        $objBSN = new PropiedadBSN();
        $arrayObj = $objBSN->cargaColeccionForm();
        foreach ($arrayObj as $objeto) {
            $this->seteaRegistroCache($objeto);
        }
    }

    protected function grabaCachefile() {
        $file = $_SERVER['DOCUMENT_ROOT'] . self::$cachePath . self::$cacheFilename;
        if (file_exists($file)) {
            unlink($file);
        }
        if (!file_put_contents($file, serialize($this->arrayPropiedad))) {
            $bsn = new BSN();
            $bsn->registraLog(0, 'CachePropiedad', 'Fallido', 'Fallo la grabacion a disco del Cache de Propiedades');
        }
    }

    protected function leeCachefile() {
        $file = $_SERVER['DOCUMENT_ROOT'] . self::$cachePath . self::$cacheFilename;
        if (file_exists($file)) {
            $this->arrayPropiedad = unserialize(file_get_contents($file));
        } else {
            $this->cargaCachePropiedades();
        }
    }

    /**
     * Regenera el CACHE
     */
    public function reseteo() {
        $this->cargaCachePropiedades();
    }

    /**
     * Actualiza el registro en CACHE de la propiedad indicado por el ID
     * @param int $id_prop
     */
    public function actualizaCache($id_prop, $accion = 'a') {
        $objBSN = new PropiedadBSN($id_prop);
        switch ($accion) {
            case 'a':
                $this->seteaRegistroCache($objBSN->getObjetoView());
                break;
            case 'd':
                unset($this->arrayPropiedad[$id_prop]);
                break;
        }
        $this->grabaCachefile();
    }

    protected function seteaRegistroCache($objeto) {
        $id_prop = $objeto['id_prop'];

        $ubiBSN=new UbicacionpropiedadBSN();
        $strUbi=$ubiBSN->armaNombreZonaAbr($objeto['id_ubica']);
        
        $empBSN=new EmprendimientoBSN($objeto['id_emp']);
        $nomEmp=$empBSN->getObjeto()->getNombre();
        $datoBSN=new DatospropBSN();
        $carac=$datoBSN->coleccionCaracteristicasProp($id_prop, 0);
        $this->seteaColeccion($objeto,$strUbi,$nomEmp, $carac);
    }

    protected function seteaColeccion($objeto,$ubicacion,$nomEmp, $accesorios) {
        $strBusc = $ubicacion."|";
        $clave = $objeto['id_prop'];

        $arrayP=array();
        foreach ($this->arrayOrdenProp as $campo) {
            $arrayP[$campo]=$objeto[$campo];
            if(array_search($campo, $this->compoAutocomp)!==false){
                $strBusc.=($objeto[$campo].'|');
            }
        }
        
        $strBusc.=$nomEmp;
        
        $arrayP[$this->nomUbica]=$ubicacion;
        $arrayP[$this->nomEmp]=$nomEmp;
        
        // Cargo las caracteristicas
        
        $clavesCarac=  array_keys($this->arrayOrdenCarac);
        foreach ($clavesCarac as $idcar){
            foreach ($accesorios as $carac){
                if($carac['id_carac']!=$idcar){
                    continue;
                }
                $arrayP[$idcar]=$carac['contenido'];
            }
        }
        $arrayP[$this->nomAutocomp]=$strBusc;
        $this->arrayPropiedad[$clave] = $arrayP;
    }

}

?>
