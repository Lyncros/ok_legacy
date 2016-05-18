<?php
include_once ("generic_class/class.menu.php");
include_once ("clases/class.ubicacionpropiedadBSN.php");
include_once ("clases/class.ubicacionpropiedad.php");
include_once ("inc/funciones.inc");

class UbicacionpropiedadVW {
	private $ubicacionpropiedad;
	private $arrayForm;
	public function __construct($_ubicacionpropiedad = 0) {
		UbicacionpropiedadVW::creaUbicacionpropiedad ();
		if ($_ubicacionpropiedad instanceof Ubicacionpropiedad) {
			UbicacionpropiedadVW::seteaUbicacionpropiedad ( $_ubicacionpropiedad );
		}
		if (is_numeric ( $_ubicacionpropiedad )) {
			if ($_ubicacionpropiedad != 0) {
				UbicacionpropiedadVW::cargaUbicacionpropiedad ( $_ubicacionpropiedad );
			}
		}
	}

	public function cargaUbicacionpropiedad($_ubicacionpropiedad) {
		$ubicacionpropiedad = new UbicacionpropiedadBSN ( $_ubicacionpropiedad );
		$this->seteaUbicacionpropiedad ( $ubicacionpropiedad->getObjeto () );
	}

	public function getId_ubica() {
		return $this->ubicacionpropiedad->getId_ubica();
	}

	protected function creaUbicacionpropiedad() {
		$this->ubicacionpropiedad = new ubicacionpropiedad ( );
	}

	protected function seteaUbicacionpropiedad($_ubicacionpropiedad) {
		$this->ubicacionpropiedad = $_ubicacionpropiedad;
		$ubicacionpropiedad = new UbicacionpropiedadBSN ( $_ubicacionpropiedad );
		$this->arrayForm = $ubicacionpropiedad->getObjetoView();
	}


	public function cargaDatosVW() {
		$ubiBSN = new UbicacionpropiedadBSN();
		print "<form action='carga_ubicacionpropiedad.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaUbicacionpropiedad(this);'>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'carga' );
		print "<script type='text/javascript' >\n";
		print "function controlZona(){\n";
		print "   if(document.getElementById('zonaPrinc').checked){\n";
		print "   	vista='none';\n";
		print "   	document.getElementById('id_padre').value=0;\n";
		print "   	document.getElementById('txtUbica').innerHTML='Zona principal';\n";
		print "   }else{\n";
		print "   	vista='block';\n";
		print "   	seleccionaZona();\n";
		print "   }\n";
		print "   	document.getElementById('botonZona').style.display=vista;\n";
		print "}\n";
		print "function seleccionaZona(){\n";
		print "   window.open('seleccionaZona.php?v='+document.getElementById('id_padre').value+'&z=0', 'ventana', 'menubar=1,resizable=1,width=950,height=550');\n";
		//		print "   window.open('carga_Telefonos.php?t=0&tc=C&c='+id+'&div='+div, 'ventana', 'menubar=1,resizable=1,width=950,height=550');\n";
		print "}\n";
		print "</script>\n";

		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Ubicaciones de propiedades</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Depende de zona </td>";
		print "<td width='85%'>";
		print "<input type='checkbox' ";
		if($this->arrayForm ['id_padre']==0){
			print " checked ";
			$display='none'; 
			$valorPadre=0;
		}else{
			$display='block';
			$valorPadre=$this->arrayForm ['id_padre'];
		}
		print " id='zonaPrinc' name='zonaPrinc' onclick='javascript: controlZona();'>";
		print "<label for='zonaPrinc'>Es zona principal. Para ver zonas secundarias destilde el casillero y/u oprima el boton</label><br />";
		//		$ubiBSN->comboUbicacionpropiedadPrincipal($this->arrayForm ['id_padre']);
		print "<input type='hidden' id='id_padre' name='id_padre' value='$valorPadre'>";
		//	print "<input type='button' id='botonZona' style='display: none;' onclick=\"window.open('seleccionaZona.php?v='+document.getElementById('id_ubica').value+'&z=0');\">";
		print "<input type='button' id='botonZona' style='display: $display;' onclick=\"seleccionaZona();\">";
		print "<div id='txtUbica'>Zona principal</div>";


		print "</td><td></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Descripci&oacute;n<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'><input class='campos' type='text' name='nombre_ubicacion' id='nombre_ubicacion' value='" . $this->arrayForm ['nombre_ubicacion'] . "' maxlength='250' size='80'></td></tr>\n";
		print "<input type='hidden' name='id_ubica' id='id_ubica' value='" . $this->arrayForm ['id_ubica'] . "'>\n";
		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}


	/** * Lee desde un formulario los datos cargados para el ubicacionpropiedad. * Los registra en un objeto del tipo ubicacionpropiedad ubicacionpropiedad de esta clase * */
	public function leeDatosVW() {
		$ubicacionpropiedad = new UbicacionpropiedadBSN ();
		$this->ubicacionpropiedad = $ubicacionpropiedad->leeDatosForm ($_POST);
	}


	/**    OK * Muestra una tabla con los datos de las zonas y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaVW() {
//		$zona = new Zona ();
//		$zonaBSN = new ZonaBSN ();
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_ubica.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Ubicacionpropiedades</span><br><br>\n";
		$menu = new Menu ( );
		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Zona</td>\n";
		print "     <td class='cd_lista_titulo'>Ubicacionpropiedad</td>\n";
		print "	  </tr>\n";
		$evenBSN = new UbicacionpropiedadBSN ( );
		$ubiBSN=new UbicacionpropiedadBSN();
		$arrayEven = $evenBSN->cargaColeccionForm ();
		if (sizeof ( $arrayEven ) == 0) {
			print "No existen datos para mostrar";
		} else {
			foreach ( $arrayEven as $Even ) {
				if ($fila == 0) {
					$fila = 1;
				} else {
					$fila = 0;
				}
				print "<tr>\n";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(132," . $Even ['id_ubica'] . ");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				print "    <a href='javascript:envia(133," . $Even ['id_ubica'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td class='row" . $fila . "'>";
				$ubiBSN->setId ( $Even ['id_padre'] );
				$ubiBSN->cargaById( $Even['id_padre'] );
				print $ubiBSN->getObjeto()->getNombre_ubicacion();
				print "</td>\n";
				print "	 <td class='row" . $fila . "'>" . $Even ['nombre_ubicacion'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='id_ubica' id='id_ubica' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}


	public function grabaModificacion() {
		$retorno = false;
		$ubicacionpropiedad = new UbicacionpropiedadBSN ( $this->ubicacionpropiedad );
		$retUPre = $ubicacionpropiedad->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabación de los datos.<br>";
		}
		return $retorno;
	}


	public function grabaDatosVW() {
		$retorno = false;
		$ubicacionpropiedad = new UbicacionpropiedadBSN ( $this->ubicacionpropiedad );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//
		//$existente = $ubicacionpropiedad->controlDuplicado ( $this->ubicacionpropiedad->getNombre_zona () );
		//if($existente){
		//	echo "Ya existe un ubicacionpropiedad con ese Titulo";
		//} else {
		$retIPre=$ubicacionpropiedad->insertaDB();
		//	die();
		if ($retIPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		//}
		// Fin control de Duplicados
		return $retorno;
	}
}
// fin clase
?>