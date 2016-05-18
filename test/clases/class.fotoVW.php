<?php
include_once ('generic_class/class.VW.php');
include_once("clases/class.propiedad.php");
include_once("clases/class.foto.php");
include_once("clases/class.fotoBSN.php");
include_once("clases/class.propiedadVW.php");
include_once("generic_class/class.menu.php");
include_once("generic_class/class.cargaConfiguracion.php");
include_once('generic_class/class.upload.php');
include_once("inc/funciones.inc");



class FotoVW extends VW {

	protected $clase="Foto";
	protected $foto;
	protected $nombreId="Id_foto";
	private $path;
	private $pathC;
	private $anchoG;
	private $anchoC;
	private $cant;

	public function __construct($_foto=0){
		FotoVW::creaObjeto();
		if($_foto instanceof Foto )	{
			FotoVW::seteaVW($_foto);
		}
		if (is_numeric($_foto)){
			if($_foto!=0){
				FotoVW::cargaVW($_foto);
			}
		}
		FotoVW::cargaDefinicionForm();
		$conf=CargaConfiguracion::getInstance();
		$this->cant =$conf->leeParametro('cant_fotos');
		$this->path =$conf->leeParametro('path_fotos');
		$this->pathC =$conf->leeParametro('path_fotos_chicas');
		$this->anchoC =$conf->leeParametro('ancho_foto_chica');
		$this->anchoG =$conf->leeParametro('ancho_foto_grande');
	}

	public function setIdPropiedad($_tema){
		$this->foto->setId_prop($_tema);
		$this->arrayForm['id_prop']=$_tema;
	}

	public function getIdPropiedad(){
		return $this->foto->getId_prop();
	}


	public function cargaDatosVW(){
		if($this->arrayForm['id_foto']==0){
			$foto0=new Foto();
			$foto0->setId_prop($this->arrayForm['id_prop']);
			$fotoaux= new FotoBSN($foto0);
			$pos=$fotoaux->proximaPosicion();
		}else{
			$pos=$this->arrayForm['posicion'];
		}

		print "<script type='text/javascript' language='javascript'>\n";
		print "function muestraCargaData(){\n";
		print "   document.getElementById('cargaData').style.display='block';\n";
		print "}\n";
		print "function submitForm(){\n";
		print "    window.open('','ventanaFoto','width=300,height=200');\n";
		print "}\n";
		print "</script>\n";
		
		print "<div id='cargaData' name='cargaData' style='display:none;'>";
		print "<form name='lista' action='carga_Foto.php' target='ventanaFoto' enctype='multipart/form-data' method='post' onSubmit='submitForm();'>";
		print "<div class='pg_titulo'>Carga de Fotos</div>\n";

		print "<table width='800' align='center' bgcolor='#f1f1f1'>";
		for($x=0;$x<$this->cant;$x++){
			print "<tr><td class='cd_celda_texto' width='100'>Foto</td>";
			print "	<td class='cd_celda_input' width='700'><input type='file' name='foto_$x' id='foto_$x' size='100' maxlength='200'>";
			print "</td></tr>\n";
		}
		print "<input type='hidden' name='posicion' id='posicion' value='".$pos."' size='2' maxlength='2'>\n";
		print "<input type='hidden' name='hfoto' id='hfoto' value='".$this->arrayForm['hfoto']."'>".$this->arrayForm['hfoto'];
		print "		<input type='hidden' name='id_foto' id='id_foto' value='".$this->arrayForm['id_foto']."'>\n";
		print "		<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop']."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='center'><input type='submit' value='Submit'></td></tr></table>";
		print "</form>";
		print "</ div>";
	}


	/**
	 * Arma una tabla con la foto y el epigrafe en el caso que existiera
	 *
	 * @param int $ancho: ancho del espacio dinde se va mostrar la foto.
	 */
	public function muestraFoto($ancho){
		print $this->armaVistaFoto($ancho);
	}

	/**
	 * Define la forma en quese visualiza la foto calculando el ancho y alto proporcional de la misma
	 * tomando como base el valor del ancho indicado
	 *
	 * @param int $ancho -> ancho de lespacio donde se despliega la foto
	 * @return string cn el formato de la foto y su nombre
	 */
	public function armaVistaFoto($ancho){
		$vista="<table width='$ancho' align='center'>";
		$anchof=40;
		$altof=25;

		$vista.= "<tr><td ><a href=\"javascript:llamarasincrono('muestra_foto.php?f=".$this->path."/".$this->arrayForm['hfoto']."', 'foto');\" border='0' ><img src='".$this->path."/".$this->arrayForm['hfoto']."' width='$anchof' height='$altof' border='0'></a></td></tr>\n";

		$vista.= "</table>";
		return $vista;
	}

