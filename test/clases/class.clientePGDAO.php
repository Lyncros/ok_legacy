<?php
include_once("generic_class/class.PGDAO.php");

class ClientePGDAO extends PGDAO {

//	protected  $INSERT="INSERT INTO #dbName#.clientes (id_cli,usuario,clave,apellido,nombre,email,observacion,fecha_base,nueva_clave,fecha_nueva,errores,activa,tipo_doc,nro_doc,fecha_nac,tipo_responsable,cuit,id_pais,empresa,id_posemp,id_estciv,grupofam,publico,capcompra,news,tipocont,categoria,asignacion,sexo) values (#id_cli#,'#usuario#','#clave#','#apellido#','#nombre#','#email#','#observacion#',STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),'#nueva_clave#',STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),#errores#,#activa#,'#tipo_doc#','#nro_doc#',STR_TO_DATE('#fecha_nac#', '%d-%m-%Y'),'#tipo_responsable#','#cuit#','#id_pais#','#empresa#',#id_posemp#,#id_estciv#,#grupofam#,#publico#,#capcompra#,'#news#',#tipocont#,#categoria#,#asignacion#,#sexo#)";
        protected  $INSERT="INSERT INTO #dbName#.clientes (id_cli,usuario,clave,apellido,nombre,email,observacion,fecha_base,nueva_clave,fecha_nueva,errores,activa,tipo_doc,nro_doc,fecha_nac,tipo_responsable,cuit,id_pais,empresa,id_posemp,id_estciv,grupofam,publico,capcompra,news,tipocont,categoria,asignacion,sexo) values (#id_cli#,'#usuario#','#clave#','#apellido#','#nombre#','#email#','#observacion#',STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),'#nueva_clave#',STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),#errores#,#activa#,'#tipo_doc#','#nro_doc#',STR_TO_DATE('#fecha_nac#', '%d-%m-%Y'),'#tipo_responsable#','#cuit#','#id_pais#','#empresa#',#id_posemp#,#id_estciv#,#grupofam#,#publico#,#capcompra#,'#news#',#tipocont#,#categoria#,#asignacion#,'#sexo#')";

	protected  $DELETE="DELETE FROM #dbName#.clientes WHERE id_cli=#id_cli#";

//	protected  $UPDATE="UPDATE #dbName#.clientes set usuario='#usuario#',clave='#clave#',apellido='#apellido#',nombre='#nombre#',email='#email#',observacion='#observacion#',fecha_base=STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),nueva_clave='#nueva_clave#',fecha_nueva=STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),errores=#errores#,activa=#activa#,tipo_doc='#tipo_doc#',nro_doc='#nro_doc#',fecha_nac=STR_TO_DATE('#fecha_nac#', '%d-%m-%Y'),tipo_responsable='#tipo_responsable#',cuit='#cuit#',id_pais='#id_pais#',empresa='#empresa#',id_posemp=#id_posemp#,id_estciv=#id_estciv#,grupofam=#grupofam#,publico=#publico#,capcompra=#capcompra#,news='#news#',tipocont=#tipocont#,categoria=#categoria#,asignacion=#asignacion#,sexo=#sexo# WHERE id_cli=#id_cli# ";
	protected  $UPDATE="UPDATE #dbName#.clientes set usuario='#usuario#',clave='#clave#',apellido='#apellido#',nombre='#nombre#',email='#email#',observacion='#observacion#',fecha_base=STR_TO_DATE('#fecha_base#', '%d-%m-%Y'),nueva_clave='#nueva_clave#',fecha_nueva=STR_TO_DATE('#fecha_nueva#', '%d-%m-%Y'),errores=#errores#,activa=#activa#,tipo_doc='#tipo_doc#',nro_doc='#nro_doc#',fecha_nac=STR_TO_DATE('#fecha_nac#', '%d-%m-%Y'),tipo_responsable='#tipo_responsable#',cuit='#cuit#',id_pais='#id_pais#',empresa='#empresa#',id_posemp=#id_posemp#,id_estciv=#id_estciv#,grupofam=#grupofam#,publico=#publico#,capcompra=#capcompra#,news='#news#',tipocont=#tipocont#,categoria=#categoria#,asignacion=#asignacion#,sexo=#sexo# WHERE id_cli=#id_cli# ";

