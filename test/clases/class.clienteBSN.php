<?php

include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");

include_once("clases/class.cliente.php");
include_once("clases/class.clientePGDAO.php");
include_once("clases/class.fechas.php");
include_once("clases/class.clienteCache.php");

include_once('clases/class.tipoTelefonoBSN.php');
include_once('clases/class.tipoDomicilioBSN.php');
include_once('clases/class.tipoMedioelectronicoBSN.php');
include_once('clases/class.asuntoBSN.php');
include_once('clases/class.crmbuscadorBSN.php');
include_once('clases/class.eventoBSN.php');
include_once 'clases/class.eventocomponente.php';

class ClienteBSN extends BSN {

    protected $clase = "Cliente";
    protected $nombreId = "Id_cli";
    protected $cliente;

    public function __construct($_login = "") {
        ClienteBSN::seteaMapa();
        if ($_login instanceof Cliente) {
            ClienteBSN::creaObjeto();
            ClienteBSN::seteaBSN($_login);
        } else {
            if (is_string($_login) || is_numeric($_login)) {
                ClienteBSN::creaObjeto();
                if ($_login != "" && $_login != 0) {
                    if (is_numeric($_login)) {
                        ClienteBSN::cargaById($_login);
                    } else {
                        ClienteBSN::cargaByUsuario($_login);
                    }
                }
            }
        }
        $conf = CargaConfiguracion::getInstance();
        $timezone = $conf->leeParametro('timezone');
        date_default_timezone_set($timezone);
    }

    public function getId() {
        return $this->cliente->getId_cli();
    }

    public function setId($id) {
        $this->cliente->setId_cli($id);
    }

    public function borraDB() {
        $this->actualizaCacheCliente($this->getObjeto()->getId_cli(), 'd');

        $retorno = parent::borraDB();
        return $retorno;
    }

    public function getJsonCliente() {
//        print_r($this->cliente);
        $cliente = $this->getObjetoView();
        return $this->getJsonColeccion($cliente);
    }

    public function clienteNuevo($datos=0){
        $retorno = parent::insertaDB();
        if($datos==0){
            $id_cli=$_POST['id_cli'];
        }else{
            $id_cli=$datos;
        }
//        $this->actualizaCacheCliente($_POST['id_cli'], 'a');
        $this->actualizaCacheCliente($id_cli, 'a');
        return $retorno;
    }
    /*
ID_FINGRESO:1
ID_FCONTACTO:1
CALLE:
NRO:
piso:
loca:
cp:
prov:
pais:
     */
    public function grabaClienteAmpliado($arrayDatos){
        $retCli=false;
        if (isset($arrayDatos['APELLIDO']) && isset($arrayDatos['NOMBRE']) && $arrayDatos['APELLIDO'] != '' && $arrayDatos['NOMBRE'] != '') {
            $cli = new Cliente();
            $idCli = $arrayDatos['ID_CLI'];
            $cli->setId_cli($idCli);
            $cli->setApellido($arrayDatos['APELLIDO']);
            $cli->setNombre($arrayDatos['NOMBRE']);
            $cli->setTipocont($arrayDatos['TIPOCONT']);
            $cli->setCategoria($arrayDatos['CATEGORIA']);
            $cli->setAsignacion($arrayDatos['ASIGNACION']);
            $cli->setCapcompra($arrayDatos['CAPCOMPRA']);
            $cli->setSexo($arrayDatos['SEXO']);
            if(trim($arrayDatos['NEWS'])=="on"){
                $news=1;
            }else{
                $news=0;
            }
            $cli->setNews($news);
            $cli->setTipo_doc($arrayDatos['TIPO_DOC']);
            $cli->setNro_doc($arrayDatos['NRO_DOC']);
            $cli->setFecha_nac($arrayDatos['FECHA_NAC']);
            $cli->setId_estciv($arrayDatos['ID_ESTCIV']);
            $cli->setGrupofam($arrayDatos['GRUPOFAM']);
            $cli->setObservacion($arrayDatos['COMENTARIOS']);
            $cli->setEmpresa($arrayDatos['EMPRESA']);
            $cli->setCuit($arrayDatos['CUIT']);
            $this->seteaBSN($cli);
            $retCli = $this->clienteNuevo($idCli);            
        }
        if ($retCli) {
            $telBSN = new TelefonosBSN();
            if (isset($arrayDatos['TELEFONO']) && $arrayDatos['TELEFONO'] != '') {
                $tel = new Telefonos(0, 'Particular', '054', '011', trim($arrayDatos['TELEFONO']), '', 'C', $idCli, 0);
                $telBSN->seteaBSN($tel);
                $retTel = $telBSN->insertaDB();
            }
            if (isset($arrayDatos['CELULAR']) && $arrayDatos['CELULAR'] != '') {
                $tel = new Telefonos(0, 'Cel Particular', '054', '011', trim($arrayDatos['CELULAR']), '', 'C', $idCli, 0);
                $telBSN->seteaBSN($tel);
                $retTel = $telBSN->insertaDB();
            }
            if (isset($arrayDatos['MAIL']) && $arrayDatos['MAIL'] != '') {
                $med = new Medioselectronicos(0, 1, trim($arrayDatos['MAIL']), '', 'C', $idCli, 0);
                $medBSN = new MedioselectronicosBSN($med);
                $medBSN->insertaDB();
            }
            if (isset($arrayDatos['EJECUTIVO']) && $arrayDatos['EJECUTIVO'] != '') {
                $relBean = new Relacion($arrayDatos['EJECUTIVO'], $idCli, 11);
                $relNBSN = new RelacionBSN($relBean);
                $relNBSN->insertaDB();
            }
            if (isset($arrayDatos['CALLE']) && $arrayDatos['CALLE'] != '') {
                $domBean = new Domicilio(0,'Particular' , $arrayDatos['ID_UBICA'], $arrayDatos['CALLE'], $arrayDatos['NRO'], $arrayDatos['PISO'], $arrayDatos['DPTO'], '', '', $arrayDatos['CP'], 'C', $idCli);
                $domNBSN = new DomicilioBSN($domBean);
                $domNBSN->insertaDB();
            }

            $retorno = array('codRet' => '0', 'msg' => 'Se proceso correctamente el registro de los datos', 'idCli' => $idCli);
            
        }else{
            $retorno = array('codRet' => '1', 'msg' => 'Fallo el registro de los datos');
        }
        return $retorno;
    }

