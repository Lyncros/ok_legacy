<?php

include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("clases/class.caracteristicaBSN.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.loginwebuserBSN.php");
include_once("clases/class.fotoBSN.php");
include_once("clases/class.telefonosBSN.php");
include_once("generic_class/class.cargaParametricos.php");

/**
 * Claseo para mapeo de tipos de propiedades y caracteristicas.
 *
 * @author edgardo
 */
class CucicbaBSN extends BSN {

    private $PORT = 80;
    private $arrayCuTprop;
    private $arrayCuCarac;
    private $arrayMapeoTprop;
    private $arrayMapeoCarac;
    private $propiedad;
    private $responsable;
    private $arrayCaracteristicas;
    private $arrayClavesCarac;
    private $arrayXml;
    private $arrayTel;
    private $arrayUbica;
    private $arrayContacto;
    private $arrayFotos;
    private $arrayErrores;
    private $arrayObligatorios;
    private $cuTprop;
    private $usuarioZp;
    private $claveZp;
    private $provZp;
    protected $COLECCIONCUTPROP = "SELECT * FROM #dbName#.cutipoprop;";
    protected $COLECCIONCUCARAC = "SELECT * FROM #dbName#.cucaracteristicas;";
    protected $COLECCIONCUCARACOBLIG = "SELECT c.id_cucarac FROM #dbName#.cucaracteristicas as c inner join #dbName#.mapeocucarac as m  on c.id_cucarac=m.id_cucarac where obligatorio='si'";
    protected $COLECCIONMAPEOTPROP = "SELECT * FROM #dbName#.mapeocutipoprop ";
    protected $COLECCIONMAPEOCARAC = "SELECT distinct m.id_cucarac,objeto,m.valor as valorc ,tipoprop,atributos,c.valor as valorz,obligatorio from #dbName#.cucaracteristicas as c inner join #dbName#.mapeocucarac as m on c.id_cucarac=m.id_cucarac ";
    protected $COLECCIONMAPEOUBICA = "SELECT cuubica from #dbName#.mapeocuubicacion where id_ubica=";
    protected $DATOSUBICAZP = "SELECT * FROM #dbName#.cuubicacion ";
    // Indicadores de ID de caracteristicas generales en el mapeo, corresponden a los id_cucarac de ZP
    private $TIPOPER = 28;
    private $APELLIDO = 2;
    private $EMAIL = 3;
    private $NOMBRE = 5;
//    private $CODAREA1 = 6;
//    private $NROTEL1 = 10;
//    private $HORARIOCONT = 4;
    private $DESCRIPCION = 12;
    private $SUBTITULO = 24;
    private $TITULO = 257;
    private $TIPOPROP = 29;
    private $ALTURA = 30;
    private $IDUBICA = 36;
    private $CALLE = 37;
    private $HECTAREAS = 45;
// Definicion de parametros de caracteristicas de monedas de venta y alquiler
    private $MONEDAUSD = 26;
    private $MONEDAAR = 25;
    private $PRECIO = 22;
    private $CAR_VALALQ = 164;
    private $CAR_VALVTA = 161;
    private $CAR_MONALQ = 166;
    private $CAR_MONVTA = 165;
    private $arrayMails = array();

    private $urlprop="http://www.okeefe.com.ar/detalle_prop.php?id=";
    
//    public function __construct($id_prop = 0, $id_resp = 0) {
    public function __construct() {
//        if ($id_prop != 0) {
//            $this->seteaArraysCU();
//            $this->seteaArrayMails();
//            $this->seteaPropiedad($id_prop);
//            $this->seteaResponsable($id_resp);
//            $this->seteaCaracteristicas($id_prop);
//            $this->seteaFotos($id_prop);
//            $this->seteaArraysMapeo();
//            $this->seteoTelefono();
//            $this->seteoContacto();
//            $this->seteaProveedor();
//        } else {
//            echo "No posee permisos para publicar una propiedad, u operarar con Cucicba";
//        }
    }

    protected function seteaArrayMails() {
        $this->arrayMails['okeefe@okeefe.com.ar'] = array('okeefe@okeefe.com.ar', 'okeefe@okeefe.com.ar');
    }

    protected function armaArrayPublicacion($id_prop,$id_resp){
            $this->seteaArraysCU();
            $this->seteaArrayMails();
            $this->seteaPropiedad($id_prop);
            $this->seteaResponsable($id_resp);
            $this->seteaCaracteristicas($id_prop);
            $this->seteaFotos($id_prop);
            $this->seteaArraysMapeo();
            $this->seteoTelefono();
    }
    
