<?php

include_once("inc/encabezado.php");
include_once("clases/class.relacion.php");
include_once("inc/encabezado_pop.php");

$ingreso = true;
$id = "";

if ($_GET['pc'] != 0 && $_GET['sc'] != 0 && $_GET['r'] != 0 && $_GET['b'] == 'b') {
    $relBSN = new RelacionBSN($_GET['pc'], $_GET['sc'], $_GET['r']);
    $relBSN->borraDB();
    $ingreso = false;
    if (!isset($_GET['div']) || $_GET['div'] == '') {
        echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
    } else {
        echo "<script type=\"text/javascript\">alert('entra');window.parent.opener.RepaintPagina();window.parent.focus();self.close(); </script>\n";
    }
}
if (isset($_GET['c']) && $_GET['c'] != 0) {
    $cli = $_GET['c'];
    $relVW = new RelacionVW(0, $cli, 0);
} else {
    print_r($_POST);
    $relVW = new RelacionVW();
    if (isset($_POST['id_pc']) && isset($_POST['id_sc']) && isset($_POST['id_relacion'])) {

        $relVW->leeDatosVW();
        $cli = $relVW->getVW()->getId_sc();
        $retorno = $relVW->grabaDatosVW(false);
        if (!$retorno) {
            echo "Fallo el registro de los datos";
        }
        if (!isset($_GET['div']) || $_GET['div'] == '') {
            echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
        } else {
            echo "<script type=\"text/javascript\">window.parent.opener.RepaintPagina();window.parent.focus();self.close(); </script>\n";
        }
    }
}
if ($ingreso) {
//    $relVW->vistaTablaVW($cli,0, 0);
    $relVW->vistaRelacionesCliente($cli, 1);
    print "<br>";
    $relVW->cargaDatosRelacionCliente('C', $cli);
} else {
    if (!isset($_GET['div']) || $_GET['div'] == '') {
        echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
    } else {
        echo "<script type=\"text/javascript\">window.parent.opener.RepaintPagina();window.parent.focus();self.close(); </script>\n";
    }
}
include_once("inc/pie.php");
?>
