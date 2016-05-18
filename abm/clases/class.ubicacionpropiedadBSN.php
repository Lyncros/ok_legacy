<?php
/**
 * Clase Propia para la definicion de la logica de negocios.
 * Utiliza dos variables propias de la clase que las hereda llamadas
 * 		"clase" que define la base del nombre, debe tener la Primer letra en Mayuscula y responder a la base de los nombres
 * 					de los metodos propios.
 * 		"objeto" que define el nombre del objeto tipo de la clase, cuyo nombre debe ser igual al de la
 * 					clase pero todo en minuscula
 *
 * En la clase derivada se deben definir metodos que ejecuten
 * 		getId		-> Retorna el Id de la clase
 * 		getClave	-> Retorna la Clave de la clase
 *
 *
 * Ejemplo del uso del retorno de la clase para el armado de un metodo de la clase derivada.
 * 	public function muestra(){
 *		$var='muestra'.$this->getClase();
 *		$this->{$var}();
 *	}
 */
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("clases/class.ubicacionpropiedad.php");
include_once("clases/class.ubicacionpropiedadPGDAO.php");


class UbicacionpropiedadBSN extends BSN {

	protected $clase = "Ubicacionpropiedad";
	protected $nombreId = "id_ubica";
	protected $ubicacionpropiedad;


	public function __construct($_id_ubica=0,$_id_padre=0,$_nombre_zona='') {
		UbicacionpropiedadBSN::seteaMapa();
		if ($_id_ubica  instanceof Ubicacionpropiedad ) {
			UbicacionpropiedadBSN::creaObjeto();
			UbicacionpropiedadBSN::seteaBSN($_id_ubica);
		} else {
			if (is_numeric($_id_ubica)) {
				UbicacionpropiedadBSN::creaObjeto();
				if($_id_ubica != 0) {
					UbicacionpropiedadBSN::cargaById($_id_ubica);
				}
			}
		}

	}

	/**
	 * retorna el ID del objeto
	 *
	 * @return id del objeto
	 */
	public function getId() {
		return $this->ubicacionpropiedad->getId_ubica();
	}

	/**
	 * Setea e ID del objeto
	 *
	 * @param unknown_type $id
	 */
	public function setId($id) {
		$this->ubicacionpropiedad->setId_ubica($id);
	}

	public function comboUbicacionpropiedadPrincipal($valor=0,$opcion=0,$campo="id_ubicaPrincipal",$campoUbica='id_ubica',$divTxtUbica='txtUbica',$campoEmp='',$class="campos_btn",$divUbicacion='') {
		$arrayPricipal=$this->cargaColeccionPrincipal();
		print "<select name='".$campo."' id='".$campo."' class='".$class."' ";
		if($opcion!=3){
			print " onblur=\"javascript: document.getElementById('$campoUbica').value=document.getElementById('$campo').value;document.getElementById('$divTxtUbica').innerHTML='';\" onchange=\"javascript: document.getElementById('$campoUbica').value=document.getElementById('$campo').value;document.getElementById('$divTxtUbica').innerHTML='';\"";
		}else{
			print " onblur=\"javascript: document.getElementById('$campoUbica').value=document.getElementById('$campo').value;document.getElementById('$divTxtUbica').innerHTML='';\" onchange=\"javascript: document.getElementById('$campoUbica').value=document.getElementById('$campo').value;document.getElementById('$divTxtUbica').innerHTML='';\"";
		}


		print ">\n";
		$all=0;
		switch ($opcion) {
			case 1:
			case 3:
				print "<option value='0'";
				if ($valor==0) {
					print " SELECTED ";
					$all=1;
				}
				print ">Todas</option>\n";
				break;
			case 2:
				print "<option value='0'";
				if ($valor==0) {
					print " SELECTED ";
					$all=1;
				}
				print ">Seleccione una opcion</option>\n";
				break;

			default:
				break;
		}
		for ($pos=0;$pos<sizeof($arrayPricipal);$pos++) {
			print "<option value='".$arrayPricipal[$pos]['id_ubica']."'";
			if (($arrayPricipal[$pos]['id_ubica']==$valor) || ($arrayPricipal[$pos]['id_ubica']==3979 && $valor==0 && $all==0)) {
				print " SELECTED ";
			}
			print ">".$arrayPricipal[$pos]['nombre_ubicacion']."</option>\n";
		}
		print "</select>\n";

	}


