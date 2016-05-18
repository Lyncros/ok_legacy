<?php
header('Content-type: text/html; charset=utf-8');
include_once("./generic_class/class.cargaConfiguracion.php");

$conf = CargaConfiguracion::getInstance('');
//$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey = $conf->leeParametro("gmkey");

require_once('webservice/lib-nusoap/nusoap.php');


$wsdl = "http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client = new nusoap_client($wsdl, 'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice
//¿ocurrio error al llamar al web service?
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
} else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}

$id_prop = $_GET['id'];

$param = array('id_prop' => $id_prop);
$prop = $client->call('Propiedad', $param, '');
//print_r($prop);

$param = array('id_zona' => $prop['id_zona']);
$loca = $client->call('ListarLocalidad', $param, '');
/* 	for($i=0;$i<count($loca);$i++){
  echo $loca[$i]['id_zona'].' - '.$loca[$i]['id_loca'].' - '.$loca[$i]['nombre_loca'].'<br>';
  }
 */
//print_r($loca);



$param = array('id_prop' => $id_prop);
$carac = $client->call('ListarDatosPropiedad', $param, '');
//print_r($carac);

$param = array('id_prop' => $id_prop);
$fotos = $client->call('ListarFotosPropiedad', $param, '');

function busca_valor($id_carac, $arreglo) {
    for ($j = 0; $j < count($arreglo); $j++) {
        if ($arreglo[$j]['id_carac'] == $id_carac) {
            if ($arreglo[$j]['contenido'] == "") {
                $valor = "-";
            } else {
//$valor = $arreglo[$j]['contenido'];
                if (is_numeric($arreglo[$j]['contenido'])) {
                    $valor = number_format($arreglo[$j]['contenido'], 0, ",", ".");
                } else {
                    if ($carac[$j]['contenido'] == "on") {
                        $valor = "<img src=\"images/tilde.png\" width=\"14\" heigth=\"15\" border=\"0\">";
                    } else {
                        $valor = $arreglo[$j]['contenido'];
                    }
                }
            }
            return $valor;
            break;
        }
    }
}

function formatValor($val) {
    if (is_numeric($val)) {
        $valor = number_format($val, 2, ",", ".");
    } else {
        if ($val == "on") {
            $valor = "<img src=\"images/tilde.png\" width=\"14\" heigth=\"15\" border=\"0\">";
        } else {
            $valor = $val;
        }
    }
    return $valor;
    break;
}

//ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="Scripts/AC_RunActiveContent.js" language="javascript"></script>
        <title>O'Keefe Propiedades</title>
        <script LANGUAGE="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
        <link href="css/ventanas.css" rel="stylesheet" type="text/css" />
        <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey ?>" type="text/javascript"></script>
    </head>

</head>
<body onload="load();" onunload="GUnload(); window.print();" style="background-color: #f5f4ee;"><div id="cabeza" style="padding-top:3px; padding-bottom: 3px;">
        <table width="945" height="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="background-color: #fff;" >
            <tr>
                <td width="180"><img name="achaval" src="images/okeefe.png" width="180" height="35" border="0" id="achaval" alt="" hspace="5" /></td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
    <table width="945" height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td valign="top"><table width="100%" height="426" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="470" style="padding-right:1px;" valign="top"><table width="470" height="426" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="4" height="4"><img src="images/blanco_tl.png" width="4" height="4" /></td>
                                    <td class="tabla_blco" width="460"></td>
                                    <td width="4" height="4"><img src="images/blanco_tr.png" width="4" height="4" /></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="tabla_blco"><table width="100%" height="426" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td height="63" style="font-size:12pt; color:#646419; font-weight:bold; padding-left:15px;"><?php echo busca_valor(257, $carac); ?></td>
                                            </tr>
                                            <tr>
                                                <td height="363" valign="top"><img src="fotos/<?php echo $fotos[0]['foto']; ?>" width="470" height="300" border="0" /></td>
                                            </tr>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td width="4" height="4"><img src="images/blanco_bl.png" width="4" height="4" /></td>
                                    <td class="tabla_blco" width="460"></td>
                                    <td width="4" height="4"><img src="images/blanco_br.png" width="4" height="4" /></td>
                                </tr>
                            </table></td>
                        <td style="padding-left:1px;" valign="top"><table width="100%" height="426" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="4" height="4"><img src="images/blanco_tl.png" width="4" height="4" /></td>
                                    <td class="tabla_blco"></td>
                                    <td width="4" height="4"><img src="images/blanco_tr.png" width="4" height="4" /></td>
                                </tr>
                                <tr>
                                    <td class="tabla_blco"></td>
                                    <td class="tabla_blco" valign="top" align="center"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td height="63"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="font-size:12pt; color:#646419; font-weight:bold;"><?php
