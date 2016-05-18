<?php

/**
 * Administra el cache de informacion de clientes para agilizar la busqueda de los datos del mismo.
 * Utiliza patron singleton para asegurar un unico acceso a los datos
 * 
 * Estructura del array de CACHE
 * 
 * array(<id_cli>=>string(<apellido>|<nombre>|<telefonos>|<medios_electronicos>|<domicilios>)
 *      )
 */
include_once("clases/class.clienteBSN.php");
include_once("clases/class.medioselectronicosBSN.php");
include_once("clases/class.telefonosBSN.php");
include_once("clases/class.domicilioBSN.php");

set_time_limit(0); 

class ClienteCache {

    private $arrayClientes;
    private static $_instance;
    private static $cacheFilename = 'clienteCache.txt';
    private static $cachePath;
    private static $cacheState = -2;  // registra el estado del cache 0: En carga    1: Activo   -2: Descargado  -1: En creacion

    private function __construct() {
        self::setStateCreacion();
        self::setCachepath();
        self::cargaCacheClientes();
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

    public function getClientes() {
        return $this->estructuraArrayRetorno($this->arrayClientes);
    }

    public function getClientesByFiltro($filtro, $modo = 'a') {
        return $this->filtraContenido($filtro, $modo);
    }

    public function getClientesMailByFiltro($filtro, $modo = 'a') {
        return $this->filtraContenidoMail($filtro, $modo);
    }

    public function getClientesByFiltroApellido($filtro, $modo = 'a') {
        return $this->filtraContenidoApellido($filtro, $modo);
    }
    
    public function getClientesById($id, $modo = 'a') {
        return $this->filtraContenidoId($id, $modo);
    }

    protected function estructuraArrayRetorno($array) {
        $arrayRet = array();
        $arrayKey = array_keys($this->arrayClientes);
        for ($i = 0; $i < sizeof($arrayKey); $i++) {
            $arrayCli=split('\|',$this->arrayClientes[$arrayKey[$i]]);
            $arrayRet[$arrayKey[$i]] = $arrayCli;
        }
        return $arrayRet;
    }

    protected function filtraContenidoId($id, $modo) {
        $arrayRet = array();
//        if(array_key_exists($id, $this->arrayClientes)){
                switch ($modo) {
                    case 't':
                        $arrayRet[$id] = $this->arrayClientes[$id];
                        break;
                    case 'j':
                        $dato = str_replace('|', ' ', $this->arrayClientes[$id]);
                        $items[$id] = $dato;
                        break;
                    default :
                        $arrayRet[$id] = split('\|',$this->arrayClientes[$id]);
                }
//        }
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
    
    protected function filtraContenidoApellido($filtro, $modo) {
        $arrayRet = array();
        $arrayKey = array_keys($this->arrayClientes);
        for ($i = 0; $i < sizeof($arrayKey); $i++) {
            $arrayCli=split('\|',$this->arrayClientes[$arrayKey[$i]]);
            $apel1=  substr(strtolower(trim($arrayCli[0])),0,1);
//            $apel1=  substr(strtolower(trim($arrayCli[1])),0,1);
            $fil1=substr(strtolower($filtro),0,1);
            $fil2=substr(strtolower($filtro),1,1);
//            if (substr($apell,0,1)== substr(strtolower($filtro),0,1) || substr($apell,0,1)== substr(strtolower($filtro),1,1)){ 
            if ($apel1==$fil1 || $apel1==$fil2){ 
                switch ($modo) {
                    case 't':
                        $arrayRet[$arrayKey[$i]] = $this->arrayClientes[$arrayKey[$i]];
                        break;
                    case 'j':
                        $dato = str_replace('|', ' ', $this->arrayClientes[$arrayKey[$i]]);
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
        $arrayKey = array_keys($this->arrayClientes);
        for ($i = 0; $i < sizeof($arrayKey); $i++) {
            if (strpos(strtolower($this->arrayClientes[$arrayKey[$i]]), strtolower($filtro)) !== false) {
                switch ($modo) {
                    case 't':
                        $arrayRet[$arrayKey[$i]] = $this->arrayClientes[$arrayKey[$i]];
                        break;
                    case 'j':
                        $dato = str_replace('|', ' ', $this->arrayClientes[$arrayKey[$i]]);
                        $items[$arrayKey[$i]] = $dato;
                        break;
                    default :
                        $arrayRet[$arrayKey[$i]] = split('\|', $this->arrayClientes[$arrayKey[$i]]);
                }
            }
        }
        // Arma el array de modelo JSON
        if ($modo == 'j' && (isset($items) && is_array($items))) {
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
    
        protected function filtraContenidoMail($filtro, $modo) {
        $arrayRet = array();
        $arrayKey = array_keys($this->arrayClientes);
        for ($i = 0; $i < sizeof($arrayKey); $i++) {
            if (strpos(strtolower($this->arrayClientes[$arrayKey[$i]]), strtolower($filtro)) !== false) {
                
                $arrayCli=split('\|',$this->arrayClientes[$arrayKey[$i]]);
                if($arrayCli[3] != ""){
                    switch ($modo) {
                        case 't':
                            $arrayRet[$arrayKey[$i]] = $this->arrayClientes[$arrayKey[$i]];
                            break;
                        case 'j':
                            $dato = $arrayCli[0].", ".$arrayCli[1] . $arrayCli[3];
                            $items[$arrayKey[$i]] = $dato;
                            break;
                        default :
                            $arrayRet[$arrayKey[$i]] = split('\|', $this->arrayClientes[$arrayKey[$i]]);
                    }
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
        return $retorno;
    }

    /**
     * Carga el cache desde un archivo o desde la base segun exista o no el primero
     */
    protected function cargaCacheClientes() {
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
        $cliBSN = new ClienteBSN();
        $arrayCli = $cliBSN->cargaColeccionForm();
        foreach ($arrayCli as $cliente) {
            $this->seteaRegistroCache($cliente['id_cli'], $cliente['nombre'], $cliente['apellido']);
        }
    }

    protected function grabaCachefile() {
        $file = $_SERVER['DOCUMENT_ROOT'] . self::$cachePath . self::$cacheFilename;
        if (file_exists($file)) {
            unlink($file);
        }
        asort($this->arrayClientes);
//        if (!file_put_contents($file, serialize(asort($this->arrayClientes)))) {
        if (!file_put_contents($file, serialize($this->arrayClientes))) {
            $bsn = new BSN();
            $bsn->registraLog(0, 'CacheCliente', 'Fallido', 'Fallo la grabacion a disco del Cache de Cliente');
        }
    }

    protected function leeCachefile() {
        $file = $_SERVER['DOCUMENT_ROOT'] . self::$cachePath . self::$cacheFilename;
        if (file_exists($file)) {
            $this->arrayClientes = unserialize(file_get_contents($file));
        } else {
            $this->cargaCacheClientes();
        }
    }

    /**
     * Regenera el CACHE
     */
    public function reseteo() {
        $this->cargaCacheClientes();
    }

    /**
     * Actualiza el registro en CACHE del cliente indicado por el ID
     * @param int $id_cli
     */
    public function actualizaCache($id_cli, $accion = 'a') {
        $cliBSN = new ClienteBSN($id_cli);
        switch ($accion) {
            case 'a':
                $this->seteaRegistroCache($cliBSN->getObjeto()->getId_cli(), $cliBSN->getObjeto()->getNombre(), $cliBSN->getObjeto()->getApellido());
                break;
            case 'd':
                unset($this->arrayClientes[$id_cli]);
                break;
        }
        $this->grabaCachefile();
    }

    protected function seteaRegistroCache($id_cli, $nombre, $apellido) {
        $medBSN = new MedioselectronicosBSN();
        $telBSN = new TelefonosBSN();
        $domBSN = new DomicilioBSN();

        $telefonos = $telBSN->listaTelefonosByCliente($id_cli);
        $medioselec = $medBSN->listaMedioselectronicosByCliente($id_cli);
        $domicilio = $domBSN->listaPrincipalByCliente($id_cli);

        $telefonos = str_replace("<strong>", "", $telefonos);
        $telefonos = str_replace("</strong>", "", $telefonos);
        $medioselec = str_replace("<strong>", "", $medioselec);
        $medioselec = str_replace("</strong>", "", $medioselec);

        $this->seteaColeccion($id_cli, $nombre, $apellido, $telefonos, $medioselec, $domicilio);
    }

    protected function seteaColeccion($id_cli, $nombre, $apellido, $telefono, $medios, $domicilio) {
        $this->arrayClientes[$id_cli] = (strtoupper($apellido) . "|" .strtoupper($nombre) . "|" .  $telefono . "|" . $medios . "|" . $domicilio);
    }

}

?>
