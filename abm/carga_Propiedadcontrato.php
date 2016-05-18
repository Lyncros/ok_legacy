<?php
include_once ("inc/encabezado.php");
include_once ("clases/class.propiedadcontratoBSN.php");
include_once ("clases/class.propiedadcontratoVW.php");
include_once ("./inc/encabezado_pop.php");
?>
<link rel="stylesheet" type="text/css"
      href="jquery.ui-1.5.2/themes/ui.datepicker.css" />
<link rel="stylesheet" type="text/css" href="css/thickbox.css"
      media="screen" />
<link rel="stylesheet" type="text/css"
      href="css/jquery.autocomplete.css" media="screen" />
<script type="text/javascript" src="jquery.ui-1.5.2/jquery.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/thickbox.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/ui/ui.datepicker.js"></script>
<script type="text/javascript"
src="jquery.ui-1.5.2/jquery.bgiframe.min.js"></script>
<script type="text/javascript"
src="jquery.ui-1.5.2/jquery.autocomplete.js"></script>
<?php
$ingreso = true;
$id = "";

$origen = "lista_propiedadcontrato.php?i=";

if (isset($_GET ['i']) && $_GET ['i'] != 0) {
    $prop = $_GET ['i'];
    $id = $_GET ['cnt'];
    if (isset($_GET ['b']) && $_GET ['b'] == 'b' && $id != 0) {
        $contBSN = new PropiedadcontratoBSN($id);
        $contBSN->borraDB();
        echo "<script type=\"text/javascript\">KillMe(); </script>\n";
    }

    if (isset($_GET ['cnt']) && $_GET ['cnt'] == 0) {
        $contVW = new PropiedadcontratoVW ();
        $contVW->setIdPropiedad($prop);
    }
} else {
    $contVW = new PropiedadcontratoVW ();
    if (isset($_POST ['id_contrato'])) {
        $contVW->leeDatosVW();
        $prop = $contVW->getIdPropiedad();
        if ($_POST ['id_contrato'] == 0) {
            $retorno = $contVW->grabaDatosVW(false);
        }
        if (!$retorno) {
            echo "Fallo el registro de los datos";
        } else {
            $ingreso = false;
        }

        //			echo "<script type=\"text/javascript\">KillMe(); </script>\n";
    }
}
if ($ingreso) {
    $_SESSION ['id_prop'] = $prop;
    $contVW->vistaTablaVW($prop);
    print "<br />";
    $contVW->cargaDatosVW();
} else {
    echo "<script type=\"text/javascript\">KillMe(); </script>\n";
}
include_once ("./inc/pie.php");
?>

