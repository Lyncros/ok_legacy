<?php

class Propiedad {

	/*	CREATE TABLE  `achaval`.`propiedad` (
	 `id_prop` int(10) unsigned NOT NULL auto_increment,
	 `id_zona` varchar(100) NOT NULL,
	 `id_loca` varchar(100) NOT NULL,
	 `calle` varchar(150) NOT NULL,
	 `entre1` varchar(150) NOT NULL,
	 `entre2` varchar(150) NOT NULL,
	 `nro` varchar(45) NOT NULL,
	 `descripcion` varchar(500) NOT NULL,
	 `id_tipo_prop` int(10) unsigned default NULL,
	 */

	private $id_tipo_prop;
	private $id_prop;
	private $id_sucursal;
	private $id_ubica;
//	private $id_zona;
//	private $id_loca;
	private $calle;
	private $entre1;
	private $entre2;
	private $nro;
	private $descripcion;
	private $intermediacion;
	private $id_inmo;
	private $operacion;
	private $piso;
	private $dpto;
	private $comentario;
	private $video;
	private $id_cliente;
	private $goglat;
	private $goglong;
	private $activa;
	private $subtipo_prop;
	private $id_emp;
	private $nomedif;
	private $plano1;
	private $plano2;
	private $plano3;
	private $id_parcela;
	private $id_comercial;
	private $compartir;
	private $suptot;
	private $supcub;
	private $cantamb;
	private $monalq;
	private $valalq;
	private $monven;
	private $valven;
	private $nombre_zona;
	private $nombre_loca;
	private $ntipo_prop;
	private $publicaprecio;
	private $destacado;
        private $oportunidad;
        private $publicadir;
        
	public function __construct(
	$id_prop=0,
	$id_ubica=0,
//	$id_zona=0,
	$id_sucursal='',
//	$id_loca=0,
	$calle='',
	$entre1='',
	$entre2='',
	$nro='',
	$descripcion='',
	$id_tipo_prop='',
	$intermediacion='',
	$id_inmo=0,
	$operacion='',
	$piso='',
	$dpto='',
	$comentario='',
	$video='',
	$id_cliente=0,
	$goglat=0,
	$goglong=0,
	$activa=0,
	$subtipo_prop='',
	$id_emp=0,
	$nomedif='',
	$plano1='',
	$plano2='',
	$plano3='',
	$id_parcela='',
	$id_comercial='',
	$compartir=1,
	$publicaprecio=0,
	$destacado=0,
        $oportunidad=0,
        $publicadir=0,
	$suptot=0,
	$supcub=0,
	$cantamb=0,
	$monalq=0,
	$valalq=0,
	$monven=0,
	$valven=0,
	$nombre_zona='',
	$nombre_loca='',
	$ntipo_prop=''
	) {

		Propiedad::setId_tipo_prop($id_tipo_prop);
		Propiedad::setId_prop($id_prop);
		Propiedad::setId_ubica($id_ubica);
//		Propiedad::setId_zona($id_zona);
//		Propiedad::setId_loca($id_loca);
		Propiedad::setId_sucursal($id_sucursal);
		Propiedad::setCalle($calle);
		Propiedad::setEntre1($entre1);
		Propiedad::setEntre2($entre2);
		Propiedad::setNro($nro);
		Propiedad::setDescripcion($descripcion);
		Propiedad::setIntermediacion($intermediacion);
		Propiedad::setId_inmo($id_inmo);
		Propiedad::setOperacion($operacion);
		Propiedad::setComentario($comentario);
		Propiedad::setVideo($video);
		Propiedad::setPiso($piso);
		Propiedad::setDpto($dpto);
		Propiedad::setId_cliente($id_cliente);
		Propiedad::setGoglat($goglat);
		Propiedad::setGoglong($goglong);
		Propiedad::setActiva($activa);
		Propiedad::setSubtipo_prop($subtipo_prop);
		Propiedad::setId_emp($id_emp);
		Propiedad::setNomedif($nomedif);
		Propiedad::setPlano1($plano1);
		Propiedad::setPlano2($plano2);
		Propiedad::setPlano3($plano3);
		Propiedad::setId_parcela($id_parcela);
		Propiedad::setId_comercial($id_comercial);
		Propiedad::setCompartir($compartir);
		Propiedad::setPublicaprecio($publicaprecio);
		Propiedad::setDestacado($destacado);
                Propiedad::setOportunidad($oportunidad);
                Propiedad::setPublicadir($publicadir);
		Propiedad::setSuptot($suptot);
		Propiedad::setSupcub($supcub);
		Propiedad::setCantamb($cantamb);
		Propiedad::setMonalq($monalq);
		Propiedad::setValalq($valalq);
		Propiedad::setMonven($monven);
		Propiedad::setValven($valven);
		Propiedad::setNombre_zona($nombre_zona);
		Propiedad::setNombre_loca($nombre_loca);
		Propiedad::setNtipo_prop($ntipo_prop);
	}


