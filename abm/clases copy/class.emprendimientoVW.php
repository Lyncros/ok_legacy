<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.emprendimientoBSN.php");
include_once("clases/class.emprendimiento.php");
include_once("clases/class.tipo_empBSN.php");
include_once ("clases/class.zonaBSN.php");
include_once ("clases/class.localidadBSN.php");
include_once("inc/funciones.inc");
include_once("inc/funciones_gmap.inc");
include_once('generic_class/class.upload.php');

class EmprendimientoVW {

	private $emprendimiento;
	private $arrayForm;
	private $path;
	private $pathC;
	private $anchoG;
	private $anchoC;	
	
	public function __construct($_emprendimiento=0){
		EmprendimientoVW::creaEmprendimiento();
		if($_emprendimiento instanceof Emprendimiento )	{
			EmprendimientoVW::seteaEmprendimiento($_emprendimiento);
		}
		if (is_numeric($_emprendimiento)){
			if($_emprendimiento!=0){
				EmprendimientoVW::cargaEmprendimiento($_emprendimiento);
			}
		}
		$conf=CargaConfiguracion::getInstance();
		$this->path =$conf->leeParametro('path_fotos');
		$this->pathC =$conf->leeParametro('path_fotos_chicas');
		$this->anchoC =$conf->leeParametro('ancho_foto_chica');
		$this->anchoG =$conf->leeParametro('ancho_foto_grande');

	}


	public function cargaEmprendimiento($_emprendimiento){
		$emprendimiento=new EmprendimientoBSN($_emprendimiento);
		$this->seteaEmprendimiento($emprendimiento->getObjeto()); //emprendimiento());		
	}
	
	public function getIdEmprendimiento(){
		return $this->emprendimiento->getId_emp();
		
	}
	
	protected function creaEmprendimiento(){
		$this->emprendimiento=new emprendimiento();
	}
	
	protected function seteaEmprendimiento($_emprendimiento){
		$this->emprendimiento=$_emprendimiento;
		$emprendimiento=new EmprendimientoBSN($_emprendimiento);
		$this->arrayForm=$emprendimiento->getObjetoView();

	}
	
