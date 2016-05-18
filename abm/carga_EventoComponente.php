<?php
include_once("inc/encabezado.php");
include_once("clases/class.eventocomponente.php");
include_once("generic_class/class.menu.php");
//include_once("./inc/encabezado_html.php");
if (isset($_GET['ev'])&& isset($_GET['c'])) {
    $id = $_GET['ev'];
    $comp = $_GET['c'];
    $div = $_GET['div'];
} else {
    $comp = $_POST['id'];
    $div = $_POST['div'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>O'Keefe Propiedades en ABM</title>
        <script language="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
        <script language="JavaScript" type="text/javascript" src="inc/menuPullDown.js"></script>


        <link rel="stylesheet" type="text/css" href="css/agenda.css" />
        <link rel="stylesheet" type="text/css" href="css/menuPullDown.css" />

        <link rel="stylesheet" href="jquery1.9/themes/base/jquery.ui.all.css">
            <link rel="stylesheet" href="jquery1.9/css/ui-lightness/jquey.ui.datepicker.css">

                <script type="text/javascript" src="jquery1.9/js/jquery-1.8.2.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.core.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.widget.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.position.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.menu.js"></script>
                <script type="text/javascript" src="jquery1.9/ui/jquery.ui.autocomplete.js"></script>
                <script src="jquery1.9/ui/jquery.ui.datepicker.js"></script>
                <script src="jquery1.9/ui/i18n/jquery.ui.datepicker-es.js"></script>

                <style>
                    .ui-autocomplete {
                        max-height: 100px;
                        overflow-y: auto;
                        /* prevent horizontal scrollbar */
                        overflow-x: hidden;
                    }
                    /* IE 6 doesn't support max-height
                     * we use height instead, but this forces the menu to always be this tall
                    */
                    * html .ui-autocomplete {
                        height: 100px;
                    }
                </style>

                <script language="javascript" type="text/javascript">
                    $(function() {
                        $( "#buscaCli" ).autocomplete({
                            source: "autocompletarClientes.php",
                            minLength: 2,
                            select: function( event, ui ) {
                                document.getElementById('id').value=ui.item.id;
                            }
                        });
                    });
                    var StayAlive = 1;
                    function KillMe(){
                        setTimeout("self.close();",StayAlive * 1000);
                    }
                </script>
                <script type="text/javascript">
                    document.oncontextmenu = function(){return false;}
                </script>
                </head>
                <body onload="this.window.focus();">

                    <div id="container">

                        <?php
                        $ingreso = true;
//                        $id = "";



                        if (isset($_GET['c']) && isset($_GET['ev'])) {
                            $comp = $_GET['c'];
                            $id=$_GET['ev'];
                            $div = $_GET['div'];

                            if (isset($_GET['b']) && $_GET['b'] == 'b' && $comp != 0) {
                                $compBSN = new EventocomponenteBSN($comp);
                                $compBSN->borraDB();
                                echo "<script type=\"text/javascript\">KillMe(); </script>\n";
                            }
                            $compVW = new EventocomponenteVW($comp);
                        } else {
                            $compVW = new EventocomponenteVW();
                            if (isset($_POST['id_evencomp'])) {
                                $compVW->leeDatosVW();

                                if ($_POST['id_evencomp'] == 0) {
                                    $retorno = $compVW->grabaDatosVW(false);
                                } else {
                                    $retorno = $compVW->grabaModificacion();
                                }

                                if (!$retorno) {
                                    echo "Fallo el registro de los datos";
                                    echo "<script type=\"text/javascript\">KillMe(); </script>\n";
                                } else {
                                    $tipo_comp = $_POST['tipo_comp'];
                                    $id = $_POST['id'];
                                    $Ecomp = new Eventocomponente();
                                    $Ecomp->setTipo_comp($tipo_comp);
                                    $Ecomp->setId($id);
                                    $compVW = new EventocomponenteVW($comp);
                                    echo "<script type=\"text/javascript\">KillMe(); </script>\n";
                                }
                            }
                        }
                        if ($ingreso) {
                            $compVW->vistaTablaVW($id, 'o');
                            print "<br>";
                            $compVW->cargaDatosVW($div,$id);
                            if ($id != 0) {
                                echo "<script type=\"text/javascript\">muestraCargaData(); </script>\n";
                            }
                        } else {
                            echo "<script type=\"text/javascript\">KillMe(); </script>\n";
                        }
                        include_once("./inc/pie.php");
                        ?>
