<?php

class Contratoimpuesto{
    
/*
| id_improp   | int(11)      | NO   | PRI | NULL    | auto_increment |
| id_prop     | int(11)      | NO   |     | NULL    |                |
| id_contrato | int(11)      | NO   |     | NULL    |                |
| id_impuesto | int(11)      | NO   |     | NULL    |                |
| porcentaje  | varchar(30)  | NO   |     | 100%    |                |
| prim_venc   | date         | YES  |     | NULL    |                |
| aviso       | int(11)      | YES  |     | NULL    |                |
| observacion | varchar(500) | NO   |     | ''      |                |

 */    

    private $id_improp;
    private $id_prop;
    private $id_contrato;
    private $id_impuesto;
    private $porcentaje;
    private $prim_venc;
    private $aviso;
    private $observacion;

    public function __construct($id_improp = 0, $id_prop = 0, $id_contrato = 0, $id_impuesto = '',  $porcentaje = '', $prim_venc = 0, $aviso = '', $observacion = '') {
        $this->setId_prop($id_prop);
        $this->setId_contrato($id_contrato);
        $this->setId_impuesto($id_impuesto);
        $this->setId_improp($id_improp);
        $this->setPorcentaje($porcentaje);
        $this->setPrim_venc($prim_venc);
        $this->setAviso($aviso);
        $this->setObservacion($observacion);
    }

    public function seteaContratoimpuesto($obj) {
        $this->setId_prop ($obj->getId_prop());
        $this->setId_contrato ($obj->getId_contrato());
        $this->setId_impuesto ($obj->getId_impuesto());
        $this->setId_improp ($obj->getId_improp());
        $this->setPorcentaje ($obj->getPorcentaje());
        $this->setPrim_venc ($obj->getPrim_venc());
        $this->setAviso ($obj->getAviso());
        $this->setObservacion ($obj->getObservacion());
    }

    public function getId_prop() {
        return $this->id_prop;
    }

    public function getId_contrato() {
        return $this->id_contrato;
    }

    public function getId_impuesto() {
        return $this->id_impuesto;
    }

    public function getId_improp() {
        return $this->id_improp;
    }

    public function getPorcentaje() {
        return $this->porcentaje;
    }

    public function getPrim_venc() {
        return $this->prim_venc;
    }

    public function getAviso() {
        return $this->aviso;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function setId_prop($id_prop) {
        $this->id_prop = $id_prop;
    }

    public function setId_contrato($id_contrato) {
        $this->id_contrato = $id_contrato;
    }

    public function setId_impuesto($id_impuesto) {
        $this->id_impuesto = $id_impuesto;
    }

    public function setId_improp($id_improp) {
        $this->id_improp = $id_improp;
    }

    public function setPorcentaje($porcentaje) {
        $this->porcentaje = $porcentaje;
    }

    public function setPrim_venc($prim_venc) {
        $this->prim_venc = $prim_venc;
    }

    public function setAviso($aviso) {
        $this->aviso = $aviso;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    public function __toString() {
        $str = "";
        $str.='id_improp: ' . $this->getId_improp() . '; ';
        $str.='id_prop: ' . $this->getId_prop() . '; ';
        $str.='id_contrato: ' . $this->getId_contrato() . '; ';
        $str.='id_impuesto: ' . $this->getId_impuesto() . '; ';
        $str.='porcentaje: ' . $this->getPorcentaje() . '; ';
        $str.='prim_venc: ' . $this->getPrim_venc() . '; ';
        $str.='aviso: ' . $this->getAviso() . '; ';
        $str.='observacion: ' . $this->getObservacion() . '; ';
        return $str;
    }
  
}

?>