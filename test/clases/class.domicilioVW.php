<?php

include_once ('generic_class/class.VW.php');
include_once("clases/class.domicilioBSN.php");
include_once("clases/class.domicilio.php");
include_once ("clases/class.ubicacionpropiedadBSN.php");
include_once("inc/funciones.inc");

class DomicilioVW extends VW {

    protected $clase = "Domicilio";
    protected $domicilio;
    protected $nombreId = "Id_dom";

    public function __construct($_domicilios = 0) {
        DomicilioVW::creaObjeto();
        if ($_domicilios instanceof Domicilio) {
            DomicilioVW::seteaVW($_domicilios);
        }
        if (is_numeric($_domicilios)) {
            if ($_domicilios != 0) {
                DomicilioVW::cargaVW($_domicilios);
            }
        }
        DomicilioVW::cargaDefinicionForm();
    }

    public function cargaDatosVW($destino = '') {
        $objBSN = new DomicilioBSN();
        print "<div id='cargaData' name='cargaData' style='display:none;'>";

        print "<form action='carga_Domicilio.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaDomicilio(this);'>\n";
        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='cd_celda_titulo'>Carga de Domicilio</td></tr>\n";

        print "<tr><td align='center'>";
        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='cd_celda_texto'>Tipo<span class='obligatorio'>&nbsp;&bull;</span><br />";
        $objBSN->comboTipodom($this->arrayForm['tipodom']);
        print "</td><td></td></tr>\n";

        print "<td class='cd_celda_texto' >Ubicacion <span class='obligatorio'>&nbsp;&bull;</span><br />";
        $ubiBSN = new UbicacionpropiedadBSN();
        if ($this->arrayForm['id_ubica'] == '') {
            $this->arrayForm['id_ubica'] = 0;
            $textoUbica = 'Seleccione una Zona';
        } else {
            $textoUbica = $ubiBSN->armaNombreZona($this->arrayForm['id_ubica']);
        }
        $id_padre = $ubiBSN->definePrincipalByHijo($this->arrayForm['id_ubica']);
        $ubiBSN->comboUbicacionpropiedadPrincipal($id_padre, 0);
        print "</td>\n";
        print "<td>\n";
        print "<div id='txtUbica'>$textoUbica</div>";
        print "<input type='hidden' id='id_ubica' name='id_ubica' value='" . $this->arrayForm['id_ubica'] . "'>";
        print "<input type='button' value='Despliegue Subzonas' onclick=\"window.open('seleccionaZona.php?v='+document.getElementById('id_ubica').value+'&z='+document.getElementById('id_ubicaPrincipal').value+'&t=r', 'ventanaDom', 'menubar=1,resizable=1,width=950,height=550');\">";


        print "</td>\n";
        print "</tr>\n";

        print "<tr><td class='cd_celda_texto'>Calle<span class='obligatorio'>&nbsp;&bull;</span><br />";
        print "<input class='campos' type='text' name='calle' id='calle' value='" . $this->arrayForm['calle'] . "' maxlength='250' size='80'></td>\n";
        print "<td class='cd_celda_texto'>Nro<br />";
        print "<input class='campos' type='text' name='nro' id='nro' value='" . $this->arrayForm['nro'] . "' maxlength='45' size='80'></td>\n";
        print "</tr>\n";

        print "<tr>\n";
        print "<td class='cd_celda_texto'>Piso<br />";
        print "<input class='campos' type='text' name='piso' id='piso' value='" . $this->arrayForm['piso'] . "' maxlength='45' size='80'></td>\n";
        print "<td class='cd_celda_texto'>Dpto<br />";
        print "<input class='campos' type='text' name='dpto' id='dpto' value='" . $this->arrayForm['dpto'] . "' maxlength='45' size='80'></td>\n";
        print "</tr>\n";

        print "<tr>\n";
        print "<td class='cd_celda_texto'>Entre<br />";
        print "<input class='campos' type='text' name='entre1' id='entre1' value='" . $this->arrayForm['entre1'] . "' maxlength='250' size='80'></td>\n";
        print "<td class='cd_celda_texto'> y <br />";
        print "<input class='campos' type='text' name='entre2' id='entre2' value='" . $this->arrayForm['entre2'] . "' maxlength='250' size='80'></td>\n";
        print "</tr>\n";

        print "<tr>\n";
        print "<td class='cd_celda_texto'>Codigo Postal<br />";
        print "<input class='campos' type='text' name='cp' id='cp' value='" . $this->arrayForm['cp'] . "' maxlength='45' size='80'></td>\n";
        print "<td class='cd_celda_texto'>Principal<br />";
        print "<input class='campos' type='checkbox' name='principal' id='principal' ";
        if ($this->arrayForm['principal'] == 1) {
            print "checked";
        }
        print "></td></tr>\n";

        print "<input type='hidden' name='id_dom' id='id_dom' value='" . $this->arrayForm['id_dom'] . "'>\n";
        print "<input type='hidden' name='id_cont' id='id_cont' value='" . $this->arrayForm['id_cont'] . "'>\n";
        print "<input type='hidden' name='tipocont' id='tipocont' value='" . $this->arrayForm['tipocont'] . "'>\n";
        print "<input type='hidden' name='div' id='div' value='" . $destino . "'>\n";

        print "<br>";
        print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
        print "</div>";
    }

