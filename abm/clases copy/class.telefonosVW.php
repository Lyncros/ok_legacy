<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.telefonosBSN.php");
include_once("clases/class.telefonos.php");
include_once("inc/funciones.inc");

class TelefonosVW {

	private $telefonos;
	private $arrayForm;

	public function __construct($_telefonos=0){
		TelefonosVW::creaTelefonos();
		if($_telefonos instanceof Telefonos )	{
			TelefonosVW::seteaTelefonos($_telefonos);
		}
		if (is_numeric($_telefonos)){
			if($_telefonos!=0){
				TelefonosVW::cargaTelefonos($_telefonos);
			}
		}
	}


	public function cargaTelefonos($_telefonos){
		$telefonos=new TelefonosBSN($_telefonos);
		$this->seteaTelefonos($telefonos->getObjeto()); //telefonos());
	}

	public function getIdTelefonos(){
		return $this->telefonos->getId_telefono();

	}

	protected function creaTelefonos(){
		$this->telefonos=new telefonos();
	}

	protected function seteaTelefonos($_telefonos){
		$this->telefonos=$_telefonos;
		$telefonos=new TelefonosBSN($_telefonos);
		$this->arrayForm=$telefonos->getObjetoView();

	}


	public function cargaDatosTelefonos($destino=''){
		$objBSN= new TelefonosBSN();

		// Array ( [id_telefono] => 8 [telefonos] => CC [codarea] => Amigo [numero] => [interno] => Cliente amigo de otro cliente )

		$menu=new Menu();
		$menu->dibujaMenu('carga','opcion');

		print "<form action='carga_Telefonos.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaTelefonos(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_titulo'>Carga de Tipos de Codarea</td></tr>\n";

		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Tipo<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='80%'>";
		$objBSN->comboTipotel($this->arrayForm['tipotel']);
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Codigo de Pais</td>";
		print "<td width='80%'><input class='campos' type='text' name='codpais' id='codpais' value='";
		if($this->arrayForm['codpais']==''){
			$codpais='054';
		}else{
			$codpais=$this->arrayForm['codpais'];
		}
		print $codpais."' maxlength='3' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Codigo de Area</td>";
		print "<td width='80%'><input class='campos' type='text' name='codarea' id='codarea' value='";
		if($this->arrayForm['codarea']==''){
			$codarea='011';
		} else{
			$codarea=$this->arrayForm['codarea'];
		}
		print $codarea."' maxlength='3' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Numero</td>";
		print "<td width='80%'><input class='campos' type='text' name='numero' id='numero' value='".$this->arrayForm['numero'] ."' maxlength='10' size='80'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Interno</td>";
		print "<td width='80%'><input class='campos' type='text' name='interno' id='interno' value='".$this->arrayForm['interno']. "'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='20%'>Principal</td>";
		print "<td width='80%'><input class='campos' type='checkbox' name='principal' id='principal' ";
		if($this->arrayForm['principal']==1){
			print "checked";
		}
		print "></td></tr>\n";

		print "<input type='hidden' name='id_telefono' id='id_telefono' value='".$this->arrayForm['id_telefono'] ."'>\n";
		print "<input type='hidden' name='id_cont' id='id_cont' value='".$this->arrayForm['id_cont'] ."'>\n";
		print "<input type='hidden' name='tipocont' id='tipocont' value='".$this->arrayForm['tipocont'] ."'>\n";
		print "<input type='hidden' name='div' id='div' value='".$destino ."'>\n";
		
		print "<br>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}



	/**
	 * Lee desde un formulario los datos cargados para el telefonos.
	 * Los registra en un objeto del tipo telefonos telefonos de esta clase
	 *
	 */
	public function leeDatosTelefonosVW(){
		$telefonos=new TelefonosBSN();
		if($_POST['principal']=='on'){
			$_POST['principal']=1;
		}
		$this->telefonos=$telefonos->leeDatosForm($_POST);
	}

	/**    OK
	 * Muestra una tabla con los datos de los telefonoss y una barra de herramientas o menu
	 * donde se despliegan las opciones ingresables para cada item
	 * La misma corresponde a los telefonos del contacto indocado por tipo e ids pasados como parametros
	 * @param string $_tipo -> tipo de contacto al que corresponde el ID   U:usuario   C:cliente
	 * @param int $_id -> id del contacto
	 * @param char $modo -> modo de visualizacion de la lista 'v' vista 'o' operativo
	 */
	public function vistaTablaTelefonos($_tipo='',$_id=0,$modo='o'){
		$fila=0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_telefono.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "function modifica(t,tc,c){\n";
		print "     document.getElementById('opcion').value='m';\n";
		print "     document.location.href='carga_Telefonos.php?t='+t+'&tc='+tc+'&c='+c;\n";
		print "}\n";
		print "function borra(t,tc,c){\n";
		print "     document.getElementById('opcion').value='b';\n";
		print "     document.location.href='carga_Telefonos.php?t='+t+'&b=b&tc='+tc+'&c='+c;\n";
		print "}\n";

		print "</script>\n";

		print "<span class='pg_titulo'>Listado de Telefonos de Contacto</span><br><br>\n";
		if($modo=='o'){
//			$menu=new Menu();
//			$menu->dibujaMenu('lista','opcion');

			print "<form name='lista' method='POST' action='respondeMenu.php'>";
			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
			print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
		}else{
			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
		}
		print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Telefono</td>\n";
		print "     <td class='cd_lista_titulo'>Cod Pais</td>\n";
		print "     <td class='cd_lista_titulo'>Cod Area</td>\n";
		print "     <td class='cd_lista_titulo'>Numero</td>\n";
		print "     <td class='cd_lista_titulo'>Interno</td>\n";
		print "	  </tr>\n";


		$evenBSN=new TelefonosBSN();
		if($_tipo=='U'){
			$arrayEven=$evenBSN->coleccionByUsuarios($_id);
		}else{
			$arrayEven=$evenBSN->coleccionByCliente($_id);
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
					print "    <a href=\"javascript:modifica(".$Even['id_telefono'].",'".$Even['tipocont']."',".$Even['id_cont'].");\" border='0'>";
					print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href=\"javascript:borra(".$Even['id_telefono'].",'".$Even['tipocont']."',".$Even['id_cont'].");\" border=0>";
					print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
					print "  </td>\n";
				}
				print "	 <td class='row".$fila."'>";
				if($Even['principal']==1){
					print "<img src='images/phone.png' alt='Principal'>";
				}else{
					print "&nbsp;";
				}
				print "</td>\n";
				print "	 <td class='row".$fila."'>".$Even['tipotel']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['codpais']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['codarea']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['numero']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['interno']."</td>\n";
				print "	</tr>\n";
			}
		}

		print "  </table>\n";
		print "<input type='hidden' name='id_telefono' id='id_telefono' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		if($modo=='o'){
			print "</form>";
		}else{

		}
	}



	public function grabaModificacion(){
		$retorno=false;
		$telefonos=new TelefonosBSN($this->telefonos);
		$retUPre=$telefonos->actualizaDB();
		if ($retUPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaTelefonos(){
		$retorno=false;
		$telefonos=new TelefonosBSN($this->telefonos);
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
		//		$existente=$telefonos->controlDuplicado($this->telefonos->getTelefonos());
		//		if($existente){
		//			echo "Ya existe un telefonos con ese Titulo";
		//		} else {
		$retIPre=$telefonos->insertaDB();
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
