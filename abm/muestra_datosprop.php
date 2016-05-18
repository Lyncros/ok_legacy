<?php
include_once("./generic_class/class.cargaConfiguracion.php");

$conf = CargaConfiguracion::getInstance('');
//$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey = $conf->leeParametro("gmkey");

include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedadVW.php");
include_once("clases/class.operacionBSN.php");
include_once("clases/class.tasacionBSN.php");

include_once("clases/class.datospropBSN.php");
include_once("clases/class.datospropVW.php");

//include_once("./inc/encabezado_html_gmap.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>O'Keefe Propiedades</title>
        <script LANGUAGE="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
        <link href="css/ventanas.css" rel="stylesheet" type="text/css" />
        <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey ?>" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/thickbox.css" />
        <script language="javascript" src="jquery.ui-1.5.2/jquery.js" type="text/javascript"></script>
        <script language="javascript" src="jquery.ui-1.5.2/thickbox.js" type="text/javascript"></script>
    </head>

    <body onload="load();" onunload="GUnload();" style="margin: 0px; background-color: #e1e1e1;">
        <div id="container" style="width: 100%;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFF">
                <tr>
                    <td align="center" height="65" style="padding-left:15px;"><img src="images/okeefe.png" alt="O'Keefe Propiedades" width="170" height="56" align="left" /></td>
                </tr>
            </table>
            <table width="100%" bgcolor="#e1e1e1" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="padding-top: 5px;">
                        <?php
                        $ingreso = true;
                        $id = "";
                        $modi = 0;
                        $origen = "lista_propiedad.php?i=";

                        if (isset($_GET['i'])) {
                            $id = $_GET['i'];

                            $notiVW = new PropiedadVW($id);
                            $notiVW->vistaDatosPropiedadBuscador();

                            //  $carVW= new DatospropVW();
                            //  $carVW->vistaDatosProp($id);
                        }
                        include_once("./inc/pie.php");
                        ?>