    public function grabaClienteReducido($arrayDatos){
        $retCli=false;
        if (isset($arrayDatos->apellido) && isset($arrayDatos->nombre) && $arrayDatos->apellido != '' && $arrayDatos->nombre != '') {
            $cli = new Cliente();
            $idCli = date('YmdHis');
            $cli->setId_cli($idCli);
            $cli->setApellido($arrayDatos->apellido);
            $cli->setNombre($arrayDatos->nombre);
            $cli->setTipocont(99);
            $cli->setCategoria(99);
            $cli->setAsignacion(99);
            $cli->setCapcompra(0);
            $this->seteaBSN($cli);
            $retCli = $this->clienteNuevo($idCli);            
//            $cliBSN = new ClienteBSN($cli);
//            $retCli = $cliBSN->clienteNuevo();
        }
        if ($retCli) {
            $telBSN = new TelefonosBSN();
            if (isset($arrayDatos->telefono) && $arrayDatos->telefono != '') {
                $tel = new Telefonos(0, 'Particular', '054', '011', trim($arrayDatos->telefono), '', 'C', $idCli, 0);
                $telBSN->seteaBSN($tel);
                $retTel = $telBSN->insertaDB();
            }
            if (isset($arrayDatos->celular) && $arrayDatos->celular != '') {
                $tel = new Telefonos(0, 'Cel Particular', '054', '011', trim($arrayDatos->celular), '', 'C', $idCli, 0);
                $telBSN->seteaBSN($tel);
                $retTel = $telBSN->insertaDB();
            }
            if (isset($arrayDatos->mail) && $arrayDatos->mail != '') {
                $med = new Medioselectronicos(0, 1, trim($arrayDatos->mail), '', 'C', $idCli, 0);
                $medBSN = new MedioselectronicosBSN($med);
                $medBSN->insertaDB();
            }
        }
        return $idCli;
    }
    
    public function insertaDB() {
        $retorno = parent::insertaDB();
        if ($retorno) {
            $this->grabaDatosCliente($_POST, $_POST['ID_CLI']); //$this->getObjeto()->getId_cli());
//            $this->grabaCRM($_POST, $_POST['ID_CLI']); //$this->getObjeto()->getId_cli());
        }
//        $this->actualizaCacheCliente($this->getObjeto()->getId_cli(), 'a');
        $this->actualizaCacheCliente($_POST['ID_CLI'], 'a');
        return $retorno;
    }