    public function publicaPropiedad($id_user = 0, $clave = '', $id_prop=0,$id_resp=0) {
        if ($this->validUser($id_user, $clave)) {
            $this->armaArrayPublicacion($id_prop, $id_resp);
            $retVal = $this->validaObligatorios();
            if ($retVal === true) {
                $ret=$this->registraPublicacion($id_prop, $id_user,$id_resp);
                if($ret){
                    echo "Se registro la solicitud de publicación en Cucicba";
                }else{
                    echo "Fallo el registro de la solicitud de publicación en Cucicba";
                }
            }else{
                    echo "Fallo el registro de la solicitud de publicación en Cucicba; faltan definir los valores para las siguientes caracteristicas<br>";
                    foreach ($retVal as $faltantes){
                        echo $faltantes."<br>";
                    }
                        
            }
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con Cucicba";
        }
    }

    
    public function retiraPropiedad($id_user = 0, $clave = '', $id_prop=0) {
        if ($this->validUser($id_user, $clave)) {
            $ret=$this->registraRetiro($id_prop);
            if($ret){
                echo "Se registro la solicitud de RETIRO de publicación en Cucicba";
            }else{
                echo "Fallo el registro de la solicitud de RETIRO de publicación en Cucicba";
            }
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con Cucicba";
        }
    }
    
    public function armoPublicacion($id_user) {
        $conf = CargaConfiguracion::getInstance();
        $path = $conf->leeParametro('path_cucicba');
        $xmlFile=$path."cucicba.xml";
        unlink($xmlFile);
        $this->generarArchivo($xmlFile, $this->seteoXmlHead());
        $arrayPublicados = $this->coleccionRegistros(1);
        foreach ($arrayPublicados as $registro) {
            
            $this->armaArrayPublicacion($registro['id_prop'],$registro['id_resp']);
            $this->generarArchivo($xmlFile, $this->armoXmlPublicarCU($registro['cuenta']));
        }
        $this->generarArchivo($xmlFile, $this->seteoXmlPie());
        return "Listo";
    }

    protected function generarArchivo($file,$data){
        return file_put_contents($file, $data,FILE_APPEND);
    }
    
// Armado del XML     

    protected function armoXmlPublicarCU($id_user) {
        $xml = '<aviso>';
        $arrayXml = $this->mapeoDatos($id_user);
        if (true) {
            foreach ($arrayXml as $datos) {
                $xml.=$datos;
            }
            $xml.='</aviso>';
            return $xml;
        } else {
            echo "<div id='errorXml'>";
            echo "<strong>Existen elementos faltantes para la publicacion en Cucicba</strong><br>";
            foreach ($retVal as $valor) {
                echo "$valor<br>";
            }
            echo "</div>";
            return false;
        }
    }

    protected function seteoTelefono() {
        if (is_object($this->responsable)) {
            $telBSN = new TelefonosBSN();
            $tel = $telBSN->principalByUsuarios($this->responsable->getId_user());
            if ($tel['codarea'] != '' && $tel['codarea'] != 0) {
                $this->arrayTel[1][] = "<codigoArea>" . $tel['codarea'] . "</codigoArea>";
//                $this->arrayObligatorios[$this->CODAREA1] = 1;
            }
            if ($tel['numero'] != '' && $tel['numero'] != 0) {
                $this->arrayTel[1][] = "<numeroTelefono>" . $tel['numero'] . "</numeroTelefono>";  // Poner TE de la empresa
//                $this->arrayObligatorios[$this->NROTEL1] = 1;
            }
            if ($tel['interno'] != '') {
                $this->arrayTel[1][] = "<extension>" . $tel['interno'] . "</extension>";
            }
        }
    }

    protected function mapeoContacto($id_user) { // Completar con datos de la empresa
        $arrayCont = $this->armaArrayContactos($id_user);
//        $xml = "<email_inmobiliaria>" . $this->armaCData($arrayCont[0]) . "</email_inmobiliaria>";
        $xml = "<email_inmobiliaria>inmobiliaria@okeefe.com.ar</email_inmobiliaria>";
        $xml.="<email_sucursal>" . $this->armaCData($id_user) . "</email_sucursal>";
        $xml.="<email_broker>" . $this->armaCData($this->responsable->getEmail()) . "</email_broker>";
        $this->arrayObligatorios[$this->EMAIL] = 1;
        return $xml;
    }

    protected function mapeoTitulo() {
        $xml = "<titulo>" . $this->armaCData($this->arrayCaracteristicas[$this->arrayClavesCarac[257]]['contenido']) . "</titulo>";
        return $xml;
    }

    protected function seteoXmlHead() {
        $headXml = '<?xml version="1.0" encoding="UTF-8"?>';
        $headXml.='<avisos> ';
        return $headXml;
    }

    protected function seteoXmlPie() {
        $pieXml = "</avisos>  ";
        return $pieXml;
    }

// Valido que los campos obligatorios tengan informacion, en caso contrario
// muestro los campos que faltan

