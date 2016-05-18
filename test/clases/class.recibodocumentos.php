<?php

class Recibodocumentos {

/*
CREATE TABLE `propiedad_recdocs` (
  `id_recdocs` int(11) NOT NULL AUTO_INCREMENT,
  `id_recibo` bigint(20) NOT NULL,
  id_impuesto int(10) unsigned NOT NULL,
  concepto varchar(50) not null DEFAULT '',
  `importe` double NOT NULL DEFAULT '0',
  `observacion` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_recdocs`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 
*/
    private $id_recdocs;
    private $id_recibo;
    private $id_impuesto;
    private $concepto;
    private $importe;
    private $observacion;

    public function __construct($id_recdocs = 0, $id_recibo = 0, $id_impuesto = 0, $concepto = '', $importe = 0, $observacion = '') {
        $this->setId_recdocs($id_recdocs);
        $this->setId_recibo($id_recibo);
        $this->setId_impuesto($id_impuesto);
        $this->setConcepto($concepto);
        $this->setImporte($importe);
        $this->setObservacion($observacion);
    }

    public function seteaRecibodocumentos($obj) {
        $this->setId_recdocs ($obj->getId_recdocs());
        $this->setId_recibo ($obj->getId_recibo());
        $this->setId_impuesto ($obj->getId_impuesto());
        $this->setConcepto ($obj->getConcepto());
        $this->setImporte ($obj->getImporte());
        $this->setObservacion ($obj->getObservacion());
    }

    public function getId_recdocs() {
        return $this->id_recdocs;
    }

    public function getId_recibo() {
        return $this->id_recibo;
    }

    public function getId_impuesto() {
        return $this->id_impuesto;
    }

    public function getConcepto() {
        return $this->concepto;
    }

    public function getImporte() {
        return $this->importe;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function setId_recdocs($id_recdocs) {
        $this->id_recdocs = $id_recdocs;
    }

    public function setId_recibo($id_recibo) {
        $this->id_recibo = $id_recibo;
    }

    public function setId_impuesto($id_impuesto) {
        $this->id_impuesto = $id_impuesto;
    }

    public function setConcepto($concepto) {
        $this->concepto = $concepto;
    }

    public function setImporte($importe) {
        $this->importe = $importe;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    public function __toString() {
        $str = "";
        $str.='id_recdocs: ' . $this->getId_recdocs() . '; ';
        $str.='id_recibo: ' . $this->getId_recibo() . '; ';
        $str.='id_impuesto: ' . $this->getId_impuesto() . '; ';
        $str.='concepto: ' . $this->getConcepto() . '; ';
        $str.='importe: ' . $this->getImporte() . '; ';
        $str.='observacion: ' . $this->getObservacion() . '; ';
        return $str;
    }
}

?>