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
include_once("clases/class.tipo_prop.php");
include_once("clases/class.tipo_propPGDAO.php");
include_once("clases/class.auxiliaresPGDAO.php");
include_once("clases/class.grupo_tipoprop.php");
include_once("clases/class.perfilesBSN.php");


class Tipo_propBSN extends BSN {

	protected $clase = "Tipo_prop";
	protected $nombreId = "id_tipo_prop";
	protected $tipo_prop;


	public function __construct($_id_tipo_prop=0,$_tipo_prop=''){
		Tipo_propBSN::seteaMapa();
		if ($_id_tipo_prop  instanceof Tipo_prop ){
			Tipo_propBSN::creaObjeto();
			Tipo_propBSN::seteaBSN($_id_tipo_prop);
		} else {
			if (is_numeric($_id_tipo_prop)){
				Tipo_propBSN::creaObjeto();
				if($_id_tipo_prop!=0){
					Tipo_propBSN::cargaById($_id_tipo_prop);
				}
			}
		}

	}

	/**
 * retorna el ID del objeto 
 *
 * @return id del objeto
 */
	public function getId(){
		return $this->tipo_prop->getId_tipo_prop();
	}

	/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->tipo_prop->setId_tipo_prop($id);
	}


	/**
 * Arma un combo con losvalores levantados de la tabla correspondiente
 *
 * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
 * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion 
 * @param str $campo -> nombre del campo en el formulario, por omision id_tipo_prop
 * @param str $class -> clase de la css con la cual se presenta el dato
 */
	public function comboTipoProp($valor=0,$opcion=0,$campo="id_tipo_prop",$class="cd_celda_input",$div='',$subtipo=''){
		$tipoProp=$this->cargaColeccionForm();
		$gtp = new Grupo_tipoprop();
		$perfilBSN = new PerfilesBSN();
		$gpf = $perfilBSN->grupoPerfil($_SESSION['Userrole']);
		print "<select name='".$campo."' id='".$campo."' class='campos'";
		if($div!=''){
			if($div=='filtro'){
				print " onchange=\"javascript: filtra();\"";
			}else{
				print "onchange=\"javascript: comboSubtipo_prop('".$campo."','".$div."','".$subtipo."','subtipo_prop');\"";
			}
		}
		print ">\n";
		switch ($opcion) {
			case 1:
				print "<option value='0'";
				if ($valor==0){
					print " SELECTED ";
				}
				print ">Todas</option>\n";
				break;
			case 2:
				print "<option value='0'";
				if ($valor==0){
					print " SELECTED ";
				}
				print ">Seleccione una opcion</option>\n";
				break;
			case 3:
				print "<option value='0'";
				if ($valor==0){
					print " SELECTED ";
				}
				print ">Todas</option>\n";
				break;

			default:
				break;
		}
		for ($pos=0;$pos<sizeof($tipoProp);$pos++){
			if($opcion==3 || $gpf == $gtp->perteneceGrupo($tipoProp[$pos]['id_tipo_prop']) || $gpf=='GRAL' || $gpf=='SUPERUSER' || $gpf=='LECTURA' || $gpf=='admin'){
				print "<option value='".$tipoProp[$pos]['id_tipo_prop']."'";
				if ($tipoProp[$pos]['id_tipo_prop']==$valor){
					print " SELECTED ";
				}
				print ">".$tipoProp[$pos]['tipo_prop']."</option>\n";
			}
		}
		print "</select>\n";
	}

/**
 * Arma un combo con los valores levantados de la tabla correspondiente segun el tipo de propiedad
 *
 * @param int $valor -> Valor actual del campo para fijar en la vista sobre la opcion actualmente seleccionada
 * @param int $id_tipo_prop -> Tipo de propiedad del cual se desea armar el combo
 * @param int $opcion -> Indica si se agrega algun tipo de opcion previo a la carga de datos 0-NO 1-Todos 2-Seleccione Opcion 
 * @param str $campo -> nombre del campo en el formulario, por omision subtipo_prop
 * @param str $class -> clase de la css con la cual se presenta el dato
 */
	public function comboSubtipoProp($valor=0,$id_tipo_prop=0,$opcion=0,$campo="subtipo_prop",$class="cd_celda_input",$div=''){
		if($id_tipo_prop!=0){
			$tipoAux = new Tipo_propBSN($id_tipo_prop);
			$lista = $tipoAux->getObjeto()->getSubtipo_prop();
			$arraySub = split(";",$lista);
		}else{
			$arraySub=array();
		}
		print "<select name='".$campo."' id='".$campo."' class='campos'";
		if($div!=''){
			//			print " onchange=\"javascript: armaFiltro('".$campo."','".$div."');\"";
			print " onchange=\"javascript: filtra();\"";
		}
		print ">\n";
		switch ($opcion) {
			case 1:
				print "<option value='0'";
				if ($valor==0){
					print " SELECTED ";
				}
				print ">Todas</option>\n";
				break;
			case 2:
				print "<option value='0'";
				if ($valor==0){
					print " SELECTED ";
				}
				print ">Seleccione una opcion</option>\n";
				break;

			default:
				break;
		}
		for ($pos=0;$pos<sizeof($arraySub);$pos++){
			print "<option value='".$arraySub[$pos]."'";
			if ($arraySub[$pos]==$valor){
				print " SELECTED ";
			}
			print ">".$arraySub[$pos]."</option>\n";
		}
		print "</select>\n";
	}


	public function checkboxTipoProp($valor,$opcion=0){
		$tipoProp=$this->cargaColeccionForm();
		$arraySeleccion=array();
		if($opcion==0){
			$auxiliar=new AuxiliaresPGDAO();
			$arraySeleccion=$auxiliar->coleccionTipopropAsignada($valor);
		}else{
        	$arraySeleccion=split(',',$valor);
		}
		print "<table width='700' align='center' bgcolor='#FFFFFF'>";
		$col=0;
		foreach ($tipoProp as $funcion){
			if($col==2){
				print "</tr>";
				$col=0;
			}
			if($col==0){
				print "<tr class='campos'>";
			}
			$col++;
			//<input type="checkbox"  checked>
			print "<td width='50%'><input type='checkbox' id='tp_".$funcion['id_tipo_prop']."' name='tp_".$funcion['id_tipo_prop']."'";
			if($opcion==0){
				foreach ($arraySeleccion as $tipo){
					if (in_array($funcion['id_tipo_prop'],$tipo)){
						print " checked ";
					}
				}
			}else{
				if (in_array($funcion['id_tipo_prop'],$arraySeleccion)){
					print " checked ";
				}
				
			}
			print ">&nbsp;&nbsp;".$funcion['tipo_prop']."</td>\n";
		}
		print "</tr></table>\n";

	}

	/**
 * Lee desde un array de POST y verifica que tipos de propiedades fueron tildadas
 *
 * @param Array de Post $post
 * @return Array con los valores de los tipos de propiedad tildadas
 */
	public function leechkTipoProp($post){
		$tipoProp=$this->cargaColeccionForm();
		$array = array();
		foreach ($tipoProp as $tipo){
			$campo='tp_'.$tipo['id_tipo_prop'];
			if($post[$campo]=="on"){
				$array[]=$tipo['id_tipo_prop'];
			}
		}
		return $array;

	}

	public function grabaCaracteristica_TipoProp($id_carac,$arrayTPAsig){
		$auxiliar = new AuxiliaresPGDAO();
		$auxiliar->borraTipopropAsignada($id_carac);
		foreach ($arrayTPAsig as $asig){
			$auxiliar->insertaTipopropAsignada($id_carac,$asig);
		}
	}

}

?>