<?php

include_once ("generic_class/class.PGDAO.php");

class ComentarioPGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.comentario (fecha,id_user,id_prop,visible,comentario) values ('#fecha#',#id_user#,'#id_prop#','#visible#','#comentario#')";
    protected $DELETE = "DELETE FROM #dbName#.comentario WHERE id_com='#id_com#'";
    protected $UPDATE = "UPDATE #dbName#.comentario SET visible=#visible#,comentario='#comentario#' WHERE id_com=#id_com#";
    protected $FINDBYID = "SELECT id_com,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha,id_user,id_prop,visible,comentario FROM #dbName#.comentario WHERE id_com=#id_com#";
    protected $FINDBYCLAVE = "SELECT id_com,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha,id_user,id_prop,visible,comentario  FROM #dbName#.comentario WHERE fecha=#fecha# AND id_prop=#id_prop# AND id_user=#id_user#";
    protected $COLECCIONBASE = "SELECT id_com,id_prop,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha,'COMENTARIO' as concepto,'' as detalle,comentario, id_user,visible  FROM #dbName#.comentario ";
    protected $COLECCION = "SELECT id_com,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha,id_user,id_prop,visible,comentario  FROM #dbName#.comentario WHERE visible=1 ORDER BY fecha";

    // SELECT PARA LEVANTAR LA INFO DE OPERACIONES Y TASACION
// select id_prop,fecha,'TASACION' as concepto,estado as detalle,tasador as usuario,observacion from tasacion 
// union 
// select id_prop,fecha,'OPERACION' as concepto, operacion as detalle,intervino as usuario,comentario as observacion from propiedad_operacion 
// order by id_prop,fecha,concepto;        

    /**
     *
     * Retorna la coleccion delos datos de los comentarios de una propiedad en particular, basado en el concepto seleccionado
     * @param int $id_prop -> id de la propiedad a revisar
     * @param string[] $concepto -> array de conceptos a mostrar en la vista
     * @param int visible -> indicador del tipo de visualizacion a mostrar 1 solo visibles , -1 todos
     */
    public function coleccionComentarios($id_prop,$visible=1, $concepto) {
        $parametro = func_get_args();
        $resultado = false;
        $subSql=array();
        if ($concepto != 0 && ($concepto != '' && is_array($concepto))) {
            if($visible==1){
                $visibilidad=" AND visible=1 ";
            }else{
                $visibilidad='';
            }
            if(in_array('COMENTARIO', $concepto) || $concepto==''){
                $subSql[]=$this->COLECCIONBASE." WHERE id_prop=$id_prop".$visibilidad;
            }
            if(in_array('OPERACION', $concepto) || $concepto==''){
                $subSql[]="select 0 as id_com,id_prop,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha,'OPERACION' as concepto, operacion as detalle,comentario,intervino as id_user,1 as visible from #dbName#.propiedad_operacion WHERE id_prop=$id_prop";
            }
            if(in_array('TASACION', $concepto) || $concepto==''){
                $subSql[]="select 0 as id_com,id_prop,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha,'TASACION' as concepto,estado as detalle,observacion as comentario,tasador as id_user,1 as visible from #dbName#.tasacion WHERE id_prop=$id_prop";
            }
            if(in_array('CARTEL', $concepto) || $concepto==''){
                $subSql[]="select 0 as id_com,id_prop,DATE_FORMAT(fecha,'%d-%m-%Y') as fecha,'CARTEL' as concepto, estado as detalle,observacion as comentario,proveedor as id_user,1 as visible from #dbName#.cartel WHERE id_prop=$id_prop";
            }
            
            $sqlStr=$subSql[0];
            for($x = 1;$x < sizeof($subSql);$x++){
                $sqlStr.=(" UNION ".$subSql[$x]);
            }
            
            $orden = " order by id_prop,fecha,concepto";        
            $resultado = $this->execSql($sqlStr.$orden, $parametro);
            if (!$resultado) {
                $this->onError($COD_COLECCION, "COLECCION DE COMENTARIOSS " . $sqlStr.$orden);
            }
        }
        return $resultado;
    }

}

// Fin clase DAO 
?>