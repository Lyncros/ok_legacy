<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
require_once 'generic_class/class.Template.php';
require_once 'clases/class.recibodocumentos.php';
require_once 'clases/class.recibodocumentosPGDAO.php';

class RecibodocumentosBSN extends BSN{

    protected $clase = "Recibodocumentos";
    protected $nombreId = "id_recdocs";
    protected $recibodocumentos;

    public function __construct($_parametro = '') {
        $this->seteaMapa();
        if ($_parametro instanceof Recibodocumentos) {
            $this->creaObjeto();
            $this->seteaBSN($_parametro);
        } else {
            $this->creaObjeto();
            if (is_numeric($_parametro) && $_parametro != 0) {
                $this->cargaById($_parametro);
            }
        }
    }

    /**
     * retorna el ID del objeto 
     *
     * @return id del objeto
     */
    public function getId() {
        return $this->recibodocumentos->getId_recdocs();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->recibodocumentos->setId_recdocs($id);
    }
    
    public function coleccionByRecibo($id_recibo) {
        $arrayRet = array();
        $objDB = new RecibodocumentosPGDAO();
        $result = $objDB->coleccionByRecibo($id_recibo);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }

    public function grabaDocumentosRecibo($arrayDatos,$id_recibo,$modo){
        if($modo=='i'){
            foreach ($arrayDatos as $datos){
                $this->seteaBSN($this->cargaObjeto($datos,$id_recibo));
                $this->insertaDB();
            }
        }else{
            $arrayOrig=  $this->coleccionByRecibo($id_recibo);
            $borrados=  $this->detectaEliminados($arrayDatos, $arrayOrig);
            foreach ($borrados as $borrar){
                $this->seteaBSN($this->cargaObjeto($borrar,$id_recibo,0));
                $this->borraDB();
            }
            foreach ($arrayDatos as $datos){
                $this->seteaBSN($this->cargaObjeto($datos,$id_recibo,0));
                if($this->comparaCarga($datos, $arrayOrig)){
                    $this->insertaDB();
                }else{
                    $this->actualizaDB();
                }
            }
        }
        return true;
    }

    protected function detectaEliminados($arrayDatos,$arrayExistentes){
        $arrayBorrados=array();
        foreach ($arrayExistentes as $orig){
            $marcaBorrar=1;
            foreach ($arrayDatos as $dato){
                if($dato->id_recdocs==0 || $dato->id_recdocs==$orig['id_recdocs']){
                    $marcaBorrar=0;
                    break;
                }
            }
            if($marcaBorrar==1){
                $arrayBorrados[]=$orig;
            }
        }
        return $arrayBorrados;
    }
    
    protected function comparaCarga($formData,$arrayExistentes){
        $retorno=true;
        if($formData->id_recdocs!=0){
            $retorno=false;
        }
        return $retorno;
    }
    
    protected function cargaObjeto($formData,$id_recibo,$id_recdocs){
        if(is_array($formData)){
            $objData=new Recibodocumentos($formData['id_recdocs'],$id_recibo,$formData['id_impuesto'],$formData['concepto'],$formData['importe'],$formData['observacion']);
        }else{
            $objData=new Recibodocumentos($formData->id_recdocs,$id_recibo,$formData->id_impuesto,$formData->concepto,$formData->importe,$formData->observacion);
        }
        return $objData;
    }
    
    public function borraDocumentosRecibo($id_recibo){
        $arrayRet = array();
        $objDB = new RecibodocumentosPGDAO();
        $result = $objDB->deleteByRecibo($id_recibo);
        return $result;
    }
    
    
}

?>