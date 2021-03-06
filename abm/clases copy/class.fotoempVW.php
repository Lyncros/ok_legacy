<?php
include_once("clases/class.emprendimiento.php");
include_once("clases/class.datosemp.php");
include_once("clases/class.datosempVW.php");
include_once("clases/class.fotoemp.php");
include_once("clases/class.fotoempBSN.php");
include_once("clases/class.emprendimientoVW.php");
include_once("generic_class/class.menu.php");
include_once("generic_class/class.cargaConfiguracion.php");
include_once('generic_class/class.upload.php');

class FotoempVW {

	private $fotoemp;
	private $path;
	private $pathC;
	private $arrayForm;
	private $anchoG;
	private $anchoC;
	private $cant;

	public function __construct($_foto=0){
		FotoempVW::creaFotoemp();
		if($_foto instanceof Fotoemp )	{
			FotoempVW::seteaFotoemp($_foto);
		}
		if (is_numeric($_foto)){
			if($_foto!=0){
				FotoempVW::cargaFotoemp($_foto);
			}
		}
		$conf=CargaConfiguracion::getInstance();
		$this->cant = $conf->leeParametro('cant_fotos');
		$this->path =$conf->leeParametro('path_fotos');
		$this->pathC =$conf->leeParametro('path_fotos_chicas');
		$this->anchoC =$conf->leeParametro('ancho_foto_chica');
		$this->anchoG =$conf->leeParametro('ancho_foto_grande');
	}


	public function cargaFotoemp($_foto){
		$foto=new FotoempBSN($_foto);
		$this->seteaFotoemp($foto->getObjeto());
	}

	public function getIdFotoemp(){
		return $this->fotoempemp->getId_foto();

	}


	protected function creaFotoemp(){
		$this->fotoemp=new Fotoemp();
	}

	protected function seteaFotoemp($_foto){
		$this->fotoemp=$_foto;
		$foto=new FotoempBSN($_foto);
		$this->arrayForm=$foto->getObjetoView();
	}

	public function setId_emp_carac($_tema){
		$this->fotoemp->setId_emp_carac($_tema);
		$this->arrayForm['id_emp_carac']=$_tema;
	}

	public function getId_emp_carac(){
		return $this->fotoemp->getId_emp_carac();
	}


	public function cargaDatosFotoemp(){
		$datosempBSN = new DatosempBSN($this->arrayForm['id_emp_carac']);
		$id_emp=$datosempBSN->getObjeto()->getId_emp();
		$id_carac=$datosempBSN->getObjeto()->getId_carac();

		$empBSN = new EmprendimientoBSN($id_emp);
		$caracBSN = new CaracteristicaempBSN($id_carac);

		$emp=$empBSN->getObjeto()->getNombre();
		$carac=$caracBSN->getObjeto()->getTitulo();

		if($this->arrayForm['id_foto']==0 || $this->arrayForm['id_foto']==''){
			$foto0 = new Fotoemp();
			$foto0->setId_emp_carac($this->arrayForm['id_emp_carac']);
			$fotoaux= new FotoempBSN($foto0);
			$pos=$fotoaux->proximaPosicion();
		}else{
			$pos=$this->arrayForm['posicion'];
		}

		print "<form name='lista' action='carga_fotoemp.php' enctype='multipart/form-data' method='post'>";

		$menu=new Menu();
		$menu->dibujaMenu('lista');

		print "<span class='pg_titulo'>Carga de Fotos para ".$carac." del emprendimiento ".$emp."</span><br><br>\n";

		print "<table width='800' align='center'>";
		for($x=0;$x<$this->cant;$x++){
			print "<tr><td class='cd_celda_texto' width='100'>Foto</td>";
			print "	<td class='cd_celda_input' width='700'><input type='file' name='foto_$x' id='foto_$x' size='100' maxlength='200'>";
			print "</td></tr>\n";
		}

		print "<input type='hidden' name='posicion' id='posicion' value='". $pos ."' size='2' maxlength='2'>\n";
		print "<input type='hidden' name='hfoto' id='hfoto' value='".$this->arrayForm['hfoto']."'>".$this->arrayForm['hfoto'];
		print "		<input type='hidden' name='id_foto' id='id_foto' value='".$this->arrayForm['id_foto']."'>\n";
		print "		<input type='hidden' name='id_emp_carac' id='id_emp_carac' value='".$this->arrayForm['id_emp_carac']."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='center'><input type='submit' value='Submit'></td></tr></table>";
		print "</form>";
	}

