<?php

include_once('inc/funciones.inc');
include_once('clases/class.loginVW.php');
include_once('clases/class.cliente.php');
include_once('clases/class.auxiliaresPGDAO.php');
include_once('clases/class.clienteBSN.php');
include_once('clases/class.medioselectronicosVW.php');
include_once('clases/class.telefonosVW.php');
include_once('clases/class.domicilioVW.php');
include_once('clases/class.familiaresVW.php');
include_once("clases/class.perfilesBSN.php");
include_once("clases/class.formacontactoBSN.php");
include_once("clases/class.ingresoclienteVW.php");
include_once("clases/class.paisBSN.php");
include_once("clases/class.estadoCivilBSN.php");
include_once("clases/class.tipoPosicionempresaBSN.php");
include_once("clases/class.ingresoclienteBSN.php");
include_once("clases/class.relacion.php");
include_once("clases/class.eventoVW.php");
include_once("clases/class.perfilesBSN.php");


class ClienteVW extends LoginVW {

    protected $clase = "Cliente";
    protected $cliente;
    protected $nombreId = "Id_cli";
    private $perfwebuser = array();
    private $estwebuser = array();
    private $infowebuser = array();
//	private $arrayForm;
    private $opcionMenu = '';

    public function __construct($_login = "") {
        ClienteVW::creaObjeto();
        if ($_login instanceof Cliente) {
            ClienteVW::seteaVW($_login);
        }
        if (is_string($_login)) {
            if ($_login != "") {
                ClienteVW::cargaVW($_login);
            }
        }
        ClienteVW::cargaDefinicionForm();
    }

