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
include_once("clases/class.tipoCategoriaBSN.php");
include_once("clases/class.tipoAsignacionBSN.php");
include_once("clases/class.tipoContactoBSN.php");
include_once("clases/class.tipoInteresesnewsBSN.php");
include_once("clases/class.rangoCapacidadCompraBSN.php");
include_once("clases/class.ingresoclienteBSN.php");
include_once("clases/class.relacion.php");
include_once("clases/class.eventoVW.php");
include_once("clases/class.asuntoVW.php");
include_once("clases/class.propiedadVW.php");

class ClienteVW extends LoginVW {

    protected $clase = "Cliente";
    protected $cliente;
    protected $nombreId = "Id_cli";
    private $perfwebuser = array();
    private $estwebuser = array();
    private $infowebuser = array();
//	private $arrayForm;
    private $opcionMenu = '';
    private $arrayTemplateCampos;
    private $asuntoActivo;

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
    public function cargaDatosRapidaVW($timestamp, $campo = '') {

        print "<script type='text/javascript' >\n";
        print "function domicilio(id,div){\n";
        print "   window.open('carga_Domicilio.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
        print "}\n";
        print "function familiares(id,div){\n";
        print "   window.open('carga_Familiares.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=1000,height=550');\n";
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

        if ($operacion == 'n') {
            $titulo = "Carga de ";
            $perfilVisible = 'block';
        } else {
            $titulo = "Editar ";
            $perfilVisible = 'none';
        }

        print "<div id=\"buscadorCliente\"><div style=\"float: left;\">Buscador de Clientes\n";
        print "  <input type='text' name='buscaCli' id='buscaCli' value=\"Ingrese para buscar nombre, apellido, email, telefono, dirección...\" onfocus=\"if(this.value == this.defaultValue){ this.value = ''; this.style.color = '#333';this.style.fontStyle='normal';}\" onblur=\"if(this.value == '') {this.value = this.defaultValue; this.style.color='#CCC';this.style.fontStyle='italic';}\" /></div>\n";
        print "  <div style=\"float: right;border-left: thick solid #FFF; padding: 2px 5px; width:215px;\"><a href=\"cargarapida_cliente.php?c=\"><img src=\"images/icono_ir.png\" width=\"22\" height=\"22\" /> Nuevo Cliente</a></div></div>\n";

        print "   <div style=\"float:left; width:750px;\">\n";
        print "      <div id=\"tituCliente\">" . $titulo . " Datos del contacto y perfil</div>\n";
        print "  <div id=\"datosCli\">\n";
        print "    <div id=\"datos\">\n";
        print "     <div id=\"subtituCliente\">Datos del contacto</div>\n";

        print "<form name='f1' method='post' action='cargarapida_cliente.php' ";
        if ($this->arrayForm['id_cli'] == 0) {
            print "onsubmit='return valida();'";
        }
        print " >\n";
        print "<input type='hidden' name='operacion' id='operacion' value='" . $operacion . "'>\n";
        print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm['id_cli'] . "'>\n";
        // Quitar esta linea cuando se habilite la carga de usuario para el cliente
        print "<input class='campos' type='hidden' name='usuario' id='usuario' value='$timestamp' maxlength='250' size='80'>";

        //print "<div class='pg_titulo'>" . $titulo . "</div>\n";
//        print "	<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
//        print "<tr><td align='center'>";
//        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
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

        print "      <div>\n";
        print "        <div class=\"col2\">\n";
        print "          <div class=\"nombreCampo\">Nombre &bull;</div>\n";
        print "          <div class=\"datoCampo\"><input type='text' name='nombre' id='nombre' value='" . $this->arrayForm ['nombre'] . "' maxlength='250' /></div>\n";
        print "        </div>\n";
        print "        <div class=\"col2\">\n";
        print "          <div class=\"nombreCampo\">Apellido &bull;</div>\n";
        print "          <div class=\"datoCampo\"><input type='text' name='apellido' id='apellido' value='" . $this->arrayForm ['apellido'] . "' maxlength='250' /></div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";

        print "      <div>\n";
        print "        <div class=\"col2\">\n";
        print "          <div class=\"nombreCampo\">Telefonos &bull; <input type='button' title='Add' value='+' onclick=\"javascript: addField('t',0);\" /></div>\n";
        print "          <div id='div_tel'>\n";
        print "          </div>\n";
        print "        </div>\n";
        print "        <div class=\"col2\">\n";
        print "          <div class=\"nombreCampo\">Mail &bull; <input type='button' title='Add' value='+' onclick=\"javascript: addField('m',0);\" /></div>\n";
        print "          <div id='div_mail'> \n";
        print "          </div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";

        if($operacion=='n'){
            print "<script type='text/javascript'>\n";
            print "addField('t',0);\n";
            print "addField('m',0);\n";
            print "</script>\n";
        }
        print "      <div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Sexo</div>\n";
        print "          <div>\n";
        print "             Masculino <input type=\"radio\" name=\"sexo\" value=\"1\" ";
        if($this->arrayForm['sexo']==1){
            print "checked";
        }
        print "> - Femenino <input type=\"radio\" name=\"sexo\" value=\"2\" ";
        if($this->arrayForm['sexo']==2){
            print "checked";
        }
        print ">\n";
        print "          </div>\n";
        print "        </div>\n";

        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Tipo de Cliente &bull;</div>\n";
        print "          <div>\n";
        $tipoCont = TipocontactoBSN::getInstance();
        $tipoCont->comboParametros($this->arrayForm ['tipocont'], 0, 'tipocont', 'datoCampo');
        print "          </div>\n";
        print "        </div>\n";

        print "      <div>\n";
        $inccliVW = new IngresoclienteVW($this->arrayForm['id_cli'], 'C');
        $inccliVW->cargaDatosVW($operacion);


        print "    <div id=\"masInfo\" onclick=\"javascript: visible('otrosDatos');if(document.getElementById('otrosDatos').style.display == 'none'){ this.innerHTML ='+ info'; }else{ this.innerHTML ='cerrar'; }\">+ info</div>\n";
        print "    <div id=\"clearfix\"></div>\n";
        print "        </div>\n";

        print "<div name='div_info' id='otrosDatos' style='display:none;'>";

        print "      <div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Nacionalidad</div>\n";
        print "          <div>\n";
        $pais = PaisBSN::getInstance();
        $pais->comboParametros($this->arrayForm ['id_pais'], 0, 'id_pais', 'datoCampo');
        print "         </div>\n";
        print "        </div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Fecha de Nacimiento</div>\n";
        print "          <div class=\"datoCampo\"><input type='text' name='fecha_nac' id='fecha_nac' value='" . $this->arrayForm['fecha_nac'] . "' maxlength='10' /></div>\n";
        print "        </div>\n";
        print "         <script type=\"text/javascript\">\n";
        print "         jQuery(\"#fecha_nac\").datepicker({changeMonth: true,changeYear: true, regional: 'es',showButtonPanel: true,dateFormat: \"dd-mm-yy\",yearRange: \"1910:2012\"});";
        print "         </script>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Estado Civil</div>\n";
        print "          <div>\n";
        $estciv = EstadocivilBSN::getInstance();
        $estciv->comboParametros($this->arrayForm ['id_estciv'], 0, 'id_estciv', 'datoCampo');
        print "             </div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";
        print "      <div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Tipo Documento</div>\n";
        print "          <div>" . armaTipoDocumento($this->arrayForm ['tipo_doc'], 'datoCampo') . "</div>\n";
        print "        </div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Nro. Documento</div>\n";
        print "          <div class=\"datoCampo\"><input type='text' name='nro_doc' id='nro_doc' value='" . $this->arrayForm ['nro_doc'] . "' maxlength='20' /></div>\n";
        print "        </div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Empresa</div>\n";
        print "          <div class=\"datoCampo\"><input type='text' name='empresa' id='empresa' value='" . $this->arrayForm ['empresa'] . "' maxlength='50' /></div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";

        print "      <div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Cargo</div>\n";
        print "          <div>\n";
        $tipopos = PosicionempresaBSN::getInstance();
        $tipopos->comboParametros($this->arrayForm ['id_posemp'], 0, 'id_posemp', 'datoCampo');
        print "              </div>\n";
        print "        </div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Tipo IVA</div>\n";
        print "          <div>" . armaTipoResponsable($this->arrayForm ['tipo_responsable'], 'datoCampo') . "</div>\n";
        print "        </div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">CUIT</div>\n";
        print "          <div class=\"datoCampo\"><input type='text' name='cuit' id='cuit' value='" . $this->arrayForm ['cuit'] . "' maxlength='20' /></div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";

        print "      <div>\n";
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">Grupo Familiar</div>\n";
        print "          <div class=\"datoCampo\"><input type='text' name='grupofam' id='grupofam' value='" . $this->arrayForm ['grupofam'] . "' maxlength='2' /></div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";

        print "      <div style=\"margin-bottom:10px;\">\n";
        print "        <div>\n";
        print "          <div class=\"nombreCampo\">Familiares&nbsp;<input type='button' title='Add' value='+' onclick=\"javascript: addField('p',0);\"></div>\n";
        print "          <div  name='div_fam' id='div_fam'></div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";

        print "      <div style=\"margin-bottom:10px;\">\n";
        print "        <div>\n";
        print "          <div class=\"nombreCampo\">Domicilios&nbsp;<input type='button' title='Add' value='+' onclick=\"javascript: addField('d',0);\"></div>\n";
        print "          <div  name='div_dom' id='div_dom'></div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";
        
/*
        print "<div name='div_dom' id='div_dom'>";
        $domVW = new DomicilioVW();
        $domVW->vistaTablaVW('C', $this->arrayForm['id_cli'], 'v');
        print "</div>";
        print "<br /><input class='boton_form' type='button' value='Cargar Domicilio' onclick=\"javascript:domicilio(" . $this->arrayForm ['id_cli'] . ",'div_dom'); \"><br />\n";
        print "     <div>\n";
        print "          <div class=\"nombreCampo\">Comentarios</div>\n";
        print "          <div><textarea rows='6' class='datoCampo' name='observacion' id='observacion'>" . $this->arrayForm ['observacion'] . "</textarea></div>\n";
        print "     </div>\n";
*/
        print "</div>";




        print "  <div id=\"subtituCliente\" style=\"display: block; cursor: pointer;\" onclick=\"javascript: visible('datosPerfil');if(document.getElementById('datosPerfil').style.display == 'none'){ document.getElementById('flechita').src ='images/fderecha.png'; }else{ document.getElementById('flechita').src ='images/fabajo.png'; }\"><img src=\"images/fderecha.png\" id=\"flechita\" /> Perfil</div>\n";
        print "  <div id=\"perfilCli\">\n";
        print "    <div id=\"datosPerfil\" style=\"display: $perfilVisible;\">\n";

        print "      <div>\n";
        print "        <div class=\"col4\">\n";
        print "          <div class=\"nombreCampo\">Categoria de Cliente</div>\n";
        print "          <div>\n";
        $tipoCat = TipocategoriaBSN::getInstance();
        $tipoCat->comboParametros($this->arrayForm ['categoria'], 0, 'categoria', 'datoCampo');
        print "         </div>\n";
        print "        </div>\n";
        print "        <div class=\"col4\">\n";
        print "          <div class=\"nombreCampo\">Monto de Inversi&oacute;n</div>\n";
        if ($this->arrayForm ['capcompra'] == '') {
            $capcompra = 0;
        } else {
            $capcompra = $this->arrayForm ['capcompra'];
        }
        print "          <div class=\"datoCampo\"><input type='text' id='capcompra' name='capcompra' value='" . $capcompra . "'></div>\n";
        print "        </div>\n";
//        print "        <div id=\"clearfix\"></div>\n";
//        print "      </div>\n";
//        print "      <div>\n";

//        print "      <div>\n";
        print "        <div class=\"col4\">\n";
        print "          <div class=\"nombreCampo\">Vínculo con O'Keefe</div>\n";
        print "          <div>\n";
        $tipoAsig = TipoasignacionBSN::getInstance();
        $tipoAsig->comboParametros($this->arrayForm ['asignacion'], 0, 'asignacion', 'datoCampo');
        print "         </div>\n";
        print "        </div>\n";
        print "        <div class=\"col4\">\n";
        print "          <div class=\"nombreCampo\">Contacto público</div>\n";
        print "          <div>\n";
        print "<input type='checkbox' name='publico' id='publico' ";
        if ($this->arrayForm['publico'] == 0 && $this->arrayForm['id_cli'] != '') {
            print ">";
        } else {
            print " checked >";
        }
        print "         </div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";
        print "      <div style=\"margin-bottom:10px;\">\n";
        print "          <div class=\"nombreCampo\">Seleccione areas de interes para recibir NOTICIAS.</div>\n";
        if ($this->arrayForm['news'] == 0 && $this->arrayForm['id_cli'] != '') {
            $valor = 0;
        } else {
            $valor = $this->arrayForm['news'];
        }
        $tipoInteres = TipointeresesnewsBSN::getInstance();
        print "          <div style=\"width: 100%\">" . $tipoInteres->checkboxParametros($valor) . "</div>\n";
        print "      </div>\n";

        print "      <div style=\"margin-bottom:10px;\">\n";
        print "        <div>\n";
        print "          <div class=\"nombreCampo\">Vendedor responsable&nbsp;<input type='button' title='Add' value='+' onclick=\"javascript: addField('r',0);\"></div>\n";
        print "          <div name='div_ejec' id='div_ejec'>\n";
        print "         </div>\n";
        print "        </div>\n";
        print "        <div id=\"clearfix\"></div>\n";
        print "      </div>\n";
        print "    </div>\n";
        print "  </div>\n";
//        print "  <div id=\"clearfix\"></div>\n";
//        print "</div>\n";

//        print "</div>";

        print "        <div id=\"clearfix\"></div>\n";

        if ($operacion == 'n') {
            print "      <div>\n";
            print "        <div>\n";
            print "<div id=\"buscadorCliente\"><div style=\"float:left;\">Propiedad\n";
            print "  <input type='text' style=\"width: 425px;\" size='150' name='buscaProp' id='buscaProp' value=\"Ingrese para buscar ID, dirección, localidad ...\" onfocus=\"if(this.value == this.defaultValue){ this.value = ''; this.style.color = '#333';this.style.fontStyle='normal';}\" onblur=\"if(this.value == '') {this.value = this.defaultValue; this.style.color='#CCC';this.style.fontStyle='italic';}\" /></div>\n";
            print "  <div style=\"float:right;border-left: thick solid #FFF; padding: 2px 5px; width:200px;\"><a href=\"javascript: visible('menuBuscadorPropiedad');\"><img src=\"images/icono_ir.png\" width=\"22\" height=\"22\" /> Buscador de Propiedades</a></div></div>\n";
            print "<input type='hidden' size='50' name='aux_prop' id='aux_prop'>";
            print "         </div>\n";
            print "        </div>\n";
            print "        <div id=\"clearfix\"></div>\n";
            print "        <div>\n";

            $propVW = new PropiedadVW();
            $propVW->armaBuscadorPropHorizontal();
            print "        </div>\n";
        }


        print "<br>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm ['id_cli'] . "'>";
        print "<input type='hidden' name='activa' id='activa' value='" . $this->arrayForm ['activa'] . "'>";
        print "<input type='hidden' name='cpo' id='cpo' value='" . $campo . "'>";
        print "    <div>\n";
        print "     <div style=\"float:left;\"><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></div>\n";
        print "     <div style=\"float: right;\"><input class='boton_form' type='submit' value='Enviar' style=\"width:90px;\" /></div>\n";
        print "    </div>\n";
        print "    <div id=\"clearfix\"></div>\n";

        print "</form>\n";
        print "     </div>\n";

        if ($operacion != 'n') {
            print "<form name='f2' method='post' action='carga_AsuntoCliente.php'>\n";

            print "      <div>\n";
            print "        <div name='div_tar' id='div_tar'>\n";

            $astoVW = new AsuntoVW();
            $astoVW->vistaTablaVW('C', $this->arrayForm['id_cli'], 'l');
            print "        </div>\n";
            print "        <div id=\"clearfix\"></div>\n";
            print "      <div>\n";
            print "        <div>\n";

            $ingBSN=new IngresoclienteBSN();
            $ingBEAN=$ingBSN->cargaByCliente($this->arrayForm ['id_cli']);

            print "<input type='hidden' name='opcion' id='opcion' value=''>";
            print "<input type='hidden' name='id_cli' id='id_cli' value='" . $this->arrayForm ['id_cli'] . "'>";
            print "<input type='hidden' name='activa' id='activa' value='" . $this->arrayForm ['activa'] . "'>";
            print "<input type='hidden' name='cpo' id='cpo' value='" . $campo . "'>";
            if($ingBEAN->getId_fcontacto()==''){
                $ingBEAN->setId_fcontacto(1);
            }
            print "<input type='hidden' name='id_fcontacto' id='id_fcontacto' value='" . $ingBEAN->getId_fcontacto() . "'>";

            print "</form>\n";
            
// ************************************** INICIO DATOS DE ULTIMAS TAREAS y CLIENTE ************************************************************************
            print "     <div style=\"margin-top: 10px;\">\n";
            print "        <div name='div_eventos' id='div_eventos'>\n";
            
            print "          <div class=\"tituListado\">ACTIVIDADES</div>\n";
            print "          <div class=\"nombreCampo\">Activas</div>\n";
            $evVW = new EventoVW();
            $evVW->vistaUltimosEventos($_SESSION['UserId'], 1);
//            print "        </div>\n";
//            print "        <div name='div_eventos' id='div_eventos'>\n";
            print "          <div class=\"nombreCampo\">Cerradas</div>\n";
            $evVW = new EventoVW();
            $evVW->vistaUltimosEventos($_SESSION['UserId'], 0);
//            print "        </div>\n";
            print "     </div>\n";
            
            print "        <div name='div_eventos' id='div_atendidos'>\n";
            print "          <div class=\"tituListado\">Ultimos Clientes Atendidos</div>\n";
            $this->vistaUltimosClientes($_SESSION['UserId']);
//            print "        </div>\n";
            print "     </div>\n";
            
            
// ************************************** FIN DATOS DE ULTIMAS TAREAS y CLIENTE ************************************************************************
             
        }

//        print "<tr><td class='cd_celda_texto' width='15%'>Observaciones<span class='obligatorio'>&nbsp;&bull;</span></td>";
//        print "<td width='85%'><textarea rows='6' class='campos_area' name='observacion' id='observacion'>" . $this->arrayForm ['observacion'] . "</textarea></td></tr>\n";

        /*
          print "				<table>";
          print "					<tr>";
          print '						<td align ="center">Codigo de Verificaci�n <img src="'.$_codseg.'" alt="" width="150" height="50" /><br /><br />Ingrese el Codio de Verificaci�n de la Imagen: <input type="text" size="8" maxlength="8" name="code" id="code" value="" /></td>';
          print "					</tr>";
          print "             </table>";
         */

        print "</div>\n";
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


        print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='nombre' id='nombre' value='" . $this->arrayForm ['nombre'] . "' maxlength='250' size='80'></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Apellido<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='apellido' id='apellido' value='" . $this->arrayForm ['apellido'] . "' maxlength='250' size='80'></td></tr>\n";

//        $inccliVW=new IngresoclienteVW($this->arrayForm['id_cli'],'C');
//        $inccliVW->cargaDatosVW();


        print "<tr><td class='cd_celda_texto' width='15%'>Nacimiento</td>";
        print "<td width='85%'><input class='campos' type='text' name='fecha_nac' id='fecha_nac' value='" . $this->arrayForm['fecha_nac'] . "' maxlength='10' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "	<script type=\"text/javascript\">\n";
        print "jQuery(\"#fecha_nac\").datepicker({changeMonth: true,changeYear: true, regional: 'es',showButtonPanel: true,dateFormat: \"dd-mm-yy\"});";
//        print "jQuery(\"#fecha_nac\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\"});\n";
        print "</script>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Documento<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        armaTipoDocumento($this->arrayForm ['tipo_doc']);
        print "&nbsp;&nbsp;<input class='campos' type='text' name='nro_doc' id='nro_doc' value='" . $this->arrayForm ['nro_doc'] . "' maxlength='20' size='30'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Nacionalidad<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $pais = PaisBSN::getInstance();
        $pais->comboParametros($this->arrayForm ['id_pais'], 0, 'id_pais');
        print " </td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Estado Civil<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $estciv = EstadocivilBSN::getInstance();
        $estciv->comboParametros($this->arrayForm ['id_estciv'], 0, 'id_estciv');
        print " </td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Empresa<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='empresa' id='empresa' value='" . $this->arrayForm ['empresa'] . "' maxlength='50' size='30'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Posicion<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        $tipopos = PosicionempresaBSN::getInstance();
        $tipopos->comboParametros($this->arrayForm ['id_posemp'], 0, 'id_posemp');
        print " </td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo Responsable<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'>";
        armaTipoResponsable($this->arrayForm ['tipo_responsable']);
        print " </td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>CUIT<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='85%'><input class='campos' type='text' name='cuit' id='cuit' value='" . $this->arrayForm ['cuit'] . "' maxlength='20' size='30'></td></tr>\n";

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
        print "<td width='85%'><textarea rows='6' class='campos_area' name='observacion' id='observacion'>" . $this->arrayForm ['observacion'] . "</textarea></td></tr>\n";

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
        print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";

        print "</form>\n";
    }

