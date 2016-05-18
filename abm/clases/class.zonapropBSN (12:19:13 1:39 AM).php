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
class ZonapropBSN extends BSN {

    //private $URL = 'http://www.zonaprop.com.ar/zp/webservice/realState?wsdl';
    private $URL = 'http://www.zonaprop.com.ar/webservice/realState';
 //   private $URL = 'http://qa.zonaprop.com.ar/webservice/realState';
    private $PORT = 80;
    private $arrayZpTprop;
    private $arrayZpCarac;
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
    private $zpTprop;
    private $usuarioZp;
    private $claveZp;
    private $provZp;
    protected $COLECCIONZPTPROP = "SELECT * FROM #dbName#.zptipoprop;";
    protected $COLECCIONZPCARAC = "SELECT * FROM #dbName#.zpcaracteristicas;";
    protected $COLECCIONZPCARACOBLIG = "SELECT c.id_zpcarac FROM #dbName#.zpcaracteristicas as c inner join #dbName#.mapeozpcarac as m  on c.id_zpcarac=m.id_zpcarac where obligatorio='si'";
    protected $COLECCIONMAPEOTPROP = "SELECT * FROM #dbName#.mapeozptipoprop ";
    protected $COLECCIONMAPEOCARAC = "SELECT distinct m.id_zpcarac,objeto,m.valor as valorc ,tipoprop,atributos,c.valor as valorz,obligatorio from #dbName#.zpcaracteristicas as c inner join #dbName#.mapeozpcarac as m on c.id_zpcarac=m.id_zpcarac ";
    protected $COLECCIONMAPEOUBICA = "SELECT zpubica from #dbName#.mapeozpubicacion where id_loca=";
    protected $DATOSUBICAZP = "SELECT * FROM #dbName#.zpubicacion ";
    // Indicadores de ID de caracteristicas generales en el mapeo, corresponden a los id_zpcarac de ZP
    private $TIPOPER = 28;
    private $APELLIDO = 2;
    private $EMAIL = 3;
    private $NOMBRE = 5;
    private $CODAREA1 = 6;
    private $NROTEL1 = 10;
    private $HORARIOCONT = 4;
    private $DESCRIPCION = 12;
    private $SUBTITULO = 24;
    private $TIPOPROP = 29;
    private $ALTURA = 30;
    private $IDUBICA = 36;
    private $CALLE = 37;
    private $HECTAREAS = 45;
// Definicion de parametros de caracteristicas de monedas de venta y alquiler
    private $MONEDAUSD = 26;
    private $MONEDAAR = 25;
    private $PRECIO = 22;
    private $CAR_VALALQ=164;
    private $CAR_VALVTA=161;
    private $CAR_MONALQ=166;
    private $CAR_MONVTA=165;

public function __construct($id_prop = 0, $id_resp = 0) {
        if ($id_prop != 0) {
            ZonapropBSN::seteaArraysZP();
            ZonapropBSN::seteaPropiedad($id_prop);
            ZonapropBSN::seteaResponsable($id_resp);
            ZonapropBSN::seteaCaracteristicas($id_prop);
            ZonapropBSN::seteaFotos($id_prop);
            ZonapropBSN::seteaArraysMapeo();
            ZonapropBSN::seteoTelefono();
            ZonapropBSN::seteoContacto();
            ZonapropBSN::seteaProveedor();
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ZonaProp";
        }
    }

//Envio de datos a Zona Prop
    public function publicoPropiedad($id_user = 0, $clave = '') {
        if ($this->validUser($id_user, $clave)) {
            $xml = '';
            $xml = $this->armoXmlPublicarZP();
//            print "XML: ".$xml."<br>";die();
	    if($xml!==false){        
		    $retData = $this->xml_post($xml, $this->URL, $this->PORT, 'Publicar');

        	    $retCode = $this->recuperoRetCode($retData);
        	    if ($retCode == 0) {
			$idPublica=$this->recuperoIdZprop($retData);
			if($idPublica == 0){
        	        	echo "Fallo la publicaci&oacute;n </b>";
			}else{
#        	        	$this->registraPublicacion($this->recuperoIdZprop($retData));
        	        	$this->registraPublicacion($idPublica);
        	        #	echo "La publicaci&oacute;n se realiz&oacute; de forma exitosa.<br /> El ID de ZonaProp es <b> " . $recuperoIdZprop($retData) . "</b>";
        	        	echo "La publicaci&oacute;n se realiz&oacute; de forma exitosa.<br /> El ID de ZonaProp es <b> " . $idPublica . "</b>";
			}
	            } else {
	                $retDesc = $this->recuperoError($retData);
	                echo 'Codigo de Error: ' . $retCode . ' - Descripcion: ' . $retDesc;
	            }
		}
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ZonaProp";
        }
    }

