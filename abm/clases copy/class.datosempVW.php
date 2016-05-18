<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.datosempBSN.php");
include_once("clases/class.datosemp.php");
include_once("clases/class.emprendimientoBSN.php");
include_once("clases/class.emprendimientoVW.php");
include_once("clases/class.caracteristicaempBSN.php");
include_once("clases/class.auxiliaresPGDAO.php");
include_once("inc/funciones.inc");

class DatosempVW {

	private $datosemp;
	private $arrayForm;
	
	public function __construct($_datosemp=0){
		DatosempVW::creaDatosemp();
		if($_datosemp instanceof Datosemp )	{
			DatosempVW::seteaDatosemp($_datosemp);
		}
		if (is_numeric($_datosemp)){
			if($_datosemp!=0){
				DatosempVW::cargaDatosemp($_datosemp);
			}
		}
	}


	public function cargaDatosemp($_datosemp){
		$datosemp=new DatosempBSN($_datosemp);
		$this->seteaDatosemp($datosemp->getObjeto()); //datosemp());		
	}
	
	public function getIdDatosemp(){
		return $this->datosemp->getId_emp_carac();
		
	}
	
	protected function creaDatosemp(){
		$this->datosemp=new datosemp();
	}
	
	protected function seteaDatosemp($_datosemp){
		$this->datosemp=$_datosemp;
		$datosemp=new DatosempBSN($_datosemp);
		$this->arrayForm=$datosemp->getObjetoView();

	}
	

