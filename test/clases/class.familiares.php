<?php

class Familiares {

    private $id_fam;
    private $id_cli;
    private $nombre;
    private $apellido;
    private $id_parent;
    private $fecha_nac;
    private $nota;
    private $tipocont;

    public function __construct($id_fam = 0, $fecha_nac = '', $id_cli = 0, $nombre = '', $apellido = '', $id_parent = 0, $nota = '', $tipocont = 'C'
    ) {

        Familiares::setId_fam($id_fam);
        Familiares::setFecha_nac($fecha_nac);
        Familiares::setId_cli($id_cli);
        Familiares::setNombre($nombre);
        Familiares::setApellido($apellido);
        Familiares::setId_parent($id_parent);
        Familiares::setNota($nota);
        Familiares::setTipocont($tipocont);
    }

    public function seteaFamiliares($_objeto) {
        $this->setId_fam($_objeto->getId_fam());
        $this->setFecha_nac($_objeto->getFecha_nac());
        $this->setId_cli($_objeto->getId_cli());
        $this->setNombre($_objeto->getNombre());
        $this->setApellido($_objeto->getApellido());
        $this->setId_parent($_objeto->getId_parent());
        $this->setNota($_objeto->getNota());
        $this->setTipocont($_objeto->getTipocont());
        
    }

    public function setNota($_tipo) {
        $this->nota = $_tipo;
    }

    public function getNota() {
        return $this->nota;
    }

    public function getTipocont() {
        return $this->tipocont;
    }

    public function setTipocont($tipocont) {
        $this->tipocont = $tipocont;
    }

    public function setFecha_nac($_fecha) {
        $this->fecha_nac = $_fecha;
    }

    public function getFecha_nac() {
        return $this->fecha_nac;
    }

    public function setId_parent($_publ) {
        $this->id_parent = $_publ;
    }

    public function getId_parent() {
        return $this->id_parent;
    }

    public function setId_fam($_id_fam) {
        $this->id_fam = $_id_fam;
    }

    public function setId_cli($_pais) {
        $this->id_cli = $_pais;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($col) {
        $this->apellido = $col;
    }

    public function getId_fam() {
        return $this->id_fam;
    }

    public function getId_cli() {
        return $this->id_cli;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function __toString() {
        $str = '';
        $str.='Id_fam: ' . $this->getId_fam() . '; ';
        $str.='Fecha_nac: ' . $this->getFecha_nac() . '; ';
        $str.='Id_cli: ' . $this->getId_cli() . '; ';
        $str.='Nombre: ' . $this->getNombre() . '; ';
        $str.='Apellido: ' . $this->getApellido() . '; ';
        $str.='Id_parent: ' . $this->getId_parent() . '; ';
        $str.='Nota: ' . $this->getNota() . '; ';
        $str.='Tipo Cont: ' . $this->getTipocont() . '; ';
        return $str;
    }

}

?>