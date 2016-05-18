<?php

include_once("inc/encabezado.php");
include_once("clases/class.operacionBSN.php");
include_once("clases/class.operacionVW.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.tasacionBSN.php");

$ingreso = true;
$id = "";

if (isset($_GET ['i']) && $_GET ['i'] != 0) {
    $prop = $_GET ['i'];
    $id = $_GET ['o'];
    if (isset($_GET ['b']) && $_GET ['b'] == 'b' && $id != 0) {
        $operBSN = new OperacionBSN($id);
        $operBSN->borraDB();
        $ingreso = false;
    }
    if (isset($_GET ['o']) && $_GET ['o'] == 0) {
        $operacionVW = new OperacionVW ();
        $operacionVW->setIdPropiedad($prop);
    }
} else {
    $operacionVW = new OperacionVW ();
    if (isset($_POST ['id_oper'])) {
        $operacionVW->leeDatosVW();
        $prop = $operacionVW->getIdPropiedad();
        $fecha = $_POST['cfecha'];
        $comentario = $_POST['observacion'];
        if ($_POST ['id_oper'] == 0 || $_POST ['id_oper'] == '') {
            $propBSN = new PropiedadBSN($prop);
            $propBSN->setOperacion($_POST ['operacion']);
            $retorno = $operacionVW->grabaDatosVW(false);
            $propBSN->actualizaDB();
            $ingreso = false;
            switch ($_POST ['operacion']) {
                case 'Tasacion':
                    $tasBSN = new TasacionBSN();
                    $tasBSN->insertaTasacionPendiente($prop, $fecha, $comentario);
                    break;
                case 'Tasado':
                    $tasBSN = new TasacionBSN();
                    $tasBSN->insertaTasacionCumplida($prop, $fecha, $comentario);
                    break;
                case 'Retirado':
                case 'Suspendido':
                    $tasBSN = new TasacionBSN();
                    $tasBSN->insertaTasacionRetirada($prop, $fecha, $comentario);
                    break;
            }
        }
        if (!$retorno) {
            echo "Fallo el registro de los datos";
        } else {
            $ingreso = false;
        }
    }
}
if ($ingreso) {
    $_SESSION ['id_prop'] = $prop;
    $operacionVW->vistaTablaVW($prop);
    print "<br />";
    $operacionVW->cargaDatosVW();
} else {
    echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
}
?>
