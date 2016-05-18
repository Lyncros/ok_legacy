<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("clases/class.caracteristicaBSN.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.loginwebuserBSN.php");
include_once("clases/class.fotoBSN.php");
include_once("clases/class.telefonosBSN.php");
include_once('clases/class.tipo_propBSN.php');
include_once('clases/class.ubicacionpropiedadBSN.php');
include_once("generic_class/class.cargaParametricos.php");

/**
 * Claseo para mapeo de tipos de propiedades y caracteristicas.
 *
 * @author edgardo
 */
class ArgenpropBSN extends BSN {

    private $URL = 'http://www.inmuebles.clarin.com/Publicaciones/PublicarIntranet?contentType=json';
    private $URLBASE = 'http://www.inmuebles.clarin.com/Publicaciones/';

    private $IDSISTORIG = 10;
    private $ESWEB = "FALSE";
    private $IDORIGEN = "OKE";  //  REVISAR
    private $USR = "okeefe@api.com";
    private $PSW = "f5t4k2m2";
    private $IDVENDEDOR = 173904;
    private $PORT = 80;
    private $arrayAPTprop;
    private $arrayAPCarac;
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
    protected $COLECCIONZPTPROP = "SELECT * FROM #dbName#.aptipoprop;";
    protected $COLECCIONZPCARAC = "SELECT * FROM #dbName#.apcaracteristicas;";
    protected $COLECCIONZPCARACOBLIG = "SELECT c.id_apcarac FROM #dbName#.apcaracteristicas as c inner join #dbName#.mapeoapcarac as m  on c.id_apcarac=m.id_apcarac where obligatorio='si'";
    protected $COLECCIONMAPEOTPROP = "SELECT * FROM #dbName#.mapeoaptipoprop ";
    protected $COLECCIONMAPEOCARAC = "SELECT distinct m.id_apcarac,objeto,m.valor as valorc ,tipoprop,atributos,c.valor as valorz,obligatorio from #dbName#.apcaracteristicas as c inner join #dbName#.mapeoapcarac as m on c.id_apcarac=m.id_apcarac ";
    protected $COLECCIONMAPEOUBICA = "SELECT id_apubica from #dbName#.mapeoapubicacion where id_loca=";
    protected $DATOSUBICAZP = "SELECT * FROM #dbName#.apubicacion ";
    // Indicadores de ID de caracteristicas generales en el mapeo, corresponden a los id_zpcarac de ZP
    private $TIPOPER = 28;
    private $APELLIDO = 2;
    private $EMAIL = 3;
    private $NOMBRE = 5;
    private $CODAREA1 = 6;
    private $NROTEL1 = 10;
    private $HORARIOCONT = 4;
    private $DESCRIPCION = 255;
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
    private $CAR_VALALQ = 164;
    private $CAR_VALVTA = 161;
    private $CAR_MONALQ = 166;
    private $CAR_MONVTA = 165;
    private $TITULOFICHA = 257;