    protected function validaObligatorios() {
        $retorno = true;
        $arrayFallos = array();
        $arrayClaves = array_keys($this->arrayObligatorios);
        foreach ($arrayClaves as $cpoOblig) {
            $atrib = $this->buscoAtributo($cpoOblig);
            if(!isset($this->arrayCaracteristicas[$this->arrayClavesCarac[$atrib[0]]]['contenido']) || $this->arrayCaracteristicas[$this->arrayClavesCarac[$atrib[0]]]['contenido']=''){
                $arrayFallos[] = $atrib[1];
                $retorno = $arrayFallos;
            }
        }
        return $retorno;
    }

/*    
// Arma un Array con las listas qe existen en los obligatorios    
    protected function armaArrayClavesListas() {
        $arrayClaves = array_keys($this->arrayObligatorios);
        $arrayListas = array();
        $pos = 0;
        $cantC = sizeof($arrayClaves);
        foreach ($arrayClaves as $clave) {
            if ($clave != 0) {
                $atrib = $this->buscoAtributo($clave);
                $pos++;
                for ($x = $pos; $x < $cantC; $x++) {
                    if ($arrayClaves[$x] != 0) {
                        $atrAct = $this->buscoAtributo($arrayClaves[$x]);
                        if ($atrib == $atrAct && $atrib != '' && $atrib != 'especificaciones.adicional' && $atrib != 'especificaciones.servicios') {
                            $arrayListas[$clave][] = $arrayClaves[$x];
                            $arrayClaves[$x] = 0;
                        }
                    }
                }
            }
        }
        return $arrayListas;
    }
*/
    
/*    
// Controla si el valor esta duplicado y/o es una lista y existe un item seleccionado
// en ese caso, completa todos los elementos de la lista con 1
    protected function controlaValidezLista($arrayListas) {
        $arrayBase = array_keys($arrayListas);
        foreach ($arrayBase as $base) {
            $marca = 0;
            $arrayCont = array();
            $arrayCont[] = $base;
            foreach ($arrayListas[$base] as $item) {
                $arrayCont[] = $item;
            }
            foreach ($arrayCont as $indice) {
                if ($this->arrayObligatorios[$indice] == 1) {
                    foreach ($arrayCont as $ind1) {
                        $this->arrayObligatorios[$ind1] = 1;
                    }
                    $marca = 1;
                }
            }
            if ($marca == 0) {
                foreach ($arrayListas[$base] as $item) {
                    $this->arrayObligatorios[$item] = 1;
                }
            }
        }
    }
*/

// Retorna la descripcion del atributo basado en la clave del mismo  
    protected function buscoAtributo($claveZp) {
        $retorno = '';
        foreach ($this->arrayMapeoCarac as $mapeo) {
            if ($mapeo['id_cucarac'] == $claveZp) {
                $arrayPartes = explode('.', $mapeo['objeto']);
                if ($arrayPartes[0] == 'caracteristicas') {
                    if ($arrayPartes[1] != '') {
                        $carac = new CaracteristicaBSN($arrayPartes[1]);
                        $retorno = array($arrayPartes[1],$carac->getObjeto()->getTitulo());
                    } else {
                        $retorno = array(0,$this->textoAtributo($mapeo['atributos']));
                    }
                } else {
                    $retorno = array(0,$this->textoAtributo($mapeo['atributos']));
                }
                break;
            }
        }
        return $retorno;
    }

    protected function textoAtributo($atrib) {
        $arrayEsp = explode('.', $atrib);
        $texto = $arrayEsp[1];
        $partes = explode('_', $texto);
        $retorno = $partes[0] . " " . $partes[1];
        return $retorno;
    }

// AMADO DE MAPEO DE TIPO DE PROPIEDAD

    protected function mapeoTprop() {
        $clave = '';
        $tipo = $this->propiedad->getId_tipo_prop();
        $subtipo = $this->propiedad->getSubtipo_prop();
        // Country - Barrio Cerrado
        $tipoUbi = $this->tipoUbicacion();
        if ($tipoUbi == 'COUNTRY' || $tipoUbi == strtoupper('Barrio Cerrado')) {
            $clave = 'Countries y Barrios cerrados_';
            switch ($tipo) {
                case 9: // Casa
                    $clave.='Casas';
                    break;
                case 1: // Depto
                    $clave.='Departamentos';
                    break;
                case 7: // Lote
                    $clave.='Terrenos';
                    break;
            }
        } else {
            $finder = 0;
            foreach ($this->arrayMapeoTprop as $registro) {
                if (trim($registro['id_tipo_prop']) == (trim($tipo))) {
                    if (trim($registro['subtipo_prop']) == (trim($subtipo))) {
                        $clave = $registro['clave'];
                        $finder = 1;
                        break;
                    }
                }
            }
            if ($finder == 0) {
                switch ($tipo) {
                    case 9: // Casa
                        $clave.='Casas_Casa';
                        break;
                    case 1: // Depto
                        $clave.='Departamentos_Departamento';
                        break;
                    case 17: //Quinta
                        $clave.='Quintas';
                        break;
                    case 6: //Campos y chacras
                    case 16: //Campos y chacras
                        $clave.='Campos y chacras';
                        break;
                }
            }
        }
        return strtolower($clave);
    }

