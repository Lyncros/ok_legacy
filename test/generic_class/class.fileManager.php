<?php
include_once('generic_class/class.cargaConfiguracion.php');
include_once('generic_class/class.upload.php');
/**
 * Clase definida para verificar la exstencia de archivos y directorios dentro de un path determinado
 * Verifica de forma autom�tica si lo que se esta ingresando es un directorio o un archivo.
 * En el caso de ser un directorio lo analisa y levanta del mismo los datos de directorios y archivos incluidos
 * dentro de esta estructura.
 * Para ello genera dos estructuras de array una para directorios y otra para archivos
 * La estrucura de directorios es simple indicando en cada posicion el nombre del subdirectorio
 * La estructura de archivos es compuesta siendo cada posicion un array con los datos de
 * nombre, path, extencon y tama�o.
 * Para el caso de archivos deja en blanco el array de directorios y completa los datos del array de archivos
 * 
 * PAra instanciar la misma se debera paar el path o nombre de archivo.
 */
class FileManager{

	protected $path;
	protected $files;
	protected $dirs;
	protected $papelera;


	function __construct($filepath=''){
		if(!file_exists($filepath)){
			die("No existe el archivo o directorio solicitado; o no posee permisos para acceder al mismo.")	;
		}else{
			FileManager::setPath($filepath);
			FileManager::leeEstructura();
			FileManager::seteaPapelera();
		}
	}

	protected function seteaPapelera(){
		$conf = CargaConfiguracion::getInstance();
		$this->papelera = $_SERVER['DOCUMENT_ROOT']."/".$conf->leeParametro("recyclepath");
	}
	
/**
 * Permite setear un nuevo path o nombre de archivo
 *
 * @param string => identificando el path o nombre de archivo, en ambos casos debe ser estrucura completa.
 */
	public function setPath($path=''){
		if(!file_exists($path)){
			die("No existe el archivo o directorio solicitado; o no posee permisos para acceder al mismo.")	;
		}else{
			$this->path=$path;
			$this->leeEstructura();
		}
	}

/**
 * Levanta la estrucura de los archivos dentro del path indicado.
 *
 * @return array complejo con cada registro definido como estrucura nombre,path,ext,size
 * 	
 */
	public function getFiles(){
		return $files;
	}

/**
 * Levanta la estrucura de directorios definidos dentro del path indicado.
 *
 * @return array simple con los nombres de los subdirectorios.
 */
	public function getDirs(){
		return $dirs;
	}

/**
 * Retorna una estructura compleja donde se definen los directorios y archivos contenidos en el path
 *
 * @return array con estructura compleja dir, file donde se reflejan los arrays de directorios y archivos.
 */
	public function getAll(){
		return array('dir'=>$dirs,'file'=>$files);
	}

	protected function leeEstructura(){
		$arrayDir=array();
		$arrayFiles= array();
		if(filetype($this->path)=='dir'){
			$filesDir = scandir($this->path);
			foreach ($filesDir as $nombre) {
				$arch=$this->path."/".$nombre;
				$tipo = filetype($arch);
				switch ($tipo) {
					case 'dir':
						if($nombre <> '.' && $nombre <> '..'){
							$arrayDir[]=$nombre;
						}
						break;
					case 'file':
						$arrayFiles[]=$this->datosArchivo($this->path."/".$nombre);
						break;
					default:
						break;
				}
			}
		}else{
			$arrayFiles[]=$this->datosArchivo($this->path);
		}
		$this->dirs = $arrayDir;
		$this->files = $arrayFiles;
	}

	protected function datosArchivo($file){
		$ext=$this->leeExtencion($file);
		$size=$this->leeSize($file);
		$path=dirname($file);
		$nombre=str_replace($path."/",'',$file);
		return array('nombre'=>$nombre,'path'=>$path,'ext'=>$ext,'size'=>$size);
	}
	
	
	protected function leeExtencion($file){
		$ext='';
		$str = $file;
		$posp= strrpos($str,'.');
		$posb=strpos($str,'/');
		if($posp > $posb){
			 $ext=substr($str,$pos+1);
		}
		return $ext;
	}
	