	public function seteaPropiedad($_tipo_prop) {
		$this->setId_tipo_prop($_tipo_prop->getId_tipo_prop());
		$this->setId_prop($_tipo_prop->getId_prop());
		$this->setId_sucursal($_tipo_prop->getId_sucursal());
		$this->setId_ubica($_tipo_prop->getId_ubica());
//		$this->setId_zona($_tipo_prop->getId_zona());
//		$this->setId_loca($_tipo_prop->getId_loca());
		$this->setCalle($_tipo_prop->getCalle());
		$this->setEntre1($_tipo_prop->getEntre1());
		$this->setEntre2($_tipo_prop->getEntre2());
		$this->setNro($_tipo_prop->getNro());
		$this->setDescripcion($_tipo_prop->getDescripcion());
		$this->setIntermediacion($_tipo_prop->getIntermediacion());
		$this->setId_inmo($_tipo_prop->getId_inmo());
		$this->setOperacion($_tipo_prop->getOperacion());
		$this->setComentario($_tipo_prop->getComentario());
		$this->setVideo($_tipo_prop->getVideo());
		$this->setPiso($_tipo_prop->getPiso());
		$this->setDpto($_tipo_prop->getDpto());
		$this->setId_cliente($_tipo_prop->getId_cliente());
		$this->setGoglat($_tipo_prop->getGoglat());
		$this->setGoglong($_tipo_prop->getGoglong());
		$this->setActiva($_tipo_prop->getActiva());
		$this->setSubtipo_prop($_tipo_prop->getSubtipo_prop());
		$this->setId_emp($_tipo_prop->getId_emp());
		$this->setNomedif($_tipo_prop->getNomedif());
		$this->setPlano1($_tipo_prop->getPlano1());
		$this->setPlano2($_tipo_prop->getPlano2());
		$this->setPlano3($_tipo_prop->getPlano3());
		$this->setId_parcela($_tipo_prop->getId_parcela());
		$this->setId_comercial($_tipo_prop->getId_comercial());
		$this->setCompartir($_tipo_prop->getCompartir());
		$this->setPublicaprecio($_tipo_prop->getPublicaprecio());
		$this->setDestacado($_tipo_prop->getDestacado());
                $this->setOportunidad($_tipo_prop->getOportunidad());
                $this->setPublicadir($_tipo_prop->getPublicadir());
		$this->setSuptot($_tipo_prop->getSuptot());
		$this->setSupcub($_tipo_prop->getSupcub());
		$this->setCantamb($_tipo_prop->getCantamb());
		$this->setMonalq($_tipo_prop->getMonalq());
		$this->setValalq($_tipo_prop->getValalq());
		$this->setMonven($_tipo_prop->getMonven());
		$this->setValven($_tipo_prop->getValven());
		$this->setNombre_zona($_tipo_prop->getNombre_zona());
		$this->setNombre_loca($_tipo_prop->getNombre_loca());
		$this->setNtipo_prop($_tipo_prop->getNtipo_prop());
	}

        public function getPublicadir() {
            return $this->publicadir;
        }

        public function setPublicadir($publicadir) {
            $this->publicadir = $publicadir;
        }

                public function setOportunidad($oportunidad){
            $this->oportunidad=$oportunidad;
        }
        
        public function getOportunidad(){
            return $this->oportunidad;
        }
        
	public function setDestacado($destacado){
		$this->destacado=$destacado;
	}
	
	public function getDestacado(){
		return $this->destacado;
	}
	
	public function setPublicaprecio($publica){
		$this->publicaprecio=$publica;
	}