	//************************************  C  O  R  R  E  G  I  R  *************************************************************
	/**
	 * Arma un array con las fotos encontradas para la posicion de la nota que se especifica
	 *
	 * @param int $nota -> ID de la nota
	 * @param int $ancho -> espacio disponible para mostrar la foto
	 * @param int $pos -> posicion donde se muestra la foto 0-Top, 1-Izq, 2-Der, 3 Pie
	 * @return array de fotos formateadas y proporcionadas para ser vistas
	 */
	public function armaArrayFotosNota($noticia,$ancho,$pos){
		$fotoDB=new FotoPGDAO();
		$arrayFoto=array();
		$resFoto=$fotoDB->ColeccionByNoticia($noticia);
		if (pg_num_rows($resFoto)!=0){
			while($row=pg_fetch_array($resFoto)){
				if($row['posicion']==$pos){
					$this->cargaFoto($row['id_foto']);
					$arrayFoto[]= $this->armaVistaFoto($ancho);
				}
			}
		}
		return $arrayFoto;
	}

	/**
	 * Recalcula el ancho de las fotos segun el espacio dispnible para publicar la misma
	 *
	 * @param int $ancho :especifica el ancho del espacio donde se va a publicar la foto
	 * @param char $parametro : especifica si se calcula "h" -> el alto  o  "w" -> el ancho
	 * @return int : responde al valor nuevo del alto o ancho segun PARAMETRO
	 */
	protected function resizeFoto($ancho,$parametro){
		//		$anchof=imagesx($this->path."/".$this->foto->getFoto());
		//		$altof=imagesy($this->path."/".$this->foto->getFoto());
		$anchof=500;
		$altof=500;
		$relacion=1;
		$retorno=0;
		if($anchof >= $ancho){
			$retorno=$ancho;
			$relacion=$ancho/$anchof;
		} else {
			$retorno=$anchof;
		}
		if($parametro =="h"){
			if($relacion==1){
				$retorno=$altof;
			} else {
				$retorno=$altof*$relacion;
			}
		}
		return $retorno;
	}

	/**
	 * Arma el array para la GALERIA
	 *
	 * @param int $tema : id del tema
	 * @param int $ancho : ancho de la vista de la foto
	 */
	public function armaArrayGaleria($tema,$ancho){
		$arrayFoto=array();
		$fotoBSN= new FotoBSN();
		$arrayFoto=$fotoBSN->cargaColeccionFormByPropiedad($tema);
		if(sizeof($arrayFoto)!=0){
			foreach ($arrayFoto as $foto) {
				$this->arrayForm=$foto;
				$arrayGal[]= $this->armaVistaFoto($ancho);
			}
		}

		return $arrayGal;
	}

	public function retornaFoto($tema,$ini,$desp){
		$pos=0;
		$retorno="";
		if(is_numeric($ini)){
			$pos=$ini;
		}

		$arrayFoto=array();
		$fotoBSN= new FotoBSN();
		$arrayFoto=$fotoBSN->cargaColeccionFormByPropiedad($tema);
		if(sizeof($arrayFoto)!=0){
			$retorno= $this->path."/".$arrayFoto[$pos]['hfoto'];
		}
		return $retorno;
	}

