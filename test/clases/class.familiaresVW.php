<?php
include_once ('generic_class/class.VW.php');
include_once("clases/class.tipoFamiliarBSN.php");
include_once("clases/class.familiaresBSN.php");
include_once("clases/class.familiares.php");
include_once("inc/funciones.inc");

class FamiliaresVW extends VW{
	protected $clase="Familiares";
	protected $familiares;
	protected $nombreId="Id_fam";

	public function __construct($_familiares=0){
		FamiliaresVW::creaObjeto();
		if($_familiares instanceof Familiares )	{
			FamiliaresVW::seteaVW($_familiares);
		}
		if (is_numeric($_familiares)){
			if($_familiares!=0){
				FamiliaresVW::cargaVW($_familiares);
			}
		}
		FamiliaresVW::cargaDefinicionForm();
	}

	public function cargaDatosVW($destino=''){
		$objBSN= new FamiliaresBSN();

		print "<div id='cargaData' name='cargaData' style='display:none;'>";

		print "<form action='carga_Familiares.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaFamiliares(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Carga de Familiares</td></tr>\n";

		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Parentezco<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='80%'>";
                $parent= FamiliarBSN::getInstance();
                $parent->comboParametros($this->arrayForm ['id_parent'],0,'id_parent');
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Nombre</td>";
		print "<td width='80%'><input class='campos' type='text' name='nombre' id='nombre' value='".$this->arrayForm['nombre'] ."' maxlength='50' size='50'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Apellido</td>";
		print "<td width='80%'><input class='campos' type='text' name='apellido' id='apellido' value='".$this->arrayForm['apellido'] ."' maxlength='15' size='80'></td></tr>\n";
                
		print "<tr><td class='cd_celda_texto' width='20%'>Nota</td>";
		print "<td width='80%'><textarea rows='6' class='campos_area' name='nota' id='nota'>" . $this->arrayForm ['nota'] . "</textarea></td></tr>\n";

                print "<tr><td class='cd_celda_texto' width='15%'>Nacimiento</td>";
                print "<td width='85%'><input class='campos' type='text' name='fecha_nac' id='fecha_nac' value='" . $this->arrayForm['fecha_nac'] . "' maxlength='10' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
                print "	<script type=\"text/javascript\">\n";
                print "jQuery(\"#fecha_nac\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\"});\n";
                print "</script>\n";

                
                print "<input type='hidden' name='id_fam' id='id_fam' value='".$this->arrayForm['id_fam'] ."'>\n";
		print "<input type='hidden' name='id_cli' id='id_cli' value='".$this->arrayForm['id_cli'] ."'>\n";
		print "<input type='hidden' name='tipocont' id='tipocont' value='".$this->arrayForm['tipocont'] ."'>\n";
		print "<input type='hidden' name='div' id='div' value='".$destino ."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
		print "</div>";
	}



	/**    OK
	 * Muestra una tabla con los datos de los familiaress y una barra de herramientas o menu
	 * donde se despliegan las opciones ingresables para cada item
	 * La misma corresponde a los familiares del contacto indocado por tipo e ids pasados como parametros
	 * @param string $_tipo -> tipo de contacto al que corresponde el ID   U:usuario   C:cliente
	 * @param int $_id -> id del contacto
	 * @param char $modo -> modo de visualizacion de la lista 'v' vista 'o' operativo
	 */
	public function vistaTablaVW($_tipo='',$_id=0,$modo='o'){
		$fila=0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_fam.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "function modifica(t,c,tc){\n";
		print "     document.getElementById('opcion').value='m';\n";
		print "     document.location.href='carga_Familiares.php?t='+t+'&tc='+tc+'&c='+c;\n";
		print "}\n";
		print "function borra(t,c,tc){\n";
		print "     document.getElementById('opcion').value='b';\n";
		print "     document.location.href='carga_Familiares.php?t='+t+'&b=b&tc='+tc+'&c='+c;\n";
		print "}\n";
		print "function muestraCargaData(){\n";
		print "   document.getElementById('cargaData').style.display='block';\n";
		print "}\n";
		print "</script>\n";
		print "<div class='pg_subtitulo'>Listado de Familiares de Contacto</div>\n";
		if($modo=='o'){
			$arrayTools=array(array('Nuevo','images/group_edit.png','muestraCargaData()'),array('Regresar','images/ui-button-navigation-back.png','KillMe()'));
			$menu=new Menu();
			$menu->barraHerramientas($arrayTools);
				
			print "<form name='lista' method='POST' action='respondeMenu.php'>";
			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
			print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		}else{
			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
		}
		print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Parentezco</td>\n";
		print "     <td class='cd_lista_titulo'>Nombre</td>\n";
		print "     <td class='cd_lista_titulo'>Apellido</td>\n";
		print "     <td class='cd_lista_titulo'>Nacimiento</td>\n";
		print "	  </tr>\n";
                $pare=  FamiliarBSN::getInstance();
                $arrayPar=$pare->getArrayParametros();
		$evenBSN=new FamiliaresBSN();
		switch ($_tipo) {
			case 'U':
				$arrayEven=$evenBSN->coleccionByUsuarios($_id);
				break;
			case 'C':
				$arrayEven=$evenBSN->coleccionByCliente($_id);
				break;
			case 'O':
				$arrayEven=$evenBSN->coleccionByContactoAgenda($_id);
				break;
		}
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
				print "<tr>\n";
				if($modo=='o'){
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href=\"javascript:modifica(".$Even['id_fam'].",".$Even['id_cli'].",'".$Even['tipocont']."');\" border='0'>";
					print "       <img src='images/user_edit.png' alt='Editar' title='Editar' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href=\"javascript:borra(".$Even['id_fam'].",".$Even['id_cli'].",'".$Even['tipocont']."');\" border=0>";
					print "       <img src='images/user_delete.png' alt='Borrar' title='Borrar' border=0></a>";
					print "  </td>\n";
				}
                                
				print "	 <td class='row".$fila."'>".$arrayPar[$Even['id_parent']]."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['nombre']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['apellido']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['fecha_nac']."</td>\n";
				print "	</tr>\n";
			}
		}

		print "  </table>\n";
		print "<input type='hidden' name='id_fam' id='id_fam' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		if($modo=='o'){
			print "</form>";
		}else{

		}
	}

} // fin clase


?>