    protected function mapeoDatos($id_user) {
        $arrayRet = array();

        $arrayRet[] = $this->mapeoContacto($id_user);
        $arrayRet[] = $this->mapeoIdAviso();
        $arrayRet[] = $this->mapeoEstadoAviso();
        $arrayRet[] = $this->mapeoTitulo();
        $arrayRet[] = $this->mapeoURLProp();
        $arrayRet[] = $this->mapeoTipoProp();
        $arrayRet[] = $this->mapeoOperacion();
        $arrayRet[] = $this->mapeoUbicacion();

        $mapValProp = $this->mapeoValorProp();
        if ($mapValProp != '') {
            $arrayRet[] = $mapValProp;
        }
        $arrayRet[] = $this->mapeoDescripcion();

        foreach ($this->arrayMapeoCarac as $caracZp) {
            $arrayPartes = explode('.', $caracZp['objeto']);
            switch ($arrayPartes[0]) {
                case 'caracteristicas':
                    $retorno = $this->mapeoCarac($caracZp);
                    break;
            }
            if ($retorno != '') {
                $arrayRetCarac[$retorno[0]][] = $retorno[1];
            }
        }
        $arrayRet[] = $this->carac2xml($arrayRetCarac);
        $arrayRet[] = $this->mapeoFotos();
        return $arrayRet;
    }

    protected function carac2xml($datos) {
        $clavesDatos = array_keys($datos);
        $arrRet = array();
        $arrAtrib = array();
        foreach ($clavesDatos as $clave) {
            if ($clave != 'Atributo' && $clave != '') {
                $arrRet[] = "<" . strtolower($clave) . ">" . $this->armaCData(implode(',', $datos[$clave])) . "</" . strtolower($clave) . ">";
            } elseif ($clave === 'Atributo') {
                foreach ($datos[$clave] as $atrDat) {
                    $strData = "<" . $atrDat[0] . ">" . $this->armaCData($atrDat[1]) . "</" . $atrDat[0] . ">";
                    if (array_search($strData, $arrAtrib) === false) {
                        $arrAtrib[] = $strData;
                    }
                }
            }
        }
        $arrRet[] = "<atributos>" . implode('', $arrAtrib) . "</atributos>";
//        print_r($arrAtrib);
        return implode('', $arrRet);
    }

    /*
      private $MONEDAUSD = 26;
      private $MONEDAAR = 25;
      private $PRECIO = 22;
      private $CAR_VALALQ=164;
      private $CAR_VALVTA=161;
      private $CAR_MONALQ=166;
      private $CAR_MONVTA=165;

     */

    protected function mapeoURLProp(){
        $strXml="<url_propiedad>".$this->armaCData($this->urlprop.$this->propiedad->getId_prop())."</url_propiedad>";
        return $strXml;;
    }
    
    protected function mapeoValorProp() {
        $strPrecio = '';
//        if ($this->propiedad->getPublicaprecio() == 1) {
            if (trim($this->propiedad->getOperacion()) == 'Venta') {
                $mon = $this->CAR_MONVTA;
                $val = $this->CAR_VALVTA;
            } else {
                $mon = $this->CAR_MONALQ;
                $val = $this->CAR_VALALQ;
            }
            $valor = intval($this->arrayCaracteristicas[$this->arrayClavesCarac[$val]]['contenido']);
            $moneda = $this->arrayCaracteristicas[$this->arrayClavesCarac[$mon]]['contenido'];
            if ($valor != 0 && $valor != '') {
                if ($moneda == 'U$S') {
                    $moneda = 'USD';
                } else {
                    $moneda = 'ARS';
                }
                $strPrecio = "<precio>" . $this->armaCData($valor) . "</precio><moneda>" . $this->armaCData($moneda) . "</moneda>";
            } else {
                $strPrecio = "<precio>" . $this->armaCData(0) . "</precio><moneda>" . $this->armaCData() . "</moneda>";
            }
        if ($this->propiedad->getPublicaprecio() == 1) {
            $strPrecio.="<publica_precio>" . $this->armaCData('si') . "</publica_precio>";
        } else {
//            $strPrecio = "<moneda>" . $this->armaCData() . "</moneda>";
            $strPrecio.="<publica_precio>" . $this->armaCData('no') . "</publica_precio>";
        }
        return $strPrecio;
    }

