<?php

include_once("clases/class.login.php");

class Cliente extends Login {

    private $id_cli;
    private $email;
    private $apellido;
    private $nombre;
    private $observacion;
    private $activa;
    private $tipo_doc;
    private $nro_doc;
    private $fecha_nac;
    private $tipo_responsable;
    private $cuit;
    private $id_pais;
    private $empresa;
    private $id_posemp;
    private $id_estciv;
    private $grupofam;
    private $publico;

    public function __construct($id_cli = 0, $usuario = "", $clave = "", $apellido = "", $nombre = "", $email = "", $tipo_doc = '', $nro_doc = '', $tipo_responsable = '', $cuit = 0, $fecha_nac = '', $id_pais = 'AR', $empresa = '', $id_posemp = 999, $id_estciv = 99, $grupofam = 1,$publico=1, $activa = 0, $observacion = '', $fecha_base = "", $errores = 0, $nueva_clave = "", $fecha_nueva = "", $maxfallos = 4, $validez = 0, $validez_nueva = 15) {

        parent::__construct($id_cli, $usuario, $clave, $fecha_base, $errores, $nueva_clave, $fecha_nueva, $maxfallos, $validez, $validez_nueva);

//	Cliente::setId_cli($id_cli);
        Cliente::setEmail($email);
        Cliente::setApellido($apellido);
        Cliente::setNombre($nombre);
        Cliente::setObservacion($observacion);
        Cliente::setActiva($activa);
        Cliente::setTipo_doc($tipo_doc);
        Cliente::setNro_doc($nro_doc);
        Cliente::setTipo_responsable($tipo_responsable);
        Cliente::setCuit($cuit);
        Cliente::setFecha_nac($fecha_nac);
        Cliente::setId_pais($id_pais);
        Cliente::setEmpresa($empresa);
        Cliente::setId_posemp($id_posemp);
        Cliente::setId_estciv($id_estciv);
        Cliente::setGrupofam($grupofam);
        Cliente::setPublico($publico);
    }

    
    /**
     * Setea los valores del Login Local con los del objeto pasado como parametro
     *
     * @param objeto tipo Login
     */
    public function seteaCliente($_login) {
        $this->setUsuario($_login->getUsuario());
        $this->setClave($_login->getClave());
        $this->setValidez($_login->getValidez());
        $this->setFecha_base($_login->getFecha_base());
        $this->setErrores($_login->getErrores());
        $this->setNueva_clave($_login->getNueva_clave());
        $this->setFecha_nueva($_login->getFecha_nueva());
        $this->setMaxfallos($_login->getMaxfallos());
        $this->setValidez_nueva($_login->getValidez_nueva());
        $this->setId_cli($_login->getId_cli());
        $this->setEmail($_login->getEmail());
        $this->setApellido($_login->getApellido());
        $this->setNombre($_login->getNombre());
        $this->setObservacion($_login->getObservacion());
        $this->setActiva($_login->getActiva());
        $this->setTipo_doc($_login->getTipo_doc());
        $this->setNro_doc($_login->getNro_doc());
        $this->setTipo_responsable($_login->getTipo_responsable());
        $this->setCuit($_login->getCuit());
        $this->setFecha_nac($_login->getFecha_nac());
        $this->setId_pais($_login->getId_pais());
        $this->setEmpresa($_login->getEmpresa());
        $this->setId_posemp($_login->getId_posemp());
        $this->setId_estciv($_login->getId_estciv());
        $this->setGrupofam($_login->getGrupofam());
        $this->setPublico($_login->getPublico());
    }

    public function getPublico() {
        return $this->publico;
    }

    public function setPublico($publico) {
        $this->publico = $publico;
    }

        public function getId_pais() {
        return $this->id_pais;
    }

