<?php

include_once("clases/class.cucicbaBSN.php");

$id_user = "felipe.atucha@okeefe.com.ar";

$cu = new CucicbaBSN();

echo $cu->armoPublicacion($id_user);
?>
