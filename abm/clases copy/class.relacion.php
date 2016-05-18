<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");
include_once("generic_class/class.PGDAO.php");
include_once ("clases/class.loginwebuserBSN.php");
include_once ("clases/class.propiedadBSN.php");
include_once ("clases/class.zonaBSN.php");
include_once ("clases/class.localidadBSN.php");
include_once ("clases/class.tiporelacionBSN.php");
include_once ("clases/class.clienteBSN.php");

class Relacion {

	private $id_pc;
	private $id_sc;
	private $id_relacion;
	private $desc_pc;
	private $desc_sc;
	private $desc_rel;

	public function __construct($id_pc=0,
	$id_sc=0,
	$id_relacion=0
	){

		Relacion::setId_pc($id_pc);
		Relacion::setId_sc($id_sc);
		Relacion::setId_relacion($id_relacion);
	}

	public function seteaRelacion($_relac){
		$this->setId_pc($_relac->getId_pc());
		$this->setId_sc($_relac->getId_sc());
		$this->setId_relacion($_relac->getId_relacion());
	}

	public function setId_pc($_id_pc){
		$this->id_pc = $_id_pc;
	}

	public function setId_sc($_id_sc){
		$this->id_sc = $_id_sc;
	}

	public function setId_relacion($id_relacion){
		$this->id_relacion = $id_relacion;
	}

	public function setDesc_pc($desc){
		$this->desc_pc=$desc;
	}

	public function setDesc_sc($desc){
		$this->desc_sc=$desc;
	}

	public function setDesc_rel($desc){
		$this->desc_rel=$desc;
	}

	public function getId_pc(){
		return $this->id_pc;
	}

	public function getId_sc(){
		return $this->id_sc;
	}

	public function getId_relacion(){
		return $this->id_relacion;
	}

	public function getDesc_pc(){
		return $this->desc_pc;
	}

	public function getDesc_sc(){
		return $this->desc_sc;
	}

	public function getDesc_rel(){
		return $this->desc_rel;
	}
} // Fin clase RELACION


class RelacionBSN extends BSN {

	protected $clase = "Relacion";
	protected $nombreId = "id_pc";
	protected $relacion;

	public function __construct($_id_pc=0,$_id_sc=0,$_id_rel=0){
		RelacionBSN::seteaMapa();
		if ($_id_pc  instanceof Relacion ){
			RelacionBSN::creaObjeto();
			RelacionBSN::seteaBSN($_id_pc);
		} else {
			if (is_numeric($_id_pc) && is_numeric($_id_sc) && is_numeric($_id_rel)){
				RelacionBSN::creaObjeto();
				if($_id_pc!=0){
					RelacionBSN::setId_pc($_id_pc);
				}
				if($_id_sc!=0){
					RelacionBSN::setId_sc($_id_sc);
				}
				if($_id_rel!=0){
					RelacionBSN::setId_rel($_id_rel);
				}

			}
		}

	}

	protected function setId_pc($_id){
		$this->relacion->setId_pc($_id);
	}

	protected function setId_sc($_id){
		$this->relacion->setId_sc($_id);
	}

	protected function setId_rel($_id){
		$this->relacion->setId_rel($_id);
	}

	/**
	 * retorna el ID del objeto
	 *
	 * @return id del objeto
	 */
	public function getId(){
		return $this->relacion->getId_pc();
	}

	/**
	 * Setea e ID del objeto
	 *
	 * @param unknown_type $id
	 */
	public function setId($id){
		$this->relacion->setId_pc($id);
	}

	/**
	 * ELimina los registros de las relaciones existentes entre Cientes, Usuarios y Propiedades; de acuerdo a los
	 * ID cargados en el objeto al momento de invocar el metodo.
	 * El contenido en 0 o '' de uno de los ID implica la no consideracoin del mismo.
	 */
	public function borraDB(){
		$relDB = new RelacionPGDAO();
		$_id_pc=$this->getObjeto()->getId_pc();
		$_id_sc=$this->getObjeto()->getId_sc();
		$_id_rel=$this->getObjeto()->getId_rel();
		$ret=$relDB->deleteByOpcion($_id_pc,$_id_sc,$_id_rel);
	}

