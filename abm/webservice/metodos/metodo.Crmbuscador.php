<?php


// Define arreglos a devolver
$server->wsdl->addComplexType(
'ArregloDatosFiltro', 				// Nombre
'complexType', 					// Tipo de Clase
'array', 						// Tipo de PHP
'', 							// definición del tipo secuencia(all|sequence|choice)
'SOAP-ENC:Array', 				// Restricted Base
array(),
array(
array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:DatosFiltro[]') // Atributos
),
'tns:DatosPropiedad'
);


// Define estructura interna de los array a devolver
$server->wsdl->addComplexType('DatosPropiedad', 'complexType', 'struct', 'all', '',
array(
'idcrm'			=> array('name' => 'idcrm'		,'type' => 'xsd:string'),
'crmpar'		=> array('name' => 'crmpar'		,'type' => 'xsd:string'),
'crmtxt'		=> array('name' => 'crmtxt'		,'type' => 'xsd:string'),
'adjuntos'		=> array('name' => 'adjuntos'	,'type' => 'xsd:string')
)
);

// Define metodo del Webservice Para lista por Filtro

function registraBusqueda($idcrm,$crmpar,$crmtxt,$adjuntos){
	require_once("clases/class.crmbuscadorBSN.php");
	require_once("clases/class.crmbuscador.php");
	$crm = new Crmbuscador();
	$crm->setIdcrm($idcrm);
	$crm->setCrmpar($crmpar);
	$crm->setCrmtxt($crmtxt);
	$crm->setAdjuntos($adjuntos);
	$crmBSN = new CrmbuscadorBSN($crm);
	$crmBSN->borraDB();
	$crmBSN->insertaDB();
	return "";
}

function datosPropiedadesCRM($idcrm){
	require_once("clases/class.crmbuscadorBSN.php");
	require_once("clases/class.datospropBSN.php");
	require_once("clases/class.propiedadBSN.php");
	$incarac = "42,161,164,165,166,198,208,255,257";
	
	$buscBSN= new CrmbuscadorBSN($idcrm);
	$inprop = $buscBSN->getObjeto()->getAdjuntos();
	$datosprop = new DatospropBSN();
	$colecdatos=$datosprop->coleccionCaracteristicasByGrupoProp($inprop,$incarac);
	$prop=new PropiedadBSN();
	$colecprop=$prop->cargaSeleccionCRM($inprop);

	$string=armaStringHTML($colecprop,$colecdatos);

	
	$id=$buscBSN->getObjeto()->getIdcrm();
	$crmpar = $buscBSN->getObjeto()->getCrmpar();
	$crmtxt = $buscBSN->getObjeto()->getCrmtxt();
	$xmlStr='';
	$xmlStr.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlStr.="<busqueda>\n";
	$xmlStr.="\t<idcrm>".$id."</idcrm>\n";
	$xmlStr.="\t<crmpar>".$crmpar."</crmpar>\n";
	$xmlStr.="\t<crmtxt>".$crmtxt."</crmtxt>\n";
	$xmlStr.="\t<adjuntos>".$inprop."</adjuntos>\n";
	$xmlStr.="\t<strhtm>\n".$string."\t</strhtm>\n";
	
	$xmlStr.="</busqueda>\n";
	

	return($xmlStr);
}

function listarFotosPropiedadCRM($id_prop){
	require_once("clases/class.fotoBSN.php");
	require_once("generic_class/class.cargaConfiguracion.php");
	$conf=new CargaConfiguracion();
	$path=$conf->leeParametro('path_fotos');
	$foto = new FotoBSN();
	$colec=$foto->cargaColeccionFormByPropiedad($id_prop);
	$retorno=array();
	foreach ($colec as $reg){
		$retorno[]=array(
						'id_foto'	=> $reg['id_foto'],
						'id_prop'	=> $reg['id_prop'],
						'foto'		=> $reg['hfoto'],
						'posicion'	=> $reg['posicion']
						);
	}
	return $retorno;
}

function listarTipoPropCRM(){
	require_once("clases/class.tipo_propBSN.php");
	$tpBSN = new Tipo_propBSN();
	$colec=$tpBSN->cargaColeccionForm();
	return $colec;
}

