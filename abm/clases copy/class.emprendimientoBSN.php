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

/*
  id_emp int(10) unsigned NOT NULL auto_increment,
  id_tipo_emp int(10) unsigned default NULL,
  id_zona int(10) unsigned NOT NULL default '0',
  id_loca int(10) unsigned NOT NULL default '0',
  ubcacion varchar(500) NOT NULL,
  descripcion varchar(5000) NOT NULL,
  logo varchar(500) NOT NULL,
  foto varchar(500) NOT NULL,
  comentario varchar(1000) default NULL,
  goglat double default NULL,
  goglong double default NULL,

*/

include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.emprendimiento.php");
include_once("clases/class.emprendimientoPGDAO.php");
include_once("clases/class.auxiliaresPGDAO.php");


class EmprendimientoBSN extends BSN {

    protected $clase = "Emprendimiento";
    protected $nombreId = "id_emp";
    protected $emprendimiento;


    public function __construct($_id_emp=0,$_emprendimiento='') {
        EmprendimientoBSN::seteaMapa();
        if ($_id_emp  instanceof Emprendimiento ) {
            EmprendimientoBSN::creaObjeto();
            EmprendimientoBSN::seteaBSN($_id_emp);
        } else {
            if (is_numeric($_id_emp)) {
                EmprendimientoBSN::creaObjeto();
                if($_id_emp!=0) {
                    EmprendimientoBSN::cargaById($_id_emp);
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
        return $this->emprendimiento->getId_emp();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->emprendimiento->setId_emp($id);
    }

    public function cargaColeccionFiltro($zona,$localidad,$tipo_emp,$activa=-1,$pagina=1) {
//		$localclass=$this->getClase().'PGDAO';
        $config= CargaConfiguracion::getInstance();
        $registros=$config->leeParametro('regemp_adm');

        $array=array();
        $prop=new Emprendimiento();
        $prop->setId_zona($zona);
        $prop->setId_loca($localidad);
        $prop->setId_tipo_emp($tipo_emp);
        $arrayTabla=$this->mapa->objTOtabla($prop);
        $datoDB = new EmprendimientoPGDAO($arrayTabla);
        $array = $this->leeDBArray($datoDB->coleccionByFiltro($zona,$localidad,$tipo_emp,$activa,$pagina,$registros));
        $arrayform=array();
        foreach ($array as $registro) {
            $arrayform[]=$this->mapa->tablaTOform($registro);
        }
        return $arrayform;
    }


    public function cantidadRegistrosFiltro($zona,$localidad,$tipo_emp,$activa=-1) {
        $retorno=0;
        $propPGDAO=new EmprendimientoPGDAO();
        $reg = $propPGDAO->cantidadRegistrosFiltro($zona,$localidad,$tipo_emp,$activa=-1);
        $array = $this->leeDBArray($reg);
        if(sizeof($array)==0) {
            $cant=0;
        }else {
            $cant=$array[0]['id_emp'];
        }
        return $cant;
    }

    /**
     * Arma un combo para ser presentado en la vista web.
     *
     * @param int $valor -> valor actual del combo
     * @param int $opcion -> indica si se debe presentar algun valores extra en la lista 0-Ningun 1-Todos 2-Seleccione una opcion * 							3-Independiente
     * @param int $zona -> id de la zona , si es 0 son todos los emprendimientos
     * @param int localidad -> id de la localidad, si es 0 son todos los emprendimentos de la zona
     * @param string $campo -> nombre del campo en la vista web
     * @param string $class -> nombre de la clase con la qe se lo visualiza en el css
     */
    public function comboEmprendimiento($valor=0,$opcion=0,$zona=0,$localidad=0,$campo="id_emp",$class="campos_btn") {
        $emp=$this->cargaColeccionFiltro($zona,$localidad,0);
        print "<select name='".$campo."' id='".$campo."' class='".$class."'>\n";
        switch ($opcion) {
            case 1:
                print "<option value='0'";
                if ($valor==0) {
                    print " SELECTED ";
                }
                print ">Todos</option>\n";
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
                print ">Independiente</option>\n";
                break;

            default:
                break;
        }
        for ($pos=0;$pos<sizeof($emp);$pos++) {
            print "<option value='".$emp[$pos]['id_emp']."'";
            if ($emp[$pos]['id_emp']==$valor) {
                print " SELECTED ";
            }
            print ">".$emp[$pos]['nombre']."</option>\n";
        }
        print "</select>\n";

    }

	public function checkboxEmprendimiento($valor,$zona=0,$localidad=0){
		
        $emprendimiento=$this->cargaColeccionFiltro($zona, $localidad, 0,-1,0);
        $arraySeleccion = array();
        if($valor!='' && $valor!=0){
        	$arraySeleccion=split(',',$valor);
        }
		print "<table align='center' bgcolor='#FFFFFF' style=\"width: 100%;\">";
		$col=0;
		foreach ($emprendimiento as $funcion){
			if($col==2){
				print "</tr>";
				$col=0;
			}
			if($col==0){
				print "<tr class='campos'>";
			}
			$col++;
			print "<td width='50%'><input type='checkbox' id='emp_".$funcion['id_emp']."' name='emp_".$funcion['id_emp']."'";
			if (in_array($funcion['id_emp'],$arraySeleccion)){
				print " checked ";
			}
			print " value='".$funcion['nombre']."'>&nbsp;&nbsp;".$funcion['nombre']."</td>\n";
		}
		print "</tr></table>\n";

	}
    
    
    public function coleccionEmprenimientosActivos() {
        $datosDB = new EmprendimientoPGDAO();
        $this->seteaArray($datosDB->coleccionEmprendimientosActivos());
        $array = $this->emprendimiento;
        $retorno=array();
        foreach ($array as $reg) {
            $retorno[]=$this->mapa->tablaTOform($reg);
        }
        return $retorno;
    }

    public function publicarEmprendimiento() {
        $retorno=false;
        $arrayTabla=$this->getArrayTabla();
        $propDB=new EmprendimientoPGDAO($arrayTabla);
        $retorno=$propDB->activar();
        return $retorno;
    }

    /**
     * Desactiva la evento para ser publicada
     *
     * @return estado de la finalizacion de la operacion
     */
    public function quitarEmprendimiento() {
        $retorno=false;
        $arrayTabla=$this->getArrayTabla();
        $propDB=new EmprendimientoPGDAO($arrayTabla);
        $retorno=$propDB->desactivar();
        return $retorno;
    }




}


?>