    protected function mapeoOperacion() {
        $strXml = '<tipo_operacion>';

        switch ($this->propiedad->getOperacion()) {
            case 'Venta':
            case 'Venta y Alquiler':
                $str = 'venta';
                break;
            case 'Alquiler':
                $str = 'alquiler';
                break;
            case 'Alquiler Temporario':
                $str = 'alquiler_temporario';
                break;
        }
        $strXml.=$this->armaCData($str);
        $strXml.='</tipo_operacion>';
        return $strXml;
    }

    protected function mapeoUbicacion() {
        $strXml = '';
        $ubi = '';
        $strXml.='<ubicacion>';
        $strXml.='<id_ubicacion>';
        $ubi = $this->cargaIdUbicacion();
        $this->arrayObligatorios[$this->IDUBICA] = 1;
        $strXml.=$this->armaCData($ubi);
        $strXml.='</id_ubicacion>';
        $strXml.=$this->mapeoDireccion();
        $strXml.='</ubicacion>';
        return $strXml;
    }

    protected function mapeoDireccion() {
        $strXml = '<calle>' . $this->armaCData($this->propiedad->getCalle()) . '</calle>';
        $strXml.='<altura>' . $this->armaCData($this->propiedad->getNro()) . '</altura>';
        $piso = $this->propiedad->getPiso();
        $depto = $this->propiedad->getDpto();
        if (trim($piso) != '') {
            $strXml.='<piso>' . $this->armaCData($piso) . '</piso>';
        }
        if (trim($depto) != '') {
            $strXml.='<departamento>' . $this->armaCData($depto) . '</departamento>';
        }
        $strXml.='<latitud>' . $this->armaCData($this->propiedad->getGoglat()) . '</latitud>';
        $strXml.='<longitud>' . $this->armaCData($this->propiedad->getGoglong()) . '</longitud>';

        return $strXml;
    }

    protected function mapeoTipoProp() {
        $strXml = '<tipo_propiedad>';
        $strXml.=$this->armaCData($this->cuTprop);
        if ($this->strZpTprop() != '') {
            $this->arrayObligatorios[$this->TIPOPROP] = 1;
        }
        $strXml.='</tipo_propiedad>';
        return $strXml;
    }

    protected function mapeoIdAviso() {
        $strXml = '<id_aviso>';
        $strXml.=$this->armaCData($this->propiedad->getId_prop());
        $strXml.='</id_aviso>';
        return $strXml;
    }

    protected function mapeoEstadoAviso() {
        $strXml = '<estado_publicacion>';
        $strXml.=$this->armaCData('activo');
        $strXml.='</estado_publicacion>';
        return $strXml;
    }

    protected function mapeoAntig() {
        print_r($this->arrayCaracteristicas);
        $arrayAntig = array('antiguedad', $valor);
        return $arrayAntig;
    }

    protected function mapeoCarac($registro) {
        $ret = '';
        $partesObj = explode('.', $registro['objeto']);
        $ind = $partesObj[1];
        $valor = '';
        $atributo = '';
        $arrayDatosCarac = array();
        //Ambientes.
        //Servicios.
        //Adicionales.
        //Atributo.
        // controlo que no sean caracteristicas de precio o moneda
        if ($ind != $this->CAR_VALALQ && $ind != $this->CAR_VALVTA && $ind != $this->CAR_MONALQ && $ind != $this->CAR_MONVTA) {
            if (array_key_exists($ind, $this->arrayClavesCarac)) {
                $defAtrib = explode('.', $registro['atributos']);
                $tipoAtr = $defAtrib[0];
                $atributo = $defAtrib[1];
                if ($tipoAtr == 'Atributo') {
//                    print_r($registro);
                    $carac = $this->arrayCaracteristicas[$this->arrayClavesCarac[$ind]];
                    // Defino tipo de Valor a armar
                    $valor = $this->definoValorAtributo($carac, $registro);
                    // Retorno VALOR ='' cuando la caracteristica original es una lista 
                    // para la misma ZPCarac existen varias opciones de Carac y depende del valor de la lista 
                    // cual se considera. En el caso de no seleccionar nada retorno con ''
                    if ($valor != '') {
                        // Defino atributo al que pertenece
//                        $atributo = $this->definoAtributo($carac, $registro, $valor);
//                        $ret = $atributo;
                        $arrayDatosCarac = array($tipoAtr, array($atributo, $valor));
                    }
//                    $arrayDatosCarac = array($tipoAtr, array($atributo, $valor));
                } else {
                    $arrayDatosCarac = array($tipoAtr, $registro['valorz']);
                }
                $ret = $arrayDatosCarac;
            }
        }
        return $ret;
    }

//| id_cucarac | objeto              | valorc                                    | tipoprop | atributos                             | valorz               | obligatorio |
//+------------+---------------------+-------------------------------------------+----------+---------------------------------------+----------------------+-------------+
//+          1 | caracteristicas.172 |                                           | *        | condicionesPago                       | String(500)          | NO          |
//|         22 | caracteristicas.161 |                                           | *        | precio                                | Numeric(12)          | NO          |
//|        104 | caracteristicas.125 |                                           | Casas_*  | especificaciones.cobertura_cochera    | cubierta             | NO          |
//|        105 | caracteristicas.126 |                                           | Casas_*  | especificaciones.cobertura_cochera    | descubierta          | NO          |
//|         60 | caracteristicas.120 | on                                        | Casas_*  | especificaciones.adicional            | sauna                | NO          |


