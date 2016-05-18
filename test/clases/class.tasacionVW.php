<?php
include_once ('generic_class/class.VW.php');
include_once("generic_class/class.menu.php");
include_once("clases/class.tasacionBSN.php");
include_once("clases/class.tasacion.php");
include_once("clases/class.propiedadVW.php");
include_once ("clases/class.loginwebuserBSN.php");
include_once("inc/funciones.inc");

class TasacionVW extends VW{
	protected $clase = "Tasacion";
	protected $tasacion;
	protected $nombreId="Id_tasacion";

	public function __construct($_tasacion=0){
		TasacionVW::creaObjeto();
		if($_tasacion instanceof Tasacion )	{
			TasacionVW::seteaVW($_tasacion);
		}
		if (is_numeric($_tasacion)){
			if($_tasacion!=0){
				TasacionVW::cargaVW($_tasacion);
			}
		}
	}


	public function setIdPropiedad($_prop){
		$this->tasacion->setId_prop($_prop);
		$this->arrayForm['id_prop']=$_prop;
	}

	public function getIdPropiedad(){
		return $this->tasacion->getId_prop();
	}


	public function cargaDatosVW(){
		$propVW = new PropiedadVW($this->arrayForm['id_prop']);		
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_foto.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "function muestracargaDataTasacion(){\n";
		print "   document.getElementById('cargaDataTasacion').style.display='block';\n";
		print "}\n";
		print "function submitForm(){\n";
		print "    window.open('','ventanaTasacion','width=300,height=200');\n";
		print "}\n";
		print "</script>\n";

		print "<div id='cargaDataTasacion' name='cargaDataTasacion' style='display:none;'>";
		
		print "<form action='carga_Tasacion.php' name='carga' id='carga' target='ventanaTasacion' method='post' onSubmit='javascript: if(ValidaTasacion(this)){submitForm();};'>\n";

		print "<div class='cd_celda_titulo'>Carga valores de Tasaci&oacute;n</div>\n";

		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr>";
		print "<td class='cd_celda_texto' width='15%'>Tasador</td>";
		print "<td width='85%'>";
		$usuario= new LoginwebuserBSN();
		$usuario->comboUsuarios($this->arrayForm['tasador'],'tasador');
		print "</td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";

	
		print "<tr><td class='cd_celda_texto' width='15%'>Fecha</td>";
		print "<td width='85%'><input class='campos' type='text' name='cfecha' id='cfecha' value='".	$this->arrayForm['cfecha'] ."' maxlength='10' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		print "	<script type=\"text/javascript\">\n";
		print "jQuery(\"#cfecha\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\"});\n";
		print "</script>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Valor</td>";
		print "<td width='85%'><input class='campos' type='text' name='valor' id='valor' value='".	$this->arrayForm['valor'] ."' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Estado</td>";
		print "<td width='85%'>";
		armaEstadoTasacion($this->arrayForm['estado']);
		print "</td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
	
		print "<tr>";
		print "<td class='cd_celda_texto' width='15%'>Observacion</td>";
		print "<td width='85%'><input class='campos' type='text' name='observacion' id='observacion' value='".	$this->arrayForm['observacion'] ."' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		
		print "<input type='hidden' name='id_tasacion' id='id_tasacion' value='".$this->arrayForm['id_tasacion'] ."'>\n";
		print "<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop'] ."'>\n";
//		print "<input type='hidden' name='tasador' id='tasador' value='".$_SESSION['UserId']."'>\n";

		print "<br>";
		print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</form>\n";
		print "</ div>";

	}

/**    OK
 * Muestra una tabla con los datos de los tasacions y una barra de herramientas o menu
 * conde se despliegan las opciones ingresables para cada item
 *
 */
	public function vistaTablaVW($id_prop=0){
		$propVW = new PropiedadVW($id_prop);		
		$fila=0;
		$usuario = new LoginwebuserBSN();
	

		if($id_prop==0 || is_nan($id_prop)) {
			echo "Debe seleccionar un Propiedad para poder verificar sus Fotos";
		} else {
			print "<form name='lista' method='POST' action='respondeMenu.php'>";

			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(nameForm,opcion,id){\n";
			print "     document.forms.lista.id_tasacion.value=id;\n";
			print "   	submitform(nameForm,opcion);\n";
			print "}\n";
			print "function modifica(prop,tas){\n";
			print "     document.getElementById('opcion').value='m';\n";
			print "     document.location.href='carga_Tasacion.php?i='+prop+'&o='+tas;\n";
			print "}\n";
			print "function borra(prop,tas){\n";
			print "   window.open('carga_Tasacion.php?i='+prop+'&o='+tas+'&b=b', 'ventanaTasacion', 'menubar=1,resizable=1,width=950,height=550');\n";
			print "}\n";
			print "</script>\n";

			print "<div class='pg_titulo'>Listado de estados de la tasacion en Propiedades</div>\n";
			$propVW->muestraDomicilio();
			
		$arrayTools=array(array('Nuevo','images/blog--plus.png','muestracargaDataTasacion()'));
		$menu=new Menu();
		$menu->barraHerramientas($arrayTools);
			
			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
			print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
			print "     <td class='cd_lista_titulo'>Tasador</td>\n";
			print "     <td class='cd_lista_titulo'>Fecha Tasacion</td>\n";
			print "     <td class='cd_lista_titulo'>Valor Tasacion</td>\n";
			print "     <td class='cd_lista_titulo'>Estado Tasacion</td>\n";			
			print "     <td class='cd_lista_titulo'>Observacion</td>\n";
			print "	  </tr>\n";


			$evenBSN=new TasacionBSN();

			$arrayEven=$evenBSN->cargaColeccionFormByPropiedad($id_prop);

			if(sizeof($arrayEven)==0){
				print "No existen datos para mostrar";
			} else {
				foreach ($arrayEven as $Even){
					if($fila==0){
						$fila=1;
					} else {
						$fila=0;
					}

					$usuario->cargaById($Even['tasador']);
					$tasador=$usuario->getObjeto()->getApellido().", ".$usuario->getObjeto()->getNombre();
                                        if(trim($tasador==', ')){
                                            $tasador='-';
                                        }

					print "<tr>\n";
					print "	 <td class='row".$fila."'>";
					print "    <a href=\"javascript:borra($id_prop,".$Even['id_tasacion'].");\" border=0>";
					print "       <img src='images/blog--minus.png' alt='Borrar' title='Borrar' border=0></a>";
					
					print "  </td>\n";
					print "	 <td class='row".$fila."'>".$tasador."</td>\n";
//					print "	 <td class='row".$fila."'>".$Even['tasador']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['cfecha']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['valor']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['estado']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['observacion']."</td>\n";
					print "	</tr>\n";
				}
			}

			print "  </table>\n";
			print "<input type='hidden' name='id_tasacion' id='id_tasacion' value=''>";
			print "<input type='hidden' name='id_prop' id='id_prop' value='".$id_prop."'>";
			print "<input type='hidden' name='opcion' id='opcion' value=''>";
			print "</form>";
		}
	}


} // fin clase


?>
