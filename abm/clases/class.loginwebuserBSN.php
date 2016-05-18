<?php

include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");

include_once("clases/class.loginwebuser.php");
include_once("clases/class.loginwebuserPGDAO.php");
include_once("clases/class.fechas.php");
include_once('clases/class.perfileswebuserPGDAO.php');

class LoginwebuserBSN extends BSN {

    protected $clase = "Loginwebuser";
    protected $nombreId = "Id_user";
    protected $loginwebuser;

    public function __construct($_login = "") {
        LoginwebuserBSN::seteaMapa();
        if ($_login instanceof Loginwebuser) {
            LoginwebuserBSN::creaObjeto();
            LoginwebuserBSN::seteaBSN($_login);
        } else {
            if (is_string($_login)) {
                LoginwebuserBSN::creaObjeto();
                if ($_login != "") {
                    if (is_numeric($_login)) {
                        LoginwebuserBSN::cargaById($_login);
                    } else {
                        LoginwebuserBSN::cargaByUsuario($_login);
                    }
                }
            }
        }
        LoginwebuserBSN::setTimezone();
    }

    public function getId() {
        return $this->loginwebuser->getId_user();
    }

    public function setId($id) {
        $this->loginwebuser->setId_user($id);
    }

    public function cargaByUsuario($_usuario) {
        $login = new Loginwebuser();
        $login->setUsuario($_usuario);
        $loginBSN = new LoginwebuserBSN($login);
        $datoDB = new LoginwebuserPGDAO($loginBSN->getArrayTabla());
        $arrayDB = $this->leeDBArray($datoDB->findByClave());
        $this->loginwebuser = $this->mapa->tablaTOobj($arrayDB[0]);
    }

    public function controlLogin($_usuario, $_clave) {
        $retorno = 0;
        $this->cargaByUsuario($_usuario);

        if ($this->loginwebuser->getUsuario() == "" || $this->loginwebuser->getActiva() == 0) {
            echo "Usuario Inexistente";
        } else {
            $logUsr = new Loginwebuser();
            $logUsr = $this->loginwebuser;
            $result = $logUsr->validaLogin($_clave);
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
                    $retorno=$result;
                    break;
                case -2:
//					echo "Clave expirada por fecha caduca";
                    $retorno=$result;
                    break;
                case -1:
//					echo "Error al ingresar la clave";
                    $retorno=$result;
                    break;
                case 0:
                    $retorno = $logUsr->getId_user();
//					echo "Ingreso al sistema autorizado";
                    break;
                case 1:
                    $retorno = $logUsr->getId_user();
//					echo "Bien venido al sistema";
            }
            $this->loginwebuser = $logUsr;
            if ($result < 0) {
                $this->registraLog(0,'Ingreso',$retorno,$_usuario.' - '.$_clave.' - '.$this->loginwebuser->getErrores());
                if (!$this->actualizaDB()) {
                    echo "fallo la actualizacion de cambios";
                }
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
        $log = new Loginwebuser();
        $log = $this->loginwebuser;
//		print_r($log);echo "<br>";
//		$_claveEnc=$this->encriptaClave($_clave);
//		$this->loginwebuser->setNueva_clave($_claveEnc);
        $log->setFecha_nueva(date("d-m-Y"));
        $log->setNueva_clave($_clave);
        $this->loginwebuser = $log;
        $logBSN = new LoginwebuserBSN($this->loginwebuser);
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
        $log = new Loginwebuser();
        $log = $this->loginwebuser;
        $log->setClave($_clave);
        $log->setFecha_base(date("d-m-Y"));
        $log->limpiaNuevaClave();
        $this->loginwebuser = $log;
        $logBSN = new LoginwebuserBSN($this->loginwebuser);
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

    private function borraPerfilWU($_id) {
        $perf = new PerfileswebuserPGDAO();
        $retorno = $perf->deleteUW_Perfiles($_id);
        return $retorno;
    }

    private function grabaPerfilWU($_perfiles, $_id) {
        $perf = new PerfileswebuserPGDAO();
        $retorno = true;
        foreach ($_perfiles as $perfil) {
            $ret = $perf->insertUW_Perfiles($_id, $perfil);
            $retorno = $retorno && $ret;
        }
        return $retorno;
    }

    public function controlDuplicado() {
        $retorno = false;
        $datoDB = new LoginwebuserPGDAO($this->getArrayTabla());
        $arrayDB = $this->leeDBArray($datoDB->findByClave());
        if (sizeof($arrayDB[0]) > 0) {
            $retorno = true;
        }
        return $retorno;
    }

    private function buscaID_usuario($_usuario) {
        $retorno = false;
        $login = new Loginwebuser();
        $login->setUsuario($_usuario);
        $arrayTabla = $this->mapa->objTOtabla($login);
        $loginDB = new LoginwebuserPGDAO($arrayTabla);
        $array = $this->leeDBArray($loginDB->findByNombre());
        $retorno = $array[0]["id_user"];
        return $retorno;
    }

    public function retornaClave($_id) {
        return $this->loginwebuser->getId_user();
    }

    public function desbloquearUsuario() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new LoginwebuserPGDAO($arrayTabla);
        $retorno = $propDB->desbloquear();
        $this->registraLog(0, 'Desbloqueo', 'Ok', $this->loginwebuser->__toString());
        return $retorno;
    }

    public function activarUsuario() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new LoginwebuserPGDAO($arrayTabla);
        $retorno = $propDB->activar();
        return $retorno;
    }