    protected function definoValorAtributo($carac, $registro) {
        switch (substr($registro['valorz'], 0, 6)) {
            case 'intege':
                $valor = intval(preg_replace('/^[^\d-]+/', '', $carac['contenido']));
                break;
            default:
                $valor = $this->casteoCarac($carac, $registro);
//                $valor = $registro['valorz'];
                break;
        }

        if ($valor != '' && $valor != 0) {
            $this->arrayObligatorios[$registro['id_cucarac']] = 1;
        }
        return $valor;
    }

    protected function casteoCarac($carac, $registro) {
        $valor = '';
        if (strtoupper(trim($carac['contenido'])) == strtoupper(trim($registro['valorc']))) {
            $valor = $registro['valorz'];
        } else {
            if (intval(substr(trim($carac['contenido']), 0, 1)) >= 5 && trim($registro['valorc']) == '5_mas') {
                $valor = $registro['valorz'];
            }
        }
        return $valor;
    }

    protected function mapeoPropiedad($registro) {
        $ret = '';
        $partesObj = explode('.', $registro['objeto']);
        $compo = $partesObj[1];
        $valor = '';
        $atributo = '';
        $getCompo = "get" . strtoupper(substr($compo, 0, 1)) . substr($compo, 1); // armo geter del objeto propiedad
        if ($this->propiedad->$getCompo() != '') {
// Defino tipo de Valor a armar
            $valor = $this->propiedad->$getCompo();
// Defino atributo al que pertenece
            $atributo = $this->definoCaracteristicaGeneral($registro, $valor);
            $ret = $atributo;
        }
        return $ret;
    }

    protected function mapeoDescripcion() {
        $strXml = '<descripcion>';
        $strXml .= $this->armaCData($this->arrayCaracteristicas[$this->arrayClavesCarac[255]]['contenido']);
        $strXml .='</descripcion>';
        return ereg_replace("/\n\r|\r\n/|\n|\r", "", $strXml);
    }

    protected function mapeoFotos() {
        $init = 0;
        $strXml = '<imagenes>';
        $conf = CargaConfiguracion::getInstance();
        $path = $conf->leeParametro('path_fotos');
        $local = $conf->leeParametro('particular');
        foreach ($this->arrayFotos as $foto) {
            if ($init <= 12) {
                $init++;
                $strXml.=("<imagen><url_imagen>" . $this->armaCData("http://" . $local . $path . "/" . $foto['hfoto']) . "</url_imagen></imagen>");
            } else {
                break;
            }
        }
        $strXml.=$this->mapeoMapas();
        $strXml .= '</imagenes>';

        return $strXml;
    }

    protected function mapeoMapas() {
        $strXml = '';
        $conf = CargaConfiguracion::getInstance();
        $path = $conf->leeParametro('path_fotos');
        $local = $conf->leeParametro('particular');
        if ($this->propiedad->getPlano1() != '') {
            $strXml.=("<imagen><url_imagen>" . $this->armaCData("http://" . $local . $path . "/" . $this->propiedad->getPlano1()) . "</url_imagen></imagen>");
        }
        if ($this->propiedad->getPlano2() != '') {
            $strXml.=("<imagen><url_imagen>" . $this->armaCData("http://" . $local . $path . "/" . $this->propiedad->getPlano2()) . "</url_imagen></imagen>");
        }
        if ($this->propiedad->getPlano3() != '') {
            $strXml.=("<imagen><url_imagen>" . $this->armaCData("http://" . $local . $path . "/" . $this->propiedad->getPlano3()) . "</url_imagen></imagen>");
        }
        return $strXml;
    }

// SETEO DE ARRAYS y DATOS DE ARRANQUE    
    protected function setZpTprop($clave) {
        $this->cuTprop = $clave;
    }

