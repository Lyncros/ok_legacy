<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.tasacionBSN.php");
include_once("clases/class.tasacion.php");
include_once("clases/class.propiedadVW.php");
include_once("inc/funciones.inc");

class TasacionVW {

	private $tasacion;
	private $arrayForm;

	public function __construct($_tasacion=0){
		TasacionVW::creaTasacion();
		if($_tasacion instanceof Tasacion )	{
			TasacionVW::seteaTasacion($_tasacion);
		}
		if (is_numeric($_tasacion)){
			if($_tasacion!=0){
				TasacionVW::cargaTasacion($_tasacion);
			}
		}
	}


	public function cargaTasacion($_tasacion){
		$tasacion=new TasacionBSN($_tasacion);
		$this->seteaTasacion($tasacion->getObjeto()); //tasacion());
	}

	public function getIdTasacion(){
		return $this->tasacion->getId_tasacion();

	}

	protected function creaTasacion(){
		$this->tasacion=new tasacion();
	}

	protected function seteaTasacion($_tasacion){
		$this->tasacion=$_tasacion;
		$tasacion=new TasacionBSN($_tasacion);
		$this->arrayForm=$tasacion->getObjetoView();

	}


	public function setIdPropiedad($_prop){
		$this->tasacion->setId_prop($_prop);
		$this->arrayForm['id_prop']=$_prop;
	}

	public function getIdPropiedad(){
		return $this->tasacion->getId_prop();
	}


	public function cargaDatosTasacion(){
		$propVW = new PropiedadVW($this->arrayForm['id_prop']);		
		$menu=new Menu();
		$menu->dibujaMenu('carga');
		$propVW->muestraDomicilio();
		print "<form action='carga_tasacion.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTasacion(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Carga de valor del Tasacion</td></tr>\n";

		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr>";
		print "<td class='cd_celda_texto' width='15%'>Tasador</td>";
		print "<td width='85%'><input class='campos' type='text' name='tasador' id='tasador' value='".	$this->arrayForm['tasador'] ."' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
	
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
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}



/**
 * Lee desde un formulario los datos cargados para el tasacion.
 * Los registra en un objeto del tipo tasacion tasacion de esta clase
 *
 */
	public function leeDatosTasacionVW(){
		$tasacion=new TasacionBSN();
		$this->tasacion=$tasacion->leeDatosForm($_POST);
	}

	/**    OK
 * Muestra una tabla con los datos de los tasacions y una barra de herramientas o menu
 * conde se despliegan las opciones ingresables para cada item
 *
 */
	public function vistaTablaTasaciones($id_prop=0){
		$propVW = new PropiedadVW($id_prop);		
		$fila=0;
		

		if($id_prop==0 || is_nan($id_prop)) {
			echo "Debe seleccionar un Propiedad para poder verificar sus Fotos";
		} else {
			print "<form name='lista' method='POST' action='respondeMenu.php'>";

			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(opcion,id){\n";
			print "     document.forms.lista.id_tasacion.value=id;\n";
			print "   	submitform(opcion);\n";
			print "}\n";
			print "</script>\n";

			print "<span class='pg_titulo'>Listado de estados de la tasacion en Propiedades</span><br><br>\n";
			$propVW->muestraDomicilio();
			$menu=new Menu();
			$menu->dibujaMenu('lista');
//			$menu->dibujaMenu('lista','opcion');

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

					print "<tr>\n";
					print "	 <td class='row".$fila."'>";
					print "    <a href='javascript:envia(2833,".$Even['id_tasacion'].");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
					print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
					print "  </td>\n";
					print "	 <td class='row".$fila."'>".$Even['tasador']."</td>\n";
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
//			print "<input type='hidden' name='opcion' id='opcion' value=''>";
			print "</form>";
		}
	}


	public function grabaModificacion(){
		$retorno=false;
		$tasacion=new TasacionBSN($this->tasacion);
		$retUPre=$tasacion->actualizaDB();
		if ($retUPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaTasacion(){
		$retorno=false;
		$tasacion=new TasacionBSN($this->tasacion);
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
		//		$existente=$tasacion->controlDuplicado($this->tasacion->getTasacion());
		//		if($existente){
		//			echo "Ya existe un tasacion con ese Titulo";
		//		} else {
		$retIPre=$tasacion->insertaDB();
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