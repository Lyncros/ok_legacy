<?php
include_once("clases/class.relacion.php");
		$id=$_GET['id'];
		$objVW = new RelacionVW();
		$objVW->vistaRelacionesUsuarioCliente(0,$id);
?>