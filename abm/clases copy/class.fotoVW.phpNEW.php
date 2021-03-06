<?php
include_once("clases/class.propiedad.php");
include_once("clases/class.foto.php");
include_once("clases/class.fotoBSN.php");
include_once("clases/class.propiedadVW.php");
include_once("generic_class/class.menu.php");
include_once("generic_class/class.cargaConfiguracion.php");
include_once('generic_class/class.upload.php');


class FotoVW {
	//
	private $foto;
	private $path;
	private $pathC;
	private $arrayForm;
	private $anchoG;
	private $anchoC;
	private $cant;

	public function __construct($_foto=0){
		FotoVW::creaFoto();
		if($_foto instanceof Foto )	{
			FotoVW::seteaFoto($_foto);
		}
		if (is_numeric($_foto)){
			if($_foto!=0){
				FotoVW::cargaFoto($_foto);
			}
		}
		$conf=new CargaConfiguracion();
		$this->cant =$conf->leeParametro('cant_fotos');
		$this->path =$conf->leeParametro('path_fotos');
		$this->pathC =$conf->leeParametro('path_fotos_chicas');
		$this->anchoC =$conf->leeParametro('ancho_foto_chica');
		$this->anchoG =$conf->leeParametro('ancho_foto_grande');
	}


	public function cargaFoto($_foto){
		$foto=new FotoBSN($_foto);
		$this->seteaFoto($foto->getObjeto());
	}

	public function getIdFoto(){
		return $this->foto->getId_foto();

	}


	protected function creaFoto(){
		$this->foto=new Foto();
	}

	protected function seteaFoto($_foto){
		$this->foto=$_foto;
		$foto=new FotoBSN($_foto);
		$this->arrayForm=$foto->getObjetoView();
	}

	public function setIdPropiedad($_tema){
		$this->foto->setId_prop($_tema);
		$this->arrayForm['id_prop']=$_tema;
	}

	public function getIdPropiedad(){
		return $this->foto->getId_prop();
	}


	public function cargaDatosFoto(){
		$propVW = new PropiedadVW($this->arrayForm['id_prop']);
		$propVW->muestraDomicilio();


		if($this->arrayForm['id_foto']==0){
			$foto0=new Foto();
			$foto0->setId_prop($this->arrayForm['id_prop']);
			$fotoaux= new FotoBSN($foto0);
			$pos=$fotoaux->proximaPosicion();
		}else{
			$pos=$this->arrayForm['posicion'];
		}

		print "<form name='lista' action='carga_foto.php' enctype='multipart/form-data' method='post'>";

		$menu=new Menu();
		$menu->dibujaMenu('lista');

		print "<table width='800' align='center'>";
		for($x=0;$x<$this->cant;$x++){
			print "<tr><td class='cd_celda_texto' width='100'>Foto</td>";
			print "	<td class='cd_celda_input' width='700'><input type='file' name='foto_$x' id='foto_$x' size='100' maxlength='200'>";
			print "</td></tr>\n";
		}
		print "<input type='hidden' name='posicion' id='posicion' value='". $pos ."' size='2' maxlength='2'>\n";
		print "<input type='hidden' name='hfoto' id='hfoto' value='".$this->arrayForm['hfoto']."'>".$this->arrayForm['hfoto'];
		print "		<input type='hidden' name='id_foto' id='id_foto' value='".$this->arrayForm['id_foto']."'>\n";
		print "		<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop']."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='center'><input type='submit' value='Submit'></td></tr></table>";
		print "</form>";
	}

