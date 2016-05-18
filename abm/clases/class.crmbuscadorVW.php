<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.cartelBSN.php");
include_once("clases/class.cartel.php");
include_once("clases/class.propiedadVW.php");
include_once("inc/funciones.inc");

class CartelVW {

	private $cartel;
	private $arrayForm;

	public function __construct($_cartel=0){
		CartelVW::creaCartel();
		if($_cartel instanceof Cartel )	{
			CartelVW::seteaCartel($_cartel);
		}
		if (is_numeric($_cartel)){
			if($_cartel!=0){
				CartelVW::cargaCartel($_cartel);
			}
		}
	}


	public function cargaCartel($_cartel){
		$cartel=new CartelBSN($_cartel);
		$this->seteaCartel($cartel->getObjeto()); //cartel());
	}

	public function getIdCartel(){
		return $this->cartel->getId_oper();

	}

	protected function creaCartel(){
		$this->cartel=new cartel();
	}

	protected function seteaCartel($_cartel){
		$this->cartel=$_cartel;
		$cartel=new CartelBSN($_cartel);
		$this->arrayForm=$cartel->getObjetoView();

	}


	public function setIdPropiedad($_prop){
		$this->cartel->setId_prop($_prop);
		$this->arrayForm['id_prop']=$_prop;
	}

	public function getIdPropiedad(){
		return $this->cartel->getId_prop();
	}


	public function cargaDatosCartel(){
		$propVW = new PropiedadVW($this->arrayForm['id_prop']);		
		$menu=new Menu();
		$menu->dibujaMenu('carga');
		$propVW->muestraDomicilio();
		print "<form action='carga_cartel.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaCartel(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Carga de estado del Cartel</td></tr>\n";

		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Fecha</td>";
		print "<td width='85%'><input class='campos' type='text' name='cfecha' id='cfecha' value='".	$this->arrayForm['cfecha'] ."' maxlength='10' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		print "	<script type=\"text/javascript\">\n";
		print "jQuery(\"#cfecha\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\"});\n";
		print "</script>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Estado</td>";
		print "<td width='85%'>";
		armaEstadoCartel($this->arrayForm['estado']);
		print "</td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";

		print "<tr>";
		print "<td class='cd_celda_texto' width='15%'>Proveedor</td>";
		print "<td width='85%'><input class='campos' type='text' name='proveedor' id='proveedor' value='".	$this->arrayForm['proveedor'] ."' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
	
		print "<tr>";
		print "<td class='cd_celda_texto' width='15%'>Observacion</td>";
		print "<td width='85%'><input class='campos' type='text' name='observacion' id='observacion' value='".	$this->arrayForm['observacion'] ."' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
		
		print "<input type='hidden' name='id_cartel' id='id_cartel' value='".$this->arrayForm['id_cartel'] ."'>\n";
		print "<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop'] ."'>\n";
//		print "<input type='hidden' name='proveedor' id='proveedor' value='".$_SESSION['UserId']."'>\n";

		print "<br>";
		print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}



/**
 * Lee desde un formulario los datos cargados para el cartel.
 * Los registra en un objeto del tipo cartel cartel de esta clase
 *
 */
	public function leeDatosCartelVW(){
		$cartel=new CartelBSN();
		$this->cartel=$cartel->leeDatosForm($_POST);
	}

	/**    OK
 * Muestra una tabla con los datos de los cartels y una barra de herramientas o menu
 * conde se despliegan las opciones ingresables para cada item
 *
 */
	public function vistaTablaVW($id_prop=0){
		$propVW = new PropiedadVW($id_prop);		
		$fila=0;

		if($id_prop==0 || is_nan($id_prop)) {
			echo "Debe seleccionar un Propiedad para poder verificar sus Fotos";
		} else {
			print "<form name='lista' method='POST' action='respondeMenu.php'>";

			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(opcion,id){\n";
			print "     document.forms.lista.id_cartel.value=id;\n";
			print "   	submitform(opcion);\n";
			print "}\n";
			print "</script>\n";

			print "<span class='pg_titulo'>Listado de Estados del cartel en Propiedades</span><br><br>\n";
			$propVW->muestraDomicilio();
			$menu=new Menu();
			$menu->dibujaMenu('lista','opcion');

			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
			print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
			print "     <td class='cd_lista_titulo'>Fecha Cartel</td>\n";
			print "     <td class='cd_lista_titulo'>Estado Cartel</td>\n";
			print "     <td class='cd_lista_titulo'>Proveedor</td>\n";
			print "     <td class='cd_lista_titulo'>Observacion</td>\n";
			print "	  </tr>\n";


			$evenBSN=new CartelBSN();

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

					print "<tr>\n";
					print "	 <td class='row".$fila."'>";
					print "    <a href='javascript:envia(273,".$Even['id_cartel'].");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
					print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
					print "  </td>\n";
					print "	 <td class='row".$fila."'>".$Even['cfecha']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['estado']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['proveedor']."</td>\n";
					print "	 <td class='row".$fila."'>".$Even['observacion']."</td>\n";
					print "	</tr>\n";
				}
			}

			print "  </table>\n";
			print "<input type='hidden' name='id_cartel' id='id_cartel' value=''>";
			print "<input type='hidden' name='id_prop' id='id_prop' value='".$id_prop."'>";
			print "<input type='hidden' name='opcion' id='opcion' value=''>";
			print "</form>";
		}
	}


	public function grabaModificacion(){
		$retorno=false;
		$cartel=new CartelBSN($this->cartel);
		$retUPre=$cartel->actualizaDB();
		if ($retUPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaCartel(){
		$retorno=false;
		$cartel=new CartelBSN($this->cartel);
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
		//		$existente=$cartel->controlDuplicado($this->cartel->getCartel());
		//		if($existente){
		//			echo "Ya existe un cartel con ese Titulo";
		//		} else {
		$retIPre=$cartel->insertaDB();
		//			die();
		if ($retIPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		//		} // Fin control de Duplicados
		return $retorno;
	}



} // fin clase


?>