	public function cargaDatosEmprendimiento(){
		$zonaBSN = new ZonaBSN ();
		$locaBSN = new LocalidadBSN();
		$tipo_empBSN=new Tipo_empBSN();
		
		mapaGmap();
		
		print "<form action='carga_emprendimiento.php' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: return ValidaEmprendimiento(this);'>\n";

		$menu=new Menu();
		$menu->dibujaMenu('carga');

		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		
		print "<tr><td class='pg_titulo'>Carga de Emprendimientos</td></tr>\n";
		
		print "<tr><td align='center'>";
		print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";
				
		print "<tr><td class='cd_celda_texto' width='15%'>Zona<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='35%'>";
		if($this->arrayForm['id_zona']==''){
			$this->arrayForm['id_zona']=0;
		}
		if($this->arrayForm['id_loca']==''){
			$this->arrayForm['id_loca']=0;
		}
		$zonaBSN = new ZonaBSN ();
		$zonaBSN->comboZona($this->arrayForm['id_zona'],$this->arrayForm['id_loca'],2);
		print "</td>\n";
		
		print "<td class='cd_celda_texto' width='15%'>Localidad<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='35%'>";
		print "<div id='localidad'>";
		$loca = new Localidad();
		$loca->setId_loca($this->arrayForm['id_loca']);
		$loca->setId_zona($this->arrayForm['id_zona']);
		$locaBSN = new LocalidadBSN($loca);
		$locaBSN->comboLocalidad($this->arrayForm['id_loca'],$this->arrayForm['id_zona'],2);
		print "</div>";
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Tipo<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='35%'>";
		$tipo_empBSN->comboTipoEmp($this->arrayForm['id_tipo_emp'],2);
		print "</td>";
		
		print "<td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='35%'><input class='campos' type='text' name='nombre' id='nombre' value='".	$this->arrayForm['nombre'] ."' maxlength='250'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Logo</td>";
		print "<td width='35%'><input type='hidden' name='logo' id='logo' value='".$this->arrayForm['logo']."'>".$this->arrayForm['logo']." <input type='checkbox' name='blogo' id='blogo' > Marque la casilla para eliminar el Logo";
		print "	<input type='file' name='hlogo' id='hlogo' maxlength='200'>";
		print "</td>\n";

		print "<td class='cd_celda_texto' width='15%'>Foto</td>";
		print "<td width='35%'><input type='hidden' name='foto' id='foto' value='".$this->arrayForm['foto']."'>".$this->arrayForm['foto']." <input type='checkbox' name='bfoto' id='bfoto' >Marque la casilla para eliminar la Foto";
		print "	<input type='file' name='hfoto' id='hfoto' maxlength='200'>";
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Ubicacion</td>";
		print "<td width='85%' colspan='3'><input class='campos' type='text' name='ubicacion' id='ubicacion' value='".	$this->arrayForm['ubicacion'] ."' maxlength='250'></td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'> Descripcion </td>";
		print "<td width='85%' colspan='3'><input class='campos' type='text' name='descripcion' id='descripcion' value='".	$this->arrayForm['descripcion'] ."' maxlength='250'></td></tr>\n";


		print "<td class='cd_celda_texto' width='15%'>Comentario</td>";
		print "<td width='85%' colspan='3'><input class='campos' type='text' name='comentario' id='comentario' value='".	$this->arrayForm['comentario'] ."' maxlength='250'></td></tr>\n";
		
		print "<tr><td class='cd_celda_texto' width='15%'>Publica en WEB</td>";
		print "<td width='85%' colspan='3'><input class='campos' type='checkbox' name='activa' id='activa'";
		if ($this->arrayForm['activa']==1){
			print "checked ";
		}
		print "></td></tr>\n";
		
		print "<tr><td class='cd_celda_texto' width='15%'>Muestra mapa ubicacion</td>";
		print "<td width='85%' colspan='3'><input type='button' class='campos' id='ver' name='ver' value=\"Muestra mapa de ubicacion\" onclick='javascript: popupMapa(\"e\");'></td></tr>\n";
		
//		print "<tr><td class='cd_celda_texto' width='15%'>Lat. (googlemap)</td>";
//		print "<td width='35%'><input class='campos' type='text' name='goglat' id='goglat' value='".	$this->arrayForm['goglat'] ."' maxlength='250'></td>\n";
//		print "<td class='cd_celda_texto' width='15%'>Long. (googlemap)</td>";
//		print "<td width='35%'><input class='campos' type='text' name='goglong' id='goglong' value='".	$this->arrayForm['goglong'] ."' maxlength='250'></td></tr>\n";

		print "<input class='campos' type='hidden' name='goglat' id='goglat'  value='";
		if($this->arrayForm['goglat']==""){
			echo "0";
		}else{
			echo $this->arrayForm['goglat'];
		}
		print "' maxlength='250' size='80'>\n";
		print "<input class='campos' type='hidden' name='goglong' id='goglong' value='";
		if($this->arrayForm['goglong']==""){
			echo "0";
		}else{
			echo $this->arrayForm['goglong'];
		}
		print "' maxlength='250' size='80'>\n";
		
		print "<input type='hidden' name='id_emp' id='id_emp' value='".$this->arrayForm['id_emp'] ."'>\n";
		
		print "<br>";
		print "<tr><td colspan='4' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	


/**
 * Lee desde un formulario los datos cargados para el emprendimiento.
 * Los registra en un objeto del tipo emprendimiento emprendimiento de esta clase
 *
 */
	public function leeDatosEmprendimientoVW(){
		$emprendimiento=new EmprendimientoBSN();
		
		$imglogo=false;
		$imgfoto=false;
		if($_POST['blogo']=="on"){
			$this->borraFoto($_POST['logo']);
			$nombre1="";
		}else{
			if ($_FILES['hlogo']['type']=='image/jpeg' || $_FILES['hlogo']['type']=='image/gif' || $_FILES['hlogo']['type']=='image/png'){
				$imglogo=true;
			}
			if (trim($_FILES['hlogo']['name'])=='' || !isset($_FILES['hlogo']['name']) || !$imglogo){
				$nombre1=$_POST['logo'];
			} else {
				$nombre1=$this->uploadImagen('hlogo',$_POST['id']);
			}
		}
		$_POST['logo']=$nombre1;
		

		if($_POST['bfoto']=="on"){
			$this->borraFoto($_POST['foto']);
			$nombre2="";
		}else{
			if ($_FILES['hfoto']['type']=='image/jpeg' || $_FILES['hfoto']['type']=='image/gif' || $_FILES['hfoto']['type']=='image/png'){
				$imgfoto=true;
			}
			if (trim($_FILES['hfoto']['name'])=='' || !isset($_FILES['hfoto']['name']) || !$imgfoto){
				$nombre2=$_POST['foto'];
			} else {
				$nombre2=$this->uploadImagen('hfoto',$_POST['id']);
			}
		}
		$_POST['foto']=$nombre2;
		
		$this->emprendimiento=$emprendimiento->leeDatosForm($_POST);
		
		if($this->emprendimiento->getActiva()=="on"){
			$this->emprendimiento->setActiva(1);
		} else {
			$this->emprendimiento->setActiva(0);
		}
	}

	protected function borraFoto($_nombre){
		$nombre=$this->path."/".$_nombre;
		if(file_exists($nombre)){
			unlink($nombre);
		}
		$nombre=$this->pathC."/".$_nombre;
		if(file_exists($nombre)){
			unlink($nombre);
		}
	}	
	
	protected function uploadImagen($campo,$id){
		$retorno='';
		if($_FILES[$campo]['type']=='image/jpeg' || $_FILES[$campo]['type']=='image/gif' || $_FILES[$campo]['type']=='image/png'){
			$fotoup= new Upload($_FILES[$campo],'es_ES');
			$nom=$_FILES[$campo]['name'];
			if($campo=='hlogo'){
				$pre='L_';
			}else{
				$pre='E_';
			}
			$nombre=$pre.$_POST[$id].'_'.substr($nom,0,strlen($nom)-4);
			if($fotoup->uploaded){
				$fotoup->image_resize 			= true;
				$fotoup->image_ratio_y 			= true;
				$fotoup->file_new_name_body   	= $nombre;

				$fotoup->image_x 				= $this->anchoG;
				
				$fotoup->Process($this->path);
			
			// we check if everything went OK
				if ($fotoup->processed) {
				} else {
				// one error occured
					echo '<fieldset>';
					echo '  <legend>file not uploaded to the wanted location</legend>';
					echo '  Error: ' . $fotoup->error . '';
					echo '</fieldset>';
				}
				
				$fotoup->image_x 				= $this->anchoC;
				$fotoup->file_new_name_body   	= $nombre;
								
				$fotoup->Process($this->pathC);
			
			// we check if everything went OK
				if ($fotoup->processed) {
				} else {
				// one error occured
					echo '<fieldset>';
					echo '  <legend>file not uploaded to the wanted location</legend>';
					echo '  Error: ' . $fotoup->error . '';
					echo '</fieldset>';
				}

			}else{
				echo '<fieldset>';
				echo '  <legend>file not uploaded on the server</legend>';
				echo '  Error: ' . $fotoup->error . '';
				echo '</fieldset>';
			}
			$retorno = $fotoup->file_dst_name;
		}
		return $retorno;
	}
	
/**    OK
 * Muestra una tabla con los datos de los emprendimientos y una barra de herramientas o menu
 * conde se despliegan las opciones ingresables para cada item
 *
 */
	public function vistaTablaEmprendimiento($pagina=1){
		$zona = new Zona ();
		$zonaBSN = new ZonaBSN ();
		$local = new Localidad();
		$localaBSN = new LocalidadBSN();
		$tipopropBSN = new Tipo_empBSN();
		$tipo_empBSN = new Tipo_empBSN();
		$config= CargaConfiguracion::getInstance();
		$registros=$config->leeParametro('regemp_adm');
		
		$fila=0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.id_emp.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "function filtra(){\n";
		print "   document.lista.action='lista_emprendimiento.php?i=';\n";
		print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
		print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
		print "   document.forms.lista.fid_tipo_emp.value=document.forms.lista.aux_emp.value;\n";
//		print "alert(document.forms.lista.operacion.value);\n";
		print "   document.lista.submit();\n";
		print "}\n";
		print "function limpiafiltro(){\n";
		print "   document.lista.action='lista_emprendimiento.php?i=';\n";
		print "   document.forms.lista.fid_zona.value=0;\n";
		print "   document.forms.lista.fid_loca.value=0;\n";
		print "   document.forms.lista.fid_tipo_emp.value=0;\n";
		print "   document.lista.submit();\n";
		print "}\n";
		print "function paginar(pagina){\n";
//		print "   document.lista.action='lista_propiedad.php?i=';\n";		
		print "   document.forms.lista.pagina.value=pagina;\n";
		print "   filtra();\n";
//		print "   document.lista.submit();\n";
		print "}\n";
		
		print "</script>\n";

		print "<span class='pg_titulo'>Listado de Emprendimientos</span><br><br>\n";
		
		print "<form name='lista' method='POST' action='respondeMenu.php'>";

		$menu=new Menu();
		$menu->dibujaMenu('lista');
//		$menu->dibujaMenu('lista','opcion');

// Filtro		
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td colspan='3' class='cd_lista_filtro' style='text-align: left; padding-left: 10px;'>Filtrar por </td>";
		print "	</tr>";

		print "<tr><td class='cd_celda_texto' width='33%'>Zona</td>";
		print "<td class='cd_celda_texto' width='33%'>Localidad</td>";
		print "<td class='cd_celda_texto' width='33%'>Tipo de Emprendimiento</td></tr>";
		
		
		
		print "<tr><td width='33%'>";
		if(isset($_POST['fid_zona']) && $_POST['fid_zona']!=0){
			$aux_zona=$_POST['fid_zona'];
		}else{
			$aux_zona=0;			
		}
		if(isset($_POST['fid_loca']) && $_POST['fid_loca']!=0){
			$aux_loca=$_POST['fid_loca'];
		}else{
			$aux_loca=0;			
		}
		if(isset($_POST['fid_tipo_emp']) && $_POST['fid_tipo_emp']!=0){
			$aux_emp=$_POST['fid_tipo_emp'];
		}else{
			$aux_emp=0;			
		}
		
		$zona1BSN = new ZonaBSN ();
		$zona1BSN->comboZona($aux_zona,$aux_loca,1,'aux_zona','aux_loca');
		
		print "</td>\n";
		print "<td width='33%'>";
		print "<div id='localidad'>";
		$loca = new Localidad();
		$loca->setId_loca($aux_loca);
		$loca->setId_zona($aux_zona);
		$locaBSN = new LocalidadBSN($loca);
		$locaBSN->comboLocalidad($aux_loca,$aux_zona,1,'aux_loca');
		print "</div>";
		print "</td>\n";

		print "<td width='33%'>";
		$tipo_empBSN->comboTipoEmp($aux_emp,1,'aux_emp');
		print "</td></tr>";

		print "<tr><td></td><td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:filtra();' value='Enviar'></td>\n";
		print "<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></td>\n";
		print "</tr>\n";
		print "</table>\n";

		
// Fin Filtro		
		
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo' colspan='4'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Nombre</td>\n";
		print "     <td class='cd_lista_titulo'>Zona</td>\n";
		print "     <td class='cd_lista_titulo'>Localidad</td>\n";
		print "     <td class='cd_lista_titulo'>Ubicacion</td>\n";
		print "     <td class='cd_lista_titulo'>Logo</td>\n";
		print "     <td class='cd_lista_titulo'>Tipo Emp.</td>\n";
		print "	  </tr>\n";

		$evenBSN=new EmprendimientoBSN();
//		$arrayEven=$evenBSN->cargaColeccionForm();
		$arrayEven=$evenBSN->cargaColeccionFiltro($aux_zona,$aux_loca,$aux_emp,-1,$pagina);

		$cantreg=$evenBSN->cantidadRegistrosFiltro($aux_zona,$aux_loca,$aux_emp,-1);
		$cantr=$cantreg/$registros;
		$cante=$cantreg%$registros;
		if($cante !=0 ){
			$paginas = intval($cantr + 1);
		}else{
			$paginas = $cantr;
		}
//		$paginas=intval($cantreg%$registros)+1;
//		echo "$cantr - $cante - $registros<br>";
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
				print "    <a href='javascript:envia(32,".$Even['id_emp'].");' border='0'>";
				print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				print "	 <td class='row".$fila."'>";
				print "    <a href='javascript:envia(33,".$Even['id_emp'].");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a></td>";
				print "	 <td class='row".$fila."'>";
				print "    <a href='javascript:envia(34,".$Even['id_emp'].");' border=0>";
				print "       <img src='images/book_edit.png' alt='Editar Caracteristicas' title='Editar Caracteristicas' border=0></a>";
				print "  </td>\n";
				print "	 <td class='row".$fila."'>";
				if($Even['activa']==1){
					print "    <a href='javascript:envia(36,".$Even['id_emp'].");' border=0>";
					print "       <img src='images/web_no.png' alt='No Publicar en Web' title='No Publicar en Web' border=0></a>";
				}else{
					print "    <a href='javascript:envia(35,".$Even['id_emp'].");' border=0>";
					print "       <img src='images/web.png' alt='Publicar en web' title='Publicar en Web' border=0></a>";
				}
				print "  </td>\n";

				print "	 <td class='row".$fila."'>".$Even['nombre']."</td>\n";
				print "	 <td class='row".$fila."'>";
				$zonaBSN->setId ( $Even ['id_zona'] );
				$zonaBSN->cargaById( $Even['id_zona'] );
				print $zonaBSN->getObjeto()->getNombre_zona();
				print "</td>\n";
				print "	 <td class='row".$fila."'>";
				$localaBSN->setId($Even['id_loca']);
				$localaBSN->cargaById( $Even['id_loca'] );
				print $localaBSN->getObjeto()->getNombre_loca();
				print "</td>\n";
				print "	 <td class='row".$fila."'>".$Even['ubicacion']."</td>\n";
				print "	 <td class='row".$fila."'>".$Even['logo']."</td>\n";
				print "	 <td class='row".$fila."'>";
				$tipopropBSN->setId($Even['id_tipo_emp']);
				$tipopropBSN->cargaById($Even['id_tipo_emp']);
				print $tipopropBSN->getObjeto()->getTipo_emp();
				print "</td>\n";
				print "	</tr>\n";
			}
			print "<tr><td align='center' colspan='19'>";
			if($pagina>1){
				print "    <a href='javascript:paginar(1);'>";
				print "       <img src='images/resultset_first.png' alt='Inicio' title='Inicio' border='0'></a>&nbsp;";
				if($pagina>2){
					print "    <a href='javascript:paginar(". ($pagina - 1) .");'>";
					print "       <img src='images/resultset_previous.png' alt='Anterior' title='Anterior' border='0'></a>&nbsp;-&nbsp;";
				}
				for($x=$pagina-5;$x<$pagina;$x++){
					if($x>0){
						print "<a href='javascript:paginar(". $x .");'>$x</a>&nbsp;-&nbsp;";
					}
				}
			}
            print "<span style='font-weight: bold; color:#FFF; background-color:#99BBEA;'>&nbsp;$pagina&nbsp;</span>-&nbsp;";
			if($pagina<$paginas){
				for($x=$pagina+1;$x<$pagina+5;$x++){
					if($x<=$paginas){
						print "<a href='javascript:paginar(". $x .");'>".$x."</a>&nbsp;-&nbsp;";
					}
				}
				if($pagina < $paginas-1){
					print "    <a href='javascript:paginar(". ($pagina + 1) .");'>";
					print "       <img src='images/resultset_next.png' alt='Siguiente' title='Siguiente' border='0'></a>&nbsp;";
				}
				print "    <a href='javascript:paginar($paginas);'>";
				print "       <img src='images/resultset_last.png' alt='Ultima' title='Ultima' border='0'></a>&nbsp;";
			}
			print "</td></tr>";
			
			
		}
		
