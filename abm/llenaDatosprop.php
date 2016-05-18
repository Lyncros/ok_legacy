<?php
include_once("clases/class.datospropVW.php");

		$tipo = $_GET['p'];
		$id_prop =$_GET['i'];
		$div=$_GET['d'];
		$dpVW = new DatospropVW();
		$dpVW->cargaDatosDatospropDiv($id_prop, $tipo,$div);

?>