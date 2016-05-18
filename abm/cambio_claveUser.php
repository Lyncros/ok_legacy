<?php

include_once("inc/encabezado.php");
include_once ('generic_class/securimage.php');
include_once('clases/class.loginwebuserBSN.php');
include_once('clases/class.loginwebuserVW.php');

include_once("./generic_class/class.cargaConfiguracion.php");
$timezone = $conf->leeParametro('timezone');
date_default_timezone_set($timezone);

include_once("inc/encabezado_html.php");


if (isset($_GET['i'])) {
    $timestamp = $_GET['i'];
} else {
    if (isset($_POST['id_user'])) {
        $timestamp = $_POST['id_user'];
    }
}


$logon = new LoginwebuserVW($timestamp);

$ingreso = true;
$id = "";
$origen = "lista_usuarios.php?i=";

$securimage = new Securimage();

if (isset($_GET['i'])) {
    $id = $_GET['i'];
} else {
    if (isset($_POST['id_user'])) {
        $logon->leeDatosLoginView();
        $captcha=$_POST['captcha'];
        echo $captcha."<br>";
        if ($securimage->check($captcha) == false) {
            echo 'Fallo la carga del codigo de seguridad. Por favor reintente.<br />';
        } else {

            $retorno = $logon->grabaModificacion();
            if (!$retorno) {
                echo "Fallo el registro de los datos";
            } else {
                $ingreso = false;
            }
        }
    }
}

$_SESSION['opcionMenu'] = 81;

if ($ingreso) {
    $logon->cambioClave();
} else {
    $_SESSION['opcionMenu'] = 81;
    header('location:' . $origen . $id);
}

include_once("./inc/pie.php");
?>