	/**
	 * Retorna un array Bidimensional conteniendo una coleccion de datos, con los registros de las relaciones que cumplen
	 * con los parametros de entrada; el contener un 0 o '' implica que dicho parametro no se tomara en cuenta para el armado
	 * de la coleccion
	 * @param int $_id_pc -> id del primer parametro de la relacion, basado en el criterio de tipo
	 * @param int $_id_sc -> id del segundo parametro de la relacion, basado en el criterio de tipo
	 * @param int $_id_rel ->  id del tipo de relacion
	 * @return string[][] -> conteniendo la coleccion de registros
	 */
	public function coleccionRelaciones($_id_pc=0,$_id_sc=0,$_id_rel=0){
		$arrayRet=array();
		$relDB = new RelacionPGDAO();
		$result=$relDB->coleccionRelaciones($_id_pc,$_id_sc,$_id_rel);
		$arrayRet=$this->leeDBArray($result);
		$retorno=$this->cargaDatosRelaciones($arrayRet);
		return $retorno;
	}

	protected function cargaDatosRelaciones($arrayDatos){
		// Recorrer el array y levantar los datos de las tablas que sea necesario segun el tipo de relacion que resulte de ID_REL
		// Cargar la informacion en los detalles de cada uno de ellos.
		$relAnt=0;
		$pcAnt=0;
		$scAnt=0;

		$pPar='';
		$pAnt='';
		$sPar='';
		$sAnt='';

		$tipo='';
		$descRel='';
		$descPc='';
		$descSc='';

		$trelBSN = new TiporelacionBSN();

		$cant=sizeof($arrayDatos);
		for($pos=0;$pos<$cant;$pos++) {
			// Busco el tipo de relacion cada vez que sea diferente al anterio
			$tiporel=$arrayDatos[$pos]['id_relacion'];
			if($tiporel!=$relAnt){
				$relAnt=$tiporel;
				$trelBSN->setId($tiporel);
				$trelBSN->cargaById($tiporel);
				$tipo=$trelBSN->getObjeto()->getTiporelacion();
				$descRel=$trelBSN->getObjeto()->getRelacion()." - ".$trelBSN->getObjeto()->getGrado();
			}
			$pPar=substr($tipo, 0,1);
			$sPar=substr($tipo, 1,1);

			// Busco el Primer componente cada vez que difiera el ID o el tipo de Primer Componente
			$pId=$arrayDatos[$pos]['id_pc'];
			if($pId!=$pcAnt || $pPar!=$pAnt){
				$pcAnt=$pId;
				$pAnt=$pPar;
				$descPc=$this->buscaDescripcionComponente($pPar, $pId);
			}

			// Busco el Segundo componente cada vez que difiera el ID o el tipo de Segundo Componente
			$sId=$arrayDatos[$pos]['id_sc'];
			if($sId!=$scAnt || $sPar!=$sAnt){
				$scAnt=$sId;
				$sAnt=$sPar;
				$descSc=$this->buscaDescripcionComponente($sPar, $sId);
			}
			$arrayDatos[$pos]['desc_pc']=$descPc;
			$arrayDatos[$pos]['desc_sc']=$descSc;
			$arrayDatos[$pos]['desc_rel']=$descRel;

		}
		return $arrayDatos;
	}

	/**
	 * Busca y arma la descripcion de los componentes basado en el tipo de componente al que se hace referencia
	 * @param string $tipo -> Identificacion del tipo de componente
	 * @param int $id -> Id del componente
	 * @return string -> descripcion del componente
	 */
	protected function buscaDescripcionComponente($tipo,$id){
		$retorno='';
		if(is_numeric($id) && $id!=0){
			switch ($tipo) {
				case 'U':
					$retorno=$this->buscaDescripcionUsuario($id);
					break;
				case 'C':
					$retorno=$this->buscaDescripcionContacto($id);
					break;
				case 'P':
					$retorno=$this->buscaDescripcionPropiedad($id);
					break;
				default:
					break;
			}
		}
		return $retorno;
	}

	public function buscaDescripcionUsuario($id){
		$desc='';
		$userBSN = new LoginwebuserBSN();
		$userBSN->cargaById($id);
		$desc=$userBSN->getObjeto()->getNombre()." ".$userBSN->getObjeto()->getApellido();
		return $desc;
	}

	public function buscaDescripcionPropiedad($id){
		$desc='';
		$propBSN = new PropiedadBSN($id);
		$desc=$propBSN->getObjeto()->getCalle()." ".$propBSN->getObjeto()->getNro();
		$zonaBSN=new ZonaBSN($propBSN->getObjeto()->getId_zona());
		$locaBSN=new LocalidadBSN($propBSN->getObjeto()->getId_loca());
		$desc=$propBSN->getObjeto()->getCalle()." ".$propBSN->getObjeto()->getNro()." (".$zonaBSN->getObjeto()->getNombre_zona()." - ".$locaBSN->getObjeto()->getNombre_loca().")";
		return $desc;
	}

