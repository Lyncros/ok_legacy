<?php

include_once("inc/encabezado.php");
include_once("clases/class.relacion.php");
include_once("./inc/encabezado_pop.php");
//$ingreso = true;
$id = "";

if (isset($_GET['i']) && $_GET['i'] != 0) {
    $prop = $_GET['i'];
    $relVW = new RelacionVW(0, $prop, 0);
//}
//if ($ingreso) {
    $relVW->vistaPropietario(0, $prop, 0);
    print "<br>";
    //$relVW->cargaDatosRelacion('P', 0, $prop, 0);
}
?>
</body>
</html>
