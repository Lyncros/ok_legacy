<?php

include_once("generic_class/class.PGDAO.php");

class ContratoinvolucradoPGDAO extends PGDAO{

    protected $INSERT = "INSERT INTO #dbName#.propiedad_involucrados (id_cli,id_contrato,id_relacion) values (#id_cli#,#id_contrato#,#id_relacion#)";
    protected $DELETE = "DELETE FROM #dbName#.propiedad_involucrados WHERE id_involucrado=#id_involucrado#";
    protected $UPDATE = "UPDATE #dbName#.propiedad_involucrados SET id_cli=#id_cli#,id_contrato=#id_contrato#,id_relacion=#id_relacion# WHERE id_involucrado=#id_involucrado# ";
    protected $FINDBYCLAVE = "SELECT id_involucrado,id_cli,id_contrato,id_relacion FROM #dbName#.propiedad_involucrados WHERE id_cli=#id_cli# AND id_contrato=#id_contrato# AND id_relacion=#id_relacion#";
    protected $FINDBYID = "SELECT id_involucrado,id_cli,id_contrato,id_relacion FROM #dbName#.propiedad_involucrados WHERE id_involucrado=#id_involucrado#";
    protected $COLECCION = "SELECT id_involucrado,id_cli,id_contrato,id_relacion FROM #dbName#.propiedad_involucrados";
    protected $COLECCIONBASE = "SELECT id_involucrado,id_cli,id_contrato,id_relacion FROM #dbName#.propiedad_involucrados";
    protected $DELETECONT = "DELETE FROM #dbName#.propiedad_involucrados WHERE id_contrato=#id_contrato#";

    public function coleccionByContrato($id_contrato=0) {
        $parametro = func_get_args();
        $resultado = '';
        $where = '';

        if ($id_contrato != '' && $id_contrato != 0) {
            $where = "  WHERE id_contrato=" . $id_contrato;
        }
        $order = " ORDER BY id_relacion desc";
        $resultado = $this->execSql($this->COLECCIONBASE . $where . $order, $parametro);
        
        if (!$resultado) {
            $this->onError("COD_COLLECION", "INVOLUCRADOS BY CONTRATO " . $this->COLECCIONBASE . $where . $order);
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