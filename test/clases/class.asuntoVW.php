<?php

include_once("generic_class/class.VW.php");
include_once("generic_class/class.menu.php");
include_once("clases/class.asuntoBSN.php");
include_once("clases/class.asunto.php");
include_once("clases/class.tipoAsuntoBSN.php");
include_once("inc/funciones.inc");
include_once("clases/class.eventoVW.php");

class AsuntoVW extends VW {

    protected $clase = "Asunto";
    protected $asunto;
    protected $nombreId = "id_asunto";

    public function __construct($_parametro = 0) {
        AsuntoVW::creaObjeto();
        if ($_parametro instanceof Asunto) {
            AsuntoVW::seteaVW($_parametro);
        }
        if (is_numeric($_parametro)) {
            if ($_parametro != 0) {
                AsuntoVW::cargaVW($_parametro);
            }
        }
        AsuntoVW::cargaDefinicionForm();
    }

    /**
     * Despliega una tabla con los datos relativos a las tareas programadas para el tipo e id indicado
     * @param char $tipo -> tipo de referencia P: Propiedad   C: Cliente  U: Usuario   O: Contacto   F: Fecha
     * @param mixed $id -> id de la referencia , en el caso que tipo=F se presentara una fecha.
     * @param char $opcion -> identifica se la lista presenta opciones de menu o es exclusivamente de vista  o: operaciones    v: vistas.
     */
    public function vistaTablaVW($tipo = 'C', $id = 0, $opcion = 'o') {
        // Cantidad de elementos en la botonera de acciones de cada registro
        print "<script type='text/javascript' language='javascript'>\n";
        print "function mostrarDetalle(elem){\n";
        print "   est=document.getElementById(elem).style.display;\n";
        print "   if(est=='none'){\n";
        print "      document.getElementById(elem).style.display='block';\n";
        print "   }else{\n";
        print "      document.getElementById(elem).style.display='none';\n";
        print "   }\n";
        print "}\n";
        print "function aplicarFiltro(asunto){\n";
        print "  generaEvento(asunto,95,".$_SESSION['UserId'].");\n";
//        print "  window.location.assign(\"lista_propiedad.php?i=0&b=\"+asunto);\n";
        print "  window.open(\"lista_propiedad.php?i=0&b=\"+asunto,'propiedades');\n";
        print "}\n";
        print "function agregarTarea(id,div){\n";
        print "   window.open('carga_Evento.php?tc=A&c='+id+'&id=0&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function agregarAsunto(div){\n";
        print "  mostrarDetalle(div);\n";
        print "  document.getElementById('aux_astoNew').value=1;\n";
//        print "   window.open('carga_AsuntoNuevo.php?tc=A&c='+id+'&id=0&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "</script>\n";

        print "<div class='pg_subtitulo'>Detalle de Tareas x Asunto  ";
        print "<img src='images/calendar_add.png' onclick=\"javascript: agregarAsunto('div_AstoNew') ;\" style=\"cursor: pointer;\">";
        print "</div>\n";
        print "<div id='grabado'>";
        print "</div>\n";

        //  Armo la vista de datos de nuevos Asunto
        print "<div id='div_AstoNew' name='div_AstoNew' style='display:none;'>";
        print "<input type='hidden' size='50' name='aux_astoNew' id='aux_astoNew' value=0>";

        print "      <div>\n";
        $fIngBSN = FormaingresoBSN::getInstance();
        $fIngBSN->selectorFormaIngreso(0,'id_fingreso');

/*        
        print "        <div class=\"col2\">\n";
        print "          <div class=\"nombreCampo\">Medio de comunicación</div>\n";
        print "          <div>\n";
        $promo = 0;
        if ($valor != '' || $valor != 0) {
            $promo = $valor;
        }
        $fIngBSN->comboParametros(0, 0, 'id_fingreso', 'datoCampo', 'comboPromociones(\'id_fingreso\',0,\'divComboPromocion\')');
        print "          </div>\n";
        print "       </div>\n";
        print "        <div class=\"col2\">\n";
        print "             <div id='divComboPromocion' name='divComboPromocion'></div>\n";
        print "             <div id='divPromoCli' name='divPromoCli' style='display:none'>\n";
        $titulo = 'Indique el Cliente';
        print "             <div class=\"nombreCampo\">$titulo</div>";
        print "             <div><input class=\"datoCampo\" type='text' size='50' name='buscaPromoCli' id='buscaPromoCli' />\n";
        print "                 <input type='hidden' size='50' name='aux_promo' id='aux_promo' />\n";
        print "             </div>\n";
        print "                 * Pon al menos 3 letras para que salgan opciones.";
        print "             </div>\n";
        print "         </div>\n";
        print "        <div id=\"clearfix\"></div>\n";

*/
        print "    </div>\n";


        print "<div id=\"buscadorCliente\">Propiedad\n";

        print "  <input type='text' size='150' name='buscaProp' id='buscaProp' />\n";
        print "  &nbsp;<a href=\"javascript: visible('menuBuscadorPropiedad');\"><img src=\"images/icono_ir.png\" width=\"22\" height=\"22\" /> Buscador</a></div>\n";
        print "<input type='hidden' size='50' name='aux_prop' id='aux_prop'>";

        print "        <div>\n";

        $propVW = new PropiedadVW();
        $propVW->armaBuscadorPropHorizontal();
        print "        </div>\n";


        print "    <div><input class='boton_form' type='submit' value='Enviar'></div>\n";
//            print "    <br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span>\n";
        print "    <div id=\"clearfix\"></div>\n";
        print "</div>";

        $objBSN = new AsuntoBSN();

        if ($tipo == 'C') {
            $arrayDatos = $objBSN->coleccionByCliente($id);
        } else {
            $arrayDatos = array();
        }
        $this->despliegaTabla($arrayDatos, $opcion);
    }