    /**
     * Modifica el juego de datos asociados al cliente
     * @param string[] $arrayPost -> array con los datos posteados por el formulario
     * @param int $idCli -> identificacion del cliente a grabar
     */
    protected function grabaDatosCliente($arrayPost, $idCli) {
        $arrClaves = array_keys($arrayPost);
        if (isset($arrayPost['ID_FINGRESO']) && $arrayPost['ID_FINGRESO'] != 0) {
            $ci = new Ingresocliente(0, $idCli, $_SESSION['UserId'], date('d/m/Y'), 0, $arrayPost['ID_FINGRESO'], $arrayPost['ID_PROMO']);
            $ciBSN = new IngresoclienteBSN($ci);
            $ciBSN->insertaDB();
        }
        foreach ($arrClaves as $dato) {
            switch (substr($dato, 0, 5)) {
                case ('id_te'):
                    $apos = substr($dato, strlen('id_telefono_'));
                    if (isset($arrayPost['numero_' . $apos]) && $arrayPost['numero_' . $apos] != '' && $arrayPost['numero_' . $apos] != 0) {
                        $telBean = new Telefonos($arrayPost['id_telefono_' . $apos], $arrayPost['tipotel_' . $apos], $arrayPost['codpais_' . $apos], $arrayPost['codarea_' . $apos], $arrayPost['numero_' . $apos], $arrayPost['interno_' . $apos], 'C', $idCli);
                        $telBSN = new TelefonosBSN($telBean);
                        $telBSN->insertaDB();
                    }
                    break;
                case ('id_me'):
                    $apos = substr($dato, strlen('id_medio_'));
                    if (isset($arrayPost['contacto_' . $apos]) && $arrayPost['contacto_' . $apos] != '') {
                        $medBean = new Medioselectronicos($arrayPost['id_medio_' . $apos], $arrayPost['id_tipomed_' . $apos], $arrayPost['contacto_' . $apos], '', 'C', $idCli);
                        $medBSN = new MedioselectronicosBSN($medBean);
                        $medBSN->insertaDB();
                    }
                    break;
                case ('id_fa'):
                    $apos = substr($dato, strlen('id_fam_'));
                    $parBean = new Familiares($arrayPost['id_fam_' . $apos], $arrayPost['fecha_nac_' . $apos], $idCli, $arrayPost['nombre_' . $apos], $arrayPost['apellido_' . $apos], $arrayPost['id_parent_' . $apos], '', 'C');
                    $parBSN = new FamiliaresBSN($parBean);
                    $parBSN->insertaDB();
                    break;
                case ('id_pc'):
                    $apos = substr($dato, strlen('id_pc_'));
                    $relBean = new Relacion($arrayPost['id_pc_' . $apos], $idCli, $arrayPost['id_relacion_' . $apos]);
                    $relNBSN = new RelacionBSN($relBean);
                    $relNBSN->insertaDB();
                    break;
                case ('id_do'):
                    $apos = substr($dato, strlen('id_dom_'));
                    $domBean = new Domicilio($arrayPost['id_dom_' . $apos], $arrayPost['tipo_' . $apos], $arrayPost['id_ubica_' . $apos], $arrayPost['calle_' . $apos], $arrayPost['nro_' . $apos], $arrayPost['piso_' . $apos], $arrayPost['dpto_' . $apos], $arrayPost['entre1_' . $apos], $arrayPost['entre2_' . $apos], $arrayPost['cp_' . $apos], 'C', $idCli);
                    $domNBSN = new DomicilioBSN($domBean);
                    $domNBSN->insertaDB();
                    break;
            }
        }
    }

