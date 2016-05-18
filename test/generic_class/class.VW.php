<?php

/**
 * Clase Basica para la definicion de Vistas de datos
 */
include_once("generic_class/class.cargaConfiguracion.php");

class VW {

	protected $arrayForm;
	protected $formName;
	protected $opcionName;

	protected function cargaDefinicionForm(){
		$conf=CargaConfiguracion::getInstance();
		$this->formName=$conf->leeParametro("formmenu");
		$this->opcionName=$conf->leeParametro("opcionmenu");
	}

	/**
	 * Retorna el nombre de la clase que se esta utilizando
	 * @return string con el nombre de la clase en uso
	 */
	protected function getClase(){
		return $this->clase;
	}

	// Retorna el nombre del Objeto definio en la clase propia
	protected function getNombreObjeto(){
		return strtolower($this->clase);
	}

	public function creaObjeto(){
		$objeto=$this->getNombreObjeto();
		$clase=$this->getClase();
		$this->{$objeto}=new $clase();
	}

	public function cargaVW($dato){
		$clase=$this->getClase();
		$objeto=strtolower($clase);
		$objBSN = $clase."BSN";
		$BSN = new $objBSN($dato);
		$this->seteaVW($BSN->getObjeto());
	}

	/**
	 * Setea el contenedor de los elementos de la vista
	 * @param unknown_type $dato
	 */
	public function seteaVW($dato){
		$clase=$this->getClase();
		$objeto=strtolower($clase);
		$this->{$objeto}=$dato;
		$objBSN = $clase."BSN";
		$BSN = new $objBSN($dato);
		$this->arrayForm = $BSN->getObjetoView();
	}

	/**
	 * Retorna el objeto de la vista
	 */
	public function getVW(){
		$objeto=$this->getNombreObjeto();
		return $this->{$objeto};
	}

	/**
	 * Retorna el Id del objeto en uso
	 */
	public function getId(){
		$objeto=$this->getNombreObjeto();
		$getid = 'get'.$this->nombreId;
		return $this->{$objeto}->{$getid}();
	}

	/**
	 * Graba la modificacion de los datos recolectados desde la vista
	 * @return retorno del estado de la modificacion de los datos
	 */
	public function grabaModificacion(){
		$clase=$this->getClase();
		$objeto=strtolower($clase);
		$objBSN = $clase."BSN";
		$BSN = new $objBSN($this->{$objeto});
		$retAWU = $BSN->actualizaDB();
		return $retAWU;
    }

    public function grabaModificacionNew() {
        $clase = $this->getClase();
        $objeto = strtolower($clase);
        $objBSN = $clase . "BSN";
        $BSN = new $objBSN($this->{$objeto});
        $retAWU = $BSN->actualizaDB();
        if ($retAWU) {
            $retorno = array('codRet' => '0', 'msg' => 'Se proceso correctamente el registro de los datos');
        } else {
            $retorno = array('codRet' => '1', 'msg' => 'Fallo el registro de los datos');
	}
        return $retorno;
    }

	/**
	 * Graba los datos recolectados desde la vista como un registro nuevo.
	 * @param bool control -> indica si se hace control de duplicados
	 * @return boolean estado del retorno de la grabacion de los datos
	 */
	public function grabaDatosVW($control='false'){
		$retCont=false;
		$retorno=false;
		$clase=$this->getClase();
		$objeto=strtolower($clase);
		$objBSN = $clase."BSN";
		$BSN = new $objBSN($this->{$objeto});
		if($control){
			$retCont=$BSN->controlDuplicado();
		}
		if($retCont){
            echo "Ya existe un " . $objeto . " elemento con esa Identificacion";
		} else {
			$retIWU=$BSN->insertaDB();
			if ($retIWU){
				echo "Se proceso la grabacion en forma correcta<br>";
				$retorno=true;
			} else {
				echo "Fallo la grabaci�n de los datos<br>";
			}
		}
		return $retorno;
	}

    public function grabaDatosVWNew($control = 'false') {
        $retCont = false;
        $retorno = array();
        $clase = $this->getClase();
        $objeto = strtolower($clase);
        $objBSN = $clase . "BSN";
        $BSN = new $objBSN($this->{$objeto});
        if ($control) {
            $retCont = $BSN->controlDuplicado();
        }
        if ($retCont) {
            $retorno = array('codRet' => '2', 'msg' => 'Ya existe un ' . $objeto . ' elemento con esa Identificacion');
        } else {
            $retIWU = $BSN->insertaDB();
            if ($retIWU) {
                $retorno = array('codRet' => '0', 'msg' => 'Se proceso correctamente el registro de los datos');
//                            $retorno=true;
            } else {
                $retorno = array('codRet' => '1', 'msg' => 'Fallo el registro de los datos');
            }
        }
        return $retorno;
    }

	/**
	 * Lee los datos del formulario y los carga en el objeto en uso
	 */
	public function leeDatosVW(){
		$clase=$this->getClase();
		$objeto=strtolower($clase);
		$objBSN = $clase."BSN";
		$BSN = new $objBSN();
		$this->{$objeto}=$BSN->leeDatosForm($_POST);
		//		$contactoBSN=new ContactoBSN();
		//		$this->contacto=$contactoBSN->leeDatosForm($_POST);
	}

    protected function borraImagen($_nombre) {
        $nombre = $this->path . "/" . $_nombre;
        if (file_exists($nombre)) {
            unlink($nombre);
        }
        $nombre = $this->pathC . "/" . $_nombre;
        if (file_exists($nombre)) {
            unlink($nombre);
        }
    }

    protected function uploadImagen($campo, $id, $prefijo) {
        $retorno = '';
//        echo "<br>***$campo***<br>";
        if ($_FILES[$campo]['type'] == 'image/jpeg' || $_FILES[$campo]['type'] == 'image/gif' || $_FILES[$campo]['type'] == 'image/png') {
            $fotoup = new Upload($_FILES[$campo], 'es_ES');
            $nom = $_FILES[$campo]['name'];

            $nombre = $prefijo . '_' . $id . '_' . substr($nom, 0, -4);
//            echo "<br>$nombre<br>";
            if ($fotoup->uploaded) {
                $fotoup->image_resize = true;
                $fotoup->image_ratio_y = true;
                $fotoup->file_new_name_body = $nombre;

                $fotoup->image_x = $this->anchoG;

                $fotoup->Process($this->path);

                // we check if everything went OK
                if ($fotoup->processed) {
                    
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $fotoup->error . '';
                    echo '</fieldset>';
}

                $fotoup->image_x = $this->anchoC;
                $fotoup->file_new_name_body = $nombre;

                $fotoup->Process($this->pathC);

                // we check if everything went OK
                if ($fotoup->processed) {
                    
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $fotoup->error . '';
                    echo '</fieldset>';
                }
            } else {
                echo '<fieldset>';
                echo '  <legend>file not uploaded on the server</legend>';
                echo '  Error: ' . $fotoup->error . '';
                echo '</fieldset>';
            }
            $retorno = $fotoup->file_dst_name;
        }
        return $retorno;
    }

}

?>