    public function retiroPropiedad($id_user = 0, $clave = '') {
        if ($this->validUser($id_user, $clave)) {
            $retData = $this->xml_post($this->armoXmlRetirarZP(), $this->URL, $this->PORT, 'Retirar');
            $this->registraRetiro();
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ZonaProp";
        }
    }

    public function consultoPropiedad($id_user = 0, $clave = '') {
        if ($this->validUser($id_user, $clave)) {
            $retData = $this->xml_post($this->armoXmlConsultarZP(), $this->URL, $this->PORT, 'Consultar');
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ZonaProp";
        }
    }

    protected function xml_post($post_xml, $url, $port, $tarea) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);              // Fail on errors

        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_PORT, $port);          //Set the port number
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 15s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_xml); // add POST fields
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'Expect:'));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 5000);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        if ($port == 443) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }

        $data = curl_exec($ch);

//        var_dump(curl_errno($ch));
//        var_dump(curl_error($ch));
        $retCode = $this->recuperoRetCode($data);
        if (curl_errno($ch) != 0 || $retCode != 0) {
            $estado = 'Fallido';
            if (curl_errno($ch) != 0) {
                $observa = curl_error($ch);
            } else {
                $observa = 'Codigo de Error: ' . $retCode . ' - Descripcion: ' . $this->recuperoError($data);
            }
        } else {
            $estado = 'OK';
            $observa = $data;
        }
        $this->registraLog($this->propiedad->getId_prop(), $tarea . ' ZonaProp', $estado, $observa);
        curl_close($ch);
        return $data;
    }

// Desarmo xml de respuesta de ZonaProp
//      <returnCode>0</returnCode>
//      <intergerValue>337186</intergerValue>

    protected function recuperoRetCode($returnData) {
        $arrayExp = explode('returnCode>', $returnData);
        $retVal = intval($arrayExp[1]);
        return $retVal;
    }

    protected function recuperoIdZprop($returnData) {
        //echo $returnData . "<br>";
        $arrayExp = explode('intergerValue>', $returnData);
        $retVal = intval($arrayExp[1]);
        return $retVal;
    }

//    <errorDescription>not valid instances found in specifications for property superficie-total</errorDescription>
//    <returnCode>-1</returnCode>
    protected function recuperoError($returnData) {
        $arrayExp = explode('errorDescription>', $returnData);
        $retVal = substr($arrayExp[1], 0, -2);
        return $retVal;
    }