	public function getPublicaprecio(){
		return $this->publicaprecio;
	}

	public function setNtipo_prop($ntipo_prop){
		$this->ntipo_prop = $ntipo_prop;
	}

	public function setNombre_zona($nombre_zona){
		$this->nombre_zona = $nombre_zona;
	}

	public function setNombre_loca($nombre_loca){
		$this->nombre_loca = $nombre_loca;
	}

	public function setSuptot($sup){
		$this->suptot = $sup;
	}

	public function setSupcub($sup){
		$this->supcub = $sup;
	}

	public function setCantamb($cant){
		$this->cantamb=$cant;
	}

	public function setMonalq($mal){
		$this->monalq=$mal;
	}
	public function setValalq($val){
		$this->valalq=$val;
	}

	public function setMonven($mal){
		$this->monven=$mal;
	}
	public function setValven($val){
		$this->valven=$val;
	}

	public function getNtipo_prop(){
		return $this->ntipo_prop ;
	}

	public function getNombre_zona(){
		return $this->nombre_zona ;
	}

	public function getNombre_loca(){
		return $this->nombre_loca ;
	}

	public function getSuptot(){
		return $this->suptot ;
	}

	public function getSupcub(){
		return $this->supcub;
	}

	public function getCantamb(){
		return $this->cantamb;
	}

	public function getMonalq(){
		return $this->monalq;
	}
	public function getValalq(){
		return $this->valalq;
	}

	public function getMonven(){
		return $this->monven;
	}
	public function getValven(){
		return $this->valven;
	}


	public function setPlano1($plano1) {
		$this->plano1 = $plano1;
	}

	public function setPlano2($plano2) {
		$this->plano2 = $plano2;
	}

	public function setPlano3($plano3) {
		$this->plano3 = $plano3;
	}

	public function setId_parcela($id_parcela) {
		$this->id_parcela = $id_parcela;
	}

	public function setId_comercial($id_comercial) {
		$this->id_comercial = $id_comercial;
	}

	public function setCompartir($compartir) {
		$this->compartir = $compartir;
	}

	public function setNomedif($nomedif) {
		$this->nomedif = $nomedif;
	}

	public function setId_tipo_prop($_id_tipo_prop) {
		$this->id_tipo_prop = $_id_tipo_prop;
	}

	public function setSubtipo_prop($_subtipo_prop) {
		$this->subtipo_prop = $_subtipo_prop;
	}

	public function setActiva($activa) {
		$this->activa=$activa;
	}

	public function setId_prop($id_prop) {
		$this->id_prop = $id_prop;
	}

	public function setId_emp($id_emp) {
		$this->id_emp = $id_emp;
	}

	public function setId_sucursal($id_sucursal) {
		$this->id_sucursal = $id_sucursal;
	}

	public function setId_ubica($id_ubica) {
		$this->id_ubica=$id_ubica;
	}
/*
	public function setId_zona($id_zona) {
		$this->id_zona=$id_zona;
	}
	public function setId_loca($id_loca) {
		$this->id_loca=$id_loca;
	}
*/
	public function setId_cliente($id_cli) {
		$this->id_cliente=$id_cli;
	}

	public function setGoglat($lat) {
		$this->goglat=$lat;
	}

	public function setGoglong($long) {
		$this->goglong=$long;
	}

	public function setCalle($calle) {
		$this->calle=$calle;
	}

	public function setEntre1($entre1) {
		$this->entre1=$entre1;
	}

	public function setEntre2($entre2) {
		$this->entre2=$entre2;
	}

	public function setNro($nro) {
		$this->nro=$nro;
	}

	public function setDescripcion($descripcion) {
		$this->descripcion=$descripcion;
	}

	public function setIntermediacion($interme) {
		$this->intermediacion=$interme;
	}

	public function setId_inmo($id_inmo) {
		$this->id_inmo=$id_inmo;
	}

	public function setOperacion($oper) {
		$this->operacion=$oper;
	}

	public function setComentario($comen) {
		$this->comentario=$comen;
	}

	public function setVideo($video) {
		$this->video=$video;
	}

	public function setPiso($piso) {
		$this->piso=$piso;
	}

	public function setDpto($dpto) {
		$this->dpto=$dpto;
	}



