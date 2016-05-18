<?php
include_once("generic_class/class.PGDAO.php");


class MedioselectronicosPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.medioselectronicos (id_tipomed,contacto,comentario,tipocont,id_cli,principal) values ('#id_tipomed#','#contacto#','#comentario#','#tipocont#',#id_cli#,#principal#)";
	protected $DELETE="DELETE FROM #dbName#.medioselectronicos WHERE id_medio=#id_medio#";
	protected $UPDATE="UPDATE #dbName#.medioselectronicos SET id_tipomed='#id_tipomed#', contacto='#contacto#',comentario='#comentario#',tipocont='#tipocont#',id_cli=#id_cli#,principal=#principal# WHERE id_medio=#id_medio# ";

	protected $FINDBYID="SELECT id_medio,id_tipomed,contacto,comentario,tipocont,id_cli,principal FROM #dbName#.medioselectronicos WHERE id_medio=#id_medio#";

	protected $FINDBYCLAVE="SELECT id_medio, id_tipomed,contacto, comentario ,tipocont,id_cli,principal
							FROM #dbName#.medioselectronicos
							WHERE tipocont='#tipocont#' AND id_cli=#id_cli# AND id_tipomed='#id_tipomed#' AND contacto='#contacto#' AND comentario='#comentario#' ";

	protected $COLECCION="SELECT id_medio,id_tipomed,contacto,comentario,tipocont,id_cli,principal FROM #dbName#.medioselectronicos ORDER BY contacto,comentario";

	protected $COLECCIONBASE="SELECT id_medio,id_tipomed,contacto,comentario,tipocont,id_cli,principal FROM #dbName#.medioselectronicos ";

	protected $SETPRINCIPAL = "UPDATE #dbName#.medioselectronicos SET principal=1 WHERE id_medio=#id_medio#";

	protected $UNSETPRINCIPAL = "UPDATE #dbName#.medioselectronicos SET principal=0 WHERE id_medio=#id_medio#";

	/**
	 * Setea el telefono indicado como principal para ese contacto
	 */
	public function seteaPrincipal(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->SETPRINCIPAL,$parametro);
		if (!$resultado){
			$this->onError("COD_UPDATE","SETEO PRINCIPAL ".$this->SETPRINCIPAL);
		}
		return $resultado;
	}

	/**
	 * Elimina la marca de principal para el telefono indicado
	 */
	public function reseteaPrincipal(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->UNSETPRINCIPAL,$parametro);
		if (!$resultado){
			$this->onError("COD_UPDATE","DESETEO PRINCIPAL ".$this->UNSETPRINCIPAL);
		}
		return $resultado;
	}


	/**
	 *
	 * Selecciona los medioselectronicos que corresponden segun tres parametros posibles
	 * @param char $_tipo -> tipo de contacto U: usario C: cliente    O: contacto agenda
	 * @param int $_id -> id del contacto a buscar
	 * @param int $_principal -> definicion de si es telefono principal o no 1: principal
	 */
	public function coleccionByTipoId($_tipo,$_id,$_principal=0){
		$parametro = func_get_args();
		$resultado='';
		$ordenprinc='';
		// WHERE medioselectronicos='#medioselectronicos#' ORDER BY comentario
		if($_tipo!=''){
			$where=" WHERE tipocont ='".$_tipo."' ";
			if($_id!=0){
				$where.=" AND id_cli=".$_id;
			}
			if($_principal==1){
				$where.=" AND principal=1 ";
				$ordenprinc=" principal DESC, ";
			}
			$order=" ORDER BY ".$ordenprinc."id_tipomed,contacto,comentario ";
			$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
			if (!$resultado){
				$this->onError("COD_COLLECION","TELEFONOS DE CONTACO ".$this->COLECCIONBASE.$where.$order);
			}
		}
		return $resultado;
	}


} // Fin clase DAO
?>