    public function desactivarUsuario() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $propDB = new LoginwebuserPGDAO($arrayTabla);
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
        $propDB = new LoginwebuserPGDAO($arrayTabla);
        $result = $propDB->findById();
        $array = $this->leeDBArray($result);
        $this->loginwebuser->setClave($array[0]["clave"]);
        $this->loginwebuser->setFecha_base($array[0]["fecha_base"]);
        $this->loginwebuser->setNueva_clave($array[0]["nueva_clave"]);
        $this->loginwebuser->setFecha_nueva($array[0]["fecha_nueva"]);
        $this->loginwebuser->setErrores($array[0]["errores"]);
        return $retorno;
    }

    public function comboUsuarios($valor = '', $campo = "id_user", $class = "campos_btn") {
        $perfil = $this->cargaColeccionForm();
        print "<select name='" . $campo . "' id='" . $campo . "' class='" . $class . "'>\n";
        print "<option value='0'";
        if ($valor == '') {
            print " SELECTED ";
        }
        print ">Seleccione una opcion</option>\n";

        for ($pos = 0; $pos < sizeof($perfil); $pos++) {
            print "<option value='" . $perfil[$pos]['id_user'] . "'";
            if ($perfil[$pos]['id_user'] == $valor) {
                print " SELECTED ";
            }
            print ">" . $perfil[$pos]['id_user'] . " - " . $perfil[$pos]['nombre'] . " " . $perfil[$pos]['apellido'] . "</option>\n";
        }
        print "</select>\n";
    }


    public function comboUsuariosMultiple($valor = '', $campo = "id_user", $class = "campos_btn") {
        $perfil = $this->cargaColeccionForm();
        $cont = array_map('intval', explode(",", $valor));
        print "<select name='" . $campo . "[]' id='" . $campo . "' class='" . $class . "' multiple size=\"5\" style=\"height: auto;\" >\n";
        print "<option value='0'";
        if ($valor == '') {
            print " SELECTED ";
        }
        print ">Seleccione una opcion</option>\n";

        for ($pos = 0; $pos < sizeof($perfil); $pos++) {
            print "<option value='" . $perfil[$pos]['id_user'] . "'";
            if (in_array($perfil[$pos]['id_user'], $cont)) {
                print " SELECTED ";
            }
            print ">" . $perfil[$pos]['nombre'] . " " . $perfil[$pos]['apellido'] . "</option>\n";
        }
        print "</select>\n";
        print "<br />Puede seleccionar varias opciones presionando CTRL (en Windows) - CMD (en MAC) ";
    }

    
    public function buscaDescripcionUsuario() {
        $desc = '';
        $desc = $this->loginwebuser->getNombre() . " " . $this->loginwebuser->getApellido();
        return $desc;
    }

    public function buscaDetalleUsuario($id) {
        $retorno = '';
        $telBSN = new TelefonosBSN();
//        $medElec=new MedioselectronicosBSN();
        $tel = $telBSN->listaTelefonosByUsuarios($id);
//        $medio=$medElec->listaMedioselectronicosByCliente($id);
        $userBSN = new LoginwebuserBSN();
        $userBSN->cargaById($id);
        $desc = $userBSN->getObjeto()->getEmail();
        if ($desc != '') {
            $retorno = "eMail: " . $desc;
        }
  //      if ($medio != '') {
  //          $retorno = "Contcto: " . $medio;
  //      }
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