	public function cargaDatosFotoemp_ORIG(){
		$datosempBSN = new DatosempBSN($this->arrayForm['id_emp_carac']);
		$id_emp=$datosempBSN->getObjeto()->getId_emp();
		$id_carac=$datosempBSN->getObjeto()->getId_carac();

		$empBSN = new EmprendimientoBSN($id_emp);
		$caracBSN = new CaracteristicaempBSN($id_carac);

		$emp=$empBSN->getObjeto()->getNombre();
		$carac=$caracBSN->getObjeto()->getTitulo();

		if($this->arrayForm['id_foto']==0 || $this->arrayForm['id_foto']==''){
			$foto0 = new Fotoemp();
			$foto0->setId_emp_carac($this->arrayForm['id_emp_carac']);
			$fotoaux= new FotoempBSN($foto0);
			$pos=$fotoaux->proximaPosicion();
		}else{
			$pos=$this->arrayForm['posicion'];
		}

		print "<form name='lista' action='carga_fotoemp.php' enctype='multipart/form-data' method='post'>";

		$menu=new Menu();
		$menu->dibujaMenu('lista');

		print "<span class='pg_titulo'>Carga de Fotos para ".$carac." del emprendimiento ".$emp."</span><br><br>\n";

		print "<table width='800' align='center'>";

		print "<tr><td class='cd_celda_texto' width='100'>Foto</td>";
		print "<td class='cd_celda_input' width='700'><input type='hidden' name='hfoto' id='hfoto' value='".$this->arrayForm['hfoto']."'>".$this->arrayForm['hfoto'];
		print "	<input type='file' name='foto' id='foto' size='100' maxlength='200'>";
		print "</td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='100'>Posicion </td>";
		print "<td class='cd_celda_input' width='700'>		<input type='text' name='posicion' id='posicion' value='".$pos."' size='2' maxlength='2'></td></tr>\n";

		print "		<input type='hidden' name='id_foto' id='id_foto' value='".$this->arrayForm['id_foto']."'>\n";
		print "		<input type='hidden' name='id_emp_carac' id='id_emp_carac' value='".$this->arrayForm['id_emp_carac']."'>\n";

		print "<br>";
		print "<tr><td colspan='2' align='center'><input type='submit' value='Submit'></td></tr></table>";
		print "</form>";
	}


	/**
 * Arma una tabla con la foto y el epigrafe en el caso que existiera
 *
 * @param int $ancho: ancho del espacio dinde se va mostrar la foto.
 */
	public function muestraFotoemp($ancho){
		print $this->armaVistaFotoemp($ancho);
	}