    /**
     * Arma la vista de datos para la carga o modificacion de datos de clientes
     * @param int $timestamp -> valor que identifiara al usuario
     * @param int $campo -> campo hidden que define a que campo se asignara el valor 
     */
    public function cargaDatosVW($timestamp, $campo = '') {

        print "<script type='text/javascript' >\n";
        print "function telefonos(id,div){\n";
        print "   window.open('carga_Telefonos.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function domicilio(id,div){\n";
        print "   window.open('carga_Domicilio.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function familiares(id,div){\n";
        print "   window.open('carga_Familiares.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function medios(id,div){\n";
        print "   window.open('carga_Medioselectronicos.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function relaciones(id,div){\n";
        print "   window.open('carga_RelacionCliente.php?c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function tareas(id,div){\n";
        print "   window.open('carga_Evento.php?c='+id+'&tc=C&id=0&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";

        if ($this->arrayForm['id_cli'] == 0 || $this->arrayForm['id_cli'] == '') {

            $this->arrayForm['id_cli'] = $timestamp;
            //	print "<script type='text/javascript' src='../../js/md5.js'></script>\n";

            print "function calculaMD5(elem) {\n";
            print "var pw = elem;\n";
            print "var enc = hex_md5(pw);\n";
            print "return (hex_md5(pw));\n";
            print "}\n";

            print "function encripta() {\n";
            //			print "var ep=calculaMD5(document.forms['f1'].elements['clave'].value);\n";
            //			print "var en=calculaMD5(document.forms['f1'].elements['password2'].value);\n";
            //			print "document.forms['f1'].elements['clave'].value = ep;\n";
            //			print "document.forms['f1'].elements['password2'].value = en;\n";

            print "}\n";

            print "function valida() {\n";
            print "var np=document.forms['f1'].elements['clave'].value;\n";
            print "var rp=document.forms['f1'].elements['password2'].value;\n";
            print "if (np == rp){\n";
            print "	encripta();\n";
            print "return (true);\n";
            print "} else {\n";
            print "	alert('Error al reingresar la nueva clave. Por favor reingrese los valores');\n";
            print "return (false);";
            print "}\n";
            print "}\n";

            $operacion = 'n';
            $visibilidad = "text";
        } else {
            $visibilidad = "hidden";
            $operacion = 'm';
        }
        print "</script>";

        print "<form name='f1' method='post' action='carga_cliente.php' ";
        if ($this->arrayForm['id_cli'] == 0) {
            print "onsubmit='return valida();'";
        }
        print " onfocus=\"listaTelefonos('C',$timestamp, 'div_tel');listaDomicilios('C',$timestamp, 'div_dom');\">\n";
        print "<input type='hidden' name='operacion' id='operacion' value='" . $operacion . "'>\n";
        print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm['id_cli'] . "'>\n";
        // Quitar esta linea cuando se habilite la carga de usuario para el cliente
        print "<input class='campos' type='hidden' name='usuario' id='usuario' value='$timestamp' maxlength='250' size='80'>";

        if ($operacion == 'n') {
            $titulo = "Carga de Clientes";
        } else {
            $titulo = "Editar Cliente";
        }
        print "<div class='pg_titulo'>" . $titulo . "</div>\n";
        print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td align='center'>";
        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
        /*
          print "<tr><td class='cd_celda_texto' width='15%'>Usuario<span class='obligatorio'>&nbsp;&bull;</span></td>";
          print "<td width='85%'>";
          if($visibilidad=="hidden") {
          print 						$this->arrayForm['usuario'];
          }//else{
          print "<input class='campos' type='$visibilidad' name='usuario' id='usuario' value='" . $this->arrayForm ['usuario'] . "' maxlength='250' size='80'>";
          //		}
          print "</td></tr>\n";
          if($visibilidad<>"hidden") {
          print "<tr><td colspan='2'>";
          print "<table wdth='100%'>";
          print "<tr><td class='cd_celda_texto' width='15%'>Clave<span class='obligatorio'>&nbsp;&bull;</span></td>";
          print "<td width='35%'><input class='campos' type='password' name='clave' id='clave' value='" . $this->arrayForm ['clave'] . "' maxlength='20' size='40'></td>\n";
          print "<td class='cd_celda_texto' width='15%'>Confirma<span class='obligatorio'>&nbsp;&bull;</span></td>";
          print "<td width='35%'><input class='campos' type='password' name='password2' id='password2' value='" . $this->arrayForm ['clave'] . "' maxlength='20' size='40'></td></tr>\n";
          print "</table>";
          print "</td></tr>\n";
          }
         */
        print "<style>\n";
        print ".errorForm{display: none; margin-left: 10px;}\n";       
        print ".error_show{color: red; margin-left: 10px;}\n";
        print "</style>\n";

        print " <script type=\"text/javascript\">\n";
        print "$(document).ready(function() {\n";
        print "$('#client_form').click(function(event){\n";
        print " var errores = 0;\n";
        print " if(!($('#nombre').length) || !($('#nombre').val().length))\n";
        print " {\n";
        print "     $('#nombre').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(1);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#nombre').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print " if(!($('#apellido').length) || !($('#apellido').val().length))\n";
        print " {\n";
        print "     $('#apellido').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(2);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#apellido').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        // print " if(!($('#nro_doc').length) || !($('#nro_doc').val().length))\n";
        // print " {\n";
        // print "     $('#nro_doc').next('span').removeClass('errorForm').addClass('error_show');\n";
        // print "     errores++;\n";
        // print "     console.log(3);\n";
        // print " }\n";
        // print " else\n";
        // print " {\n";
        // print "     $('#nro_doc').next('span').removeClass('error_show').addClass('errorForm');\n";
        // print " }\n";
        print " if($('#id_pais').val() == 0)\n";
        print " {\n";
        print "     $('#id_pais').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(4);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#id_pais').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print " if($('#id_estciv').val() == 0)\n";
        print " {\n";
        print "     $('#id_estciv').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(5);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#id_estciv').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print " if(!($('#empresa').length) || !($('#empresa').val().length))\n";
        print " {\n";
        print "     $('#empresa').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(6);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#empresa').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print " if($('#id_posemp').val() == 0)\n";
        print " {\n";
        print "     $('#id_posemp').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(7);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#id_posemp').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print " if(!($('#cuit').length) || !($('#cuit').val().length))\n";
        print " {\n";
        print "     $('#cuit').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(8);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#cuit').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print " if(!($('#grupofam').length) || !($('#grupofam').val().length))\n";
        print " {\n";
        print "     $('#grupofam').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(9);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#grupofam').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print " if(!($('#observacion').length) || !($('#observacion').val().length))\n";
        print " {\n";
        print "     $('#observacion').next('span').removeClass('errorForm').addClass('error_show');\n";
        print "     errores++;\n";
        print "     console.log(10);\n";
        print " }\n";
        print " else\n";
        print " {\n";
        print "     $('#observacion').next('span').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        if($operacion == 'n')
        {
            print " if($('#id_fingreso').val() == 0)\n";
            print " {\n";
            print "     $('#medioConc').removeClass('errorForm').addClass('error_show');\n";
            print "     errores++;\n";
            print " }\n";
            print " else\n";
            print " {\n";
            print "     $('#medioConc').removeClass('error_show').addClass('errorForm');\n";
            print " }\n";
            print " if($('#id_fcontacto').val() == 0)\n";
            print " {\n";
            print "     $('#medioCont').removeClass('errorForm').addClass('error_show');\n";
            print "     errores++;\n";
            print " }\n";
            print " else\n";
            print " {\n";
            print "     $('#medioCont').removeClass('error_show').addClass('errorForm');\n";
            print " }\n";
        } 
        print "if(errores > 0){\n";
        print "     $('#errorSubmit').removeClass('errorForm').addClass('error_show');\n";
        print "     event.preventDefault();\n";
        print "     alert('Hay que llenar los campos obligatorios.');\n";
        print " }\n";
        print " else{\n";
        print "     $('#errorSubmit').removeClass('error_show').addClass('errorForm');\n";
        print " }\n";
        print "});\n";
        print "});\n";
        print "</script>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='nombre' id='nombre' value='" . $this->arrayForm ['nombre'] . "' maxlength='250' size='80'><span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Apellido<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='apellido' id='apellido' value='" . $this->arrayForm ['apellido'] . "' maxlength='250' size='80'><span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

//        $inccliVW=new IngresoclienteVW($this->arrayForm['id_cli'],'C');
//        $inccliVW->cargaDatosVW();


        print "<tr><td class='cd_celda_texto' width='15%'>Nacimiento</td>";
        print "<td width='85%'><input class='campos' type='text' name='fecha_nac' id='fecha_nac' value='" . $this->arrayForm['fecha_nac'] . "' maxlength='10' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "	<script type=\"text/javascript\">\n";
        print "jQuery(\"#fecha_nac\").datepicker({changeMonth: true,changeYear: true, regional: 'es',showButtonPanel: true,dateFormat: \"dd-mm-yy\"});";
//        print "jQuery(\"#fecha_nac\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\"});\n";
        print "</script>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Documento<span class=''></span></td>";
        print "<td width='85%'>";
        armaTipoDocumento($this->arrayForm ['tipo_doc']);
        print "&nbsp;&nbsp;<input class='campos' type='text' name='nro_doc' id='nro_doc' value='" . $this->arrayForm ['nro_doc'] . "' maxlength='20' size='30'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Nacionalidad<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $pais = PaisBSN::getInstance();
        $pais->comboParametros($this->arrayForm ['id_pais'], 0, 'id_pais');
        print " <span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Estado Civil<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $estciv = EstadocivilBSN::getInstance();
        $estciv->comboParametros($this->arrayForm ['id_estciv'], 0, 'id_estciv');
        print " <span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Empresa<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='empresa' id='empresa' value='" . $this->arrayForm ['empresa'] . "' maxlength='50' size='30'><span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Posicion<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $tipopos = PosicionempresaBSN::getInstance();
        $tipopos->comboParametros($this->arrayForm ['id_posemp'], 0, 'id_posemp');
        print " <span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo Responsable<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        armaTipoResponsable($this->arrayForm ['tipo_responsable']);
        print " <span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>CUIT<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='cuit' id='cuit' value='" . $this->arrayForm ['cuit'] . "' maxlength='20' size='30'><span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

//        print "<tr><td class='cd_celda_texto' width='15%'>E-Mail<span class='obligatorio'>&nbsp;&bull;</span></td>";
//        print "<td width='85%'><input class='campos' type='text' name='email' id='email' value='" . $this->arrayForm ['email'] . "' maxlength='250' size='80'></td></tr>\n";


        print "<tr><td class='cd_celda_texto' width='15%'>Medios de Contacto</td><td>";
        print "<div name='div_med' id='div_med'>";
        $medVW = new MedioselectronicosVW();
        $medVW->vistaTablaVW('C', $this->arrayForm['id_cli'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Medio Contacto' onclick=\"javascript:medios(" . $this->arrayForm ['id_cli'] . ",'div_med'); \"><br />\n";
        print "</td></tr>";


        print "<tr><td class='cd_celda_texto' width='15%'>Domicilio</td><td>";
        print "<div name='div_dom' id='div_dom'>";
        $domVW = new DomicilioVW();
        $domVW->vistaTablaVW('C', $this->arrayForm['id_cli'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Domicilio' onclick=\"javascript:domicilio(" . $this->arrayForm ['id_cli'] . ",'div_dom'); \"><br />\n";
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Telefono</td><td>";
        print "<div name='div_tel' id='div_tel'>";
        $telVW = new TelefonosVW();
        $telVW->vistaTablaVW('C', $this->arrayForm['id_cli'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Telefono' onclick=\"javascript:telefonos(" . $this->arrayForm ['id_cli'] . ",'div_tel'); \"><br />\n";
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Grupo Familiar<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='grupofam' id='grupofam' value='" . $this->arrayForm ['grupofam'] . "' maxlength='50' size='30'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Familiares</td><td>";
        print "<div name='div_fam' id='div_fam'>";
        $famVW = new FamiliaresVW();
        $famVW->vistaTablaVW('C', $this->arrayForm['id_cli'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Familiares' onclick=\"javascript:familiares(" . $this->arrayForm ['id_cli'] . ",'div_fam'); \"><br />\n";
        print "</td></tr>";

        if ($operacion == 'n') {
            $ingCli = new IngresoclienteVW();
            $ingCli->cargaDatosVW();
        } else {
//            $ingCli = new IngresoclienteVW($timestamp);
        }
//        $ingCli->cargaDatosVW();

        print "<tr><td class='cd_celda_texto' width='15%'>Publico</td><td>";
        print "<input type='checkbox' name='publico' id='publico' ";
        if ($this->arrayForm['publico'] == 0 && $this->arrayForm['id_cli'] != '') {
            print ">";
        } else {
            print " checked >";
        }
        print "</td>\n";
        print "</tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Ejecutivo de Cuenta</td><td>";
        print "<div name='div_ejec' id='div_ejec'>";
        $relVW = new RelacionVW();
        $relVW->vistaRelacionesUsuarioCliente(0, $this->arrayForm['id_cli']);
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Relaciones' onclick=\"javascript:relaciones(" . $this->arrayForm ['id_cli'] . ",'div_ejec'); \"><br />\n";
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Registro de Tareas</td><td>";
        print "<div name='div_tar' id='div_tar'>";
        $evVW = new EventoVW();
        $evVW->vistaTablaVW('C',$this->arrayForm['id_cli'],'v');
//        $evVW->vistaEventoCliente(0, $this->arrayForm['id_cli']);
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Tareas' onclick=\"javascript:tareas(" . $this->arrayForm ['id_cli'] . ",'div_tar'); \"><br />\n";
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Observaciones<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><textarea rows='6' class='campos_area' name='observacion' id='observacion'>" . $this->arrayForm ['observacion'] . "</textarea><span class='errorForm'>Este campo es obligatorio</span></td></tr>\n";

        /*
          print "				<table>";
          print "					<tr>";
          print '						<td align ="center">Codigo de Verificaci�n <img src="'.$_codseg.'" alt="" width="150" height="50" /><br /><br />Ingrese el Codio de Verificaci�n de la Imagen: <input type="text" size="8" maxlength="8" name="code" id="code" value="" /></td>';
          print "					</tr>";
          print "             </table>";
         */

        print "<br>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm ['id_cli'] . "'>";
        print "<input type='hidden' name='activa' id='activa' value='" . $this->arrayForm ['activa'] . "'>";
        print "<input type='hidden' name='cpo' id='cpo' value='" . $campo . "'>";
        print "<tr><td colspan='2' align='right'><input id='client_form' class='boton_form' type='submit' value='Enviar'><br /><br /><span id='errorSubmit' class='errorForm'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";

        print "</form>\n";
    }

    
    public function leeDatosVW() {
        if ($_POST['publico'] == "on") {
            $_POST['publico'] = 1;
        }else{
            $_POST['publico'] = 0;
        }
        parent::leeDatosVW();
    }
    
    public function vistaDatos() {
        $telBSN = new TelefonosBSN();
        $medioBSN = new MedioselectronicosBSN();
        $famiBSN = new FamiliaresBSN();

        $paisBSN = PaisBSN::getInstance();
        $estvic = EstadocivilBSN::getInstance();
        $posemp = PosicionempresaBSN::getInstance();


        print "<div id=\"datosCli\">\n";
        //print "<ul>\n";
        print "<li class='tituloNombreCli'>" . $this->arrayForm ['apellido'] . ', ' . $this->arrayForm ['nombre'] . "</li>\n";

        print "<li class='tituloVistaCli'>Nacionalidad: <span class='detalleVistaCli'>" . $paisBSN->getDescripcionById($this->arrayForm['id_pais']) . "</span></li>\n";

        print "<li class='tituloVistaCli'>Nacimiento: <span class='detalleVistaCli'>" . $this->arrayForm['fecha_nac'] . "</span></li>\n";

        print "<li class='tituloVistaCli'>Documento: <span class='detalleVistaCli'>" . $this->arrayForm ['tipo_doc'] . ' ' . $this->arrayForm ['nro_doc'] . "</span></li>\n";

        print "<li class='tituloVistaCli'>Est. Civil: <span class='detalleVistaCli'>" . $estvic->getDescripcionById($this->arrayForm['id_estciv']) . "</span></li>\n";
        print "<li class='tituloVistaCli'>Gpo. Familiar: <span class='detalleVistaCli'>" . $this->arrayForm['grupofam'] . "</span></li>\n";
        $familia = $famiBSN->listaFamiliaresByCliente($this->arrayForm['id_cli']);
        print "<span class='detalleVistaCli'>" . $familia . "</span></li>\n";

        print "<li class='tituloVistaCli'>Empresa: <span class='detalleVistaCli'>" . $this->arrayForm['empresa'] . "</span></li>\n";
        print "<li class='tituloVistaCli'>Posicion: <span class='detalleVistaCli'>" . $posemp->getDescripcionById($this->arrayForm['id_posemp']) . "</span></li>\n";

        print "<li class='tituloVistaCli'>Tipo Responsable: <span class='detalleVistaCli'>" . $this->arrayForm ['tipo_responsable'] . "</li>\n";

        print "<li class='tituloVistaCli'>CUIT: <span class='detalleVistaCli'>" . $this->arrayForm ['cuit'] . "</li>\n";

//        print "<li class='tituloVistaCli'>E-Mail: <span class='detalleVistaCli'>" . $this->arrayForm ['email'] . "</li>\n";
        print "<li class='tituloVistaCli'>Medio de Contacto: ";
        $medioElec = $medioBSN->listaMedioselectronicosByCliente($this->arrayForm['id_cli']);
        print "<span class='detalleVistaCli'>" . $medioElec . "</span></li>\n";

        print "<li class='tituloVistaCli'>Domicilio: <span class='detalleVistaCli'>";
        $domVW = new DomicilioVW();
        $domVW->vistaTablaReducidaVW('C', $this->arrayForm['id_cli'], 'v');
        print "</li>\n";

        print "<li class='tituloVistaCli'>Telefono: ";
        $telefono = $telBSN->listaTelefonosByCliente($this->arrayForm['id_cli']);
        print "<span class='detalleVistaCli'>" . $telefono . "</span></li>\n";
        print "<li class='tituloVistaCli'>Observaciones: <span class='detalleVistaCli'>" . nl2br($this->arrayForm ['observacion']) . "</li>\n";
        //print "</ul>\n";
        print "</div>\n";
    }

    public function vistaTablaVW() {
        $perf = new PerfilesBSN();
        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.id_cli.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function filtro(opc){\n";
        print "  id=0;\n";
        print "  switch (opc){\n";
        print "    case 0:\n";
        print "     param='';\n";
        print "     document.getElementById('valorFiltro').value='';\n";
        print "     break;\n";
        print "    case 1:\n";
        print "     param=document.getElementById('valorFiltro').value;\n";
        print "     break;\n";
        print "    case 2:\n";
        print "     param='';\n";
        print "     id=document.getElementById('auxFiltro').value;\n";
        print "  }\n";
        print "  destino='vistaTablaBuscador'\n";
        print "  filtraClientes(param,id,destino);\n";
        print "}\n";
        print "function muestraDatos(opc){\n";
        print "  if(opc!=0){\n";
        print "     destino='vistaDatosCli'\n";
        print "     muestraCliente(opc,destino);\n";
        print "     destino2='vistaDatosRel'\n";
        print "     muestraRelacionCliente(opc,destino2);\n";
        print "  }\n";
        print "}\n";
        print "function showHideDesc(campo,imagen){\n";
        print "  if(document.getElementById(campo).style.display=='none'){\n";
        print "   document.getElementById(campo).style.display='block';\n";
        print "   document.getElementById(imagen).src='images/fabajo.png';\n";
        print "  }else{\n";
        print "   document.getElementById(campo).style.display='none';\n";
        print "   document.getElementById(imagen).src='images/fderecha.png';\n";
        print "  }\n";
        print "}\n";
        print "</script>\n";
        print "<div class='pg_titulo'>Listado de Clientes</div>\n";
        if($_SESSION['messageExito'])
        {
            print "<br><div>". $_SESSION['messageExito']. "</div>\n";
            unset($_SESSION['messageExito']);
        }
        print "<form name='lista' method='POST' action='respondeMenu.php'>";

        print "<div id=\"tabs\" style='float:left;'>\n";
        print "    <ul>\n";
        
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
			print "<li><a href=\"#tabs-1\">A-B</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=CD&pos=0\">C-D</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=EF&pos=0\">E-F</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=GH&pos=0\">G-H</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=IJK&pos=0\">I-J-K</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=LM&pos=0\">L-M</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=NO&pos=0\">N-0</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=PQR&pos=0\">P-Q-R</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=ST&pos=0\">S-T</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=UVW&pos=0\">U-V-W</a></li>\n";
			print "<li><a href=\"filtrarCliente.php?c=XYZ&pos=0\">X-Y-Z</a></li>\n";
		}
        print "<li><a href=\"#tabs-buscador\">Buscar</a></li>\n";
        print "</ul>\n";
        print "<div id=\"tabs-1\">\n";
//        $evenBSN = new ClienteBSN();
//        $arrayEven = $evenBSN->coleccionClientesFiltrados('AB', 0);
        if (strtoupper($perfGpo) != 'LECTURA' && strtoupper($perfGpo) != 'STAFF') {
			$evenBSN = ClienteCache::getInstance();
			$arrayEven=$evenBSN->getClientesByFiltroApellido('AB', 'a');
			$this->despliegaTablaCache($arrayEven);
		}
        print "    </div>\n";
        print "<div id=\"tabs-buscador\">\n";
        print "Filtrar por: <input class='campos' name='valorFiltro' id='valorFiltro' type='text' value='' style='width:300px;' >\n";
        print " <input type='button' value='Borrar filtro' onclick='filtro(0);' /> <input type='button' value='Filtrar' onclick='filtro(1);' />\n";
        print "<input name='auxFiltro' id='auxFiltro' type='hidden' value='' style='width:300px;' >\n";
        print "<div id='vistaTablaBuscador'>\n";
        print "</div>\n";
        print "</div>\n";
        print "</div>\n";
        //print "</div>\n";
        print "  <div id='vistaDatosCli' >\n";
        print "  </div>\n";
        print "  <div id='vistaDatosRel' >\n";
        print "  </div>\n";

        print "<input type='hidden' name='id_cli' id='id_cli' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "</form>";
    }

    public function despliegaTablaCache($arrayDatos) {
        $perf = new PerfilesBSN();

        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfwebuser = $perf->grupoPerfil($_SESSION['Userrole']);

        $fila = 0;
//        $telBSN = new TelefonosBSN();
//        $medioBSN = new MedioselectronicosBSN();

        print "<div id='vistaTablaCli' class='vistaTabla' >\n";
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {

            print "    <div>\n";
            print "     <div class=\"div_lista_titulo\" id='cliAcc' >&nbsp;</div>\n";
            //		print "     <td class='cd_lista_titulo'>Usuario</td>\n";
            print "     <div class=\"div_lista_titulo\" id='cliNom' >Apellido y Nombre</div>\n";
            print "     <div class=\"div_lista_titulo\" id='cliMail' >eMail</div>\n";
            print "     <div class=\"div_lista_titulo\" id='cliTel' >Telefono / Forma Contacto</div>\n";
            print "     <div id=\"clearfix\"></div>\n";
            print "	  </div>\n";
            $fila = 0;

            print "<div style='overflow:auto; clear:both; height:520px;'>\n";

            $arrayKey=  array_keys($arrayDatos);
            foreach ($arrayKey as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                $onclick = "onclick=\"javascript: muestraDatos(" . $Even . ")\" ";

//                $telefono = $telBSN->listaTelefonosByCliente($Even ['id_cli']);
//                $medioElec = $medioBSN->listaMedioselectronicosByCliente($Even ['id_cli']);
                print "<div class=\"div_lista_" . $fila . "\">\n";
                if (strtoupper($perfwebuser) != 'LECTURA' && strtoupper($perfwebuser) != 'STAFF') {
                    print "	 <div id='cliAcc'>";
                    print "    <a href='javascript:envia(\"lista\",62,\"" . $Even . "\");' border='0'>";
                    print "       <img src='images/user--pencil.png' alt='Editar' title='Editar' border=0></a>&nbsp;";
                    print "    <a href='javascript:envia(\"lista\",63,\"" . $Even . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                    print "       <img src='images/user--minus.png' alt='Borrar' title='Borrar' border=0></a>";
                    print "  </div>\n";
                } else {
                    print "<div id='cliAcc'>\n";
                    print "    <a href='javascript:envia(\"lista\",62,\"" . $Even . "\");' border='0'>";
                    print "       <img src='images/user--pencil.png' alt='Editar' title='Editar' border=0></a>&nbsp;";
                    print "  </div>\n";
                }
                //				print "	 <td  class='row" . $fila . "'>" . $Even ['usuario'] . "</td>\n";
                if ($arrayDatos[$Even][1] != "") {
//                    $nombre = $arrayDatos[$Even][1] . ", " . $arrayDatos[$Even][0];
                    $nombre = $arrayDatos[$Even][0] . ", " . $arrayDatos[$Even][1];
                } else {
                    $nombre = $arrayDatos[$Even][1];
//                    $nombre = $arrayDatos[$Even][0];
                }
                if ($arrayDatos[$Even][3] != "") {
                    $mail = $arrayDatos[$Even][3];
                } else {
                    $mail = '&nbsp;';
                }
                if ($arrayDatos[$Even][2] != "") {
                    $telef = $arrayDatos[$Even][2];
                } else {
                    $telef = '&nbsp;';
                }
                
                print "	 <div id='cliNom' $onclick >" . $nombre . "</div>\n";
//                print "	 <li class=\"li_lista_" . $fila . "\" id='cliMail' $onclick style='width:180px; overflow:hidden;'>" . $Even ['email'] . "</li>\n";
                print "	 <div id='cliMail' $onclick >" . $mail . "</div>\n";
                print "	 <div id='cliTel' $onclick >" . $telef . "</div>\n";
                print "    <div id=\"clearfix\"></div>\n";
                print "	</div>\n";
            }
            print "  </div>\n";
        }
        print "  </div>\n";
                        print "    <div id=\"clearfix\"></div>\n";

//        print "  <div id='vistaDatosCli' >\n";
//        print "  </div>\n";
    }
    
    
    public function despliegaTabla($arrayDatos) {
        $perf = new PerfilesBSN();

        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfwebuser = $perf->grupoPerfil($_SESSION['Userrole']);

        $fila = 0;
        $telBSN = new TelefonosBSN();
        $medioBSN = new MedioselectronicosBSN();

        print "<div id='vistaTablaCli' class='vistaTabla' >\n";
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {

            print "    <ul>\n";
            print "     <li class=\"li_lista_titulo\" id='cliAcc' >&nbsp;</li>\n";
            //		print "     <td class='cd_lista_titulo'>Usuario</td>\n";
            print "     <li class=\"li_lista_titulo\" id='cliNom' >Apellido y Nombre</li>\n";
            print "     <li class=\"li_lista_titulo\" id='cliMail' >eMail</li>\n";
            print "     <li class=\"li_lista_titulo\" id='cliTel' >Telefono / Forma Contacto</li>\n";
            print "	  </ul>\n";
            $fila = 0;

            print "<div style='overflow:auto; clear:both; height:520px;'>\n";

            foreach ($arrayDatos as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                $onclick = "onclick=\"javascript: muestraDatos(" . $Even ['id_cli'] . ")\" ";

                $telefono = $telBSN->listaTelefonosByCliente($Even ['id_cli']);
                $medioElec = $medioBSN->listaMedioselectronicosByCliente($Even ['id_cli']);
                print "<ul>\n";
                if (strtoupper($perfwebuser) != 'LECTURA' && strtoupper($perfwebuser) != 'STAFF') {
                    print "	 <li class=\"li_lista_" . $fila . "\"  id='cliAcc'>";
                    print "    <a href='javascript:envia(\"lista\",62,\"" . $Even ['id_cli'] . "\");' border='0'>";
                    print "       <img src='images/user--pencil.png' alt='Editar' title='Editar' border=0></a>&nbsp;&nbsp;";
                    print "    <a href='javascript:envia(\"lista\",63,\"" . $Even ['id_cli'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                    print "       <img src='images/user--minus.png' alt='Borrar' title='Borrar' border=0></a>";
                    print "  </li>\n";
                } else {
                    print "<li style='width:45px;'></li>\n";
                }
                //				print "	 <td  class='row" . $fila . "'>" . $Even ['usuario'] . "</td>\n";
                if ($Even ['apellido'] != "") {
                    $nombre = $Even ['apellido'] . ", " . $Even ['nombre'];
                } else {
                    $nombre = $Even ['nombre'];
                }
                print "	 <li class=\"li_lista_" . $fila . "\" id='cliNom' $onclick style='width:200px; overflow:hidden;'>" . $nombre . "</li>\n";
//                print "	 <li class=\"li_lista_" . $fila . "\" id='cliMail' $onclick style='width:180px; overflow:hidden;'>" . $Even ['email'] . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\" id='cliMail' $onclick style='width:180px; overflow:hidden;'>" . $medioElec . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\" id='cliTel' $onclick style='width:150px; overflow:hidden;'>" . substr($telefono, 0, 23) . "</li>\n";
                print "	</ul>\n";
            }
            print "  </div>\n";
        }
        print "  </div>\n";
//        print "  <div id='vistaDatosCli' >\n";
//        print "  </div>\n";
    }

    public function cargaAsignacionCliente() {
        $menu = new Menu ( );
        $menu->dibujaMenu('carga', 'opcion');

        print "<form name='carga' method='post' action='asignaPerfil.php' ";
        print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_titulo'>Asignacion de Perfil al Usuario</td></tr>\n";
        print "<tr><td align='center'>";
        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Usuario<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        print $this->arrayForm['usuario'];
        print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>" . $this->arrayForm ['nombre'] . " " . $this->arrayForm ['apellido'] . "</td></tr>\n";
        $perfilWU = new PerfileswebuserBSN('', $this->arrayForm['id_cli']);
        $perfil = $perfilWU->coleccionPerfilesUsuario($this->arrayForm['id_cli']);
        $perfilBSN = new PerfilesBSN();
        print "<tr><td class='cd_celda_texto' width='15%'>Perfil<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";

        $perfilBSN->comboPerfiles($perfil);
        print "</td></tr>\n";
        print "<br>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm ['id_cli'] . "'>";
        print "<input type='hidden' name='auxperfil' id='auxperfil' value='" . $perfil . "'>";
        print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";

        print "</form>\n";
    }

}

// Fin Clase
?>
