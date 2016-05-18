<?php
include_once("generic_class/class.PGDAO.php");


class DomicilioPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.domicilios (tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal) values ('#tipodom#','#id_ubica#','#calle#','#nro#','#piso#','#dpto#','#entre1#','#entre2#','#cp#','#tipocont#',#id_cont#,#principal#)";
	protected $DELETE="DELETE FROM #dbName#.domicilios WHERE id_dom=#id_dom#";
	protected $UPDATE="UPDATE #dbName#.domicilios SET tipodom='#tipodom#', id_ubica='#id_ubica#',calle='#calle#',nro='#nro#',piso='#piso#',dpto='#dpto#',entre1='#entre1#',entre2='#entre2#',cp='#cp#',tipocont='#tipocont#',id_cont=#id_cont#,principal=#principal# WHERE id_dom=#id_dom# ";

	protected $FINDBYID="SELECT id_dom,tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal FROM #dbName#.domicilios WHERE id_dom=#id_dom#";

	protected $FINDBYCLAVE="SELECT id_dom, tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal
							FROM #dbName#.domicilios
							WHERE tipocont='#tipocont#' AND id_cont=#id_cont# AND tipodom='#tipodom#' AND id_ubica='#id_ubica#' AND calle like '#calle#'";

	protected $COLECCION="SELECT id_dom,tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal FROM #dbName#.domicilios ORDER BY id_ubica,";

	protected $COLECCIONBASE="SELECT id_dom,tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal FROM #dbName#.domicilios ";
	
	
/*	
	protected $INSERT="INSERT INTO #dbName#.domicilios (tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal) values ('#tipodom#','#id_ubica#','##','#calle#','#nro#','#piso#','#dpto#','#entre1#','#entre2#','#cp#','#tipocont#',#id_cont#,#principal#)";
	protected $DELETE="DELETE FROM #dbName#.domicilios WHERE id_dom=#id_dom#";
	protected $UPDATE="UPDATE #dbName#.domicilios SET tipodom='#tipodom#', id_ubica='#id_ubica#',='##',calle='#calle#',nro='#nro#',piso='#piso#',dpto='#dpto#',entre1='#entre1#',entre2='#entre2#',cp='#cp#',tipocont='#tipocont#',id_cont=#id_cont#,principal=#principal# WHERE id_dom=#id_dom# ";

	protected $FINDBYID="SELECT id_dom,tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal FROM #dbName#.domicilios WHERE id_dom=#id_dom#";

	protected $FINDBYCLAVE="SELECT id_dom, tipodom,id_ubica,  ,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal
							FROM #dbName#.domicilios
							WHERE tipocont='#tipocont#' AND id_cont=#id_cont# AND tipodom='#tipodom#' AND id_ubica='#id_ubica#' AND ='##' AND calle like '#calle#'";

	protected $COLECCION="SELECT id_dom,tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal FROM #dbName#.domicilios ORDER BY id_ubica,";

	protected $COLECCIONBASE="SELECT id_dom,tipodom,id_ubica,calle,nro,piso,dpto,entre1,entre2,cp,tipocont,id_cont,principal FROM #dbName#.domicilios ";
*/
	protected $SETPRINCIPAL = "UPDATE #dbName#.domicilios SET principal=1 WHERE id_dom=#id_dom#";

	protected $UNSETPRINCIPAL = "UPDATE #dbName#.domicilios SET principal=0 WHERE id_dom=#id_dom#";

	/**
	 * Setea el domicilio indicado como principal para ese contacto
	 */
	public function seteaPrincipal(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->SETPRINCIPAL,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"SETEO PRINCIPAL ".$this->SETPRINCIPAL);
		}
		return $resultado;
	}

	/**
	 * Elimina la marca de principal para el domicilio indicado
	 */
	public function reseteaPrincipal(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->UNSETPRINCIPAL,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"DESETEO PRINCIPAL ".$this->UNSETPRINCIPAL);
		}
		return $resultado;
	}


	/**
	 *
	 * Selecciona los domicilios que corresponden segun tres parametros posibles
	 * @param char $_tipo -> tipo de contacto U: usario C: cliente
	 * @param int $_id -> id del contacto a buscar
	 * @param int $_principal -> definicion de si es domicilio principal o no 1: principal
	 */
	public function coleccionByTipoId($_tipo,$_id,$_principal=0){
		$parametro = func_get_args();
		$resultado='';
		$ordenprinc='';
		// WHERE domicilios='#domicilios#' ORDER BY ,calle
		if($_tipo!=''){
			$where=" WHERE tipocont ='".$_tipo."' ";
			if($_id!=0){
				$where.=" AND id_cont=".$_id;
			}
			if($_principal==1){
				$where.=" AND principal=1 ";
				$ordenprinc=" principal DESC, ";
			}
			$order=" ORDER BY ".$ordenprinc."tipodom,id_ubica,calle,nro,piso,dpto ";
			$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
			if (!$resultado){
				$this->onError($COD_COLLECION,"TELEFONOS DE CONTACO ".$this->COLECCIONBASE.$where.$order);
			}
		}
		return $resultado;
	}


} // Fin clase DAO
?>