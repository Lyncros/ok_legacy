<?php
include_once("generic_class/class.db_pg_connection.php");
include_once("generic_class/class.db_my_connection.php");
include_once("generic_class/class.cargaConfiguracion.php");
include_once("generic_class/class.maper.php");


/**
 * Ver 1.0 del 28-01-2011
 * Incluye registro de error de SQL en tabla de errores sqlerror, on fecha de error y texto del sql
 * Clase basica de trabajo con objetos de base de datos
 * utilizar por extencion.
 * Genera conectividad con la Base de datos
 *
 * Ver 1.2 del 11/07/2011
 * Incluye la lectura del parametro ROOT_PATH
 *
 * Ver 1.3 del 08/10/2011
 * INcluye la actualizacion del registro en la tabla de log
 */

class PGDAO {

	private $conn;
	private $dato;
	private $tipodb;
	private $dbname;
	private $sqlError;
	private $textoSql;
	private $ROOT_PATH;

	public function __construct($dato=0){
		//verifica la existencia del archivo de configuraci�n y el path relativo del mismo para efectuar la inclucion
		PGDAO::setRootPath();
		$conf= CargaConfiguracion::getInstance('');
		//		 $conf=CargaConfiguracion::getInstance('');
		$db_tipo=$conf->leeParametro("tipodb");
		$db_host=$conf->leeParametro("host");
		$db_port=$conf->leeParametro("port");
		$db_name=$conf->leeParametro("name");
		$db_user=$conf->leeParametro("user");
		$db_pass=$conf->leeParametro("dbpass");
		$ROOT_PATH = PGDAO::getRootPath();
		$this->dbname = $db_name;
//		print $db_host." - ".$db_port." - ".$db_name." - ".$db_user." - ".$db_pass."<br>";
		switch ($db_tipo) {
			case "my":
				$oconn=DB_my_connection::getInstance($ROOT_PATH,$db_host,$db_port,$db_name,$db_user,$db_pass);
				break;
			case "pg":
				$oconn=DB_pg_connection::getInstance($ROOT_PATH,$db_host,$db_port,$db_name,$db_user,$db_pass);
				break;

			default:
				break;
		}
		$this->conn=$oconn->getConector();
		$this->tipodb=$db_tipo;
//		print $this->conn;
		if ($dato!=0){
			PGDAO::seteaDato($dato);
		}
	}

	protected function getRootPath(){
		return $this->ROOT_PATH;
	}

	protected function setRootPath(){
		$this->ROOT_PATH=$_SERVER['DOCUMENT_ROOT'];
	}

	protected function seteaDato($dato){
		$this->dato=$dato;
	}



	/**
	 * Reemplaza la identificacion de la tabla y los valores de la sentencia SQL pasada como parametro
	 * con los parametros pasados en la creacion del objeto como una tabla clave-valor ; donde la clave
	 * responde al nombre del campo en la tabla definido en el mapa
	 *
	 * @param string SQL
	 * @param array Variables que se reemplazan en  el String de SQL
	 * @return string SQL con los valores reemplazando las variables
	 */

	protected function reemplazaParametro(){
		$vector=func_get_arg(0);
		$params=$this->dato;
		//		print_r($params);
		//		echo "<br>";
		$cant=sizeof($params);
		$accion=$vector[0];
		if (sizeof($params)!=0){
			$claves=array_keys($params);
			//		for($x=1;$x<$cant;$x++){
			foreach($claves as $clave){
				$str="#".($clave)."#";
				$accion=str_replace($str,$params[$clave],$accion);
			}
		}
		return $accion;
	}

	protected function reemplazaDBName($inst){
		$str="#dbName#";
		$accion=str_replace($str,$this->dbname,$inst);
		return $accion;
	}


	/**
	 * Procesa las instrucciones SQL pasadas como primer parametro, para ello utiliza el reemplazo de las
	 * variables definidas como %x por el valor ordinal de la lista de parametros.
	 *
	 * @param string SQL
	 * @param array Variables que se reemplazan en  el String de SQL
	 * @return resultset con los atos traidos del SQL
	 */

	public function execSql(){
		$cant=func_num_args();
		$instSql="";
		$instSql=$this->reemplazaParametro(func_get_args());
		$instSql=$this->reemplazaDBName($instSql);
//		echo $instSql;
//		die();
		switch ($this->tipodb) {
			case "my":
				$resultado=mysql_query($instSql,$this->conn);
				$this->sqlError=mysql_errno()." - ".mysql_error();
				break;
			case "pg":
				$resultado=pg_query($this->conn,$instSql);
				$this->sqlError=pg_errormessage();
				break;
		}
		if(!$resultado){
			$this->textoSql=$instSql;
		}
		return $resultado;
	}



	public function getUltimoIdInsertado(){
		$resultado = $this->execSql("SELECT LAST_INSERT_ID()",$parametro);
		if(!$resultado){
			$this->onError("COD_LASTINSERT","SELECT LAST_INSERT_ID()");
		}
		return $resultado;
	}

