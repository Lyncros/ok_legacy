<?php

class Recibodescuentos {

/*
CREATE TABLE `propiedad_recdesc` (
  `id_recdesc` int(11) NOT NULL AUTO_INCREMENT,
  `id_recibo` bigint(20) NOT NULL,
  `fec_desc` date DEFAULT NULL,
  concepto varchar(50) not null DEFAULT '',
  `importe` double NOT NULL DEFAULT '0',
  `observacion` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_recdesc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 
*/
    private $id_recdesc;
    private $id_recibo;
    private $fec_desc;
    private $concepto;
    private $importe;
    private $observacion;

    public function __construct($id_recdesc = 0, $id_recibo = 0, $fec_desc = '', $concepto = '', $importe = 0, $observacion = '') {
        $this->setId_recdesc($id_recdesc);
        $this->setId_recibo($id_recibo);
        $this->setFec_desc($fec_desc);
        $this->setConcepto($concepto);
        $this->setImporte($importe);
        $this->setObservacion($observacion);
    }

    public function seteaRecibodescuentos($obj) {
        $this->setId_recdesc ($obj->getId_recdesc());
        $this->setId_recibo ($obj->getId_recibo());
        $this->setFec_desc ($obj->getFec_desc());
        $this->setConcepto ($obj->getConcepto());
        $this->setImporte ($obj->getImporte());
        $this->setObservacion ($obj->getObservacion());
    }

    public function getId_recdesc() {
        return $this->id_recdesc;
    }

    public function getId_recibo() {
        return $this->id_recibo;
    }

    public function getFec_desc() {
        return $this->fec_desc;
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

    public function setId_recdesc($id_recdesc) {
        $this->id_recdesc = $id_recdesc;
    }

    public function setId_recibo($id_recibo) {
        $this->id_recibo = $id_recibo;
    }

    public function setFec_desc($fec_desc) {
        $this->fec_desc = $fec_desc;
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
        $str.='id_recdesc: ' . $this->getId_recdesc() . '; ';
        $str.='id_recibo: ' . $this->getId_recibo() . '; ';
        $str.='fec_desc: ' . $this->getFec_desc() . '; ';
        $str.='concepto: ' . $this->getConcepto() . '; ';
        $str.='importe: ' . $this->getImporte() . '; ';
        $str.='observacion: ' . $this->getObservacion() . '; ';
        return $str;
    }
}

?>