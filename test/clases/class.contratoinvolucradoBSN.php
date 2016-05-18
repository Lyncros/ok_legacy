<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
require_once 'generic_class/class.Template.php';
require_once 'clases/class.contratoinvolucrado.php';
require_once 'clases/class.contratoinvolucradoPGDAO.php';
require_once 'clases/class.clienteBSN.php';
require_once 'clases/class.relacion.php';
require_once 'clases/class.telefonosBSN.php';
require_once 'clases/class.medioselectronicosBSN.php';

class ContratoinvolucradoBSN extends BSN{
    
    protected $clase = "Contratoinvolucrado";
    protected $nombreId = "id_involucrado";
    protected $contratoinvolucrado;

    public function __construct($_parametro = '') {
        $this->seteaMapa();
        if ($_parametro instanceof Contratoinvolucrado) {
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
        return $this->contratoinvolucrado->getId_involucrado();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->contratoinvolucrado->setId_involucrado($id);
    }

    public function coleccionByContrato($id_contrato) {
        $arrayRet = array();
        $objDB = new ContratoinvolucradoPGDAO();
        $result = $objDB->coleccionByContrato($id_contrato);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }        
    
    public function coleccionByContratoFE($id_contrato) {
        $arrayRet = array();
        $objDB = new ContratoinvolucradoPGDAO();
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
    
    protected function objeto2dataFE($datos,$indice){
        $arrayRet=array();
        $arrayRet['indice']=$indice;
        $arrayRet['id_involucrado']=$datos['id_involucrado'];
        $arrayRet['id_cli']=$datos['id_cli'];
        $arrayRet['id_relacion']=$datos['id_relacion'];
        $cliBSN=new ClienteBSN();
        $cliBSN->cargaById($datos['id_cli']);
        $telsBSN=new TelefonosBSN();
        $arrTels=$telsBSN->coleccionByClienteString($datos['id_cli']);
        $medBSN=new MedioselectronicosBSN();
        $arrMeds=$medBSN->coleccionByClienteString($datos['id_cli']);
        $tipoRel=new TiporelacionBSN($datos['id_relacion']);
        
        $arrayRet['nombre']=$cliBSN->getObjeto()->getNombre();
        $arrayRet['apellido']=$cliBSN->getObjeto()->getApellido();
        if(isset($arrTels[1])){
            $arrayRet['telefono']=$arrTels[0];
        }else{
            $arrayRet['telefono']='';
        }
        if(isset($arrTels[1])){
            $arrayRet['celular']=$arrTels[1];
        }else{
            $arrayRet['celular']='';
        }
        if(isset($arrMeds[0])){
            $arrayRet['mail']=$arrMeds[0];
        }else{
            $arrayRet['mail']='';
        }
        $arrayRet['relacion']=$tipoRel->getObjeto()->getRelacion();
        return $arrayRet;
    }
    
    public function grabaInvolucradosContrato($arrayDatos,$id_contrato,$id_prop,$modo){
        $modoLocal='u';
        if($modo=='i'){
            foreach ($arrayDatos as $datos){
                if(is_array($datos)){
                    if($datos['id_cli']==0){
                        $id_cli=  $this->grabaClienteNuevo($datos);
                    }else{
                        $id_cli=$datos['id_cli'];
                    }
                    $rel=$datos['id_relacion'];
                }else{
                    if($datos->id_cli==0){
                        $id_cli=  $this->grabaClienteNuevo($datos);
                    }else{
                        $id_cli=$datos->id_cli;
                    }
                    $rel=$datos->id_relacion;
                }
                $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_cli));
                $this->insertaDB();
                $this->grabaRelacion($id_cli, $id_prop,$rel);
            }
        }else{
            $arrayOrig=  $this->coleccionByContrato($id_contrato);
            $borrados=  $this->detectaEliminados($arrayDatos, $arrayOrig);
            foreach ($borrados as $borrar){
                $id_cli=$borrar['id_cli'];
                $this->seteaBSN($this->cargaObjeto($borrar,$id_contrato,$id_cli));
                $this->borraDB();
                $this->borraRelacion($id_cli, $id_prop);
            }
            foreach ($arrayDatos as $datos){
                if($datos->id_cli==0){
                    $id_cli=  $this->grabaClienteNuevo($datos);
                }else{
                    $id_cli=$datos->id_cli;
                }
                $estado=$this->comparaCarga($datos, $arrayOrig);
                switch($estado){
                    case 1:
                        $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_cli));
                        $this->insertaDB();
                        break;
                    case -1:
                        break;
                    default :
                        $this->seteaBSN($this->cargaObjeto($datos,$id_contrato,$id_cli));
                        $this->actualizaDB();
                        $this->borraRelacion($id_cli, $id_prop);
                        break;
                }
                $this->grabaRelacion($id_cli, $id_prop,$datos->id_relacion);

            }
        }
        return true;
    }

    protected function borraRelacion($id_pc,$id_sc){
        $relBSN=new RelacionBSN($id_pc,$id_sc,0);
        $relBSN->borraDB(0);
    }
    
    protected function grabaRelacion($id_pc,$id_sc,$id_rel){
        $relBSN=new RelacionBSN($id_pc,$id_sc,$id_rel);
        $relBSN->insertaDB();
    }
    
    protected function grabaClienteNuevo($dato){
        $cliBSN=new ClienteBSN();
        $id_cli=$cliBSN->grabaClienteReducido($dato);
//        $cliBSN->seteaBSN($cliBSN->leeDatosForm($datos));
//        $id_cli=date('YmdHis');
//        $cliBSN->setId($id_cli);
//        $cliBSN->clienteNuevo();
        return $id_cli;
    }
    
    protected function detectaEliminados($arrayDatos,$arrayExistentes){
        $arrayBorrados=array();
        foreach ($arrayExistentes as $orig){
            $marcaBorrar=1;
            foreach ($arrayDatos as $dato){
                if($dato->id_involucrado==$orig['id_involucrado']){
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
/*
        $retorno=true;
        if($formData->id_involucrado!=0){
            $retorno=false;
        }
 */
        $retorno=1;
        foreach ($arrayExistentes as $orig){
            if($formData->id_involucrado==$orig['id_involucrado']){
                if($formData->id_relacion==$orig['id_relacion']){
                    $retorno=-1;
                }else{
                    $retorno=$orig['id_relacion'];
                }
                break;
            }
        }

        return $retorno;
    }
    
    protected function cargaObjeto($formData,$id_contrato,$id_cli){
        if(is_array($formData)){
            $objData=new Contratoinvolucrado($formData['id_involucrado'],$id_cli,$id_contrato,$formData['id_relacion']);
        }else{
            $objData=new Contratoinvolucrado($formData->id_involucrado,$id_cli,$id_contrato,$formData->id_relacion);
        }
        return $objData;
    }
    
    
    
    public function borraInvolucradosContrato($id_contrato){
        $arrayRet = array();
        $objDB = new ContratoinvolucradoPGDAO();
        $result = $objDB->deleteByContrato($id_contrato);
        return $result;
    }
}

?>