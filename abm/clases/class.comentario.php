<?php

/*
 * 	id_com int(10) AUTO_INCREMENT,
 * 	id_prop int(10) unsigned not null,
 * 	id bigint(20) default 0,
 * 	fecha datetime not null,
 * 	tiposeg varchar(20) not null default 'COMENTARIO',
 * 	comentario varchar(1000) not null,
 * 	id_user bigint(20) unsigned not null,
  visible int(1) unsigned not null default 1,
 */

class Comentario {

    protected $id_com;          //
    protected $id_prop;         //
    protected $id_user;         //
    protected $fecha;           //
    protected $comentario;      //
    protected $visible;
    protected $concepto;
    protected $detalle;

    public function __construct($id_com = 0, $id_prop = 0, $fecha = '', $comentario = '', $id_user = 0, $visible = 0, $concepto = ''
    ) {

        Comentario::setId_com($id_com);
        Comentario::setFecha($fecha);
        Comentario::setId_prop($id_prop);
        Comentario::setVisible($visible);
        Comentario::setComentario($comentario);
        Comentario::setId_user($id_user);
        Comentario::setConcepto($concepto);
    }

    public function seteaComentario($_comentario) {
        $this->setId_com($_comentario->getId_com());
        $this->setFecha($_comentario->getFecha());
        $this->setId_prop($_comentario->getId_prop());
        $this->setVisible($_comentario->getVisible());
        $this->setComentario($_comentario->getComentario());
        $this->setId_user($_comentario->getId_user());
        $this->setConcepto($_comentario->getConcepto());
        $this->setDetalle($_comentario->getDetalle());
    }

    public function getDetalle() {
        return $this->detalle;
    }

    public function setDetalle($detalle) {
        $this->detalle = $detalle;
    }

    public function setId_user($id) {
        $this->id_user = $id;
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function getConcepto() {
        return $this->concepto;
    }

    public function setConcepto($concepto) {
        $this->concepto = $concepto;
    }

    public function setComentario($_observ) {
        $this->comentario = $_observ;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function setId_com($_id_com) {
        $this->id_com = $_id_com;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
    }

    public function setFecha($_fecha) {
        $this->fecha = $_fecha;
    }

    public function setId_prop($_id_prop) {
        $this->id_prop = $_id_prop;
    }

    public function getId_prop() {
        return $this->id_prop;
    }

    public function getId_com() {
        return $this->id_com;
    }

    public function getVisible() {
        return $this->visible;
    }

    public function getFecha() {
        return $this->fecha;
    }

}

?>