    /**
     * Registra los datos de la busqueda del cliente en la base
     * @param type $arrayPost
     * @param type $idCli
     */
    public function grabaCRM($arrayPost, $idCli) {
        $crmpar = '';
        $crmtxt = '';
        $titAsto = '';
        $titEvto = '';
        $retorno = array();
        $nuevo = $arrayPost['actualiza'];

        unset($_SESSION['filtro']);
        if (isset($arrayPost['aux_prop'])) {
            if (($arrayPost['aux_prop'] == '' || $arrayPost['aux_prop'] == 0)) {
                $postTit = '';
                if (isset($arrayPost['aux_Tprop']) && $arrayPost['aux_Tprop'] != 0 && $arrayPost['aux_Tprop'] != '') {
                    $postTit.=(' Tipo Prop:' . $arrayPost['aux_Tprop'] . ' ');
                    $_SESSION['filtro']['fid_tipo_prop'] = $arrayPost['aux_Tprop'];
                    $_SESSION['filtro']['fid_seltipo_prop'] = $arrayPost['aux_selTprop'];
                    $crmpar.=('fid_tipo_prop->' . $arrayPost['aux_Tprop'] . '|');
                    $crmpar.=('fid_seltipo_prop->' . $arrayPost['aux_selTprop'] . '|');
                }
                if (isset($arrayPost['aux_seloperacion']) && $arrayPost['aux_seloperacion'] != '') {
                    $postTit.=(' Operacion:' . $arrayPost['aux_seloperacion'] . ' ');
                    $_SESSION['filtro']['foperacion'] = $arrayPost['aux_operacion'];
                    $_SESSION['filtro']['seloperacion'] = $arrayPost['aux_seloperacion'];
                    $crmpar.=('aux_operacion->' . $arrayPost['aux_operacion'] . '|');
                    $crmpar.=('aux_seloperacion->' . $arrayPost['aux_seloperacion'] . '|');
                    $crmpar.=('foperacion->' . $arrayPost['aux_operacion'] . '|');
                    $crmpar.=('seloperacion->' . $arrayPost['aux_seloperacion'] . '|');
                }

                if (isset($arrayPost['ID_UBICAPRINCIPAL']) && $arrayPost['ID_UBICAPRINCIPAL'] != 0 && $arrayPost['ID_UBICAPRINCIPAL'] != '') {
                    $postTit.=(' Zona:' . $arrayPost['ID_UBICAPRINCIPAL'] . ' ');
                    $_SESSION['filtro']['ID_UBICAPRINCIPAL'] = $arrayPost['ID_UBICAPRINCIPAL'];
                    $crmpar.=('ID_UBICAPRINCIPAL->' . $arrayPost['ID_UBICAPRINCIPAL'] . '|');
                }
                if (isset($arrayPost['fid_ubica']) && $arrayPost['fid_ubica'] != 0 && $arrayPost['fid_ubica'] != '') {
                    $postTit.=(' Localidad:' . $arrayPost['fid_ubica'] . ' ');
                    $_SESSION['filtro']['fid_ubica'] = $arrayPost['fid_ubica'];
                    $crmpar.=('fid_ubica->' . $arrayPost['fid_ubica'] . '|');
                }
                if (isset($arrayPost['fid_selloca']) && trim($arrayPost['fid_selloca']) != '') {
                    $postTit.=('Txt Localidad:' . $arrayPost['fid_selloca'] . ' ');
                    $_SESSION['filtro']['fid_selloca'] = $arrayPost['fid_selloca'];
                    $crmpar.=('fid_selloca->' . $arrayPost['fid_selloca'] . '|');
                }
                if (isset($arrayPost['aux_calle']) && $arrayPost['aux_calle'] != 0 && $arrayPost['aux_calle'] != '') {
                    $postTit.=(' Calle:' . $arrayPost['aux_calle'] . ' ');
                    $_SESSION['filtro']['fid_calle'] = $arrayPost['aux_calle'];
                    $crmpar.=('aux_calle->' . $arrayPost['aux_calle'] . '|');
                    $crmpar.=('fid_calle->' . $arrayPost['aux_calle'] . '|');
                }
                if (isset($arrayPost['aux_emp']) && $arrayPost['aux_emp'] != 0 && $arrayPost['aux_emp'] != '') {
                    $postTit.=(' Emprendimientos:' . $arrayPost['aux_emp'] . ' ');
                    $_SESSION['filtro']['fid_emp'] = $arrayPost['aux_emp'];
                    $_SESSION['filtro']['fid_selemp'] = $arrayPost['aux_selemp'];
                    $crmpar.=('aux_emp->' . $arrayPost['aux_emp'] . '|');
                    $crmpar.=('fid_emp->' . $arrayPost['aux_emp'] . '|');
                    $crmpar.=('fid_selemp->' . $arrayPost['aux_selemp'] . '|');
                }
                if (isset($arrayPost['DORMITORIOS']) && $arrayPost['DORMITORIOS'] != 0 && $arrayPost['DORMITORIOS'] != '') {
                    $postTit.=(' Dormitorios:' . $arrayPost['DORMITORIOS'] . ' ');
                    $_SESSION['filtro']['DORMITORIOS'] = $arrayPost['DORMITORIOS'];
                    $crmpar.=('DORMITORIOS->' . $arrayPost['DORMITORIOS'] . '|');
                }

                if ($postTit != '') {
                    $titAsto = 'Busqueda propiedades ' . $postTit;
                    if ($nuevo == 1) {
                        $titEvto = 'Busqueda propiedades ' . $postTit;
                    } else {
                        $titEvto = 'Ajuste de Busqueda de propiedades ' . $postTit;
                    }
                }
                if (isset($arrayPost['adjuntos'])) {
                    $adjuntos = str_replace('"', '', $arrayPost['adjuntos']);
                    $adjuntos = str_replace('{', '', $adjuntos);
                    $adjuntos = str_replace('}', '', $adjuntos);
                    $arrayElems = explode(",", $adjuntos);
                    foreach ($arrayElems as $elem) {
                        if (trim($elem != '')) {
                            $dato = explode(":", $elem);
                            $arrayRet[trim($dato[0])] = trim($dato[1]);
                        }
                    }
                    $_SESSION['adjuntos'] = $arrayRet;
                }
            } else {
                $titAsto = 'Informacion propiedad ' . $arrayPost['aux_prop'];
                $_SESSION['filtro']['fid_codigo'] = $arrayPost['aux_prop'];
                $crmpar.=('aux_prop->' . $arrayPost['aux_prop'] . '|');
                $crmpar.=('fid_codigo->' . $arrayPost['aux_prop']);
            }
            $fecAsto = date('d/m/Y');
            if (!isset($arrayPost['id_asunto'])) {
                if (!isset($arrayPost['id_crm']) || $arrayPost['id_crm'] == 0 || $arrayPost['id_crm'] == '' || $nuevo == 1) {
                    $arrayPost['id_asunto'] = date('YmdHis');
                    $id_evento = date('YmdHis');
                } else {
                    $arrayPost['id_asunto'] = $arrayPost['id_crm'];
                    $id_evento = date('YmdHis');
                }
            }
            $astoBean = new Asunto($arrayPost['id_asunto'], $idCli, 1, $titAsto, $fecAsto, '', '', $_SESSION['UserId'], 0, '');
            $astoBSN = new AsuntoBSN($astoBean);
            if ($nuevo == 1) {
                $retAs = $astoBSN->insertaDB();
            } else {
                $retAs = $astoBSN->actualizaDB();
            }

            if ($nuevo == 1) {
                $tareaBean = new Evento($id_evento, $arrayPost['id_asunto'], $titEvto, '', 8, date('d-m-Y'), '', $_SESSION['UserId']);
                $tareaBSN = new EventoBSN($tareaBean);
                $retTar = $tareaBSN->insertaDB();
                if($retTar){
                    $evcom=new Eventocomponente(0,$id_evento,$idCli,'C');
                    $evcomBSN=new EventocomponenteBSN($evcom);
                    $evcomBSN->insertaDB();
                }
            }else{
                $retTar=true;
            }


            $crmBean = new Crmbuscador($arrayPost['id_asunto'], $crmpar, $postTit, $adjuntos);
            $crmBSN = new CrmbuscadorBSN($crmBean);
            if ($nuevo == 1) {
                $retCrm = $crmBSN->insertaDB();
            } else {
                $retCrm = $crmBSN->actualizaDB();
            }

            if ($retAs && $retTar && $retCrm) {
                $retorno = array('codRet' => '0', 'msg' => 'Se proceso correctamente el registro de los datos de la busqueda', 'idCrm' => $arrayPost['id_asunto']);
            } else {
                $retorno = array('codRet' => '1', 'msg' => 'Fallo el registro de los datos de la busqueda');
            }
        } else {
            $retorno = array('codRet' => '-1', 'msg' => 'Acceso invalido');
        }
        return $retorno;
    }