	protected  $FINDBYCLAVE="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa,tipo_doc,nro_doc,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,tipo_responsable,cuit,id_pais,empresa,id_posemp,id_estciv,grupofam,publico,capcompra,news,tipocont,categoria,asignacion,sexo FROM #dbName#.clientes WHERE usuario='#usuario#' OR (apellido LIKE '#apellido#' AND nombre LIKE '#nombre#') OR (tipo_doc='#tipo_doc#' AND nro_doc='#nro_doc#' AND nro_doc!='')";
	protected  $FINDBYNOMBRE="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa,tipo_doc,nro_doc,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,tipo_responsable,cuit,id_pais,empresa,id_posemp,id_estciv,grupofam,publico,capcompra,news,tipocont,categoria,asignacion,sexo FROM #dbName#.clientes WHERE apellido LIKE '#apellido#' AND nombre LIKE '#nombre#'";

	protected  $FINDBYID="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa,tipo_doc,nro_doc,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,tipo_responsable,cuit,id_pais,empresa,id_posemp,id_estciv,grupofam,publico,capcompra,news,tipocont,categoria,asignacion,sexo FROM #dbName#.clientes WHERE id_cli=#id_cli#";

	protected  $COLECCION="SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa,tipo_doc,nro_doc,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,tipo_responsable,cuit,id_pais,empresa,id_posemp,id_estciv,grupofam,publico,capcompra,news,tipocont,categoria,asignacion,sexo FROM #dbName#.clientes ORDER BY apellido, nombre";

	protected $COLECCIONBASE = "SELECT id_cli,usuario,clave,apellido,nombre,email,observacion,DATE_FORMAT(fecha_base,'%d-%m-%Y') as fecha_base,nueva_clave,DATE_FORMAT(fecha_nueva,'%d-%m-%Y') as fecha_nueva,errores,activa,tipo_doc,nro_doc,DATE_FORMAT(fecha_nac,'%d-%m-%Y') as fecha_nac,tipo_responsable,cuit,id_pais,empresa,id_posemp,id_estciv,grupofam,publico,capcompra,news,tipocont,categoria,asignacion,sexo FROM #dbName#.clientes ";

	protected $ACTIVAR="UPDATE #dbName#.clientes SET activa=1 WHERE id_cli=#id_cli#";

	protected $DESACTIVAR="UPDATE #dbName#.clientes SET activa=0 WHERE id_cli=#id_cli#";

	public function findByNombre(){
		$parametro = func_get_args ();
		$resultado=$this->execSql($this->FINDBYNOMBRE,$parametro);
		if (!$resultado){
			$this->onError ( $COD_UPDATE, "FIND BY NOMBRE" );
		}
		return $resultado;
	}

	public function activar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->ACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"ACTIVACION de Usuario");
		}
		return $resultado;
	}

	public function desactivar(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DESACTIVAR,$parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"INACTIVACION de Usuario");
		}
		return $resultado;
	}

	public function coleccionFiltrada($param="",$pos=1){
		$parametro = func_get_args();
		if($param == ""){
			$where = "";
		}else{
                    if($pos==0){
                        $w3='';
                        $w2='';
                        switch(strlen($param)){
                            case 3:
                                $w3=" OR concat(apellido,nombre) LIKE '".substr($param, 2,1)."%'";
                            case 2:
                                $w2=" OR concat(apellido,nombre) LIKE '".substr($param, 1,1)."%'".$w3;
                            case 1:
                                $where=" WHERE concat(apellido,nombre) LIKE '".substr($param, 0,1)."%'".$w2;
                                break;
                            default:
                                $where='';
                        }
                    }else{
			$where = " WHERE concat(apellido,', ',nombre) LIKE '%".$param."%'";
                    }
		}
		$orden = " ORDER BY apellido,nombre";
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$orden, $parametro);
		if (!$resultado){
			$this->onError($COD_UPDATE,"FILTRAR de Clientes");
		}
		return $resultado;
	}


} // Fin clase DAO
?>