<?php
include_once("inc/encabezado.php");
include_once('clases/class.clienteBSN.php');
include_once('clases/class.clienteVW.php');

include_once("generic_class/class.cargaConfiguracion.php");
header('Content-Type: text/html; charset=utf-8');
$conf = CargaConfiguracion::getInstance();
$anchoPagina = $conf->leeParametro("ancho_pagina");
$gmapkey = $conf->leeParametro("gmkey");

$timezone = $conf->leeParametro('timezone');
date_default_timezone_set($timezone);
$vista = 0;
$campo = '';

if (isset($_GET['c'])) {
    if ($_GET['c'] == 0 || $_GET['c'] == '') {
        $timestamp = date('YmdHis');
    } else {
        $timestamp = $_GET['c'];
    }
} else {
    if (isset($_POST['id_cli'])) {
        $timestamp = $_POST['id_cli'];
    }
}
if (isset($_POST['cpo']) && $_POST['cpo'] != '') {
    $campo = $_POST['cpo'];
    $vista = 1;
}
if (isset($_GET['cpo']) && $_GET['cpo'] != '') {
    $campo = $_GET['cpo'];
    $vista = 1;
}
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
        
<!--        <script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gmapkey ?>" type="text/javascript"></script>  -->
        <script language="javascript" type="text/javascript">
            $(function() {
                $( "#buscaCli" ).autocomplete({
                    source: "autocompletarClientes.php",
                    minLength: 2,
                    select: function( event, ui ) {
                        document.getElementById('id_promo').value=ui.item.id;
                    }
                });
            });
            function RepaintPagina(){
                listaTelefonos('C',<?php echo $timestamp; ?>, 'div_tel');
                listaDomicilios('C',<?php echo $timestamp; ?>, 'div_dom');
                listaFamiliares('C',<?php echo $timestamp; ?>, 'div_fam');
                listaMediosElectronicos('C',<?php echo $timestamp; ?>, 'div_med');
                listaRelacionesCliente(<?php echo $timestamp; ?>, 'div_ejec');
                listaTareas('C',<?php echo $timestamp; ?>, 'div_tar');
            }
<?php
if ($vista == 1) {
    print "function recargaComboParent(){\n";
    print "    window.parent.opener.actualizaComboRelacionRemoto('$campo',$timestamp);\n";
    print "}\n";
}
?>
        </script>

    </head>

    <body onload="load();" onunload="GUnload();" onfocus="RepaintPagina();">

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
//require_once('generic_class/LiteVerifyCode.class.php');

                $logon = new ClienteVW();
                $ingreso = true;
                $id = "";
                $origen = "lista_clientes.php?c=";

                if (isset($_GET['c'])) {
                    $id = $_GET['c'];
                    $logon->cargaVW($id);
                } else {
                    if (isset($_POST['id_cli'])) {
                        $logon->leeDatosVW();

//		LiteVerifyCode::checkSessionState();
//		if(isset($_POST['code']) && (strtoupper($_POST['code']) === $_SESSION['VERIFY_CODE'])){
//		if(isset($_POST['code'])){
                        if ($_POST['operacion'] == 'n') {
                            $cliIng = new IngresoclienteVW();
                            $cliIng->leeDatosVW();
                            $retorno = $logon->grabaDatosVW(true);
//                            if($retorno){
                            $cliIng->grabaDatosVW(false);

                            //                          }
                        } else {
                            $retorno = $logon->grabaModificacion();
                        }
                        if (!$retorno) {
                            echo "Fallo el registro de los datos";
                        } else {
                            $_SESSION['messageExito'] = "Los datos fueron grabados exitosamente.";
                            if ($vista == 0) {
                                $ingreso = false;
                            } else {
                                echo "<script type=\"text/javascript\">recargaComboParent();window.parent.focus();self.close(); </script>\n";
                            }
                        }
//		}
//		}else {
//			echo "Fallo la carga del codigo de seguridad. Por favor reintente.";
//		}
                    }
                }

//$logon=new LoginwebuserVW($id);
                if ($ingreso) {

//	LiteVerifyCode::Code();
//	$codseg=$ROOT_URL."generic_class/LiteVerifyCode.class.php?code.gif";
//	$logon->cargaDatosVW($codseg);
                    $logon->cargaDatosVW($timestamp, $campo);
                    $_SESSION['opcionMenu'] = 6;
                } else {
                    $_SESSION['opcionMenu'] = 6;
                    header('location:' . $origen . $id);
                }
                ?>

            </div>
            <!-- end #container --></div>
    </body>
</html>