	public function getPlano1() {
		return $this->plano1;
	}

	public function getPlano2() {
		return $this->plano2;
	}

	public function getPlano3() {
		return $this->plano3;
	}

	public function getId_parcela() {
		return $this->id_parcela;
	}

	public function getId_comercial() {
		return $this->id_comercial;
	}

	public function getCompartir() {
		return $this->compartir;
	}

	public function getNomedif() {
		return $this->nomedif;
	}

	public function getId_cliente() {
		return $this->id_cliente;
	}

	public function getActiva() {
		return $this->activa;
	}

	public function getGoglat() {
		return $this->goglat;
	}

	public function getGoglong() {
		return $this->goglong;
	}

	public function getId_sucursal() {
		return $this->id_sucursal;
	}

	public function getId_prop() {
		return $this->id_prop;
	}

	public function getId_emp() {
		return $this->id_emp;
	}

	public function getId_ubica() {
		return $this->id_ubica;
	}
/*
	public function getId_zona() {
		return $this->id_zona;
	}

	public function getId_loca() {
		return $this->id_loca;
	}
*/
	public function getCalle() {
		return $this->calle;
	}

	public function getEntre1() {
		return $this->entre1;
	}

	public function getEntre2() {
		return $this->entre2;
	}

	public function getNro() {
		return $this->nro;
	}

	public function getDescripcion() {
		return $this->descripcion;
	}

	public function getId_tipo_prop() {
		return $this->id_tipo_prop;
	}

	public function getSubtipo_prop() {
		return $this->subtipo_prop;
	}

	public function getIntermediacion() {
		return $this->intermediacion;
	}

	public function getId_inmo() {
		return  $this->id_inmo;
	}

	public function getOperacion() {
		return $this->operacion;
	}

	public function getComentario() {
		return $this->comentario;
	}

	public function getVideo() {
		return $this->video;
	}

	public function getPiso() {
		return $this->piso;
	}

	public function getDpto() {
		return $this->dpto;
	}

	public function __toString(){
		$str='';
		$str.='id_prop: '.$this->id_prop.' ;';
		$str.='id_tipo_prop: '.$this->id_tipo_prop.' ;';
		$str.='subtipo_prop: '.$this->subtipo_prop.' ;';
		$str.='operacion: '.$this->operacion.' ;';
		$str.='id_sucursal: '.$this->id_sucursal.' ;';
		$str.='id_ubica: '.$this->id_ubica.' ;';
//		$str.='id_zona: '.$this->id_zona.' ;';
//		$str.='id_loca: '.$this->id_loca.' ;';
		$str.='calle: '.$this->calle.' ;';
		$str.='nro: '.$this->nro.' ;';
		$str.='piso: '.$this->piso.' ;';
		$str.='dpto: '.$this->dpto.' ;';
		$str.='entre1: '.$this->entre1.' ;';
		$str.='entre2: '.$this->entre2.' ;';
		$str.='descripcion: '.$this->descripcion.' ;';
		$str.='intermediacion: '.$this->intermediacion.' ;';
		$str.='id_inmo: '.$this->id_inmo.' ;';
		$str.='comentario: '.$this->comentario.' ;';
		$str.='video: '.$this->video.' ;';
		$str.='id_cliente: '.$this->id_cliente.' ;';
		$str.='goglat: '.$this->goglat.' ;';
		$str.='goglong: '.$this->goglong.' ;';
		$str.='activa: '.$this->activa.' ;';
		$str.='id_emp: '.$this->id_emp.' ;';
		$str.='nomedif: '.$this->nomedif.' ;';
		$str.='plano1: '.$this->plano1.' ;';
		$str.='plano2: '.$this->plano2.' ;';
		$str.='plano3: '.$this->plano3.' ;';
		$str.='id_parcela: '.$this->id_parcela.' ;';
		$str.='id_comercial: '.$this->id_comercial.' ;';
		$str.='compartir: '.$this->compartir.' ;';
		$str.='publicaprecio: '.$this->publicaprecio.' ;';
		$str.='destacado: '.$this->destacado.' ;';
		$str.='oportunidad: '.$this->oportunidad.' ;';
                $str.='publicadir: '.$this->publicadir.' ;';
		return $str;
	}

}

?>
