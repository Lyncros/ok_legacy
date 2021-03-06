<?php
include_once("generic_class/class.PGDAO.php");

class ContactoPGDAO extends PGDAO {

	protected  $INSERT="INSERT INTO #dbName#.contactos (id_cont,apellido,nombre,email,razon,cuit,tipo_responsable,observacion,id_rubro,web) values (#id_cont#,'#apellido#','#nombre#','#email#','#razon#','#cuit#','#tipo_responsable#','#observacion#',#id_rubro#,'#web#')";
	
	protected  $DELETE="DELETE FROM #dbName#.contactos WHERE id_cont=#id_cont#";
	
	protected  $UPDATE="UPDATE #dbName#.contactos set apellido='#apellido#',nombre='#nombre#',email='#email#',razon='#razon#',cuit='#cuit#',tipo_responsable='#tipo_responsable#',observacion='#observacion#',id_rubro=#id_rubro#,web='#web#' WHERE id_cont=#id_cont# ";
	
	protected  $FINDBYCLAVE="SELECT id_cont,apellido,nombre,email,razon,cuit,tipo_responsable,observacion,id_rubro,web FROM #dbName#.contactos WHERE apellido='#apellido#' AND nombre='#nombre#' AND razon='#razon#'";
	protected  $FINDBYRAZON="SELECT id_cont,apellido,nombre,email,razon,cuit,tipo_responsable,observacion,id_rubro,web FROM #dbName#.contactos WHERE apellido LIKE '#apellido#' AND nombre LIKE '#nombre#'";
	
	protected  $FINDBYID="SELECT id_cont,apellido,nombre,email,razon,cuit,tipo_responsable,observacion,id_rubro,web FROM #dbName#.contactos WHERE id_cont=#id_cont#";
	
	protected  $COLECCION="SELECT id_cont,apellido,nombre,email,razon,cuit,tipo_responsable,observacion,id_rubro,web FROM #dbName#.contactos ORDER BY razon";
	
	protected  $COLECCIONRUBRO="SELECT id_cont,razon,cuit,tipo_responsable,apellido,nombre,email,observacion,id_rubro,web FROM #dbName#.contactos WHERE id_rubro=#id_rubro# ORDER BY razon";
	
	protected  $COLECCIONBASE="SELECT id_cont,razon,cuit,tipo_responsable,apellido,nombre,email,observacion,id_rubro,web FROM #dbName#.contactos ";
	
	public function coleccionFiltro($contacto='',$rubro=0){
		$parametro = func_get_args ();
		$where='';
		if(trim($contacto)!=''){
			$where=" concat(razon,' ',nombre,' ',apellido) like '%$contacto%' ";
		}
		if($rubro!=0){
			if($where!=''){
				$where.=' AND ';
			}
			$where.='id_rubro='.$rubro;
		}
			if($where!=''){
				$where=' WHERE '.$where;
			}
		$order=' ORDER BY razon ';
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
		if (!$resultado){
			$this->onError ( $COD_COLECCION, "COLECCION FILTRO ".$this->COLECCIONBASE.$where.$order);
		}
		return $resultado;
	}
	
	public function coleccionByRubro(){
		$parametro = func_get_args ();
		$resultado=$this->execSql($this->COLECCIONRUBRO,$parametro);
		if (!$resultado){
			$this->onError ( $COD_COLECCION, "COLECCION BY RUBRO ".$this->COLECCIONRUBRO );
		}
		return $resultado;
	}
	
	public function findByNombre(){
		$parametro = func_get_args ();
		$resultado=$this->execSql($this->FINDBYNOMBRE,$parametro);
		if (!$resultado){
			$this->onError ( $COD_UPDATE, "FIND BY NOMBRE" );
		}
		return $resultado;
	}
	
	public function findByRazon(){
		$parametro = func_get_args ();
		$resultado=$this->execSql($this->FINDBYRAZON,$parametro);
		if (!$resultado){
			$this->onError ( $COD_UPDATE, "FIND BY RAZON" );
		}
		return $resultado;
	}
	
	
} // Fin clase DAO
?>