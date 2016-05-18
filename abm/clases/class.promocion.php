<?php

/**
 * Gestion de las formas en que se promociona la inmobiliaria
 *
 * @author edgardo
 */
class Promocion {

    private $id_promo;
    private $id_fpromo;
    private $titulo;
    private $empresa;
    private $medio;
    private $ubicacion;
    private $comentario;
    private $imagen1;
    private $imagen2;
    private $imagen3;
    private $imagen4;
    private $fec_ini;
    private $fec_fin;
    private $visible;

    public function __construct(
    $id_promo = 0, $id_fpromo = 0,$titulo='', $empresa = '', $medio = '', $ubicacion = '', $comentario = '', $imagen1 = '', $imagen2 = '', $imagen3 = '', $imagen4 = '', $fec_ini = '', $fec_fin = '', $visible = 1
    ) {
        Promocion::setId_promo($id_promo);
        Promocion::setId_fpromo($id_fpromo);
        Promocion::setTitulo($titulo);
        Promocion::setEmpresa($empresa);
        Promocion::setMedio($medio);
        Promocion::setUbicacion($ubicacion);
        Promocion::setComentario($comentario);
        Promocion::setImagen1($imagen1);
        Promocion::setImagen2($imagen2);
        Promocion::setImagen3($imagen3);
        Promocion::setImagen4($imagen4);
        Promocion::setFec_ini($fec_ini);
        Promocion::setFec_fin($fec_fin);
        Promocion::setVisible($visible);
    }

    public function seteaPromocion($objeto) {

        $this->setId_promo($objeto->getId_promo());
        $this->setId_fpromo($objeto->getId_fpromo());
        $this->setTitulo($objeto->getTitulo());
        $this->setEmpresa($objeto->getEmpresa());
        $this->setMedio($objeto->getMedio());
        $this->setUbicacion($objeto->getUbicacion());
        $this->setComentario($objeto->getComentario());
        $this->setImagen1($objeto->getImagen1());
        $this->setImagen2($objeto->getImagen2());
        $this->setImagen3($objeto->getImagen3());
        $this->setImagen4($objeto->getImagen4());
        $this->setFec_ini($objeto->getFec_ini());
        $this->setFec_fin($objeto->getFec_fin());
        $this->setVisible($objeto->getVisible());
    }

    public function __toString() {
        $strObj = '';
        $strObj.=$this->getId_promo();
        $strObj.=$this->getId_fpromo();
        $strObj.=$this->getTitulo();
        $strObj.=$this->getEmpresa();
        $strObj.=$this->getMedio();
        $strObj.=$this->getUbicacion();
        $strObj.=$this->getComentario();
        $strObj.=$this->getImagen1();
        $strObj.=$this->getImagen2();
        $strObj.=$this->getImagen3();
        $strObj.=$this->getImagen4();
        $strObj.=$this->getFec_ini();
        $strObj.=$this->getFec_fin();
        $strObj.=$this->getVisible();
        return $strObj;
    }
    
    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

        public function getId_promo() {
        return $this->id_promo;
    }

    public function setId_promo($id_promo) {
        $this->id_promo = $id_promo;
    }

    public function getId_fpromo() {
        return $this->id_fpromo;
    }

    public function setId_fpromo($id_fpromo) {
        $this->id_fpromo = $id_fpromo;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    public function getMedio() {
        return $this->medio;
    }

    public function setMedio($medio) {
        $this->medio = $medio;
    }

    public function getUbicacion() {
        return $this->ubicacion;
    }

    public function setUbicacion($ubicacion) {
        $this->ubicacion = $ubicacion;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function getImagen1() {
        return $this->imagen1;
    }

    public function setImagen1($imagen1) {
        $this->imagen1 = $imagen1;
    }

    public function getImagen2() {
        return $this->imagen2;
    }

    public function setImagen2($imagen2) {
        $this->imagen2 = $imagen2;
    }

    public function getImagen3() {
        return $this->imagen3;
    }

    public function setImagen3($imagen3) {
        $this->imagen3 = $imagen3;
    }

    public function getImagen4() {
        return $this->imagen4;
    }

    public function setImagen4($imagen4) {
        $this->imagen4 = $imagen4;
    }

    public function getFec_ini() {
        return $this->fec_ini;
    }

    public function setFec_ini($fec_ini) {
        $this->fec_ini = $fec_ini;
    }

    public function getFec_fin() {
        return $this->fec_fin;
    }

    public function setFec_fin($fec_fin) {
        $this->fec_fin = $fec_fin;
    }

    public function getVisible() {
        return $this->visible;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
    }


}

?>