	public function checkboxUbicacionpropiedad($valor,$zona,$tipo='c'){

		$ubicacionpropiedad=$this->cargaArrayArbol($zona);//cargaColeccionHijos($zona);
		$arraySeleccion = array();
		if($valor!='' && $valor!=0){
			$arraySeleccion=split(',',$valor);
		}
		$arraySZ1=array();
		$arraySZ2=array();

		$cantSZ=sizeof($ubicacionpropiedad);
		for($indSA=0 ; $indSA < $cantSZ ; $indSA+=2){
			$arraySZ1[]=$ubicacionpropiedad[$indSA];
			if($indSA+1 < $cantSZ){
				$arraySZ2[]=$ubicacionpropiedad[$indSA+1];
			}
		}

		print "<div style=\"width: 680px; height: 500px;\" id=\"contenedor\">\n";

		$this->armaSubDiv($arraySZ1,$arraySeleccion,10,$tipo);
		$this->armaSubDiv($arraySZ2,$arraySeleccion,360,$tipo);
		print "<input type='hidden' id='aux_campo' name='aux_campo' value='0' />\n";
		print "<input type='hidden' id='txt_campo' name='txt_campo' value='' />\n";
		print "</div>\n";

	}

	protected function armaSubDiv($arraySZ,$arraySeleccion,$left,$tipo){
		print "<div style=\"position: fixed; left: ".$left."px; top: 10px; width: 320px; height: 480px; overflow: auto;\" id=\"left\">\n";
		print "<dl>\n";

		if($tipo!='r'){
			$this->armaSubDivChk($arraySZ, $arraySeleccion);
		}else{
			$this->armaSubDivRadio($arraySZ, $arraySeleccion);
		}

		print "</dl>\n";
		print "</div>\n";

	}

	protected function armaSubDivChk($arraySZ,$arraySeleccion){
		$tipoSel='checkbox';
		foreach ($arraySZ as $subZ) {
			$nomCampo="sz_".$subZ['id_ubica'];
			print "<dt>\n";
			print "<input type=\"$tipoSel\" id=\"$nomCampo\" name=\"$nomCampo\"  title='".$subZ['nombre_ubicacion']."'";
			if (in_array($subZ['id_ubica'],$arraySeleccion)){
				print " checked ";
			}
			print "/>\n";
			print "<label for=\"$nomCampo\" title=\"".$subZ['nombre_ubicacion']."\">".$subZ['nombre_ubicacion']."</label>\n";
			if(key_exists('hijos', $subZ)){
				print "<img src=\"images/table_add.png\" width=\"16\" height=\"16\" id=\"sp_sz_".$subZ['id_ubica']."\" onclick=\"expandirLista('sz_".$subZ['id_ubica']."');\" />\n";
			}
			print "</dt>\n";
			if(key_exists('hijos', $subZ)){
				print "<dd id='dd_sz_".$subZ['id_ubica']."' style=\"display: none;\">\n";
				foreach ($subZ['hijos'] as $hijo){
					$nomCampoHijo="sz_".$hijo['id_ubica'];
					print "<ul>\n";
					print "<input type=\"$tipoSel\" id=\"$nomCampoHijo\" name=\"$nomCampoHijo\" title='".$hijo['nombre_ubicacion']."' ";
					if (in_array($hijo['id_ubica'],$arraySeleccion)){
						print " checked ";
					}
					print "/>\n";
					print "<label for=\"$nomCampoHijo\" title=\"".$hijo['nombre_ubicacion']."\">".$hijo['nombre_ubicacion']."</label>\n";
					print "</ul>\n";
				}

				print "</dd>\n";
			}
		}

	}

