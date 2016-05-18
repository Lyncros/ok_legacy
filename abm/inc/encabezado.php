<?php

/*
  ANTERIOR
  session_start();
  ob_start();
  //include_once("inc/controlAcceso.php");

  $comienzo=1;

 */

session_start();
ob_start();

include_once("generic_class/class.cargaConfiguracion.php");
include_once("generic_class/class.controlAccesos.php");
include_once("generic_class/class.menu.php");
include_once("./inc/funciones.inc");

parserURL();

$comienzo = 1;
//include_once("auth.php");
//include_once "./config/config_gral.php";

$conf = CargaConfiguracion::getInstance();
$timezone = $conf->leeParametro('timezone');
date_default_timezone_set($timezone);

$acceso = new Acceso();

if (!isset($_SESSION['opcionMenu']) && $_SESSION['opcionMenu'] == "") {
    $_SESSION['opcionMenu'] = 0;
}
$padre = $_SESSION['opcionMenu'];

if (!isset($_SESSION['UserId']) && !isset($_GET['u'])) {
//	$_SESSION['UserId']=2; // Se debera recuperar del CRM
    header("location: login.php");
} else {
    if (isset($_GET['u'])) {
        $_SESSION['UserId'] = $_GET['u'];
    }
}

if (!isset($_SESSION['Userrole']) && !isset($_GET['p'])) {
    // cambiar y poner perfil = "" 
//	$perfil="admin";
//	$_SESSION['Userrole']=$perfil;
    header("location:login.php");
} else {
    if (isset($_GET['p'])) {
        $perfil = $_GET['p'];
        $_SESSION['Userrole'] = $perfil;
    } else {
        $perfil = $_SESSION['Userrole'];
    }
}
if (!$acceso->validaAcceso($perfil, $_SESSION['opcionMenu'])) {
    $_SESSION['opcionMenu'] = 0;
    $_SESSION['Userrole'] = '';
    $menu = new Menu();
    $menu->ventanaAlerta("Intento acceder a una opcion no habilitada para su perfil");
    header("location:login.php");
}
?>
