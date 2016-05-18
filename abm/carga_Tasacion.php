<?php
include_once ("inc/encabezado.php");
include_once ("clases/class.tasacionBSN.php");
include_once ("clases/class.tasacionVW.php");
include_once ("clases/class.operacionBSN.php");

$ingreso = true;
$id = "";

if (isset ( $_GET ['i'] ) && $_GET ['i'] != 0) {
	$prop = $_GET ['i'];
	$id = $_GET ['o'];
	if (isset ( $_GET ['b'] ) && $_GET ['b'] == 'b' && $id != 0) {
		$tasBSN = new TasacionBSN ( $id );
		$tasBSN->borraDB ();
		$ingreso = false;
	}
	
	if (isset ( $_GET ['o'] ) && $_GET ['o'] == 0) {
		$tasacionVW = new TasacionVW ();
		$tasacionVW->setIdPropiedad ( $prop );
	}
} else {
	$tasacionVW = new TasacionVW ();
	if (isset ( $_POST ['id_tasacion'] )) {
		$tasacionVW->leeDatosVW ();
		$prop = $tasacionVW->getIdPropiedad ();
                $estado=$_POST['estado'];
                
		if ($_POST ['id_tasacion'] == 0) {
			$retorno = $tasacionVW->grabaDatosVW ( false );
			$ingreso = false;
                        $opBSN = new OperacionBSN();
                        $opBSN->insertaTasacion($tasacionVW->getVW());
		}
		if (! $retorno) {
			echo "Fallo el registro de los datos";
		} else {
			$ingreso = false;
		}

	}
}
if ($ingreso) {
	$_SESSION ['id_prop'] = $prop;
	$tasacionVW->vistaTablaVW ( $prop );
	print "<br />";
	$tasacionVW->cargaDatosVW ();
} else {
        echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
}

?>
