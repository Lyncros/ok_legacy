<?php

include_once("generic_class/class.VW.php");
include_once("generic_class/class.menu.php");
include_once("clases/class.eventoBSN.php");
include_once("clases/class.evento.php");
include_once("inc/funciones.inc");
include_once("clases/class.tipoEventoBSN.php");
include_once("clases/class.eventocomponente.php");

class EventoVW extends VW {

    protected $clase = "Evento";
    protected $evento;
    protected $nombreId = "id_evento";

    public function __construct($_parametro = 0) {
        EventoVW::creaObjeto();
        if ($_parametro instanceof Evento) {
            EventoVW::seteaVW($_parametro);
        }
        if (is_numeric($_parametro)) {
            if ($_parametro != 0) {
                EventoVW::cargaVW($_parametro);
            }
        }
        EventoVW::cargaDefinicionForm();
    }

    public function cargaDatosVW($id, $div, $tipocomp, $comp) {
        print "<script type='text/javascript' language='javascript'>\n";
        print "function concatena_fecha(){\n";
        print "   	var anio = document.getElementById('auxFecha').value;\n";
        print "   	var hora = document.getElementById('hora_ev').value;\n";
        print "   	var minuto = document.getElementById('min_ev').value;\n";
        print "   	var fecha = anio + ' ' + hora + ':' + minuto;\n";
        print "   	document.getElementById('fecha_even').value = fecha;\n";
        print "		return true;\n";
        print "}\n";
        print "function componente(id,div){\n";
        print "   window.open('carga_EventoComponente.php?c=0&tc=C&ev='+id+'&div='+div, 'ventcomp', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";

        
        print "</script>\n";

        print "<div id='cargaData' name='cargaData' style='display:none;'>";
        print "<form action='carga_Evento.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaEvento(this);'>\n";

        print "<div class='pg_titulo'>Registro de tareas a programar</div>\n";

        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo Tarea<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $tipoEv = TipoEventoBSN::getInstance();
        $tipoEv->comboParametros($this->arrayForm ['id_tipoevento'], 0, 'id_tipoevento');
        print " </td></tr>\n";


        print "<tr>";
        print "<td class='cd_celda_texto' width='15%'>Asunto</td>";
        print "<td width='85%'><input class='campos' type='text' name='tarea' id='tarea' value='" . $this->arrayForm['tarea'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "<tr>";
        print "<td class='cd_celda_texto' width='15%'>Detalle</td>";
        print "<td width='85%'><input class='campos' type='text' name='detalle' id='detalle' value='" . $this->arrayForm['detalle'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Activa</td><td>";
        print "<input type='checkbox' name='activa' id='activa' ";
        if ($this->arrayForm['activa'] == 0) {
            print ">";
        } else {
            print " checked >";
        }
        print "</td>\n";
        print "</tr>\n";

        $fecha = substr($this->arrayForm['fecha_even'], 0, 10);
        $horamin = explode(':', trim(substr($this->arrayForm['fecha_even'], 10)));
        print "<tr><td class='cd_celda_texto' width='15%'>Fecha</td>";
        print "<td width='85%'><input class='campos' type='text' name='auxFecha' id='auxFecha' value='" . $fecha . "' maxlength='10' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "	<script type=\"text/javascript\">\n";
        print "jQuery(\"#auxFecha\").datepicker({changeMonth: true,changeYear: true, regional: 'es',showButtonPanel: true,dateFormat: \"dd-mm-yy\"});";
        print "</script>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Hora</td>";
        print "<td width='85%'>";
        print "<select class=\"campos_fecha\" name='hora_ev' id='hora_ev' onchange='javascript:concatena_fecha();'>\n" . armaComboHora($horamin[0]) . "</select>\n";
        print ":\n";
        if (isset($horamin[1])) {
            $minuto = $horamin[1];
        } else {
            $minuto = 0;
        }
        print "<select class=\"campos_fecha\" name='min_ev' id='min_ev' onchange='javascript:concatena_fecha();'>\n" . armaComboMinu($minuto) . "</select>\n";
//        print "<select class=\"campos_fecha\" name='min_ev' id='min_ev' onchange='javascript:concatena_fecha();'>\n" . armaComboMinu($horamin[1]) . "</select>\n";
        print "</td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Componentes</td><td>";
        print "<div name='div_comp' id='div_comp'>";
        $evcVW = new EventocomponenteVW();
        $evcVW->vistaTablaVW($this->arrayForm['id_evento'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Componente' onclick=\"javascript:componente(" . $id . ",'div_comp'); \"><br />\n";
        print "</td></tr>";

        if ($this->arrayForm['id_evento'] == 0 || $this->arrayForm['id_evento'] == '') {
            print "<input type='hidden' name='id_evento' id='id_evento' value='" . $id . "'>\n";
            print "<input type='hidden' name='operacion' id='operacion' value='n'>\n";
        } else {
            print "<input type='hidden' name='id_evento' id='id_evento' value='" . $this->arrayForm['id_evento'] . "'>\n";
            print "<input type='hidden' name='operacion' id='operacion' value='m'>\n";
        }
        if ($tipocomp == 'A') {
            print "<input type='hidden' name='id_asunto' id='id_asunto' value='" . $comp . "'>\n";
        }
        print "<input type='hidden' name='fecha_even' id='fecha_even' value='" . $this->arrayForm['fecha_even'] . "'>\n";
        print "<input type='hidden' name='div' id='div' value='" . $div . "'>\n";
        print "<input type='hidden' name='id_comp' id='id_comp' value='" . $comp . "'>\n";
        print "<input type='hidden' name='tipocomp' id='tipocomp' value='" . $tipocomp . "'>\n";
        print "<input type='hidden' name='user' id='user' value='" . $_SESSION['UserId'] . "'>\n";

        print "<br>";
        print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
        print "</div>";
    }

    public function formulario2str($id, $div, $tipocomp, $comp) {
        $str='';
        $str.= "<div id='cargaData' name='cargaData' style='display:block;'>";
        $str.= "<form name='tareasCli' id='tareasCli' method='post' >\n";

        $str.= "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

        $str.= "<tr><td class='cd_celda_texto' width='15%'>Tipo Tarea<span class='obligatorio'>&nbsp;&bull;</span></td>";
        $str.= "<td width='85%'>";
        $tipoEv = TipoEventoBSN::getInstance();
        $str.=$tipoEv->comboParametros2string($this->arrayForm ['id_tipoevento'], 0, 'id_tipoevento');
        $str.= " </td></tr>\n";


        $str.= "<tr>";
        $str.= "<td class='cd_celda_texto' width='15%'>Asunto</td>";
        $str.= "<td width='85%'><input class='campos' type='text' name='tarea' id='tarea' value='" . $this->arrayForm['tarea'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        $str.= "<tr>";
        $str.= "<td class='cd_celda_texto' width='15%'>Detalle</td>";
        $str.= "<td width='85%'><input class='campos' type='text' name='detalle' id='detalle' value='" . $this->arrayForm['detalle'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";

        $str.= "<tr><td class='cd_celda_texto' width='15%'>Activa</td><td>";
        $str.= "<input type='checkbox' name='activa' id='activa' ";
        if ($this->arrayForm['activa'] == 0) {
            $str.= ">";
        } else {
            $str.= " checked >";
        }
        $str.= "</td>\n";
        $str.= "</tr>\n";

        $fecha = substr($this->arrayForm['fecha_even'], 0, 10);
        $horamin = explode(':', trim(substr($this->arrayForm['fecha_even'], 10)));
        $str.= "<tr><td class='cd_celda_texto' width='15%'>Fecha</td>";
        $str.= "<td width='85%'><input class='campos' type='text' name='auxFecha' id='auxFecha' value='" . $fecha . "' maxlength='10' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        $str.= "	<script type=\"text/javascript\">\n";
        $str.= "jQuery(\"#auxFecha\").datepicker({changeMonth: true,changeYear: true, regional: 'es',showButtonPanel: true,dateFormat: \"dd-mm-yy\"});";
        $str.= "</script>\n";
        $str.= "<tr><td class='cd_celda_texto' width='15%'>Hora</td>";
        $str.= "<td width='85%'>";
        $str.= "<select class=\"campos_fecha\" name='hora_ev' id='hora_ev' onchange='javascript:concatena_fecha();'>\n" . armaComboHora($horamin[0]) . "</select>\n";
        $str.= ":\n";
        if (isset($horamin[1])) {
            $minuto = $horamin[1];
        } else {
            $minuto = 0;
        }
        $str.= "<select class=\"campos_fecha\" name='min_ev' id='min_ev' onchange='javascript:concatena_fecha();'>\n" . armaComboMinu($minuto) . "</select>\n";
//        $str.= "<select class=\"campos_fecha\" name='min_ev' id='min_ev' onchange='javascript:concatena_fecha();'>\n" . armaComboMinu($horamin[1]) . "</select>\n";
        $str.= "</td></tr>\n";
    /*
        $str.= "<tr><td class='cd_celda_texto' width='15%'>Componentes</td><td>";
        $str.= "<div name='div_comp' id='div_comp'>";
        $evcVW = new EventocomponenteVW();
        $evcVW->vistaTablaVW($this->arrayForm['id_evento'], 'v');
        $str.= "</div>";
        $str.= "<br /><input class='boton_form' type='button' value='Cargar Componente' onclick=\"javascript:componente(" . $id . ",'div_comp'); \"><br />\n";
        $str.= "</td></tr>";
*/
        if ($this->arrayForm['id_evento'] == 0 || $this->arrayForm['id_evento'] == '') {
            $str.= "<input type='hidden' name='id_evento' id='id_evento' value='" . $id . "'>\n";
            $str.= "<input type='hidden' name='operacion' id='operacion' value='n'>\n";
        } else {
            $str.= "<input type='hidden' name='id_evento' id='id_evento' value='" . $this->arrayForm['id_evento'] . "'>\n";
            $str.= "<input type='hidden' name='operacion' id='operacion' value='m'>\n";
        }
        if ($tipocomp == 'A') {
            $str.= "<input type='hidden' name='id_asunto' id='id_asunto' value='" . $comp . "'>\n";
        }
        $str.= "<input type='hidden' name='fecha_even' id='fecha_even' value='" . $this->arrayForm['fecha_even'] . "'>\n";
        $str.= "<input type='hidden' name='div' id='div' value='" . $div . "'>\n";
        $str.= "<input type='hidden' name='id_comp' id='id_comp' value='" . $comp . "'>\n";
        $str.= "<input type='hidden' name='tipocomp' id='tipocomp' value='" . $tipocomp . "'>\n";
        $str.= "<input type='hidden' name='user' id='user' value='" . $_SESSION['UserId'] . "'>\n";
        $str.= "<input type='hidden' name='id_prop_evt' id='id_prop_evt' value='0'>\n";
        $str.= "<input type='hidden' name='id_cli_evt' id='id_cli_evt' value='0'>\n";
        
        
        $str.= "<br>";
        $str.= "<tr><td colspan='3' align='right'><input class='boton_form' type='button' value='Enviar' onclick=\"javascript: registraEvento();\"><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        $str.= "</td></tr>\n</table>\n";
        $str.= "</form>\n";
        $str.= "</div>";
        return $str;
    }
    
    
    
    /**
     * Despliega una tabla con los datos relativos a las tareas programadas para el tipo e id indicado
     * @param char $tipo -> tipo de referencia A: Asunto    P: Propiedad   C: Cliente  U: Usuario   O: Contacto   F: Fecha
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
        print "</script>\n";
        if ($opcion == 'o') {
            print "<form name='lista' method='POST' action='respondeMenu.php'>";

            print "<script type='text/javascript' language='javascript'>\n";
            print "function envia(nameForm,opcion,id){\n";
            print "   document.forms.lista.id_evento.value=id;\n";
            print "   submitform(nameForm,opcion);\n";
            print "}\n";
            print "function modifica(id){\n";
            print "     document.getElementById('opcion').value='m';\n";
            print "     tipo='".$tipo."';\n";
            print "     comp=".$id.";\n";
            print "     document.location.href='carga_Evento.php?id='+id+'&c='+comp+'&tc='+tipo;\n";
            print "}\n";
            print "function borra(evento){\n";
            print "     document.getElementById('opcion').value='b';\n";
            print "     document.location.href='carga_Evento.php?id='+evento+'&b=b';\n";
            print "}\n";
            print "function muestraCargaData(){\n";
            print "   document.getElementById('cargaData').style.display='block';\n";
            print "}\n";

            print "</script>\n";

            print "<div class='pg_subtitulo'>    Tareas programadas  </div>\n";

            $arrayTools = array(array('Nuevo', 'images/building_edit.png', 'muestraCargaData()'), array('Regresar', 'images/ui-button-navigation-back.png', 'KillMe()'));
            $menu = new Menu();
            $menu->barraHerramientas($arrayTools);

            $endForm = "</form>";
        } else {
            print "<div class='pg_subtitulo'>    Tareas programadas  </div>\n";
            $endForm = "";
        }

        $objBSN = new EventoBSN();
        if ($tipo == 'A') {
            $arrayDatos = $objBSN->cargaColeccionByAsunto($id);
        } else {
        $arrayDatos = $objBSN->cargaColeccionByTipo($tipo, $id);
        }
        $this->despliegaTabla($arrayDatos, $opcion);


        print "<input type='hidden' name='id_evento' id='id_evento' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";

        print $endForm;
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
            $tipoBSN = TipoEventoBSN::getInstance();
            $arrayTipo = $tipoBSN->getArrayParametros();
            $compEv = new EventocomponenteBSN();

            foreach ($arrayDatos as $datosCont) {
                $onclick = "onclick=\"javascript: mostrarDetalle('detEven_" . $datosCont['id_evento'] . "')\" ";
                $arrayComp = $compEv->cargaColeccionDetalleComponentesByEvento($datosCont['id_evento']);

                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }

                print "<div $onclick>\n";
                // Botonera de acciones por registro
                print "	 <div class='row" . $fila . "' id=\"tareaNadaT\">";
                if ($opcion == 'o') {
                    print "    <a href=\"javascript:modifica(" . $datosCont['id_evento'] . ");\" border='0'>";
                    print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a>";
                    print "  &nbsp;\n";
//                    print "	 <td class='row" . $fila . "'>";
                    print "    <a href=\"javascript:borra(" . $datosCont['id_evento'] . ");\" border=0>";
                    print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
                } else {
                    print "&nbsp;";
                }
                print "  </div>\n";
                // Fin Botonera de acciones
                $detalle = "Detalle: ";
                foreach ($arrayComp as $datoComp) {
                    $detalle .= $datoComp[0] . "<br />" . $datoComp[1];
                }
                print "	 <div class='row" . $fila . "' id=\"tareaFechaT\" title=\"$detalle\"> " . date("d-m-Y", strtotime($datosCont['fecha_even'])) . "</div>\n";
                print "	 <div class='row" . $fila . "' id=\"tareaTipoT\" title=\"$detalle\">" . $arrayTipo[$datosCont['id_tipoevento']] . "</div>\n";
                print "	 <div class='row" . $fila . "' id=\"tareaTareaT\" title=\"$detalle\">" . $datosCont['tarea'] . "</div>\n";
//                print "	 <div class='row" . $fila . "' id=\"tareaDetalleT\" title=\"$detalle\">" . $datosCont['detalle'];
//                print "  <div id='detEven_" . $datosCont['id_evento'] . "' style='display:none;'>";
//                print "Referente a:<br>";
//                print "     </div>";
                // agregar las columnas a mostrar

                print "	</div>\n";
            }

            //print "  </div>\n";
        }
    }

    public function vistaDatosAsunto($id_asunto = 0) {
        print "<script type='text/javascript' language='javascript'>\n";
        print "function mostrarDetalle(elem){\n";
        print "   est=document.getElementById(elem).style.display;\n";
        print "   if(est=='none'){\n";
        print "      document.getElementById(elem).style.display='block';\n";
        print "   }else{\n";
        print "      document.getElementById(elem).style.display='none';\n";
        print "   }\n";
        print "}\n";
        print "</script>\n";

        $objBSN = new EventoBSN();
        $arrayDatos = $objBSN->cargaColeccionByAsunto($id_asunto);
        $this->despliegaTabla($arrayDatos, 'l');
}

    public function vistaUltimosEventos($id_user = 0, $estado = -99) {

        $evBSN = new EventoBSN();
        $arrayDatos = $evBSN->cargaColeccionByUsuarioEstado($id_user, $estado);
        $fila = 0;
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {
            $tipoBSN = TipoEventoBSN::getInstance();
            $arrayTipo = $tipoBSN->getArrayParametros();
            $compEv = new EventocomponenteBSN();

            foreach ($arrayDatos as $datosCont) {
                $arrayComp = $compEv->cargaColeccionDetalleComponentesByEvento($datosCont['id_evento']);

                $detalle = "Referente a:<br>";
                foreach ($arrayComp as $datoComp) {
                    $detalle .= $datoComp[0] . "<br>" . $datoComp[1] . "<br>";
                }

                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "  <div class='row" . $fila . "'  style=\"width:100%; color: #02508F\">\n";

                print "	 <div id=\"asuntoFechaT\" title=\"$detalle\">" . substr($datosCont['fecha_even'],0,10) . "</div>\n";
                print "	 <div id=\"asuntoTipoT\" title=\"$detalle\">" . $arrayTipo[$datosCont['id_tipoevento']] . "</div>\n";
                print "	 <div id=\"asuntoAsuntoT\" title=\"$detalle\">" . $datosCont['tarea'] . "</div>\n";
                print "   <div class=\"clearfix\"></div>\n";
                print "     </div>";
            }
        }
    }

    public function vistaUltimosEventosAjax($id_user = 0, $estado = -99) {
        $evBSN = new EventoBSN();
        $arrayDatos = $evBSN->cargaColeccionByUsuarioEstado($id_user, $estado);
        $fila = 0;
        $strRet = '';
        if (sizeof($arrayDatos) == 0) {
            $strRet.="No existen datos para mostrar";
        } else {
            $tipoBSN = TipoEventoBSN::getInstance();
            $arrayTipo = $tipoBSN->getArrayParametros();
            $compEv = new EventocomponenteBSN();
            
            $astoBSN = new AsuntoBSN();
            $cliBSN = new ClienteBSN();
            print "<link href=\"css/vista_principal.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
            print " <div style=\"width:792px; padding:0px 2px; float:right;\">\n";
            print "    <div id=\"tabla_tareas\">\n";
            print "      <li style=\"background-color: #E8E8E8;\">\n";
            print "        <div id=\"id_tarea\" style=\"text-align:center\"><strong>TAREA</strong></div>\n";
            print "        <div id=\"cliente_tarea\" style=\"text-align:center\"><strong>CLIENTE</strong></div>\n";
            print "        <div id=\"status_tarea\" style=\"text-align:center\"><strong>STATUS</strong></div>\n";
            print "        <div id=\"asoc_tarea\" style=\"text-align:center\"><strong>ID Asoc.</strong></div>\n";
            print "        <div class=\"clearfix\"></div>\n";
            print "      </li>\n";

            
            foreach ($arrayDatos as $datosCont) {
                $arrayComp = $compEv->cargaColeccionDetalleComponentesByEvento($datosCont['id_evento']);
                if($datosCont['id_asunto']!=0){
                    $astoBSN->cargaById($datosCont['id_asunto']);
                    $cliBSN->cargaById($astoBSN->getObjeto()->getId_cli());
                    $idCli=$astoBSN->getObjeto()->getId_cli();
                    $referente=trim($cliBSN->getObjeto()->getNombre())+" "+trim($cliBSN->getObjeto()->getApellido());
                }else{
                    foreach ($arrayComp as $datoCom){
                        if($datoCom[3]=='C'){
                            $idCli=$datoCom[2];
                            $referente=$datoCom[0];
                        }
                    }
                }    

                $detalle = "Referente a:"+$referente;
                $onClick="javascript:document.getElementById('ID_CLI').value=".$idCli .";cargaInfoCliente('v');";
                $asoc=array();
                foreach ($arrayComp as $datoComp) {
                    $detalle .= $datoComp[0] . "<br>" . $datoComp[1] . "<br>";
                    if($datoComp[3]=='P'){
                        $asoc[]=$datoComp[2];
                    }
                }

                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
/*                $strRet.="  <div class='rowVista" . $fila . "'  style=\"width:100%; color: #02508F\"  onclick=\"".$onClick."\">\n";

                $strRet.="	 <div id=\"asuntoFechaT\" title=\"$detalle\">" . substr($datosCont['fecha_even'],0,10) . "</div>\n";
                $strRet.="	 <div id=\"asuntoTipoT\" title=\"$detalle\">" . $arrayTipo[$datosCont['id_tipoevento']] . "</div>\n";
                $strRet.="	 <div id=\"asuntoAsuntoT\" title=\"$detalle\">" . $datosCont['tarea'] . "</div>\n";
                $strRet.="   <div class=\"clearfix\"></div>\n";
                $strRet.="     </div>";
 * 
 */
                $strRet.="      <li class='row" . $fila . "'  onclick=\"".$onClick."\">\n";
                $strRet.="        <div id=\"id_tarea\">" . substr($datosCont['fecha_even'],0,10) . " > ". $arrayTipo[$datosCont['id_tipoevento']] . "</div>\n";
//                $strRet.="        <div id=\"cliente_tarea\">" . trim($cliBSN->getObjeto()->getNombre()) . " " . trim($cliBSN->getObjeto()->getApellido()) . "</div>\n";
                $strRet.="        <div id=\"cliente_tarea\">" . $referente . "</div>\n";
                $strRet.="        <div id=\"status_tarea\">";
                if($datosCont['activa']==1){
                    $status='ACTIVA';
                }else{
                    $status='CERRADA';
                }
                $strRet.=($status."          </div>\n");
                $strRet.="        <div id=\"asoc_tarea\">".  implode(',', $asoc)."</div>\n";
                $strRet.="        <div class=\"clearfix\"></div>\n";
                $strRet.="      </li>\n";

            }
        }

        return $strRet;
    }

}

// fin clase
?>
