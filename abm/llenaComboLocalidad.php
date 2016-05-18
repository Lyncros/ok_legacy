<?php
include_once("clases/class.localidadBSN.php");
include_once("clases/class.localidad.php");
		$zona=$_GET['p'];
		$id_loca=$_GET['a'];
		$campoloc=$_GET['c'];
		$campoemp=$_GET['e'];
		$loca = new Localidad();
		$loca->setId_zona($zona);
		$loca->setId_loca($id_loca);
		$part=new LocalidadBSN($loca);
		$part->comboLocalidad($id_loca,$id_zona,1,$campoloc,'campos_btn',$campoemp);

?>