	/**
 * Define la forma en quese visualiza la foto calculando el ancho y alto proporcional de la misma
 * tomando como base el valor del ancho indicado
 *
 * @param int $ancho -> ancho de lespacio donde se despliega la foto
 * @return string cn el formato de la foto y su nombre
 */
	public function armaVistaFotoemp($ancho){
		$vista="<table width='$ancho' align='center'>";
		//		$anchof=$this->resizeFotoemp($ancho,"w");
		//		$altof=$this->resizeFotoemp($ancho,"h");
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
	public function armaArrayFotoempsNota($noticia,$ancho,$pos){
		$fotoDB=new FotoempPGDAO();
		$arrayFotoemp=array();
		$resFotoemp=$fotoDB->ColeccionByNoticia($noticia);
		if (pg_num_rows($resFotoemp)!=0){
			while($row=pg_fetch_array($resFotoemp)){
				if($row['posicion']==$pos){
					$this->cargaFotoemp($row['id_foto']);
					$arrayFotoemp[]= $this->armaVistaFotoemp($ancho);
				}
			}
		}
		return $arrayFotoemp;
	}

	/**
 * Recalcula el ancho de las fotos segun el espacio dispnible para publicar la misma
 *
 * @param int $ancho :especifica el ancho del espacio donde se va a publicar la foto
 * @param char $parametro : especifica si se calcula "h" -> el alto  o  "w" -> el ancho
 * @return int : responde al valor nuevo del alto o ancho segun PARAMETRO
 */
	protected function resizeFotoemp($ancho,$parametro){
		//		$anchof=imagesx($this->path."/".$this->fotoemp->getFotoemp());
		//		$altof=imagesy($this->path."/".$this->fotoemp->getFotoemp());
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
		$arrayFotoemp=array();
		$fotoBSN= new FotoempBSN();
		$arrayFotoemp=$fotoBSN->cargaColeccionFormByDatosemp($tema);
		if(sizeof($arrayFotoemp)!=0){
			foreach ($arrayFotoemp as $foto) {
				$this->arrayForm=$foto;
				$arrayGal[]= $this->armaVistaFotoemp($ancho);
			}
		}

		return $arrayGal;
	}

	public function retornaFotoemp($tema,$ini,$desp){
		$pos=0;
		$retorno="";
		if(is_numeric($ini)){
			$pos=$ini;
		}

		$arrayFotoemp=array();
		$fotoBSN= new FotoempBSN();
		$arrayFotoemp=$fotoBSN->cargaColeccionFormByDatosemp($tema);
		if(sizeof($arrayFotoemp)!=0){
			$retorno= $this->path."/".$arrayFotoemp[$pos]['hfoto'];
		}
		return $retorno;
	}

	/**
 * Muestra una tabla con los datos de los Fotoempes para un curso y una barra de herramientas o menu
 * conde se despliegan las opciones ingresables para cada item
 *
 */
	public function vistaTablaFotoemp($id_emp_carac=0){
		$datosempBSN = new DatosempBSN($id_emp_carac);
		$id_emp=$datosempBSN->getObjeto()->getId_emp();
		$id_carac=$datosempBSN->getObjeto()->getId_carac();

		$empBSN = new EmprendimientoBSN($id_emp);
		$caracBSN = new CaracteristicaempBSN($id_carac);

		$emp=$empBSN->getObjeto()->getNombre();
		$carac=$caracBSN->getObjeto()->getTitulo();

		$fila=0;

		if($id_emp_carac==0 || is_nan($id_emp_carac)) {
			echo "Debe seleccionar un Datosemp para poder verificar sus Fotoemps";
		} else {
			print "<form name='lista' method='POST' action='respondeMenu.php'>";
			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(opcion,id){\n";
			print "     document.forms.lista.id_foto.value=id;\n";
			print "   	submitform(opcion);\n";
			print "}\n";
			print "</script>\n";

			print "<span class='pg_titulo'>Carga de Fotos para ".$carac." del emprendimiento ".$emp."</span><br><br>\n";

			$menu=new Menu();
			$menu->dibujaMenu('lista');
			//			$menu->dibujaMenu('lista','opcion');

			print "  <table class='cd_tabla'>\n";
			print "    <tr>\n";
			print "     <td class='cd_lista_titulo' width='50' colspan='3'>&nbsp;</td>\n";
			print "     <td class='cd_lista_titulo' width='400' colspan='2'>Foto</td>\n";
			print "     <td class='cd_lista_titulo' width='100'>Posicion</td>\n";
			print "	  </tr>\n";

			$fotoBSN= new FotoempBSN();
			$arrayFotoemp=$fotoBSN->cargaColeccionFormByCaracteristica($id_emp_carac);

			if(sizeof($arrayFotoemp)==0){
				print "No existen datos para mostrar";
			} else {
				foreach ($arrayFotoemp as $foto){
					if($fila==0){
						$fila=1;
					} else {
						$fila=0;
					}
					print "<tr>\n";

					print "	 <td class='row".$fila."'>";
					print "    <a href='javascript:envia(3443,".$foto['id_foto'].");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
					print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href='javascript:envia(3444,".$foto['id_foto'].");' border='0'>";
					print "       <img src='images/up.png' alt='Subir' border=0></a></td>";
					print "	 <td class='row".$fila."' width='16'>";
					print "    <a href='javascript:envia(3445,".$foto['id_foto'].");' border=0>";
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
			print "<input type='hidden' name='id_emp_carac' id='id_emp_carac' value='".$id_emp_carac."'>";
			print "<input type='hidden' name='id_emp' id='id_emp' value='".$id_emp."'>";
			$_SESSION['id_emp']=$id_emp;
			print "<input type='hidden' name='id_foto' id='id_foto' value='".$id_foto."'>";
			print "</form>";
		}
	}



	public function leeDatosFotoempVW(){
		$foto=new FotoempBSN();
		$retorno=false;
		for($x=0;$x<$this->cant;$x++){

			if($_FILES['foto_'.$x]['type']=='image/jpeg' || $_FILES['foto_'.$x]['type']=='image/gif' || $_FILES['foto_'.$x]['type']=='image/png'){
				$fotoup= new Upload($_FILES['foto_'.$x],'es_ES');
				$nom=$_FILES['foto_'.$x]['name'];
				$nombre='CE_'.$_POST['id_emp_carac'].'_'.substr($nom,0,strlen($nom)-4);
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
				$retorno=true;
				$_POST['hfoto'][$x]=$fotoup->file_dst_name;
				$this->fotoemp=$foto->leeDatosForm($_POST);
			}
		}
		return $retorno;
	}


	public function leeDatosFotoempVW_ORIG(){
		$foto=new FotoempBSN();
		$retorno=false;
		if($_FILES['foto']['type']=='image/jpeg' || $_FILES['foto']['type']=='image/gif' || $_FILES['foto']['type']=='image/png'){
			$fotoup= new Upload($_FILES['foto'],'es_ES');
			$nom=$_FILES['foto']['name'];
			$nombre='CE_'.$_POST['id_emp_carac'].'_'.substr($nom,0,strlen($nom)-4);
			if($fotoup->uploaded){
				$fotoup->image_resize 			= true;
				$fotoup->image_ratio_y 			= true;
				$fotoup->image_x 				= $this->anchoG;
                $fotoup->file_new_name_body   	= $nombre;

				$fotoup->Process($this->path);

				// we check if everything went OK
				if (!$fotoup->processed) {
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
				if (!$fotoup->processed) {	
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

			/*
			$nombre='CE_'.$_POST['id_emp_carac'].'_'.$_FILES['foto']['name'];
			if (trim($nombre)=="" || !isset($_FILES['foto']['name'])){
			$nombre=$_POST['hfoto'];
			} else {
			if (is_uploaded_file($_FILES['foto']['tmp_name'])){
			copy($_FILES['foto']['tmp_name'], "$this->path/$nombre");
			}
			}

			*/
			$retorno=true;
			$_POST['hfoto']=$fotoup->file_dst_name;
			//		$foto->setFotoemp($nombre);
			$this->fotoemp=$foto->leeDatosForm($_POST);
		}
		return $retorno;
	}


	public function grabaModificacion(){
		$retorno=false;
		$foto=new FotoempBSN($this->fotoemp);
		$retUFotoemp=$foto->actualizaDB();
		if ($retUFotoemp){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}

	public function grabaFotoemp(){
		$retorno=false;
		$arrayfotos= $this->fotoemp->getFoto();
		$mfoto = new Fotoemp();
		$mfoto->setId_foto($this->fotoemp->getId_foto());
		$mfoto->setId_emp_carac($this->fotoemp->getId_emp_carac());
		$pos = $this->fotoemp->getPosicion();
		for($x=0;$x<$this->cant;$x++){
			if($arrayfotos[$x]!=''){
				$mfoto->setFoto($arrayfotos[$x]);
				$mfoto->setPosicion($pos + $x);
				$foto=new FotoempBSN($mfoto);
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


	public function grabaFotoemp_ORIG(){
		$retorno=false;
		$foto=new FotoempBSN($this->fotoemp);
		$retIFotoemp=$foto->insertaDB();
		if ($retIFotoemp){
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno=true;
		} else {
			echo "Fallo la grabación de los datos<br>";
		}
		return $retorno;
	}
	
	
	protected function descripcionDatosempFotoemp($id){
		$tema=new Datosemp();
		return $tema->getDatosempFotoemp($id);
	}



} // fin clase


?>