	protected function buscaDescripcionContacto($id){
		$desc='';
		$cliBSN = new ClienteBSN();
		$cliBSN->cargaById($id);
		$desc=$cliBSN->getObjeto()->getNombre()." ".$cliBSN->getObjeto()->getApellido();
//		$desc='CONTACTO NO ENCONTRADO';

		return $desc;
	}

}

class RelacionPGDAO extends PGDAO {

	protected $INSERT="INSERT INTO #dbName#.relaciones (id_pc,id_sc,id_relacion) values (#id_pc#,#id_sc#,#id_relacion#)";
	protected $DELETEBASE="DELETE FROM #dbName#.relaciones ";

	protected $FINDBYID="SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones WHERE id_pc=#id_pc# AND id_sc=#id_sc# AND id_relacion=#id_relacion#";

	protected $FINDBYCLAVE="SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones WHERE id_pc=#id_pc# AND id_sc=#id_sc# AND id_relacion=#id_relacion#";

	protected $COLECCION="SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones ORDER BY id_pc,id_relacion";

	protected $COLECCIONBASE="SELECT id_pc,id_sc,id_relacion FROM #dbName#.relaciones";

	public function deleteByOpcion($_id_pc=0,$_id_sc=0,$_id_rel=0){
		$where=$this->armaWhere($_id_pc,$_id_sc,$_id_rel);
		$resultado=$this->execSql($this->DELETEBASE.$where,$parametro);
		if (!$resultado){
			$this->onError($COD_DELETE,"BORRADO POR OPCION ".$this->DELETEBASE.$where);
		}
		return $resultado;
	}

	public function update(){
		return true;
	}

	public function coleccionRelaciones($_id_pc=0,$_id_sc=0,$_id_rel=0){
		$parametro = func_get_args();
		$where=$this->armaWhere($_id_pc,$_id_sc,$_id_rel);
		$order=$this->armaOrden($_id_pc,$_id_sc,$_id_rel);
		$resultado=$this->execSql($this->COLECCIONBASE.$where.$order,$parametro);
		if (!$resultado){
			$this->onError($COD_COLLECION,"RELACIONES ENTRE COMPONENTES".$this->COLECCIONBASE.$where.$order);
		}
		return $resultado;
	}

	protected function armaWhere($_id_pc=0,$_id_sc=0,$_id_rel=0){
		$where = ' WHERE ';
		if(is_numeric($_id_pc) && $_id_pc!=0){
			$where.=" id_pc=$_id_pc ";
		}
		if(is_numeric($_id_sc) && $_id_sc!=0){
			if($where!=' WHERE '){
				$where.='AND';
			}
			$where.=" id_sc=$_id_sc ";
		}
		if(is_numeric($_id_rel) && $_id_rel!=0){
			if($where!=' WHERE '){
				$where.='AND';
			}
			$where=" id_relacion=$_id_rel ";
		}
		return $where;
	}

	protected function armaOrden($_id_pc=0,$_id_sc=0,$_id_rel=0){
		$orden = '';
		if(is_numeric($_id_rel) && $_id_rel!=0){
			$orden="id_relacion";
		}
		if(is_numeric($_id_pc) && $_id_pc!=0){
			if($orden!=''){
				$orden.=',';
			}
			$orden.=" id_pc";
		}
		if(is_numeric($_id_sc) && $_id_sc!=0){
			if($orden!=''){
				$orden.=',';
			}
			$orden.=" id_sc ";
		}
		if($orden!=''){
			$orden = ' ORDER BY '.$orden;
		}
		return $orden;
	}

} // Fin clase DAO

class RelacionVW {
	private $relacion;
	private $arrayForm;

	public function __construct($_id_pc=0,$_id_sc=0,$_relacion=0) {
		RelacionVW::creaRelacion();
		if ($_id_pc instanceof Relacion ) {
			RelacionVW::seteaRelacion( $_id_pc);
		}
		if (is_numeric ( $_id_pc) && is_numeric($_id_sc) && is_numeric($_relacion)) {
			if ($_id_pc != 0 || $_id_sc!=0 || $_relacion!=0) {
				RelacionVW::cargaRelacion ( $_id_pc,$_id_sc,$_relacion);
			}
		}
	}

	public function cargaRelacion($_id_pc=0,$_id_sc=0,$_relacion=0) {
		$relacion = new RelacionBSN( $_id_pc,$_id_sc,$_relacion );
		$this->seteaRelacion( $relacion->getObjeto () );
	}

