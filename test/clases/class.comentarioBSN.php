<?php

/**
 * Clase Propia para la definicion de la logica de negocios.
 * Utiliza dos variables propias de la clase que las hereda llamadas
 * 		"clase" que define la base del nombre, debe tener la Primer letra en Mayuscula y responder a la base de los nombres
 * 					de los metodos propios.
 * 		"objeto" que define el nombre del objeto tipo de la clase, cuyo nombre debe ser igual al de la
 * 					clase pero todo en minuscula
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
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.comentario.php");
include_once("clases/class.comentarioPGDAO.php");
include_once("generic_class/class.cargaConfiguracion.php");

class ComentarioBSN extends BSN {

    protected $clase = "Comentario";
    protected $nombreId = "id_com";
    protected $comentario;

    public function __construct($_id_com = 0) {
        ComentarioBSN::seteaMapa();
        if ($_id_com instanceof Comentario) {
            ComentarioBSN::creaObjeto();
            ComentarioBSN::seteaBSN($_id_com);
        } else {
            if (is_numeric($_id_com)) {
                ComentarioBSN::creaObjeto();
                if ($_id_com != 0) {
                    ComentarioBSN::cargaById($_id_com);
                }
            }
        }
    }

    /**
     * retorna el ID del objeto
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->comentario->getId_com();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->comentario->setId_com($id);
    }

    public function seteaComentario($id_com = 0, $fecha = '', $id_user = 0, $id_prop = '', $visible = '', $comentario = ''
    ) {
        $comentario = new Comentario();
        $comentario->setId_com($id_com);
        $comentario->setFecha($fecha);
        $comentario->setId_user($id_user);
        $comentario->setId_prop($id_prop);
        $comentario->setVisible($visible);
        $comentario->setComentario($comentario);
        $this->seteaBSN($comentario);
    }

    /**
     * Arma un array con los datos de los comentarios registrados para una propiedad,
     * en el caso que el usuario logueado sea administrador muestra todos los comentarios
     * @param int $id_prop -> id de la propiedad a seguir
     * @return string[][] -> array con los atos de los comentarios
     */
    public function cargaColeccionComentarios($id_prop) {
        if ($_SESSION['Userrole'] == 'admin') {
            $visible = -1;
        } else {
            $visible = 1;
        }
        $arrayRet = Array();
        $comDB = new ComentarioPGDAO();
        $result = $comDB->coleccionComentarios($id_prop, $visible, array('COMENTARIO'));
        if ($result != false) {
            $arrayRet = $this->leeDBArray($result);
        }
        return $arrayRet;
    }

    /**
     * Arma un array con los datos de los comentarios, los registros de las operaciones, tasaciones, carteles y datos relacionados con 
     * la propiedad segun sea lo definido en el array de conceptos.
     * Los conceptos corresponden a los definidos en el archivo de parametros
     * En el caso que el usuario logueado sea administrador muestra todos los comentarios
     * @param int $id_prop -> id de la propiedad a seguir
     * @param string[] -> array de conceptos a mostra.
     * @return string[][] -> array con los atos de los comentarios
     */
    public function cargaColeccionHistorial($id_prop, $concepto) {
        if ($_SESSION['Userrole'] == 'admin') {
            $visible = -1;
        } else {
            $visible = 1;
        }
        $arrayConc=split(',',$concepto);
        $arrayRet = Array();
        $comDB = new ComentarioPGDAO();
        $result = $comDB->coleccionComentarios($id_prop, $visible, $arrayConc);
        if ($result != false) {
            $arrayRet = $this->leeDBArray($result);
        }
        return $arrayRet;
    }

    public function checkboxComentario($valor = 0) {
        $params = new CargaParametricos('tiposConceptoComentario.xml');
        $lista = $params->getParametros();
        $arrayClaves = array_keys($lista);
        $arraySeleccion = array();
        if ($valor != 0 && $valor != '') {
            $arraySeleccion = split(',', $valor);
        }
        print "<table align='center' bgcolor='#FFFFFF' style=\"width: 100%;\">";
        $col = 0;
        for ($pos = 0; $pos < sizeof($lista); $pos++) {
            if ($col == 3) {
                print "</tr>";
                $col = 0;
            }
            if ($col == 0) {
                print "<tr class='campos'>";
            }
            $col++;
            print "<td width='33%'><input type='checkbox' id='ckTcc_" . $arrayClaves[$pos] . "' name='ckTcc_" . $arrayClaves[$pos] . "'";
            if (in_array($arrayClaves[$pos], $arraySeleccion)) {
                print " checked ";
            }
            print " value='" . $lista[$arrayClaves[$pos]] . "' />&nbsp;&nbsp;" . $lista[$arrayClaves[$pos]] . "</td>\n";
        }
        print "</tr></table>\n";
    }

    public function leeChkboxComentarioJS($campoCom){
        $params = new CargaParametricos('tiposConceptoComentario.xml');
        $lista = $params->getParametros();
        $claves = array_keys($lista);
        $retorno='';
        print "  strCom='';\n";
        foreach ($claves as $clave){
            $pos=strpos($clave, 'ckTcc_');
            print "  if(document.getElementById('ckTcc_".$clave."').checked){\n";
            print "     strCom+='".$clave."'+',';\n";
            print "  }\n";
        }
        print "  if(strCom!=''){\n";
        print "     strCom=strCom.substring(0,strCom.length -1);\n";
        print "  }\n";
        print $campoCom."=strCom;\n";
    }
    
    public function leeChkboxComentario($post){
        $claves= array_keys($post);
        $retorno='';
        foreach ($claves as $clave){
            $pos=strpos($clave, 'ckTcc_');
            if($pos!==false){
                $retorno.=(str_replace('ckTcc_','',$clave).",");
            }
        }
        if($retorno!=''){
            $retorno= substr($retorno,0, -1);
        }
        return $retorno;
    }
    
}

?>