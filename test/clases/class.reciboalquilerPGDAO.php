<?php
include_once("generic_class/class.PGDAO.php");

class ReciboalquilerPGDAO extends PGDAO {
	
	protected $INSERT="INSERT INTO #dbName#.propiedad_recibo (id_recibo,id_contrato,rec_nro,fec_recibo,id_venc,montomes,diasret,intereses,totaldesc,total,observacion,id_user) values (#id_recibo#,#id_contrato#,'#rec_nro#',STR_TO_DATE('#fec_recibo#', '%d-%m-%Y'),#id_venc#,#montomes#,#diasret#,#intereses#,#totaldesc#,#total#,'#observacion#',#id_user#)";

	protected $DELETE="DELETE FROM #dbName#.propiedad_recibo WHERE id_recibo=#id_recibo#";
        
        protected $UPDATE="UPDATE #dbName#.propiedad_recibo SET id_contrato=#id_contrato#,rec_nro='#rec_nro#',fec_recibo=STR_TO_DATE('#fec_recibo#', '%d-%m-%Y'),id_venc=#id_venc#,montomes=#montomes#,diasret=#diasret#,intereses=#intereses#,totaldesc=#totaldesc#,total=#total#,observacion='#observacion#',id_user=#id_user# WHERE id_recibo=#id_recibo# ";

	protected $FINDBYID="SELECT id_recibo,id_contrato,rec_nro,DATE_FORMAT(fec_recibo,'%d-%m-%Y') as fec_recibo,id_venc,montomes,diasret,intereses,totaldesc,total,observacion,id_user FROM #dbName#.propiedad_recibo WHERE id_recibo=#id_recibo#";

	protected $FINDBYCLAVE="SELECT id_recibo,id_contrato, rec_nro,DATE_FORMAT(fec_recibo,'%d-%m-%Y') as fec_recibo,id_venc,montomes,diasret,intereses,totaldesc,total,observacion,id_user FROM #dbName#.propiedad_recibo							WHERE rec_nro LIKE  '#rec_nro#'";

        protected $COLECCION="SELECT id_recibo,id_contrato,rec_nro,DATE_FORMAT(fec_recibo,'%d-%m-%Y') as fec_recibo,id_venc,montomes,diasret,intereses,totaldesc,total,observacion,id_user FROM #dbName#.propiedad_recibo ORDER BY id_venc";

	protected $COLECCIONBYCONTRATO="SELECT id_recibo,id_contrato,rec_nro,DATE_FORMAT(fec_recibo,'%d-%m-%Y') as fec_recibo,id_venc,montomes,diasret,intereses,totaldesc,total,observacion,id_user FROM #dbName#.propiedad_recibo WHERE id_contrato=#id_contrato# ORDER BY id_venc";

        protected $COLECCIONBASE="SELECT id_recibo,id_contrato,rec_nro,DATE_FORMAT(fec_recibo,'%d-%m-%Y') as fec_recibo,id_venc,montomes,diasret,intereses,totaldesc,total,observacion,id_user FROM #dbName#.propiedad_recibo ";
        
        protected $MAXNRORECIBO="SELECT MAX(rec_nro) as rec_nro from #dbName#.propiedad_recibo";
        
        
	public function coleccionByContrato($id_contrato=0){
		$parametro=func_get_args();
                $where='';
                if($id_contrato!=0){
                    $where=' WHERE id_contrato='.$id_contrato;
                }
                $order=' ORDER BY id_recibo';
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
		if (!$resultado){
			$this->onError('COD_COLECCION',"X CONTRATO ".$this->COLECCIONBYCONTRATO.$where.$order);
		}
		return $resultado;
	}
        
	public function proximoRecibo(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->MAXNRORECIBO,$parametro);
		if (!$resultado){
			$this->onError('COD_COLECCION',"proximo Recibo ".$this->MAXNRORECIBO);
		}
		return $resultado;
	}
        
} // Fin clase DAO?>