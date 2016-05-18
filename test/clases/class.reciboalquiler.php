<?php

class Reciboalquiler {
/*
CREATE TABLE `propiedad_recibo` (
  `id_recibo` bigint(20) NOT NULL,
  `id_contrato` bigint(20) NOT NULL,
 * rec_nro
  `fec_recibo` date DEFAULT NULL,
  id_venc int(11) NOT NULL ,
  `total` double NOT NULL DEFAULT '0',
  id_user bigint(20) unsigned not null,
  `observacion` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_recibo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1  
 */
    
    private $id_recibo;
    private $id_contrato;
    private $rec_nro;
    private $fec_recibo;
    private $id_venc;
    private $total;
    private $totaldesc;
    private $diasret;
    private $montomes;
    private $intereses;
    private $observacion;
    private $id_user;

    public function __construct($id_recibo=0, $id_contrato=0, $rec_nro='', $fec_recibo='', $id_venc=0,$montomes=0,$diasret=0,$intereses=0,$totaldesc=0, $total=0, $observacion='',$id_user=0) {

        $this->setId_recibo($id_recibo);
        $this->setRec_nro($rec_nro);
        $this->setId_contrato($id_contrato);
        $this->setFec_recibo($fec_recibo);
        $this->setId_venc($id_venc);
        $this->setTotal($total);
        $this->setMontomes($montomes);
        $this->setDiasret($diasret);
        $this->setIntereses($intereses);
        $this->setTotaldesc($totaldesc);
        $this->setObservacion($observacion);
        $this->setId_user($id_user);
    }

    public function seteaReciboalquiler($_rec_nro) {
        $this->setId_recibo($_rec_nro->getId_recibo());
        $this->setRec_nro($_rec_nro->getRec_nro());
        $this->setId_contrato($_rec_nro->getId_contrato());
        $this->setFec_recibo($_rec_nro->getFec_recibo());
        $this->setId_venc($_rec_nro->getId_venc());
        $this->setMontomes($_rec_nro->getMontomes());
        $this->setDiasret($_rec_nro->getDiasret());
        $this->setIntereses($_rec_nro->getIntereses());
        $this->setTotaldesc($_rec_nro->getTotaldesc());
        $this->setTotal($_rec_nro->getTotal());
        $this->setObservacion($_rec_nro->getObservacion());
        $this->setId_user($_rec_nro->getId_user());
       
    }
    public function getTotaldesc() {
        return $this->totaldesc;
    }

    public function getDiasret() {
        return $this->diasret;
    }

    public function getMontomes() {
        return $this->montomes;
    }

    public function getIntereses() {
        return $this->intereses;
    }

    public function setTotaldesc($totaldesc) {
        $this->totaldesc = $totaldesc;
    }

    public function setDiasret($diasret) {
        $this->diasret = $diasret;
    }

    public function setMontomes($montomes) {
        $this->montomes = $montomes;
    }

    public function setIntereses($intereses) {
        $this->intereses = $intereses;
    }

        public function getId_user() {
        return $this->id_user;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }

    public function setId_recibo($_id_recibo) {
        $this->id_recibo = $_id_recibo;
    }

    public function setId_contrato($_id_contrato) {
        $this->id_contrato = $_id_contrato;
    }

    public function setRec_nro($_rec_nro) {
        $this->rec_nro = $_rec_nro;
    }

    public function setFec_recibo($_fec_recibo) {
        $this->fec_recibo = $_fec_recibo;
    }

    public function setId_venc($_ini) {
        $this->id_venc = $_ini;
    }

    public function setTotal($_fin) {
        $this->total = $_fin;
    }

    public function setObservacion($_comen) {
        $this->observacion = $_comen;
    }

    public function getId_recibo() {
        return $this->id_recibo;
    }

    public function getId_contrato() {
        return $this->id_contrato;
    }

    public function getRec_nro() {
        return $this->rec_nro;
    }

    public function getFec_recibo() {
        return $this->fec_recibo;
    }

    public function getId_venc() {
        return $this->id_venc;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function __toString() {
        $str = '';
        $str.='id_recibo: ' . $this->id_recibo . "; ";
        $str.='id_contrato: ' . $this->id_contrato . "; ";
        $str.='rec_nro: ' . $this->rec_nro . "; ";
        $str.='fec_recibo: '.$this->fec_recibo."; ";
        $str.='id_venc: '.$this->id_venc."; ";
        $str.='total: '.$this->total."; ";
        $str.='observacion: '.$this->observacion."; ";
        $str.='id_user: '.$this->id_user."; ";
        return $str;
    }

}

?>