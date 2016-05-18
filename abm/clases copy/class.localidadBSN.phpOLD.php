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
include_once("clases/class.localidad.php");
include_once("clases/class.localidadPGDAO.php");
include_once("clases/class.auxiliaresPGDAO.php");


class LocalidadBSN extends BSN {

    protected $clase = "Localidad";
    protected $nombreId = "id_loca";
    protected $localidad;


    public function __construct($_id_loca=0,$_id_zona=0,$_nombre_zona='') {
        LocalidadBSN::seteaMapa();
        if ($_id_loca  instanceof Localidad ) {
            LocalidadBSN::creaObjeto();
            LocalidadBSN::seteaBSN($_id_loca);
        } else {
            if (is_numeric($_id_loca)) {
                LocalidadBSN::creaObjeto();
                if($_id_loca != 0) {
                    LocalidadBSN::cargaById($_id_loca);
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
        return $this->localidad->getId_loca();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->localidad->setId_loca($id);
    }

    public function comboLocalidad($valor=0,$zona=0,$opcion=0,$campo="id_loca",$class="cd_celda_input",$campoemp='') {
        $localidad=$this->cargaColeccionByZona($zona);
        print "<select name='".$campo."' id='".$campo."' class='campos' ";
        if($campoemp!='') {
            print " onchange=\"javascript: comboEmprendimiento('".$campo."','".$zona."','emprendimiento','".$campoemp."');\"";
        }
        print ">\n";
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

            default:
                break;
        }
        for ($pos=0;$pos<sizeof($localidad);$pos++) {
            print "<option value='".$localidad[$pos]['id_loca']."'";
            if ($localidad[$pos]['id_loca']==$valor) {
                print " SELECTED ";
            }
            print ">".$localidad[$pos]['nombre_loca']."</option>\n";
        }
        print "</select>\n";

    }

    
	public function checkboxLocalidad($valor,$zona){
		
        $localidad=$this->cargaColeccionByZona($zona);
        $arraySeleccion = array();
        if($valor!='' && $valor!=0){
        	$arraySeleccion=split(',',$valor);
        }
		print "<table width='700' align='center' bgcolor='#FFFFFF'>";
		$col=0;
		foreach ($localidad as $funcion){
			if($col==2){
				print "</tr>";
				$col=0;
			}
			if($col==0){
				print "<tr class='campos'>";
			}
			$col++;
			print "<td width='50%'><input type='checkbox' id='loc_".$funcion['id_loca']."' name='loc_".$funcion['id_loca']."'";
			if (in_array($funcion['id_loca'],$arraySeleccion)){
				print " checked ";
			}
			print ">&nbsp;&nbsp;".$funcion['nombre_loca']."</td>\n";
		}
		print "</tr></table>\n";

	}
	
	
	public function cargaColeccionByZona($zona) {
        $datoDB = new LocalidadPGDAO($this->getArrayTabla());
        $this->seteaArray($datoDB->coleccionByZona());

        $array=$this->{$this->getNombreObjeto()};
        $arrayform=array();
        foreach ($array as $registro) {
            $arrayform[]=$this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }


}

?>