	protected function leeSize($file){
		return filesize($file);
	}
	
/**
 * Mueve el archivo indicado a la papelera de reciclaje de la aplicacion
 *
 * @param string $file => nombre del archivo a borrar con path incluido
 * @return boolean
 */
	public function borraArchivo($file){
		$retorno = false;
		if(file_exists($file) && filetype($file)=='file'){
			$nombre=str_replace('/','#',$file);
			$retorno = rename($file,$this->papelera."/".$nombre);
		}
		return $retorno;
	}
	
/**
 * Restaura el archivo desde la papelera de la aplicacion.
 * En el caso que el archivo de destino exista lo indica en el retorno.
 * Si el directorio original del archivo no se encuentra, lo crea nuevamente.
 * Retorna un entero con 1 => final Ok; 0 => Final erroneo; -1 => archivo de destino existente;
 *
 * @param string $file
 * @return entero
 */
	public function restauraArchivo($file){
		$retorno = 0;
		$existe = false;
		if(file_exists($this->papelera."/".$file) && filetype($this->papelera."/".$file)=='file'){
			$nombre=str_replace('#','/',$file);
			$path=dirname($nombre);
			if(!file_exists($path)){
				$existe = mkdir($path);
			}else{
				$existe=true;
			}
			if($existe){
				if(file_exists($nombre)){
					$retorno=-1;
				}else{
					$ret = rename($this->papelera."/".$file,$nombre);
					if($ret){
						$retorno=1;
					}
				}
			}
		}
		return $retorno;
	}

/**
 * Elimina todos los archivos del directorio de la papelera de reciclaje
 *
 */
	protected function vaciaPapelera(){
		$arrayFiles= array();
		$filesDir = scandir($this->papelera);
		foreach ($filesDir as $nombre) {
			if($nombre<>'.' && $nombre<>'..'){
				$arch=$this->papelera."/".$nombre;
				unlink($arch);
			}
		}
	}		
	
/**
 * Mueve un archivo desde su origen a una nueva locacion, en caso de indicarlo, le cambiara el nombre
 * Retorna un entero que indica con 1 => final Ok; 0 => Final erroneo; -1 => archivo de destino existente;
 * 
 * @param string $origen => archivo de origen incluyendo el path
 * @param string  $destino => archivo de destino, incluyendo el path
 * 
 * @return entero
 */
	public function mueveArchivo($origen,$destino){
		$retorno=0;
		$existe=false;
		if(file_exists($origen)){
			if(file_exists($destino)){
				$retorno=-1;
			}else{
				$path=dirname($destino);
				if(!file_exists($path)){
					$existe = mkdir($path);
				}else{
					$existe=true;
				}
				if($existe){
					$ret = rename($origen,$destino);
					if($ret){
						$retorno=1;
					}
				}
			}
		}
		return $retorno;
	}

/**
 * Copia un archivo desde su origen a una nueva locacion, en caso de indicarlo, le cambiara el nombre
 * Retorna un entero que indica con 1 => final Ok; 0 => Final erroneo; -1 => archivo de destino existente;
 * 
 * @param string $origen => archivo de origen incluyendo el path
 * @param string  $destino => archivo de destino, incluyendo el path
 * 
 * @return entero
 */
	public function copiaArchivo($origen,$destino){
		$retorno=0;
		$existe=false;
		if(file_exists($origen)){
			if(file_exists($destino)){
				$retorno=-1;
			}else{
				$path=dirname($destino);
				if(!file_exists($path)){
					$existe = mkdir($path,0777,true);
				}else{
					$existe=true;
				}
				if($existe){
					$ret = copy($origen,$destino);
					if($ret){
						$retorno=1;
					}
				}
			}
		}
		return $retorno;
	}

/**
 * Crea un directorio en el path especificado.
 * Retorna un entero indicando con  1 => final Ok; 0 => Final erroneo; -1 => archivo de destino existente;
 * 
 * @param string $path
 * 
 * @return int
 */
	public function creaDirectorio($path){
		$retorno=0;
		if(file_exists($path)){
				$retorno=-1;
		}else{
			$existe = mkdir($path,0777,true);
			if($existe){
				$retorno=1;
			}
		}
		return $retorno;
	}
	
/**
 * Borra el directorio directorio indicado por el ultimo segmento del path.
 * Retorna un entero indicando con  1 => final Ok; 0 => Final erroneo; -1 => directorio no vacio;
 * 
 * @param string $path
 * 
 * @return int
 */
	public function borraDirectorio($path){
		$retorno=0;
		if(file_exists($path)){
			if(sizeof(scandir($path))<=2){
				$existe = rmdir($path);
				if($existe){
					$retorno=1;
				}
			}else{
				$retorno=-1;
			}
		}
		return $retorno;
	}

	public function uploadFile($path){
		$retorno=false;
		$fotoup= new Upload($_FILES['archivo'],'es_ES');
		$nom=$_FILES['archivo']['name'];
		if($fotoup->uploaded){
			$fotoup->process($path);
//			$fotoup->file_dst_path = $path;
		}else{
			echo '<fieldset>';
			echo '  <legend>file not uploaded on the server</legend>';
			echo '  Error: ' . $fotoup->error . '';
			echo '</fieldset>';
		}
		$retorno=true;
		return $retorno;
	}
	
