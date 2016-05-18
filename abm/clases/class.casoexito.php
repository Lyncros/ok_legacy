<?php

include_once("generic_class/class.VW.php");
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("generic_class/class.PGDAO.php");
include_once ("clases/class.propiedadBSN.php");

class Casoexito {

    private $id_caso;
    private $id_elemento;
    private $tipo;
    private $comentario;
    private $publicar;
    private $orden;

    public function __construct($id_caso=0, $id_elemento=0, $tipo='',$comentario='',$pulicar=0,$orden=0
    ) {

        Casoexito::setId_caso($id_caso);
        Casoexito::setId_elemento($id_elemento);
        Casoexito::setTipo($tipo);
        Casoexito::setComentario($comentario);
        Casoexito::setPublicar($pulicar);
        Casoexito::setOrden($orden);
    }

    public function seteaCasoexito($_relac) {
        $this->setId_caso($_relac->getId_caso());
        $this->setId_elemento($_relac->getId_elemento());
        $this->setTipo($_relac->getTipo());
        $this->setComentario($_relac->getComentario());
        $this->setPublicar($_relac->getPublicar());
        $this->setOrden($_relac->getOrden());
    }

    public function getOrden() {
        return $this->orden;
    }

    public function setOrden($orden) {
        $this->orden = $orden;
    }

