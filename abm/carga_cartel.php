<?php
include_once ("inc/encabezado.php");
include_once ("clases/class.cartelBSN.php");
include_once ("clases/class.cartelVW.php");

$ingreso = true;
$id = "";

if (isset ( $_GET ['i'] ) && $_GET ['i'] != 0) {
	$prop = $_GET ['i'];
	$id = $_GET ['o'];
	if (isset ( $_GET ['b'] ) && $_GET ['b'] == 'b' && $id != 0) {
		$cartelBSN = new CartelBSN ( $id );
		$cartelBSN->borraDB ();
                $ingreso=false;
	}
	
	if (isset ( $_GET ['o'] ) && $_GET ['o'] == 0) {
		$cartelVW = new CartelVW ();
		$cartelVW->setIdPropiedad ( $prop );
	}
} else {
	$cartelVW = new CartelVW ();
	if (isset ( $_POST ['id_cartel'] )) {
		$cartelVW->leeDatosVW ();
		$prop = $cartelVW->getIdPropiedad ();
		if ($_POST ['id_cartel'] == 0) {
			$retorno = $cartelVW->grabaDatosVW ( false );
		}
                $ingreso=false;

		if (! $retorno) {
			echo "Fallo el registro de los datos";
		} else {
			$ingreso = false;
		}
	
	}
}
if ($ingreso) {
	$_SESSION ['id_prop'] = $prop;
	$cartelVW->vistaTablaVW ( $prop );
	print "<br />";
	$cartelVW->cargaDatosVW ();
} else {
                echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
}

?>

