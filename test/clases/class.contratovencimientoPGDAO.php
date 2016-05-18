<?php

include_once("generic_class/class.PGDAO.php");

class ContratovencimientoPGDAO extends PGDAO{

    protected $INSERT = "INSERT INTO #dbName#.propiedad_vencimiento (id_prop,id_contrato,tipo_venc,id_improp,fec_ven,importe,fec_carga,observacion) values (#id_prop#,#id_contrato#,#tipo_venc#,#id_improp#,STR_TO_DATE('#fec_ven#', '%d-%m-%Y'),#importe#,STR_TO_DATE('#fec_carga#', '%d-%m-%Y'),'#observacion#')";
    protected $DELETE = "DELETE FROM #dbName#.propiedad_vencimiento WHERE id_venc=#id_venc#";
    protected $UPDATE = "UPDATE #dbName#.propiedad_vencimiento SET id_prop=#id_prop#,id_contrato=#id_contrato#,tipo_venc=#tipo_venc#,id_improp=#id_improp#,fec_ven=STR_TO_DATE('#fec_ven#', '%d-%m-%Y'),importe=#importe#,fec_carga=STR_TO_DATE('#fec_carga#', '%d-%m-%Y'),observacion='#observacion#' WHERE id_venc=#id_venc# ";
    protected $FINDBYCLAVE = "SELECT id_venc,id_prop,id_contrato,tipo_venc,id_improp,DATE_FORMAT(fec_ven,'%d-%m-%Y') as fec_ven,importe,DATE_FORMAT(fec_carga,'%d-%m-%Y') as fec_carga,observacion,pagado FROM #dbName#.propiedad_vencimiento WHERE id_prop=#id_prop# AND id_contrato=#id_contrato# AND tipo_venc=#tipo_venc# AND id_improp=#id_improp# AND fec_ven='##'";
    protected $FINDBYID = "SELECT id_venc,id_prop,id_contrato,tipo_venc,id_improp,DATE_FORMAT(fec_ven,'%d-%m-%Y') as fec_ven,importe,DATE_FORMAT(fec_carga,'%d-%m-%Y') as fec_carga,observacion,pagado FROM #dbName#.propiedad_vencimiento WHERE id_venc=#id_venc#";
    protected $COLECCION = "SELECT id_venc,id_prop,id_contrato,tipo_venc,id_improp,DATE_FORMAT(fec_ven,'%d-%m-%Y') as fec_ven,importe,DATE_FORMAT(fec_carga,'%d-%m-%Y') as fec_carga,observacion,pagado FROM #dbName#.propiedad_vencimiento";
    protected $COLECCIONBASE = "SELECT id_venc,id_prop,id_contrato,tipo_venc,id_improp,fec_ven as fec_orden,DATE_FORMAT(fec_ven,'%d-%m-%Y') as fec_ven,importe,DATE_FORMAT(fec_carga,'%d-%m-%Y') as fec_carga,observacion,pagado FROM #dbName#.propiedad_vencimiento";
    protected $DELETECONT = "DELETE FROM #dbName#.propiedad_vencimiento WHERE id_contrato=#id_contrato#";

    protected $MARCAPAGO="UPDATE #dbName#.propiedad_vencimiento SET pagado=1 where id_venc=#id_venc#";
    protected $DESMARCAPAGO="UPDATE #dbName#.propiedad_vencimiento SET pagado=0 where id_venc=#id_venc#";
    
    
    public function coleccionByContrato($id_contrato=0) {
        $parametro = func_get_args();
        $resultado = '';
        $where = '';

        if ($id_contrato != '' && $id_contrato != 0) {
            $where = " WHERE id_contrato=" . $id_contrato;
        }
        $order = " ORDER BY fec_orden";
        $resultado = $this->execSql($this->COLECCIONBASE . $where . $order, $parametro);
        
        if (!$resultado) {
            $this->onError("COD_COLLECION", "VENCIMIENTOS BY CONTRATO " . $this->COLECCIONBASE . $where . $order);
        }
        return $resultado;
    }

    public function coleccionPendientesByContrato($id_contrato=0) {
        $parametro = func_get_args();
        $resultado = '';
        $where = '';

        if ($id_contrato != '' && $id_contrato != 0) {
            $where = " WHERE pagado=0 AND id_contrato=" . $id_contrato;
        }
        $order = " ORDER BY fec_orden";
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
    
    public function marcaPagado(){
        $parametro = func_get_args();
        $resultado = $this->execSql($this->MARCAPAGO, $parametro);
        
        if (!$resultado) {
            $this->onError("COD_UPDATE", "MARCA PAGADO " . $this->MARCAPAGO);
        }
        return $resultado;
        
    }

    public function desmarcaPagado(){
        $parametro = func_get_args();
        $resultado = $this->execSql($this->DESMARCAPAGO, $parametro);
        
        if (!$resultado) {
            $this->onError("COD_UPDATE", "DESMARCA PAGADO " . $this->DESMARCAPAGO);
        }
        return $resultado;
        
    }
    
}

?>