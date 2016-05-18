<?php
include_once ('generic_class/class.VW.php');
//include_once("generic_class/class.menu.php");
include_once("clases/class.tipo_caracBSN.php");
include_once("clases/class.tipo_carac.php");
include_once("inc/funciones.inc");

class Tipo_caracVW extends VW{
	protected $clase="Tipo_carac";
	protected $tipo_carac;
	protected $nombreId="Id_tipo_carac";
//	private $arrayForm;
	
	public function __construct($_tipo_carac=0){
		Tipo_caracVW::creaObjeto();
		if($_tipo_carac instanceof Tipo_carac )	{
			Tipo_caracVW::seteaVW($_tipo_carac);
		}
		if (is_numeric($_tipo_carac)){
			if($_tipo_carac!=0){
				Tipo_caracVW::cargaVW($_tipo_carac);
			}
		}
	}

	public function cargaDatosVW(){
		if($this->arrayForm['id_tipo_carac']==0 || $this->arrayForm['id_tipo_carac']==''){
			$objBSN= new Tipo_caracBSN();
			$orden=$objBSN->proximaPosicion();

		}else{
			$orden=$this->arrayForm['orden'];
		}

		print "<form action='carga_tipo_carac.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTipo_carac(this);'>\n";
		
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
	public function leeDatosVW(){
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
	public function vistaTablaVW(){
		$fila=0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_tipo_carac.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "</script>\n";

		print "<div class='pg_titulo'>Listado de Tipos de Caracteristicas</div>\n";
		
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
				print "    <a href='javascript:envia(\"lista\",112,".$Even['id_tipo_carac'].");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(\"lista\",113,".$Even['id_tipo_carac'].");' border=0>";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				print "  </td>\n";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(\"lista\",114,".$Even['id_tipo_carac'].");' border='0'>";
				print "       <img src='images/up.png' alt='Subir' border=0></a></td>";
				print "	 <td class='row".$fila."' width='16'>";
				print "    <a href='javascript:envia(\"lista\",115,".$Even['id_tipo_carac'].");' border=0>";
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



	
} // fin clase


?>