    public function despliegaTabla($arrayDatos, $opcion) {
        if ($opcion == 'o') {
            $elemBotonera = 2;
        } else {
            $elemBotonera = 1;
        }
        $fila = 0;
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {
            $tipoBSN = TipoasuntoBSN::getInstance();
            $arrayTipo = $tipoBSN->getArrayParametros();
            $evenVW = new EventoVW();

            print "  <div id=\"asuntos\">\n";
            print "    <div>\n";
            print "     <div class=\"tituColCli\" id=\"asuntoNadaT\">&nbsp;</div>\n";
            print "     <div class=\"tituColCli\" id=\"asuntoFechaT\">Fecha</div>\n";
            print "     <div class=\"tituColCli\" id=\"asuntoTipoT\">Tipo</div>\n";
            print "     <div class=\"tituColCli\" id=\"asuntoAsuntoT\">Asunto</div>\n";
            // agregar los titulos de las columnas a mostrar
            print "     <div class=\"clearfix\"></div>\n";
            print "    </div>\n";

            foreach ($arrayDatos as $datosCont) {
                $onclick = "onclick=\"javascript: mostrarDetalle('detAsto_" . $datosCont['id_asunto'] . "')\" ";
//                $evtVW = $evenBSN->cargaColeccionByAsunto($datosCont['id_asunto']);

                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "<div>\n";

                print "<div id=\"tarea\" class='row" . $fila . "'  $onclick>\n";
                // Botonera de acciones por registro
                print "	 <div id=\"asuntoNadaT\">";
                print "     <img src='images/eye.png' width=\"14\" height=\"14\" onclick='javascript: aplicarFiltro(" . $datosCont['id_asunto'] . ") '>";
                print "     <img src='images/calendar_add.png' width=\"14\" height=\"14\" onclick=\"javascript: agregarTarea(" . $datosCont['id_asunto'] . ",'detAsto_" . $datosCont['id_asunto'] . "') ;\">";
                print "  </div>\n";
                // Fin Botonera de acciones
                print "	 <div id=\"asuntoFechaT\"> " . str_replace('/', '-', $datosCont['fec_inicio']) . "</div>\n";
                print "	 <div id=\"asuntoTipoT\">" . $arrayTipo[$datosCont['id_tipoasu']] . "</div>\n";
                print "	 <div id=\"asuntoAsuntoT\">" . $datosCont['titulo'] . "</div>\n";
                print "   <div class=\"clearfix\"></div>\n";
                print "</div>\n";
                print "  <div id='detAsto_" . $datosCont['id_asunto'] . "' style='display:none; width: 100%;'>\n";
//                print "Tareas:<br>";
                $evenVW->vistaDatosAsunto($datosCont['id_asunto']);
                print " </div>\n";
                print "   <div class=\"clearfix\"></div>\n";
                print "</div>\n";
                // agregar las columnas a mostrar
            }

            print "  </div>\n";
        }
    }

}

// fin clase
?>
