<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
require_once 'generic_class/class.Template.php';
require_once 'clases/class.contratovencimiento.php';
require_once 'clases/class.contratovencimientoPGDAO.php';

class ContratovencimientoBSN extends BSN{

    protected $clase = "Contratovencimiento";
    protected $nombreId = "id_venc";
    protected $contratovencimiento;

    public function __construct($_parametro = '') {
        $this->seteaMapa();
        if ($_parametro instanceof Contratovencimiento) {
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
        return $this->contratovencimiento->getId_venc();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->contratovencimiento->setId_venc($id);
    }
    
    public function coleccionPeriodosByContrato($id_contrato){
        $arrayRet=array();
        $arrayDatos=  $this->coleccionByContrato($id_contrato);
        $impAnt='';
        $ini='';
        $id_per=1;
        $arrayPer=array();
        foreach ($arrayDatos as $datos){
            $arrayFecha=  explode('-', $datos['fec_ven']);
            $dia=date('d',(mktime(0,0,0,$arrayFecha[1]+1,1,$arrayFecha[2])-1));
            $fecVen=$dia."-".$arrayFecha[1].'-'.$arrayFecha[2];
            if($datos['importe']==$impAnt){
                $arrayPer['fec_perfin']=$fecVen;//$datos['fec_ven'];
            }else{
                if($ini==''){
                    $ini=$datos['fec_ven'];
                }else{
                    $arrayRet[]=$arrayPer;
                    $id_per++;
                }
                $arrayPer['id_periodo']=$id_per;
                $arrayPer['monto']=$datos['importe'];
                $arrayPer['fec_perini']=$datos['fec_ven'];
                $arrayPer['fec_perfin']=$fecVen;//$datos['fec_ven'];
                $impAnt=$datos['importe'];
            }
        }
        $arrayRet[]=$arrayPer;
        return $arrayRet;
    }
    
    public function coleccionByContrato($id_contrato) {
        $arrayRet = array();
        $objDB = new ContratovencimientoPGDAO();
        $result = $objDB->coleccionByContrato($id_contrato);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }
    
    public function coleccionPendientesByContrato($id_contrato) {
        $arrayRet = array();
        $objDB = new ContratovencimientoPGDAO();
        $result = $objDB->coleccionPendientesByContrato($id_contrato);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }

    public function coleccionComboVencimientosByContrato($id_contrato){
        $arrayCombo=array();
//        $arrayVenc=$this->coleccionPendientesByContrato($id_contrato);
        $arrayVenc=$this->coleccionByContrato($id_contrato);
        foreach ($arrayVenc as $venc){
            $arrayCombo[]=array($venc['id_venc'],$venc['fec_ven'].' $'.$venc['importe'],$venc['pagado']);
        }
        return $arrayCombo;
    }
    
    public function grabaPeriodosContrato($arrayDatosForm,$id_contrato,$id_prop,$modo){
        $arrayDatos=  $this->periodo2fecha($arrayDatosForm);
        if($modo=='i'){
            foreach ($arrayDatos as $datos){
                $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_prop,0));
                $this->insertaDB();
            }
        }else{
            $arrayOrig=  $this->coleccionByContrato($id_contrato);
            $borrados=  $this->detectaEliminados($arrayDatos, $arrayOrig);
            foreach ($borrados as $borrar){
                $this->seteaBSN($this->cargaObjeto($borrar,$id_contrato,$id_prop,0));
                $this->borraDB();
            }
            foreach ($arrayDatos as $datos){
                $id_venc=$this->comparaCarga($datos, $arrayOrig);
                switch($id_venc){
                    case 1:
                        $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_prop,0));
                        $this->insertaDB();
                        break;
                    case -1:
                        break;
                    default :
                        $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_prop,$id_venc));
                        $this->actualizaDB();
                        break;
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
                if($dato[0]==$orig['fec_ven']){
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
        $retorno=1;
        foreach ($arrayExistentes as $orig){
            if($formData[0]==$orig['fec_ven']){
                if($formData[1]==$orig['importe']){
                    $retorno=-1;
                }else{
                    $retorno=$orig['id_venc'];
                }
                break;
            }
        }
        return $retorno;
    }
    
    protected function cargaObjeto($formData,$id_contrato,$id_prop,$id_venc){
        if(is_array($formData) && sizeof($formData)>2){
            $objData=new Contratovencimiento($formData['id_venc'],$id_prop,$id_contrato,1,0,$formData['fec_ven'],$formData['importe'],date('d-m-Y'),'');
        }else{
            $objData=new Contratovencimiento($id_venc,$id_prop,$id_contrato,1,0,$formData[0],$formData[1],date('d-m-Y'),'');
        }
        return $objData;
    }
    
    /**
     * Convierte el array de ingreso en un array de fechas y monto 
     */
    protected function periodo2fecha($arrayDatos){
        $arrayRet=array();
        foreach ($arrayDatos as $datos){
            $arrayFecIni=  explode('-', $datos->fec_perini);
            $arrayFecFin=  explode('-', $datos->fec_perfin);
            $dia=$arrayFecIni[0];
            $mesIni=$arrayFecIni[1];
            $anioIni=$arrayFecIni[2];
            $mesFin=$arrayFecFin[1];
            $anioFin=$arrayFecFin[2];
            if($anioFin > $anioIni){
                $meses=(12-$mesIni+1)+$mesFin+(12*($anioFin-$anioIni-1));
            }else{
                $meses=$mesFin-$mesIni+1;
            }
            $auxMes=$mesIni+0;
            $auxAnio=$anioIni;
            for($x=0; $x<$meses;$x++){
                if($auxMes>12){
                    $auxAnio++;
                    $auxMes=1;
                }
                if($auxMes<10){
                    $strMes='0'.$auxMes;
                }else{
                    $strMes=$auxMes;
                }
                $arrayRet[]=array($dia.'-'.$strMes.'-'.$auxAnio, $datos->monto);
                $auxMes++;
            }
        }
        return $arrayRet;
    }
    
    public function borraPeriodosContrato($id_contrato){
        $arrayRet = array();
        $objDB = new ContratovencimientoPGDAO();
        $result = $objDB->deleteByContrato($id_contrato);
        return $result;
    }
    
    public function marcaPagado($id_venc){
        $auxBSN=new ContratovencimientoBSN();
        $auxBSN->contratovencimiento->setId_venc($id_venc);
        $objDB = new ContratovencimientoPGDAO($auxBSN->getArrayTabla());
        $result = $objDB->marcaPagado();
        return $result;
        
    }

    public function desmarcaPagado($id_venc){
        $auxBSN=new ContratovencimientoBSN();
        $auxBSN->contratovencimiento->setId_venc($id_venc);
        $objDB = new ContratovencimientoPGDAO($auxBSN->getArrayTabla());
        $result = $objDB->desmarcaPagado();
        return $result;
        
    }
    
    
}

?>