		print "  </table>\n";
		print "<input type='hidden' name='id_emp' id='id_emp' value=''>";
		print "<input type='hidden' name='fid_tipo_emp' id='fid_tipo_emp' value=''>";
		print "<input type='hidden' name='fid_zona' id='fid_zona' value=''>";
		print "<input type='hidden' name='fid_loca' id='fid_loca' value=''>";
		print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>";

		print "</form>";
	}

	
	public function grabaModificacion(){
		$retorno=false;
		$emprendimiento=new EmprendimientoBSN($this->emprendimiento);
		$retUPre=$emprendimiento->actualizaDB();
		if ($retUPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaEmprendimiento(){
		$retorno=false;
		$emprendimiento=new EmprendimientoBSN($this->emprendimiento);
//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
//		$existente=$emprendimiento->controlDuplicado($this->emprendimiento->getEmprendimiento());
//		if($existente){
//			echo "Ya existe un emprendimiento con ese Titulo";
//		} else {
			$retIPre=$emprendimiento->insertaDB();
//			die();
			if ($retIPre){
				$this->emprendimiento->setId_emp($emprendimiento->buscaID());
				echo "Se proceso la grabacion en forma correcta<br>";
				$retorno=true;
			} else {
				echo "Fallo la grabación de los datos<br>";
			}
//		} // Fin control de Duplicados
		return $retorno;
	}	
	
	public function muestraNombre(){

		print "<span class='pg_titulo'>Emprendimiento:".$this->arrayForm['nombre']." ".$this->arrayForm['logo'];
		print "</span><br><br>\n";
	}
	
} // fin clase


?>