	public function cargaDatosFoto_ORIG(){
		$propVW = new PropiedadVW($this->arrayForm['id_prop']);
		$propVW->muestraDomicilio();


		if($this->arrayForm['id_foto']==0){
			$foto0=new Foto();
			$foto0->setId_prop($this->arrayForm['id_prop']);
			$fotoaux= new FotoBSN($foto0);
			$pos=$fotoaux->proximaPosicion();
		}else{
			$pos=$this->arrayForm['posicion'];
		}



		print "<form name='lista' action='carga_foto.php' enctype='multipart/form-data' method='post'>";

		$menu=new Menu();
		$menu->dibujaMenu('lista');

		print "<table width='800' align='center'>";

		print "<tr><td class='cd_celda_texto' width='100'>Foto</td>";
		print "<td class='cd_celda_input' width='700'><input type='hidden' name='hfoto' id='hfoto' value='".$this->arrayForm['hfoto']."'>".$this->arrayForm['hfoto'];
		print "	<input type='file' name='foto' id='foto' size='100' maxlength='200'>";
		print "</td></tr>\n";

		//		print "<tr><td class='cd_celda_texto' width='100'>Posicion </td>";
		//		print "<td class='cd_celda_input' width='700'>		<input type='text' name='posicion' id='posicion' value='".$pos."' size='2' maxlength='2'></td></tr>\n";
		print "<input type='hidden' name='posicion' id='posicion' value='".$pos."' size='2' maxlength='2'></td></tr>\n";

		print "		<input type='hidden' name='id_foto' id='id_foto' value='".$this->arrayForm['id_foto']."'>\n";
		print "		<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop']."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='center'><input type='submit' value='Submit'></td></tr></table>";
		print "</form>";
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
		//		$anchof=$this->resizeFoto($ancho,"w");
		//		$altof=$this->resizeFoto($ancho,"h");
		$anchof=40;
		$altof=25;

		$vista.= "<tr><td ><a href=\"javascript:llamarasincrono('muestra_foto.php?f=".$this->path."/".$this->arrayForm['hfoto']."', 'foto');\" border='0' ><img src='".$this->path."/".$this->arrayForm['hfoto']."' width='$anchof' height='$altof' border='0'></a></td></tr>\n";
		//$vista.= "<tr><td ><a href='".$this->path."/".$this->arrayForm['hfoto']."' target='_blank' border='0' ><img src='".$this->path."/".$this->arrayForm['hfoto']."' width='$anchof' height='$altof'></a></td></tr>\n";

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
	public function vistaTablaFotos($id_prop=0){
		$propVW = new PropiedadVW($id_prop);

		$fila=0;

		if($id_prop==0 || is_nan($id_prop)) {
			echo "Debe seleccionar un Propiedad para poder verificar sus Fotos";
		} else {
			print "<form name='lista' method='POST' action='respondeMenu.php'>";
			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(opcion,id){\n";
			print "     document.forms.lista.id_foto.value=id;\n";
			print "   	submitform(opcion);\n";
			print "}\n";
			print "</script>\n";

			print "<span class='pg_titulo'>Listado de Fotos Disponibles para el Propiedad</span><br><br>\n";
			$propVW->muestraDomicilio();

			$menu=new Menu();
			$menu->dibujaMenu('lista');
			//			$menu->dibujaMenu('lista','opcion');

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
					print "    <a href='javascript:envia(253,".$foto['id_foto'].");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
					print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href='javascript:envia(254,".$foto['id_foto'].");' border='0'>";
					print "       <img src='images/up.png' alt='Subir' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href='javascript:envia(255,".$foto['id_foto'].");' border=0>";
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


	public function leeDatosFotoVW(){
		$foto=new FotoBSN();
		$retorno=false;
		for($x=0;$x<$this->cant;$x++){

			if($_FILES['foto_'.$x]['type']=='image/jpeg' || $_FILES['foto_'.$x]['type']=='image/gif' || $_FILES['foto_'.$x]['type']=='image/png'){
				$fotoup= new Upload($_FILES['foto_'.$x],'es_ES');
				$nom=$_FILES['foto_'.$x]['name'];
				$nombre='P_'.$_POST['id_prop'].'_'.substr($nom,0,strlen($nom)-4);
				if($fotoup->uploaded){
					echo "$nom - $nombre<br>";

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

					$fotoup->image_resize 			= true;
					$fotoup->image_ratio_y 			= true;

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

				$retorno=true;
				$_POST['hfoto'][$x]=$fotoup->file_dst_name;
				$this->foto=$foto->leeDatosForm($_POST);
			}
		}
		return $retorno;
	}



	public function leeDatosFotoVW_ORIG(){
		$foto=new FotoBSN();
		$retorno=false;
		if($_FILES['foto']['type']=='image/jpeg' || $_FILES['foto']['type']=='image/gif' || $_FILES['foto']['type']=='image/png'){
			$fotoup= new Upload($_FILES['foto'],'es_ES');
			$nom=$_FILES['foto']['name'];
			$nombre='P_'.$_POST['id_prop'].'_'.substr($nom,0,strlen($nom)-4);
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

				$fotoup->image_resize 			= true;
				$fotoup->image_ratio_y 			= true;

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

			/*			$nombre='P_'.$_POST['id_prop'].'_'.$_FILES['foto']['name'];
			if (trim($nombre)=="" || !isset($_FILES['foto']['name'])){
			$nombre=$_POST['hfoto'];
			} else {
			if (is_uploaded_file($_FILES['foto']['tmp_name'])){
			copy($_FILES['foto']['tmp_name'], "$this->path/$nombre");
			$retorno=true;
			}
			}
			*/
			$retorno=true;
			$_POST['hfoto']=$fotoup->file_dst_name;
			$this->foto=$foto->leeDatosForm($_POST);
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
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaFoto(){
		$retorno=false;
		$arrayfotos= $this->foto->getFoto();
		print_r($arrayfotos);echo"<br>";
		$mfoto = new Foto();
		$mfoto->setId_foto($this->foto->getId_foto());
		$mfoto->setId_prop($this->foto->getId_prop());
		$pos = $this->foto->getPosicion();
		for($x=0;$x<$this->cant;$x++){
			if($arrayfotos[$x]!=''){
				$mfoto->setFoto($arrayfotos[$x]);
				$mfoto->setPosicion($pos + $x);
				print_r($mfoto);echo"<br>";
				$foto=new FotoBSN($mfoto);
				$retIFoto=$foto->insertaDB();
				if ($retIFoto){
					echo "Se proceso la grabacion en forma correcta<br>";
					$retorno=true;
				} else {
					echo "Fallo la grabación de los datos<br>";
				}
			}

		}
		return $retorno;
	}

	public function grabaFoto_ORIG(){
		$retorno=false;
		$foto=new FotoBSN($this->foto);
		$retIFoto=$foto->insertaDB();
		if ($retIFoto){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}


	protected function descripcionPropiedadFoto($id){
		$tema=new Propiedad();
		return $tema->getPropiedadFoto($id);
	}



} // fin clase


?>