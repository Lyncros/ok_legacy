<?php
include_once("inc/encabezado.php");
include_once("./generic_class/class.cargaConfiguracion.php");
header('Content-Type: text/html; charset=utf-8');
$conf=new CargaConfiguracion();
$anchoPagina=$conf->leeParametro("ancho_pagina");

include_once("clases/class.datospropBSN.php");
include_once("clases/class.datospropVW.php");
//include_once("./inc/encabezado_html.php");
include_once("clases/class.propiedadVW.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Achaval Cornejo Propiedades</title>
        <script LANGUAGE="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
        <link href="css/agenda.css" rel="stylesheet" type="text/css" />
        <link href="jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<!--        <script src="jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script> -->
        <script src="jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
        <link type="text/css" href="jquery1.8/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="jquery1.8/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="jquery1.8/js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript">
            $(function(){

                // Tabs
                $('#tabs').tabs();

                //hover states on the static widgets
                $('#dialog_link, ul#icons li').hover(
                function() { $(this).addClass('ui-state-hover'); },
                function() { $(this).removeClass('ui-state-hover'); }
            );

            });

            function ShowHide(prefD,prefF,elem) {
                id=prefD+elem;
                idf=prefF+elem;
                if(document.getElementById(id).style["display"] != "none"){
                    document.getElementById(id).style["display"] = "none";
                    document.getElementById(id).style.paddingBottom = "0px";
                    document.getElementById(idf).src="images/down.png";
                } else {
                    document.getElementById(id).style["display"] = "block";
                    document.getElementById(id).style.paddingBottom = "20px";
                    document.getElementById(idf).src="images/up.png";
                }
            }
            function ShowAll(prefD,prefF,elemcant) {
                cant=document.getElementById(elemcant).value;
                for(x=0;x<=cant;x++){
                    id=prefD+x;
                    idf=prefF+x;
                    document.getElementById(id).style["display"] = "block";
                    document.getElementById(id).style.paddingBottom = "20px";
                    document.getElementById(idf).src="images/up.png";
                }
            }
            function HideAll(prefD,prefF,elemcant) {
                cant=document.getElementById(elemcant).value;
                for(x=0;x<=cant;x++){
                    id=prefD+x;
                    idf=prefF+x;
                    document.getElementById(id).style["display"] = "none";
                    document.getElementById(id).style.paddingBottom = "0px";
                    document.getElementById(idf).src="images/down.png";
                }
            }

            function preloadImages() { //v3.0
                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=preloadImages.arguments; for(i=0; i<a.length; i++)
                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                }

        </script>
    </head>
    <body onLoad="preloadImages('images/up.png','images/down.png')">
        <div id="container">
            <table width="<?php echo $anchoPagina; ?>" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left" height="80"><img src="images/achaval.gif" alt="Achaval Cornejo Propiedades" width="223" height="43" align="middle" /></td>
                </tr>
            </table>
            <table width="980" align="center" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <div class="demo">

                            <div id="tabs">
                                <ul>
                                    <li><a href="#tabs-1">Propiedades</a></li>
                                    <li><a href="#tabs-2" onclick='javascript:muestraDatos("aux_id_prop","D","tabs-2")'>Datos</a></li>
                                    <li><a href="#tabs-3" onclick='javascript:muestraDatos("aux_id_prop","C","tabs-3")'>Caracteristicas</a></li>
                                    <li><a href="#tabs-4" onclick='javascript:muestraDatos("aux_id_prop","P","tabs-4")'>Planos</a></li>
                                    <li><a href="#tabs-5" onclick='javascript:muestraDatos("aux_id_prop","F","tabs-5")'>Fotos</a></li>
                                    <li><a href="#tabs-6" onclick='javascript:muestraDatos("aux_id_prop","M","tabs-6")'>Mapa</a></li>
                                    <li><a href="#tabs-7" onclick='javascript:muestraDatos("aux_id_prop","I","tabs-7")'>Uso Interno</a></li>
                                </ul>
                                <div id="tabs-1">
                                    <?php
//if (isset($_GET['i'])) {
//    $id= $_GET['i'];
                                    if(!isset($_POST['pagina'])) {
                                        $pag=1;
                                    }else {
                                        $pag=$_POST['pagina'];
                                    }
                                    //$postreVW= new PropiedadVW();
                                    //$postreVW->vistaTablaPropiedad($pag);

                                    //print_r($_POST);
                                    $prop = new PropiedadVW();
                                    $prop->vistaTablaBuscadorTabs($pag);


//}
                                    ?>
                                </div>
                                <div id="tabs-2"></div>
                                <div id="tabs-3"></div>
                                <div id="tabs-4"></div>
                                <div id="tabs-5"></div>
                                <div id="tabs-6"></div>
                                <div id="tabs-7"></div>
                            </div>
                        </div><!-- End demo -->

                    </td>
                </tr>
            </table>
        </div><!-- end #container -->
    </body>
</html>

<?php ob_end_flush(); ?>