// Armado del XML     

    protected function armoXmlPublicarZP() {
        $xml = $this->seteoXmlHead();
        $xml.='<q0:publicar>';
        $xml.='<q0:aviso>';
        $arrayXml = $this->mapeoDatos();
        $retVal = $this->validaObligatorios();
        if ($retVal === true) {
            foreach ($arrayXml as $datos) {
                $xml.=$datos;
            }
            $xml.=$this->mapeoFotos();
            $xml.=$this->mapeoMapas();
            $xml.='</q0:aviso>';
            $xml.='</q0:publicar>';
            $xml.= $this->seteoXmlPie();
            return $xml;
        } else {
            echo "<div id='errorXml'>";
            echo "<strong>Existen elementos faltantes para la publicacion en ZonaProp</strong><br>";
            foreach ($retVal as $valor) {
                echo "$valor<br>";
            }
            echo "</div>";
		return false;
        }
    }

    protected function armoXmlRetirarZP() {
        $xml = $this->seteoXmlHead();
        $xml.='<q0:finalizar>';
        $xml.='<nroAvisoProveedor>' . $this->propiedad->getId_prop() . '</nroAvisoProveedor>';
        $xml.='</q0:finalizar>';
        $xml.= $this->seteoXmlPie();
        return $xml;
    }

    protected function armoXmlConsultarZP() {
        $xml = $this->seteoXmlHead();
        $xml.='<q0:consultarAviso>';
        $arrayXml = $this->mapeoDatos();
        $xml.='<idAviso>' . $this->propiedad->getId_prop() . '</idAviso>';
        $xml.='</q0:consultarAviso>';
        $xml.= $this->seteoXmlPie();
        return $xml;
    }

    protected function seteoTelefono() {
        if (is_object($this->responsable)) {
            $telBSN = new TelefonosBSN();
            $tel = $telBSN->principalByUsuarios($this->responsable->getId_user());
            if ($tel['codarea'] != '' && $tel['codarea'] != 0) {
                $this->arrayTel[1][] = "<codigoArea>" . $tel['codarea'] . "</codigoArea>";
                $this->arrayObligatorios[$this->CODAREA1] = 1;
            }
            if ($tel['numero'] != '' && $tel['numero'] != 0) {
                $this->arrayTel[1][] = "<numeroTelefono>" . $tel['numero'] . "</numeroTelefono>";  // Poner TE de la empresa
                $this->arrayObligatorios[$this->NROTEL1] = 1;
            }
            if ($tel['interno'] != '') {
                $this->arrayTel[1][] = "<extension>" . $tel['interno'] . "</extension>";
            }
        }
    }

    protected function seteoContacto() { // Completar con datos de la empresa
        if (is_object($this->responsable)) {
            if ($this->responsable->getNombre() != '') {
                $this->arrayContacto[] = "<nombre>" . $this->responsable->getNombre() . "</nombre>";
                $this->arrayObligatorios[$this->NOMBRE] = 1;
            }
            if ($this->responsable->getApellido() != '') {
                $this->arrayContacto[] = "<apellido>" . $this->responsable->getApellido() . "</apellido>";
                $this->arrayObligatorios[$this->APELLIDO] = 1;
            }
            if ($this->responsable->getEmail() != '') {
                $this->arrayContacto[] = "<email>" . $this->responsable->getEmail() . "</email>";
                $this->arrayObligatorios[$this->EMAIL] = 1;
            }
            $this->arrayContacto[] = "<horarioContacto>9 a 13 y 15 a 19 hs</horarioContacto>";
            $this->arrayObligatorios[$this->HORARIOCONT] = 1;
        }
    }

    protected function seteoXmlHead() {
        $headXml = '<?xml version="1.0" encoding="UTF-8"?>';
        $headXml.='<soapenv:Envelope  ';
        $headXml.='xmlns:q0="http://service.g7.ws.inmuebles.dridco.com/"  ';
        $headXml.='xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"  ';
        $headXml.='xmlns:xsd="http://www.w3.org/2001/XMLSchema"  ';
        $headXml.='xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">  ';
        $headXml.='<soapenv:Header>  ';
        $headXml.='<q0:usuario>' . $this->usuarioZp . '</q0:usuario>';
        $headXml.='<q0:proveedor>' . $this->provZp . '</q0:proveedor>';
        $headXml.='<q0:password>' . $this->claveZp . '</q0:password>';
        $headXml.='</soapenv:Header>  ';
        $headXml.='<soapenv:Body>  ';
        return $headXml;
    }

    protected function seteoXmlPie() {
        $pieXml = "</soapenv:Body></soapenv:Envelope>  ";
        return $pieXml;
    }

