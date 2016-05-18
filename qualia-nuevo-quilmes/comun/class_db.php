<?php
/**
 * Class para manejo de base de datos
 *
 * @version 2.1
 * @author Andres Carizza www.andrescarizza.com.ar
 */
class class_db{
	
	/** Graba log con los errores de BD **/
	public $grabarArchivoLogError = false;
	
	/** Graba log con todas las consultas realizadas **/
	public $grabarArchivoLogQuery = false;
	
	/** Imprime cuando hay errores sql **/
	public $mostrarErrores = true;
	
	/** Usar die() si hay un error de sql. Esto es util para etapa de desarrollo **/
	public $dieOnError = false;
	
	/** Setear un email para enviar email cuando hay errores sql **/
	public $emailAvisoErrorSql;
	
	private $dbUser;
	private $dbHost;
	private $dbPass;
	private $dbName;
	private $charset;
	public $con;
	
	public function __construct($host, $user, $pass, $db, $charset){
		$this->dbHost = $host;
		$this->dbUser = $user;
		$this->dbPass = $pass;
		$this->dbName = $db;
		$this->charset = $charset;
	}
	
	public function connect(){
		$this->con = mysqli_connect($this->dbHost , $this->dbUser , $this->dbPass) or die(mysqli_error($this->con));
		mysqli_select_db($this->con, $this->dbName) or die(mysqli_error($this->con));
		mysqli_set_charset($this->con, $this->charset) or die(mysqli_error($this->con));
	}
	
	public function query($str_query){
		global $debug, $debugsql, $sitio;
	
		$result = mysqli_query($this->con, $str_query);
	
		if($debug) echo "<div style='background-color:#E8E8FF; padding:10px; margin:10px; font-family:Arial; font-size:11px; border:1px solid blue'>".$this->format_query_imprimir($str_query)."</div>";
		if($debugsql) consola($str_query);
	
		if ($this->grabarArchivoLogQuery) {
			$str_log = date("d/m/Y H:i:s")." ".getenv("REQUEST_URI")."\n";
			$str_log .= $str_query;
			$str_log .= "\n------------------------------------------------------\n";
			error_log($str_log);
		}
	
		if(mysqli_errno($this->con)!=0 and mysqli_errno($this->con)!=1062){ //el error 1062 es "Duplicate entry"
			if( $this->mostrarErrores ){
				echo "<div style='background-color:#FFECEC; padding:10px; margin:10px; font-family:Arial; font-size:11px; border:1px solid red'>";
				echo "<B>Error:</B> ".mysqli_error($this->con)."<br><br>";
				echo "<B>Página:</B> ".getenv("REQUEST_URI")."<br>";
				echo "<br>".$this->format_query_imprimir($str_query);
				echo "</div>";
			}else{
				echo "DB Error";
			}
			if($this->dieOnError) die("class_db die()");
		}
		
		if (mysqli_errno($this->con)!=0 and mysqli_errno($this->con)!=1062) {
			if ($this->grabarArchivoLogError) {
				$str_log = "******************* ERROR ****************************\n";
				$str_log .= date("d/m/Y H:i:s")." ".getenv("REQUEST_URI")."\n";
				$str_log .= "IP del visitante: ".getenv("REMOTE_ADDR")."\n";
				$str_log .= "Error: ".mysqli_error($this->con)."\n";
				$str_log .= $str_query;
				$str_log .= "\n------------------------------------------------------\n";
				error_log($str_log);
			}

			//envio de aviso de error
			if( $this->emailAvisoErrorSql != "" ){
				@mail($this->emailAvisoErrorSql, "Error MySQL", "Error: ".mysqli_error($this->con)."\n\nPágina:".getenv("REQUEST_URI")."\n\nIP del visitante:".getenv("REMOTE_ADDR")."\n\nQuery:".$str_query);
			}

		}
	
		return $result;
	}
	
	public function fetch_assoc($result, $limpiarEntidadesHTML=false){
		if ($limpiarEntidadesHTML) {
			return limpiarEntidadesHTML(mysqli_fetch_assoc($result));
		}else{
			return mysqli_fetch_assoc($result);
		}
	}
	
	public function fetch_array($result, $limpiarEntidadesHTML=false){
		if ($limpiarEntidadesHTML) {
			return limpiarEntidadesHTML(mysqli_fetch_array($result));
		}else{
			return mysqli_fetch_array($result);
		}
	}
	