	public function registroLog($id_log=0,
	$fecha='',
	$id_user=0,
	$tarea='',
	$id=0,
	$proceso='',
	$estado='',
	$observacion=''){
            $parametro='';
		$instSql="INSERT INTO #dbName#.log (fecha,id_user,tarea,id,proceso,estado,observacion) values ('$fecha',$id_user,'$tarea','$id','$proceso','$estado','$observacion')";
		$resultado = $this->execSql($instSql,$parametro);
		if(!$resultado){
			$this->onError("COD_GRABALOG",'Error en la GRABACION del LOG '.$instSql);
		}

	}

	protected function registroError($_texto,$_sql,$_usr,$_error){
		$textoesc='';
		$erroresc='';
		switch ($this->tipodb) {
			case "my":
				$textoesc=mysql_real_escape_string($_texto);
				$sqlesc=mysql_real_escape_string($_sql);
				$erroresc=mysql_real_escape_string($_error);
				break;
			case "pg":
				$textoesc=pg_escape_string($_texto);
				$sqlesc=pg_escape_string($_sql);
				$erroresc=pg_escape_string($_error);
				break;
		}
		$instSql="INSERT INTO #dbName#.sqlerror (fecha,textoerror,textosql,id_user,sqlerror) values (localtimestamp,'".$textoesc."','".$sqlesc."',".$_usr.",'".$erroresc."')";
		$instSql=$this->reemplazaDBName($instSql);
		switch ($this->tipodb) {
			case "my":
				$resultado=mysql_query($instSql,$this->conn);
				break;
			case "pg":
				$resultado=pg_query($this->conn,$instSql);
				break;
		}
		$this->sqlError='';
	}



	/**
	 * Metodo gnerico de INSERT en tablas, funciona en conjunto con la variable de INSERT definida dentro
	 * del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del insert
	 */
	public function insert(){
		$parametro = func_get_args();
		$resultado = $this->execSql($this->INSERT,$parametro);
		//			die();
		if(!$resultado){
			$this->onError("COD_INSERT",$this->INSERT);
		}
		return $resultado;
	}


	/**
	 * Metodo gnerico de UPDATE en tablas, funciona en conjunto con la variable de UPDATE definida dentro
	 * del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del UPDATE
	 */
	public function update(){
		$parametro = func_get_args();
		$resultado = $this->execSql($this->UPDATE,$parametro);
		if(!$resultado){
			$this->onError("COD_UPDATE",$this->UPDATE);
		}
		return $resultado;
	}

	/**
	 * Metodo generico de Borrado por ID de la tabla, funciona en conjunto con la variable de DELETE definida dentro
	 * del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del query
	 */

	public function delete(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->DELETE,$parametro);
		if (!$resultado){
			$this->onError("COD_DELETE",$this->DELETE);
		}
		return $resultado;
	}

	/**
	 * Metodo generico de Armado de una Coleccion General de los objeto s de la tabla
	 * , funciona en conjunto con la variable de COLECCION definida dentro
	 * del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del query
	 */

	public function coleccion(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCION);
		if (!$resultado){
			$this->onError("COD_COLECCION",$this->COLECCION);
		}
		return $resultado;
	}

	/**
	 * Metodo generico de armado de una Coleccion de la tabla en base a campos principales
	 * , funciona en conjunto con la variable de COLECCIONPRINCIPAL definida dentro
	 * del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del query
	 */

	public function coleccionPrincipal(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->COLECCIONPRINCIPAL,$parametro);
		if (!$resultado){
			$this->onError("COD_COLECCION",$this->COLECCIONPRINCIPAL);
		}
		return $resultado;
	}

	/**
	 * Metodo generico que sirve para retornar el valor maximo de una coleccion. funciona en conjunto con la variable
	 *  definida $MAYORORDEN dentro del metodo de la clase especifica desde la que se invoca.
	 * El campo del maximo valor debe estar definido 'AS max'
	 *	@param lista de parametro necesarios para el metodo
	 * @return resultado del query
	 */
	public function maximoPosicion(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->MAYORORDEN,$parametro);
		if (!$resultado){
			$this->onError("COD_COLECCION",$this->MAYORORDEN);
		}
		return $resultado;
	}

	/**
	 * Metodo generico de Busqueda por el ID de la tabla, funciona en conjunto con la variable de
	 * FINDBYID definida dentro del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del query
	 */
	public function findById(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->FINDBYID,$parametro);
		if (!$resultado){
			$this->onError("COD_FIND",$this->FINDBYID);
		}
		return $resultado;
	}



	/**
	 * Metodo generico de Busqueda por la Clave Principal de la tabla, funciona en conjunto con la variable de
	 * FINDBYCLAVE definida dentro del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del query
	 */

	public function findByClave(){
		$parametro = func_get_args();
		$resultado=$this->execSql($this->FINDBYCLAVE,$parametro);
		if (!$resultado){
			$this->onError("COD_FIND",$this->FINDBYCLAVE);
		}
		return $resultado;
	}


	public function onError($codigo,$error){
            if(!isset($_SESSION['UserId'])){
                $_SESSION['UserId']=-99;
            }
		$this->registroError($codigo." - ".$error,$this->textoSql,$_SESSION['UserId'],$this->sqlError);
		//		die("Fallo la aplicacion con codigo $codigo en $error");
	}

} // Fin clase DAO

?>
