<?php

require_once 'generic_class/class.cargaConfiguracion.php';

/**
 * Clase basica para el reemplazo de etiquetas y patrones determinados dentro de los templates definidos para la aplicacion.
 * Basandose en la configuracion de la aplicacion determina el idioma del browser que genera la peticion y especifica el archivo 
 * a utilizar para traducir las etiquetas
 * Para ello verifica la existencia en el conf.xml de un atributo multilang=SI o si, el cual identifica si se utilizan archivos de 
 * idiomas.
 * En caso afirmativo determina el nombre del mismo, el path donde se alojan y el idioma default para el caso de no existir una traduccion
 * para el idioma del navegador con los siguientes parametros:
 * def_lang: idioma por default
 * filelang: nombre de los archivos de idioma, utiliza la misma base para todos
 * baselangpath: path base donde se alojan los archivos
 */
class Template {

    private $templateSalida;
    private $templatePath;
    private $multilang;
    private $lang;
    private $deflang;
    private $entorno;
    private $langpath;

    public function __construct($nombreArchivo) {
        $conf = CargaConfiguracion::getInstance('');
        $this->templatePath = $conf->leeParametro("path_templates");
        $this->multilang = $conf->leeParametro("multilang");
        if (strtoupper($this->multilang) == 'SI') {
            $this->deflang = $conf->leeParametro("def_lang");
            $this->entorno = $conf->leeParametro("filelang");
            $this->langpath = $conf->leeParametro("baselangpath");
            $this->setArchivoIdioma();
        }
        $filename = $this->templatePath . "/" . $nombreArchivo;
        $fileIn = fopen($filename, "r");
        $this->setTemplateSalida(fread($fileIn, filesize($filename)));
        fclose($fileIn);
    }

    /**
     * Utilizado para el caso de sistemas multilinguales.
     * Setea el archivo de Idiomas utilizado para la definicion de las leyendas y mensajes, basado en el idioma del navegador
     */
    protected function setArchivoIdioma() {
        $this->detectarIdioma();
        putenv("LANG=" . $this->lang);
        bindtextdomain($this->entorno, $this->langpath);
        textdomain($this->entorno);
    }

    /**
     * Utilizado para el caso de sistemas multilinguales.
     * Especifica el idioma a utilizar para traducir las etiquetas de la aplicacion, basado en las preferencias del navegador
     * del cliente que consulta el sitio.
     */
    protected function detectarIdioma() {
        if (!isset($_SESSION['Lang'])) {
            $idiomas = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $this->lang = $this->deflang;
            $arrayLang = $this->listarDirectoriosIdiomas();
            foreach ($idiomas as $browlang) {
                foreach ($arrayLang as $idioma) {
                    if (strpos($idioma, substr($browlang, 0, 2)) !== FALSE) {
                        $this->lang = substr($browlang, 0, 2);
                        $_SESSION['Lang']=  $this->lang;
                        break(2);
                    }
                }
            }
        } else {
            $this->lang=$_SESSION['Lang'];
        }
    }

    /**
     * Utilizado para el caso de sistemas multilinguales.
     * Detecta los diferentes idiomas existentes en el directorio definido para contener los archivos de idiomas.
     * @return string[] conteniendo los diferentes nombres de los directorios de idiomas
     */
    public function listarDirectoriosIdiomas() {
        $arrayLang = array();
        if (is_dir($this->langpath)) {
            $dh = opendir($this->langpath);
            if ($dh !== FALSE) {
                while (($file = readdir($dh)) !== false) {
                    if (is_dir($this->langpath . "/" . $file) && $file != "." && $file != "..") {
                        $arrayLang[] = $file;
                    }
                }
                closedir($dh);
            }
        }
        return $arrayLang;
    }

    public function setTemplateSalida($templ) {
        $this->templateSalida = $templ;
    }

    public function Incluir($tag, $replace) {
        $this->templateSalida = str_replace("[" . $tag . "]", $replace, $this->templateSalida);
    }

    /**
     * Reemplaza los tags READONLY y DISABLED por su estado real segun lo solicitado
     * @param boolean $habilitar
     */
    public function enableDisable($habilitar){
        if($habilitar){
            $this->parsearDato('READONLY', '');
            $this->parsearDato('DISABLED', 'disabled="disabled"');
        }else{
            $this->parsearDato('READONLY', 'readonly="readonly"');
            $this->parsearDato('DISABLED', '');
        }
    }
    
    /**
     * Reemplaza el tag de la etiqueta con el contenido del archivo de idiomas detectado en base al idioma del navegador.
     * @param string $label: nombre de la etiqueta a reemplazar
     */
    public function parsearEtiquetas($label) {
        if (strtoupper($this->multilang) == 'SI') {
            $this->parsearDato($label, _($label));
        } else {
            $this->parsearDato($label, $label);
        }
    }

    /**
     * Reemplaza el tag indicado por su valor correspondiente
     * @param string $tag : identificador dentro del template
     * @param string $replace : valor que reemplazara el tag
     */
    public function parsearDato($tag, $replace) {
        $this->templateSalida = str_replace("{" . strtoupper($tag) . "}", $replace, $this->templateSalida);
    }

    /**
     * Reemplaza el tag indicado por el contenido del archivo especificado, en el caso de no existir el archivo 
     * lo rellena con espacio en blanco
     * @param string $tag : identificador dentro del template
     * @param string $fileReplace : nombre del archivo con la ruta del mismo
     */
    public function parsearFile($tag, $fileReplace) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/" . $fileReplace)) {
            $replace = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/" . $fileReplace);
            $this->parsearDato($tag, $replace);
        } else {
            $this->parsearDato($tag, '');
        }
    }

    /**
     * En base al array de datos pasado como parametro ( del tipo clave->valor )
     * parsea su contenido reemplazando en el template los parametros que se encuentren.
     * @param type $arrayDatos
     */
    public function parsearArray($arrayDatos) {
        $arrayClaves = array_keys($arrayDatos);
        foreach ($arrayClaves as $clave) {
            $this->parsearDato($clave, $arrayDatos[$clave]);
        }
    }

    /**
     * Despliega el template en pantalla
     */
    public function show() {
        echo($this->templateSalida);
    }

    /**
     * Retorna el template con los parseos efecuatados sobre el mismo
     * @return string conteniendo el template parseado al momento de ser requerido.
     */
    public function getSalida() {
        return ($this->templateSalida);
    }

}

?>
