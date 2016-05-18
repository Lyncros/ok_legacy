<?php
/**
 * Archivo de Clases que contiene la definicion de
 * MENU, PERFILES y MENU_PERFILES
 * 			para la determinaci�n de accesos y armado de menues posibles por perfil.
 * ACCESOS
 */

include_once("generic_class/class.cargaConfiguracion.php");
include_once("generic_class/class.PGDAO.php");
// include_once("/www/gen_lib/josso-lib/josso.php");

/**
 * Clase de consulta sobre la tabla de Menues de la aplicacion
 *
 */
class MenuPGDAO extends PGDAO {

	private static $FINDBYID="SELECT * FROM #dbName#.menu WHERE id_opcion=#id_opcion#";
	private static $COLECCIONPERFILHIJOS="SELECT M.id_opcion,M.opcion,M.descripcion,M.visible,M.padre FROM #dbName#.menu as M INNER JOIN #dbName#.menu_perfiles as P ON M.id_opcion=P.id_opcion WHERE M.padre=#padre# AND P.perfil='#perfil#' AND M.visible=1 ORDER BY orden";
	private static $COLECCIONPERFIL="SELECT M.id_opcion,M.opcion,M.descripcion,M.visible FROM #dbName#.menu as M INNER JOIN #dbName#.menu_perfiles as P ON M.id_opcion=P.id_opcion WHERE P.perfil='#perfil#' AND M.visible=1 ORDER BY padre,orden";


	public function findById($id_opcion){
		$this->seteaDato(array('id_opcion'=>$id_opcion));
		$resultado=$this->execSql(self::$FINDBYID);
		if(!$resultado){
			echo "Fallo el Query";
		}
		return $resultado;
	}
	
	/**
	 * Consulta los datos de las diferentes subopciones de un menu respecto
	 * al ID del padre
	 *
	 * @param int $_id -> id del menu padre
	 * @param string $_perfil -> perfil del usuario que accede al menu
	 * @return retorna un resultset con los datos pedidos.
	 */
	public function coleccionPerfilHijos($padre,$perfil){
		$this->seteaDato(array('padre'=>$padre,'perfil'=>$perfil));
		$resultado=$this->execSql(self::$COLECCIONPERFILHIJOS);
		if(!$resultado){
			echo "Fallo el Query";
		}
		return $resultado;
	}

	/**
	 * Consulta los datos de las diferentes opciones de un menu respecto
	 * al ID del perfil
	 *
	 * @param string $_perfil -> perfil del usuario que accede al menu
	 * @return retorna un resultset con los datos pedidos.
	 */
	public function coleccionPerfil($perfil){
		$this->seteaDato(array('perfil'=>$perfil));
		$resultado=$this->execSql(self::$COLECCIONPERFIL);
		if(!$resultado){
			echo "Fallo el Query";
		}
		return $resultado;
	}

	public function findByPadre($padre){
		$this->seteaDato(array('id_opcion'=>$padre));
		$resultado=$this->execSql(self::$FINDBYID);
		if(!$resultado){
			echo "Fallo el Query";
		}
		return $resultado;
	}

} // Fin clase MENUPGDAO



/**
 * Clase de manejo de los PERFILES asociados a la aplicacion
 *
 */
class PerfilPGDAO extends PGDAO {

	private static $FINDBYPERFIL="SELECT * FROM #dbName#.menu_perfiles WHERE trim(perfil)=trim('#perfil#') AND id_opcion=#id_opcion#";
	private static $FINDINICIO="SELECT * FROM #dbName#.perfiles WHERE  trim(perfil)=trim('#perfil#')";

	public function findByPerfil($_perfil,$_opcion){
		if($_opcion==0){
			$this->seteaDato(array('perfil'=>$_perfil));
			$resultado=$this->execSql(self::$FINDINICIO);
		}else{
			$this->seteaDato(array('perfil'=>$_perfil,'id_opcion'=>$_opcion));
			$resultado=$this->execSql(self::$FINDBYPERFIL);
		}
		if (!$resultado){
			echo "Fallo la ejecucion del Query";
		}
		return $resultado;
	}

} // Fin clase PERFILESPGDAO


