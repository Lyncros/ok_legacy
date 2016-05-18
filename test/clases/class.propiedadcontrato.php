<?php

class Propiedadcontrato {

    private $id_contrato;
    private $id_prop;
    private $cont_nro;
    private $fec_firma;
    private $fec_ini;
    private $fec_fin;
    private $intereses;
    private $observacion;
    private $tipo_contrato;
    private $administrada;
    private $honorarios;

    public function __construct($id_contrato=0, $id_prop=0, $tipo_contrato='',$cont_nro='', $fec_firma='', $fec_ini='', $fec_fin='',$intereses='',$honorarios=0, $observacion='',$administrada=1
    ) {
        $this->setId_contrato($id_contrato);
        $this->setTipo_contrato($tipo_contrato);
        $this->setCont_nro($cont_nro);
        $this->setId_prop($id_prop);
        $this->setIntereses($intereses);
        $this->setFec_firma($fec_firma);
        $this->setFec_ini($fec_ini);
        $this->setFec_fin($fec_fin);
        $this->setObservacion($observacion);
        $this->setAdministrada($administrada);
        $this->setHonorarios($honorarios);
    }

    public function seteaPropiedadcontrato($_cont_nro) {
        $this->setId_contrato($_cont_nro->getId_contrato());
        $this->setTipo_contrato($_cont_nro->getTipo_contrato());
        $this->setCont_nro($_cont_nro->getCont_nro());
        $this->setId_prop($_cont_nro->getId_prop());
        $this->setIntereses($_cont_nro->getIntereses());
        $this->setFec_firma($_cont_nro->getFec_firma());
        $this->setFec_ini($_cont_nro->getFec_ini());
        $this->setFec_fin($_cont_nro->getFec_fin());
        $this->setObservacion($_cont_nro->getObservacion());
        $this->setAdministrada($_cont_nro->getAdministrada());
        $this->setHonorarios($_cont_nro->getHonorarios());
    }

    public function getHonorarios() {
        return $this->honorarios;
    }

    public function setHonorarios($honorarios) {
        $this->honorarios = $honorarios;
    }

    public function getIntereses() {
        return $this->intereses;
    }

    public function setIntereses($intereses) {
        $this->intereses = $intereses;
    }

    
    public function getAdministrada() {
        return $this->administrada;
    }

    public function setAdministrada($administrada) {
        $this->administrada = $administrada;
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
        $str.='honorarios: '.  $this->honorarios."; ";
        $str.='observacion: '.$this->observacion."; ";
        $str.='administrada: '.$this->administrada."; ";
        return $str;
    }

}

?>