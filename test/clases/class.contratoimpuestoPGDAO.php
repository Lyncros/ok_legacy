<?php

include_once("generic_class/class.PGDAO.php");

class ContratoimpuestoPGDAO extends PGDAO{

    protected $INSERT = "INSERT INTO #dbName#.propiedad_impuesto (id_prop,id_contrato,id_impuesto,porcentaje,prim_venc,aviso,observacion) values (#id_prop#,#id_contrato#,#id_impuesto#,'#porcentaje#',STR_TO_DATE('#prim_venc#', '%d-%m-%Y'),#aviso#,'#observacion#')";
    protected $DELETE = "DELETE FROM #dbName#.propiedad_impuesto WHERE id_improp=#id_improp#";
    protected $UPDATE = "UPDATE #dbName#.propiedad_impuesto SET id_prop=#id_prop#,id_contrato=#id_contrato#,id_impuesto=#id_impuesto#,porcentaje='#porcentaje#',prim_venc=STR_TO_DATE('#prim_venc#', '%d-%m-%Y'),aviso=#aviso#,observacion='#observacion#' WHERE id_improp=#id_improp# ";
    protected $FINDBYCLAVE = "SELECT id_improp,id_prop,id_contrato,id_impuesto,porcentaje,DATE_FORMAT(prim_venc,'%d-%m-%Y') as prim_venc,aviso,observacion FROM #dbName#.propiedad_impuesto WHERE id_prop=#id_prop# AND id_contrato=#id_contrato# AND id_impuesto=#id_impuesto#";
    protected $FINDBYID = "SELECT id_improp,id_prop,id_contrato,id_impuesto,porcentaje,DATE_FORMAT(prim_venc,'%d-%m-%Y') as prim_venc,aviso,observacion FROM #dbName#.propiedad_impuesto WHERE id_improp=#id_improp#";
    protected $COLECCION = "SELECT id_improp,id_prop,id_contrato,id_impuesto,porcentaje,DATE_FORMAT(prim_venc,'%d-%m-%Y') as prim_venc,aviso,observacion FROM #dbName#.propiedad_impuesto";
    protected $COLECCIONBASE = "SELECT id_improp,id_prop,id_contrato,id_impuesto,porcentaje,DATE_FORMAT(prim_venc,'%d-%m-%Y') as prim_venc,aviso,observacion FROM #dbName#.propiedad_impuesto";
    protected $DELETECONT = "DELETE FROM #dbName#.propiedad_impuesto WHERE id_contrato=#id_contrato#";

    public function coleccionByContrato($id_contrato=0) {
        $parametro = func_get_args();
        $resultado = '';
        $where = '';

        if ($id_contrato != '' && $id_contrato != 0) {
            $where = "  WHERE id_contrato=" . $id_contrato;
        }
        $order = " ORDER BY prim_venc desc";
        $resultado = $this->execSql($this->COLECCIONBASE . $where . $order, $parametro);
        
        if (!$resultado) {
            $this->onError("COD_COLLECION", "VENCIMIENTOS BY CONTRATO " . $this->COLECCIONBASE . $where . $order);
        }
        return $resultado;
    }
    
    public function deleteByContrato($id_contrato=0) {
        $parametro = func_get_args();

        if ($id_contrato != '' && $id_contrato != 0) {
            $resultado = $this->execSql($this->DELETECONT, $parametro);
        }
        
        if (!$resultado) {
            $this->onError("COD_DELETE", "BORRADO BY CONTRATO " . $this->DELETECONT);
        }
        return $resultado;
    }
    

    
}

?>