    private $ARRAYUSR = array('tomas@okeefe.com.ar' => array(
            'usr' => 'okeefe@api.com',
            'psw' => 'f5t4k2m2',
            'idsist' => '10',
            'idorig' => 'OKE',
            'idvend' => '173904',
            'url' => 'http://www.argenprop.com/Abonos/AvisosDestacables?key=69d82dcf875d39aeb583e7d964ed0156'),
        'charlie@okeefe.com.ar' => array(
            'usr' => 'okeefe@api.com',
            'psw' => 'f5t4k2m2',
            'idsist' => '10',
            'idorig' => 'OKE',
            'idvend' => '173904',
            'url' => 'http://www.argenprop.com/Abonos/AvisosDestacables?key=69d82dcf875d39aeb583e7d964ed0156'),
        'dennis@okeefe.com.ar' => array(
            'usr' => 'okeefe@api.com',
            'psw' => 'f5t4k2m2',
            'idsist' => '10',
            'idorig' => 'OKE',
            'idvend' => '173904',
            'url' => 'http://www.argenprop.com/Abonos/AvisosDestacables?key=69d82dcf875d39aeb583e7d964ed0156'),
        'felipe.atucha@okeefe.com.ar' => array(
            'usr' => 'okeefe@api.com',
            'psw' => 'f5t4k2m2',
            'idsist' => '10',
            'idorig' => 'OKE',
            'idvend' => '173904',
            'url' => 'http://www.argenprop.com/Abonos/AvisosDestacables?key=69d82dcf875d39aeb583e7d964ed0156'),
        'ignacio@okeefe.com.ar' => array(
            'usr' => 'okeefe@api.com',
            'psw' => 'f5t4k2m2',
            'idsist' => '10',
            'idorig' => 'OKE',
            'idvend' => '173904',
            'url' => 'http://www.argenprop.com/Abonos/AvisosDestacables?key=69d82dcf875d39aeb583e7d964ed0156'),
        'mike@okeefe.com.ar' => array(
            'usr' => 'okeefe@api.com',
            'psw' => 'f5t4k2m2',
            'idsist' => '10',
            'idorig' => 'OKE',
            'idvend' => '173904',
            'url' => 'http://www.argenprop.com/Abonos/AvisosDestacables?key=69d82dcf875d39aeb583e7d964ed0156')
    );

    public function __construct($id_prop = 0, $id_resp = 0) {
        if ($id_prop != 0) {
            ArgenpropBSN::seteaArraysZP();
            ArgenpropBSN::seteaPropiedad($id_prop);
            ArgenpropBSN::seteaResponsable($id_resp);
            ArgenpropBSN::seteaCaracteristicas($id_prop);
            ArgenpropBSN::seteaFotos($id_prop);
            ArgenpropBSN::seteaArraysMapeo();
            ArgenpropBSN::seteoTelefono();
            ArgenpropBSN::seteoContacto();
            ArgenpropBSN::seteaProveedor();
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ArgenProp";
        }
    }

//Envio de datos a Zona Prop
    public function publicoPropiedad($id_user = 0, $clave = '',$actividad='a') {
        if ($this->validUser($id_user, $clave)) {
        	//---CONTROL DE ANTERIOR PUBLICACION PARA ACTVIVARLA
            if(count($this->consultaPublicacionPropiedad()) != 0){
                $this->activoPropiedad();
            }

            $post = '';
            $post = $this->armoPostPublicarAP();
//            echo "ENTRA";
//            print $post . "<br>";
//            die();
            if ($post !== false) {
                $retData = $this->curlPost($post, $this->URL, $this->PORT, 'Publicar');
//                echo $retData;
//                print_r($retData);
                $retCode = $this->recuperoRetCode($retData);
//                echo $retCode;
                if (intval($retCode) == 0) {
                    $idPublica = $this->recuperoIdZprop($retData);
//                    echo sizeof($idPublica);
//                    print_r($idPublica);
                    if (sizeof($idPublica) == 1 && $idPublica[0]=='' && $actividad=='a') {
                        echo "Fallo la publicaci&oacute;n </b>". $idPublica[0];
                    } else {
                        echo "La publicaci&oacute;n se realiz&oacute; de forma exitosa.<br /> ";
                        if($actividad!='m'){
                          $this->registraPublicacion($idPublica,$id_user);
                          echo "Los ID de ArgenProp son <b> " . implode(',', $idPublica) . "</b>";
                        }
                    }
                } else {
//                    $retDesc = $this->recuperoError($retData);
                    echo 'Codigo de Error: 400 - Descripcion: Fallo la publicacion';
                }
            }
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ArgenProp";
        }
    }