    /**
     * Registra las modificaciones de los datos del cliente
     */
    protected function modificaDatosCliente($arrayPost, $idCli) {
        $arrClaves = array_keys($arrayPost);
        foreach ($arrClaves as $dato) {
            switch (substr($dato, 0, 5)) {
                case ('id_te'):
                    $apos = substr($dato, strlen('id_telefono_'));
                    if (isset($arrayPost['numero_' . $apos]) && $arrayPost['numero_' . $apos] != '' && $arrayPost['numero_' . $apos] != 0) {
                        $telBean = new Telefonos($arrayPost['id_telefono_' . $apos], $arrayPost['tipotel_' . $apos], $arrayPost['codpais_' . $apos], $arrayPost['codarea_' . $apos], $arrayPost['numero_' . $apos], $arrayPost['interno_' . $apos], 'C', $idCli);
                        $telBSN = new TelefonosBSN($telBean);
                        if ($arrayPost['id_telefono_' . $apos] == 0) {
                            $telBSN->insertaDB();
                        } else {
                            $telBSN->actualizaDB();
                        }
                    }
                    break;
                case ('id_me'):
                    $apos = substr($dato, strlen('id_medio_'));
                    if (isset($arrayPost['contacto_' . $apos]) && $arrayPost['contacto_' . $apos] != '') {
                        $medBean = new Medioselectronicos($arrayPost['id_medio_' . $apos], $arrayPost['id_tipomed_' . $apos], $arrayPost['contacto_' . $apos], '', 'C', $idCli);
                        $medBSN = new MedioselectronicosBSN($medBean);
                        if ($arrayPost['id_medio_' . $apos] == 0) {
                            $medBSN->insertaDB();
                        } else {
                            $medBSN->actualizaDB();
                        }
                    }
                    break;
                case ('id_fa'):
                    $apos = substr($dato, strlen('id_fam_'));
                    $parBean = new Familiares($arrayPost['id_fam_' . $apos], $arrayPost['fecha_nac_' . $apos], $idCli, $arrayPost['nombre_' . $apos], $arrayPost['apellido_' . $apos], $arrayPost['id_parent_' . $apos], '', 'C');
                    $parBSN = new FamiliaresBSN($parBean);
                    if ($arrayPost['id_fam_' . $apos] == 0) {
                        $parBSN->insertaDB();
                    } else {
                        $parBSN->actualizaDB();
                    }
                    break;
                case ('id_pc'):
                    $apos = substr($dato, strlen('id_pc_'));
                    $relBean = new Relacion($arrayPost['id_pc_' . $apos], $idCli, $arrayPost['id_relacion_' . $apos]);
                    $relBA = new RelacionBSN();
                    $colRel = $relBA->coleccionRelacionesUC($arrayPost['id_pc_' . $apos], $idCli);
                    foreach ($colRel as $relacion) {
                        if ($relacion['id_pc'] == $arrayPost['id_pc_' . $apos] && $relacion['id_relacion'] != $arrayPost['id_relacion_' . $apos]) {
                            $relA = new Relacion($relacion['id_pc'], $relacion['id_sc'], 0);
                            $relBA->borraDB($relA);
                            break;
                        }
                    }
                    $relNBSN = new RelacionBSN($relBean);
                    $relNBSN->insertaDB();
                    break;
                case ('id_do'):
                    $apos = substr($dato, strlen('id_dom_'));
                    $domBean = new Domicilio($arrayPost['id_dom_' . $apos], $arrayPost['tipo_' . $apos], $arrayPost['id_ubica_' . $apos], $arrayPost['calle_' . $apos], $arrayPost['nro_' . $apos], $arrayPost['piso_' . $apos], $arrayPost['dpto_' . $apos], $arrayPost['entre1_' . $apos], $arrayPost['entre2_' . $apos], $arrayPost['cp_' . $apos], 'C', $idCli);
                    $domNBSN = new DomicilioBSN($domBean);
                    if ($arrayPost['id_dom_' . $apos] == 0) {
                        $domNBSN->insertaDB();
                    } else {
                        $domNBSN->actualizaDB();
                    }
                    break;
            }
        }
    }