	public function fetch_object($result){
		return mysqli_fetch_object($result);
	}
	
	public function num_rows($result){
		return mysqli_num_rows($result);
	}
	
	public function num_fields($result){
		return mysqli_num_fields($result);
	}
	
	public function result($result, $row, $field = null){
		return mysqli_result($result, $row, $field);
	}
	
	public function affected_rows(){
		return mysqli_affected_rows($this->con);
	}
	
	public function data_seek($result, $row_number){
		return mysqli_data_seek($result, $row_number);
	}
	
	public function insert_id(){
		return mysqli_insert_id($this->con);
	}
	
	public function errno(){
		return mysqli_errno($this->con);
	}
	
	public function error(){
		return mysqli_error($this->con);
	}

	public function close(){
		return mysqli_close($this->con);
	}
	
	public function real_escape_string($string){
		return mysqli_real_escape_string($this->con, $string);
	}
	
	private function format_query_imprimir($str_query){
		$str_query_debug = nl2br(htmlentities($str_query));
		$str_query_debug = eregi_replace("SELECT",   "<span style='color:green;font-weight:bold;'>SELECT</span>",    $str_query_debug);
		$str_query_debug = eregi_replace("INSERT",   "<span style='color:#660000;font-weight:bold;'>INSERT</span>",  $str_query_debug);
		$str_query_debug = eregi_replace("UPDATE",   "<span style='color:#FF6600;font-weight:bold;'>UPDATE</span>",  $str_query_debug);
		$str_query_debug = eregi_replace("REPLACE",  "<span style='color:#FF6600;font-weight:bold;'>UPDATE</span>",  $str_query_debug);
		$str_query_debug = eregi_replace("DELETE",   "<span style='color:#CC0000;font-weight:bold;'>DELETE</span>",  $str_query_debug);
		$str_query_debug = eregi_replace("FROM",     "<br/><B>FROM</B>",                           $str_query_debug);
		$str_query_debug = eregi_replace("WHERE",    "<br/><B>WHERE</B>",                          $str_query_debug);
		$str_query_debug = eregi_replace("ORDER BY", "<br/><B>ORDER BY</B>",                       $str_query_debug);
		$str_query_debug = eregi_replace("GROUP BY", "<br/><B>GROUP BY</B>",                       $str_query_debug);
		$str_query_debug = eregi_replace("INTO",     "<br/><B>INTO</B>",                           $str_query_debug);
		$str_query_debug = eregi_replace("VALUES",   "<br/><B>VALUES</B>",                         $str_query_debug);
		return $str_query_debug;
	}
	
	/**
	 * Obtiene el valor de un campo de una tabla. Si no obtiene una sola fila retorna FALSE
	 *
	 * @param string $table Tabla
	 * @param string $field Campo
	 * @param string $id Valor para seleccionar con el campo clave
	 * @param string $fieldId Campo clave de la tabla
	 * @return string o false
	 */
	public function getValue($table, $field, $id, $fieldId="id"){
		$result = mysqli_query($this->con, "SELECT $field FROM $table WHERE $fieldId='$id'");
		
		if ($result and mysqli_num_rows($result) == 1) {
			if ($fila = mysqli_fetch_assoc($result)) {
				return $fila[$field];
			}
		}else{
			return false;
		}
		
	}
	
	/**
	 * Obtiene una fila de una tabla. Si no obtiene una sola fila retorna FALSE
	 *
	 * @param string $table Tabla
	 * @param string $id Valor para seleccionar con el campo clave
	 * @param string $fieldId Campo clave de la tabla
	 * @return array mysqli_fetch_assoc o false
	 */
	public function getRow($table, $id, $fieldId="id", $limpiarEntidadesHTML=false){
		$result = mysqli_query($this->con, "SELECT * FROM $table WHERE $fieldId='$id'");
		
		if ($result and mysqli_num_rows($result) == 1) {
			if ($limpiarEntidadesHTML) {
				return limpiarEntidadesHTML(mysqli_fetch_array($result));
			}else{
				return mysqli_fetch_array($result);
			}
		}else{
			return false;
		}
		
	}
	
