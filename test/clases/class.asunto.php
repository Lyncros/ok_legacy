<?php

class Asunto {

    private $id_asunto;
    private $id_cli;
    private $id_tipoasu;
    private $fec_inicio;
    private $fec_cierre;
    private $comentario;
    private $user_ini;
    private $user_fin;
    private $titulo;
    private $estado;


    public function __construct($id_asunto = 0, $id_cli = 0, $id_tipoasu = 0,$titulo='', $fec_inicio = '', $fec_cierre = '', $comentario = '', $user_ini = 0, $user_fin = 0,$estado='') {
        Asunto::setId_asunto($id_asunto);
        Asunto::setId_cli($id_cli);
        Asunto::setId_tipoasu($id_tipoasu);
        Asunto::setFec_inicio($fec_inicio);
        Asunto::setFec_cierre($fec_cierre);
        Asunto::setComentario($comentario);
        Asunto::setUser_ini($user_ini);
        Asunto::setUser_fin($user_fin);
        Asunto::setTitulo($titulo);
        Asunto::setEstado($estado);
        
    }

    public function seteaAsunto($_asunto) {
        $this->setId_asunto($_asunto->getId_asunto());
        $this->setId_cli($_asunto->getId_cli());
        $this->setId_tipoasu($_asunto->getId_tipoasu());
        $this->setFec_inicio($_asunto->getFec_inicio());
        $this->setFec_cierre($_asunto->getFec_cierre());
        $this->setComentario($_asunto->getComentario());
        $this->setUser_ini($_asunto->getUser_ini());
        $this->setUser_fin($_asunto->getUser_fin());
        $this->setTitulo($_asunto->getTitulo());
        $this->setEstado($_asunto->getEstado());
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    
    public function getId_asunto() {
        return $this->id_asunto;
    }

    public function setId_asunto($id_asunto) {
        $this->id_asunto = $id_asunto;
    }

    public function getId_cli() {
        return $this->id_cli;
    }

    public function setId_cli($id_cli) {
        $this->id_cli = $id_cli;
    }

    public function getId_tipoasu() {
        return $this->id_tipoasu;
    }

    public function setId_tipoasu($id_tipoasu) {
        $this->id_tipoasu = $id_tipoasu;
    }

    public function getFec_inicio() {
        return $this->fec_inicio;
    }

    public function setFec_inicio($fec_inicio) {
        $this->fec_inicio = $fec_inicio;
    }

    public function getFec_cierre() {
        return $this->fec_cierre;
    }

    public function setFec_cierre($fec_cierre) {
        $this->fec_cierre = $fec_cierre;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function getUser_ini() {
        return $this->user_ini;
    }

    public function setUser_ini($user_ini) {
        $this->user_ini = $user_ini;
    }

    public function getUser_fin() {
        return $this->user_fin;
    }

    public function setUser_fin($user_fin) {
        $this->user_fin = $user_fin;
    }

    public function __toString() {
        $str = "Id_asunto: " . $this->id_asunto . "\n";
        $str.="Id_cli: " . $this->id_cli . "\n";
        $str.="Tipo: " . $this->id_tipoasu. "\n";
        $str.="Titulo: " . $this->titulo. "\n";
        $str.="Inicio: " . $this->fec_inicio. "\n";
        $str.="Cierre: " . $this->fec_cierre. "\n";
        $str.="Comentario: " . $this->comentario. "\n";
        $str.="Usario Inicio" . $this->user_ini. "\n";
        $str.="Usuario Cierre" . $this->user_fin  . "\n";
        $str.="Estado" . $this->estado  . "\n";
    }

}

?>