class Acceso {

	/**
	 * Verifica si un perfil determiado esta habilitado en la aplicacion
	 *
	 * @param string $_perfil -> perfil del usuario
	 * @return verdadero o falso segun exista el perfil
	 */
	public function validaAcceso($_perfil='',$_opcion=''){
		$conf=CargaConfiguracion::getInstance();
		$tipodb=$conf->leeParametro("tipodb");
		if($tipodb=="my"){
			$nrows="mysql_numrows";
		} else {
			$nrows="pg_numrows";
		}

		//		echo "Perfil - ".$_perfil."**<br>";
		$retorno=false;
		$perfil=new PerfilPGDAO();
		$result=$perfil->findByPerfil($_perfil,$_opcion);
		if($nrows($result) != 0){
			$retorno=true;
		}
		return $retorno;
	}

	/**
	 * Determina las opciones de menu habilitadas para un determinado perfil
	 * , dependiendo de la opcion del menu desde donde se haya derivado.
	 *
	 * @param int $_padre -> menu del cual deriva
	 * @param string $_perfil -> perfil del usuario actual
	 * @return array multidimensional conteniendo el id de la opcion, la opcion y la descripcion
	 * de las opciones disponibles
	 */
	public function arrayOpciones($_padre=0,$_perfil=''){
		$conf=CargaConfiguracion::getInstance();
		$tipodb=$conf->leeParametro("tipodb");
		$tipoMenu=$conf->leeParametro("tipomenu");
		if($tipodb=="my"){
			$nrows="mysql_numrows";
			$fetch="mysql_fetch_array";
		} else {
			$nrows="pg_numrows";
			$fetch="pg_fetch_array";
		}
		$arrayOpcion=array();
		switch ($tipoMenu){
			case 'il':
				$arrayOpcion=$this->arrayOpcionesInLine($nrows,$fetch,$_padre,$_perfil);
				break;
			case 'pd':
				$arrayOpcion=$this->arrayOpcionesPullDown($nrows,$fetch,0,$_perfil);
				break;
			default :
				$arrayOpcion=$this->arrayOpcionesInLine($nrows,$fetch,$_padre,$_perfil);
				break;
		}
		return $arrayOpcion;

	}

	protected function arrayOpcionesInLine($nrows,$fetch,$_padre,$_perfil){
		$arrayOpcion=array();
		$menu=new MenuPGDAO();
		$result=$menu->coleccionPerfilHijos($_padre,$_perfil);
		if($nrows($result) != 0 ){
			while ($row = $fetch($result)){
				$arrayOpcion[]=array($row['id_opcion'],$row['opcion'],$row['descripcion'],$row['visible']);
			}
		} else {
			$result=$menu->findByPadre($_padre);
			while ($row=$fetch($result)) {
				$opcion=$row['padre'];
				$arrayOpcion=$this->arrayOpciones($opcion,$_perfil);
			}

		}
		return $arrayOpcion;

	}

	protected function arrayOpcionesPullDown($nrows,$fetch,$_padre,$_perfil){
		$arrayResult=array();
		$menu=new MenuPGDAO();
		$result=$menu->coleccionPerfilHijos($_padre,$_perfil);
		$padreAnt=0;
		if($nrows($result)!=0){
			while ($row=$fetch($result)){
				//if(substr($row['id_opcion'],-1)!='9'){
					$subArray=$this->arrayOpcionesPullDown($nrows,$fetch,$row['id_opcion'],$_perfil);
					$arrayResult[]=array($row['id_opcion'],$row['opcion'],$row['descripcion'],$row['visible'],$row['padre'],$subArray);
				//}
			}
		}
		return $arrayResult;
	}


} // Fin clase Acceso

?>