function armaStringHTML($prop,$carac){

	$search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,ñ");
	$replace = explode(",","c,ae,oe,&aacute;,&eacute;,&iacute;,&oacute;,&uacute;,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,&ntilde;");
	
	$strHTML='';

    $tipo_prop =listarTipoPropCRM();
	
	for($i=0; $i < sizeof($prop); $i++) {
		$id_prop = $prop[$i]['id_prop'];

		$fotos = listarFotosPropiedadCRM($id_prop);

		for($j = 0; $j < count($carac); $j++) {
			if($carac[$j]['id_prop'] == $prop[$i]['id_prop']) {
				if($carac[$j]['id_carac'] == 42) {
					if(is_null($carac[$j]['contenido'])) {
						$estado = "Sin definir";
					}else {
						$estado = $carac[$j]['contenido'];
					}
				}
				if($prop[$i]['operacion'] =="Venta") {
					if($carac[$j]['id_carac'] == 165) {
						if(is_null($carac[$j]['contenido']) || $carac[$j]['contenido'] == "Sin definir") {
							$moneda = "";
						}else {
							$moneda = $carac[$j]['contenido'];
						}
					}
					if($carac[$j]['id_carac'] == 161) {
						if(is_null($carac[$j]['contenido']) || $carac[$j]['contenido'] == 0) {
							$valor = "Consulte";
						}else {
							$valor = $carac[$j]['contenido'];
						}
					}
				}else {
					if($carac[$j]['id_carac'] == 166) {
						if(is_null($carac[$j]['contenido']) || $carac[$j]['contenido'] == "Sin definir") {
							$moneda = "";
						}else {
							$moneda = $carac[$j]['contenido'];
						}
					}
					if($carac[$j]['id_carac'] == 164) {
						if(is_null($carac[$j]['contenido']) || $carac[$j]['contenido'] == 0) {
							$valor = "Consulte";
						}else {
							$valor = $carac[$j]['contenido'];
						}
					}
				}
				if($carac[$j]['id_carac'] == 198) {
					if($carac[$j]['contenido'] == "") {
						$superficie = "-";
					}else {
						$superficie = $carac[$j]['contenido'];
					}
				}
				if($carac[$j]['id_carac'] == 208) {
					if(is_null($carac[$j]['contenido'])) {
						$ambientes = "-";
					}else {
						$ambientes = $carac[$j]['contenido'];
					}
				}
				if($carac[$j]['id_carac'] == 255) {
					if($carac[$j]['contenido'] == "") {
						$desc = "";
					}else {
						$desc = $carac[$j]['contenido'];
					}
				}
				if($carac[$j]['id_carac'] == 257) {
					if($carac[$j]['contenido'] == "") {
						$titulo = "-";
					}else {
						$titulo = $carac[$j]['contenido'];
					}
				}
			}
		}
		for($k=0;$k < count($tipo_prop);$k++) {
			if($tipo_prop[$k]['id_tipo_prop'] == $prop[$i]['id_tipo_prop']) {
				$tipo = $tipo_prop[$k]['tipo_prop'];
				break;
			}
		}
		$strHTML.="\t\t<propiedad id=\"".$id_prop."\">\n";
		$strHTML.="\t\t<![CDATA[";
		
		$strHTML.="\t\t<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		$strHTML.="\t\t<tr>";
		$strHTML.="\t\t<td style='height:152px;'><table width='100%' border='0' cellspacing='0' cellpadding='0' style='padding-bottom:4px;'>\n";
		$strHTML.="  			<tr>";
		$strHTML.="            <td width='75%' style='padding-right:1px; height:152px;' valign='top'><table width='100%' height='100%' border='0' cellspacing='0' cellpadding='0'>\n";
		$strHTML.="                    <tr>";
		$strHTML.="                        <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_tl.png' width='4' height='4' /></td>\n";
		$strHTML.="                            <td class='tabla_blco'></td>\n";
		$strHTML.="                            <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_tr.png' width='4' height='4' /></td>\n";
		$strHTML.="                        </tr>\n";
		$strHTML.="                        <tr>\n";
		$strHTML.="                            <td class='tabla_blco'></td>\n";
		$strHTML.="                            <td class='tabla_blco' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
		$strHTML.="                                    <tr><td colspan='3' style='padding:3px;' class='titulo_resumen'>". $titulo . "</td></tr>\n";
		$strHTML.="                                    <tr>\n";
		$strHTML.="                                        <td width='235' valign='top' style=' padding-right: 5px;'>\n";
		if($fotos[0]['foto'] != '') {
			$strHTML.="                                            <img src='http://abm.achavalcornejo.com/fotos_th/".$fotos[0]['foto']."' width='235' height='121' />\n";
		}else {
			$strHTML.="                                            <img src='http://abm.achavalcornejo.com/images/no_disponible.jpg' width='235' height='121' />\n";
		}
		$strHTML.="                                        </td>\n";
		$strHTML.="                                        <td width='33%' valign='top' style='background-image:url(http://www.achavalcornejonordelta.com/images/fdo_resumen.jpg); background-repeat:repeat-y; height:121px; padding-left: 10px; padding-right: 10px;'>\n";
		$strHTML.="                                            <table width='100%' border='0' cellspacing='1' cellpadding='0'>\n";
		$strHTML.="                                                <tr>\n";
		$strHTML.="                                                    <td class='blanco_resumen' align='left' height='24'>\n";
		$barrio = $prop[$i]['nombre_loca'];

		$strHTML.= $barrio;
		$strHTML.="                                                    </td>\n";
		$strHTML.="                                                    <td class='blanco_resumen' style='font-weight:bold; color:#ccdd00; text-align: right;'>".$prop[$i]['operacion']."</td>\n";
		$strHTML.="                                                </tr>\n";
		$strHTML.="                                                <tr>\n";
		$strHTML.="                                                    <td class='blanco_resumen' colspan='2'>".$tipo."</td>\n";
		$strHTML.="                                                </tr>\n";
		$strHTML.="                                                <tr>\n";
		$strHTML.="                                                    <td class='blanco_resumen' colspan='2'>Ambientes: ".$ambientes."</td>\n";
		$strHTML.="                                                </tr>\n";
		$strHTML.="                                                <tr>\n";
		$strHTML.="                                                    <td class='blanco_resumen' colspan='2'>Estado: ".$estado."</td>\n";
		$strHTML.="                                                </tr>\n";
		$strHTML.="                                                <tr>\n";
		$strHTML.="                                                    <td class='blanco_resumen' colspan='2'>Superficie: ". $superficie." m2</td>\n";
		$strHTML.="                                                </tr>\n";
		$strHTML.="                                                <tr>\n";
		$strHTML.="                                                    <td class='precio_resumen' height='24' colspan='2' align='right' valign='bottom'>". $moneda ." ";
		$strHTML.=(($valor == 'Consulte') ?  'Consulte' : number_format($valor,0,',','.'))."</td>\n";
		$strHTML.="                                                </tr>\n";
		$strHTML.="                                            </table></td>\n";
		$strHTML.="                                        <td valign='top' class='desc_resumen' style='padding-left: 5px;'>". str_replace($search, $replace, $desc) ."</td>\n";
		$strHTML.="                                    </tr>\n";
		$strHTML.="                                </table></td>\n";
		$strHTML.="                            <td class='tabla_blco'></td>\n";
		$strHTML.="                        </tr>\n";
		$strHTML.="                        <tr>\n";
		$strHTML.="                            <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_bl.png' width='4' height='4' /></td>\n";
		$strHTML.="                            <td class='tabla_blco'></td>\n";
		$strHTML.="                            <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_br.png' width='4' height='4' /></td>\n";
		$strHTML.="                        </tr>\n";
		$strHTML.="                    </table></td>\n";
		$strHTML.="                <td style='padding-left:1px;' valign='top'><table width='235' height='100%' border='0' cellspacing='0' cellpadding='0'>\n";
		$strHTML.="                        <tr>\n";
		$strHTML.="                            <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_tl.png' width='4' height='4' /></td>\n";
		$strHTML.="                            <td class='tabla_blco' width='228'></td>\n";
		$strHTML.="                            <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_tr.png' width='4' height='4' /></td>\n";
		$strHTML.="                        </tr>\n";
		$strHTML.="                        <tr>\n";
		$strHTML.="                            <td colspan='3' class='tabla_blco'><table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
		$strHTML.="                                    <tr>\n";
		$strHTML.="                                        <td style=' padding: 10px 15px 0px 15px;'><table width='100%' border='0' cellspacing='0' cellpadding='0' style=' height: 142px;'>\n";
		$strHTML.="                                                <tr>\n";
		$strHTML.="                                                    <td style=' padding-bottom: 10px;'><table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
		$strHTML.="                                                            <tr>\n";
		$strHTML.="                                                                <td align='left'><a href='detalle_prop.php?id=". $id_prop."'><img src='http://www.achavalcornejonordelta.com/images/vermas.jpg' width='72' height='23' alt='ver mas' border='0' /></a></td>\n";
		$strHTML.="                                                                <td align='right' class='titulo_resumen'>". $prop[$i]['id_sucursal'] . str_repeat('0', 5-strlen(strval($id_prop))) . $id_prop."</td>\n";
		$strHTML.="                                                            </tr>\n";
		$strHTML.="                                                        </table>\n";
		$strHTML.="                                                    </td>\n";
		$strHTML.="                                                </tr>\n";
		$strHTML.="                                            </table></td>\n";
		$strHTML.="                                    </tr>\n";
		$strHTML.="                                </table></td>\n";
		$strHTML.="                        </tr>\n";
		$strHTML.="                        <tr>\n";
		$strHTML.="                            <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_bl.png' width='4' height='4' /></td>\n";
		$strHTML.="                            <td class='tabla_blco'></td>\n";
		$strHTML.="                            <td width='4' height='4'><img src='http://www.achavalcornejonordelta.com/images/blanco_br.png' width='4' height='4' /></td>\n";
		$strHTML.="                        </tr>\n";
		$strHTML.="                    </table></td>\n";
		$strHTML.="            </tr>\n";
		$strHTML.="        </table></td>\n";
		$strHTML.="\t\t</tr>\n";
		$strHTML.="  </table>\n";
		$strHTML.="\t\t]]>\n";
		$strHTML.="\t\t</propiedad>\n";

	}
	return $strHTML;
}