    public function retiroPropiedad($id_user = 0, $clave = '') {
        if ($this->validUser($id_user, $clave)) {
            $url=$this->URLBASE."DarDeBaja/?contentType=json";
            $post=$this->armoPostRetirarAP();
            $retData = $this->curlPost($post, $url, $this->PORT, 'Retirar');
            $this->registraRetiro();
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ArgenProp";
        }
    }

    public function activoPropiedad() {
		$url=$this->URLBASE."Activar/?contentType=json";
		$post=$this->armoPostRetirarAP();
		$retData = $this->curlPost($post, $url, $this->PORT, 'Activar');
    }

    public function consultoPropiedad($id_user = 0, $clave = '') {
        if ($this->validUser($id_user, $clave)) {
            $retData = $this->json_post($this->armoJsonConsultarZP(), $this->URL, $this->PORT, 'Consultar');
        } else {
            echo "No posee permisos para publicar una propiedad, u operarar con ArgenProp";
        }
    }

    protected function curlPost($post_xml, $url, $port, $tarea) {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);              // Fail on errors

        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_PORT, $port);          //Set the port number
        curl_setopt($ch, CURLOPT_TIMEOUT, 360); // times out after 15s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_xml); // add POST fields
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8', 'Expect:'));
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

//        echo $data;
//        print_r($data);
//        die();
        $retCode = $this->recuperoRetCode($data);
        if (curl_errno($ch) != 0 || $retCode != 0) {
            $estado = 'Fallido';
            if (curl_errno($ch) != 0) {
                $observa = curl_error($ch);
            } else {
                $observa = 'Codigo de Error: ' . $retCode . ' - Descripcion: ' . $post_xml;
            }
        } else {
            $estado = 'OK';
            $observa = $data;
        }
        $this->registraLog($this->propiedad->getId_prop(), $tarea . ' ArgenProp', $estado, $observa);
        curl_close($ch);
        return $observa;
    }

    protected function recuperoRetCode($returnData) {
        //echo $returnData;
        if(strpos($returnData, 'error: 400')!==false || strpos($returnData, 'error: 500')!==false){
            $retVal = 1;
        }else{
            $retVal=0;
        }
        return $retVal;
    }

    protected function recuperoIdZprop($returnData) {
//        [6414562,6414563,6414564]
        $retCode=  substr(substr(trim($returnData),1),0,-1);
        $arrayExp = explode(',', $retCode);
        $retVal = $arrayExp;
        return $retVal;
    }

    protected function recuperoError($returnData) {
        $retVal = '';
        return $retVal;
    }

