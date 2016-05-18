<?php

class Contratoinvolucrado{
    
    private $id_involucrado;
    private $id_cli;
    private $id_contrato;
    private $id_relacion;

    public function __construct($id_involucrado = 0, $id_cli = 0, $id_contrato = 0, $id_relacion = 0) {
        $this->setId_cli($id_cli);
        $this->setId_contrato($id_contrato);
        $this->setId_relacion($id_relacion);
        $this->setId_involucrado($id_involucrado);
    }

    public function seteaContratoinvolucrado($obj) {
        $this->setId_cli ($obj->getId_cli());
        $this->setId_contrato ($obj->getId_contrato());
        $this->setId_relacion ($obj->getId_relacion());
        $this->setId_involucrado ($obj->getId_involucrado());
    }

    public function getId_cli() {
        return $this->id_cli;
    }

    public function getId_contrato() {
        return $this->id_contrato;
    }

    public function getId_relacion() {
        return $this->id_relacion;
    }

    public function getId_involucrado() {
        return $this->id_involucrado;
    }

    public function setId_cli($id_cli) {
        $this->id_cli = $id_cli;
    }

    public function setId_contrato($id_contrato) {
        $this->id_contrato = $id_contrato;
    }

    public function setId_relacion($id_relacion) {
        $this->id_relacion = $id_relacion;
    }

    public function setId_involucrado($id_involucrado) {
        $this->id_involucrado = $id_involucrado;
    }

    public function __toString() {
        $str = "";
        $str.='id_involucrado: ' . $this->getId_involucrado() . '; ';
        $str.='id_cli: ' . $this->getId_cli() . '; ';
        $str.='id_contrato: ' . $this->getId_contrato() . '; ';
        $str.='id_relacion: ' . $this->getId_relacion() . '; ';
        return $str;
    }
  
}

?>