	/**
	 * Retorna un array con el arbol jerarquico a partir del nodo indicado (0 si es el root)
	 * Esta funcion es para ser usada en tablas con este formato de campos: id, valor, idPadre
	 *
	 * @param string $tabla Nombre de la tabla
	 * @param string $campoId Nombre del campo que es id de la tabla
	 * @param string $campoPadreId Nombre del campo que es el FK sobre la misma tabla
	 * @param string $campoDato Nombre del campo que tiene el dato
	 * @param string $orderBy Para usar en ORDER BY $orderBy
	 * @param int $padreId El id del nodo del cual comienza a generar el arbol, o 0 si es el root
	 * @param int $nivel No enviar (es unicamente para recursividad)
	 * @return array Formato: array("nivel" => X, "dato" => X, "id" => X, "padreId" => X);
	 * 
	 * Un código de ejemplo para hacer un arbol de categorias con links:
	 
	 	 for ($i=0; $i<count($arbol); $i++){
	     echo str_repeat("&nbsp;&nbsp;&nbsp;", $arbol[$i][nivel])."<a href='admin_categorias.php?c=".$arbol[$i][id]."'>".$arbol[$i][dato]."</a><br/>";
     }
	 */
	public function getArbol($tabla, $campoId, $campoPadreId, $campoDato, $orderBy, $padreId=0, $nivel=0){
		$tabla = mysqli_real_escape_string($this->con,$tabla);
		$campoId = mysqli_real_escape_string($this->con,$campoId);
		$campoPadreId = mysqli_real_escape_string($this->con,$campoPadreId);
		$campoDato = mysqli_real_escape_string($this->con,$campoDato);
		$orderBy = mysqli_real_escape_string($this->con,$orderBy);
		$padreId = mysqli_real_escape_string($this->con,$padreId);
		
		$result = $this->query("SELECT * FROM $tabla WHERE $campoPadreId='$padreId' ORDER BY $orderBy");
		
		$arrayRuta = array();
		
		while($fila = $this->fetch_array($result)){
			$arrayRuta[] = array("nivel" => $nivel, "dato" => $fila[$campoDato], "id" => $fila[$campoId], "padreId" => $fila[$campoPadreId]);
			$retArrayFunc = $this->getArbol($tabla, $campoId, $campoPadreId, $campoDato, $orderBy, $fila[$campoId], $nivel +1);
			$arrayRuta = array_merge($arrayRuta, $retArrayFunc);
		}

		return $arrayRuta;
	}
	
	/**
	 * Retorna un array con la ruta tomada de un arbol jerarquico a partir del nodo indicado en $id. Ej: array("33"=>"Autos", "74"=>"Ford", "85"=>"Falcon")
	 * Esta funcion es para ser usada en tablas con este formato de campos: id, valor, idPadre
	 *
	 * @param string $tabla Nombre de la tabla
	 * @param string $campoId Nombre del campo que es id de la tabla
	 * @param string $campoPadreId Nombre del campo que es el FK sobre la misma tabla
	 * @param string $campoDato Nombre del campo que tiene el dato
	 * @param int El id del nodo del cual comienza a generar el path
	 * @return array Formato: array("33"=>"Autos", "74"=>"Ford", "85"=>"Falcon")
	 */
	public function getArbolRuta($tabla, $campoId, $campoPadreId, $campoDato, $id){
		$tabla = mysqli_real_escape_string($this->con,$tabla);
		$campoId = mysqli_real_escape_string($this->con,$campoId);
		$campoPadreId = mysqli_real_escape_string($this->con,$campoPadreId);
		$campoDato = mysqli_real_escape_string($this->con,$campoDato);
		$id = mysqli_real_escape_string($this->con,$id);
		
		if($id == 0) return;
		
		$arrayRuta = array();
		
		$result = $this->query("SELECT $campoId, $campoDato, $campoPadreId FROM $tabla WHERE $campoId='$id'");
		
		while ($this->num_rows($result) == 1 or $fila[$campoId] == '0') {
			$fila = $this->fetch_assoc($result);
			$arrayRuta[$fila[$campoId]] = $fila[$campoDato];
			$result = $this->query("SELECT $campoId, $campoDato, $campoPadreId FROM $tabla WHERE $campoId='".$fila[$campoPadreId]."'");
		}
		
		$arrayRuta = array_reverse($arrayRuta, true);
		
		return $arrayRuta;
	}
	
