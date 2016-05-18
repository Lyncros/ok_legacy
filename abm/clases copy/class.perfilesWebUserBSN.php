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
include_once("clases/class.perfileswebuser.php");
include_once("clases/class.perfilesWebUserPGDAO.php");


class PerfilesWebUserBSN extends BSN {
	
	protected $clase = "Perfileswebuser";
	protected $nombreId = "id_user";
	protected $perfileswebuser;

	
	public function __construct($_perfil='',$_id=''){
		PerfilesWebUserBSN::seteaMapa();
		if ($_perfil  instanceof Perfileswebuser ){
			PerfilesWebUserBSN::creaObjeto();
			PerfilesWebUserBSN::seteaBSN($_perfil);
		} else {
			PerfilesWebUserBSN::creaObjeto();
			if (is_numeric($_id)){
				if($_id!=''){
					PerfilesWebUserBSN::cargaById($_id);
				}
			}else{
				PerfilesWebUserBSN::setPerfil($_perfil);
			}
		}	

	}
	
/**
 * retorna el ID del objeto 
 *
 * @return id del objeto
 */
	public function getId(){
		return $this->perfileswebuser->getId_user();
	}
	
/**
 * Setea e ID del objeto
 *
 * @param unknown_type $id
 */
	public function setId($id){
		$this->perfileswebuser->setId_user($id);
	}

	public function setPerfil($perf){
		$this->perfileswebuser->setPerfil($perf);
	}
	
	public function coleccionPerfilesUsuario($_id_user){
//		echo $_id_user."<br>";
		$perf= new Perfileswebuser();
		$perf->setId_user($_id_user);
//		print_r($perf);echo"<br>";
		$perfBSN = new PerfilesWebUserBSN($perf);
//		print_r($perfBSN);echo"<br>";
		$datoDB = new PerfilesWebUserPGDAO($perfBSN->getArrayTabla());
		$arrayDB=$this->leeDBArray($datoDB->coleccionUW_Perfiles_byUW());
		$this->perfileswebuser=$this->mapa->tablaTOobj($arrayDB[0]);
		return $this->perfileswebuser->getPerfil();
	}

}

?>