<?php
include_once ('generic_class/class.VW.php');
//include_once("generic_class/class.menu.php");
include_once("clases/class.medioselectronicosBSN.php");
include_once("clases/class.medioselectronicos.php");
include_once("clases/class.tipoMedioelectronicoBSN.php");
include_once("inc/funciones.inc");

class MedioselectronicosVW extends VW{
	protected $clase="Medioselectronicos";
	protected $medioselectronicos;
	protected $nombreId="Id_medio";

	public function __construct($_medioselectronicos=0){
		MedioselectronicosVW::creaObjeto();
		if($_medioselectronicos instanceof Medioselectronicos )	{
			MedioselectronicosVW::seteaVW($_medioselectronicos);
		}
		if (is_numeric($_medioselectronicos)){
			if($_medioselectronicos!=0){
				MedioselectronicosVW::cargaVW($_medioselectronicos);
			}
		}
		MedioselectronicosVW::cargaDefinicionForm();
	}

	public function cargaDatosVW($destino=''){
		$objBSN= new MedioselectronicosBSN();

		print "<div id='cargaData' name='cargaData' style='display:none;'>";

		print "<form action='carga_Medioselectronicos.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaMedioselectronicos(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Carga de Medios de Contacto Electronicos</td></tr>\n";

		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Tipo<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='80%'>";
                $objBSN=  TipoMedioElectronicoBSN::getInstance();
		$objBSN->comboParametros($this->arrayForm ['id_tipomed'],0,'id_tipomed');
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Contacto</td>";
		print "<td width='80%'><input class='campos' type='text' name='contacto' id='contacto' value='".$this->arrayForm['contacto']. "'></td></tr>\n";
                
		print "<tr><td class='cd_celda_texto' width='20%'>Comentario</td>";
                print "<td width='85%'><textarea rows='6' class='campos_area' name='comentario' id='comentario'>" . $this->arrayForm ['comentario'] . "</textarea></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Principal</td>";
		print "<td width='80%'><input class='campos' type='checkbox' name='principal' id='principal' ";
		if($this->arrayForm['principal']==1){
			print "checked";
		}
		print "></td></tr>\n";

		print "<input type='hidden' name='id_medio' id='id_medio' value='".$this->arrayForm['id_medio'] ."'>\n";
		print "<input type='hidden' name='id_cli' id='id_cli' value='".$this->arrayForm['id_cli'] ."'>\n";
		print "<input type='hidden' name='tipocont' id='tipocont' value='".$this->arrayForm['tipocont'] ."'>\n";
		print "<input type='hidden' name='div' id='div' value='".$destino ."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
		print "</div>";
	}



	/**
	 * Lee desde un formulario los datos cargados para el medioselectronicos.
	 * Los registra en un objeto del tipo medioselectronicos medioselectronicos de esta clase
	 *
	 */
	public function leeDatosVW(){
		$medioselectronicos=new MedioselectronicosBSN();
		if($_POST['principal']=='on'){
			$_POST['principal']=1;
		}
		$this->medioselectronicos=$medioselectronicos->leeDatosForm($_POST);
	}

	/**    OK
	 * Muestra una tabla con los datos de los medioselectronicoss y una barra de herramientas o menu
	 * donde se despliegan las opciones ingresables para cada item
	 * La misma corresponde a los medioselectronicos del contacto indocado por tipo e ids pasados como parametros
	 * @param string $_tipo -> tipo de contacto al que corresponde el ID   U:usuario   C:cliente
	 * @param int $_id -> id del contacto
	 * @param char $modo -> modo de visualizacion de la lista 'v' vista 'o' operativo
	 */
	public function vistaTablaVW($_tipo='',$_id=0,$modo='o'){
		$fila=0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(nameForm,opcion,id){\n";
		print "     document.forms.lista.id_medio.value=id;\n";
		print "   	submitform(nameForm,opcion);\n";
		print "}\n";
		print "function modifica(t,tc,c){\n";
		print "     document.getElementById('opcion').value='m';\n";
		print "     document.location.href='carga_Medioselectronicos.php?t='+t+'&tc='+tc+'&c='+c;\n";
		print "}\n";
		print "function borra(t,tc,c){\n";
		print "     document.getElementById('opcion').value='b';\n";
		print "     document.location.href='carga_Medioselectronicos.php?t='+t+'&b=b&tc='+tc+'&c='+c;\n";
		print "}\n";
		print "function muestraCargaData(){\n";
		print "   document.getElementById('cargaData').style.display='block';\n";
		print "}\n";
		print "</script>\n";
		print "<div class='pg_subtitulo'>Listado de Medios Electronicos de Contacto</div>\n";
		if($modo=='o'){
			$arrayTools=array(array('Nuevo','images/telephone--plus.png','muestraCargaData()'),array('Regresar','images/ui-button-navigation-back.png','KillMe()'));
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
		print "     <td class='cd_lista_titulo'>Tipo</td>\n";
		print "     <td class='cd_lista_titulo'>Contacto</td>\n";
		print "     <td class='cd_lista_titulo'>Comentario</td>\n";
		print "	  </tr>\n";


		$evenBSN=new MedioselectronicosBSN();
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
                    $tipo=  TipoMedioElectronicoBSN::getInstance();
                    $arrayTipo=$tipo->getArrayParametros();
                    
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
					print "    <a href=\"javascript:modifica(".$Even['id_medio'].",'".$Even['tipocont']."',".$Even['id_cli'].");\" border='0'>";
					print "       <img src='images/telephone--pencil.png' alt='Editar' title='Editar' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href=\"javascript:borra(".$Even['id_medio'].",'".$Even['tipocont']."',".$Even['id_cli'].");\" border=0>";
					print "       <img src='images/telephone--minus.png' alt='Borrar' title='Borrar' border=0></a>";
					print "  </td>\n";
				}
				print "	 <td class='row".$fila."'>";
				if($Even['principal']==1){
					print "<img src='images/telephone--arrow.png' alt='Principal'>";
				}else{
					print "&nbsp;";
				}
				print "</td>\n";
				print "	 <td class='row".$fila."'>".$arrayTipo[$Even['id_tipomed']]."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['contacto']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['comentario']."</td>\n";
				print "	</tr>\n";
			}
		}

		print "  </table>\n";
		print "<input type='hidden' name='id_medio' id='id_medio' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		if($modo=='o'){
			print "</form>";
		}else{

		}
	}

} // fin clase


?>
