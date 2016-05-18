<?php

include_once ('generic_class/class.VW.php');
include_once ('inc/funciones.inc');
include_once('clases/class.contacto.php');
include_once('clases/class.contactoBSN.php');
include_once ('clases/class.rubroBSN.php');
include_once ('clases/class.telefonosVW.php');
include_once ('clases/class.domicilioVW.php');
include_once("clases/class.perfilesBSN.php");

class ContactoVW extends VW {

    protected $clase = "Contacto";
    protected $contacto;
    protected $nombreId = "Id_cont";

    public function __construct($_contacto = 0) {
        ContactoVW::creaObjeto();
        if ($_contacto instanceof Contacto) {
            ContactoVW::seteaVW($_contacto);
        }
        if (is_numeric($_contacto)) {
            if ($_contacto != 0) {
                ContactoVW::cargaVW($_contacto);
            }
        }
        ContactoVW::cargaDefinicionForm();
    }

    public function cargaDatosVW($timestamp, $destino = '') {
//		$cliBSN= new ContactoBSN();
        $rubroBSN = new RubroBSN();
        print "<script type='text/javascript' >\n";
        print "function telefonos(id,div){\n";
        print "   window.open('carga_Telefonos.php?t=0&tc=O&c='+id+'&div='+div, 'ventanaTel', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function domicilio(id,div){\n";
        print "   window.open('carga_Domicilio.php?t=0&tc=O&c='+id+'&div='+div, 'ventanaCDom', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function rubro(){\n";
        print "   window.open('popupCargaRubro.php?u=0', 'ventanaRubro', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";

        if ($this->arrayForm['id_cont'] == 0 || $this->arrayForm['id_cont'] == '') {
            $this->arrayForm['id_cont'] = $timestamp;

            $operacion = 'n';
            $visibilidad = "text";
        } else {
            $visibilidad = "hidden";
            $operacion = 'm';
        }
        print "</script>";

        if ($operacion == 'n') {
            $titulo = "Carga de Contactos";
        } else {
            $titulo = "Editar Contacto";
        }
        print "<div class='pg_titulo'>" . $titulo . "</div>\n";
        print "<form name='f1' method='post' action='carga_contacto.php' ";
        if ($this->arrayForm['id_cont'] == 0) {
            print "onsubmit='return valida();'";
        }
        print " onfocus=\"listaTelefonos('O',$timestamp, 'div_tel');listaDomicilios('O',$timestamp, 'div_dom');comboRubro('div_rubro'," . $this->arrayForm['id_rubro'] . ");\">\n";

        print "<input type='hidden' name='operacion' id='operacion' value='" . $operacion . "' />\n";
        print "<input type='hidden' name='id_cont' id='id_cont' value='" . $this->arrayForm['id_cont'] . "' />\n";
        print "<input class='campos' type='hidden' name='usuario' id='usuario' value='$timestamp' maxlength='250' size='80' />";
        print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td align='center'>";
        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Razon social<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='razon' id='razon' value='" . $this->arrayForm ['razon'] . "' maxlength='250' size='80' /></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>CUIT<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='cuit' id='cuit' value='" . $this->arrayForm ['cuit'] . "' maxlength='20' size='30' /></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo Responsable<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        armaTipoResponsable($this->arrayForm ['tipo_responsable']);
        print " </td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Rubro<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        print "<div id='div_rubro' name='div_rubro' style='float: left; width: 250px;padding-right:10px;'>";
        $rubroBSN->comboRubro($this->arrayForm ['id_rubro']);
        print "</div>";
        print "<input class='boton_form' type='button' value='Nuevo Rubro' onclick=\"javascript:rubro(); \" />\n";

        print " </td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='nombre' id='nombre' value='" . $this->arrayForm ['nombre'] . "' maxlength='250' size='80' /></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Apellido<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='apellido' id='apellido' value='" . $this->arrayForm ['apellido'] . "' maxlength='250' size='80' /></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>E-Mail<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='email' id='email' value='" . $this->arrayForm ['email'] . "' maxlength='250' size='80' /></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Web (sin http) <span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='web' id='web' value='" . $this->arrayForm ['web'] . "' maxlength='250' size='80' /></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Domicilio</td><td>";
        print "<div name='div_dom' id='div_dom'>";
        $domVW = new DomicilioVW();
        $domVW->vistaTablaVW('O', $this->arrayForm['id_cont'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Domicilio' onclick=\"javascript:domicilio(" . $this->arrayForm ['id_cont'] . ",'div_dom'); \" /><br />\n";
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Telefono</td><td>";
        print "<div name='div_tel' id='div_tel'>";
        $telVW = new TelefonosVW();
        $telVW->vistaTablaVW('O', $this->arrayForm['id_cont'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Telefono' onclick=\"javascript:telefonos(" . $this->arrayForm ['id_cont'] . ",'div_tel'); \" /><br />\n";
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Observaciones<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><textarea rows='6' class='campos_area' name='observacion' id='observacion'>" . $this->arrayForm ['observacion'] . "</textarea></td></tr>\n";

        print "<br>";
        print "<input type='hidden' name='id_cont' id='id_cont' value='" . $this->arrayForm ['id_cont'] . "' />";
        print "<input type='hidden' name='div' id='div' value='" . $destino . "' />\n";
        print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar' /><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";

        print "</form>\n";
    }

    public function vistaTablaVW($_id = 0, $modo = 'o') {
        $fila = 0;
        $rubBSN = new RubroBSN();
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.id_cont.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function filtro(opc){\n";
        print "     campoCont='valorFiltro';\n";
        print "     campoRub='filtroRubro';\n";
        print "  if(opc==0){\n";
        print "     document.getElementById('valorFiltro').value='';\n";
        print "     document.getElementById('filtroRubro').selectedItem=0;\n";
        print "     document.getElementById('filtroRubro').value=0;\n";
        print "  }\n";
        print "  destino='tablaContactos'\n";
        print "  filtraContacto(campoCont,campoRub,destino);\n";
        print "}\n";

        print "</script>\n";
        print "<div class='pg_titulo'>Listado de Contactos</div>\n";
        print "<div id='auto_datos'>\n";
        print "Filtrar por: <input class='campos' name='valorFiltro' id='valorFiltro' type='text' value='' style='width:300px;' onkeyup='filtro(1);'>\n";
        print "&nbsp;&nbsp;o Rubro: \n";
        $rubBSN->comboRubro(0, 1, 'filtroRubro');
        print "&nbsp;&nbsp;<input type='button' value='Borrar filtro' onclick='filtro(0);' /> <input type='button' value='Filtrar' onclick='filtro(1);' />\n";
        print "</div>\n";

        if ($modo == 'o') {
            print "<form name='lista' method='POST' action='respondeMenu.php'>";
        } else {
            
        }
        $evenBSN = new ContactoBSN();
        $arrayEven = $evenBSN->cargaColeccionForm();
        print "<div id='tablaContactos'  style='height:600px; overflow:auto;'>";
        $this->despliegaTabla($arrayEven);
        print "</div>";

        print "<input type='hidden' name='id_cont' id='id_cont' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        if ($modo == 'o') {
            print "</form>";
        } else {
            
        }
    }

    public function despliegaTabla($arrayDatos) {
    	$perf = new PerfilesBSN();
        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);
    	
        $telBSN = new TelefonosBSN();

        $rubBSN = new RubroBSN();
        $arrayRubro = $rubBSN->armaArrayRubros();
        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
        print "     <td class='cd_lista_titulo'>Razon</td>\n";
        print "     <td class='cd_lista_titulo'>Nombre y Apellido</td>\n";
        print "     <td class='cd_lista_titulo'>Rubro</td>\n";
        print "     <td class='cd_lista_titulo'>eMail</td>\n";
        print "     <td class='cd_lista_titulo'>Telefono</td>\n";
        print "     <td class='cd_lista_titulo'>Web</td>\n";
        print "     <td class='cd_lista_titulo'>Cuit</td>\n";
        print "     <td class='cd_lista_titulo'>Tipo Resp.</td>\n";
        print "	  </tr>\n";
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {
            $fila=0;
            foreach ($arrayDatos as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                $telefono = $telBSN->listaTelefonosByContactoAgenda($Even ['id_cont']);
                print "<tr>\n";
//				if($modo=='o'){
       			if (strtoupper($perfGpo) != 'LECTURA') {
	                print "	 <td align='center' width='25' class='row" . $fila . "'>";
	                print "    <a href='javascript:envia(\"lista\",52,\"" . $Even ['id_cont'] . "\");' border='0'>";
	                print "       <img src='images/user_edit.png' alt='Editar' title='Editar' border=0></a></td>";
	                print "	 <td  align='center' width='25' class='row" . $fila . "'>";
	                print "    <a href='javascript:envia(\"lista\",53,\"" . $Even ['id_cont'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
	                print "       <img src='images/user_delete.png' alt='Borrar' title='Borrar' border=0></a>";
	                print "  </td>\n";
       			}else{
       				print "<td colspan='2'></td>\n";
       			}
//				}
                print "	 <td  class='row" . $fila . "'>" . $Even ['razon'] . "</td>\n";
                print "	 <td  class='row" . $fila . "'>" . $Even ['nombre'] . " " . $Even ['apellido'] . "</td>\n";
                print "	 <td  class='row" . $fila . "'>" . $arrayRubro[$Even ['id_rubro']] . "</td>\n";
                print "	 <td  class='row" . $fila . "'>" . $Even ['email'] . "</td>\n";
                print "	 <td  class='row" . $fila . "'>" . $telefono . "</td>\n";
                print "	 <td  class='row" . $fila . "'>";
                if ($Even ['web'] != '') {
                    print "<a href='http://" . $Even ['web'] . "' target='_new'>" . $Even ['web'] . "</a>";
                } else {
                    print "&nbsp;";
                }
                print "</td>\n"; // TARGET="_nTARGET="_new"ew"


                print "	 <td  class='row" . $fila . "'>" . $Even ['cuit'] . "</td>\n";
                print "	 <td  class='row" . $fila . "'>" . $Even ['tipo_responsable'] ."</td>\n";
                print "	</tr>\n";
            }
        }
        print "  </table>\n";
    }

}

// Fin Clase
?>