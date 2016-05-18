<?php
include_once("clases/class.telefonosVW.php");
		$tipocont=$_GET['t'];
		$cont=$_GET['tc'];
		$telVW = new TelefonosVW();
		$telVW->vistaTablaVW($tipocont,$cont,'v');
?>