	protected function armaSubDivRadio($arraySZ,$arraySeleccion){
		$tipoSel='radio';
		$onClick="javascript: document.getElementById('aux_campo').value=this.value;javascript: document.getElementById('txt_campo').value=this.title;";
		foreach ($arraySZ as $subZ) {
			$nomCampo="subZona";
			print "<dt>\n";
			print "<input type=\"$tipoSel\" id=\"$nomCampo\" name=\"$nomCampo\"  value=\"".$subZ['id_ubica']."\" title='".$subZ['nombre_ubicacion']."'";
			if (in_array($subZ['id_ubica'],$arraySeleccion)){
				print " checked ";
			}
			print " onclick=\"$onClick\" />";
			print $subZ['nombre_ubicacion']."\n";
			if(key_exists('hijos', $subZ)){
				print "<img src=\"images/table_add.png\" width=\"16\" height=\"16\" id=\"sp_sz_".$subZ['id_ubica']."\" onclick=\"expandirLista('sz_".$subZ['id_ubica']."');\" />\n";
			}
			print "</dt>\n";
			if(key_exists('hijos', $subZ)){
				print "<dd id='dd_sz_".$subZ['id_ubica']."' style=\"display: none;\">\n";
				foreach ($subZ['hijos'] as $hijo){
					print "<ul>\n";
					print "<input type=\"$tipoSel\" id=\"$nomCampo\" name=\"$nomCampo\" value=\"".$hijo['id_ubica']."\" title='".$hijo['nombre_ubicacion']."' ";
					if (in_array($hijo['id_ubica'],$arraySeleccion)){
						print " checked ";
					}
					print " onclick=\"$onClick\" />";
					print $hijo['nombre_ubicacion']."\n";
					print "</ul>\n";
				}

				print "</dd>\n";
			}
		}

	}

	/**
	 * Retorna un array compuesto por los elementos primarios de la estructura
	 * @return string[] -> array con el id y nombre de los componentes principales de la estructura
	 */
	public function cargaColeccionPrincipal(){
		return $this->cargaColeccionHijos(0);
	}

	/**
	 * Retorna un array con los nodos hijos del nodo pasado como parametro
	 * @param int $id_padre -> nodo padre en la estrucura
	 * @return string[] -> array bidimensional conteniendo en cada fila un array con los datos de cada nodo hijo
	 */
	public function cargaColeccionHijos($id_padre){
		$arrayRet=array();
		if(is_int($id_padre) || is_numeric($id_padre)){
			$datoDB = new UbicacionpropiedadPGDAO();
			$result=$datoDB->coleccionHijos($id_padre);
			$arrayRet=$this->leeDBArray($result);
		}
		return $arrayRet;
	}

	/**
	 * Retorna un array con los nodos que poseen su padre o nombre que contien el parametro
	 * @param string $nombre -> string a buscar en el nombre del nodo o del padre
	 * @return string[] -> array bidimensional conteniendo en cada fila un array con los datos de cada nodo
	 */
	public function cargaColeccionFiltro($nombre){
		$arrayRet=array();
		$datoDB = new UbicacionpropiedadPGDAO();
		$result=$datoDB->coleccionFiltro($nombre);
		$arrayRet=$this->leeDBArray($result);
		return $arrayRet;
	}
	
	/**
	 * Carga un array con el arbol jerarquico dependiente del nodo pasado como parametro
	 * @param int $id_padre -> nodo padre del cual se pretende conocer la jerarquia dependiente
	 * @return string[] -> array conteniendo la jerarquia.
	 * @example Array (
	 [0] => Array (
	 [id_ubica] => 4
	 [id_padre] => 1
	 [nombre_ubicacion] => SubZona 1.1
	 [hijos] => Array (
	 [0] => Array (
	 [id_ubica] => 16
	 [id_padre] => 4
	 [nombre_ubicacion] => SubSubZona 1.1.1)

	 [1] => Array (
	 [id_ubica] => 19
	 [id_padre] => 4
	 [nombre_ubicacion] => SubSubZona 1.1.2 )

	 [2] => Array (
	 [id_ubica] => 22
	 [id_padre] => 4
	 [nombre_ubicacion] => SubSubZona 1.1.3 )
	 )

	 )
	 [1] => Array  (
	 [id_ubica] => 7
	 [id_padre] => 1
	 [nombre_ubicacion] => SubZona 1.2
	 [hijos] => Array(
	 [0] => Array (
	 [id_ubica] => 17
	 [id_padre] => 7
	 [nombre_ubicacion] => SubSubZona 1.2.1 )
	 )
	 )
	 )
	 */
	public function cargaArrayArbol($id_padre){
		$arrayRet=array();
		if(is_int($id_padre)  || is_numeric($id_padre)){
			$datoDB = new UbicacionpropiedadPGDAO();
			$result=$datoDB->coleccionHijos($id_padre);
			$arrayRet=$this->leeDBArray($result);
			for($ind=0; $ind < sizeof($arrayRet);$ind++){
				$arrayHijos=$this->cargaArrayArbol($arrayRet[$ind]['id_ubica']);
				if(sizeof($arrayHijos)>0){
					$arrayRet[$ind]['hijos']=$arrayHijos;
				}
			}
		}
		return $arrayRet;
	}