    public function actualizaDB() {
        $retorno = parent::actualizaDB();
        if ($retorno) {
            $this->modificaDatosCliente($_POST, $_POST['ID_CLI']); //$this->getObjeto()->getId_cli());
//            $this->grabaCRM($_POST, $_POST['ID_CLI']); //$this->getObjeto()->getId_cli());
        }
//        $this->actualizaCacheCliente($this->getObjeto()->getId_cli(), 'a');
        $this->actualizaCacheCliente($_POST['ID_CLI'], 'a');
        return $retorno;
    }

    protected function actualizaCacheCliente($id, $operacion) {
        $cliC = ClienteCache::getInstance();
        $cliC->actualizaCache($id, $operacion);
    }

    public function cargaByUsuario($_usuario) {
        $login = new Cliente();
        $login->setUsuario($_usuario);
        $loginBSN = new ClienteBSN($login);
        $datoDB = new ClientePGDAO($loginBSN->getArrayTabla());
        $arrayDB = $this->leeDBArray($datoDB->findByClave());
        $this->cliente = $this->mapa->tablaTOobj($arrayDB[0]);
    }

    public function controlLogin($_usuario, $_clave) {
        $retorno = 0;
        $this->cargaByUsuario($_usuario);

        if ($this->cliente->getUsuario() == "" || $this->cliente->getActiva() == 0) {
            echo "Usuario Inexistente";
        } else {
            $log = new Cliente();
            $log = $this->cliente;
            $result = $log->validaLogin($_clave);
            /*
             * -3 	clave bloqueada por exceso de intentos fallidos
             * -2 	clave expirada por fecha
             * -1 	error de ingreso
             *  0	ingreso valido con clave original
             *  1 	ingreso valido con clave modificada
             */
            switch ($result) {
                case -3:
//					echo "Usuario Bloqueado por intentos Fallidos";
                    break;
                case -2:
//					echo "Clave expirada por fecha caduca";
                    break;
                case -1:
//					echo "Error al ingresar la clave";
                    break;
                case 0:
                    $retorno = $log->getId_cli();
//					echo "Ingreso al sistema autorizado";
                    break;
                case 1:
                    $retorno = $log->getId_cli();
//					echo "Bien venido al sistema";
            }
            $logBSN = new ClienteBSN($log);
            if (!$logBSN->actualizaDB()) {
                echo "fallo la actualizacion de cambios";
            }
        }
        return $retorno;
    }

