<?php
include_once("clases/class.emprendimientoBSN.php");
include_once("clases/class.emprendimiento.php");
		$zona=$_GET['z'];
		$valor=$_GET['a'];
		$emp = new Emprendimiento();
		$emp->setId_ubica($zona);
		
		$part=new EmprendimientoBSN($emp);
		$part->comboEmprendimiento($valor,3,$zona);
//		$part->comboEmprendimiento($valor,3,$id_zona,$id_loca,$campoemp);

?>