// Valido que los campos obligatorios tengan informacion, en caso contrario
// muestro los campos que faltan

    protected function validaObligatorios() {
        $this->controlaValidezLista($this->armaArrayClavesListas());
        $retorno = true;
        $arrayFallos = array();
        $arrayClaves = array_keys($this->arrayObligatorios);
        foreach ($arrayClaves as $cpoOblig) {
            $atrib = $this->buscoAtributo($cpoOblig);
            if ($this->arrayObligatorios[$cpoOblig] == 0) {
                $arrayFallos[] = $atrib;
                $retorno = $arrayFallos;
            }
        }
        return $retorno;
    }


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

// Retorna la descripcion del atributo basado en la clave del mismo  
    protected function buscoAtributo($claveZp) {
        $retorno = '';
        foreach ($this->arrayMapeoCarac as $mapeo) {
            if ($mapeo['id_zpcarac'] == $claveZp) {
                $arrayPartes = explode('.', $mapeo['objeto']);
                if ($arrayPartes[0] == 'caracteristicas') {
                    if ($arrayPartes[1] != '') {
                        $carac = new CaracteristicaBSN($arrayPartes[1]);
                        $retorno = $carac->getObjeto()->getTitulo();
                    } else {
                        $retorno = $this->textoAtributo($mapeo['atributos']);
                    }
                } else {
                    $retorno = $this->textoAtributo($mapeo['atributos']);
                }
                break;
            }
        }
        return $retorno;
    }

    protected function textoAtributo($atrib) {
        $arrayEsp = explode('.', $atrib);
        $texto = $arrayEsp[1];
        $partes=explode('_',$texto);
        $retorno =$partes[0]." ".$partes[1];
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
                    case 19: // Industriales
                        $clave.='Galpones, depósitos y edificios industriales';
                        break;
                }
            }
        }
        return $clave;
    }

    protected function mapeoDatos() {
        $arrayRet = array();

        $arrayRet[] = $this->mapeoIdAviso();
        $arrayRet[] = $this->mapeoTipoProp();
        foreach ($this->arrayMapeoCarac as $caracZp) {
            $arrayPartes = explode('.', $caracZp['objeto']);
            switch ($arrayPartes[0]) {
                case 'caracteristicas':
                    $retorno = $this->mapeoCarac($caracZp);
                    break;
                case 'propiedad':
                    $retorno = $this->mapeoPropiedad($caracZp);
            }
            if ($retorno != '') {
                $arrayRet[] = $retorno;
            }
        }
	$mapValProp=$this->mapeoValorProp();
	if($mapValProp!=''){
	        $arrayRet[] = $mapValProp;
	}
        $arrayRet[] = $this->mapeoUbicacion();
        $arrayRet[] = $this->mapeoContacto();
        return $arrayRet;
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
	protected function mapeoValorProp(){
	   $strPrecio='';
	   if($this->propiedad->getPublicaprecio()==1){
			if(trim($this->propiedad->getOperacion())=='Venta'){
				$mon=$this->CAR_MONVTA;
				$val=$this->CAR_VALVTA;
			}else{
				$mon=$this->CAR_MONALQ;
				$val=$this->CAR_VALALQ;
			}
			$valor=	intval($this->arrayCaracteristicas[$this->arrayClavesCarac[$val]]['contenido']);
			$moneda=$this->arrayCaracteristicas[$this->arrayClavesCarac[$mon]]['contenido'];
			if($valor!=0 && $valor!=''){
				if($moneda=='U$S'){
					$moneda='USD';
				}else{
					$moneda='ARS';
				}
				$strPrecio="<precio>".$valor."</precio><tipoMoneda>".$moneda."</tipoMoneda>";
			}else{
				$strPrecio="<tipoMoneda></tipoMoneda>";
			}

	   }else{
			$strPrecio="<tipoMoneda></tipoMoneda>";
	   }
	   return $strPrecio;
	}

    protected function mapeoContacto() {
        if (sizeof($this->arrayTel) > 0) {
            for ($x = 1; $x <= sizeof($this->arrayTel); $x++) {
                $strTel = "<telefono" . $x . ">";
                foreach ($this->arrayTel[$x] as $datos) {
                    $strTel.=$datos;
                }
                $strTel.="</telefono" . $x . ">";
            }
        }
        $strCont = "<contacto>";
        foreach ($this->arrayContacto as $conta) {
            $strCont.=$conta;
        }
        $strCont.=$strTel;
        $strCont.="</contacto>";
        return $strCont;
    }

    protected function mapeoUbicacion() {
        $strXml = '';
        $ubi = '';
        if (sizeof($this->arrayUbica) > 0) {
            $strXml.='<ubicacion>';
            foreach ($this->arrayUbica as $ubica) {
                $strXml.=$ubica;
            }
            $strXml.='<idUbicacion>';
            $ubi = $this->cargaIdUbicacion();
            if ($ubi != '' && $ubi != 0) {
                $this->arrayObligatorios[$this->IDUBICA] = 1;
                $strXml.=$ubi;
            }
            $strXml.='</idUbicacion>';
            $strXml.='</ubicacion>';
        }
        return $strXml;
    }

    protected function mapeoTipoProp() {
        $strXml = '';
        $strXml.='<tipoPropiedad>';
        $strXml.=$this->zpTprop;
        if ($this->strZpTprop() != '') {
            $this->arrayObligatorios[$this->TIPOPROP] = 1;
        }
        $strXml.='</tipoPropiedad>';
        return $strXml;
    }

    protected function mapeoIdAviso() {
        $strXml = '';
        $strXml.='<idAvisoProveedor>';
//        $strXml.=($this->propiedad->getId_sucursal().$this->propiedad->getId_prop());
        $strXml.=($this->propiedad->getId_prop());
        $strXml.='</idAvisoProveedor>';
        return $strXml;
    }

    protected function mapeoCarac($registro) {
        $ret = '';
        $partesObj = explode('.', $registro['objeto']);
        $ind = $partesObj[1];
        $valor = '';
        $atributo = '';
	// controlo que no sean caracteristicas de precio o moneda
	if($ind!=$this->CAR_VALALQ && $ind!=$this->CAR_VALVTA && $ind!=$this->CAR_MONALQ && $ind!=$this->CAR_MONVTA){
	        if (array_key_exists($ind, $this->arrayClavesCarac)) {
        	    $carac = $this->arrayCaracteristicas[$this->arrayClavesCarac[$ind]];
		    // Defino tipo de Valor a armar
        	    $valor = $this->definoValorAtributo($carac, $registro);
		    // Retorno VALOR ='' cuando la caracteristica original es una lista 
		    // para la misma ZPCarac existen varias opciones de Carac y depende del valor de la lista 
	            // cual se considera. En el caso de no seleccionar nada retorno con ''
        	    if ($valor != '') {
		    // Defino atributo al que pertenece
	                $atributo = $this->definoAtributo($carac, $registro, $valor);
        	        $ret = $atributo;
        	    }
		}
        }
//	echo $ret."<br>";
        return $ret;
    }

//| id_zpcarac | objeto              | valorc                                    | tipoprop | atributos                             | valorz               | obligatorio |
//+------------+---------------------+-------------------------------------------+----------+---------------------------------------+----------------------+-------------+
//+          1 | caracteristicas.172 |                                           | *        | condicionesPago                       | String(500)          | NO          |
//|         22 | caracteristicas.161 |                                           | *        | precio                                | Numeric(12)          | NO          |
//|        104 | caracteristicas.125 |                                           | Casas_*  | especificaciones.cobertura_cochera    | cubierta             | NO          |
//|        105 | caracteristicas.126 |                                           | Casas_*  | especificaciones.cobertura_cochera    | descubierta          | NO          |
//|         60 | caracteristicas.120 | on                                        | Casas_*  | especificaciones.adicional            | sauna                | NO          |


    protected function definoValorAtributo($carac, $registro) {
        switch (substr($registro['valorz'], 0, 6)) {
            case 'String':
                $cantC = str_replace('String(', '', $registro['valorz']);
                $valor = htmlspecialchars(substr($carac['contenido'], 0, $cantC));
                break;
            case 'Numeri':
                $valor = intval($carac['contenido']);
                break;
            default:
                $valor = $this->casteoCarac($carac, $registro);
                break;
        }

        if ($valor != '' && $valor != 0) {
            $this->arrayObligatorios[$registro['id_zpcarac']] = 1;
        }
        return $valor;
    }

    protected function casteoCarac($carac, $registro) {
        $valor = '';
        if (strtoupper(trim($carac['contenido'])) == strtoupper(trim($registro['valorc']))) {
            $valor = $registro['valorz'];
        }else{
		if(intval(substr(trim($carac['contenido']),0,1)) >=5  && trim($registro['valorc'])=='5_mas'){
			$valor =$registro['valorz'];
		}
	}
        return $valor;
    }

    protected function definoAtributo($carac, $registro, $valor) {
        $partesAtrib = explode('.', $registro['atributos']);
        $strXml = '';
        switch ($partesAtrib[0]) {
            case 'especificaciones':
                $strXml = '<especificaciones><nombre>' . $partesAtrib[1] . '</nombre><valor>' . $valor . '</valor></especificaciones>';
                break;
            case 'medidas':
// Ver que formato traen las cosas y preparar un regexp para buscar si contenido tiene 99 x 99 o parecido
                $strXml = '<medidas><ambiente>' . $partesAtrib[1] . '</ambiente><valor>' . $valor . '</valor></medidas>';
                break;
            default:
                $strXml = '<' . $partesAtrib[0] . '>' . $valor . '</' . $partesAtrib[0] . '>';
                break;
        }
        if (trim($valor) != '') {
            $this->arrayObligatorios[$registro['id_zpcarac']] = 1;
        }

        return $strXml;
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

    protected function definoCaracteristicaGeneral($registro, $valor) {
        $partesAtrib = explode('.', $registro['atributos']);
        $strXml = '';
        switch ($partesAtrib[0]) {
            case 'contacto':
                switch ($partesAtrib[1]) {
                    case 'telefono':
                        $posPar = strpos($partesAtrib[2], '(');
                        $this->arrayTelefono[substr($partesAtrib[2], $posPar, 1)][] = '<' . substr($partesAtrib[2], 0, $posPar - 1) . '>' . $valor . '</' . substr($partesAtrib[2], 0, $posPar - 1) . '>';
                        break;
                    default:
                        $this->arrayContacto[] = '<' . $partesAtrib[1] . '>' . $valor . '</' . $partesAtrib[1] . '>';
                        break;
                }
                break;
            case 'ubicacion':  // idUbicacion
                if($partesAtrib[1]!='idUbicacion'){
                    $this->arrayUbica[] = '<' . $partesAtrib[1] . '>' . $valor . '</' . $partesAtrib[1] . '>';
                    if ($valor != '') {
                        $this->arrayObligatorios[$registro['id_zpcarac']] = 1;
                    }
                }
                break;
            default:
                if ($partesAtrib[0] == 'tipoOperacion') {
                    $valor = strtolower($valor);
                }
                if ($valor != '') {
                    $strXml = '<' . $partesAtrib[0] . '>' . $valor . '</' . $partesAtrib[0] . '>';
                    $this->arrayObligatorios[$this->TIPOPER] = 1;
                }
                break;
        }
        return $strXml;
    }

    protected function mapeoFotos() {
        $strXml = '';
        $conf = CargaConfiguracion::getInstance();
        $path = $conf->leeParametro('path_fotos');
        $local = $conf->leeParametro('particular');
        foreach ($this->arrayFotos as $foto) {
            $strXml.=("<urlImagenes>http://" . $local . $path . "/" . $foto['hfoto'] . "</urlImagenes>");
        }
        return $strXml;
    }

    protected function mapeoMapas() {
        $strXml = '';
        $conf = CargaConfiguracion::getInstance();
        $path = $conf->leeParametro('path_fotos');
        $local = $conf->leeParametro('particular');
        if($this->propiedad->getPlano1()!=''){
            $strXml.=("<urlImagenes>http://" . $local . $path . "/" . $this->propiedad->getPlano1() . "</urlImagenes>");
        }
        if($this->propiedad->getPlano2()!=''){
            $strXml.=("<urlImagenes>http://" . $local . $path . "/" . $this->propiedad->getPlano2() . "</urlImagenes>");
        }
        if($this->propiedad->getPlano3()!=''){
            $strXml.=("<urlImagenes>http://" . $local . $path . "/" . $this->propiedad->getPlano3() . "</urlImagenes>");
        }
        return $strXml;
    }
    
// SETEO DE ARRAYS y DATOS DE ARRANQUE    
    protected function setZpTprop($clave) {
        $this->zpTprop = $clave;
    }

    protected function seteaArraysZP() {
        $this->arrayZpTprop = $this->armaColeccionZpTprop();
        $this->arrayZpCarac = $this->armaColeccionZpCarac();
    }

    protected function seteaArraysMapeo() {
        $this->arrayMapeoTprop = $this->armaColeccionMapeoTprop();
        $this->setZpTprop($this->mapeoTprop());
        $this->arrayMapeoCarac = $this->armaColeccionMapeoCarac();
        $this->arrayObligatorios = $this->armaColeccionObligatorios();
    }

    protected function seteaProveedor() {
        $conf = CargaConfiguracion::getInstance();
        $this->provZp = $conf->leeParametro('proveedorZp');
        $this->claveZp = $conf->leeParametro('claveZp');
    }

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
        $id = $arrayDatos[0]['zpubica'];
        return $id;
    }

    /**
     * Lee los datos de la tabla de Tipos de Propiedad de ZP
     * @return string[][] con los datos de los tipos de propiedad 
     */
    protected function armaColeccionZpTprop() {
        $dao = new PGDAO();
        $arrayRet = $this->leeDBArray($dao->execSql($this->COLECCIONZPTPROP));
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
            $colec = $this->leeDBArray($dao->execSql($this->COLECCIONZPCARACOBLIG . " and tipoprop in (" . $where . ")"));
        } else {
            $colec = $this->leeDBArray($dao->execSql($this->COLECCIONZPCARACOBLIG));
        }
        foreach ($colec as $carac) {
            $result[$carac['id_zpcarac']] = 0;
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
            $arrayRet = $this->leeDBArray($dao->execSql($this->COLECCIONZPCARAC . " where tipoprop in (" . $where . ")"));
        } else {
            $arrayRet = $this->leeDBArray($dao->execSql($this->COLECCIONZPCARAC));
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
        $arrayTp = explode('_', $this->zpTprop);
        $tipo = trim($arrayTp[0]);
        $subt = trim($arrayTp[1]);
        if ($tipo != '') {
            $where = "'" . $tipo . "_*'";
            if ($subt != '') {
		if(trim($tipo)=='Quintas' || trim($tipo)=='Campos y Chacras'){
                $where.=(", '" . $tipo . "','*'");
		}else{
                $where.=(", '" . $tipo . "_" . $subt . "','*'");
		}
            } else {
                $where.=(", '" . $tipo ."','*'");
            }
        } else {
            $where = '';
        }
        return $where;
    }

    // Administracion de datos de Registros en ZonaProp   

    protected function registraPublicacion($id_zprop) {
        $id_prop = $this->propiedad->getId_prop();
        $id_resp = $this->responsable->getId_user();
        $id_user = $_SESSION['UserId'];
        $INSERT = "INSERT INTO #dbName#.registropublicacion (id_prop,id_zprop,proveedor,fec_ini,id_resp,id_user)";
        $INSERT .=" values ($id_prop,$id_zprop,'ZonaProp',now(),$id_resp,$id_user);";
        $dao = new PGDAO();
        return $dao->execSql($INSERT);
    }

    protected function registraRetiro() {
        $id_prop = $this->propiedad->getId_prop();
        $UPDATE = "UPDATE #dbName#.registropublicacion set fec_fin=now() where id_prop=$id_prop AND fec_fin is null;";
        $dao = new PGDAO();
        return $dao->execSql($UPDATE);
    }

    public function consultaRegistroPropiedad() {
        $id_prop = $this->propiedad->getId_prop();
        $FINDBYIDPROP = "SELECT * FROM #dbName#.registropublicacion WHERE id_prop=$id_prop ORDER BY id_registro DESC LIMIT 1;";
        $dao = new PGDAO();
        return $this->leeDBArray($dao->execSql($FINDBYIDPROP));
    }

    public function coleccionRegistros($activas = -1) {
        $COLECCION = "SELECT id_registro,id_prop,id_zprop,proveedor,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,id_resp,id_user from #dbName#.registropublicacion ";
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

    //  Vista de datos

    public function muestraColeccionActivas() {
        $this->muestraColeccion(1);
    }

    public function muestraColeccionInactivas() {
        $this->muestraColeccion(0);
    }

    public function muestraColeccion($activas = -1) {
        $usuario = new LoginwebuserBSN();
        $prop = new PropiedadBSN();
        print "<div class='pg_titulo'>Listado de Propiedades";
        switch ($activas) {
            case 1:
                $estado = ' activas';
                break;
            case 2:
                $estado = ' inactivas';
                break;
        }
        print " en ZonaProp</div>\n";

        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td class='cd_lista_titulo'>Propiedad</td>\n";
        print "     <td class='cd_lista_titulo'>Publicada</td>\n";
        print "     <td class='cd_lista_titulo'>Retirada</td>\n";
        print "     <td class='cd_lista_titulo'>Responsable</td>\n";
        print "     <td class='cd_lista_titulo'>Publico</td>\n";
        print "	  </tr>\n";


        $arrayEven = $this->coleccionRegistros($activas);

        if (sizeof($arrayEven) == 0) {
            print "No existen datos para mostrar";
        } else {
            foreach ($arrayEven as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }

                $usuario->cargaById($Even['id_resp']);
                $responsable = $usuario->getObjeto()->getApellido() . ", " . $usuario->getObjeto()->getNombre();
                if (trim($responsable == ', ')) {
                    $responsable = '-';
                }

                $usuario->cargaById($Even['id_user']);
                $publico = $usuario->getObjeto()->getApellido() . ", " . $usuario->getObjeto()->getNombre();
                if (trim($publico == ', ')) {
                    $publico = '-';
                }

                $prop->cargaById($Even['id_prop']);
                $propiedad = $prop->getObjeto()->getCalle() . " " . $prop->getObjeto()->getNro() . " " . $prop->getObjeto()->getPiso() . " " . $prop->getObjeto()->getDpto();
                print "<tr>\n";
                print "	 <td class='row" . $fila . "'>" . $propiedad . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['fec_ini'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['fec_fin'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $responsable . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $publico . "</td>\n";
                print "	</tr>\n";
            }
        }

        print "  </table>\n";
    }

}

?>