// Armado del POST

    protected function armoPostPublicarAP() {
        $post = $this->seteoPostHead();
        $arrayXml = $this->mapeoDatos();
        $retVal = $this->validaObligatorios();
        if ($retVal === true) {
            foreach ($arrayXml as $datos) {
                $post.=$datos;
            }
            $post.=$this->mapeoFotos();
//            $post.=$this->mapeoMapas();
            return $post;
        } else {
            echo "<div id='errorXml'>";
            echo "<strong>Existen elementos faltantes para la publicacion en ArgenProp</strong><br>";
            foreach ($retVal as $valor) {
                echo "$valor<br>";
            }
            echo "</div>";
            return false;
        }
    }

    protected function armoPostRetirarAP() {
        $post = 'usr=' . $this->USR . "&";
        $post.='psd=' . $this->PSW . "&";
        $post.= 'aviso.SistemaOrigen.Id=' . $this->IDSISTORIG . "&";
        $post.= 'aviso.IdOrigen=' . $this->IDORIGEN ."-". $this->propiedad->getId_prop();
        return $post;
    }

    protected function armoJsonConsultarZP() {
        $post = $this->seteoPostHead();
        $post.='<q0:consultarAviso>';
        $arrayXml = $this->mapeoDatos();
        $post.='<idAviso>' . $this->propiedad->getId_prop() . '</idAviso>';
        $post.='</q0:consultarAviso>';
        $post.= $this->seteoXmlPie();
        return $post;
    }

    protected function seteoTelefono() {
        $telStr = '';
        if (is_object($this->responsable)) {
            $telBSN = new TelefonosBSN();
            $tel = $telBSN->principalByUsuarios($this->responsable->getId_user());
            if ($tel['codarea'] != '' && $tel['codarea'] != 0) {
                $telStr.=("(" . $tel['codarea'] . ")");
                $this->arrayObligatorios[$this->CODAREA1] = 1;
            }
            if ($tel['numero'] != '' && $tel['numero'] != 0) {
                $telStr.=(" " . $tel['numero']);
                $this->arrayObligatorios[$this->NROTEL1] = 1;
            }
            if ($tel['interno'] != '') {
                $telStr.=(" Int.: " . $tel['interno']);
            }
        }
        if ($telStr != '') {
            $telStr = ("&aviso.DatosContacto.Telefono=" . $telStr . "&");
        }
        $this->arrayTel[] = $telStr;
    }

    protected function seteoContacto() { // Completar con datos de la empresa
        if (is_object($this->responsable)) {
            $prefijo = "aviso.DatosContacto.";
            if ($this->responsable->getNombre() != '') {
                $this->arrayContacto[] = $prefijo . "Nombre=" . trim($this->responsable->getNombre());
                $this->arrayObligatorios[$this->NOMBRE] = 1;
            }
            if ($this->responsable->getApellido() != '') {
                $this->arrayContacto[] = $prefijo . "Apellido=" . trim($this->responsable->getApellido());
                $this->arrayObligatorios[$this->APELLIDO] = 1;
            }
            if ($this->responsable->getEmail() != '') {
                $this->arrayContacto[] = $prefijo . "Email=" . trim($this->responsable->getEmail());
                $this->arrayObligatorios[$this->EMAIL] = 1;
            }
            $this->arrayContacto[] = $prefijo . "DisponibilidadAtencion=9 a 13 y 15 a 19 hs";
            $this->arrayObligatorios[$this->HORARIOCONT] = 1;
        }
    }

    protected function seteoPostHead() {
        $headPost = 'usr=' . $this->USR . "&";
        $headPost.='psd=' . $this->PSW . "&";
        $headPost.= 'aviso.EsWeb=' . $this->ESWEB . "&";
        $headPost.= 'aviso.SistemaOrigen.Id=' . $this->IDSISTORIG . "&";
        $headPost.= 'aviso.Vendedor.SistemaOrigen.Id=' . $this->IDSISTORIG . "&";
        $headPost.= 'aviso.Vendedor.IdOrigen=' . $this->IDORIGEN . "&";
        $headPost.= 'aviso.Vendedor.Id=' . $this->IDVENDEDOR . "&";
        return $headPost;
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
        $partes = explode('_', $texto);
        $retorno = $partes[0] . " " . $partes[1];
        return $retorno;
    }