foreach ($loca as $assoc_array) {
    if ($assoc_array['id_loca'] == $prop['id_loca']) {
        $barrio = $assoc_array['nombre_loca'];
        break;
    }
}
echo $barrio;
?> - <?php echo $prop['operacion']; ?></td>
                                                            <td style="font-size:12pt; color:#646419;" align="right"><?php echo $prop['id_sucursal'] . str_repeat("0", 5 - strlen(strval($id_prop))) . $id_prop; ?></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                            <tr>
                                                <td valign="top" height="283"><table width="100%" height="300" border="0" cellspacing="0" cellpadding="0" bgcolor="#f5f4ee">
                                                        <tr>
                                                            <td style="padding-left:15px; padding-right:15px; padding-top:15px;" valign="top" height="98"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td style="padding-right:1px;" width="50%"><table width="100%" height="68" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td width="4" height="4"><img src="images/blanco_tl.png" width="4" height="4" /></td>
                                                                                    <td class="tabla_blco"></td>
                                                                                    <td width="4" height="4"><img src="images/blanco_tr.png" width="4" height="4" /></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="tabla_blco"></td>
                                                                                    <td class="tabla_blco" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                            <tr>
                                                                                                <td class="desc_resumen" style="padding-top:10px; padding-left:10px;">Precio</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td class="precio_ficha" style="padding-left:10px;">
                                                                                                    <?php
                                                                                                    switch ($prop['operacion']) {
                                                                                                        case "Alquiler":
                                                                                                            $moneda = busca_valor(166, $carac);
                                                                                                            $valor = busca_valor(164, $carac);
                                                                                                            break;
                                                                                                        case "Venta":
                                                                                                            $moneda = busca_valor(165, $carac);
                                                                                                            $valor = busca_valor(161, $carac);
                                                                                                            break;
                                                                                                        default:
                                                                                                            $moneda = "";
                                                                                                            $valor = "Consultar";
                                                                                                            break;
                                                                                                    }
                                                                                                    echo $moneda . " " . $valor;
                                                                                                    ?></td>
                                                                                            </tr>
                                                                                        </table></td>
                                                                                    <td class="tabla_blco"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="4" height="4"><img src="images/blanco_bl.png" width="4" height="4" /></td>
                                                                                    <td class="tabla_blco"></td>
                                                                                    <td width="4" height="4"><img src="images/blanco_br.png" width="4" height="4" /></td>
                                                                                </tr>
                                                                            </table></td>
                                                                        <td style="padding-left:1px;"><table width="100%" height="68" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td width="4" height="4"><img src="images/blanco_tl.png" width="4" height="4" /></td>
                                                                                    <td class="tabla_blco"></td>
                                                                                    <td width="4" height="4"><img src="images/blanco_tr.png" width="4" height="4" /></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="tabla_blco"></td>
                                                                                    <td class="tabla_blco" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                            <tr>
                                                                                                <td class="desc_resumen" style="padding-top:10px; padding-left:10px;">Superficie</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td class="superficie_ficha" style="padding-left:10px;"><?php echo intval(busca_valor(198, $carac)); ?> m2</td>
                                                                                            </tr>
                                                                                        </table></td>
                                                                                    <td class="tabla_blco"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="4" height="4"><img src="images/blanco_bl.png" width="4" height="4" /></td>
                                                                                    <td class="tabla_blco"></td>
                                                                                    <td width="4" height="4"><img src="images/blanco_br.png" width="4" height="4" /></td>
                                                                                </tr>
                                                                            </table></td>
                                                                    </tr>
                                                                </table></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-left:15px; padding-right:15px;" valign="top"><span class="datos_resumen">Descripción:</span><br />
                                                                <span class="desc_resumen"><?php echo busca_valor(255, $carac); ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td height="1" style="padding-left:15px; padding-right:15px;"><hr /></td>
                                                        </tr>
                                                        <tr>
                                                            <td height="50"></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                            <tr>
                                                <td height="63"></td>
                                            </tr>
                                        </table></td>
                                    <td class="tabla_blco"></td>
                                </tr>
                                <tr>
                                    <td width="4" height="4"><img src="images/blanco_bl.png" width="4" height="4" /></td>
                                    <td class="tabla_blco"></td>
                                    <td width="4" height="4"><img src="images/blanco_br.png" width="4" height="4" /></td>
                                </tr>
                            </table></td>
                    </tr>
                </table></td>
        </tr>
        <tr>
            <td style="padding-top:4px; padding-bottom:4px;"><table width="100%" height="54" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="4" height="4"><img src="images/blanco_tl.png" width="4" height="4" /></td>
                        <td class="tabla_blco"></td>
                        <td width="4" height="4"><img src="images/blanco_tr.png" width="4" height="4" /></td>
                    </tr>
                    <tr>
                        <td class="tabla_blco"></td>
                        <td class="tabla_blco"><table width="100%" border="0" cellspacing="0" cellpadding="15">
                                <tr>
                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="50%" style="padding-right:20px;" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                                        <tr>
                                                            <td style="padding-bottom:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td width="20" align="left" valign="top"><img src="images/flechita.gif" width="16" height="16" /></td>
                                                                        <td class="datos_resumen" style="padding-bottom:15px;">Detalles de la Propiedad</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>&nbsp;</td>
                                                                        <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 5) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']); ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                                </table></td>
                                                                                        </tr>
                                                                                    </table></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding-bottom:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td width="20" align="left" valign="top"><img src="images/flechita.gif" width="16" height="16" /></td>
                                                                                            <td class="datos_resumen" style="padding-bottom:15px;">Información complementaria</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>&nbsp;</td>
                                                                                            <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 7) {
                                                                                                            if ($carac[$j]['contenido'] != "" || $carac[$j]['contenido'] != "off") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']); ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 8) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']) ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 9) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']) ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 10) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']); ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 11) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']); ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                                </table></td>
                                                                                        </tr>
                                                                                    </table></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding-bottom:30px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td width="20" align="left" valign="top"><img src="images/flechita.gif" width="16" height="16" /></td>
                                                                                            <td class="datos_resumen" style="padding-bottom:15px;">Caracteristicas del edificio</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>&nbsp;</td>
                                                                                            <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 12) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']); ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 13) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']); ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                <?php
                                                                                                    for ($j = 0; $j < count($carac); $j++) {
                                                                                                        if ($carac[$j]['id_prop_carac'] == 14) {
                                                                                                            if ($carac[$j]['contenido'] != "") {
                                                                                ?>
                                                                                                                <tr class="tabla_ficha">
                                                                                                                    <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                                                                                            <tr class="tabla_ficha">
                                                                                                                                <td class="detalle_ficha" width="65%"><?php echo $carac[$j]['titulo']; ?></td>
                                                                                                                                <td class="data_ficha"><?php echo formatValor($carac[$j]['contenido']); ?></td>
                                                                                                                            </tr>
                                                                                                                        </table></td>
                                                                                                                </tr>
                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                ?>
                                                                                                </table></td>
                                                                                        </tr>
                                                                                    </table></td>
                                                                            </tr>
                                                                        </table></td>
                                                                    <td valign="top" style="padding-left:20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td width="20" align="left" valign="top"><img src="images/flechita.gif" width="16" height="16" border="0" style="vertical-align:middle;" /></td>
                                                                                <td class="datos_resumen" style="padding-bottom:15px;">Ubicación</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" valign="top" colspan="2"><script type="text/javascript" language="JavaScript">
                                                                                    var map = null;
                                                                                    var geocoder = null;
                                                                                    var contextmenu;
                                                                                    function load() {
                                                                                        if (GBrowserIsCompatible()) {
                                                                                            var point;
                                                                                            map=new GMap2(document.getElementById("map"), { size: new GSize(425,425) });
                                                                                            map.setUIToDefault();
                                                                                            createContextMenu(map);
                                                                                            var address='';

                                                                                            point = new GLatLng(<?php echo $prop['goglat'] . "," . $prop['goglong']; ?>);

                                                                                            var marker = new GMarker(point);
                                                                                            map.setCenter(point,17);
                                                                                            map.addOverlay(marker);
                                                                                            map.setMapType(G_HYBRID_MAP);

                                                                                        }
                                                                                    }

                                                                                    function createContextMenu(map) {
                                                                                        contextmenu = document.createElement("div");
                                                                                        contextmenu.style.visibility="hidden";
                                                                                        contextmenu.style.background="#ffffff";
                                                                                        contextmenu.style.border="1px solid #8888FF";

                                                                                        contextmenu.innerHTML = '<a href="javascript:zoomIn()"><div class="context">&nbsp;&nbsp;Zoom in&nbsp;&nbsp;</div></a>'
                                                                                            + '<a href="javascript:zoomOut()"><div class="context">&nbsp;&nbsp;Zoom out&nbsp;&nbsp;</div></a>'
                                                                                            + '<a href="javascript:zoomInHere()"><div class="context">&nbsp;&nbsp;Zoom in here&nbsp;&nbsp;</div></a>'
                                                                                            + '<a href="javascript:zoomOutHere()"><div class="context">&nbsp;&nbsp;Zoom out here&nbsp;&nbsp;</div></a>'
                                                                                            + '<a href="javascript:centreMapHere()"><div class="context">&nbsp;&nbsp;Centre map here&nbsp;&nbsp;</div></a>';

                                                                                        map.getContainer().appendChild(contextmenu);
                                                                                        GEvent.addListener(map,"singlerightclick",function(pixel,tile) {
                                                                                            clickedPixel = pixel;
                                                                                            var x=pixel.x;
                                                                                            var y=pixel.y;
                                                                                            if (x > map.getSize().width - 120)
                                                                                            {
                                                                                                x = map.getSize().width - 120
                                                                                            }
                                                                                            if (y > map.getSize().height - 100)
                                                                                            {
                                                                                                y = map.getSize().height - 100
                                                                                            }
                                                                                            var pos = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(x,y));
                                                                                            pos.apply(contextmenu);
                                                                                            contextmenu.style.visibility = "visible";
                                                                                        });
                                                                                        GEvent.addListener(map, "click", function() {
                                                                                            contextmenu.style.visibility="hidden";
                                                                                        });
                                                                                    }
                                                                                    function zoomIn() {
                                                                                        map.zoomIn();
                                                                                        contextmenu.style.visibility="hidden";
                                                                                    }
                                                                                    function zoomOut() {
                                                                                        map.zoomOut();
                                                                                        contextmenu.style.visibility="hidden";
                                                                                    }
                                                                                    function zoomInHere() {
                                                                                        var point = map.fromContainerPixelToLatLng(clickedPixel)
                                                                                        map.zoomIn(point,true);
                                                                                        contextmenu.style.visibility="hidden";
                                                                                    }
                                                                                    function zoomOutHere() {
                                                                                        var point = map.fromContainerPixelToLatLng(clickedPixel)
                                                                                        map.setCenter(point,map.getZoom()-1);
                                                                                        contextmenu.style.visibility="hidden";
                                                                                    }
                                                                                    function centreMapHere() {
                                                                                        var point = map.fromContainerPixelToLatLng(clickedPixel)
                                                                                        map.setCenter(point);
                                                                                        contextmenu.style.visibility="hidden";
                                                                                    }
                                                                                    </script>
                                                                                    <div id="map" style="width: 425px; height: 425px; border: solid thick #CCC;"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" height="20"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20" align="left" valign="top"><img src="images/flechita.gif" width="16" height="16" /></td>
                                                                                <td class="datos_resumen" style="padding-bottom:15px;">Planos</td>
                                                                            </tr>
                                                        <?php
                                                                                                    for ($i = 1; $i <= 3; $i++) {
                                                                                                        if ($prop['plano' . $i] != "") {
                                                                                                            print "    <tr>\n";
                                                                                                            print "         <td colspan=\"2\"><img src=\"propiedades/fotos/" . $prop['plano' . $i] . "\" border=\"0\" width='425'>\n";
                                                                                                            print "         </td>\n";
                                                                                                            print "    </tr>\n";
                                                                                                        }
                                                                                                    }
                                                        ?>
                                                                                                </table>
                                                                                                <table width="100%" border="0" cellspacing="1" cellpadding="0">
                                                                                                    <tr>
                                                                                                        <td><table width="420" border="0" cellspacing="0" cellpadding="0">
                                                                                                                <tr>
                                                                                                                    <td width="20" align="left" valign="top">&nbsp;</td>
                                                                                                                    <td class="datos_resumen" style="padding-bottom:15px;">&nbsp;</td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>&nbsp;</td>
                                                                                                                    <td valign="top" class="data_ficha"><?php echo nl2br(busca_valor(256, $carac)); ?></td>
                                                                                                                </tr>
                                                                                                            </table></td>
                                                                                                    </tr>
                                                                                                </table></td>
                                                                                        </tr>
                                                                                    </table></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td width="50%" style="padding-right:20px;"></td>
                                                                                            <td></td>
                                                                                        </tr>
                                                                                    </table></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td valign="top" class="legales_ficha">Toda la información comercial, descripción,  precios, planos, imágenes, medidas y superficies que se proporcionan en esta web se basa en información que consideramos fiable y que es proporcionada por terceros. No podemos asegurar que sea exacta ni completa, representa material preliminar al solo efecto informativo e ilustrativo de las características del inmueble, pudiendo estar sujeta a errores, omisiones y cambios, incluyendo el precio o la retirada de oferta sin previo aviso.<br />
                                                                                                Recomendamos que el interesado consulte con sus profesionales las medidas, superficies que surjen de la documentación final. </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table></td>
                                                                    <td class="tabla_blco"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><img src="images/blanco_bl.png" width="4" height="4" /></td>
                                                                    <td class="tabla_blco"></td>
                                                                    <td><img src="images/blanco_br.png" width="4" height="4" /></td>
                                                                </tr>
                                                            </table></td>
                                                    </tr>
                                                </table>
                                            </body>
                                            </html>
<?php
//ob_flush();
?>