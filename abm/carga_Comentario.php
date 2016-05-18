<?php

include_once ("inc/encabezado.php");
include_once ("clases/class.comentarioBSN.php");
include_once ("clases/class.comentarioVW.php");
include_once ("./inc/encabezado_pop.php");

$ingreso = true;
$id = "";

if (isset($_GET ['i']) && $_GET ['i'] != 0) {
    $prop = $_GET ['i'];
    if (isset($_GET ['acc']) && isset($_GET ['o']) && $_GET ['o'] != 0) {
        $comBSN = new ComentarioBSN($_GET['o']);
        switch ($_GET ['acc']) {
            case 'b':
                $comBSN->borraDB();
                $ingreso = false;
                break;
            default :
                break;
        }
    }
    if (isset($_GET ['o']) && $_GET ['o'] == 0) {
        $comVW = new ComentarioVW ();
    }else{
        $comVW = new ComentarioVW ($_GET['o']);
    }
} else {
    $comVW = new ComentarioVW ();

    if (isset($_POST ['id_com'])) {
        $comVW->leeDatosVW();
        $prop = $comVW->getIdPropiedad();
        if ($_POST ['id_com'] == 0) {
            $retorno = $comVW->grabaDatosVW(false);
        }else{
            $retorno = $comVW->grabaModificacion();
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
    $comVW->cargaDatosVW($prop);
} else {
    echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
}
include_once ("./inc/pie.php");
?>