	/**
	 * Retorna el nombre completo de una zona segun su id, conformando el mismo con los nombres de toda
	 * la estructura jerarquica de la cual depende
	 * @param int $id_ubica -> id de la zona
	 * @return string -> nombre completo de la ubicacion
	 */
	public function armaNombreZona($id_ubica){
		$nombre='';
		$arrayNom=array();
		$arrayIds = explode(',', $id_ubica);
		foreach($arrayIds as $id){
			$ubiBSN=new UbicacionpropiedadBSN(trim($id));
			$padre=$ubiBSN->getObjeto()->getId_padre();
			$nombre=$ubiBSN->getObjeto()->getNombre_ubicacion();
			if($padre!=0){
				$nombre=$this->armaNombreZona($padre).", ".$nombre;
			}
			$arrayNom[]=$nombre;
		}
		$nombre=implode('; ', $arrayNom);
		return $nombre;
	}

	/**
	 * Retorna el nombre completo de una zona segun su id, conformando el mismo con los nombres de toda
	 * la estructura jerarquica de la cual depende en orden desde el más dependiente hasta el origen
	 * @param int $id_ubica -> id de la zona
	 * @return string -> nombre completo de la ubicacion
	 */
	public function armaNombreZonaGMap($id_ubica){
		$nombre='';
		$arrayNom=array();
		$arrayIds = explode(',', $id_ubica);
		foreach($arrayIds as $id){
			$ubiBSN=new UbicacionpropiedadBSN(trim($id));
			$padre=$ubiBSN->getObjeto()->getId_padre();
			$nombre=$ubiBSN->getObjeto()->getNombre_ubicacion();
			if($padre!=0){
				$nombre=$nombre.', '.$this->armaNombreZonaGMap($padre);
			}
			$arrayNom[]=$nombre;
		}
		$nombre=implode('; ', $arrayNom);
		return $nombre;
	}
	
	
	/**
	 * Retorna el nombre de una zona segun su id
	 * @param int $id_ubica -> id de la zona
	 * @return string -> nombre completo de la ubicacion
	 */
	public function armaNombreZonaAbr($id_ubica){
		$nombre='';
		$arrayNom=array();
		$arrayIds = explode(',', $id_ubica);
		foreach($arrayIds as $id){
			$ubiBSN=new UbicacionpropiedadBSN(trim($id));
			$padre=$ubiBSN->getObjeto()->getId_padre();
			$nombre=$ubiBSN->getObjeto()->getNombre_ubicacion();
			$arrayNom[]=$nombre;
		}
		$nombre=implode('; ', $arrayNom);
		return $nombre;
	}


	/**
	 * Determina el ID del nodo principal de un nodo determinado
	 * @param int $id_ubica -> id del nodo
	 * @return int -> id del nodo principal
	 */
	public function definePrincipalByHijo($id_ubica){
		$arrayIds=explode(',',$id_ubica);
		foreach ($arrayIds as $id) {
			$ubiBSN=new UbicacionpropiedadBSN(trim($id));
			$padre=$ubiBSN->getObjeto()->getId_padre();
			if($padre!=0){
				$padre=$this->definePrincipalByHijo($padre);
			}else{
				$padre=$id_ubica;
			}
		}
		return $padre;
	}



	/**
	 * Arma un string con todos los id de los nodos terminales de la cadena jerarquica dependiente de la seleccion dada.
	 * En el caso que no posea nodos hijos, retorna el valor pasado
	 * @param int $id_padre
	 * @return string -> lista de id de subzonas separados por ,
	 */
	public function armaListaSeleciones($id_padre,$lista){
		$arrayIds=explode(',', $id_padre);
		foreach ($arrayIds as $id) {
			if(is_int($id)  || is_numeric($id)){
				$datoDB = new UbicacionpropiedadPGDAO();
				$result=$datoDB->coleccionHijos($id);
				$arrayRet=$this->leeDBArray($result);
				if(sizeof($arrayRet)>0){
					for($ind=0; $ind < sizeof($arrayRet);$ind++){
						$lista=($this->armaListaSeleciones($arrayRet[$ind]['id_ubica'],$lista).','.$id);
					}
				}else{
					if($lista==''){
						$lista=$id;
					}else{
						$lista .=(','. $id);
					}
				}
			}
		}
		return $lista;

	}
}

?>