        public function getComentario() {
        return $this->comentario;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function getPublicar() {
        return $this->publicar;
    }

    public function setPublicar($publicar) {
        $this->publicar = $publicar;
    }

    public function setId_caso($_id_caso) {
        $this->id_caso = $_id_caso;
    }

    public function setId_elemento($_id_elemento) {
        $this->id_elemento = $_id_elemento;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getId_caso() {
        return $this->id_caso;
    }

    public function getId_elemento() {
        return $this->id_elemento;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function __toString() {
        $str='';
        $str.="id_caso:".$this->id_caso."; ";
        $str.="id_elemento:".$this->id_elemento."; ";
        $str.="tipo:".$this->tipo."; ";
        $str.="comentario:".$this->comentario."; ";
        $str.="publicar:".$this->publicar."; ";
        $str.="orden:".$this->orden."; ";
        return $str;
    }
}

// Fin clase RELACION

class CasoexitoBSN extends BSN {

    protected $clase = "Casoexito";
    protected $nombreId = "id_caso";
    protected $casoexito;

    public function __construct($_caso=0) {
        CasoexitoBSN::seteaMapa();
        if ($_caso instanceof Casoexito) {
            CasoexitoBSN::creaObjeto();
            CasoexitoBSN::seteaBSN($_caso);
        } else {
            if (is_numeric($_caso)) {
                CasoexitoBSN::creaObjeto();
                if ($_id_caso != 0) {
                    CasoexitoBSN::setId_caso($_caso);
                }
            }
        }
    }

    protected function setId_caso($_id) {
        $this->casoexito->setId_caso($_id);
    }


    /**
     * retorna el ID del objeto
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->casoexito->getId_caso();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->casoexito->setId_caso($id);
    }

    /**
     * Arma un string separando con , los id de las propiedades o emprendimientos seleccionados como caso de exito
     * @param char $tipo
     * @return string 
     */
    public function armaListaIds($tipo='p'){
        $strIds='';
        $arrayDatos=$this->coleccionCasosexitos($tipo);
        foreach ($arrayDatos as $registro) {
            $strIds.=($registro['id_elemento'].',');
        }
        if(strlen($strIds)>0){
            $strIds=substr($strIds,0,-1);
        }
        return $strIds;
    }
    
    /**
     * Retorna un array Bidimensional conteniendo una coleccion de datos, con los registros de las casos de exito que cumplen
     * con los parametros de entrada; el contener un 0 o '' implica que dicho parametro no se tomara en cuenta para el armado
     * de la coleccion
     * @param char $tipo -> tipo de elemento a mostrar
     * @return string[][] -> conteniendo la coleccion de registros
     */
    public function coleccionCasosexitos($tipo='') {
        $arrayRet = array();
        $relDB = new CasoexitoPGDAO();
        $result = $relDB->coleccionCasosexito($tipo);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
//        $retorno = $this->cargaDatosCasoexitoes($arrayRet);
//        return $retorno;
    }

    public function cargaByClave(){
        $casoDB = new CasoexitoPGDAO($this->getArrayTabla());
        $result=$casoDB->findByClave();
        $arrayRet=$this->leeDBArray($result);
        return $arrayRet;
    }
    
    public function publicarCasoExito(){
        $casoDB = new CasoexitoPGDAO($this->getArrayTabla());
        $retorno=$casoDB->publicarCasoexito();
        return $retorno;
    }
    
    public function retirarCasoExito(){
        $casoDB = new CasoexitoPGDAO($this->getArrayTabla());
        $retorno=$casoDB->retirarCasoexito();
        return $retorno;
        
    }
    
    public function proximoOrden(){
        $casoDB=new CasoexitoPGDAO();
        $result=$casoDB->mayorOrden();
        if(!$result){
            $retorno=1;
        }else{
            $arrayRes=$this->leeDBArray($result);
            $retorno=$arrayRes[0]['orden']+1;
        }
        return $retorno;
    }
    
    public function desplazaOrden($orden){
        $casoDB= new CasoexitoPGDAO();
        $retorno=$casoDB->desplazaOrden($orden);
        return $retorno;
    }
    
    public function insertaDB() {
        if($this->desplazaOrden($this->getObjeto()->getOrden())){
            parent::insertaDB();
        }
    }
    
    public function actualizaDB() {
        if($this->desplazaOrden($this->getObjeto()->getOrden())){
            parent::actualizaDB();
        }
    }
}

class CasoexitoPGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.caso_exito (id_caso,id_elemento,tipo,comentario,publicar,orden) values (#id_caso#,#id_elemento#,'#tipo#','#comentario#',#publicar#,#orden#)";
    protected $UPDATE = "UPDATE #dbName#.caso_exito SET comentario='#comentario#', publicar=#publicar#,orden=#orden# WHERE id_caso=#id_caso#";
    protected $DELETE = "DELETE FROM #dbName#.caso_exito WHERE id_caso=#id_caso#";
    protected $FINDBYID = "SELECT id_caso,id_elemento,tipo,comentario,publicar,orden FROM #dbName#.caso_exito WHERE id_caso=#id_caso#";
    protected $FINDBYCLAVE = "SELECT id_caso,id_elemento,tipo,comentario,publicar,orden FROM #dbName#.caso_exito WHERE id_elemento=#id_elemento# AND tipo='#tipo#'";
    protected $COLECCION = "SELECT id_caso,id_elemento,tipo,comentario,publicar,orden FROM #dbName#.caso_exito ORDER BY id_caso,tipo";
    protected $COLECCIONBASE = "SELECT id_caso,id_elemento,tipo,comentario,publicar,orden FROM #dbName#.caso_exito";
    protected $PUBLICAR="UPDATE #dbName#.caso_exito set publicar=1";
    protected $RETIRAR="UPDATE #dbName#.caso_exito set publicar=0";
    protected $ULTIMOORDEN = "SELECT MAX(orden) as orden from #dbName#.caso_exito";
    protected $DESPLAZAORDEN = "UPDATE #dbName#.caso_exito SET orden=orden+1 WHERE orden>=";
    
    public function desplazaOrden($orden){
        $parametro = func_get_args();
        $resultado = $this->execSql($this->DESPLAZAORDEN.$orden, $parametro);
        if (!$resultado) {
            $this->onError($COD_COLLECTION, "MAYOR ORDEN" . $this->DESPLAZAORDEN.$orden);
        }
        return $resultado;
        
    }
    
    public function mayorOrden(){
        $parametro = func_get_args();
        $resultado = $this->execSql($this->ULTIMOORDEN, $parametro);
        if (!$resultado) {
            $this->onError($COD_COLLECTION, "MAYOR ORDEN" . $this->ULTIMOORDEN);
        }
        return $resultado;

    }
    
    public function coleccionCasosexito($_tipo='') {
        $parametro = func_get_args();
        $where=' WHERE publicar=1 ';
        if($_tipo!=''){
            $where.= " AND tipo='$_tipo' ";
        }
        $order=" ORDER BY orden ";
        $resultado = $this->execSql($this->COLECCIONBASE . $where . $order, $parametro);
        if (!$resultado) {
            $this->onError($COD_COLLECION, "CASOS DE EXITO" . $this->COLECCIONBASE . $where . $order);
        }
        return $resultado;
    }

    public function publicarCasoexito() {
        $parametro = func_get_args();
        $resultado = $this->execSql($this->PUBLICAR, $parametro);
        if (!$resultado) {
            $this->onError($COD_PUBLICAR, "CASOS DE EXITO" . $this->PUBLICAR);
        }
        return $resultado;
    }
    
    public function retirarCasoexito() {
        $parametro = func_get_args();
        $resultado = $this->execSql($this->RETIRAR, $parametro);
        if (!$resultado) {
            $this->onError($COD_PUBLICAR, "CASOS DE EXITO" . $this->RETIRAR);
        }
        return $resultado;
    }

}

// Fin clase DAO

?>