    /**
     * Lee desde un formulario los datos cargados para el domicilios.
     * Los registra en un objeto del tipo domicilios domicilios de esta clase
     *
     */
    public function leeDatosVW() {
        $domicilios = new DomicilioBSN();
        if ($_POST['principal'] == 'on') {
            $_POST['principal'] = 1;
        }
        $this->domicilio = $domicilios->leeDatosForm($_POST);
    }

    /**    OK
     * Muestra una tabla con los datos de los domicilioss y una barra de herramientas o menu
     * donde se despliegan las opciones ingresables para cada item
     * La misma corresponde a los domicilios del contacto indocado por tipo e ids pasados como parametros
     * @param string $_tipo -> tipo de contacto al que corresponde el ID   U:usuario   C:cliente
     * @param int $_id -> id del contacto
     * @param char $modo -> modo de visualizacion de la lista 'v' vista 'o' operativo
     */
    public function vistaTablaVW($_tipo = '', $_id = 0, $modo = 'o') {
        $ubiBSN = new UbicacionpropiedadBSN();
        $fila = 0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.id_dom.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function modifica(t,tc,c){\n";
        print "     document.getElementById('opcion').value='m';\n";
        print "     document.location.href='carga_Domicilio.php?t='+t+'&tc='+tc+'&c='+c;\n";
        print "}\n";
        print "function borra(t,tc,c){\n";
        print "     document.getElementById('opcion').value='b';\n";
        print "     document.location.href='carga_Domicilio.php?t='+t+'&b=b&tc='+tc+'&c='+c;\n";
        print "}\n";
        print "function muestraCargaData(){\n";
        print "   document.getElementById('cargaData').style.display='block';\n";
        print "}\n";

        print "</script>\n";

        print "<div class='pg_subtitulo'>Listado de Domicilio de Contacto</div>\n";
        if ($modo == 'o') {
            $arrayTools = array(array('Nuevo', 'images/card--plus.png', 'muestraCargaData()'), array('Regresar', 'images/ui-button-navigation-back.png', 'KillMe()'));
            $menu = new Menu();
            $menu->barraHerramientas($arrayTools);

            print "<form name='lista' method='POST' action='respondeMenu.php'>";
            print "  <table class='cd_tabla' width='100%'>\n";
            print "    <tr>\n";
            print "     <td class='tituColCli' colspan='2'>&nbsp;</td>\n";
        } else {
            print "  <table class='cd_tabla' width='100%'>\n";
            print "    <tr>\n";
        }
        print "     <td class='tituColCli'>&nbsp;</td>\n";
        print "     <td class='tituColCli'>Tipo</td>\n";
        print "     <td class='tituColCli'>Zona</td>\n";
        print "     <td class='tituColCli'>Calle</td>\n";
        print "     <td class='tituColCli'>Nro</td>\n";
        print "     <td class='tituColCli'>Piso</td>\n";
        print "     <td class='tituColCli'>Dpto</td>\n";
        print "	  </tr>\n";


        $evenBSN = new DomicilioBSN();
        switch ($_tipo) {
            case 'U':
                $arrayEven = $evenBSN->coleccionByUsuarios($_id);
                break;
            case 'C':
                $arrayEven = $evenBSN->coleccionByCliente($_id);
                break;
            case 'O':
                $arrayEven = $evenBSN->coleccionByContactoAgenda($_id);
                break;
        }
        if (sizeof($arrayEven) == 0) {
            print "No existen datos para mostrar";
        } else {
            $zonAnt = 0;
            //			$locaAnt=0;
            foreach ($arrayEven as $Even) {
                if ($zonAnt != $Even['id_ubica']) {
                    $zonAnt = $Even['id_ubica'];
                    $strZona = $ubiBSN->armaNombreZona($Even['id_ubica']);
                }
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "<tr>\n";
                print "<tr>\n";
                if ($modo == 'o') {
                    print "	 <td class='row" . $fila . "' width='16'>";
                    print "    <a href=\"javascript:modifica(" . $Even['id_dom'] . ",'" . $Even['tipocont'] . "'," . $Even['id_cont'] . ");\" border='0'>";
                    print "       <img src='images/card--pencil.png' alt='Editar' title='Editar' border=0></a></td>";
                    print "	 <td class='row" . $fila . "' width='16'>";
                    print "    <a href=\"javascript:borra(" . $Even['id_dom'] . ",'" . $Even['tipocont'] . "'," . $Even['id_cont'] . ");\" border=0>";
                    print "       <img src='images/card--minus.png' alt='Borrar' title='Borrar' border=0></a>";
                    print "  </td>\n";
                }
                print "	 <td class='row" . $fila . "'>";
                if ($Even['principal'] == 1) {
                    print "<img src='images/house_link.png' alt='Principal'>";
                } else {
                    print "&nbsp;";
                }
                print "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['tipodom'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $strZona . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['calle'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['nro'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['piso'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['dpto'] . "</td>\n";
                print "	</tr>\n";
            }
        }

        print "  </table>\n";
        print "<input type='hidden' name='id_dom' id='id_dom' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        if ($modo == 'o') {
            print "</form>";
        } else {
            
        }
    }

