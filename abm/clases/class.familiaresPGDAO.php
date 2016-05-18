<?php
include_once("generic_class/class.PGDAO.php");


class FamiliaresPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.familiares (fecha_nac,id_cli,nombre,apellido,id_parent,nota,tipocont) values (STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),#id_cli#,'#nombre#','#apellido#','#id_parent#','#nota#',#tipocont#)";
	protected $DELETE="DELETE FROM #dbName#.familiares WHERE id_fam=#id_fam#";
	protected $UPDATE="UPDATE #dbName#.familiares SET fecha_nac=STR_TO_DATE('#fecha_base#', '%d-%m-%Y'), id_cli='#id_cli#',nombre='#nombre#',apellido='#apellido#',id_parent='#id_parent#',nota='#nota#',tipocont=#tipocont# WHERE id_fam=#id_fam# ";

	protected $FINDBYID="SELECT id_fam,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,id_cli,nombre,apellido,id_parent,nota,tipocont FROM #dbName#.familiares WHERE id_fam=#id_fam#";

	protected $FINDBYCLAVE="SELECT id_fam, DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,id_cli, nombre ,apellido,id_parent,nota,tipocont
							FROM #dbName#.familiares
							WHERE nota='#nota#' AND tipocont=#tipocont# AND fecha_nac='#fecha_nac#' AND id_cli='#id_cli#' AND nombre='#nombre#' AND apellido like '#apellido#'";

	protected $COLECCION="SELECT id_fam,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,id_cli,nombre,apellido,id_parent,nota,tipocont FROM #dbName#.familiares ORDER BY id_cli,nombre";

	protected $COLECCIONBASE="SELECT id_fam,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,id_cli,nombre,apellido,id_parent,nota,tipocont FROM #dbName#.familiares ";

	protected $SETPRINCIPAL = "UPDATE #dbName#.familiares SET principal=1 WHERE id_fam=#id_fam#";

	protected $UNSETPRINCIPAL = "UPDATE #dbName#.familiares SET principal=0 WHERE id_fam=#id_fam#";


	/**
	 *
	 * Selecciona los familiares que corresponden segun tres parametros posibles
	 * @param char $_tipo -> tipo de contacto U: usario C: cliente    O: contacto agenda
	 * @param int $_id -> id del contacto a buscar
	 * @param int $_principal -> definicion de si es familiar principal o no 1: principal
	 */
	public function coleccionByTipoId($_tipo,$_id){
		$parametro = func_get_args();
		$resultado='';
		$ordenprinc='';
		// WHERE familiares='#familiares#' ORDER BY nombre,apellido
		if($_tipo!=''){
			$where=" WHERE tipocont ='".$_tipo."' ";
			if($_id!=0){
				$where.=" AND id_cli=".$_id;
			}
			$order=" ORDER BY ".$ordenprinc."fecha_nac,id_cli,nombre,apellido ";
			$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
			if (!$resultado){
				$this->onError($COD_COLLECION,"FAMILIARES DE CONTACTO ".$this->COLECCIONBASE.$where.$order);
			}
		}
		return $resultado;
	}


} // Fin clase DAO
?>