    /**
     * Metodo que registra una solicitud de cambio de clave por Olvido
     *
     * @return boolean
     */
    public function solicitudcambioClave() {
        $_clave = $this->generaClave();
        $log = new Cliente();
        $log = $this->cliente;
//		print_r($log);echo "<br>";
//		$_claveEnc=$this->encriptaClave($_clave);
//		$this->cliente->setNueva_clave($_claveEnc);
        $log->setFecha_nueva(date("d-m-Y"));
        $log->setNueva_clave($_clave);
        $this->cliente = $log;
        $logBSN = new ClienteBSN($this->cliente);
        $retorno = $logBSN->actualizaDB();
        if ($retorno) {
            $retorno = $_clave;
        }
        return $retorno;
    }

    /**
     * Metodo que registra un cambio de clave por por el usuario
     *
     * @return boolean
     */
    public function ingresocambioClave($_clave) {
        $log = new Cliente();
        $log = $this->cliente;
        $log->setClave($_clave);
        $log->setFecha_base(date("d-m-Y"));
        $log->limpiaNuevaClave();
        $this->cliente = $log;
        $logBSN = new ClienteBSN($this->cliente);
        $retorno = $logBSN->actualizaDB();
        return $retorno;
    }

    /**
     * Metodo que confirma la modificacion de la clave
     *
     * @return boolean
     */
    protected function confirmacambioClave() {
        $retorno = $this->actualizaDB();
        return $retorno;
    }

    public function controlDuplicado() {
        $retorno = false;
        $datoDB = new ClientePGDAO($this->getArrayTabla());
        $arrayDB = $this->leeDBArray($datoDB->findByClave());
        if (array_key_exists(0, $arrayDB) && sizeof($arrayDB[0]) > 0) {
            $retorno = true;
        }
        return $retorno;
    }

    private function buscaID_usuario($_usuario) {
        $retorno = false;
        $login = new Cliente();
        $login->setUsuario($_usuario);
        $arrayTabla = $this->mapa->objTOtabla($login);
        $loginDB = new ClientePGDAO($arrayTabla);
        $array = $this->leeDBArray($loginDB->findByNombre());
        $retorno = $array[0]["id_cli"];
        return $retorno;
    }