	/**
	 * Muestra una tabla con los datos de los Fotoes para un curso y una barra de herramientas o menu
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
			print "function envia(nameForm,opcion,id){\n";
			print "     document.forms.lista.id_foto.value=id;\n";
			print "   	submitform(nameForm,opcion);\n";
			print "}\n";
			print "function borra(prop,foto){\n";
			print "   window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=b', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');\n";
			print "}\n";
			print "function subir(prop,foto){\n";
			print "   window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=u', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');\n";
			print "}\n";
			print "function bajar(prop,foto){\n";
			print "   window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=d', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');\n";
			print "}\n";
                        
			print "</script>\n";

			
			
			print "<div class='pg_titulo'>Listado de Fotos Disponibles para la Propiedad</div><br>\n";
			$propVW->muestraDomicilio();

			$arrayTools=array(array('Nuevo','images/picture--plus.png','muestraCargaData()'),array('Regresar','images/ui-button-navigation-back.png','KillMe()'));
			$menu=new Menu();
			$menu->barraHerramientas($arrayTools);
			
			print "  <table class='cd_tabla'>\n";
			print "    <tr>\n";
			print "     <td class='cd_lista_titulo' width='50' colspan='3'>&nbsp;</td>\n";
			print "     <td class='cd_lista_titulo' width='400' colspan='2'>Foto</td>\n";
			print "     <td class='cd_lista_titulo' width='100'>Posicion</td>\n";
			print "	  </tr>\n";

			$fotoBSN= new FotoBSN();
			$arrayFoto=$fotoBSN->cargaColeccionFormByPropiedad($id_prop);

			if(sizeof($arrayFoto)==0){
				print "No existen datos para mostrar";
			} else {
				foreach ($arrayFoto as $foto){
					if($fila==0){
						$fila=1;
					} else {
						$fila=0;
					}
					print "<tr>\n";

					print "	 <td class='row".$fila."'>";
					print "    <a href='javascript:borra($id_prop,".$foto['id_foto'].");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
					print "       <img src='images/picture--minus.png' alt='Borrar' title='Borrar' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href='javascript:subir($id_prop,".$foto['id_foto'].");' border='0'>";
					print "       <img src='images/up.png' alt='Subir' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href='javascript:bajar($id_prop,".$foto['id_foto'].");' border=0>";
					print "       <img src='images/down.png' alt='Bajar' border=0></a>";
					print "  </td>\n";


					print "	 <td class='row".$fila."'>";
					print "<img src=".$this->pathC."/".$foto['hfoto']." width='80'></td>\n";
					print "	 <td class='row".$fila."'>";
					print $foto['hfoto']."</td>\n";
					print "	 <td class='row".$fila."'>".$foto['posicion']."</td>\n";
					print "	</tr>\n";
				}
			}
			print "  </table>\n";
			//			print "<input type='hidden' name='opcion' id='opcion' value=''>";
			print "<input type='hidden' name='id_prop' id='id_prop' value='".$id_prop."'>";
			print "<input type='hidden' name='id_foto' id='id_foto' value='".$id_foto."'>";
			print "</form>";
		}
	}


	public function muestraFotosProp($id_prop=0){
		$propVW = new PropiedadVW($id_prop);

		$fila=0;
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo' colspan='2'>Fotos de la Propiedad</td></tr>\n";

		if($id_prop==0 || is_nan($id_prop)) {
			echo "Debe seleccionar un Propiedad para poder verificar sus Fotos";
		} else {

			$fotoBSN= new FotoBSN();
			$arrayFoto=$fotoBSN->cargaColeccionFormByPropiedad($id_prop);

			if(sizeof($arrayFoto)==0){
				print "No existen datos para mostrar";
			} else {
				foreach ($arrayFoto as $foto){
					print "<tr><td width='45%'>".$foto['hfoto']."<br>";
					print "<img src=".$this->pathC."/".$foto['hfoto']." width='80'></td>\n";
					print "	</tr>\n";
					print "<tr><td class='cd_celda_texto' height='2px'>&nbsp;</td>\n";
					print "	</tr>\n";
				}
			}
		}
		print "  </table>\n";

	}




	public function leeDatosFotoVW(){
		$foto=new FotoBSN();
		$retorno=false;

		$paramFoto = array(
        "pathDestino" => $this->path,
        "anchoMaximo" => $this->anchoG,
        "altoMaximo" => 300,
        "relacionW" => 1.565,
        "relacionH" => 0.637,
        "tamanoMaximo" => 15000000
		);

		for($x=0;$x<$this->cant;$x++){

			if($_FILES['foto_'.$x]['type']=='image/jpeg' || $_FILES['foto_'.$x]['type']=='image/gif' || $_FILES['foto_'.$x]['type']=='image/png'){

				$objCarga = new Upload($_FILES['foto_'.$x],'es_ES');
				$nom=remover_acentos($_FILES['foto_'.$x]['name']);
				$nombre='P_'.$_POST['id_prop'].'_'.substr($nom,0,strlen($nom)-4);
				$anchoOrig = $objCarga->image_src_x;
				$altoOrig = $objCarga->image_src_y;
				//list($anchoOrig, $altoOrig) = getimagesize($_FILES['foto_'.$x]['tmp_name']);

				if ($anchoOrig < $altoOrig) {
					$cropAncho = 0;
					$cropAlto = ($altoOrig - ($anchoOrig * $paramFoto['relacionH']));
				} else {
					if(($anchoOrig/$altoOrig) < $paramFoto['relacionW']) {
						$cropAncho = 0;
						$cropAlto = ($altoOrig - ($anchoOrig / $paramFoto['relacionW'])) / 2;
					}else {
						$cropAlto = 0;
						$cropAncho = ($anchoOrig - ($altoOrig * $paramFoto['relacionW'])) / 2;
					}
				}

				$objCarga->file_is_image = true;
				$objCarga->file_new_name_body = $nombre;
				//    $objCarga->image_ratio = true;
				$objCarga->image_resize = true;
				$objCarga->image_ratio_y = true;
				$objCarga->image_x = $paramFoto['anchoMaximo'];
				//    $objCarga->image_y = $paramFoto['altoMaximo'];
				//$objCarga->image_precrop = array($cropAlto, $cropAncho, $cropAlto, $cropAncho);
				$objCarga->image_precrop = array($cropAlto, $cropAncho, 0, $cropAncho);
				
				$objCarga->image_watermark = 'images/marca_agua.png';
				$objCarga->image_watermark_x = $paramFoto['anchoMaximo'] / 2;
				$objCarga->image_watermark_y = - 10;

				$objCarga->Process($paramFoto['pathDestino']);

				if ($objCarga->processed) {
					//echo 'image resized';
					//$objCarga->clean();
				} else {
					echo 'error : ' . $objCarga->error;
					//echo $objCarga->log;
				}
				$objCarga->image_resize = true;
				$objCarga->image_ratio_y = true;

				$objCarga->image_x = $this->anchoC;
				$objCarga->file_new_name_body = $nombre;
				//$objCarga->image_precrop = array($cropAlto, $cropAncho, $cropAlto, $cropAncho);
				$objCarga->image_precrop = array($cropAlto, $cropAncho, 0, $cropAncho);
				
				$objCarga->image_watermark = 'images/marca_agua_ch.png';
				$objCarga->image_watermark_x = $this->anchoC / 2;
				$objCarga->image_watermark_y = - 5;
				$objCarga->image_watermark_no_zoom_out = true;
				
				
				$objCarga->Process($this->pathC);

				if ($objCarga->processed) {
					//echo 'image resized';
					//$objCarga->clean();
				} else {
					echo 'error : ' . $objCarga->error;
					//echo $objCarga->log;
				}

				$retorno=true;
				$_POST['hfoto'][$x]=$objCarga->file_dst_name;
				$this->foto=$foto->leeDatosForm($_POST);
				$objCarga->clean();
			}
		}
		return $retorno;
	}




	public function grabaModificacion(){
		$retorno=false;
		$foto=new FotoBSN($this->foto);
		$retUFoto=$foto->actualizaDB();
		if ($retUFoto){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabacion de los datos<br>";
		}
		return $retorno;
	}

	public function grabaFoto(){
		$retorno=false;
		$arrayfotos= $this->foto->getFoto();
		//print_r($arrayfotos);echo"<br>";
		$mfoto = new Foto();
		$mfoto->setId_foto($this->foto->getId_foto());
		$mfoto->setId_prop($this->foto->getId_prop());
		$pos = $this->foto->getPosicion();
		for($x=0;$x<$this->cant;$x++){
			if($arrayfotos[$x]!=''){
				$mfoto->setFoto($arrayfotos[$x]);
				$mfoto->setPosicion($pos + $x);
				//print_r($mfoto);echo"<br>";
				$foto=new FotoBSN($mfoto);
				$retIFoto=$foto->insertaDB();
				if ($retIFoto){
					echo "Se proceso la grabacion en forma correcta<br>";
					$retorno=true;
				} else {
					echo "Fallo la grabaci�n de los datos<br>";
				}
			}

		}
		return $retorno;
	}

	protected function descripcionPropiedadFoto($id){
		$tema=new Propiedad();
		return $tema->getPropiedadFoto($id);
	}



} // fin clase


?>