    public function leeDatosVW() {
        if (isset($_POST['publico']) && $_POST['publico'] == "on") {
            $_POST['publico'] = 1;
        }else{
            $_POST['publico'] = 0;
        }
        if ((isset($_POST['NEWS']) && $_POST['NEWS'] == "on") || (isset($_POST['news']) && $_POST['news'] == "on")) {
            $_POST['NEWS'] = 1;
        } else {
            $_POST['publico'] = 0;
        }

//        $tiposInteres = TipointeresesnewsBSN::getInstance();
//        $_POST['news'] = $tiposInteres->leeCheckboxParametros($_POST);

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
        print "<form name='lista' method='POST' action='respondeMenu.php'>";

        print "<div id=\"tabs\" style='float:left;'>\n";
        print "    <ul>\n";
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
        print "<li><a href=\"#tabs-buscador\">Buscar</a></li>\n";
        print "</ul>\n";
        print "<div id=\"tabs-1\">\n";
//        $evenBSN = new ClienteBSN();
//        $arrayEven = $evenBSN->coleccionClientesFiltrados('AB', 0);
			$evenBSN = ClienteCache::getInstance();
			$arrayEven=$evenBSN->getClientesByFiltroApellido('AB', 'a');
			$this->despliegaTablaCache($arrayEven);
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
                    print "<div id='cliAcc'></div>\n";
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

    
    public function vistaUltimosClientes($id_user = 0) {

        $evBSN = new EventoBSN();
        $arrayDatos = $evBSN->cargaColeccionClientesByUsuario($id_user);
        $fila = 0;
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {
            $cliBSN= new ClienteBSN();
            $telBSN=new TelefonosBSN();
            foreach ($arrayDatos as $datosCont) {
                $cliBSN->cargaById($datosCont['id_cli']);
                $telefonos=$telBSN->listaTelefonosByCliente($datosCont['id_cli']);

                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "  <div class='row" . $fila . "'  style=\"width:100%; color: #02508F\">\n";

                print "	 <div id=\"asuntoFechaT\" >" . $datosCont['fecha_even'] . "</div>\n";
                print "	 <div id=\"asuntoTipoT\" >" . $cliBSN->getObjeto()->getNombre()." ".$cliBSN->getObjeto()->getApellido() . "</div>\n";
                print "	 <div id=\"asuntoAsuntoT\" >" . $telefonos . "</div>\n";
                print "   <div class=\"clearfix\"></div>\n";
                print "     </div>";
            }
        }
    }

    public function vistaUltimosClientesAjax($id_user = 0) {
        $strRet='';
        $evBSN = new EventoBSN();
        $arrayDatos = $evBSN->cargaColeccionClientesByUsuario($id_user);
        $fila = 0;
        if (sizeof($arrayDatos) == 0) {
            $strRet= "No existen datos para mostrar";
        } else {
            $cliBSN = new ClienteBSN();
            $telBSN = new TelefonosBSN();
            foreach ($arrayDatos as $datosCont) {
                $cliBSN->cargaById($datosCont['id_cli']);
                $telefonos = $telBSN->listaTelefonosByCliente($datosCont['id_cli']);
      
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                $onClick="javascript:document.getElementById('ID_CLI').value=".$datosCont['id_cli'].";cargaInfoCliente('v');";
                $strRet.="  <div class='rowVista" . $fila . "'  style=\"width:100%; color: #02508F\" onclick=\"".$onClick."\">\n";

                $strRet.="	 <div id=\"asuntoFechaT\" >" . substr($datosCont['fecha_even'],0,10) . "</div>\n";
                $strRet.="	 <div id=\"asuntoTipoT\" >" . $cliBSN->getObjeto()->getNombre() . " " . $cliBSN->getObjeto()->getApellido() . "</div>\n";
                $strRet.="	 <div id=\"asuntoAsuntoT\" >" . $telefonos . "</div>\n";
                $strRet.="   <div class=\"clearfix\"></div>\n";
                $strRet.="     </div>";
            }
        }
        return $strRet;
    }

    
    
    public function vistaClienteCMR() {
        $pathTemp = 'templates/tareas.html';
        $templ = file_get_contents($pathTemp);

        $templ = $this->rellenaDatosTemplate($templ);

        print $templ;
    }

    protected function rellenaDatosTemplate($template) {
        $this->defineTemplateCampos();
        $arrayCampos = array_keys($this->arrayTemplateCampos);
        foreach ($arrayCampos as $campo) {
            $dato = 'get' . strtoupper(substr($campo, 0, 1)) . substr($campo, 1);
//            echo $this->cliente->{$dato}();
            $template = str_replace($this->arrayTemplateCampos[$campo], $this->cliente->{$dato}(), $template);
        }
        $template = $this->rellenaDatosTels($template);
        $template = $this->rellenaDatosMail($template);
        $template = $this->rellenaDatosAsig($template);
        $template = $this->rellenaDatosCat($template);
        $template = $this->rellenaDatosTCont($template);
        $template = $this->rellenaDatosRelacion($template);
        $template = $this->rellenaDatosContactos($template);
        $template = $this->rellenaDatosPropiedadesAsociadas($template);
        $template = $this->rellenaDatosAsuntos($template);

        $template = $this->rellenaDatosProductos($template);
        return $template;
    }

    protected function rellenaDatosProductos($template) {
        $pathTemp = 'templates/vistaProductoAC.html';
        $templ = file_get_contents($pathTemp);

        $crm = new CrmbuscadorBSN();
        $arrayCrm = $crm->cargaById(20130212201659);//$this->asuntoActivo);
print_r($arrayCrm);
        $strPA = '';
        $strPC='';
        
        foreach ($arrayCrm as $data) {
            $arrProps=  explode(',', $data['adjuntos']);
            foreach ($arrProps as $props){
                $detProp=  explode(':', $props);
                if(intval($detProp[1])<10){
                    $strPA.=$this->rellenaDatosPropiedad($templ, $detProp[0]);
                }else{
                    $strPC.=$this->rellenaDatosPropiedad($templ, $detProp[0]);
                }
            }
        }
        $template = str_replace('{producto}', $strPA, $template);
        $template = str_replace('{detalleCierres}', $strPC, $template);
        return $template;
    }

    protected function rellenaDatosAsuntos($template) {
        $asBSN = new AsuntoBSN();
        $aRet = $asBSN->coleccionByClienteActivas($this->cliente->getId_cli());
        $strTemp = '';
        switch (sizeof($aRet)) {
            case 0:
                $this->asuntoActivo = 0;
                $strTemp = 'No hay registradas Actividades Activas';
                break;
            case 1:
                $retArr = $this->armaDataAsunto($aRet[0]);
                $strTemp = $retArr[1];
                $this->asuntoActivo = $retArr[0];
                break;
            default :
                $strTemp = "<select id='asunto' name='asunto'>";
                foreach ($aRet as $asunto) {
                    $retArr = $this->armaDataAsunto($asunto);
                    $this->asuntoActivo = $retArr[0];
                    $strTemp.="<option value='" . $retArr[0] . "'>" . $retArr[1] . "</option>";
                }
                $strTemp.="</select>";
        }
        $template = str_replace('{cantConsultas}', sizeof($aRet), $template);
        $template = str_replace('{operacion}', $strTemp, $template);
        return $template;
    }

    protected function armaDataAsunto($asunto) {
        $strData = '';
        if (strpos($asunto['titulo'], 'Busqueda propiedades') !== false) {
            $arrPartes = explode("  ", $asunto['titulo']);
            $strData.=$arrPartes[0] . " ";
            for ($pos = 1; $pos < sizeof($arrPartes); $pos++) {
                $strData.=$this->defineAsuntoTitulo($arrPartes[$pos]);
            }
        } else {
            $strData = $asunto['titulo'];
        }
        $arrRet = array();
        $arrRet[] = $asunto['id_asunto'];
        $arrRet[] = $asunto['estado'] . " - " . $strData;
        return $arrRet;
    }

    protected function defineAsuntoTitulo($parte) {
        //Busqueda propiedades  Tipo Prop:1  Operacion:Venta  Zona:30 
        $subPartes = explode(":", $parte);
        $strRet = $subPartes[0] . ":";
        switch ($subPartes[0]) {
            case 'Tipo Prop';
                $datos = explode(',', $subPartes[1]);
                $tpBSN = new Tipo_propBSN();
                foreach ($datos as $tpro) {
                    $tpBSN->cargaById($tpro);
                    $strRet.=$tpBSN->getObjeto()->getTipo_prop() . ", ";
                }
                $strRet = substr($strRet, 0, -2);
                break;
            case 'Operacion':
                $strRet.=$subPartes[1];
                break;
            case 'Zona':
                $datos = explode(',', $subPartes[1]);
                $zonaBSN = new ZonaBSN();
                foreach ($datos as $zona) {
                    $zonaBSN->cargaById($zona);
                    $strRet.=$zonaBSN->getObjeto()->getNombre_zona() . ", ";
                }
                $strRet = substr($strRet, 0, -2);
                break;
            case 'Localidad':
                $datos = explode(',', $subPartes[1]);
                $locBSN = new LocalidadBSN();
                foreach ($datos as $loca) {
                    $locBSN->cargaById($loca);
                    $strRet.=$locBSN->getObjeto()->getNombre_loca() . ", ";
                }
                $strRet = substr($strRet, 0, -2);
                break;
            case 'Emprendimiento':
                $datos = explode(',', $subPartes[1]);
                $empBSN = new EmprendimientoBSN();
                foreach ($datos as $emp) {
                    $empBSN->cargaById($emp);
                    $strRet.=$empBSN->getObjeto()->getNombre() . ", ";
                }
                $strRet = substr($strRet, 0, -2);
                break;
        }
        return $strRet . " ";
    }

    protected function rellenaDatosRelacion($template) {
        $rel = new RelacionBSN();
        $aRel = $rel->coleccionRelacionesUC(0, $this->cliente->getId_cli());
        $relacion = "( " . $aRel[0]['desc_rel'] . " ) " . $aRel[0]['desc_pc'];
        $template = str_replace('{ejecutivo}', $relacion, $template);
        return $template;
    }

    protected function rellenaDatosContactos($template) {
        $pathTemp = 'templates/vistaContactoReducida.html';
        $templ = file_get_contents($pathTemp);

        $cliAux = new ClienteBSN();
        $rel = new RelacionBSN();
        $aRel = $rel->cargaRelacionesCC($this->cliente->getId_cli());
        $strRel = array();
        $pos = 0;
        foreach ($aRel as $relacion) {
            $cliAux->cargaById($relacion['id_comp']);
            $strRel[$pos] = str_replace('{nomape}', $cliAux->getObjeto()->getNombre() . " " . $cliAux->getObjeto()->getApellido(), $templ);
            $strRel[$pos] = str_replace('{empresa}', $cliAux->getObjeto()->getEmpresa(), $strRel[$pos]);
            $strRel[$pos] = $this->rellenaDatosMail($strRel[$pos], $relacion['id_comp']);
            $strRel[$pos] = $this->rellenaDatosTels($strRel[$pos], $relacion['id_comp']);
            $pos++;
        }
        $htmlRelaciones = implode(' ', $strRel);
        $template = str_replace('{vistaContactoReducida}', $htmlRelaciones, $template);
        return $template;
    }

    protected function rellenaDatosPropiedadesAsociadas($template) {
        $pathTemp = 'templates/vistaProductoAsociado.html';
        $templ = file_get_contents($pathTemp);

//        $propBSN=new PropiedadBSN();
//        $tipoProp = new Tipo_propBSN();
        $rel = new RelacionBSN();
        $aRel = $rel->cargaRelacionesPropiedadByCliente($this->cliente->getId_cli());

        $strRel = array();
        $pos = 0;
        foreach ($aRel as $productos) {
//            $propBSN->cargaById($productos['id_comp']);
//            $tipoProp->cargaById($propBSN->getObjeto()->getId_tipo_prop());
//            $strRel[$pos]=  str_replace('{relacion}',$productos['desc_rel'] , $templ);
//            $strRel[$pos]=  str_replace('{direccion}',$productos['desc_comp'] , $strRel[$pos]);
//            $strRel[$pos]=  str_replace('{tipo_prop}',$tipoProp->getObjeto()->getTipo_prop() , $strRel[$pos]);

            $strRel[$pos] = $this->rellenaDatosPropiedad($templ, $productos['id_comp']);
            $pos++;
        }
        $htmlProductos = implode(' ', $strRel);
        $template = str_replace('{vistaProductoAsociado}', $htmlProductos, $template);
        return $template;
    }

    protected function rellenaDatosPropiedad($template, $id_prop) {
        $propBSN = new PropiedadBSN();
        $tipoProp = new Tipo_propBSN();
        $propBSN->cargaById($productos['id_comp']);
        $tipoProp->cargaById($propBSN->getObjeto()->getId_tipo_prop());
        $template = str_replace('{relacion}', $productos['desc_rel'], $template);
        $template = str_replace('{id_prop}', $id_prop, $template);
        $template = str_replace('{direccion}', $productos['desc_comp'], $template);
        $template = str_replace('{tipo_prop}', $tipoProp->getObjeto()->getTipo_prop(), $template);
        return $template;
    }

    protected function rellenaDatosAsig($template) {
        $asign = new TipoasignacionBSN();
        $template = str_replace('{asignacion}', $asign->getDescripcionById($this->cliente->getAsignacion()), $template);
        return $template;
    }

    protected function rellenaDatosCat($template) {
        $obj = new TipocategoriaBSN();
        $template = str_replace('{categoria}', $obj->getDescripcionById($this->cliente->getCategoria()), $template);
        return $template;
    }

    protected function rellenaDatosTCont($template) {
        $obj = new TipocontactoBSN();
        $template = str_replace('{contacto}', $obj->getDescripcionById($this->cliente->getTipocont()), $template);
        return $template;
    }

    protected function rellenaDatosMail($template, $cli = 0) {
        $mail = '';
        $medio = new MedioselectronicosBSN();
        if ($cli == 0) {
            $cli = $this->cliente->getId_cli();
        }
        $arraMed = $medio->coleccionByCliente($cli);
        foreach ($arraMed as $datos) {
            if ($datos[1] == 1) {
                $mail = $datos[2];
                break;
            }
        }
        $template = str_replace('{email}', $mail, $template);
        return $template;
    }

    protected function rellenaDatosTels($template, $cli = 0) {
        $tels = new TelefonosBSN();
        if ($cli == 0) {
            $cli = $this->cliente->getId_cli();
        }
        $arrTel = $tels->coleccionByCliente($cli);
        $telefono = $arrTel[0][2] . "-" . $arrTel[0][3] . "-" . $arrTel[0][4];
        $template = str_replace('{tipo}', $arrTel[0][1], $template);
        $template = str_replace('{telefono}', $telefono, $template);
        return $template;
    }

    protected function defineTemplateCampos() {
        $array['nombre'] = '{nombre}';
        $array['apellido'] = '{apellido}';
        $array['empresa'] = '{empresa}';
//        $array['asignacion']='{asignacion}';
//        $array['categoria']='{categoria}';
        $array['news'] = '{news}';
//        $array['tipocont']='{contacto}';
        $this->arrayTemplateCampos = $array;
    }

}

// Fin Clase
?>
