<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.tipo_caracBSN.php");
include_once("clases/class.tipo_carac.php");
include_once("inc/funciones.inc");

class Tipo_caracVW {

	private $tipo_carac;
	private $arrayForm;
	
	public function __construct($_tipo_carac=0){
		Tipo_caracVW::creaTipo_carac();
		if($_tipo_carac instanceof Tipo_carac )	{
			Tipo_caracVW::seteaTipo_carac($_tipo_carac);
		}
		if (is_numeric($_tipo_carac)){
			if($_tipo_carac!=0){
				Tipo_caracVW::cargaTipo_carac($_tipo_carac);
			}
		}
	}


	public function cargaTipo_carac($_tipo_carac){
		$tipo_carac=new Tipo_caracBSN($_tipo_carac);
		$this->seteaTipo_carac($tipo_carac->getObjeto()); //tipo_carac());		
	}
	
	public function getIdTipo_carac(){
		return $this->tipo_carac->getId_tipo_carac();
		
	}
	
	protected function creaTipo_carac(){
		$this->tipo_carac=new tipo_carac();
	}
	
	protected function seteaTipo_carac($_tipo_carac){
		$this->tipo_carac=$_tipo_carac;
		$tipo_carac=new Tipo_caracBSN($_tipo_carac);
		$this->arrayForm=$tipo_carac->getObjetoView();

	}
	
	
	public function cargaDatosTipo_carac(){
		if($this->arrayForm['id_tipo_carac']==0 || $this->arrayForm['id_tipo_carac']==''){
			$objBSN= new Tipo_caracBSN();
			$orden=$objBSN->proximaPosicion();

		}else{
			$orden=$this->arrayForm['orden'];
		}

		print "<form action='carga_tipo_carac.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTipo_carac(this);'>\n";
		
		$menu=new Menu();
		$menu->dibujaMenu('carga');

		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		
		print "<tr><td class='cd_celda_titulo'>Carga de Tipos de Caracteristica</td></tr>\n";
		
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
				
		print "<tr><td class='cd_celda_texto' width='20%'>Tipo<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='80%'><input class='campos' type='text' name='tipo_carac' id='tipo_carac' value='". $this->arrayForm['tipo_carac'] ."' maxlength='250' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Orden</td>";
		print "<td width='80%'><input class='campos' type='text' name='orden' id='orden' value='".	$orden ."' maxlength='2' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Vista en Columas</td>";
		print "<td width='80%'><input class='campos' type='text' name='columnas' id='columnas' value='".	$this->arrayForm['columnas'] ."' maxlength='2' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Vista Publica</td>";
		print "<td width='80%'><input class='campos' type='checkbox' name='publica' id='publica' ";
		if($this->arrayForm['publica']==1){
			print " checked ";
		}
		print "></td></tr>\n";
		
		print "<input type='hidden' name='id_tipo_carac' id='id_tipo_carac' value='".$this->arrayForm['id_tipo_carac'] ."'>\n";
		
		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	


/**
 * Lee desde un formulario los datos cargados para el tipo_carac.
 * Los registra en un objeto del tipo tipo_carac tipo_carac de esta clase
 *
 */
	public function leeDatosTipo_caracVW(){
		$tipo_carac=new Tipo_caracBSN();
		if($_POST['publica']=='on'){
			$_POST['publica']=1;
		}else{
			$_POST['publica']=0;
		}
	
		$this->tipo_carac=$tipo_carac->leeDatosForm($_POST);
	}

/**    OK
 * Muestra una tabla con los datos de los tipo_caracs y una barra de herramientas o menu
 * conde se despliegan las opciones ingresables para cada item
 *
 */
	public function vistaTablaTipo_carac(){
		$fila=0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_tipo_carac.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";

		print "<span class='pg_titulo'>Listado de Tipos de Caracteristicas</span><br><br>\n";
		

		
		$menu=new Menu();
		$menu->dibujaMenu('lista','opcion');

		print "<form name='lista' method='POST' action='respondeMenu.php'>";

		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='4'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Tipo de Caracteristica</td>\n";
		print "     <td class='cd_lista_titulo'>Orden</td>\n";
		print "     <td class='cd_lista_titulo'>Columnas</td>\n";
		print "     <td class='cd_lista_titulo'>Publica</td>\n";
		print "	  </tr>\n";

		
		$evenBSN=new Tipo_caracBSN();
		
		$arrayEven=$evenBSN->cargaColeccionForm();

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
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(112,".$Even['id_tipo_carac'].");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(113,".$Even['id_tipo_carac'].");' border=0>";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(114,".$Even['id_tipo_carac'].");' border='0'>";
				print "       <img src='images/up.png' alt='Subir' border=0></a></td>";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(115,".$Even['id_tipo_carac'].");' border=0>";
				print "       <img src='images/down.png' alt='Bajar' border=0></a>";
				print "  </td>\n";
				
				print "	 <td class='row".$fila."'>".$Even['tipo_carac']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['orden']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['columnas']."</td>\n";
				print "	 <td class='row".$fila."'>";
				if($Even['publica']==1){
					print "SI";
				}
				print "</td>\n";
				print "	</tr>\n";
			}
		}
		
		print "  </table>\n";
		print "<input type='hidden' name='id_tipo_carac' id='id_tipo_carac' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}


	
	public function grabaModificacion(){
		$retorno=false;
		$tipo_carac=new Tipo_caracBSN($this->tipo_carac);
		$retUPre=$tipo_carac->actualizaDB();
		if ($retUPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaTipo_carac(){
		$retorno=false;
		$tipo_carac=new Tipo_caracBSN($this->tipo_carac);
//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
//		$existente=$tipo_carac->controlDuplicado($this->tipo_carac->getTipo_carac());
//		if($existente){
//			echo "Ya existe un tipo_carac con ese Titulo";
//		} else {
			$retIPre=$tipo_carac->insertaDB();
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