	public function actuar($accion,$path,$newpath,$archivo){
		$conf = CargaConfiguracion::getInstance();
		$basepath=$_SERVER['DOCUMENT_ROOT']."/".$conf->leeParametro("basepath");
		
		switch ($accion) {
			case 'bk':
				if($basepath <> $this->path )	{
					$backpath=substr($this->path,0,strrpos($this->path,"/"));
					$this->setPath($backpath);
				}
				break;
			case 'cd':
				$this->setPath($this->path."/".$path);
				break;
			case 'md';
				$ret=$this->creaDirectorio($this->path."/".$newpath);
				$this->setPath($this->path);
				break;
			case 'rmd':
				$path=substr($path,3);
				$ret=$this->borraDirectorio($this->path."/".$path);
				$this->setPath($this->path);				
				break;
			case 've':
				break;
			case 'cp':
				$path=substr($path,3);
				$this->copiaArchivo($this->path."/".$path,$newpath);
				$this->setPath($this->path);				
				break;
			case 'mv':
				$path=substr($path,3);
				$this->mueveArchivo($this->path."/".$path,$newpath);
				$this->setPath($this->path);				
				break;
			case 'rm':
				$path=substr($path,3);
				$ret=$this->borraArchivo($this->path."/".$path);
				$this->setPath($this->path);				
				break;
			case 'rmp':
				$path=substr($path,3);
				unlink($this->papelera."/".$path);
				$this->setPath($this->papelera);				
				break;
			case 'pa':
				$this->setPath($this->papelera);
				break;
			case 're':
				$path=substr($path,3);
				$this->restauraArchivo($path);
				$this->setPath($this->papelera);				
				break;
			case 'em':
				$this->vaciaPapelera();
				$this->setPath($this->papelera);				
				break;
			case 'ad':
				$this->setPath($basepath);
				break;
			case 'up':
				$this->uploadFile($this->path);
				$this->setPath($this->path);
				break;
			default:
				break;
		}
	}
	
/**
 * Muestra el administrador de archivos basado en el path indicado al instanciar la clase
 * o en el path activo.
 * 
 * @param int vista => indica el modo de apertura 0=Normal 1=Selector de Path 2=Papelera
 */
	public function showFileManager($vista,$arcorigen='',$pathorig=''){
		switch ($vista) {
			case 0:
				$titulo="Administrador de Archivos";
				$ancho=800;
				$alto=500;
				$accion='filemanager.php';
				break;
			case 2:
				$titulo="Papelera";
				$ancho=800;
				$alto=500;
				$accion='filemanager.php';
				break;
			case 1:
				$titulo="Seleccione destino";
				$ancho=300;
				$alto=300;
				$accion='selectorDestino.php';
				break;
			default:
				break;
		}
		print "<html>\n";
		print "<head>\n";
		print "<title>$titulo</title>\n";
		print "<link href='css/fileManager.css' rel='stylesheet' type='text/css' />\n";
		print "<SCRIPT LANGUAGE=\"JavaScript\" type=\"text/javascript\" src=\"inc/filemanager.js\"></script>\n";
		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/thickbox.css\" />\n";
                print "<script src=\"jquery.ui-1.5.2/jquery.js\" type=\"text/javascript\"></script>\n";
                print "<script language=\"javascript\" src=\"jquery.ui-1.5.2/thickbox.js\" type=\"text/javascript\"></script>\n";

		print "</head>\n";
		print "<body>\n";
		print "<table width='$ancho' border='1' cellpadding='2' cellspacing='2' bordercolor='#CCCC00' bgcolor='#FFFF33'>\n";
		print "  <tr bordercolor='#FFFF66' bgcolor='#FFFF99'>\n";
		print "    <td colspan='2' bordercolor='#FFFF66' class='tools'>\n";
		print "    	<table>\n";
		print "          <tr>\n";
		switch ($vista){
			case 0:
				print "            <td class='tdmenu'><a href=\"javascript:actuar('bk')\"><img src=\"images/arrow_undo.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Atras\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:crearDir('md')\"><img src=\"images/folder_add.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Agregar carpeta\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:actuar('rmd')\"><img src=\"images/folder_delete.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Borrar carpeta\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:verArchivo('ve',".strlen($_SERVER['DOCUMENT_ROOT']."/").")\"><img src=\"images/eye.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Ver archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:copiarArchivo('cp')\"><img src=\"images/page_copy.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Copiar archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:moverArchivo('mv')\"><img src=\"images/page_go.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Mover archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:actuar('rm')\"><img src=\"images/page_delete.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Borrar archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:uploadFile('up')\"><img src=\"images/page_add.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Subir archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:downloadFile('dw')\"><img src=\"images/page_save.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Descargar archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:irPapelera('pa')\"><img src=\"images/trash.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Papelera\"></a></td>\n";
				break;
			case 1:
				print "            <td class='tdmenu'><a href=\"javascript:actuar('bk')\">Atras</a></td>\n";
				break;
			case 2:
				print "            <td class='tdmenu'><a href=\"javascript:actuar('re')\"><img src=\"images/page_go.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Recuperar archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:actuar('rmp')\"><img src=\"images/page_delete.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Borrar archivo\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:actuar('em')\"><img src=\"images/trash.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Vaciar papelera\"></a></td>\n";
				print "            <td class='tdmenu'><a href=\"javascript:irAdministracion('ad')\">Administrador</a></td>\n";
				break;
		}
		print "          </tr>\n";
		print "        </table>\n";
 		print "   </td>\n";
		print "  </tr>\n";
		print "  <tr>\n";
		print "    <td colspan='2' class='titulopath'>Posicion actual <span class='path'>". $this->path."</span></td>\n";
		print "  </tr>\n";
		print "  <tr align='left' valign='top' bgcolor='#FFFFFF'>\n";
		if($vista <> 2){
			if($vista==1){
				$colspand=' colspan="2" ';
			}else{
				$colspand='';
			}
			print "    <td $colspand class='directorios'  height=$alto title='Directorios'><strong>Directorios</strong><br>\n";
			if(sizeof($this->dirs)>0){
				print " 	 <table>\n";
				foreach ($this->dirs as $dir) {
					print "		 <tr><td class='tdlista'>";
					$id="tdd$dir";
					print "<span id='$id' ondblclick=\"javascript:cambiarDir('$id')\" onclick=\"javascript:marcar('$id')\">$dir</span>";
					print "</td></tr>\n";
				}
				print " 	 </table>\n";
			}
			print "    </td>\n";
		}
		if($vista==0 || $vista == 2){
			if($vista==2){
				$colspanf=' colspan="2" ';
			}else{
				$colspanf='';
			}

			print "    <td $colspanf class='archivos' title='Archivos'><strong>Archivos</strong><br>\n";
			if(sizeof($this->files)>0){
				print " 	 <table>\n";
				foreach ($this->files as $file) {
					$nombre=$file[nombre];
					print "		 <tr><td class='tdlista'>";
					$id="tdf$nombre";
					if($vista==2){
						$dbc='';
					}else{
						$dbc="ondblclick=\"javascript:verArchivoDC('$id',".strlen($_SERVER['DOCUMENT_ROOT']."/").")\"";
					}
					print "<span id='$id' $dbc onclick=\"javascript:marcar('$id')\">$nombre</span>";
					print "</td></tr>\n";
				}
				print " 	 </table>\n";
			}
			print "    </td>\n";
			
		}
		
		print "  </tr>\n";
		print "  <tr class='mensajes'>\n";
		print "    <td>Listo ...</td>\n";
		print "    <td>";
		print "<form method='POST' action='$accion' enctype='multipart/form-data' id='filemanager'>\n";
		print "<input type='hidden' name='path' id='path' value=''>\n";
		print "<input type='hidden' name='curpath' id='curpath' value='".$this->path."'>\n";
		if($vista<>1){
			print "<input type='hidden' name='vista' id='vista' value='$vista'>\n";
			print "<input type='hidden' name='accion' id='accion' value=''>\n";
			print "<div id='divarc' name='divarc' style='display:none;'>Seleccione archivo: <input type='file' id='archivo' name='archivo'> </div>\n";
			print "<div id='divpath' name='divpath' style='display:none;'>Ingrese nombre: <input type='text' id='newpath' name='newpath'> </div>\n";
			print "<div id='boton' name='boton'  style='display:none;'><input type='submit' value='Procesar'></div>\n";
		}else{
			print "<input type='hidden' name='accion' id='accion' value='se'>\n";
			print "<input type='hidden' name='pathorig' id='pathorig' value='$pathorig'>\n";
			print "Ingrese nombre: <input type='text' id='newpath' name='newpath' value='$arcorigen'> \n";
			print "<input type='button' onclick=\"javascript:actualizaOrigen()\" value='Seleccionar'>\n";
		}
		print "</form>\n";
		print "    </td>";
		print "  </tr>\n";
		print "</table>\n";
		print "</body>\n";
		print "</html>	\n";	
	}	
}


?>