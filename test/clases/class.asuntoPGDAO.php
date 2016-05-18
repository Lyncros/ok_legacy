<?php

include_once("generic_class/class.PGDAO.php");

class AsuntoPGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.asuntos (id_asunto,id_cli,id_tipoasu,titulo,fec_inicio,comentario,user_ini,estado) values (#id_asunto#,#id_cli#,#id_tipoasu#,'#titulo#',STR_TO_DATE('#fec_inicio#', '%d/%m/%Y'),'#comentario#',#user_ini#,'#estado#')";
    protected $DELETE = "DELETE FROM #dbName#.asuntos WHERE id_asunto=#id_asunto#";
    protected $UPDATE = "UPDATE #dbName#.asuntos SET id_cli=#id_cli#,id_tipoasu=#id_tipoasu#,titulo='#titulo#',fec_inicio=STR_TO_DATE('#fec_inicio#', '%d/%m/%Y'),fec_cierre=STR_TO_DATE('#fec_cierre#', '%d/%m/%Y'),comentario='#comentario#',user_ini=#user_ini#,user_fin=#user_fin#,estado='#estado#' WHERE id_asunto=#id_asunto# ";
    protected $FINDBYCLAVE = "SELECT id_asunto,id_cli,id_tipoasu,titulo,DATE_FORMAT(fec_inicio,'%d/%m/%Y') as fec_inicio,DATE_FORMAT(fec_cierre,'%d/%m/%Y') as fec_cierre,comentario,user_ini,user_fin,estado FROM #dbName#.asuntos WHERE id_asunto=#id_asunto#";
    protected $FINDBYID = "SELECT id_asunto,id_cli,id_tipoasu,titulo,DATE_FORMAT(fec_inicio,'%d/%m/%Y') as fec_inicio,DATE_FORMAT(fec_cierre,'%d/%m/%Y') as fec_cierre,comentario,user_ini,user_fin,estado FROM #dbName#.asuntos WHERE id_asunto=#id_asunto#";
    protected $COLECCION = "SELECT id_asunto,id_cli,id_tipoasu,titulo,DATE_FORMAT(fec_inicio,'%d/%m/%Y') as fec_inicio,DATE_FORMAT(fec_cierre,'%d/%m/%Y') as fec_cierre,comentario,user_ini,user_fin,estado FROM #dbName#.asuntos WHERE id_asunto=#id_asunto#";
    protected $COLECCIONBASE = "SELECT id_asunto,id_cli,id_tipoasu,titulo,DATE_FORMAT(fec_inicio,'%d/%m/%Y') as fec_inicio,DATE_FORMAT(fec_cierre,'%d/%m/%Y') as fec_cierre,comentario,user_ini,user_fin,estado FROM #dbName#.asuntos";

    /**
     * Retorna un resultset con los datos obtenidos basado en los parametros.
     * @param int $id_cli -> id de cliente, si es 0 se retorna la info de todos los clientes
     * @param char $estado -> identificador del estado deseado 't': todos   'a': activos   'c': cerrados
     * @return resulset
     */
    public function coleccionByClienteEstado($id_cli = 0, $estado = 'a') {
        $parametro = func_get_args();
        $resultado = '';
        $ordenprinc = '';
        $where = '';
        $filtro = array();

        if ($id_cli != '' && $id_cli != 0) {
            $filtro[] = "id_cli=" . $id_cli;
        } else {
            $ordenprinc = 'id_cli,';
        }
        switch ($estado) {
            case 'a':
                $filtro[] = "(fec_cierre='' or fec_cierre is null or year(fec_cierre)='0000')";
                break;
            case 'c':
                $filtro[] = "(fec_cierre!='' and !(fec_cierre is null) and year(fec_cierre)!='0000')";
                break;
        }
        if (sizeof($filtro) != 0) {
            $where.=" WHERE ";
            foreach ($filtro as $cond) {
                $where.=($cond . " AND ");
            }
            $where = substr($where, 0, -4);
        }
//        $order = " ORDER BY " . $ordenprinc . "fec_inicio desc";
        $order = " ORDER BY " . $ordenprinc . "date_format(fec_inicio,'%Y-%m-%d') desc";
        $resultado = $this->execSql($this->COLECCIONBASE . $where . $order, $parametro);
        
        if (!$resultado) {
            $this->onError("COD_COLLECION", "ASUNTOS BY CLIENTE " . $this->COLECCIONBASE . $where . $order);
        }
        return $resultado;
    }

}

?>