    protected function seteaArraysCU() {
        $this->arrayCuTprop = $this->armaColeccionZpTprop();
        $this->arrayCuCarac = $this->armaColeccionZpCarac();
    }

    protected function seteaArraysMapeo() {
        $this->arrayMapeoTprop = $this->armaColeccionMapeoTprop();
        $this->setZpTprop($this->mapeoTprop());
        $this->arrayMapeoCarac = $this->armaColeccionMapeoCarac();
        $this->arrayObligatorios = $this->armaColeccionObligatorios();
    }

/*    
    protected function seteaProveedor() {
        $conf = CargaConfiguracion::getInstance();
        $this->provZp = $conf->leeParametro('proveedorZp');
        $this->claveZp = $conf->leeParametro('claveZp');
    }
*/
    
    protected function seteaPropiedad($id_prop) {
        $propBSN = new PropiedadBSN($id_prop);
        $this->propiedad = $propBSN->getObjeto();
    }

    protected function seteaResponsable($id_resp) {
        $respBSN = new LoginwebuserBSN();
        $respBSN->cargaById($id_resp);
        $this->responsable = $respBSN->getObjeto();
    }

    protected function seteaCaracteristicas($id_prop) {
        $datosBSN = new DatospropBSN();
        $this->arrayCaracteristicas = $datosBSN->coleccionCaracteristicasProp($id_prop, 1);
        $this->arrayClavesCarac = $this->seteaClavesCarac();
    }

    protected function seteaClavesCarac() {
        $arrayRet = array();
        $pos = 0;
        foreach ($this->arrayCaracteristicas as $registro) {
            $arrayRet[$registro['id_carac']] = $pos;
            $pos++;
        }
        return $arrayRet;
    }

    protected function seteaFotos($id_prop) {
        $fotos = new FotoBSN();
        $this->arrayFotos = $fotos->cargaColeccionFormByPropiedad($id_prop);
    }

// Lectura de Usuarios y armado de usuario clave desde XML
    protected function validUser($id_user = '', $clave = '') {
        $retorno = false;
        $parametricos = new CargaParametricos('usuariosZp.xml');
        $arraUsers = $parametricos->getParametros();
        if (array_key_exists($id_user, $arraUsers)) {
            $datosUser = $arraUsers[$id_user];
            $arrayDatos = explode("|", $datosUser);
            if (trim($clave) == trim($arrayDatos[1])) {
                $this->usuarioZp = $arrayDatos[0];
                $retorno = true;
            }
        }
        return $retorno;
    }

// LECTURA DE LOS COMPONENTES DESDE LAS TABLAS

    protected function cargaIdUbicacion() {
        $dao = new PGDAO();
        $strSql = $this->COLECCIONMAPEOUBICA . $this->propiedad->getId_ubica();
        $arrayDatos = $this->leeDBArray($dao->execSql($strSql));
        $id = $arrayDatos[0]['cuubica'];
        return $id;
    }

    /**
     * Lee los datos de la tabla de Tipos de Propiedad de ZP
     * @return string[][] con los datos de los tipos de propiedad 
     */
    protected function armaColeccionZpTprop() {
        $dao = new PGDAO();
        $arrayRet = $this->leeDBArray($dao->execSql($this->COLECCIONCUTPROP));
        return $arrayRet;
    }

    /*
     * Lee los datos de los campos de llenado obligatorio para definir si se puede publicar e indicar las diferencias.
     * Retorna un array donde la key del mismo es el ID de la caracteristica obligatoria segun la tabla de caracteristicas de ZP
     */

    protected function armaColeccionObligatorios() {
        $dao = new PGDAO();
        $result = array();
        $where = $this->strZpTprop();
        if ($where != '') {
            $colec = $this->leeDBArray($dao->execSql($this->COLECCIONCUCARACOBLIG . " and tipoprop in (" . $where . ")"));
        } else {
            $colec = $this->leeDBArray($dao->execSql($this->COLECCIONCUCARACOBLIG));
        }
        foreach ($colec as $carac) {
            $result[$carac['id_cucarac']] = 0;
        }
        return $result;
    }

    /**
     * Lee los datos de la tabla de Caracteristicas de ZP
     * @return string[][] con los datos de los caracteristicas
     */
    protected function armaColeccionZpCarac() {
        $arrayRet = array();
        $dao = new PGDAO();
        $where = $this->strZpTprop();
        if ($where != '') {
            $arrayRet = $this->leeDBArray($dao->execSql($this->COLECCIONCUCARAC . " where tipoprop in (" . $where . ")"));
        } else {
            $arrayRet = $this->leeDBArray($dao->execSql($this->COLECCIONCUCARAC));
        }
        return $arrayRet;
    }

