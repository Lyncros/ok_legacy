<?php

include_once("inc/encabezado.php");
include_once("clases/class.propiedadVW.php");
include_once("clases/class.clienteBSN.php");
include_once("clases/class.crmbuscadorBSN.php");
include_once("inc/encabezado_pop.php");

if (!isset($_POST['id_cli'])) {
    print "<div style='text-align:center; margin:auto; width:450px; margin-top:30px;'>\n";
    print "<div class='pg_titulo'>Envio de busqueda</div>\n";
    print "<form action='enviarSeleccion.php?i=' name='carga' id='carga' method='post' >\n";
    print "<table width='450'>\n";
    print "<tr><td width='20%'>Seleccione Cliente: \n";
    print "</td>\n";
    print "<td width='80%'>\n";
    $cliBSN = new ClienteBSN();
    $cliBSN->comboUsuarios();
    print "</td></tr>\n";
    print "<tr><td width='20%'>Ingrese Mensaje:\n";
    print "</td>\n";
    print "<td width='80%'>\n";
    print "<textarea cols='42' rows='7' name='msgMail' id='msgMail'>De acuerdo a su consulta le adjuntamos propiedades que se ajustan a su búsqueda.
        No dude en contactarnos para resolver cualquier inquietud sobre la misma.
    </textarea>\n";
    print "</td></tr>\n";
    print "<input type='hidden' name='aux_id_prop' id='aux_id_prop' value='" . $_POST['aux_id_prop'] . "' />\n";
    print "<input type='hidden' name='fid_tipo_prop' id='fid_tipo_prop' value='" . $_POST['fid_tipo_prop'] . "' />\n";
    print "<input type='hidden' name='fid_seltipo_prop' id='fid_seltipo_prop' value='" . $_POST['fid_seltipo_prop'] . "' />\n";
    print "<input type='hidden' name='fid_codigo' id='fid_codigo' value='" . $_POST['fid_codigo'] . "' />\n";
    print "<input type='hidden' name='fid_calle' id='fid_calle' value='" . $_POST['fid_calle'] . "' />\n";
    print "<input type='hidden' name='fid_ubica' id='fid_ubica' value='" . $_POST['fid_ubica'] . "' />";
    print "<input type='hidden' name='fid_selloca' id='fid_selloca' value='" . $_POST['fid_selloca'] . "' />\n";
    print "<input type='hidden' name='fid_emp' id='fid_emp' value='" . $_POST['fid_emp'] . "' />\n";
    print "<input type='hidden' name='fid_selemp' id='fid_selemp' value='" . $_POST['fid_selemp'] . "' />\n";
    print "<input type='hidden' name='foperacion' id='foperacion' value='" . $_POST['foperacion'] . "' />\n";
    print "<input type='hidden' name='seloperacion' id='seloperacion' value='" . $_POST['seloperacion'] . "' />\n";
    print "<input type='hidden' name='filtro' id='filtro' value='" . $_POST['filtro'] . "' />\n";
    print "<input type='hidden' name='campo' id='campo' value='" . $_POST['campo'] . "' />\n";
    print "<input type='hidden' name='orden' id='orden' value='" . $_POST['orden'] . "' />\n";
    print "<input type='hidden' name='adjuntos' id='adjuntos' value='" . $_POST['adjuntos'] . "' />\n";
    print "<input type='hidden' name='vistasel' id='vistasel' value='" . $_POST['vistasel'] . "' />\n";

    print "<input type='hidden' name='crmtxt' id='crmtxt' value='" . $_POST['crmtxt'] . "' />\n";
    print "<input type='hidden' name='crmpar' id='crmpar' value='" . $_POST['crmpar'] . "' />\n";
    print "<input type='hidden' name='identificador' id='identificador' value='" . $_POST['identificador'] . "' />\n";
    print "<tr><td colspan='2'>\n";
    print "<input type='submit' value='Enviar ...'>\n";
    print "</td></tr>\n";

    print "</form>\n";
    print "</div>\n";
} else {

    if (isset($_GET['i'])) {
        $id = $_GET['i'];

        $postreVW = new PropiedadVW();
        $postreVW->armaMailSeleccion();
    }
}
include_once("inc/pie.php");
?>

