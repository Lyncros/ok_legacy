<?php

include_once("inc/encabezado.php");
include_once("clases/class.clienteVW.php");

include_once("generic_class/class.cargaConfiguracion.php");
header('Content-Type: text/html; charset=utf-8');
$conf = CargaConfiguracion::getInstance();
$anchoPagina = $conf->leeParametro("ancho_pagina");

$timezone = $conf->leeParametro('timezone');
date_default_timezone_set($timezone);
$vista = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>O'Keefe Propiedades en ABM</title>
        <script language="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/menuPullDown.js"></script>


        <link rel="stylesheet" type="text/css" href="css/agenda.css" />
        <link rel="stylesheet" type="text/css" href="css/menuPullDown.css" />
        <link rel="stylesheet" type="text/css" href="css/vistaTablas.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" media="screen" />

        <link rel="stylesheet" href="jquery1.9/themes/base/jquery.ui.all.css">
            <link rel="stylesheet" href="jquery1.9/css/ui-lightness/jquey.ui.datepicker.css">

                <script type="text/javascript" src="jquery1.9/js/jquery-1.8.2.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.core.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.widget.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.position.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.menu.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.autocomplete.js"></script>
                <script src="jquery1.9/ui/jquery.ui.datepicker.js"></script>
                <script src="jquery1.9/ui/i18n/jquery.ui.datepicker-es.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.tabs.js"></script>

                <style>
                    .ui-autocomplete {
                        max-height: 100px;
                        overflow-y: auto;
                        /* prevent horizontal scrollbar */
                        overflow-x: hidden;
                    }
                    /* IE 6 doesn't support max-height
                     * we use height instead, but this forces the menu to always be this tall
                    */
                    * html .ui-autocomplete {
                        height: 100px;
                    }
                </style>

                <script language="javascript" type="text/javascript">
                    $(function() {
                        $( "#tabs" ).tabs({
                            ajaxOptions: {
                                error: function( xhr, status, index, anchor ) {
                                    $( anchor.hash ).html(
                                    "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                                        "If this wouldn't be a demo." );
                                }
                            }
                        });
                    });

                    $(function() {
                        $( "#valorFiltro" ).autocomplete({
                            source: "autocompletarClientes.php",
                            minLength: 2,
                            select: function( event, ui ) {
                                document.getElementById('auxFiltro').value=ui.item.id;
                                filtro(2);
                                muestraDatos(ui.item.id);
                            }
                        });
                    });
                </script>

                </head>

                <body onload="load();" onunload="GUnload();" >

                    <div id="container">
                        <div style='width:1000px; height:70px;'>
                            <div  id='Encabezado' style='border: 0px; position: relative; left: 0px; right: 0px;'><img src="images/okeefe.png" alt="O'Keefe Propiedades" width="170" height="56" align="middle" /></div>
                            <div id="menuEncabezado"><a href="javascript:ventana('rssNovedades.php', 'Novedades', 400, 400);">Novedades</a></div>
                            <div style="clear: both;"></div>
                        </div>
                        <div id="divMenu" width="<?php echo $anchoPagina; ?>px">
                            <?php
                            if ($vista == 0) {
                                $menu = new Menu();
                                $menu->dibujaMenu();
                            }
                            ?>
                        </div>
                        <div id="divCuerpo">

                            <?php
                            $postreVW = new ClienteVW();
                            $postreVW->vistaTablaVW();

                            include_once("inc/pie.php");
                            ?>
