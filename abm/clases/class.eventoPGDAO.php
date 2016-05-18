<?php

include_once("generic_class/class.PGDAO.php");
include_once("clases/class.eventocomponente.php");

class EventoPGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.eventos (id_evento,tarea,detalle,id_tipoevento,fecha_even) values (#id_evento#,'#tarea#','#detalle#',#id_tipoevento#,STR_TO_DATE('#fecha_even#', '%d-%m-%Y %H:%i'))";
    protected $DELETE = "DELETE #dbName#.eventos,#dbName#.evento_componente FROM #dbName#.eventos,#dbName#.evento_componente WHERE evento_componente.id_evento=eventos.id_evento AND eventos.id_evento=#id_evento#";
    protected $UPDATE = "UPDATE #dbName#.eventos SET tarea='#tarea#',detalle='#detalle#',id_tipoevento=#id_tipoevento#,fecha_even=STR_TO_DATE('#fecha_even#', '%d-%m-%Y %H:%i') WHERE id_evento=#id_evento# ";
    protected $FINDBYID = "SELECT id_evento,id_tipoevento,tarea,detalle,activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even FROM #dbName#.eventos WHERE id_evento=#id_evento#";
    protected $FINDBYCLAVE = "SELECT id_evento,id_tipoevento, tarea, detalle, activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even 
							FROM #dbName#.eventos
							WHERE id_tipoevento=#id_tipoevento# AND fecha_even LIKE  '#fecha_even#' ORDER BY fecha_even";
    protected $COLECCION = "SELECT id_evento,id_tipoevento,tarea,detalle,activa,DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even FROM #dbName#.eventos ORDER BY fecha_even";
    protected $FINDBYFECHA = "SELECT id_evento, id_tipoevento, tarea, detalle, activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even 
							FROM #dbName#.eventos
							WHERE fecha_even LIKE '#fecha_even#' ORDER BY fecha_even";
    protected $COLECBASE = "SELECT id_evento, id_tipoevento, tarea, detalle, activa, DATE_FORMAT(fecha_even ,'%d-%m-%Y %H:%i') as fecha_even FROM #dbName#.eventos";

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
        $where=' where id_evento in ('.$listaIn.')';
        $order=' order by fecha_even';
        $resultado = $this->execSql($this->COLECBASE.$where.$order, $parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION", $this->COLECBASE.$where.$order." EVENTOS x FECHA");
        }
        return $resultado;
        
    }

}

?>