	public function getRelacion() {
		return $this->relacion->getId_relacion();
	}

	protected function creaRelacion() {
		$this->relacion = new Relacion();
	}

	protected function seteaRelacion($_relacion) {
		$this->relacion = $_relacion;
		$relacion = new RelacionBSN( $_relacion);
		$this->arrayForm = $relacion->getObjetoView();
	}


	public function cargaRelacionUsuarioCliente($_id_user=0,$_id_contacto=0){
		if(is_numeric($_id_user) && is_numeric($_id_contacto)){
			$this->cargaDatosRelacion('UC', $_id_user, $_id_contacto, 0);
		}
	}

	public function cargaRelacionUsuarioPropiedad($_id_user=0,$_id_prop=0){
		if(is_numeric($_id_user) && is_numeric($_id_prop)){
			$this->cargaDatosRelacion('UP', $_id_user, $_id_prop, 0);
		}
	}

	public function cargaRelacionUsuarioUsuario($_id_userp=0,$_id_userc=0){
		if(is_numeric($_id_userp) && is_numeric($_id_userc)){
			$this->cargaDatosRelacion('UU', $_id_user, $_id_userc, 0);
		}
	}

	public function cargaRelacionClientePropiedad($_id_contacto=0,$_id_prop=0){
		if(is_numeric($_id_contacto) && is_numeric($_id_prop)){
			$this->cargaDatosRelacion('CP', $_id_contacto, $_id_prop, 0);
		}
	}

	public function cargaRelacionClienteCliente($_id_contactop=0,$_id_contactoc=0){
		if(is_numeric($_id_contactop) && is_numeric($_id_contactoc)){
			$this->cargaDatosRelacion('CC', $_id_contactop, $_id_contactoc, 0);
		}
	}


	protected function cargaDatosRelacion($tipo,$_id_pc,$_id_sc,$_id_rel) {
		//		$menu = new Menu ( );
		//		$menu->dibujaMenu ( 'carga' ,'opcion');
		$tipoRelBSN=new TiporelacionBSN();

		print "<form action='carga_contactoZP.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaPerfil(this);'>\n";
		print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo'>Carga de Relacion ";
		print $tipoRelBSN->getDescripcionTipo($tipo);
		print "</td></tr>\n";
		print "<tr><td align='center'>";
		print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
		// primer componente
		if($_id_pc!=0){
			$this->armaDetalleComponente(substr($tipo,0,1), $_id_pc,'id_pc');
		}else{
			$this->armaComboComponente(substr($tipo,0,1), $_id_pc,'id_pc');
		}

		// segundo componente
		if($_id_sc!=0){
			$this->armaDetalleComponente(substr($tipo,1,1), $_id_sc,'id_sc');
		}else{
			$this->armaComboComponente(substr($tipo,1,1), $_id_sc,'id_sc');
		}


		print "<tr><td class='cd_celda_texto' width='15%'>Relacion<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";
		if($_id_rel==0){
			$tipoRelBSN->comboRelacion(0,$tipo,'id_relacion');
		}else{
			$tipoRelBSN->cargaById($_id_rel);
			print $tipoRelBSN->getObjeto()->getRelacion()." - ".$tipoRelBSN->getObjeto()->getGrado();
			print "<input type='hidden' name='id_relacion' id='id_relacion' value='$_id_rel'>";
		}
		print "</td></tr>\n";

		print "<br>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "<tr><td colspan='2' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
		print "</td></tr>\n</table>\n";
		print "</form>\n";
	}

	protected function armaDetalleComponente($tipo,$id,$campo){
		print "<tr><td class='cd_celda_texto' width='15%'>";
		$relAux=new RelacionBSN();
		switch ($tipo) {
			case 'U':
				$label="Usuario";
				$mostrar=$relAux->buscaDescripcionUsuario($id);
				break;
			case 'P':
				$label="Propiedad";
				$mostrar=$relAux->buscaDescripcionPropiedad($id);
				break;
			case 'c':
				$label="Contacto";
				$mostrar="No existen datos para mostrar";
			default:
				;
				break;
		}
		print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
		print "<td width='85%'>";
		print $mostrar;
		print "<input type='hidden' name='$campo' id='$campo' value='$id'>";
		print "</td></tr>\n";

	}