    public function vistaTablaReducidaVW($_tipo = '', $_id = 0, $modo = 'o') {
        $ubiBSN = new UbicacionpropiedadBSN();
        $fila = 0;

        $evenBSN = new DomicilioBSN();
        switch ($_tipo) {
            case 'U':
                $arrayEven = $evenBSN->coleccionByUsuarios($_id);
                break;
            case 'C':
                $arrayEven = $evenBSN->coleccionByCliente($_id);
                break;
            case 'O':
                $arrayEven = $evenBSN->coleccionByContactoAgenda($_id);
                break;
        }
        if (sizeof($arrayEven) != 0) {
            $zonAnt = 0;
            //			$locaAnt=0;
            print "  <table class='cd_tabla' width='100%'>\n";
            print "    <tr>\n";
            print "     <td class='cd_lista_titulo'>Tipo</td>\n";
            print "     <td class='cd_lista_titulo'>Zona</td>\n";
            print "     <td class='cd_lista_titulo'>Calle</td>\n";
            print "	  </tr>\n";

            foreach ($arrayEven as $Even) {
                if ($zonAnt != $Even['id_ubica']) {
                    $zonAnt = $Even['id_ubica'];
                    $strZona = $ubiBSN->armaNombreZonaAbr($Even['id_ubica']);
                }
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "<tr>\n";
                print "<tr>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['tipodom'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $strZona . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['calle'] . " " . $Even['nro'] . " - " . $Even['piso'] . " " . $Even['dpto'] . "</td>\n";
                print "	</tr>\n";
            }
        }

        print "  </table>\n";
    }

}

// fin clase
?>
