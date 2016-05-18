<?php

class Evento {

    private $id_evento;
    private $tarea;
    private $detalle;
    private $id_tipoevento;
    private $fecha_even;
    private $activa;

    public function __construct($id_evento = 0, $tarea = '', $detalle = '', $id_tipoevento = 0, $fecha_even = '', $para = 0, $con = 0, $activa = false) {

        Evento::setId_evento($id_evento);
        Evento::setTarea($tarea);
        Evento::setDetalle($detalle);
        Evento::setId_tipoevento($id_tipoevento);
        Evento::setFecha_even($fecha_even);
        Evento::setActiva($activa);
    }

    public function seteaEvento($_evento) {
        $this->setId_evento($_evento->getId_evento());
        $this->setTarea($_evento->getTarea());
        $this->setId_tipoevento($_evento->getId_tipoevento());
        $this->setDetalle($_evento->getDetalle());
        $this->setFecha_even($_evento->getFecha_even());
        $this->setActiva($_evento->getActiva());
    }

    public function setId_evento($_id_evento) {
        $this->id_evento = $_id_evento;
    }

    public function setTarea($_tarea) {
        $this->tarea = $_tarea;
    }

    public function setDetalle($_detalle) {
        $this->detalle = $_detalle;
    }

    public function setId_tipoevento($id_tipoevento) {
        $this->id_tipoevento = $id_tipoevento;
    }

    public function setFecha_even($_fecha_even) {
        $this->fecha_even = $_fecha_even;
    }

    public function setActiva($_activa) {
        $this->activa = $_activa;
    }

    public function getId_evento() {
        return $this->id_evento;
    }

    public function getTarea() {
        return $this->tarea;
    }

    public function getDetalle() {
        return $this->detalle;
    }

    public function getId_tipoevento() {
        return $this->id_tipoevento;
    }

    public function getActiva() {
        return $this->activa;
    }

    public function getFecha_even() {
        return $this->fecha_even;
    }

    public function __toString() {
        $str = "Id_evento: " . $this->id_evento;
        $str.="Tarea: " . $this->tarea;
        $str.="Detalle: " . $this->detalle;
        $str.="Tipo : " . $this->id_tipoevento;
        $str.="Fecha: " . $this->fecha_even;
        $str.="Activa: " . $this->activa;
        return $str;
    }

}

?>