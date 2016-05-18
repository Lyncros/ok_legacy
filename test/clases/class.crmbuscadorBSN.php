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
 *		$var='muestra'.$this->getClase();
 *		$this->{$var}();
 *	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.crmbuscador.php");
include_once("clases/class.crmbuscadorPGDAO.php");
require_once 'clases/class.propiedadBSN.php';
require_once 'generic_class/class.Template.php';

class CrmbuscadorBSN extends BSN {
	
	protected $clase = "Crmbuscador";
	protected $nombreId = "idcrm";
	protected $crmbuscador;

	public function __construct($_idcrm=0){
        $this->seteaMapa();
		if ($_idcrm  instanceof Crmbuscador  ){
            $this->creaObjeto();
            $this->seteaBSN($_idcrm);
		} else {
//			if (is_string($_idcrm)){
            if (is_numeric($_idcrm)) {
                $this->creaObjeto();
				if($_idcrm!=''){
                    $this->cargaById($_idcrm);
				}
			}
		}	
	}
	
/**
 * retorna el ID del objeto 
 *
 * @return id del objeto
 */
	public function getId(){
		return $this->crmbuscador->getIdcrm();
	}
	
/**
 * Setea el ID del objeto
 *
 * @param entero $id del objeto
 */
	public function setId($id){
		$this->crmbuscador->setIdcrm($id);
	}

    public function actualizaEstadoAdjunto($id_prop, $estado) {
        $aux_adjuntos = $this->getObjeto()->getAdjuntos();
        $sel = split(',', $aux_adjuntos);
        $arryRes = array();
        foreach ($sel as $elemAdj) {
            $elemPuros = split(':', $elemAdj);
            if (sizeof($elemPuros) > 1 && trim($elemPuros[0]) == $id_prop) {
                $elemPuros[1] = $estado;
                $arryRes[] = $id_prop . ":" . $estado;
            } else {
                $arryRes[] = $elemAdj;
}
        }
        $this->getObjeto()->setAdjuntos(implode(',', $arryRes));
        $retorno=$this->actualizaDB();
        if($retorno){
            $retorno= array('codRet'=>0,'msg'=>'Actualizacion de estado OK');//}";
        }else{
            $retorno= array('codRet'=>1,'msg'=>'Fallo Actualizacion de estado');//}";
        }
        echo json_encode($retorno);
    }

    public function armaVistaPropiedadesAbiertasByAsunto($id_asunto) {
        $strRet = '<div id="div_telefono" class="celda celda_50"><label for="nombre" class="datoResaltado">Producto</label><br />';
        $strRet.='<div class="scrolable celda50_550"><table width="400px;" border="0" cellspacing="0" cellpadding="0">';
        $arrProp = $this->getAdjuntosActivos();
        if (sizeof($arrProp) > 0) {
            $propBSN = new PropiedadBSN();
            $template = new Template("listaPropiedadesAbiertas.html");
            $patron = $template->getSalida();
            foreach ($arrProp as $prop) {
                $template->setTemplateSalida($patron);
                $propBSN->cargaById($prop[0]);
                
                $javascriptSel = 'onclick="javascript: marcaPropiedadSel(' .$prop[0] . ');"';
                $template->parsearDato('ONCLICK_SEL', $javascriptSel);

                $javascript = 'onclick="javascript: cargaDataPropiedad(' . $id_asunto . ',' . $prop[0] . ');"';
                $template->parsearDato('ONCLICK', $javascript);

                $javascriptClose = 'onclick="javascript: actualizarEstadoPropiedad(' . $id_asunto . ',' . $prop[0] . ','.($prop[1]+10).');"';
                $template->parsearDato('ONCLICK_CLOSE', $javascriptClose);

//                $template->parsearDato('CERRAR', '<img src="images/lock.png" width="20px;" />');
                
                $template->parsearDato('PROPIEDAD', $propBSN->buscaDescripcionPropiedadCorto());
                $template->parsearDato('TIPO_PROP', $propBSN->buscaDetallePropiedad(0));
                
                $strRet.=$template->getSalida();
            }
        }
        $strRet.='</table></div></div>';
        return $strRet;
    }

    public function armaVistaPropiedadesCerradasByAsunto($id_asunto) {
        $strRet = '<div id="div_telefono" class="celda celda_50"><label for="nombre" class="datoResaltado">Producto Cerrados</label><br />';
        $strRet.='<div class="scrolable celda50_550"><table width="400px;" border="0" cellspacing="0" cellpadding="0">';
        $arrProp = $this->getAdjuntosCerrados();
        if (sizeof($arrProp) > 0) {
            $propBSN = new PropiedadBSN();
            $template = new Template("listaPropiedadesAbiertas.html");
            $patron = $template->getSalida();
            foreach ($arrProp as $prop) {
                $template->setTemplateSalida($patron);
                $propBSN->cargaById($prop[0]);
                $javascript = 'onclick="javascript: cargaDataPropiedad(' . $id_asunto . ',' . $prop[0] . ');"';
                $template->parsearDato('ONCLICK', $javascript);

                $javascriptClose = 'onclick="javascript: actualizarEstadoPropiedad(' . $id_asunto . ',' . $prop[0] . ','.  substr(trim($prop[1]),-1).');"';
                $template->parsearDato('ONCLICK_CLOSE', $javascriptClose);

//                $template->parsearDato('CLOSE', '<img src="images/lock_open.png" width="20px;" />');
                
                $template->parsearDato('PROPIEDAD', $propBSN->buscaDescripcionPropiedadCorto());
                $template->parsearDato('TIPO_PROP', $propBSN->buscaDetallePropiedad(0));
                $strRet.=$template->getSalida();
            }
        }
        $strRet.='</table></div></div>';
        return $strRet;
    }

    public function getAdjuntosActivos() {
        $aux_adjuntos = $this->getObjeto()->getAdjuntos();
        $sel = split(',', $aux_adjuntos);
        $arryRes = array();
        foreach ($sel as $elemAdj) {
            $elemPuros = split(':', $elemAdj);
            if (sizeof($elemPuros) > 1 && trim($elemPuros[1]) < 10) { // menores que diez => activos
                $arryRes[] = array($elemPuros[0],$elemPuros[1]);
            }
        }
        return $arryRes;
    }

    public function getAdjuntosCerrados() {
        $aux_adjuntos = $this->getObjeto()->getAdjuntos();
        $sel = split(',', $aux_adjuntos);
        $arryRes = array();
        foreach ($sel as $elemAdj) {
            $elemPuros = split(':', $elemAdj);
            if (sizeof($elemPuros) > 1 && trim($elemPuros[1]) > 10) { // menores que diez => activos
                $arryRes[] = array($elemPuros[0],$elemPuros[1]);
            }
        }
        return $arryRes;
    }
    
    public function getArrayParametros(){
        $arrayRet=array();
        $arrayElems=explode("|",$this->getObjeto()->getCrmpar());
        foreach ($arrayElems as $elem){
            $dato=explode("->",$elem);
            if(sizeof($dato)>1){
                $arrayRet[trim($dato[0])]=trim($dato[1]);
            }else{
                $arrayRet[trim($dato[0])]='';
            }
        }
        return $arrayRet;
    }

    public function getArrayAdjuntos(){
        $arrayRet=array();
        $arrayElems=explode(",",$this->getObjeto()->getAdjuntos());
        foreach ($arrayElems as $elem){
            if(trim($elem!='')){
                $dato=explode(":",$elem);
                $arrayRet[trim($dato[0])]=trim($dato[1]);
            }
        }
        return $arrayRet;
    }
    
    
}

?>