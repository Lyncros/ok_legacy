<?php

/**
 * Clase de con la logica de negocio para el Login
 *
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");

include_once("clases/class.login.php");
include_once("clases/class.loginPGDAO.php");
include_once("clases/class.fechas.php");

class LoginBSN {

    private $clase = "Login";
    private $nombreId = "Id_user";
    private $login;

    public function __construct($_login = "") {
        if ($_login instanceof Login) {
            LoginBSN::creaLogin();
            LoginBSN::seteaLoginBSN($_login);
        } else {
            if (is_string($_login)) {
                LoginBSN::creaLogin();
                if ($_login != "") {
                    if (is_numeric($_login)) {
                        LoginBSN::cargaLoginId($_login);
                    } else {
                        LoginBSN::cargaLoginUsuario($_login);
                    }
                }
            }
        }
        $conf = CargaConfiguracion::getInstance();
        $timezone = $conf->leeParametro('timezone');
        date_default_timezone_set($timezone);
    }

    public function getId() {
        return $this->login->getId_user();
    }

    public function setId($id) {
        $this->login->setId_user($id);
    }

    private function creaLogin() {
        $this->login = new Login();
    }

    /**
     * Setea los valores del Login Local con los del objeto pasado como parametro
     *
     * @param objeto tipo Login
     */
    private function seteaLoginBSN($_login) {
        $this->login->setId_usuario($_login->getId_user());
        $this->login->setUsuario($_login->getUsuario());
        $this->login->setClave($_login->getClave());
        $this->login->setValidez($_login->getValidez());
        $this->login->setFecha_base($_login->getFecha_base());
        $this->login->setErrores($_login->getErrores());
        $this->login->setNueva_clave($_login->getNueva_clave());
        $this->login->setFecha_nueva($_login->getFecha_nueva());
        $this->login->setMaxfallos($_login->getMaxfallos());
        $this->login->setValidez_nueva($_login->getValidez_nueva());
    }

    /**
     * Instancia un objeto Login con los datos leidos desde la base de datos
     *
     * @param rowset
     * @return objeto Login
     */
    protected function leeLoginDB($_result) {
        $arrayDB = $this->leeDBArray($_result);
        $this->login = $this->mapa->tablaTOobj($arrayDB[0]);
        /*
          while ($row = pg_fetch_array($_result)){
          $this->login->setUsuario($row['usuario']);
          $this->login->setClave($row['clave']);
          //			$this->login->setValidez($row["validez"]);
          $this->login->setFecha_base($row["fecha_base"]);
          $this->login->setErrores($row["errores"]);
          $this->login->setNueva_clave($row["nueva_clave"]);
          $this->login->setFecha_nueva($row["fecha_nueva"]);
          //  		$this->login->setMaxfallos($row["maxfallos"]);
          //			$this->login->setValidez_nueva($row["validez_nueva"]);
          }
         */
    }

    protected function cargaLoginUsuario($_usuario) {
        $loginDB = new LoginPGDAO();
        $this->leeLoginDB($loginDB->findByNombre($_usuario));
    }

    public function controlLogin($_usuario, $_clave) {
        $retorno = false;
        $this->cargaLoginUsuario($_usuario);

        if ($this->login->getUsuario() == "") {
            echo "Usuario Inexistente";
        } else {
            $result = $this->login->validaLogin($_clave);
            echo "Result :" . $result;
            /*
             * -3 	clave bloqueada por exceso de intentos fallidos
             * -2 	clave expirada por fecha
             * -1 	error de ingreso
             *  0	ingreso valido con clave original
             *  1 	ingreso valido con clave modificada
             */
            switch ($result) {
                case -3:
                    echo "Usuario Bloqueado por intentos Fallidos";
                    break;
                case -2:
                    echo "Clave expirada por fecha caduca";
                    break;
                case -1:
                    echo "Error al ingresar la clave";
                    break;
                case 0:
                    $retorno = true;
                    echo "Ingreso al sistema autorizado";
                    break;
                case 1:
                    $retorno = true;
                    echo "Bien venido al sistema";
//					$this->confirmacambioClave();		
            }
            if (!$this->actualizaCambios()) {
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
        $_claveEnc = $this->encriptaClave($_clave);
        $this->login->setNueva_clave($_claveEnc);
        $this->login->setFecha_nueva(date("d-m-Y"));
        $retorno = $this->actualizaCambios();
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
        $this->login->setClave($_clave);
        $this->login->setFecha_base(date("d-m-Y"));
        $this->login->limpiaNuevaClave();
        $retorno = $this->actualizaCambios();
        return $retorno;
    }

    /**
     * Metodo que confirma la modificacion de la clave
     *
     * @return boolean
     */
    protected function confirmacambioClave() {
        $retorno = $this->actualizaCambios();
        return $retorno;
    }

    private function actualizaCambios() {
        $loginDB = new LoginPGDAO();
        $_id = $this->buscaID_usuario($this->login->getUsuario());
        $retorno = $loginDB->updateLogin($_id, $this->login->getUsuario(), $this->login->getClave(), $this->login->getFecha_base(), $this->login->getNueva_clave(), $this->login->getFecha_nueva(), $this->login->getErrores());
        return $retorno;
    }

    private function buscaID_usuario($_usuario) {
        $retorno = false;
        $loginDB = new LoginPGDAO();
        $result = $loginDB->findByNombre($this->login->getUsuario());
        while ($row = pg_fetch_array($result)) {
            $retorno = $row["id_usuario"];
        }
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

}

// Fin Clase LoginBSN
?>