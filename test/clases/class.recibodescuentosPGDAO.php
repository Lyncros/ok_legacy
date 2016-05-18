<?php

include_once("generic_class/class.PGDAO.php");

class RecibodescuentosPGDAO extends PGDAO{

    protected $INSERT = "INSERT INTO #dbName#.propiedad_recdesc (fec_desc,id_recibo,concepto,importe,observacion) values (STR_TO_DATE('#fec_desc#', '%d-%m-%Y'),#id_recibo#,'#concepto#',#importe#,'#observacion#')";
    protected $DELETE = "DELETE FROM #dbName#.propiedad_recdesc WHERE id_recdesc=#id_recdesc#";
    protected $UPDATE = "UPDATE #dbName#.propiedad_recdesc SET fec_desc=STR_TO_DATE('#fec_desc#', '%d-%m-%Y'),id_recibo=#id_recibo#,concepto='#concepto#',importe=#importe#,observacion='#observacion#' WHERE id_recdesc=#id_recdesc# ";
    protected $FINDBYCLAVE = "SELECT id_recdesc,DATE_FORMAT(fec_desc,'%d-%m-%Y') as fec_desc,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdesc WHERE fec_desc=#fec_desc# AND id_recibo=#id_recibo# AND concepto=#concepto# AND id_improp=#id_improp# AND fec_ven='##'";
    protected $FINDBYID = "SELECT id_recdesc,DATE_FORMAT(fec_desc,'%d-%m-%Y') as fec_desc,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdesc WHERE id_recdesc=#id_recdesc#";
    protected $COLECCION = "SELECT id_recdesc,DATE_FORMAT(fec_desc,'%d-%m-%Y') as fec_desc,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdesc";
    protected $COLECCIONBASE = "SELECT id_recdesc,DATE_FORMAT(fec_desc,'%d-%m-%Y') as fec_desc,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdesc";
    protected $DELETECONT = "DELETE FROM #dbName#.propiedad_recdesc WHERE id_recibo=#id_recibo#";

    public function coleccionByRecibo($id_recibo=0) {
        $parametro = func_get_args();
        $resultado = '';
        $where = '';

        if ($id_recibo != '' && $id_recibo != 0) {
            $where = " WHERE id_recibo=" . $id_recibo;
        }
        $resultado = $this->execSql($this->COLECCIONBASE . $where , $parametro);
        
        if (!$resultado) {
            $this->onError("COD_COLLECION", "VENCIMIENTOS BY RECIBO " . $this->COLECCIONBASE . $where );
        }
        return $resultado;
    }

    public function deleteByRecibo($id_recibo=0) {
        $parametro = func_get_args();

        if ($id_recibo != '' && $id_recibo != 0) {
            $resultado = $this->execSql($this->DELETECONT, $parametro);
        }
        
        if (!$resultado) {
            $this->onError("COD_DELETE", "BORRADO BY RECIBO " . $this->DELETECONT);
        }
        return $resultado;
    }
    
    
}

?>