	protected function armaComboComponente($tipo,$id,$campo){
		print "<tr><td class='cd_celda_texto' width='15%'>";
		switch ($tipo) {
			case 'U':
				$label="Usuario";
				print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
				print "<td width='85%'>";
				$usrBSN2=new LoginwebuserBSN();
				$usrBSN2->cargaById($id);
				$usrBSN2->comboUsuarios($id,$campo);
				break;
			case 'P':
				$label="Propietario";
				print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
				print "<td width='85%'>";

				print "No se ha especificado una propiedad";
				break;
			case 'C':
				$label="Cliente";
				print "$label<span class='obligatorio'>&nbsp;&bull;</span></td>";
				print "<td width='85%'>";
				$cliBSN=new ClienteBSN();
				$cliBSN->cargaById($id);
				$cliBSN->comboUsuarios($id,$campo);
//				print "No existen datos para mostrar";
				break;
			default:
				break;
		}
		print "</td></tr>\n";

	}


	/** * Lee desde un formulario los datos cargados para el relacion. * Los registra en un objeto del tipo relacion relacion de esta clase * */
	public function leeDatosRelacionVW() {
		$relacion = new RelacionBSN();
		$this->relacion= $relacion->leeDatosForm ($_POST);
	}


	/**    OK * Muestra una tabla con los datos de las relacions y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
	public function vistaTablaRelacion($_id_pc=0,$_id_sc=0,$_id_rel=0) {
		$fila = 0;
		print "<script type='text/javascript' language='javascript'>\n";
		print "function envia(opcion,id){\n";
		print "     document.forms.lista.perfil.value=id;\n";
		print "   	submitform(opcion);\n";
		print "}\n";
		print "</script>\n";
		print "<span class='pg_titulo'>Listado de Relaciones</span><br><br>\n";
		//		$menu = new Menu ( );
		//		$menu->dibujaMenu ( 'lista', 'opcion' );
		print "<form name='lista' method='POST' action='respondeMenu.php'>";
		print "  <table class='cd_tabla' width='100%'>\n";
		print "    <tr>\n";
		//		print "     <td class='cd_lista_titulo' colspan='3'>&nbsp;</td>\n";
		print "     <td class='cd_lista_titulo'>Origen</td>\n";
		print "     <td class='cd_lista_titulo'>Destino</td>\n";
		print "     <td class='cd_lista_titulo'>Relacion</td>\n";
		print "	  </tr>\n";
		$evenBSN = new RelacionBSN();
		$arrayEven = $evenBSN->coleccionRelaciones($_id_pc,$_id_sc,$_id_rel);
		if (sizeof ( $arrayEven ) == 0) {
			print "No existen datos para mostrar";
		} else {
			foreach ( $arrayEven as $Even ) {
				if ($fila == 0) {
					$fila = 1;
				} else {
					$fila = 0;
				}
				print "<tr>\n";
				/*				print "	 <td align='center' width='25' class='row" . $fila . "'>";
				 print "    <a href='javascript:envia(822,\"" . $Even ['perfil'] . "\");' border='0'>";
				 print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
				 print "	 <td  align='center' width='25' class='row" . $fila . "'>";
				 print "    <a href='javascript:envia(823,\"" . $Even ['perfil'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
				 print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
				 print "  </td>\n";
				 print "	 <td align='center' width='25' class='row" . $fila . "'>";
				 print "    <a href='javascript:envia(824,\"" . $Even ['perfil'] . "\");' border='0'>";
				 print "       <img src='images/asignar.png' alt='Asignar' title='Asignar' border=0></a></td>";
				 */
				print "	 <td  class='row" . $fila . "'>" . $Even ['desc_pc'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['desc_sc'] . "</td>\n";
				print "	 <td  class='row" . $fila . "'>" . $Even ['desc_rel'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		print "<input type='hidden' name='perfil' id='perfil' value=''>";
		print "<input type='hidden' name='opcion' id='opcion' value=''>";
		print "</form>";
	}


	public function grabaModificacion() {
		$retorno = false;
		$relacion = new RelacionBSN ( $this->relacion );
		$retUPre = $relacion->actualizaDB ();
		if ($retUPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabaci�n de los datos.<br>";
		}
		return $retorno;
	}


	public function grabaRelacion() {
		$retorno = false;
		$relacion = new RelacionBSN ( $this->relacion );
		//Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//
		//$existente = $relacion->controlDuplicado ( $this->relacion->getNombre_relacion () );
		//if($existente){
		//	echo "Ya existe un relacion con ese Titulo";
		//} else {
		$retIPre=$relacion->insertaDB();
		//	die();
		if ($retIPre) {
			echo "Se proceso la grabacion en forma correcta<br>";
			$retorno = true;
		} else {
			echo "Fallo la grabaci�n de los datos<br>";
		}
		//}
		// Fin control de Duplicados
		return $retorno;
	}
}

?>
