<?php
include_once("generic_class/class.PGDAO.php");

class ReciborendicionPGDAO extends PGDAO {
	
//	protected $INSERT="INSERT INTO #dbName#.propiedad_rendicion (id_rendicion,id_contrato,id_recibo,fec_rend,comision,id_user) values (#id_rendicion#,#id_contrato#,'#id_recibo#',STR_TO_DATE('#fec_rend#', '%d-%m-%Y'),#comision#,#id_user#)";
	protected $INSERT="INSERT INTO #dbName#.propiedad_rendicion (id_contrato,id_recibo,fec_rend,comision,id_user) values (#id_contrato#,'#id_recibo#',STR_TO_DATE('#fec_rend#', '%d-%m-%Y'),#comision#,#id_user#)";

	protected $DELETE="DELETE FROM #dbName#.propiedad_rendicion WHERE id_rendicion=#id_rendicion#";
        
        protected $UPDATE="UPDATE #dbName#.propiedad_rendicion SET id_contrato=#id_contrato#,id_recibo='#id_recibo#',fec_rend=STR_TO_DATE('#fec_rend#', '%d-%m-%Y'),comision=#comision#,id_user=#id_user# WHERE id_rendicion=#id_rendicion# ";

	protected $FINDBYID="SELECT id_rendicion,id_contrato,id_recibo,DATE_FORMAT(fec_rend,'%d-%m-%Y') as fec_rend,comision,id_user FROM #dbName#.propiedad_rendicion WHERE id_rendicion=#id_rendicion#";

	protected $FINDBYCLAVE="SELECT id_rendicion,id_contrato, id_recibo,DATE_FORMAT(fec_rend,'%d-%m-%Y') as fec_rend,comision,id_user FROM #dbName#.propiedad_rendicion							WHERE id_recibo LIKE  '#id_recibo#'";

        protected $COLECCION="SELECT id_rendicion,id_contrato,id_recibo,DATE_FORMAT(fec_rend,'%d-%m-%Y') as fec_rend,comision,id_user FROM #dbName#.propiedad_rendicion ORDER BY ";

	protected $COLECCIONBYCONTRATO="SELECT id_rendicion,id_contrato,id_recibo,DATE_FORMAT(fec_rend,'%d-%m-%Y') as fec_rend,comision,id_user FROM #dbName#.propiedad_rendicion WHERE id_contrato=#id_contrato# ORDER BY ";

        protected $COLECCIONBASE="SELECT id_rendicion,id_contrato,id_recibo,DATE_FORMAT(fec_rend,'%d-%m-%Y') as fec_rend,comision,id_user FROM #dbName#.propiedad_rendicion ";
        
        
	public function coleccionByContrato($id_contrato=0){
		$parametro=func_get_args();
                $where='';
                if($id_contrato!=0){
                    $where=' WHERE id_contrato='.$id_contrato;
                }
                $order=' ORDER BY id_rendicion';
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
		if (!$resultado){
			$this->onError('COD_COLECCION',"X CONTRATO ".$this->COLECCIONBYCONTRATO.$where.$order);
		}
		return $resultado;
	}
        
	public function coleccionByRecibo($id_recibo=0){
		$parametro=func_get_args();
                $where='';
                if($id_contrato!=0){
                    $where=' WHERE id_recibo='.$id_recibo;
                }
                $order=' ORDER BY id_rendicion';
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
		if (!$resultado){
			$this->onError('COD_COLECCION',"X RECIBO ".$this->COLECCIONBYCONTRATO.$where.$order);
		}
		return $resultado;
	}
        
} // Fin clase DAO?>