	public function cargaDatosDatosemp($id_emp,$pagorig){

		$carac = new CaracteristicaempBSN();
		$arrayEven = $carac->cargaColeccionForm();

		$empBSN = new EmprendimientoBSN($id_emp);
		
		$datosEmp2 = new Datosemp();
		$datosEmp2->setId_emp($id_emp);
		
		$evenBSN=new DatosempBSN($datosEmp2);
		
		print "<form action='carga_datosemp.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaDatosemp(this);'>\n";

		$menu=new Menu();
		$menu->dibujaMenu('carga');

		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Caracteristicas</td></tr>\n";
		$empBSN->getObjeto()->getNombre();
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		$ancho=700;
		
		foreach ($arrayEven as $elemCarac){
			print "<tr>";
			
			$datosEmp2->setId_carac($elemCarac['id_carac']);
			$evenBSN=new DatosempBSN($datosEmp2);
//			$evenBSN->seteaBSN($datosEmp2);
			
			$arrayDatos=$evenBSN->cargaColeccionEmpCarac($id_emp,$elemCarac['id_carac']);
			
			$titulo=$elemCarac['titulo'];
			$tipo=$elemCarac['tipo'];
			$comentario=$elemCarac['comentario'];
			$lista=$elemCarac['lista'];
			$maximo=$elemCarac['maximo'];
			
			$contenido=$arrayDatos[0]['contenido'];
			$comentarioD=$arrayDatos[0]['comentario'];

			
			switch ($tipo) {
				case 'CheckBox':
					$this->armaCheckbox($elemCarac['id_carac'],$titulo,$contenido,$comentario,$comentarioD,$ancho);						
					break;
				case 'Lista':
					$this->armaLista($elemCarac['id_carac'],$titulo,$lista,$contenido,$ancho);
					break;
				case 'Numerico':
					$this->armaNumerico($elemCarac['id_carac'],$titulo,$maximo,$comentario,$contenido,$comentarioD,$ancho);
					break;
				case 'Texto':
					$this->armaTexto($elemCarac['id_carac'],$titulo,$contenido,$ancho);
					break;
				case 'Web':
					$this->armaWeb($elemCarac['id_carac'],$titulo,$contenido,$ancho);
					break;
			}
			print "</tr>";
		}

		if($pagorig==1){
			print "<input type='hidden' name='pagorig' id='pagorig' value='".$pagorig."'>\n";		
		}
		print "<input type='hidden' name='id_emp' id='id_emp' value='".$id_emp."'>\n";
		
		print "<br>";
		print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	
	
	
	public function cargaDatosDatosemp2($id_emp,$pagorig){
		
		$empBSN = new EmprendimientoBSN($id_emp);
		$datosEmp2 = new Datosemp();
		$datosEmp2->setId_emp($id_emp);
		
		$evenBSN=new DatosempBSN($datosEmp2);
		
		$arrayEven=$evenBSN->cargaColeccionForm();
		
//		print_r($empBSN);
		
		print "<form action='carga_datosemp.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaDatosemp(this);'>\n";

		$menu=new Menu();
		$menu->dibujaMenu('carga');

		$carac = new CaracteristicaempBSN();

		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		
		print "<tr><td class='cd_celda_titulo'>Carga de Caracteristicas</td></tr>\n";
		
		$empBSN->getObjeto()->getNombre();
		
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";

		$ancho=700;
		
		foreach ($arrayEven as $elemCarac){
			print "<tr>";

			$carac->cargaById($elemCarac['id_carac']);
			$titulo=$carac->getObjeto()->getTitulo();
			$tipo=$carac->getObjeto()->getTipo();
			$comentario=$carac->getObjeto()->getComentario();
			$lista=$carac->getObjeto()->getLista();
			$maximo=$carac->getObjeto()->getMaximo();

			
			switch ($tipo) {
				case 'CheckBox':
					$this->armaCheckbox($elemCarac['id_carac'],$titulo,$elemCarac['contenido'],$comentario,$elemCarac['comentario'],$ancho);						
					break;
				case 'Lista':
					$this->armaLista($elemCarac['id_carac'],$titulo,$lista,$elemCarac['contenido'],$ancho);
					break;
				case 'Numerico':
					$this->armaNumerico($elemCarac['id_carac'],$titulo,$maximo,$comentario,$elemCarac['contenido'],$elemCarac['comentario'],$ancho);
					break;
				case 'Texto':
					$this->armaTexto($elemCarac['id_carac'],$titulo,$elemCarac['contenido'],$ancho);
					break;
				case 'Web':
					$this->armaWeb($elemCarac['id_carac'],$titulo,$elemCarac['contenido'],$ancho);
					break;
			}
			print "</tr>";
		}
		if($pagorig==1){
			print "<input type='hidden' name='pagorig' id='pagorig' value='".$pagorig."'>\n";		
		}
		print "<input type='hidden' name='id_emp' id='id_emp' value='".$id_emp."'>\n";
		
		print "<br>";
		print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}
	

	
	
/* Arma una lista desplegable con los valores pasados en LISTA, dejando marcado como Seleccion el dato pasado en CONTENIDO
*/	
	protected function armaLista($id_carac,$titulo,$lista,$contenido,$ancho){
		$array=split(';',$lista);
		$campo='l_'.$id_carac;
		$ancho-=130;
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		print "<td width='".$ancho."'>";
		print "<select name='".$campo."' id='".$campo."' class='campos_btn'>\n";
		for ($pos=0;$pos<sizeof($array);$pos++){
			print "<option value='".$array[$pos]."'";
			if ($array[$pos]==$contenido){
				print " SELECTED ";
			}
			print ">".$array[$pos]."</option>\n";
		}
		print "</select>\n";
		print "</td>\n";
	}
	
/* Arma una campo de ingreso ( si maximo = 0) o una lista desplegable con valores numericos desde 1 hasta el MAXIMO, dejando Seleccionado el valor indicado en CONTENIDO
   en el caso de estar indicado el ingreso de comentarios, habilita un campo de texto para el mismo.
*/
	protected function armaNumerico($id_carac,$titulo,$maximo,$comentario,$contenido,$contcoment,$ancho){
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=130;
		print "<td width='$ancho'>";
		if($maximo>0){
			$campo='n_'.$id_carac;
			print "<select name='".$campo."' id='".$campo."' class='campos_btn'>\n";
			for ($pos=1;$pos<=$maximo;$pos++){
				print "<option value='".$pos."'";
				if ($pos==$contenido){
					print " SELECTED ";
				}
				print ">".$pos."</option>\n";
			}
			print "</select>\n";
		}else{
			$campo='n_'.$id_carac;
			$this->armaCampo($campo,$contenido);
		}
		if($comentario=='Si'){
			$campo='com_'.$id_carac;
			$this->armaCampo($campo,$contcoment);
		}
		print "</td>\n";
		
	}

// Arma un campo de ingreso de tipo Texto
	protected function armaCampo($campo,$contenido){
		print "<input class='campos' type='text' name='$campo' id='$campo' value='".	$contenido ."' maxlength='200' size='40'>";
	}

	protected function armaCheckbox($id_carac,$titulo,$contenido,$comentario,$contcoment,$ancho){
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=130;
		print "<td width='$ancho'>";
		$campo='c_'.$id_carac;
		print "<input type='checkbox' id='".$campo."' name='".$campo."'";
		if ($contenido=='on'){
			print " checked ";
		}
		print ">\n";
		if($comentario=='Si'){
			$campo='com_'.$id_carac;
			$this->armaCampo($campo,$contcoment);
		}
		print "</td>\n";
	
	}
	
	protected function armaTexto($id_carac,$titulo,$contenido,$ancho){
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=130;
		print "<td width='$ancho'>";
		$campo='t_'.$id_carac;
		$this->armaCampo($campo,$contenido);
		print "</td>\n";
	}
	
	protected function armaWeb($id_carac,$titulo,$contenido,$ancho){
		include_once("fckeditor/fckeditor.php") ;
		$sBasePath = "./fckeditor/" ;
		$campo='w_'.$id_carac;
		$rFCKeditor = new FCKeditor($campo) ;
		$rFCKeditor->BasePath	= $sBasePath ;
		$rFCKeditor->Value		= $contenido ;
		$rFCKeditor->Height = 400;
		$rFCKeditor->Width = 700;
		print "<td class='cd_celda_texto' width='15%'>$titulo</td>";
		$ancho-=130;
		print "<td width='$ancho'>";
					$rFCKeditor->Create() ;
		print "</td>\n";
	}
	
/**
 * Lee desde un formulario los datos cargados para el datosemp.
 * Los registra en un objeto del tipo datosemp datosemp de esta clase
 *
 */
	public function leeDatosDatosempVW(){
		$datosaux=new Datosemp();
		$this->datosemp=array();
		$campos = array_keys($_POST);
		foreach ($campos as $datos){
			$posg=strpos($datos,"_");
			$tipo=substr($datos,0,$posg);//,strpos("_",$datos)-2);
			$id_carac=substr($datos,$posg+1);//,strpos("_",$datos)-1);
			$id_emp=$_POST['id_emp'];
			if($tipo=='c' || $tipo=='l' || $tipo=='n' || $tipo=='t' || $tipo=='w'){
				$this->datosemp[$id_carac]=array('id_emp'=>$id_emp,'contenido'=>$_POST[$datos]);
			}elseif($tipo=='com'){
				$this->datosemp[$id_carac]['comentario']=$_POST[$datos];
			}
		}

//		print_r($_POST);die();
	}

/**    OK
 * Muestra una tabla con los datos de los datosemps y una barra de herramientas o menu
 * conde se despliegan las opciones ingresables para cada item
 *
 */
	public function vistaTablaDatosemp($id){
		
		$emp=new EmprendimientoBSN($id);
		$fila=0;
		print "<span class='pg_titulo'>Listado de Caracteristicas de la emprendimiento de </span><br>\n";
		
		print "<span class='pg_titulo'>".$emp->getObjeto()->getNombre()."</span><br><br>\n";

		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		$menu=new Menu();
		$menu->dibujaMenu('lista');

		print "  <table class='cd_tabla' width='100%'>\n";
		
		$datoemp=new Datosemp();
		$datoemp->setId_emp($id);
		
		$evenBSN=new DatosempBSN($datoemp);
		
		$arrayEven=$evenBSN->cargaColeccionForm();

			print "    <tr>\n";
			print "     <td class='cd_lista_titulo' colspan='3'>&nbsp;</td>\n";
			print "     <td class='cd_lista_titulo' >Caracteristica</td>\n";
			print "     <td class='cd_lista_titulo' >Contenido</td>\n";
			print "     <td class='cd_lista_titulo' >Comentario</td>\n";
			print "	  </tr>\n";

			$carac = new CaracteristicaempBSN();

		if(sizeof($arrayEven)==0){
			print "No existen datos para mostrar";
		} else {
			print "<form name='lista' method='POST' action='respondeMenu.php'>";
			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(opcion,id){\n";
			print "     document.forms.lista.id_emp_carac.value=id;\n";
			print "   	submitform(opcion);\n";
			print "}\n";
			print "</script>\n";

			foreach ($arrayEven as $Even){
				if($fila==0){
					$fila=1;
				} else {
					$fila=0;
				}

				$carac->cargaById($Even['id_carac']);
				
				$titulo=$carac->getObjeto()->getTitulo();
//				$contenido=$this->muestraDato($Even['tipo'],$Even['contenido']);
//				$comentario=$Even['comentario'];
				
				$renglon= "<tr>\n";
				$renglon.= "	 <td class='row".$fila."'>\n";
				$renglon.= "    <a href='javascript:envia(343,".$Even['id_emp_carac'].");' border=0  onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				$renglon.= "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a></td>\n";
				
				$renglon.= "	 <td class='row".$fila."' width='16'>";
				$renglon.= "    <a href='javascript:envia(344,".$Even['id_emp_carac'].");' border='0'>";
				$renglon.= "       <img src='images/camera_edit.png' alt='Fotografias' title='Fotografías' border=0></a></td>\n";
				$renglon.= "	 <td class='row".$fila."'>";
				if($Even['activa']==1){
					$renglon.= "    <a href='javascript:envia(346,".$Even['id_emp_carac'].");' border=0>";
					$renglon.= "       <img src='images/web_no.png' alt='No Publicar en Web' title='No Publicar en Web' border=0></a>";
				}else{
					$renglon.= "    <a href='javascript:envia(345,".$Even['id_emp_carac'].");' border=0>";
					$renglon.= "       <img src='images/web.png' alt='Publicar en web' title='Publicar en Web' border=0></a>";
				}
				$renglon.= "  </td>\n";
			
				$renglon.= "	 <td class='row".$fila."'>".$titulo."</td>\n";
				$renglon.="	 <td class='row".$fila."'>".$Even['contenido']."</td>\n";
				$renglon.="	 <td class='row".$fila."'>".$Even['comentario']."</td>\n";
				
				$renglon.="	</tr>\n";
				
				print $renglon;
			}
		}
		
		print "  </table>\n";
		print "<input type='hidden' name='id_emp_carac' id='id_emp_carac' value=''>";
		print "<input type='hidden' name='id_emp' id='id_emp' value='$id'>";
//		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}


	protected function muestraDato($tipo,$contenido){
		if ($var=="CheckBox"){
			if($contenido=='on'){
				$imagen='check.png';
			}else{
				$imagen='uncheck.png';
			}
			print "<img src='images/$imagen' border=0>\n";
		}else{
			print "$contenido\n";
		}
	}
	
	public function grabaModificacion(){
		$retorno=false;
		$datosemp=new DatosempBSN($this->datosemp);
		$retUPre=$datosemp->actualizaDB();
		if ($retUPre){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaDatosemp($id_emp){
		$retorno=false;
		$datosemp=new DatosempBSN();
		$retIPre=$datosemp->grabaCaracteristica_Emp($id_emp,$this->datosemp);
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