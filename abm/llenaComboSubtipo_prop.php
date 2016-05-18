<?php
include_once("clases/class.tipo_propBSN.php");
include_once("clases/class.tipo_prop.php");

		$tipo = $_GET['p'];
		$subtipo =$_GET['a'];
		$campoloc=$_GET['c'];
		$tipoBSN = new Tipo_propBSN($tipo);
		$tipoBSN->comboSubtipoProp($subtipo,$tipo,0,$campoloc);

?>