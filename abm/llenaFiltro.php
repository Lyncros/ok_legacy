<?php 
header("Cache-Control: no-store, no-cache, must-revalidate");
?>
<html><head></head>
<body>
<td colspan='4'>
<?php
include_once("clases/class.datospropVW.php");
		$dpropVW = new DatospropVW();
		$arraydp = array();
		$arrayDB = array();
		$arraydp = $dpropVW->leeFiltroDatospropVW();  // Lee los valores desde el filtro del formulario
		$dpropVW->cargaFiltroDatosprop($arraydp,$_GET['p']); // Carga nuevamente el filtro con los valores leidos

?>
</td>
</body>
</html>
