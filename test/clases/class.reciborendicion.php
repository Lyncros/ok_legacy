<?php

class Reciborendicion {
    
    private $id_rendicion;
    private $id_contrato;
    private $id_recibo;
    private $fec_rend;
    private $comision;
    private $id_user;

    public function __construct($id_rendicion=0, $id_contrato=0, $id_recibo='', $fec_rend='', $comision=0,$id_user=0) {

        $this->setId_rendicion($id_rendicion);
        $this->setId_recibo($id_recibo);
        $this->setId_contrato($id_contrato);
        $this->setFec_rend($fec_rend);
        $this->setComision($comision);
        $this->setId_user($id_user);
    }

    public function seteaReciborendicion($_id_recibo) {
        $this->setId_rendicion($_id_recibo->getId_rendicion());
        $this->setId_recibo($_id_recibo->getId_recibo());
        $this->setId_contrato($_id_recibo->getId_contrato());
        $this->setFec_rend($_id_recibo->getFec_rend());
        $this->setComision($_id_recibo->getComision());
        $this->setId_user($_id_recibo->getId_user());
       
    }

    public function getId_rendicion() {
        return $this->id_rendicion;
    }

    public function getId_contrato() {
        return $this->id_contrato;
    }

    public function getId_recibo() {
        return $this->id_recibo;
    }

    public function getFec_rend() {
        return $this->fec_rend;
    }

    public function getComision() {
        return $this->comision;
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function setId_rendicion($id_rendicion) {
        $this->id_rendicion = $id_rendicion;
    }

    public function setId_contrato($id_contrato) {
        $this->id_contrato = $id_contrato;
    }

    public function setId_recibo($id_recibo) {
        $this->id_recibo = $id_recibo;
    }

    public function setFec_rend($fec_rend) {
        $this->fec_rend = $fec_rend;
    }

    public function setComision($comision) {
        $this->comision = $comision;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }
    
    public function __toString() {
        $str = '';
        $str.='id_rendicion: ' . $this->id_rendicion . "; ";
        $str.='id_contrato: ' . $this->id_contrato . "; ";
        $str.='id_recibo: ' . $this->id_recibo . "; ";
        $str.='fec_rend: '.$this->fec_rend."; ";
        $str.='comicion: '.$this->comision."; ";
        $str.='id_user: '.$this->id_user."; ";
        return $str;
    }

}

?>