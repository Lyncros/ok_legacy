<?php
include_once("inc/encabezado.php");
include_once("generic_class/class.cargaConfiguracion.php");

include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedadVW.php");

include_once("clases/class.datospropBSN.php");
include_once("clases/class.datospropVW.php");

include_once("clases/class.operacionBSN.php");
include_once("clases/class.tasacionBSN.php");

$conf = CargaConfiguracion::getInstance();
$timezone = $conf->leeParametro('timezone');
date_default_timezone_set($timezone);

include_once("clases/class.perfilesBSN.php");
$perf = new PerfilesBSN();
$perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
$perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);
//        if ($perfGpo == 'SUPERUSER' || strtoupper($perfGpo) == 'LECTURA' || $perfGpo == 'admin' || $perfGpo == 'GRAL' || strtoupper($perfGpo) == 'STAFF' || strtoupper($perfGpo) == 'GERENCIA') {


include_once("inc/encabezado_html_tabs.php");

if (isset($_GET['i']) && $_GET['i'] != '') {
    $id = $_GET['i'];
    $propVW = new PropiedadVW($id);
    $operacion = $propVW->getOperacion();
}
if (isset($_GET['c'])) {
    $cli = $_GET['c'];
} else {
    $cli = 0;
}
if(($operacion == "Tasacion" &&  strtoupper($perfGpo) == 'STAFF') || ($perfGpo == 'SUPERUSER' || $perfGpo == 'admin' || $perfGpo == 'GRAL' || strtoupper($perfGpo) == 'GERENCIA')){
?>

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Datos Propiedad</a></li>
        <li><a href="carga_Foto.php?i=<?php echo $id; ?>&f=0">Fotos</a></li>
        <li><a href="carga_Operacion.php?i=<?php echo $id; ?>&o=0">Operacion</a></li>
        <li><a href="carga_Cartel.php?i=<?php echo $id; ?>&o=0'">Cartel</a></li>
        <li><a href="carga_Tasacion.php?i=<?php echo $id; ?>&o=0'">Tasacion</a></li>
        <li><a href="carga_Relacion.php?i=<?php echo $id; ?>">Relacion</a></li>
    </ul>
    <div id="tabs-1">
        <?php
        //$propVW = new PropiedadVW($id);
        $tipo_prop = $propVW->getIdTipoProp();
        if ($tipo_prop == '') {
            $tipo_prop = 0;
        }
        $datosVW = new DatospropVW();

        print "<form action='carga_propiedad.php' target='report' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: if(ValidaPropiedad(this)){window.open('about:blank','report','width=300,height=200')};'>\n";


        $propVW->cargaDatosPropiedadDiv($cli);
        $datosVW->cargaDatosDatospropDiv($id, $tipo_prop);

        print "</form>\n";
        $_SESSION['opcionMenu'] = 2;
        ?>
    </div>
</div>

<?php
}else{
    echo "<div style=\"text-align:center; width:100%;padding-top:50px;;vertical-align:middle;font-size:20px; font-weight:bold;\">No tiene permisos para editar esta propiedad</div>\n";
}
include_once("./inc/pie.php");
?>

