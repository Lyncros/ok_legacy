<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
require_once 'generic_class/class.Template.php';
require_once 'clases/class.recibodescuentos.php';
require_once 'clases/class.recibodescuentosPGDAO.php';

class RecibodescuentosBSN extends BSN{

    protected $clase = "Recibodescuentos";
    protected $nombreId = "id_recdesc";
    protected $recibodescuentos;

    public function __construct($_parametro = '') {
        $this->seteaMapa();
        if ($_parametro instanceof Recibodescuentos) {
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
        return $this->recibodescuentos->getId_recdesc();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->recibodescuentos->setId_recdesc($id);
    }
    
    public function coleccionByRecibo($id_recibo) {
        $arrayRet = array();
        $objDB = new RecibodescuentosPGDAO();
        $result = $objDB->coleccionByRecibo($id_recibo);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }
    
    public function grabaDescuentosRecibo($arrayDatos,$id_recibo,$modo){
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
                if($dato->id_recdesc==0 || $dato->id_recdesc==$orig['id_recdesc']){
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
        if($formData->id_recdesc!=0){
            $retorno=false;
        }
        return $retorno;
    }
    
    protected function cargaObjeto($formData,$id_recibo,$id_prop,$id_recdesc){
        if(is_array($formData)){
            $objData=new Recibodescuentos($formData['id_recdesc'],$id_recibo,$formData['fec_desc'],$formData['concepto'],$formData['importe'],$formData['observacion']);
        }else{
            $objData=new Recibodescuentos($formData->id_recdesc,$id_recibo,$formData->fec_desc,$formData->concepto,$formData->importe,$formData->observacion);
        }
        return $objData;
    }
    
    public function borraDescuentosRecibo($id_recibo){
        $arrayRet = array();
        $objDB = new RecibodescuentosPGDAO();
        $result = $objDB->deleteByRecibo($id_recibo);
        return $result;
    }
    
    
}

?>