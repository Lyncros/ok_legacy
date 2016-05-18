<?php

class Propiedadcontrato {

    private $id_contrato;
    private $id_prop;
    private $cont_nro;
    private $fec_firma;
    private $fec_ini;
    private $fec_fin;
    private $observacion;
    private $tipo_contrato;

    public function __construct($id_contrato=0, $id_prop=0, $tipo_contrato='',$cont_nro='', $fec_firma='', $fec_ini='', $fec_fin='', $observacion=''
    ) {

        Propiedadcontrato::setId_contrato($id_contrato);
        Propiedadcontrato::setTipo_contrato($tipo_contrato);
        Propiedadcontrato::setCont_nro($cont_nro);
        Propiedadcontrato::setId_prop($id_prop);
        Propiedadcontrato::setFec_firma($fec_firma);
        Propiedadcontrato::setFec_ini($fec_ini);
        Propiedadcontrato::setFec_fin($fec_fin);
        Propiedadcontrato::setObservacion($observacion);
    }

    public function seteaPropiedadcontrato($_cont_nro) {
        $this->setId_contrato($_cont_nro->getId_contrato());
        $this->setTipo_contrato($_cont_nro->getTipo_contrato());
        $this->setCont_nro($_cont_nro->getCont_nro());
        $this->setId_prop($_cont_nro->getId_prop());
        $this->setFec_firma($_cont_nro->getFec_firma());
        $this->setFec_ini($_cont_nro->getFec_ini());
        $this->setFec_fin($_cont_nro->getFec_fin());
        $this->setObservacion($_cont_nro->getObservacion());
    }

    public function setTipo_contrato($tipo){
        $this->tipo_contrato=$tipo;
    }
    
    public function getTipo_contrato(){
        return $this->tipo_contrato;
    }
    
    public function setId_contrato($_id_contrato) {
        $this->id_contrato = $_id_contrato;
    }

    public function setId_prop($_id_prop) {
        $this->id_prop = $_id_prop;
    }

    public function setCont_nro($_cont_nro) {
        $this->cont_nro = $_cont_nro;
    }

    public function setFec_firma($_fec_firma) {
        $this->fec_firma = $_fec_firma;
    }

    public function setFec_ini($_ini) {
        $this->fec_ini = $_ini;
    }

    public function setFec_fin($_fin) {
        $this->fec_fin = $_fin;
    }

    public function setObservacion($_comen) {
        $this->observacion = $_comen;
    }

    public function getId_contrato() {
        return $this->id_contrato;
    }

    public function getId_prop() {
        return $this->id_prop;
    }

    public function getCont_nro() {
        return $this->cont_nro;
    }

    public function getFec_firma() {
        return $this->fec_firma;
    }

    public function getFec_ini() {
        return $this->fec_ini;
    }

    public function getFec_fin() {
        return $this->fec_fin;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function __toString() {
        $str = '';
        $str.='id_contrato: ' . $this->id_contrato . "; ";
        $str.='id_prop: ' . $this->id_prop . "; ";
        $str.='tipo_contrato: '.  $this->tipo_contrato.'; ';
        $str.='cont_nro: ' . $this->cont_nro . "; ";
        $str.='fec_firma: '.$this->fec_firma."; ";
        $str.='fec_ini: '.$this->fec_ini."; ";
        $str.='fec_fin: '.$this->fec_fin."; ";
        $str.='observacion: '.$this->observacion."; ";
        return $str;
    }

}

?>