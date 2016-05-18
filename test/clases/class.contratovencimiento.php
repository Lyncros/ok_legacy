<?php

class Contratovencimiento {

    private $id_venc;
    private $id_prop;
    private $id_contrato;
    private $tipo_venc;
    private $id_improp;
    private $fec_ven;
    private $importe;
    private $fec_carga;
    private $observacion;
    private $pagado;
    
    public function __construct($id_venc = 0, $id_prop = 0, $id_contrato = 0, $tipo_venc = '', $id_improp = 0, $fec_ven = '', $importe = 0, $fec_carga = '', $observacion = '',$pagado=0) {
        $this->setId_venc($id_venc);
        $this->setId_prop($id_prop);
        $this->setId_contrato($id_contrato);
        $this->setTipo_venc($tipo_venc);
        $this->setId_improp($id_improp);
        $this->setFec_ven($fec_ven);
        $this->setImporte($importe);
        $this->setFec_carga($fec_carga);
        $this->setObservacion($observacion);
        $this->setPagado($pagado);
    }

    public function seteaContratovencimiento($obj) {
        $this->setId_venc ($obj->getId_venc());
        $this->setId_prop ($obj->getId_prop());
        $this->setId_contrato ($obj->getId_contrato());
        $this->setTipo_venc ($obj->getTipo_venc());
        $this->setId_improp ($obj->getId_improp());
        $this->setFec_ven ($obj->getFec_ven());
        $this->setImporte ($obj->getImporte());
        $this->setFec_carga ($obj->getFec_carga());
        $this->setObservacion ($obj->getObservacion());
        $this->setPagado($obj->getPagado());
    }

    public function getPagado() {
        return $this->pagado;
    }

    public function setPagado($pagado) {
        $this->pagado = $pagado;
    }
    
    public function getId_venc() {
        return $this->id_venc;
    }

    public function getId_prop() {
        return $this->id_prop;
    }

    public function getId_contrato() {
        return $this->id_contrato;
    }

    public function getTipo_venc() {
        return $this->tipo_venc;
    }

    public function getId_improp() {
        return $this->id_improp;
    }

    public function getFec_ven() {
        return $this->fec_ven;
    }

    public function getImporte() {
        return $this->importe;
    }

    public function getFec_carga() {
        return $this->fec_carga;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function setId_venc($id_venc) {
        $this->id_venc = $id_venc;
    }

    public function setId_prop($id_prop) {
        $this->id_prop = $id_prop;
    }

    public function setId_contrato($id_contrato) {
        $this->id_contrato = $id_contrato;
    }

    public function setTipo_venc($tipo_venc) {
        $this->tipo_venc = $tipo_venc;
    }

    public function setId_improp($id_improp) {
        $this->id_improp = $id_improp;
    }

    public function setFec_ven($fec_ven) {
        $this->fec_ven = $fec_ven;
    }

    public function setImporte($importe) {
        $this->importe = $importe;
    }

    public function setFec_carga($fec_carga) {
        $this->fec_carga = $fec_carga;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    public function __toString() {
        $str = "";
        $str.='id_venc: ' . $this->getId_venc() . '; ';
        $str.='id_prop: ' . $this->getId_prop() . '; ';
        $str.='id_contrato: ' . $this->getId_contrato() . '; ';
        $str.='tipo_venc: ' . $this->getTipo_venc() . '; ';
        $str.='id_improp: ' . $this->getId_improp() . '; ';
        $str.='fec_ven: ' . $this->getFec_ven() . '; ';
        $str.='importe: ' . $this->getImporte() . '; ';
        $str.='fec_carga: ' . $this->getFec_carga() . '; ';
        $str.='observacion: ' . $this->getObservacion() . '; ';
        return $str;
    }
}

?>