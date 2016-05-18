<?php

/**
 * Clase Basica para la definicion de la logica de negocios.
 * Utiliza una variable propia de la clase que la hereda llamada "clase" que define la base del nombre
 * del elemento que representa.
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
 *
 * 07/10/2011
 * Se agrega la registracion del log como tarea generica dentro de esta clase
 * Esta clase se acompa�a de un archivo php llamado logedObjects.php en el cual se definen a modo de array
 * aquellas clases de la aplicacion a las cuales por default se les aplica un logueo ante cualquier movimiento
 * en la base de datos (INSERT, UPDATE o DELETE) independientemente de aquellas otras opciones que se quierann loguear
 * El modelo del array es
 * 						 $objetos=array(clase=>etiqueta, .....);
 * Donde
 * 		clase = al nombre definido en la variable Clase en claseBSN ( Ej. en ClienteBSN -> Cliente )
 * 		etiqueta = un string que se va autilizar para definir como se identificara en el log, la clase a la cual se hace referencia
 *
 * Se agregan al final, modelos de los metodos a definir en las clases propias
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.maper.php");
include_once("generic_class/class.cargaConfiguracion.php");

class BSN {

    protected $mapa;
    protected $logedObjects;
    protected $logedFile = 'logedObjects.php';

    public function setTimezone() {
        $conf = CargaConfiguracion::getInstance();
        $timezone = $conf->leeParametro('timezone');
        date_default_timezone_set($timezone);
    }

    protected function seteaMapa() {
        $this->mapa = new Maper($this->getClase());
    }

    public function getMapa() {
        return $this->mapa;
    }

    /**
     * Retorna el nombre de la Clase que se esta utilizando
     *
     * @return string con el nombre de la clase en USO
     */
    protected function getClase() {
        return $this->clase;
    }

    // Retorna el nombre del Objeto definio en la clase propia
    protected function getNombreObjeto() {
        return strtolower($this->clase);
    }

    protected function getArrayTabla() {
        return $this->mapa->objTOtabla($this->{$this->getNombreObjeto()});
    }

    /**
     * Define la propiedad del objeto definida en la Clase propia con los valores del objeto pasado
     * como parametro
     * La propiedad de la Clase que define el objeto tiene que tener el mismo nombre de la clase base pero en minusculas
     * @param unknown_type $dato -> objeto de un tipo definido
     */
    public function seteaBSN($dato) {
        $clase = $this->getClase();
        $objeto = strtolower($clase);
        $seteoObj = 'setea' . $clase;
        $this->{$objeto}->{$seteoObj}($dato);
    }

    /**
     * Define y crea un objeto vacio de la Clase que lo imboca
     *
     */
    public function creaObjeto() {
        $clase = $this->getClase();
        $objeto = strtolower($clase);
        $this->{$objeto} = new $clase();
    }

    /**
     * Carga un objeto desde la base de datos buscandolo por su ID
     *
     * @param unknown_type $_id
     */
    public function cargaById($_id) {
        $clase = $this->getClase();
        $objeto = strtolower($clase);

        $localclass = $this->getClase() . 'PGDAO';
        $this->setId($_id);
        $datoDB = new $localclass($this->getArrayTabla());
        $arrayDB = $this->leeDBArray($datoDB->findById());
        if (sizeof($arrayDB) != 0) {
            $this->{$objeto} = $this->mapa->tablaTOobj($arrayDB[0]);
        }
    }

    /**
     * Buesca el ID de un objeto dado, basandose en la clave unica del mismo
     *
     * @return id del objeto definido por la clave
     */
    // Definir en la Clase Propia
    public function buscaID() {
        $retorno = false;
        $localclass = $this->getClase() . 'PGDAO';
        $datoDB = new $localclass($this->getArrayTabla());
        $arrayDB = $this->leeDBArray($datoDB->findByClave());
        $clase = $this->getClase() . "BSN";
        $objaux = $this->mapa->tablaTOobj($arrayDB[0]);
        $obj = new $clase($objaux);
        $retorno = $obj->getId();
        return $retorno;
    }

    /**
     * Carga los datos de la base en un objeto definido
     *
     * @param unknown_type $result
     */
    public function seteaDB($result) {
        $leeDB = 'lee' . $this->getClase() . 'DB';
        $this->seteaBSN($this->{$leeDB}($result));
    }

    /**
     * Carga los datos de la base en un Array y los asigna al objeto definido
     *
     * @param resultset $result
     */
    protected function seteaArray($result) {
        $clase = $this->getClase();
        $objeto = strtolower($clase);
        $this->{$objeto} = $this->leeDBArray($result);
    }

    /**
     * Lee un resultset de un query y lo transforma en un array bidimensional normal
     * donde la primer dimension es cada registro y el contenido de la segunda el el contenido de los registros
     *
     * @param resultset $result -> retorno del query
     * @return array conteniendo el resultado del query
     */
    protected function leeDBArray($result) {
        $_array = array();
        $conf = CargaConfiguracion::getInstance();
        $tipoDB = $conf->leeParametro("tipodb");
        if ($tipoDB == "my") {
            $fetch = "mysql_fetch_array";
        } else {
            $fetch = "pg_fetch_array";
        }
        while ($row = $fetch($result)) {
            $_array[] = $row;
        }
        return $_array;
    }

    /**
     * Carga una Coleccion de Objetos de la Clase Definida
     *
     */
    public function cargaColeccion() {
        $localclass = $this->getClase() . 'PGDAO';
        $datoDB = new $localclass($this->getArrayTabla());
        $this->seteaArray($datoDB->coleccion());
    }

    /**
     * Carga una coleccion de datos de un query en un array multidimensional para ser utilizado en un form
     * Para ello toma los datos retornados por el query y aplicando el mapeo los transforma para ser utilizados en el form
     *
     * @return array con las definiciones de nombres para el form segun el mapa
     */
    public function cargaColeccionForm() {
        $this->cargaColeccion();
        $array = $this->{$this->getNombreObjeto()};
        $arrayform = array();
        foreach ($array as $registro) {
            $arrayform[] = $this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }

    /**
     * Retorna el valor siguiente al maximo indicado en $MAYORORDEN de la clase PGDAO del objeto
     * Para ello se debe crear el objeto BSN con los datos necesarios para armar las condiciones necesarias
     * para filtrar
     *
     * @return int valor siguiente al maximo del campo indicado en la PGDAO
     */
    public function proximaPosicion() {
        $localclass = $this->getClase() . 'PGDAO';
        $datoDB = new $localclass($this->getArrayTabla());
        $this->seteaArray($datoDB->maximoPosicion());

        $array = $this->{$this->getNombreObjeto()};
        $max = 0;
        if (sizeof($array[0]) > 0) {
            $max = $array[0]['max'];
        }
        return $max + 1;
    }

    /**
     * Retorna el objeto definido y con los valores cargados segun se haya procedido
     *
     * @return objeto del tipo definido
     */
    public function getObjeto() {
        $clase = $this->getClase();
        $objeto = strtolower($clase);
        return $this->{$objeto};
    }

    /**
     * Retorna un array definido para el formulario web basado en la relacion objeto - formulario del mapa
     *
     * @return array del form
     */
    public function getObjetoView() {
        return $this->mapa->objTOform($this->getObjeto());
    }

    
    /**
     * Lee un resultset de un query o un array y  lo transforma en un JSON
     *
     * @param resultset / array $result
     * @return json conteniendo el resultado del query/ array
     */
    public function getJsonColeccion($array){
        $arrayRes=array();
        if(is_array($array)){
            $arrayRes=$array;
        }else{
            $arrayRes=  $this->leeDBArray($array);
        }
        return json_encode($arrayRes);
    }

    /**
     * Lee los datos del array de POST pasados como parametro y los carga en un objeto
     *
     * @param array $post -> definido como el array leido por el POST del formulario, armado como clave-valor
     * @return objeto de la clase actual
     */
    public function leeDatosForm($post) {
        return $this->mapa->formTOobj($post);
    }

    /**
     * Borra de la Tabla el elemento con el Id definido en el Objeto
     *
     * @return resultado de la operacion
     */
    public function borraDB() {
        $retorno = false;
        $clase = $this->getClase();
        $claseDB = $clase . "PGDAO";
        $datoDB = new $claseDB($this->getArrayTabla());
        $retorno = $datoDB->delete();
        $this->logeaBasico('D');
        return $retorno;
    }

    /**
     * Inserta en la tabla los datos del objeto actual
     *
     * @return estado de finalizacion del insert (true o false)
     */
    public function insertaDB() {
        $retorno = false;
        $clase = $this->getClase();
        $claseDB = $clase . "PGDAO";
        $duplicado = false;
        $retorno = false;
        //Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
        //		$duplicado=$this->controlDuplicado(	$this->noticia->getNoticia());
        //		if (!$duplicado){
        $datoDB = new $claseDB($this->getArrayTabla());
        $retorno = $datoDB->insert();
        //die();
        if ($retorno) {
            $this->logeaBasico('I');
            //				$this->setId($this->buscaID());
            $retorno = true;
        }

        //		} // Fin control duplicado
        return $retorno;
    }

    /**
     * Actualiza los datos de la tabla con los definidos en el objeto actual
     *
     * @return estado de finalizacion del update
     */
    public function actualizaDB() {
        $retorno = false;
        $clase = $this->getClase();
        $claseDB = $clase . "PGDAO";
        $datoDB = new $claseDB($this->getArrayTabla());
        $retorno = $datoDB->update();
        $this->logeaBasico('U');
        return $retorno;
    }

    /**
     * Registra en el log las operaciones basicas
     * Para ello verifica si esta registrada o no en el archivo de log la clase
     * @param char $tarea : tipo de tarea I: Insert   U:Update    D: Delete
     */
    protected function logeaBasico($tarea) {
        if ($this->isLoged($this->clase)) {
            switch ($tarea) {
                case 'I':
                    $proceso = 'Nueva';
                    $estado = 'Carga';
                    break;
                case 'U':
                    $proceso = 'Actualiza';
                    $estado = 'Modificados';
                    break;
                case 'D':
                    $proceso = 'Elimina';
                    $estado = 'Borrado';
                    break;

                default:
                    $proceso = '';
                    $estado = '';
                    break;
            }
            $obs = $this->getObjeto()->__toString();
            $this->registraLog($this->getId(), $proceso, $estado, $obs);
        }
    }

    /**
     * Metodo definido para registrar eventos en el Log de la aplicacion
     * @param variant $id_tarea: Id que representa a la tarea que se registro
     * @param string $proceso: Proceso que se esta ejecutando
     * @param string $estado: Estado de la ejecucion del proceso
     * @param string $observacion
     */
    public function registraLog($id_tarea, $proceso, $estado, $observacion) {
        $conf = CargaConfiguracion::getInstance();
        $timezone = $conf->leeParametro('timezone');
        date_default_timezone_set($timezone);
        if (!array_key_exists('UserId', $_SESSION) || $_SESSION['UserId']=='') {
            $_SESSION['UserId'] = 0;
        }
        $clase = $this->getClase();
        $claseDB = $clase . "PGDAO";
        $datoDB = new $claseDB();
        $datoDB->registroLog(0, date('Y-m-d H:i:s'), $_SESSION['UserId'], $this->logedObjects[$this->clase], $id_tarea, $proceso, $estado, $observacion);
    }

    /**
     * Verifica si una tarea u objeto existe en el archivo de objetos logueables
     * @param string $tarea : nombre de la clase o tarea a verificar
     * @return boolean 
     */
    protected function isLoged($tarea) {
        $this->cargaLogeables();
        $retorno = false;
        if (array_key_exists($tarea, $this->logedObjects)) {
            $retorno = true;
        }
        return $retorno;
    }

    /**
     * Carga el array de clases logueables para su uso
     */
    protected function cargaLogeables() {
        if (file_exists("generic_class/" . $this->logedFile)) {
//            include_once("generic_class/" . $this->logedFile);
            include("generic_class/" . $this->logedFile);
            $this->logedObjects = $objetos;
        } else {
            $this->logedObjects = array();
        }
    }

    /**
     * Arma un combo con los valores enviados en el Array
     * @param string[][] -> array de dos dimensiones  el 1er valor representa el ID el segunod el dato a mostrar
     * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
     * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion 
     * @param str $campo -> nombre del campo en el formulario
     * @param str $class -> clase de la css con la cual se presenta el dato
     */
    public function armaCombo($lista, $valor = '', $nombre = 'combo', $clase = 'campos_btn', $opcion = 0) {
        print "<select name='" . $nombre . "' id='" . $nombre . "'  style='width: 97%;' class=\"" . $clase . "\" ";
        print ">";

        switch ($opcion) {
            case 1:
                print "<option value='0'";
                if ($valor == 0) {
                    print " SELECTED ";
                }
                print ">Todas</option>\n";
                break;
            case 2:
                print "<option value='0'";
                if ($valor == 0) {
                    print " SELECTED ";
                }
                print ">Seleccione una opcion</option>\n";
                break;

            default:
                break;
        }
        for ($pos = 0; $pos < sizeof($lista); $pos++) {
            print "<option value='" . $lista[$pos][0] . "'";
            if ($lista[$pos][0] == $valor) {
                print " SELECTED ";
            }
            print ">" . $lista[$pos][1] . "</option>\n";
        }
        print "</select>";
    }

}
//Fin Clase BSN Abstracta
?>