function datosBusquedaCrm($idcrm){
	require_once("clases/class.crmbuscadorBSN.php");
	
	$buscBSN= new CrmbuscadorBSN($idcrm);
	$id=$buscBSN->getObjeto()->getIdcrm();
	$adjuntos = $buscBSN->getObjeto()->getAdjuntos();
	$crmpar = $buscBSN->getObjeto()->getCrmpar();
	$crmtxt = $buscBSN->getObjeto()->getCrmtxt();
	$xmlStr='';
	$xmlStr.='<?xml version="1.0" encoding="UTF-8"?>\n';
	$xmlStr.='\t<busqueda>\n';
	$xmlStr.='\t  <idcrm>'.$id.'</idcrm>\n';
	$xmlStr.='\t  <crmpar>'.$crmpar.'</crmpar>\n';
	$xmlStr.='\t  <crmtxt>'.$crmtxt.'</crmtxt>\n';
	$xmlStr.='\t  <adjuntos>'.$adjuntos.'</adjuntos>\n';
	$xmlStr.='\t<busqueda>\n';
	
    return new soapval('return', 'xsd:string', $xmlStr);
	
}

$server->register('datosBusquedaCrm', // Nombre de la funcion
                   array('idcrm'=> 'xsd:string'), // Parametros de entrada
                   array('return' => 'xsd:string') // Parametros de salida
);

$server->register(
'datosPropiedadesCRM',
array('idcrm'=> 'xsd:string'),           // Parametros de Entrada
array('return' => 'xsd:string')   //Datos de Salida
);

$server->register(
'registraBusqueda',
array('idcrm'=> 'xsd:string','crmpar'=> 'xsd:string','crmtxt'=> 'xsd:string','adjuntos'=> 'xsd:string'),           // Parametros de Entrada
array('return' => 'xsd:string')   //Datos de Salida
);
?>
