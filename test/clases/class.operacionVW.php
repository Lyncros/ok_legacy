<?php
include_once ('generic_class/class.VW.php');
//include_once("generic_class/class.menu.php");
include_once("clases/class.operacionBSN.php");
include_once("clases/class.operacion.php");
include_once("clases/class.propiedadVW.php");
include_once("inc/funciones.inc");
include_once ("clases/class.loginwebuserBSN.php");

class OperacionVW extends VW {

	protected $clase = "Operacion";
	protected $operacion;
	protected $nombreId="Id_oper";

	public function __construct($_operacion=0) {
		OperacionVW::creaObjeto();
		if($_operacion instanceof Operacion ) {
			OperacionVW::seteaVW($_operacion);
		}
		if (is_numeric($_operacion)) {
			if($_operacion!=0) {
				OperacionVW::cargaVW($_operacion);
			}
		}
            $conf = CargaConfiguracion::getInstance();
            $timezone = $conf->leeParametro('timezone');
            date_default_timezone_set($timezone);

	}

	public function setIdPropiedad($_prop) {
		$this->operacion->setId_prop($_prop);
		$this->arrayForm['id_prop']=$_prop;
	}

	public function getIdPropiedad() {
		return $this->operacion->getId_prop();
	}


	public function cargaDatosVW() {
		$propVW = new PropiedadVW($this->arrayForm['id_prop']);
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_foto.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "function submitForm(){\n";
                print "    window.open('','ventanaOperacion','width=300,height=200');\n";
		print "}\n";
                
		print "function muestraCargaDataOper(){\n";
		print "   document.getElementById('cargaDataOper').style.display='block';\n";
		print "}\n";
		print "</script>\n";

		print "<div id='cargaDataOper' name='cargaDataOper' style='display:none;'>";
		print "<form action='carga_Operacion.php' name='carga' id='carga' target='ventanaOperacion' method='post' onSubmit='javascript: if(ValidaOperacion(this)){submitForm();};'>\n";

		print "<div class='cd_celda_titulo'>Carga de estado de la Operacion</div>\n";
		
		print "<table width='100%' align='center' cellspacing='10'>\n";

		print "<tr><td class='cd_celda_texto' width='50%'>Fecha <span class='obligatorio'>&nbsp;&bull;</span><br />";
		if($this->arrayForm['cfecha'] != ''){
			$fecha = $this->arrayForm['cfecha'];
		}else{
			$fecha = date('d-m-Y');
		}
		print "<input class='campos' type='text' name='cfecha' id='cfecha' value='".	$fecha ."' maxlength='10' size='80' style='width: 90%;'></td>\n";
		print "	<script type=\"text/javascript\">\n";
		print "jQuery(\"#cfecha\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\", defaultDate: '0w', selectDefaultDate: true });\n";
		print "</script>\n";

		print "<td class='cd_celda_texto' width='50%'>Operacion <span class='obligatorio'>&nbsp;&bull;</span><br />";
		armaTipoOperacion($this->arrayForm['operacion']);
		print "</td></tr>\n";

		print "<tr>";
		print "<td class='cd_celda_texto' colspan='2'>Comentario<br />";
		print "<input class='campos' type='text' name='comentario' id='comentario' value='".	$this->arrayForm['comentario'] ."' maxlength='250' size='80'></td></tr>\n";

		print "<input type='hidden' name='id_oper' id='id_oper' value='".$this->arrayForm['id_oper'] ."'>\n";
		print "<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop'] ."'>\n";
		print "<input type='hidden' name='intervino' id='intervino' value='".$_SESSION['UserId']."'>\n";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "<br>";
		print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</form>\n";
		print "</ div>";
	}


	/**    OK
	 * Muestra una tabla con los datos de los operacions y una barra de herramientas o menu
	 * conde se despliegan las opciones ingresables para cada item
	 *
	 */
	public function vistaTablaVW($id_prop=0) {
		$propVW = new PropiedadVW($id_prop);
		$fila=0;
		$usuario = new LoginwebuserBSN();

		if($id_prop==0 || is_nan($id_prop)) {
			echo "Debe seleccionar un Propiedad para poder verificar sus Fotos";
		} else {
			print "<form name='lista' method='POST' action='respondeMenu.php'>";

			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(nameForm,opcion,id){\n";
			print "     document.forms.lista.id_oper.value=id;\n";
			print "   	submitform(nameForm,opcion);\n";
			print "}\n";
                        
			print "function modifica(prop,oper){\n";
			print "     document.getElementById('opcion').value='m';\n";
			print "     document.location.href='carga_Operacion.php?i='+prop+'&o='+oper;\n";
			print "}\n";
			print "function borra(prop,oper){\n";
			print "   window.open('carga_Operacion.php?i='+prop+'&o='+oper+'&b=b', 'ventanaOperacion', 'menubar=1,resizable=1,width=950,height=550');\n";
                        
			print "}\n";

			print "</script>\n";

			print "<div class='pg_titulo'>Estados de Operacion de la Propiedad</div>\n";
			$propVW->muestraDomicilio();
			
		$arrayTools=array(array('Nuevo','images/briefcase--plus.png','muestraCargaDataOper()'));
		$menu=new Menu();
		$menu->barraHerramientas($arrayTools);

			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
			print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
			print "     <td class='cd_lista_titulo'>Fecha Operacion</td>\n";
			print "     <td class='cd_lista_titulo'>Estado Operacion</td>\n";
			print "     <td class='cd_lista_titulo'>Intervino</td>\n";
			print "     <td class='cd_lista_titulo'>Comentario</td>\n";
			print "	  </tr>\n";

			$evenBSN=new OperacionBSN();

			$arrayEven=$evenBSN->cargaColeccionFormByPropiedad($id_prop);

			if(sizeof($arrayEven)==0) {
				print "No existen datos para mostrar";
			} else {
				foreach ($arrayEven as $Even) {
					if($fila==0) {
						$fila=1;
					} else {
						$fila=0;
					}
					$usuario->cargaById($Even['intervino']);
					$operador=$usuario->getObjeto()->getApellido().", ".$usuario->getObjeto()->getNombre();
                                        if(trim($operador==', ')){
                                            $operador='-';
                                        }
					print "<tr>\n";
					print "	 <td class='row".$fila."'>";
					print "    <a href=\"javascript:borra($id_prop,".$Even['id_oper'].");\" border=0>";
					print "       <img src='images/briefcase--minus.png' alt='Borrar' title='Borrar' border=0></a>";
					print "  </td>\n";
					print "	 <td class='row".$fila."'>".$Even['cfecha']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['operacion']."</td>\n";
//					print "	 <td class='row".$fila."'>".$Even['intervino']."</td>\n";
					print "	 <td class='row".$fila."'>".$operador."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['comentario']."</td>\n";
					print "	</tr>\n";
				}
			}

			print "  </table>\n";
			print "<input type='hidden' name='id_oper' id='id_oper' value=''>";
			print "<input type='hidden' name='id_prop' id='id_prop' value='".$id_prop."'>";
			print "<input type='hidden' name='opcion' id='opcion' value=''>";
			print "</form>";
		}
	}


} // fin clase


?>