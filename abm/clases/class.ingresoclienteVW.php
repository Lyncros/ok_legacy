<?php

include_once("generic_class/class.VW.php");
include_once("generic_class/class.menu.php");
include_once("clases/class.ingresoclienteBSN.php");
include_once("clases/class.ingresocliente.php");
include_once("inc/funciones.inc");
include_once("clases/class.formaingresoBSN.php");
include_once("clases/class.formacontactoBSN.php");
include_once("clases/class.promocionBSN.php");
include_once("clases/class.mediosweb.php");

class IngresoclienteVW extends VW {

    protected $clase = "Ingresocliente";
    protected $ingresocliente;
    protected $nombreId = "Id_ingreso";

    public function __construct($_id = 0, $tipo = 'I') {
        IngresoclienteVW::creaObjeto();
        if ($_id instanceof Ingresocliente) {
            IngresoclienteVW::seteaVW($_id);
        }
        if ($_id != 0 && $tipo == 'C') { // Ingreso el Id de un cliente
        } else {
            if (is_numeric($_id)) {
                if ($_id != 0) {
                    IngresoclienteVW::cargaVW($_id);
                }
            }
        }

        IngresoclienteVW::cargaDefinicionForm();
    }

// Cargo los datos del ingreso de un cliente segun su ID    
    protected function cargaByCliente($id) {
        
    }

    public function cargaDatosVW() {
        print "<div id='cargaData' name='cargaData' style='display:none;'>";

        print "<div class='pg_titulo'>Forma de Ingreso del cliente  </div>\n";

        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

        if (!isset($this->arrayForm['id_ingreso']) || (isset($this->arrayForm['id_ingreso']) && $this->arrayForm['id_ingreso'] != '' && $this->arrayForm['id_ingreso'] != 0)) {
            $usr_carga = $_SESSION['UserId'];
            print "<input type='hidden' name='fec_cont' id='fec_cont' value='" . date('d/m/Y') . "'>\n";
        } else {
            $usr_carga = $this->arrayForm['usr_carga'];
            print "<tr>";
            print "<td class='cd_celda_texto' width='15%'>Fecha Contacto</td>";
            print "<td width='85%'><input class='campos' type='text' readonly='readonly' name='fec_cont' id='fec_cont' value='" . $this->arrayForm['fec_cont'] . "' maxlength='250' size='80'></td><td></td></tr>\n";
        }

        print "<tr><td class='cd_celda_texto' width='15%'>Via de contacto<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $fContBSN = FormacontactoBSN::getInstance();
        $fContBSN->comboParametros($this->arrayForm ['id_fcontacto'], 0, 'id_fcontacto');
        print "<span id='medioCont' class='errorForm'>Un medio de contacto es obligatorio.</span>\n";
        print " </td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Medio de Conocimiento<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $promo=0;
        if($this->arrayForm ['id_promo']!=''){
            $promo=$this->arrayForm ['id_promo'];
        }
        $fIngBSN = FormaingresoBSN::getInstance();
        $fIngBSN->comboParametros($this->arrayForm ['id_fingreso'], 0, 'id_fingreso','campos_btn','comboPromociones(\'id_fingreso\','.$promo.',\'divComboPromocion\')');
        print "<span id='medioConc' class='errorForm'>Un medio de conocimiento es obligatorio.</span>\n";
        print "</ br>";
        print "<div id='divComboPromocion' name='divComboPromocion'>";
        print "</div>";

        print "<div id='divPromoCli' name='divPromoCli' style='display:none'>";
        $titulo = 'Indique el Cliente';
        print "<br>$titulo<br>";
        print "<input type='text' size='50' name='buscaCli' id='buscaCli'>";
        print "<br> * Pon al menos 3 letras para que salgan opciones.";
        print "<input type='hidden' size='50' name='aux_promo' id='aux_promo'>";
        print "</div>";
        print " </td></tr>\n";

        print "<input type='hidden' name='id_ingreso' id='id_ingreso' value='" . $this->arrayForm['id_ingreso'] . "'>\n";
        print "<input type='hidden' name='usr_carga' id='usr_carga' value='" . $usr_carga . "'>\n";

        print "<br>";
        print "</div>";
    }

    public function vistaTablaVW() {
        $fila = 0;
        $elemBotonera = 1; // Cantidad de elementos en la botonera de acciones de cada registro
        print "<form name='lista' method='POST' action='respondeMenu.php'>";

        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "   document.forms.lista.ingresoclienteId.value=id;\n";
        print "   submitform(nameForm,opcion);\n";
        print "}\n";
        /*        print "function modifica(id){\n";
          print "     document.getElementById('opcion').value='m';\n";
          print "     document.location.href='carga_Ingresocliente.php?i='+id;\n";
          print "}\n";
          print "function borra(cartel){\n";
          print "     document.getElementById('opcion').value='b';\n";
          print "     document.location.href='carga_Ingresocliente.php?i='+cartel+'&b=b';\n";
          print "}\n";
         */
        print "</script>\n";

        print "<div class='pg_titulo'>Lista de formas de Ingreso de Clientes</div>\n";

//        $arrayTools = array(array('Nuevo', 'images/building_edit.png', 'muestraCargaData()'), array('Regresar', 'images/ui-button-navigation-back.png', 'KillMe()'));
        $menu = new Menu();
        $menu->barraHerramientas($arrayTools);



        $objBSN = new IngresoclienteBSN();

        $arrayDatos = $objBSN->cargaColeccion();
        $this->despliegaTabla($arrayDatos);

        print "<input type='hidden' name='ingresoclienteId' id='ingresoclienteId' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "</form>";
    }

    public function despliegaTabla($arrayDatos) {
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {
            print "  <table class='cd_tabla' width='100%'>\n";
            print "    <tr>\n";
            print "     <td class='cd_lista_titulo' colspan='$elemBotonera'>&nbsp;</td>\n";
            print "     <td class='cd_lista_titulo'>Titulo_columna</td>\n";
            // agregar los titulos de las columnas a mostrar
            print "	  </tr>\n";

            foreach ($arrayDatos as $datosCont) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }

                print "<tr>\n";
                // Botonera de acciones por registro
                print "	 <td class='row" . $fila . "'>";
                print "    <a href=\"javascript:modifica(" . $datosCont['ingresoclienteId'] . ");\" border='0'>";
                print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a>";
                print "  </td>\n";
                print "	 <td class='row" . $fila . "'>";
                print "    <a href=\"javascript:borra(" . $datosCont['ingresoclienteId'] . ");\" border=0>";
                print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
                print "  </td>\n";
                // Fin Botonera de acciones
                print "	 <td class='row" . $fila . "'>" . $datosCont['nombre_campo'] . "</td>\n";
                // agregar las columnas a mostrar

                print "	</tr>\n";
            }
            print "  </table>\n";
        }
    }

}

// fin clase
?>
