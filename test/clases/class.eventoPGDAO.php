<?php

include_once("generic_class/class.PGDAO.php");
include_once("clases/class.eventocomponente.php");

class EventoPGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.eventos (id_evento,id_asunto,tarea,detalle,id_tipoevento,fecha_even,user) values (#id_evento#,#id_asunto#,'#tarea#','#detalle#',#id_tipoevento#,STR_TO_DATE('#fecha_even#', '%d-%m-%Y %H:%i'),#user#)";
    protected $DELETE = "DELETE #dbName#.eventos,#dbName#.evento_componente FROM #dbName#.eventos,#dbName#.evento_componente WHERE evento_componente.id_evento=eventos.id_evento AND eventos.id_evento=#id_evento#";
    protected $UPDATE = "UPDATE #dbName#.eventos SET tarea='#tarea#',detalle='#detalle#',id_tipoevento=#id_tipoevento#,fecha_even=STR_TO_DATE('#fecha_even#', '%d-%m-%Y %H:%i')= WHERE id_evento=#id_evento# ";
    protected $FINDBYID = "SELECT id_evento,id_asunto,id_tipoevento,tarea,detalle,activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even,user,DATE_FORMAT(fecha_cierre ,'%d-%m-%Y %H:%i') as fecha_cierre FROM #dbName#.eventos WHERE id_evento=#id_evento#";
    protected $FINDBYCLAVE = "SELECT id_evento,id_asunto,id_tipoevento, tarea, detalle, activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even,user,DATE_FORMAT(fecha_cierre ,'%d-%m-%Y %H:%i') as fecha_cierre 
							FROM #dbName#.eventos
							WHERE id_tipoevento=#id_tipoevento# AND fecha_even LIKE  '#fecha_even#' ORDER BY fecha_even";
    protected $COLECCION = "SELECT id_evento,id_asunto,id_tipoevento,tarea,detalle,activa,DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even,user,DATE_FORMAT(fecha_cierre ,'%d-%m-%Y %H:%i') as fecha_cierre FROM #dbName#.eventos ORDER BY fecha_even";
    protected $FINDBYFECHA = "SELECT id_evento,id_asunto, id_tipoevento, tarea, detalle, activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even,user,DATE_FORMAT(fecha_cierre ,'%d-%m-%Y %H:%i') as fecha_cierre 
							FROM #dbName#.eventos
							WHERE fecha_even LIKE '#fecha_even#' ORDER BY fecha_even";
    protected $COLECBASE = "SELECT id_evento,id_asunto, id_tipoevento, tarea, detalle, activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even,user,DATE_FORMAT(fecha_cierre ,'%d-%m-%Y %H:%i') as fecha_cierre FROM #dbName#.eventos";
    
    protected $COLECCLIENTES = "SELECT id_cli,max(DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i')) as fecha_even 
                                from #dbName#.eventos as e inner join #dbName#.asuntos as a
                                on e.id_asunto=a.id_asunto ";

    public function coleccionEventoFecha() {
        $parametro = func_get_args();
        $resultado = $this->execSql($this->FINDBYFECHA, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", "EVENTOS x FECHA");
        }
        return $resultado;
    }

    public function coleccionByTipo($tipo = 'C', $id = 0) {
        $parametro = func_get_args();
        $evCompBSN=new EventocomponenteBSN();
        $listaIn=$evCompBSN->armaListaIdEventoByTipo($tipo, $id);
        if($listaIn==''){
            $listaIn=0;
        }
        $where=' where id_evento in ('.$listaIn.')';
        $order=" order by date_format(fecha_even,'%Y-%m-%d')";
        $resultado = $this->execSql($this->COLECBASE.$where.$order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", $this->COLECBASE.$where.$order." EVENTOS x TIPO");
        }
        return $resultado;
        
    }

    public function coleccionByTipoEstado($tipo = 'C', $id = 0,$estado=-99) {
        $parametro = func_get_args();
        $evCompBSN=new EventocomponenteBSN();
        $listaIn=$evCompBSN->armaListaIdEventoByTipo($tipo, $id);
        if($listaIn==''){
            $listaIn=0;
        }
        $where=' where id_evento in ('.$listaIn.')';
        if($estado!=-99){
            $where=$where.' and ';
            $where=$where.' activa='.$estado;
        }

        $order=" order by date_format(fecha_even,'%Y-%m-%d') desc";
        $resultado = $this->execSql($this->COLECBASE.$where.$order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", $this->COLECBASE.$where.$order." EVENTOS x TIPO");
        }
        return $resultado;
        
    }
    
    /*
    public function coleccionByAsunto($id_asunto = 0) {
        $parametro = func_get_args();
        if($id_asunto!=0){
            $where=' where id_asunto in ('.$id_asunto.')';
        }
        $order=" order by  date_format(fecha_even,'%Y-%m-%d')";
        $resultado = $this->execSql($this->COLECBASE.$where.$order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", $this->COLECBASE.$where.$order." EVENTOS x ASUNTO");
        }
        return $resultado;
        
    }
    */
    
    public function coleccionByAsunto($id_asunto = 0,$id_prop=0) {
        $parametro = func_get_args();
        $where='';
        if($id_asunto!=0){
            $where='id_asunto in ('.$id_asunto.')';
        }
        if($id_prop!=0){
            if($where!=''){
                $where.=' AND ';
            }
            $where.="detalle like '%de la propiedad: ".$id_prop."%'";
        }
        if($where!=''){
            $where=" where ".$where;
        }
        $order=" order by  date_format(fecha_even,'%Y-%m-%d')";
        $resultado = $this->execSql($this->COLECBASE.$where.$order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", $this->COLECBASE.$where.$order." EVENTOS x ASUNTO y Propiedad");
        }
        return $resultado;
        
    }
    
    public function coleccionByUsuarioEstado($user=0,$estado=-99){
        $parametro = func_get_args();
        $where='';
        if($user!=0){
            $where=' where user='.$user;
        }
        if($estado!=-99){
            if($where==''){
                $where=' where ';
            }else{
                $where=$where.' and ';
            }
            $where=$where.' activa='.$estado;
        }
         $order=" order by date_format(fecha_even,'%Y-%m-%d') desc";
        $resultado = $this->execSql($this->COLECBASE.$where.$order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", $this->COLECBASE.$where.$order." EVENTOS x USUARIO y Estado");
        }
        return $resultado;
    }

    public function coleccionClientesByUsuario($user=0){
        $parametro = func_get_args();
        $where='';
        if($user!=0){
            $where=' where user='.$user;
        }
       $group=" group by id_cli ";        
        $order=" order by date_format(fecha_even,'%Y-%m-%d') desc";
        $resultado = $this->execSql($this->COLECCLIENTES.$where.$group.$order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", $this->COLECCLIENTES.$where.$group.$order." EVENTOS x USUARIO");
        }
        return $resultado;
    }
    
    

}

?>
