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
 *		$var='muestra'.$this->getClase();
 *		$this->{$var}();
 *	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.zona.php");
include_once("clases/class.zonaPGDAO.php");
include_once("clases/class.auxiliaresPGDAO.php");


class ZonaBSN extends BSN {

    protected $clase = "Zona";
    protected $nombreId = "id_zona";
    protected $zona;


    public function __construct($_id_zona=0,$_nombre_zona='') {
        ZonaBSN::seteaMapa();
        if ($_id_zona  instanceof Zona ) {
            ZonaBSN::creaObjeto();
            ZonaBSN::seteaBSN($_id_zona);
        } else {
            if (is_numeric($_id_zona)) {
                ZonaBSN::creaObjeto();
                if($_id_zona!=0) {
                    ZonaBSN::cargaById($_id_zona);
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
        return $this->zona->getId_zona();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->zona->setId_zona($id);
    }

    public function comboZona($valor=0,$loca=0,$opcion=0,$campo="id_zona",$campoloc='id_loca',$campoemp='',$class="campos_btn") {
        $zona=$this->cargaColeccionForm();
        print "<select name='".$campo."' id='".$campo."' class='".$class."' ";
        if($opcion!=3){
	        print " onblur=\"javascript: comboLocalidad('".$campo."','localidad','".$loca."','".$campoloc."','".$campoemp."');\" onchange=\"javascript: comboLocalidad('".$campo."','localidad','".$loca."','".$campoloc."','".$campoemp."');\"";
        }else{
            print " onblur=\"javascript: limpiaLocalidad($valor);\" onchange=\"javascript: limpiaLocalidad($valor);\"";
        }
//        print " onchange=\"javascript: checkValor();\" >\n";
        print " >\n";
        switch ($opcion) {
            case 1:
                print "<option value='0'";
                if ($valor==0) {
                    print " SELECTED ";
                }
                print ">Todas</option>\n";
                break;
            case 2:
                print "<option value='0'";
                if ($valor==0) {
                    print " SELECTED ";
                }
                print ">Seleccione una opcion</option>\n";
                break;
            case 3:
                print "<option value='0'";
                if ($valor==0) {
                    print " SELECTED ";
                }
                print ">Todas</option>\n";
                break;

            default:
                break;
        }

        for ($pos=0;$pos<sizeof($zona);$pos++) {
            print "<option value='".$zona[$pos]['id_zona']."'";
            if ($zona[$pos]['id_zona']==$valor) {
                print " SELECTED ";
            }
            print ">".$zona[$pos]['nombre_zona']."</option>\n";
        }
        print "</select>\n";
    }
}

?>