	/**
	 * Realiza un INSERT en una tabla usando los datos que vienen por POST, donde el nombre de cada campo es igual al nombre en la tabla.
	 * Esto es especialmente util para backends, donde con solo agregar un campo al <form> ya estamos agregandolo al query automaticamente
	 * 
	 * Ejemplos:
	 * 
	 * Para casos como backend donde no hay que preocuparse por que el usuario altere los campos del POST se puede omitir el parametro $campos
	 * $db->insertFromPost("usuarios");
	 * 
	 * Si ademas queremos agregar algo al insert
	 * $db->insertFromPost("usuarios", "", "fechaAlta=NOW()");
	 * 
	 * Este es el caso más seguro, se indican cuales son los campos que se tienen que insertar
	 * $db->insertFromPost("usuarios", array("nombre", "email"));
	 *
	 * @param string $tabla Nombre de la tabla en BD
	 * @param array $campos Campos que vienen por $_POST que queremos insertar, ej: array("nombre", "email")
	 * @param string $adicionales Si queremos agregar algo al insert, ej: fechaAlta=NOW()
	 * @return boolean El resultado de la funcion query
	 */
	public function insertFromPost($tabla, $campos=array(), $adicionales=""){
		
		//campos de $_POST
		foreach ($_POST as $campo => $valor) {
			if (is_array($campos) and count($campos) > 0) {
				//solo los campos indicados
				if (in_array($campo, $campos)) {
					if($camposInsert != "") $camposInsert .= ", ";
					$camposInsert .= "`$campo`='".mysqli_real_escape_string($this->con, $valor)."'";
				}
			}else{
				//van todos los campos que vengan en $_POST
				if($camposInsert != "") $camposInsert .= ", ";
				$camposInsert .= "`$campo`='".mysqli_real_escape_string($this->con, $valor)."'";
			}
		}
		
		//campos adicionales
		if ($adicionales != "") {
			if($camposInsert != "") $camposInsert .= ", ";
			$camposInsert .= $adicionales;
		}
		
		return $this->query("INSERT INTO $tabla SET $camposInsert");
	}
	
	
	/**
	 * Realiza un UPDATE en una tabla usando los datos que vienen por POST, donde el nombre de cada campo es igual al nombre en la tabla.
	 * Esto es especialmente util para backends, donde con solo agregar un campo al <form> ya estamos agregandolo al query automaticamente
	 * 
	 * Ejemplos:
	 * 
	 * Para casos como backend donde no hay que preocuparse por que el usuario altere los campos del POST se puede omitir el parametro $campos
	 * $db->updateFromPost("usuarios");
	 * 
	 * Si ademas queremos agregar algo al update
	 * $db->updateFromPost("usuarios", "", "fechaModificacion=NOW()");
	 * 
	 * Este es el caso más seguro, se indican cuales son los campos que se tienen que insertar
	 * $db->updateFromPost("usuarios", array("nombre", "email"));
	 *
	 * @param string $tabla Nombre de la tabla en BD
	 * @param string $where Condiciones para el WHERE. Ej: id=2. También puede agregarse un LIMIT para los casos donde solo se necesita actualizar un solo registro. Ej: id=3 LIMIT 1. El limit en este caso es por seguridad
	 * @param array $campos Campos que vienen por $_POST que queremos insertar, ej: array("nombre", "email")
	 * @param string $adicionales Si queremos agregar algo al insert, ej: fechaAlta=NOW()
	 * @return boolean El resultado de la funcion query
	 */
	public function updateFromPost($tabla, $where, $campos=array(), $adicionales=""){
		
		//campos de $_POST
		foreach ($_POST as $campo => $valor) {
			if (is_array($campos) and count($campos) > 0) {
				//solo los campos indicados
				if (in_array($campo, $campos)) {
					if($camposInsert != "") $camposInsert .= ", ";
					$camposInsert .= "`$campo`='".mysqli_real_escape_string($this->con, $valor)."'";
				}
			}else{
				//van todos los campos que vengan en $_POST
				if($camposInsert != "") $camposInsert .= ", ";
				$camposInsert .= "`$campo`='".mysqli_real_escape_string($this->con, $valor)."'";
			}
		}
		
		//campos adicionales
		if ($adicionales != "") {
			if($camposInsert != "") $camposInsert .= ", ";
			$camposInsert .= $adicionales;
		}
		
		return $this->query("UPDATE $tabla SET $camposInsert WHERE $where");
	}
}



function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
}
?>