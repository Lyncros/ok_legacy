<?php

/**
 * Clase Propia para la definicion de la logica de negocios.
 * Utiliza dos variables propias de la clase que las hereda llamadas
 * 		"clase" que define la base del nombre, debe tener la Primer letra en Mayuscula y responder a la base de los nombres
 * 					de los metodos propios.
 * 		"objeto" que define el nombre del objeto tipo de la clase, cuyo nombre debe ser igual al de la 
 * 					clase pero todo en minuscula
 * 
 * En la clase derivada se deben definir metodos que ejecuten
 * 		getId		-> Retorna el Id de la clase
 * 		getClave	-> Retorna la Clave de la clase
 * 
 *
 * Ejemplo del uso del retorno de la clase para el armado de un metodo de la clase derivada. 
 * 	public function muestra(){
 * 		$var='muestra'.$this->getClase();
 * 		$this->{$var}();
 * 	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.asunto.php");
include_once("clases/class.asuntoPGDAO.php");
require_once 'generic_class/class.Template.php';
require_once 'clases/class.propiedadBSN.php';
require_once 'clases/class.eventoBSN.php';

class AsuntoBSN extends BSN {

    protected $clase = "Asunto";
    protected $nombreId = "id_asunto";
    protected $asunto;

    public function __construct($_parametro = '') {
        $this->seteaMapa();
        if ($_parametro instanceof Asunto) {
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
        return $this->asunto->getId_asunto();
    }

    /**
     * Setea e ID del objeto
     *
     * @param unknown_type $id
     */
    public function setId($id) {
        $this->asunto->setId_asunto($id);
    }

    public function cerrarAsunto() {
        $retorno = false;
        $arrayTabla = $this->getArrayTabla();
        $astoDB = new AsuntoPGDAO($arrayTabla);
        $retorno = $astoDB->cerrarAsunto();
        return $retorno;
    }
    
    /**
     * Retorna un array bidimensional con los datos de todos los asuntos correspondientes a un cliente en particular
     * @param int $id_cli ->id del cliente del cual se pretenden ver los asuntos en curso
     */
    public function coleccionByClienteActivas($id_cli = 0) {
        return $this->coleccionByClienteEstado($id_cli, 'a');
    }

    public function coleccionByClienteCerradas($id_cli = 0) {
        return $this->coleccionByClienteEstado($id_cli, 'c');
    }

    public function coleccionByCliente($id_cli = 0) {
        return $this->coleccionByClienteEstado($id_cli, 't');
    }

    public function coleccionActivas() {
        return $this->coleccionByClienteEstado(0, 'a');
    }

    public function coleccionCerradas() {
        return $this->coleccionByClienteEstado(0, 'c');
    }

    public function coleccionTotal() {
        return $this->coleccionByClienteEstado(0, 't');
    }

    protected function coleccionByClienteEstado($id_cli, $estado) {
        $arrayRet = array();
        $objDB = new AsuntoPGDAO();
        $result = $objDB->coleccionByClienteEstado($id_cli, $estado);
        $arrayRet = $this->leeDBArray($result);
        return $arrayRet;
    }

    public function armaVistaAsuntosAbiertosCliente($id_cli){
        $strRet='<!--<div class="titulo4">Abiertas {CANTIDADABIERTAS}</div><div class="scrolable celda50_550">--><table><thead><td class="td_fecha">Fecha</td><td class="td_operacion">Operacion</td>';
        $strRet.='<td class="td_estado">Estado</td><td class="td_titulo">Titulo</td><td></td></thead><tbody>';
        $evBSN=new EventoBSN();
        $arrayDatos=$evBSN->cargaColeccionByTipoEstado('C', $id_cli, 1);
        if(sizeof($arrayDatos)>0){
            $template = new Template("listaAsuntosAbiertos.html");
            $patron=$template->getSalida();
            $propBSN=new PropiedadBSN();
            foreach ($arrayDatos as $dato){
                $template->setTemplateSalida($patron);
                $operacion='';
                $template->parsearDato('ONCLICK', '');
                if($dato['id_tipoevento']==8){
                    $javascript2='onclick="javascript: abreVistaListaProps('.$dato['id_asunto'].');"';
                    $imagen='<img src="images/eye.png">';
                }else{
                    $javascript2='';
                    $imagen='';
                }
                $template->parsearDato('IMG_BUSCAR', $imagen);
                $template->parsearDato('ONCLICK_BUSCAR', $javascript2);
                $template->parsearDato('FECHA', $dato['fecha_even']);
                if(strpos($dato['tarea'],'Informacion propiedad')!==false){
                    $id_prop=$dato['tarea'];
                    $id_prop=str_replace('Informacion propiedad', '', $id_prop);
                    $propBSN->cargaById(trim($id_prop));
                    $operacion=$propBSN->getObjeto()->getOperacion();
                }else{
                    $arrayCont=  explode(' ', $dato['tarea']);
                    foreach ($arrayCont as $cont){
                        if(strpos($cont,'Operacion:')!==false){
                            $cont=str_replace('Operacion:', '', $cont);
                            $operacion=$cont;
                            break;
                        }
                    }
                }
                $template->parsearDato('OPERACION', $operacion);
                $template->parsearDato('ESTADO', $dato['estado']);
                $template->parsearDato('TITULO', $this->rearmaTitulo($dato['tarea']));
                $strRet.=$template->getSalida();
            }
        }
        
        $strRet.='</tbody></table><!--</div>-->';
        return $strRet;
    }

    public function armaVistaAsuntosAbiertosClienteORIG($id_cli){
        $strRet='<!--<div class="titulo4">Abiertas {CANTIDADABIERTAS}</div><div class="scrolable celda50_550">--><table><thead><td class="td_fecha">Fecha</td><td class="td_operacion">Operacion</td>';
        $strRet.='<td class="td_estado">Estado</td><td class="td_titulo">Titulo</td><td></td></thead><tbody>';
        $arrayDatos=$this->coleccionByClienteActivas($id_cli);
//        print_r($arrayDatos);
        $strRet=  str_replace('{CANTIDADABIERTAS}', sizeof($arrayDatos), $strRet);
        if(sizeof($arrayDatos)>0){
            $template = new Template("listaAsuntosAbiertos.html");
            $patron=$template->getSalida();
            $propBSN=new PropiedadBSN();
            foreach ($arrayDatos as $dato){
                $template->setTemplateSalida($patron);
                $operacion='';
                $javascriptSel = 'onclick="javascript: marcaAsuntoSel('.$dato['id_asunto'].');"';
                $javascript='onclick="javascript: cargaDataAsuntos('.$dato['id_asunto'].');"';
                $javascript2='onclick="javascript: abreVistaListaProps('.$dato['id_asunto'].');"';
                $javascriptClose='onclick="javascript: asuntoSel='.$dato['id_asunto'].';"';
//                $javascriptClose='onclick="javascript: asuntoSel='.$dato['id_asunto'].';cierreAsunto.open();"';

                $template->parsearDato('ONCLICK_SEL', $javascriptSel);
                $template->parsearDato('ONCLICK', $javascript);
                $template->parsearDato('ONCLICK_BUSCAR', $javascript2);
                $template->parsearDato('ONCLICK_CLOSE', $javascriptClose);
                $template->parsearDato('FECHA', $dato['fec_inicio']);
                if(strpos($dato['titulo'],'Informacion propiedad')!==false){
                    $id_prop=$dato['titulo'];
                    $id_prop=str_replace('Informacion propiedad', '', $id_prop);
                    $propBSN->cargaById(trim($id_prop));
                    $operacion=$propBSN->getObjeto()->getOperacion();
                }else{
                    $arrayCont=  explode(' ', $dato['titulo']);
                    foreach ($arrayCont as $cont){
                        if(strpos($cont,'Operacion:')!==false){
                            $cont=str_replace('Operacion:', '', $cont);
                            $operacion=$cont;
                            break;
                        }
                    }
                }
                $template->parsearDato('OPERACION', $operacion);
                $template->parsearDato('ESTADO', $dato['estado']);
                $template->parsearDato('TITULO', $this->rearmaTitulo($dato['titulo']));
                $strRet.=$template->getSalida();
            }
        }
        
        $strRet.='</tbody></table><!--</div>-->';
        return $strRet;
    }
    
    public function armaVistaAsuntosCerradosCliente($id_cli){
//        $strRet='<div class="titulo4">Cerradas {CANTIDADCERRADAS}</div><div class="scrolable celda50_550"><table><thead><td class="td_fecha">Fecha</td><td class="td_operacion">Operacion</td>';
//        $strRet.='<td class="td_estado">Estado</td><td class="td_titulo">Titulo</td></thead><tbody>';
        $strRet='<table><tbody>';
        $arrayDatos=$this->coleccionByClienteCerradas($id_cli);
//        $strRet=  str_replace('{CANTIDADCERRADAS}', sizeof($arrayDatos), $strRet);
        if(sizeof($arrayDatos)>0){
            $template = new Template("listaAsuntosCerrados.html");
            $patron=$template->getSalida();
            $propBSN=new PropiedadBSN();
            foreach ($arrayDatos as $dato){
                $template->setTemplateSalida($patron);
                $operacion='';
//                $javascript='onclick="javascript: cargaDataAsuntos('.$dato['id_asunto'].');"';
                $template->parsearDato('ONCLICK', '');//$javascript);
                $template->parsearDato('PERIODO', $dato['fec_inicio'].' al '.$dato['fec_cierre']);
                if(strpos($dato['titulo'],'Informacion propiedad')!==false){
                    $id_prop=$dato['titulo'];
                    $id_prop=str_replace('Informacion propiedad', '', $id_prop);
                    $propBSN->cargaById(trim($id_prop));
                    $operacion=$propBSN->getObjeto()->getOperacion();
                }else{
                    $arrayCont=  explode(' ', $dato['titulo']);
                    foreach ($arrayCont as $cont){
                        if(strpos($cont,'Operacion:')!==false){
                            $cont=str_replace('Operacion:', '', $cont);
                            $operacion=$cont;
                            break;
                        }
                    }
                }
                $template->parsearDato('OPERACION', $operacion);
                $template->parsearDato('ESTADO', $dato['estado']);
                $template->parsearDato('TITULO', $this->rearmaTitulo($dato['titulo']));
                $strRet.=$template->getSalida();
            }
        }
        
        $strRet.='</tbody></table>';
//        $strRet.='</tbody></table><div>';
        return $strRet;
    }
    
    protected function rearmaTitulo($titulo){
        //Busqueda propiedades  Tipo Prop:9,1  Operacion:Venta, Alquiler  Zona:52,62,53,68  Emprendimientos:55,67,83
        $tpropBSN=new Tipo_propBSN();
        $arrayTprop=$tpropBSN->armaArrayTipoProp();
        
        $zonaBSN=new UbicacionpropiedadBSN();
        
        $empBSN=new EmprendimientoBSN();

        $titRet='';
        if(strpos($titulo, 'Busqueda')!==false){
            $arrTit=  explode('  ', $titulo);
            foreach ($arrTit as $porcion){
                $arrPor=  explode(':',$porcion);
                $ret='';
                if($arrPor[0]=='Tipo Prop'){
                    $ret='';
                    $arrCont=  explode(',', $arrPor[1]);
                    foreach ($arrCont as $cont){
                        $ret.=($arrayTprop[$cont].',');
                    }
                    $ret=  'Tipo Prop:'.substr($ret, 0,-1).' - ';
                }
                if($arrPor[0]=='Zona'){
                    $arrCont=  explode(',', $arrPor[1]);
                    $ret='';
                    foreach ($arrCont as $cont){
                        $zonaBSN->armaNombreZonaAbr($cont);
                        $ret.=($zonaBSN->armaNombreZonaAbr($cont).',');
                    }
                    $ret=  'Zona:'.substr($ret, 0,-1).' - ';
                    
                }
                if($arrPor[0]=='Emprendimientos'){
                    $arrCont=  explode(',', $arrPor[1]);
                    $ret='';
                    foreach ($arrCont as $cont){
                        $empBSN->cargaById($cont);
                        $ret.=($empBSN->getObjeto()->getNombre().',');
                    }
                    $ret=  'Emprendimientos:'.substr($ret, 0,-1).' - ';
                    
                }
                if($arrPor[0]=='Operacion'){
                    $ret=$arrPor[0].':'.$arrPor[1].' - ';
                }
                $titRet.=$ret;
            }
            $titRet=  substr($titRet, 0,-2);
        }else{
            $titRet=$titulo;
        }
        return $titRet;
    }
    
    
}

?>
