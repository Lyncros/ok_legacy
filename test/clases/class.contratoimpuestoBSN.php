<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
require_once 'generic_class/class.Template.php';
require_once 'clases/class.contratoimpuesto.php';
require_once 'clases/class.contratoimpuestoPGDAO.php';

class ContratoimpuestoBSN extends BSN{
    
    protected $clase = "Contratoimpuesto";
    protected $nombreId = "id_improp";
    protected $contratoimpuesto;

    public function __construct($_parametro = '') {
        $this->seteaMapa();
        if ($_parametro instanceof Contratoimpuesto) {
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
        return $this->contratoimpuesto->getId_improp();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->contratoimpuesto->setId_improp($id);
    }

    public function coleccionByContrato($id_contrato) {
        $arrayRet = array();
        $objDB = new ContratoimpuestoPGDAO();
        $result = $objDB->coleccionByContrato($id_contrato);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }        
    
    public function coleccionByContratoFE($id_contrato) {
        $arrayRet = array();
        $objDB = new ContratoimpuestoPGDAO();
        $result = $objDB->coleccionByContrato($id_contrato);
        $arrayResult = $this->leeDBArray($result);
        $indice=0;
        foreach ($arrayResult as $datos){
            $indice++;
            $arrayRet[]=  $this->objeto2dataFE($datos,$indice);
        }
        return $arrayRet;
    }        
    
    public function cargaByIdFE($id){
        $this->cargaById($id);
        return $this->objeto2dataFE($this->getObjeto(),0);
    }
  
    public function getObjetoViewFE() {
        return $this->objeto2dataFE($this->getObjetoView(),0);
    }
    
/*
aviso: "0"
id_contrato: "8"
id_improp: "1"
id_impuesto: "1"
id_prop: "4234"
observacion: ""
porcentaje: "100"
prim_venc: "18-06-2014"

{"indice":1,"id_impcont":"0","impuesto":"1","porcentaje":"100","vencimiento":"02-06-2014"}

*/
    protected function objeto2dataFE($datos,$indice){
        $arrayRet=array();
        $arrayRet['indice']=$indice;
        $arrayRet['id_impcont']=$datos['id_improp'];
        $arrayRet['impuesto']=$datos['id_impuesto'];
        $arrayRet['porcentaje']=$datos['porcentaje'];
        $arrayRet['vencimiento']=$datos['prim_venc'];
        return $arrayRet;
    }
    
    public function grabaImpuestosContrato($arrayDatos,$id_contrato,$id_prop,$modo){
        $modoLocal='u';
        if($modo=='i'){
            foreach ($arrayDatos as $datos){
                $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_prop));
                $this->insertaDB();
            }
        }else{
            $arrayOrig=  $this->coleccionByContrato($id_contrato);
            $borrados=  $this->detectaEliminados($arrayDatos, $arrayOrig);
            foreach ($borrados as $borrar){
                $this->seteaBSN($this->cargaObjeto($borrar,$id_contrato,$id_prop));
                $this->borraDB();
            }
            foreach ($arrayDatos as $datos){
                $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_prop));
                if($this->comparaCarga($datos, $arrayOrig)){
//                    $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_prop));
                    $this->insertaDB();
                }else{
//                    $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_prop));
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
                if($dato->id_impcont==0 || $dato->id_impcont==$orig['id_improp']){
                    $marcaBorrar=0;
                    break;
                }
            }
            if($marcaBorrar==1){
                $arrayBorrados[]=$orig;//$dato;
            }
        }
        return $arrayBorrados;
    }
    
    protected function comparaCarga($formData,$arrayExistentes){
        $retorno=true;
        if($formData->id_impcont!=0){
            $retorno=false;
        }
        return $retorno;
    }
    
    protected function cargaObjeto($formData,$id_contrato,$id_prop){
        if(is_array($formData)){
            $objData=new Contratoimpuesto($formData['id_improp'],$id_prop,$id_contrato,$formData['id_impuesto'],$formData['porcentaje'],$formData['prim_venc'],0,'');
        }else{
            $objData=new Contratoimpuesto($formData->id_impcont,$id_prop,$id_contrato,$formData->impuesto,$formData->porcentaje,$formData->vencimiento,0,'');
        }
        return $objData;
    }
    
    
    
    public function borraImpuestosContrato($id_contrato){
        $arrayRet = array();
        $objDB = new ContratoimpuestoPGDAO();
        $result = $objDB->deleteByContrato($id_contrato);
        return $result;
    }
}

?>