    public function retornaClave($_id) {
        return $this->cliente->getId_cli();
    }

    public function activarUsuario() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new ClientePGDAO($arrayTabla);
        $retorno = $propDB->activar();
        return $retorno;
    }

    public function desactivarUsuario() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new ClientePGDAO($arrayTabla);
        $retorno = $propDB->desactivar();
        return $retorno;
    }

    protected function generaClave() {
        $_clave = "";
        for ($x = 0; $x < 8; $x++) {
            $rand = rand(1, 3);
            switch ($rand) {
                case 1:
                    $min = 48;
                    $max = 57;
                    break;
                case 2:
                    $min = 65;
                    $max = 90;
                    break;
                case 3:
                    $min = 97;
                    $max = 122;
                    break;
            }
            $_clave.=chr(rand($min, $max));
        }
        return $_clave;
    }

    protected function encriptaClave($_clave) {
        return md5($_clave);
    }

    /**
     * Metodo para la carga de datos privados de la aplicaci�n de login, que no son 
     * mostrados al usuario pero deben ser mantenidos ante una modificaci�n de datos b�sicos
     *
     * @return estado de finalizacion de la operacion
     */
    public function cargaPrivados() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new ClientePGDAO($arrayTabla);
        $result = $propDB->findById();
        $array = $this->leeDBArray($result);
        $this->cliente->setClave($array[0]["clave"]);
        $this->cliente->setFecha_base($array[0]["fecha_base"]);
        $this->cliente->setNueva_clave($array[0]["nueva_clave"]);
        $this->cliente->setFecha_nueva($array[0]["fecha_nueva"]);
        $this->cliente->setErrores($array[0]["errores"]);
        return $retorno;
    }

    public function comboUsuarios($valor = '', $campo = "id_cli", $class = "campos_btn") {
        $perfil = $this->cargaColeccionForm();
        print "<select name='" . $campo . "' id='" . $campo . "' class='" . $class . "'>\n";
        print "<option value='0'";
        if ($valor == '') {
            print " SELECTED ";
        }
        print ">Seleccione una opcion</option>\n";

        for ($pos = 0; $pos < sizeof($perfil); $pos++) {
            print "<option value='" . $perfil[$pos]['id_cli'] . "'";
            if ($perfil[$pos]['id_cli'] == $valor) {
                print " SELECTED ";
            }
            //    print ">" . $perfil[$pos]['id_cli'] . " - " . $perfil[$pos]['nombre'] . " " . $perfil[$pos]['apellido'] . "</option>\n";
            print ">" . $perfil[$pos]['apellido'] . ", " . $perfil[$pos]['nombre'] . " &lt;" . $perfil[$pos]['email'] . "&gt;</option>\n";
        }
        print "</select>\n";
    }

    /**
     * Arma una coleccion de cliente basado en un filtro definido por nombre y apellido
     * @param string $filtro -> string que define el patron a buscar
     */
    public function coleccionClientesFiltrados($filtro = '', $pos = 1) {
        $arrayRet = array();
        $cliDB = new ClientePGDAO();
        $arrayRet = $this->leeDBArray($cliDB->coleccionFiltrada($filtro, $pos));
        return $arrayRet;
    }

    public function autocompletarClientes($campoBusq = 'buscaCli', $campoVal = 'id_cli') {
        print "<input type='text' size='50' name='$campoBusq' id='$campoBusq'>";
        print "<br> * Pon al menos 3 letras para que salgan opciones.";
        print "<input type='hidden' size='50' name='$campoVal' id='$campoVal'>";
    }

    public function buscaDescripcionCliente() {
        $desc = '';
        $desc = $this->cliente->getNombre() . " " . $this->cliente->getApellido();
        return $desc;
    }
    
    public function buscaDetalleCliente($id) {
        $retorno = '';
        $telBSN = new TelefonosBSN();
        $tel = $telBSN->listaTelefonosByCliente($id);
        $medElec=new MedioselectronicosBSN();
        $medio=$medElec->listaMedioselectronicosByCliente($id);
        
        if ($medio != '') {
            $retorno = "Contcto: " . $medio;
        }
        if ($tel != '') {
            if ($retorno != '') {
                $retorno.=' - ';
            }
            $retorno.="Tel: " . $tel;
        }

        return $retorno;
    }
    
}

// Fin clase
?>