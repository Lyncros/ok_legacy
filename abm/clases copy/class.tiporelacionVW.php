<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.tiporelacionBSN.php");
include_once("clases/class.tiporelacion.php");
include_once("inc/funciones.inc");

class TiporelacionVW {

	private $tiporelacion;
	private $arrayForm;

	public function __construct($_tiporelacion=0){
		TiporelacionVW::creaTiporelacion();
		if($_tiporelacion instanceof Tiporelacion )	{
			TiporelacionVW::seteaTiporelacion($_tiporelacion);
		}
		if (is_numeric($_tiporelacion)){
			if($_tiporelacion!=0){
				TiporelacionVW::cargaTiporelacion($_tiporelacion);
			}
		}
	}


	public function cargaTiporelacion($_tiporelacion){
		$tiporelacion=new TiporelacionBSN($_tiporelacion);
		$this->seteaTiporelacion($tiporelacion->getObjeto()); //tiporelacion());
	}

	public function getIdTiporelacion(){
		return $this->tiporelacion->getId_tiporel();

	}

	protected function creaTiporelacion(){
		$this->tiporelacion=new tiporelacion();
	}

	protected function seteaTiporelacion($_tiporelacion){
		$this->tiporelacion=$_tiporelacion;
		$tiporelacion=new TiporelacionBSN($_tiporelacion);
		$this->arrayForm=$tiporelacion->getObjetoView();

	}


	public function cargaDatosTiporelacion(){
		$objBSN= new TiporelacionBSN();

// Array ( [id_tiporel] => 8 [tiporelacion] => CC [relacion] => Amigo [grado] => [observacion] => Cliente amigo de otro cliente )
		print "<form action='carga_tiporelacion.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTiporelacion(this);'>\n";
		$menu=new Menu();
		$menu->dibujaMenu('carga');
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Carga de Tipos de Relacion</td></tr>\n";

		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Tipo<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='80%'>";
		$objBSN->comboTipoRelacion($this->arrayForm['tiporelacion']);
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Relacion</td>";
		print "<td width='80%'><input class='campos' type='text' name='relacion' id='relacion' value='".$this->arrayForm['relacion'] ."' maxlength='255' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Grado de la Relacion</td>";
		print "<td width='80%'><input class='campos' type='text' name='grado' id='grado' value='".$this->arrayForm['grado'] ."' maxlength='255' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Observacion</td>";
		print "<td width='80%'><input class='campos' type='text' name='observacion' id='observacion' value='".$this->arrayForm['observacion']. "'></td></tr>\n";

		print "<input type='hidden' name='id_tiporel' id='id_tiporel' value='".$this->arrayForm['id_tiporel'] ."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	


	/**
	 * Lee desde un formulario los datos cargados para el tiporelacion.
	 * Los registra en un objeto del tipo tiporelacion tiporelacion de esta clase
	 *
	 */
	public function leeDatosTiporelacionVW(){
		$tiporelacion=new TiporelacionBSN();
		$this->tiporelacion=$tiporelacion->leeDatosForm($_POST);
	}

	/**    OK
	 * Muestra una tabla con los datos de los tiporelacions y una barra de herramientas o menu
	 * conde se despliegan las opciones ingresables para cada item
	 *
	 */
	public function vistaTablaTiporelacion(){
		$tipoRelBSN = new TiporelacionBSN();
		$fila=0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_tiporel.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";

		print "<span class='pg_titulo'>Listado de Tipos de Relaciones</span><br><br>\n";

		$menu=new Menu();
		$menu->dibujaMenu('lista','opcion');

		print "<form name='lista' method='POST' action='respondeMenu.php'>";

		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		//		print "     <td class='cd_lista_titulo'>Tipo de Relacion</td>\n";
		print "     <td class='cd_lista_titulo'>Relacion</td>\n";
		print "     <td class='cd_lista_titulo'>Grado</td>\n";
		print "     <td class='cd_lista_titulo'>Observacion</td>\n";
		print "	  </tr>\n";


		$evenBSN=new TiporelacionBSN();

		$arrayEven=$evenBSN->cargaColeccionForm();
		$tipoAnt='';
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
				if($tipoAnt!=$Even['tiporelacion']){
					print "	 <td class='cd_lista_titulo' colspan='5'>Relacion: ".$tipoRelBSN->getDescripcionTipo($Even['tiporelacion'])."</td>\n";
					print "</tr>\n";
					$tipoAnt=$Even['tiporelacion'];
				}
				print "<tr>\n";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(172,".$Even['id_tiporel'].");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(173,".$Even['id_tiporel'].");' border=0>";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";

				print "	 <td class='row".$fila."'>".$Even['relacion']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['grado']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['observacion']."</td>\n";
				print "	</tr>\n";
			}
		}

		print "  </table>\n";
		print "<input type='hidden' name='id_tiporel' id='id_tiporel' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}



	public function grabaModificacion(){
		$retorno=false;
		$tiporelacion=new TiporelacionBSN($this->tiporelacion);
		$retUPre=$tiporelacion->actualizaDB();
		if ($retUPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaTiporelacion(){
		$retorno=false;
		$tiporelacion=new TiporelacionBSN($this->tiporelacion);
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
		//		$existente=$tiporelacion->controlDuplicado($this->tiporelacion->getTiporelacion());
		//		if($existente){
		//			echo "Ya existe un tiporelacion con ese Titulo";
		//		} else {
		$retIPre=$tiporelacion->insertaDB();
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