<?php

include_once("generic_class/class.PGDAO.php");

class IngresoclientePGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.cliente_ingreso (id_cli , usr_carga , fec_cont , id_fcontacto , id_fingreso , id_promo,id_asunto ) values (#id_cli# , #usr_carga# , STR_TO_DATE('#fec_cont#', '%d/%m/%Y') , #id_fcontacto# , #id_fingreso# , #id_promo#,#id_asunto# )";
    protected $DELETE = "DELETE FROM #dbName#.cliente_ingreso WHERE id_ingreso=#id_ingreso#";
    protected $UPDATE = "UPDATE #dbName#.cliente_ingreso SET id_cli=#id_cli# , usr_carga=#usr_carga# , fec_cont=STR_TO_DATE('#fec_cont#', '%d/%m/%Y') , id_fcontacto=#id_fcontacto# , id_fingreso=#id_fingreso# , id_promo=#id_promo#  WHERE id_ingreso=#id_ingreso# ";
    protected $FINDBYCLAVE = "SELECT id_ingreso , id_cli , usr_carga , DATE_FORMAT(fec_cont,'%d/%m/%Y') as fec_cont , id_fcontacto , id_fingreso , id_promo,id_asunto  FROM #dbName#.cliente_ingreso WHERE id_cli=#id_cli#";
    protected $FINDBYID = "SELECT id_ingreso , id_cli , usr_carga ,DATE_FORMAT(fec_cont,'%d/%m/%Y') as fec_cont , id_fcontacto , id_fingreso , id_promo,id_asunto  FROM #dbName#.cliente_ingreso WHERE id_ingreso=#id_ingreso#";
    protected $COLECCION = "SELECT id_ingreso , id_cli , usr_carga , DATE_FORMAT(fec_cont,'%d/%m/%Y') as fec_cont , id_fcontacto , id_fingreso , id_promo,id_asunto  FROM #dbName#.cliente_ingreso";
    protected $COLECCIONBASE = "SELECT id_ingreso , id_cli , usr_carga , DATE_FORMAT(fec_cont,'%d/%m/%Y') as fec_cont , id_fcontacto , id_fingreso , id_promo,id_asunto  FROM #dbName#.cliente_ingreso ";

    public function coleccionByFiltro($id = 0, $tipo = '') {
        $resultado = array();
        if ($id != 0 && $tipo != '') {
            $parametro = func_get_args();
            $where = ' WHERE ';
            switch($tipo){ //C: Cliente  I: Ingreso   P: promocion   O: Contacto
                case 'C':
                    $where.=('id_cli='.$id);
                    break;
                case 'I':
                    $where.=('id_fingreso='.$id);
                    break;
                case 'P':
                    $where.=('id_promo='.$id);
                    break;
                case 'O':
                    $where.=('id_fcontacto='.$id);
                    break;
                default:
                    $where='';
            }
            $resultado = $this->execSql($this->COLECCIONBASE.$where, $parametro);
            if (!$resultado) {
                $this->onError($COD_COLLECION, "FORMA DE INGRESO DE CLIENTES");
            }
        }
        return $resultado;
    }

}    
?>
