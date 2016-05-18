<?php

include_once("generic_class/class.PGDAO.php");

class RecibodocumentosPGDAO extends PGDAO{

    protected $INSERT = "INSERT INTO #dbName#.propiedad_recdocs (id_impuesto,id_recibo,concepto,importe,observacion) values (#id_impuesto#,#id_recibo#,'#concepto#',#importe#,'#observacion#')";
    protected $DELETE = "DELETE FROM #dbName#.propiedad_recdocs WHERE id_recdocs=#id_recdocs#";
    protected $UPDATE = "UPDATE #dbName#.propiedad_recdocs SET id_impuesto=#id_impuesto#,id_recibo=#id_recibo#,concepto='#concepto#',importe=#importe#,observacion='#observacion#' WHERE id_recdocs=#id_recdocs# ";
    protected $FINDBYCLAVE = "SELECT id_recdocs,id_impuesto,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdocs WHERE id_impuesto=#id_impuesto# AND id_recibo=#id_recibo# AND concepto=#concepto# AND id_improp=#id_improp# AND fec_ven='##'";
    protected $FINDBYID = "SELECT id_recdocs,id_impuesto,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdocs WHERE id_recdocs=#id_recdocs#";
    protected $COLECCION = "SELECT id_recdocs,id_impuesto,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdocs";
    protected $COLECCIONBASE = "SELECT id_recdocs,id_impuesto,id_recibo,concepto,importe,observacion FROM #dbName#.propiedad_recdocs";
    protected $DELETECONT = "DELETE FROM #dbName#.propiedad_recdocs WHERE id_recibo=#id_recibo#";

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