    public function setId_pais($id_pais) {
        $this->id_pais = $id_pais;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    public function getId_posemp() {
        return $this->id_posemp;
    }

    public function setId_posemp($id_posemp) {
        $this->id_posemp = $id_posemp;
    }

    public function getId_estciv() {
        return $this->id_estciv;
    }

    public function setId_estciv($id_estciv) {
        $this->id_estciv = $id_estciv;
    }

    public function getGrupofam() {
        return $this->grupofam;
    }

    public function setGrupofam($grupofam) {
        $this->grupofam = $grupofam;
    }

    public function getFecha_nac() {
        return $this->fecha_nac;
    }

    public function setFecha_nac($fecha_nac) {
        $this->fecha_nac = $fecha_nac;
    }

    public function getTipo_responsable() {
        return $this->tipo_responsable;
    }

    public function setTipo_responsable($tipo_responsable) {
        $this->tipo_responsable = $tipo_responsable;
    }

    public function getCuit() {
        return $this->cuit;
    }

    public function setCuit($cuit) {
        $this->cuit = $cuit;
    }

    public function setTipo_doc($_tipo_doc) {
        $this->tipo_doc = $_tipo_doc;
    }

    public function setNro_doc($_nro_doc) {
        $this->nro_doc = $_nro_doc;
    }

    public function setActiva($_activa) {
        $this->activa = $_activa;
    }

    public function setId_cli($_id) {
        $this->id_cli = $_id;
    }

    public function setEmail($_email) {
        $this->email = $_email;
    }

    public function setApellido($_apellido) {
        $this->apellido = $_apellido;
    }

    public function setNombre($_nombre) {
        $this->nombre = $_nombre;
    }

    public function setObservacion($_observacion) {
        $this->observacion = $_observacion;
    }

    public function setProvincia($_provincia) {
        $this->provincia = $_provincia;
    }

    public function getActiva() {
        return $this->activa;
    }

    public function getId_cli() {
        return $this->id_cli;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function getTipo_doc() {
        return $this->tipo_doc;
    }

    public function getNro_doc() {
        return $this->nro_doc;
    }

    public function __toString() {
        $str = "";
        $str.='Usuario: ' . $this->getUsuario() . '; ';
        $str.='Clave: ' . $this->getClave() . '; ';
        $str.='Validez: ' . $this->getValidez() . '; ';
        $str.='Fecha_base: ' . $this->getFecha_base() . '; ';
        $str.='Errores: ' . $this->getErrores() . '; ';
        $str.='Nueva_clave: ' . $this->getNueva_clave() . '; ';
        $str.='Fecha_nueva: ' . $this->getFecha_nueva() . '; ';
        $str.='Maxfallos: ' . $this->getMaxfallos() . '; ';
        $str.='Validez_nueva: ' . $this->getValidez_nueva() . '; ';
        $str.='Id_cli: ' . $this->getId_cli() . '; ';
        $str.='Email: ' . $this->getEmail() . '; ';
        $str.='Apellido: ' . $this->getApellido() . '; ';
        $str.='Nombre: ' . $this->getNombre() . '; ';
        $str.='Observacion: ' . $this->getObservacion() . '; ';
        $str.='Activa: ' . $this->getActiva() . '; ';
        $str.='Tipo_doc: ' . $this->getTipo_doc() . '; ';
        $str.='Nro_doc: ' . $this->getNro_doc() . '; ';
        $str.='Tipo_resp: ' . $this->getTipo_responsable() . '; ';
        $str.='Cuit: ' . $this->getCuit() . '; ';
        $str.='Fecha_nac: ' . $this->getFecha_nac() . '; ';
        $str.='Pais: ' . $this->getId_pais() . '; ';
        $str.='Empresa: ' . $this->getEmpresa() . '; ';
        $str.='Posicion: ' . $this->getId_posemp() . '; ';
        $str.='EstCivil: ' . $this->getId_estciv() . '; ';
        $str.='GrupoFam: ' . $this->getGrupofam() . '; ';
        $str.='Publico: ' . $this->getPublico() . '; ';
        return $str;
    }

}

// Fin Clase Login User
?>