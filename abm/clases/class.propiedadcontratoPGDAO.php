<?php
include_once("generic_class/class.PGDAO.php");

class PropiedadcontratoPGDAO extends PGDAO {
	
	protected $INSERT="INSERT INTO #dbName#.propiedad_contrato (id_prop,tipo_contrato,cont_nro,fec_firma,fec_ini,fec_fin,observacion) values (#id_prop#,'#tipo_contrato#','#cont_nro#',STR_TO_DATE('#fec_firma#', '%d-%m-%Y'),STR_TO_DATE('#fec_ini#', '%d-%m-%Y'),STR_TO_DATE('#fec_fin#', '%d-%m-%Y'),'#observacion#')";

	protected $DELETE="DELETE FROM #dbName#.propiedad_contrato WHERE id_contrato=#id_contrato#";
        
        protected $UPDATE="UPDATE #dbName#.propiedad_contrato SET id_prop=#id_prop#,tipo_contrato='#tipo_contrato#',cont_nro='#cont_nro#',fec_firma=STR_TO_DATE('#fec_firma#', '%d-%m-%Y'),fec_ini=STR_TO_DATE('#fec_ini#', '%d-%m-%Y'),fec_fin=STR_TO_DATE('#fec_fin#', '%d-%m-%Y'),observacion='#observacion#' WHERE id_contrato=#id_contrato# ";

	protected $FINDBYID="SELECT id_contrato,id_prop,tipo_contrato,cont_nro,DATE_FORMAT(fec_firma,'%d-%m-%Y') as fec_firma,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,observacion FROM #dbName#.propiedad_contrato WHERE id_contrato=#id_contrato#";

	protected $FINDBYCLAVE="SELECT id_contrato,id_prop, tipo_contrato,cont_nro,DATE_FORMAT(fec_firma,'%d-%m-%Y') as fec_firma,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,observacion FROM #dbName#.propiedad_contrato							WHERE cont_nro LIKE  '#cont_nro#'";

        protected $COLECCION="SELECT id_contrato,id_prop,tipo_contrato,cont_nro,DATE_FORMAT(fec_firma,'%d-%m-%Y') as fec_firma,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,observacion FROM #dbName#.propiedad_contrato ORDER BY fec_ini";

	protected $COLECCIONBYPROP="SELECT id_contrato,id_prop,tipo_contrato,cont_nro,DATE_FORMAT(fec_firma,'%d-%m-%Y') as fec_firma,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,observacion FROM #dbName#.propiedad_contrato WHERE id_prop=#id_prop# ORDER BY fec_ini";

        protected $COLECCIONBASE="SELECT id_contrato,id_prop,tipo_contrato,cont_nro,DATE_FORMAT(fec_firma,'%d-%m-%Y') as fec_firma,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,observacion FROM #dbName#.propiedad_contrato ";
        
        /* propiedadcontrato  id_contrato INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,  id_prop INTEGER UNSIGNED NOT NULL,  cont_nro VARCHAR(45) NOT NULL,  fecha DATETIME NOT NULL,  fec_ini INTEGER UNSIGNED NOT NULL, */
	public function coleccionByProp(){
		$parametro=func_get_args();
		$resultado=$this->execSql($this->COLECCIONBYPROP,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"X PROP");
		}
		return $resultado;
	}
        
        /**
         * Retorna una coleccion de contratos segun el tipo y estado definido en los parametros
         * @param string $tipo -> tipo de contrato si se omite, se rotornaran todos los tipos
         * @param char $estado -> identificador del tipo de estado del contrato 'a': Activo    'v': Vencido si se omite se retornaran todos los estados
         * @return string[][] 
         */
        public function coleccionByTipoEstado($tipo,$estado){
		$parametro=func_get_args();
                $where='';
                if($tipo!=''){
                    $where=" WHERE tipo_contrato='$tipo' ";
                }
                if($estado!=''){
                    if($where==''){
                        $where=' WHERE ';
                    }else{
                        $where.=" AND ";                        
                    }
                    if($estado=='a'){
                        $where.=" fec_fin >= now() ";
                    }else{
                        $where.=" fec_fin < now() ";
                    }
                }
                $order=" ORDER BY fec_ini";
                
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
		if (!$resultado){
			$this->onError($COD_COLECCION,"POR ESTADO Y TIPO ".$this->COLECCIONBASE.$where.$order);
		}
		return $resultado;
            
        }


        
} // Fin clase DAO?>