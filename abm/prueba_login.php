<?php

/* PRUEBAS DE LOGINBSN */
include_once("clases/class.loginBSN.php");
include_once("clases/class.login.php");

//$loginBSN=new LoginBSN();

$login=new Login("paco","p1o2i3u4","25/06/2007",0,"","01/01/0001");

$loginBSN=new LoginBSN($login);

$retorno=$loginBSN->solicitudcambioClave();

//$retorno=$loginBSN->controlLogin("paco","p1o2i3u4");
if ($retorno!=true){
	echo "Fallo";
} else {
	echo "Paso";
}



/* PRUEBAS DE LOGINPGDAO
include_once($ROOT_PATH."pruebas/login/class.loginPGDAO.php");

$login= new LoginPGDAO();

$login->insertLogin("paco","p1o2i3u4","25/06/2007","","01/01/0001",0);


$result=$login->findByNombre("pepe");
while ($row = pg_fetch_array($result)){
	print_r($row);
}


$login->updateLogin(4,"pepe","p1o2i3u4","25/06/2007","123456789","15/07/2007",0);

$result=$login->coleccionLogin();
while ($row = pg_fetch_array($result)) {
	print_r($row);
}



echo "<br>BORRO<BR>";
$result=$login->deleteLogin(4);


echo "<br>BORRE  a PEPE <BR>";
$result=$login->coleccionLogin();
while ($row = pg_fetch_array($result)) {
	print_r(array_keys($row));
	
}
*/
?>