    /**
     * Lee los datos de la tabla de Mapeos en Tipos de Propiedad de ZP
     * @return string[][] con los datos de los tipos de propiedad 
     */
    protected function armaColeccionMapeoTprop() {
        $dao = new PGDAO();
        $result = $this->leeDBArray($dao->execSql($this->COLECCIONMAPEOTPROP));
        return $result;
    }

    /**
     * Lee los datos de la tabla de Mapeos en Caracteristicas de ZP
     * @return string[][] con los datos de los caracteristicas
     */
    protected function armaColeccionMapeoCarac() {
        $dao = new PGDAO();
        $where = $this->strZpTprop();
        $strSql = $this->COLECCIONMAPEOCARAC . " WHERE tipoprop in (" . $where . ");";
        $arrayRet = $this->leeDBArray($dao->execSql($strSql));
        return $arrayRet;
    }

    /**
     * Transforma el tipo de propiedad segun ZP en un string que identifica las diferentes caracs que pueden estar contenidoas.
     * @return type 
     */
    protected function strZpTprop() {
        $arrayTp = explode('_', $this->cuTprop);
        $tipo = trim($arrayTp[0]);
        $subt = trim($arrayTp[1]);
        if ($tipo != '') {
            $where = "'" . $tipo . "_*'";
            if ($subt != '') {
                if (trim($tipo) == 'Quintas' || trim($tipo) == 'Campos y Chacras') {
                    $where.=(", '" . $tipo . "','*'");
                } else {
                    $where.=(", '" . $tipo . "_" . $subt . "','*'");
                }
            } else {
                $where.=(", '" . $tipo . "','*'");
            }
        } else {
            $where = '';
        }
        return $where;
    }

    // Administracion de datos de Registros en Cucicba   

    protected function registraPublicacion($id_prop, $id_user,$id_resp) {
//        $id_prop = $this->propiedad->getId_prop();
//        $id_resp = $this->responsable->getId_user();
        $usuario = $_SESSION['UserId'];
        $INSERT = "INSERT INTO #dbName#.registropublicacion (id_prop,id_zprop,proveedor,fec_ini,id_resp,id_user,cuenta)";
        $INSERT .=" values ($id_prop,'0','Cucicba',now(),$id_resp,$usuario,'$id_user');";
        $dao = new PGDAO();
        return $dao->execSql($INSERT);
    }

    protected function registraRetiro($id_prop) {
//        $id_prop = $this->propiedad->getId_prop();
        $UPDATE = "UPDATE #dbName#.registropublicacion set fec_fin=now() where id_prop=$id_prop AND proveedor='Cucicba'  AND fec_fin is null;";
        $dao = new PGDAO();
        return $dao->execSql($UPDATE);
    }

    public function consultaRegistroPropiedad($id_prop) {
//        $id_prop = $this->propiedad->getId_prop();
        $FINDBYIDPROP = "SELECT * FROM #dbName#.registropublicacion WHERE id_prop=$id_prop and proveedor='Cucicba' ORDER BY id_registro DESC LIMIT 1;";
        $dao = new PGDAO();
        return $this->leeDBArray($dao->execSql($FINDBYIDPROP));
    }

    public function coleccionRegistros($activas = -1) {
        $COLECCION = "SELECT id_registro,id_prop,id_zprop,proveedor,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,id_resp,id_user,cuenta from #dbName#.registropublicacion ";
        $ORDER = " order by id_prop,fec_ini,fec_fin";
        $WHERE = '';
        switch ($activas) {
            case 1:
                $WHERE = ' where fec_fin is null ';
                break;
            case 0:
                $WHERE = ' where fec_fin not is null ';
                break;
        }
        if ($WHERE == '') {
            $WHERE = ' where ';
        } else {
            $WHERE.=' and ';
        }
        $WHERE.=" proveedor= 'Cucicba' ";
        $dao = new PGDAO();
        return $this->leeDBArray($dao->execSql($COLECCION . $WHERE . $ORDER));
    }

    // Recibe el Id de ubicacion propio, lo contrata con el mapeo y retorna el tipo
    protected function tipoUbicacion() {
        $where = ' WHERE id_ubi = ' . $this->cargaIdUbicacion();
        $dao = new PGDAO();
        $registro = $this->leeDBArray($dao->execSql($this->DATOSUBICAZP . $where));
        $tipo = $registro[0]['tipo_ubi'];
        return trim(strtoupper($tipo));
    }

    protected function armaCData($str) {
        return "<![CDATA[" . $str . "]]>";
    }

    protected function armaArrayContactos($id_user) {
        $arrayRet = $this->arrayMails[$id_user];
        return $arrayRet;
    }

    //  Vista de datos

    public function muestraColeccionActivas() {
        $this->muestraColeccion(1);
    }

    public function muestraColeccionInactivas() {
        $this->muestraColeccion(0);
    }

}

?>
