<?php

/**
 * Manejo de la informacion de la forma y motivo del ingreso de un cliente
 *
 * @author edgardo
 */
class Ingresocliente {

    private $id_ingreso;  // int(10) unsigned NOT NULL auto_increment,
    private $id_cli;  // bigint(20) unsigned  NOT NULL ,
    private $usr_carga;  // bigint(20) unsigned  NOT NULL ,
    private $fec_cont; // date  NOT NULL,
    private $id_fcontacto; // int(2)  unsigned  NOT NULL ,
    private $id_fingreso; // int(2)  unsigned NOT NULL ,
    private $id_promo;      // int(10) unsigned NOT NULL default 0	
    private $id_asunto;

    public function __construct(
    $id_ingreso = 0, $id_cli = 0, $usr_carga = 0, $fec_cont = '', $id_fcontacto = 0, $id_fingreso = 0, $id_promo = 0,$id_asunto=0
    ) {
        Ingresocliente::setId_ingreso($id_ingreso);
        Ingresocliente::setId_cli($id_cli);
        Ingresocliente::setUsr_carga($usr_carga);
        Ingresocliente::setFec_cont($fec_cont);
        Ingresocliente::setId_fcontacto($id_fcontacto);
        Ingresocliente::setId_fingreso($id_fingreso);
        Ingresocliente::setId_promo($id_promo);
        Ingresocliente::setId_asunto($id_asunto);
    }

    public function seteaIngresocliente($_obj) {
        $this->setId_ingreso($_obj->getId_ingreso());
        $this->setId_cli($_obj->getId_cli());
        $this->setUsr_carga($_obj->getUsr_carga());
        $this->setFec_cont($_obj->getFec_cont());
        $this->setId_fcontacto($_obj->getId_fcontacto());
        $this->setId_fingreso($_obj->getId_fingreso());
        $this->setId_promo($_obj->getId_promo());
        $this->setId_asunto($_obj->getId_asunto());
    }

    public function getId_asunto() {
        return $this->id_asunto;
    }

    public function setId_asunto($id_asunto) {
        $this->id_asunto = $id_asunto;
    }

    public function getId_ingreso() {
        return $this->id_ingreso;
    }

    public function setId_ingreso($id_ingreso) {
        $this->id_ingreso = $id_ingreso;
    }

    public function getId_cli() {
        return $this->id_cli;
    }

    public function setId_cli($id_cli) {
        $this->id_cli = $id_cli;
    }

    public function getUsr_carga() {
        return $this->usr_carga;
    }

    public function setUsr_carga($usr_carga) {
        $this->usr_carga = $usr_carga;
    }

    public function getFec_cont() {
        return $this->fec_cont;
    }

    public function setFec_cont($fec_cont) {
        $this->fec_cont = $fec_cont;
    }

    public function getId_fcontacto() {
        return $this->id_fcontacto;
    }

    public function setId_fcontacto($id_fcontacto) {
        $this->id_fcontacto = $id_fcontacto;
    }

    public function getId_fingreso() {
        return $this->id_fingreso;
    }

    public function setId_fingreso($id_fingreso) {
        $this->id_fingreso = $id_fingreso;
    }

    public function getId_promo() {
        return $this->id_promo;
    }

    public function setId_promo($id_promo) {
        $this->id_promo = $id_promo;
    }

    public function __toString() {
        $str = '';
        $str.='id_ingreso : ' . $this->id_ingreso . '; ';
        $str.='id_cli : ' . $this->id_cli . '; ';
        $str.='usr_carga : ' . $this->usr_carga . '; ';
        $str.='fec_cont : ' . $this->fec_cont . '; ';
        $str.='id_fcontacto : ' . $this->id_fcontacto . '; ';
        $str.='id_fingreso : ' . $this->id_fingreso . '; ';
        $str.='id_promo : ' . $this->id_promo . '; ';
        $str.='id_asunto :'.$this->id_asunto.';';
        return $str;
    }

}

?>
