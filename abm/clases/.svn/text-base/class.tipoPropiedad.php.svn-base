<?php
include_once("generic_class/class.cargaConfiguracion.php");
include_once("clases/class.auxiliaresPGDAO.php");

class TipoPropiedad{

	private $tipoProp;
	private static $_instance;
	
	public function __construct(){
		$aux=new AuxiliaresPGDAO();
		$colaux=$aux->coleccionTipoProp();
		$this->tipoProp=$colaux;
	}

	static public function getInstance(){
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function getArrayTipoProp(){
		return $this->tipoProp;
	}
	
	public function getTipoProp($id){
		$retorno="";
		foreach ($this->tipoProp as $funcion){
			if($funcion[0]==$id){
				$retorno=$funcion[1];
				break;
			}
		}
		return $retorno;
	}
	
	public function comboTipoProp($valor=0,$campo="id_tipo_prop",$class="cd_celda_input"){
		print "<select name='".$campo."' id='".$campo."' class='campos_btn'>\n";
		foreach ($this->tipoProp as $funcion){
			print "<option value='".$funcion[0]."'";
			if ($funcion[0]==$valor){
				print " SELECTED ";
			}
			print ">".$funcion[1]."</option>\n";
		}
		print "</select>\n";
	}
	
} 
?>
