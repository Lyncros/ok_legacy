<?php

include_once("inc/encabezado.php");
include_once("clases/class.relacion.php");

$ingreso = true;
$id = "";

if ($_GET['pc']!=0 && $_GET['sc']!=0 && $_GET['r']!=0 && $_GET['b'] == 'b') {
    $relBSN = new RelacionBSN($_GET['pc'], $_GET['sc'], $_GET['r']);
    $relBSN->borraDB();
    $ingreso=false;
    echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
}
if (isset($_GET['i']) && $_GET['i'] != 0) {
    $prop = $_GET['i'];
    $relVW = new RelacionVW(0, $prop, 0);
} else {
    $relVW = new RelacionVW();
    if (isset($_POST['id_pc']) && isset($_POST['id_sc']) && isset($_POST['id_relacion'])) {
        $relVW->leeDatosVW();
        $prop = $relVW->getVW()->getId_sc();
        $retorno = $relVW->grabaDatosVW(false);
        if (!$retorno) {
            echo "Fallo el registro de los datos";
        }
        echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
    }
}
if ($ingreso) {
    $relVW->vistaTablaVW(0, $prop, 0);
    print "<br>";
    $relVW->cargaDatosRelacion('P', 0, $prop, 0);
} else {
    echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
}
?>