// AMADO DE MAPEO DE TIPO DE PROPIEDAD

    protected function mapeoTprop() {
        $clave = '';
        $tipo = $this->propiedad->getId_tipo_prop();
        foreach ($this->arrayMapeoTprop as $registro) {
            if (trim($registro['id_tipo_prop']) == (trim($tipo))) {
//                    if (trim($registro['subtipo_prop']) == (trim($subtipo))) {
                $clave = $registro['clave'];
//                        $finder = 1;
//                        break;
//                    }
            }
        }
        return $clave;
    }

    protected function mapeoDatos() {
        $arrayRet = array();

        $arrayRet[] = $this->mapeoOperacion();
        $arrayRet[] = $this->mapeoIdAviso();
        $arrayRet[] = $this->mapeoTitulo();
        $arrayRet[] = $this->mapeoTipoProp();
//        print_r($this->arrayMapeoCarac);
        foreach ($this->arrayMapeoCarac as $caracZp) {
            $arrayPartes = explode('.', $caracZp['objeto']);
            switch ($arrayPartes[0]) {
                case 'caracteristicas':
                    $retorno = $this->mapeoCarac($caracZp);
                    break;
                case 'propiedad':
                    $retorno = $this->mapeoPropiedad($caracZp);
            }
            if ($retorno != '' && !($this->verificoExistencia($arrayRet, $retorno))) {
                $arrayRet[] = $retorno;
            }
        }
//        print_r($arrayRet);
        $mapValProp = $this->mapeoValorProp();
        if ($mapValProp != '') {
            $arrayRet[] = $mapValProp;
        }
        $arrayRet[] = $this->mapeoUbicacion();
        $arrayRet[] = $this->mapeoDireccion();
        $arrayRet[] = $this->mapeoContacto();
        return $arrayRet;
    }

    protected function verificoExistencia($array, $valor) {
        $retorno = array_search($valor, $array);
        return $retorno;
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

    protected function mapeoValorProp() {
        $strPrecio = '';
        if ($this->propiedad->getPublicaprecio() == 1) {
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
                    $moneda = '2';
                } else {
                    $moneda = '1';
                }
                $strPrecio = "&visibilidades[0].Moneda.Id=" . $moneda . "&";
                $strPrecio .= "visibilidades[0].MontoOperacion=" . $valor;
            }
        }
        return $strPrecio;
    }

    protected function mapeoContacto() {
        if (sizeof($this->arrayTel) > 0) {
            $strTel = $this->arrayTel[0];
        }else{
            $strTel="&";
        }
        $strCont = "";
        foreach ($this->arrayContacto as $conta) {
            $strCont.=("&" . $conta);
        }
        $strCont.=($strTel);
//        echo $strCont."<br>";
        return $strCont;
    }

    protected function mapeoUbicacion() {
        $strXml = '';
        $ubi = '';
        $ubi = $this->cargaIdUbicacion();
        $datosUbi = $this->datosUbicacion($ubi);

        if ($ubi != '' && $ubi != 0) {
            $strXml.=("&propiedad.Direccion.Pais.Id=" . $datosUbi['id_pais'] . "&");
            $strXml.=("propiedad.Direccion.Provincia.Id=" . $datosUbi['id_prov'] . "&");
            $strXml.=("propiedad.Direccion.Partido.Id=" . $datosUbi['id_partido'] . "&");
            $strXml.=("propiedad.Direccion.Localidad.Id=" . $datosUbi['id_loca']);
            if ($datosUbi['id_barrio'] != 0) {
                $strXml.=("&propiedad.Direccion.Barrio.Id=" . $datosUbi['id_barrio']);
                if ($datosUbi['id_subbarrio'] != 0) {
                    $strXml.=("&propiedad.Direccion.SubBarrio.Id=" . $datosUbi['id_subbarrio']);
                }
            }
            $this->arrayObligatorios[$this->IDUBICA] = 1;
//            $strXml.=$ubi;
        }
        return $strXml;
    }

    protected function mapeoTipoProp() {
        $strXml = '';
        $strXml.='&tipoPropiedad=';
        $strXml.=$this->zpTprop;
        if ($this->strZpTprop() != '') {
            $this->arrayObligatorios[$this->TIPOPROP] = 1;
        }
        $strXml.=$this->mapeoSubTipoProp();
        return $strXml;
    }

    protected function mapeoSubTipoProp() {
        $subtipo = '';
        switch ($this->zpTprop) {
            case 3: //Casa ;Duplex ;Tripl<ex ;Chalet ;CabaÃ±a; TownHouse
                switch (trim($this->propiedad->getSubtipo_prop())) {
                    case 'Casa' :
                        $subtipo = 1;
                        break;
                    case 'Duplex' :
                        $subtipo = 3;
                        break;
                    case 'Triplex' :
                        $subtipo = 4;
                        break;
                    case 'Chalet' :
                        $subtipo = 2;
                        break;
                    case 'CabaÃ±a' :
                        $subtipo = 6;
                        break;
                }
                break;
            case 1:
                switch (trim($this->propiedad->getSubtipo_prop())) {
                    case 'Departamento' :
                        $subtipo = 1;
                        break;
                    case 'Duplex' :
                        $subtipo = 4;
                        break;
                    case 'Triplex' :
                        $subtipo = 5;
                        break;
                    case 'Loft' :
                        $subtipo = 6;
                        break;
                    case 'Piso' :
                        $subtipo = 2;
                        break;
                    case 'Semipiso' :
                        $subtipo = 3;
                        break;
                    case 'Penthose' :
                        $subtipo = 7;
                        break;
                }
                break;
            case 10: //CAMPOS Agricolas;Ganadero;Mixtos;tambo;Forestal
                switch (trim($this->propiedad->getSubtipo_prop())) {
                    case 'Agricolas' :
                        $subtipo = 1;
                        break;
                    case 'Ganadero' :
                        $subtipo = 2;
                        break;
                    case 'Mixtos' :
                        $subtipo = 3;
                        break;
                    case 'tambo' :
                        $subtipo = 10;
                        break;
                    case 'Forestal' :
                        $subtipo = 14;
                        break;
                    default :
                        $subtipo = 11;
                        break;
                }
                break;
        }
        $strXml = '';
        $strXml.='&propiedad.TipoUnidad=';
        $strXml.=$subtipo;
        return $strXml;
    }

    protected function mapeoOperacion() {
        switch (trim($this->propiedad->getOperacion())) {
            case 'Venta' :
                $operacion = 1;
                break;
            case 'Alquiler' :
                $operacion = 2;
                break;
            case 'Alquiler Temporario' :
                $operacion = 3;
                break;
        }
        $strXml = '';
        $strXml.='aviso.TipoOperacion=';
        $strXml.=$operacion;
        return $strXml;
    }

    protected function mapeoDireccion() {

        $strXml = '';
        $strXml.='&propiedad.Direccion.Nombrecalle=' . $this->propiedad->getCalle();
        $strXml.='&propiedad.Direccion.Numero=' . $this->propiedad->getNro();
//        $strXml.='&propiedad.Direccion.Piso=' . $this->propiedad->getPiso();
//        $strXml.='&propiedad.Direccion.Departamento=' . $this->propiedad->getDpto();
        if ($this->propiedad->getGoglat() != 0 && $this->propiedad->getGoglong() != 0) {
            $strXml.='&propiedad.Direccion.Coordenadas.Latitud=' . str_replace('.', ',', $this->propiedad->getGoglat());
            $strXml.='&propiedad.Direccion.Coordenadas.Longitud=' . str_replace('.', ',', $this->propiedad->getGoglong());
        }
        return $strXml;
    }

    protected function mapeoIdAviso() {
        $strXml = '';
        $strXml.='&aviso.IdOrigen=';
        $strXml.=($this->IDORIGEN . "-" . $this->propiedad->getId_prop());
        return $strXml;
    }

    protected function mapeoTitulo() {
        $strXml = '';
        $strXml.='&aviso.Titulo=';
        $strXml.=($this->arrayCaracteristicas[$this->arrayClavesCarac[$this->TITULOFICHA]]['contenido']);
        $strXml.='&aviso.InformacionAdicional=';
        $strXml.=($this->arrayCaracteristicas[$this->arrayClavesCarac[$this->DESCRIPCION]]['contenido']);
        return $strXml;
    }

    protected function mapeoCarac($registro) {
        $ret = '';
        $partesObj = explode('.', $registro['objeto']);
        $ind = $partesObj[1];
        $valor = '';
        $atributo = '';
        // controlo que no sean caracteristicas de precio o moneda
        if ($ind != $this->CAR_VALALQ && $ind != $this->CAR_VALVTA && $ind != $this->CAR_MONALQ && $ind != $this->CAR_MONVTA) {
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
                $valor = substr($carac['contenido'], 0, $cantC);
                break;
            case 'Numeri':
                $valor = intval($carac['contenido']);
                break;
            case 'Boolea':
                $valor = 'TRUE';
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
        } else {
            if (intval(substr(trim($carac['contenido']), 0, 1)) >= 5 && trim($registro['valorc']) == '5_mas') {
                $valor = $registro['valorz'];
            }
        }
        return $valor;
    }

    protected function definoAtributo($carac, $registro, $valor) {
        $strXml = '';
        $strXml = "&" . $registro['atributos'] . "=" . $valor;
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
                if ($partesAtrib[1] != 'idUbicacion') {
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
        $pos = 0;
        foreach ($this->arrayFotos as $foto) {
            $strXml.=("aviso.Fotos[$pos].Url=http://" . $local . $path . "/" . $foto['hfoto'] . "&");
            $pos++;
        }
        $strXml.=$this->mapeoMapas($pos);
        if ($strXml != '') {
            $retorno = substr($strXml, 0, -1);
        } else {
            $retorno = '';
        }
        return $retorno;
    }

    protected function mapeoMapas($pos) {
        $strXml = '';
        $conf = CargaConfiguracion::getInstance();
        $path = $conf->leeParametro('path_fotos');
        $local = $conf->leeParametro('particular');
        if ($this->propiedad->getPlano1() != '' && $this->propiedad->getPlano1() != 'NULL') {
            $strXml.=("aviso.Fotos[$pos].Url=http://" . $local . $path . "/" . $this->propiedad->getPlano1() . "&");
            $pos++;
        }
        if ($this->propiedad->getPlano2() != '' && $this->propiedad->getPlano2() != 'NULL') {
            $strXml.=("aviso.Fotos[$pos].Url=http://" . $local . $path . "/" . $this->propiedad->getPlano2() . "&");
            $pos++;
        }
        if ($this->propiedad->getPlano3() != '' && $this->propiedad->getPlano3() != 'NULL') {
            $strXml.=("aviso.Fotos[$pos].Url=http://" . $local . $path . "/" . $this->propiedad->getPlano3() . "&");
            $pos++;
        }
        return $strXml;
    }

// SETEO DE ARRAYS y DATOS DE ARRANQUE
    protected function setZpTprop($clave) {
        $this->zpTprop = $clave;
    }

    protected function seteaArraysZP() {
        $this->arrayAPTprop = $this->armaColeccionZpTprop();
        $this->arrayAPCarac = $this->armaColeccionZpCarac();
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
//        print_r($arraUsers);
        if (array_key_exists($id_user, $arraUsers)) {
//        	echo "ENTRA<br>";
            $datosUser = $arraUsers[$id_user];
            $arrayDatos = explode("|", $datosUser);
//            echo $id_user." - ". $clave."<br>";
            if (trim($clave) == trim($arrayDatos[1])) {
//            	echo "ENTRA 2";
                $this->usuarioZp = $arrayDatos[0];
                $this->seteoDatosUsuario($id_user);
                $retorno = true;
            }
        }
        return $retorno;
    }

    protected function seteoDatosUsuario($usr){
//        $usr=  $this->usuarioZp;
        $this->USR=$this->ARRAYUSR[$usr]['usr'];
        $this->PSW=$this->ARRAYUSR[$usr]['psw'];
        $this->IDSISTORIG=$this->ARRAYUSR[$usr]['idsist'];
        $this->IDORIGEN=$this->ARRAYUSR[$usr]['idorig'];
        $this->IDVENDEDOR=$this->ARRAYUSR[$usr]['idvend'];
    }

// LECTURA DE LOS COMPONENTES DESDE LAS TABLAS

    protected function cargaIdUbicacion() {
        $dao = new PGDAO();
        $strSql = $this->COLECCIONMAPEOUBICA . $this->propiedad->getId_ubica();
        $arrayDatos = $this->leeDBArray($dao->execSql($strSql));
        $id = $arrayDatos[0]['id_apubica'];
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
//        echo "*** ".$strSql."  ****";
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

    // Administracion de datos de Registros en ArgenProp

    protected function registraPublicacion($id_zprop,$usuario) {
        $id_prop = $this->propiedad->getId_prop();
        $id_resp = $this->responsable->getId_user();
        $id_user = $_SESSION['UserId'];
        for($x=0;$x<sizeof($id_zprop);$x++){
            $INSERT = "INSERT INTO #dbName#.registropublicacion (id_prop,id_zprop,proveedor,fec_ini,id_resp,id_user,cuenta)";
            $INSERT .=" values ($id_prop,'$id_zprop[$x]','ArgenProp-$x',now(),$id_resp,$id_user,'$usuario');";
            $dao = new PGDAO();
            $ret=$dao->execSql($INSERT);
        }
        return $ret;
    }

    protected function registraRetiro() {
        $id_prop = $this->propiedad->getId_prop();
        $UPDATE = "UPDATE #dbName#.registropublicacion set fec_fin=now() where id_prop=$id_prop AND proveedor like '%ArgenProp%' AND fec_fin is null;";
        $dao = new PGDAO();
        return $dao->execSql($UPDATE);
    }

    public function consultaRegistroPropiedad() {
        $id_prop = $this->propiedad->getId_prop();
        $FINDBYIDPROP = "SELECT * FROM #dbName#.registropublicacion WHERE id_prop=$id_prop AND proveedor like '%ArgenProp%' ORDER BY id_registro DESC LIMIT 1;";
        $dao = new PGDAO();
        return $this->leeDBArray($dao->execSql($FINDBYIDPROP));
    }

    public function consultaPublicacionPropiedad() {
        $id_prop = $this->propiedad->getId_prop();
        $FINDBYIDPROP = "SELECT * FROM #dbName#.registropublicacion WHERE id_prop=$id_prop AND proveedor like '%ArgenProp%';";
        $dao = new PGDAO();
        return $this->leeDBArray($dao->execSql($FINDBYIDPROP));
    }

    public function coleccionRegistros($activas = -1) {
        $COLECCION = "SELECT id_registro,id_prop,id_zprop,proveedor,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,id_resp,id_user from #dbName#.registropublicacion ";
        $ORDER = " order by id_prop,fec_ini,fec_fin";
        $WHERE = '';
        switch ($activas) {
            case 1:
                $WHERE = ' where fec_fin is null AND proveedor LIKE "%ArgenProp-0" ';
                break;
            case 0:
                $WHERE = ' where fec_fin not is null  AND proveedor LIKE "%ArgenProp-0" ';
                break;
        }
        $dao = new PGDAO();
        return $this->leeDBArray($dao->execSql($COLECCION . $WHERE . $ORDER));
    }

    // Recibe el Id de ubicacion propio, lo contrata con el mapeo y retorna el tipo
    protected function tipoUbicacion() {
        $where = ' WHERE id_apubica = ' . $this->cargaIdUbicacion();
        $dao = new PGDAO();
        $registro = $this->leeDBArray($dao->execSql($this->DATOSUBICAZP . $where));
        $tipo = $registro[0]['tipo_ubi'];
        return trim(strtoupper($tipo));
    }

    protected function datosUbicacion($ubi) {
        $where = ' WHERE id_apubica = ' . $ubi;
        $dao = new PGDAO();
        $registro = $this->leeDBArray($dao->execSql($this->DATOSUBICAZP . $where));
//        $tipo = $registro[0]['tipo_ubi'];
//        return trim(strtoupper($tipo));
        return $registro[0];
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
        print " en ArgenProp</div>\n";

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
