<?php
set_time_limit(0);
include_once('clases/class.loginwebuserBSN.php');
include_once("clases/class.perfilesBSN.php");
include_once("clases/class.argenpropBSN.php");
include_once("generic_class/class.cargaParametricos.php");

$apColeccion = new ArgenpropBSN();
$colec = $apColeccion->coleccionRegistros($activas = 1);
// print_r($colec);
// echo "<br />" . count($colec);
// die();

for ($i = 0; $i<=count($colec); $i++) {
    $apConsulta = new ArgenpropBSN($colec[$i]['id_prop']);
    $propAP = $apConsulta->consultaRegistroPropiedad();

    if ($propAP[$i]['fec_ini'] != '' && ($propAP[$i]['fec_fin'] == '' || is_null($propAP[$i]['fec_fin']))) {

        $usrBSN = new LoginwebuserBSN();
        $usrBSN->cargaById($propZP[$i]['id_user']);
        $miUser = $usrBSN->getObjeto();

        $id_resp = $propAP[$i]['id_resp'];
        $id_user = $propAP[$i]['id_user'];
        $usrMail = $propAP[$i]['cuenta'];
        //                print_r($propZP);//die();
        $clave = '';
        $parametricos = new CargaParametricos('usuariosZp.xml');
        $arraUsers = $parametricos->getParametros();
        if (array_key_exists($usrMail, $arraUsers)) {
            $datosUser = $arraUsers[$usrMail];
            $arrayDatos = explode("|", $datosUser);
            $clave = $arrayDatos[1];
        }
        $ap = new ArgenpropBSN($colec[$i]['id_prop'], $id_resp);
        $ap->publicoPropiedad($usrMail, $clave, 'm');
        
		echo $colec[$i]['id_prop']."<br />";
    }
}
?>