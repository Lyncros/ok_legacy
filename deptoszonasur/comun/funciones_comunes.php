<?php
//*****************************************************************************************************************//
//*****************************************************************************************************************//
//                                  Funciones comunes, la mayoría no especificas del sitio
//*****************************************************************************************************************//
//*****************************************************************************************************************//


//*****************************************************************************************************************//
// FUNCIONES DE TEXTO
//*****************************************************************************************************************//

function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function getNombreMes($numMes){
	switch ($numMes) {
		case 1:
			return "Enero";
			break;
		case 2:
			return "Febrero";
			break;
		case 3:
			return "Marzo";
			break;
		case 4:
			return "Abril";
			break;
		case 5:
			return "Mayo";
			break;
		case 6:
			return "Junio";
			break;
		case 7:
			return "Julio";
			break;
		case 8:
			return "Agosto";
			break;
		case 9:
			return "Septiembre";
			break;
		case 10:
			return "Octubre";
			break;
		case 11:
			return "Noviembre";
			break;
		case 12:
			return "Diciembre";
			break;
	
		default:
			break;
	}
}

/**
 * Obtiene el ID de video de una url de Youtube
 */
function getYoutubeVideoId($youtubeVideoLink){
	if(stripos($youtubeVideoLink, "youtube") > 0){
		$url = parse_url($youtubeVideoLink);
		$queryUrl = $url['query'];
		$queryUrl = parse_str($queryUrl);
		return $v;
	}elseif(stripos($youtubeVideoLink, "youtu.be") > 0){
		preg_match('/youtu.be\/([a-zA-Z0-9]*)/i', $youtubeVideoLink, $matches);
		return $matches[1];
	}
}

function encryptData($value, $key){ 
   $text = $value; 
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
    return $crypttext; 
} 

function decryptData($value, $key){ 
   $crypttext = $value; 
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
    return trim($decrypttext); 
} 

/**
 * Agrega el prefijo http:// a un string url si es que no lo tiene
 */
function agregarHTTP($string){
	if(!preg_match('/^(https?:\/\/)/i', $string)){
		$string = 'http://'.$string;
	}
	return $string;
}

/**
 * Remplaza links y emails agregandole los links
 */
function agregarLinksEmail($str, $target = "_blank"){
	$str = preg_replace('/([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})/i','<a href="mailto:\\1">\\1</a>',$str);
	return $str;
}

/**
 * Formatear un texto para remplazar URLs por links, también para remplazar URLs largas por 
 * sus versiónes resumidas solo para fines estéticos Ej: http://www.somesite.com/with/a/really/long/url/link   por   http://www.some...url/link
 * 
 * @param string $str texto donde hay que remplazar los links
 * @param int $len largo máximo
 * @param string $mid Caracteres ... de "continuacion"
 * @return string
 */
function formatearLinks($str, $target='_blank', $maxLen=50, $mid='...'){
	$left = ceil(0.6666 * $maxLen);
	$right = $maxLen - $left;
	preg_match_all('/(?<!=|\]|\/)((https?|ftps?|irc):\/\/|' . '(www([0-9]{1,3})?|ftp)\.)([0-9a-z-]{1,25}' . '[0-9a-z]{1}\.)([^\s&\[\{\}\]]+)/ims', $str, $matches);
	foreach($matches[0] as $key=>$value){
		$temp = $value;
		if(strlen($value) > ($maxLen + strlen($mid) + 2)){
			$value = substr($value, 0, $left) . $mid . substr($value,(-1 * $right));
		}
		$temp = !preg_match('/:\/\//', $temp) ? (substr($temp, 0, 3) === 'ftp' ? 'ftp://' . $temp : 'http://' . $temp) : $temp;
		$temp = $temp === $matches[0][$key] && $value === $matches[0][$key] ? '' : '=' . $temp;
		$str = str_replace($matches[0][$key],'[url' . $temp . ']' . $value . '[/url]', $str);
	}
	$str = preg_replace('/\[url=(?!http|ftp|irc)/ims', '[url=http://', $str);
	$str = preg_replace('/\[url\](.+?)\[\/url\]/ims','<a href="$1" target="'.$target.'" title="$1">$1</a>',$str);
	$str = preg_replace('/\[url=(.+?)\](.+?)\[\/url\]/ims', '<a href="$1" target="'.$target.'" title="$1">$2</a>', $str);
	return $str;
}

/**
formatMoney(1050); # 1,050 
formatMoney(1321435.4, true); # 1,321,435.40 
formatMoney(10059240.42941, true); # 10,059,240.43 
formatMoney(13245); # 13,245 
*/
function formatMoney($number, $fractional=false) { 
    if ($fractional) { 
        $number = sprintf('%.2f', $number); 
    } 
    while (true) { 
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
        if ($replaced != $number) { 
            $number = $replaced; 
        } else { 
            break; 
        } 
    } 
    return $number; 
} 

/**
 * Remplaza links y emails agregandole los links
 */
function remplazarEmailyWWW($str, $target = "_blank", $maxLen=50, $mid='...'){
	$str = agregarLinksEmail($str, $target);
	$str = formatearLinks($str, $target, $maxLen, $mid);
	return $str;
}

/**
 *  Corta un string del largo de caracteres especificado sin cortar palabras a la mitad.
 *
 * @param $str
 * @param Integer $len
 * @param String $txt_continua Texto que se agrega al final del string si es cortado (Ej: Mi perro se llam(continua...) )
 * @return String
 */
function cortar_str($str,$len,$txt_continua="..."){
	if(strlen($str)>$len){
		// Cortamos la cadena por los espacios
		$arrayTexto = split(' ',$str);
		
		$texto = '';
		$contador = 0;
		 
		// Reconstruimos la cadena
		while($len >= strlen($texto) + strlen($arrayTexto[$contador])){
		    $texto .= ' '.$arrayTexto[$contador];
		    $contador++;
		}
		
		return $texto.$txt_continua;
	}else{
		return $str;
	}
}

/**
 * Saca los saltos de linea ASCII de un string
 */
function strip_saltos($str){
	$str = ereg_replace( chr(13) , "" , $str );
	$str = ereg_replace( chr(10) , "" , $str );
	return $str;
}

/**
 * Formatea un string para crear una cadena para usar como url amigable para los buscadores
 *
 * @param string $str
 * @return string
 * @author Andres Carizza
 * @version 1.2
 */
function url_amigable($str){
	global $sitio; //de mi framework
	
	$url = mb_strtolower($str, $sitio->charset);

	$url = remplazar_caracteres_latinos ($url); 

	// Añadimos los guiones 
	$find = array(' ', '&', '\r\n', '\n', '+'); 
	$url = str_replace ($find, '-', $url); 

	// Eliminamos y Reemplazamos demás caracteres especiales 
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/'); 
	$repl = array('', '-', ''); 
	$url = preg_replace ($find, $repl, $url); 

	return $url; 
}

/**
 * Remplaza caracteres latinos. Ej: á -> a, ñ -> n
 * @param string $str
 * @return string
 * @author Andres Carizza
 * @version 1.2
 * */
function remplazar_caracteres_latinos($str){
	$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç', 'ã', 'ê', 'à'   ,   'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ü', 'Ç', 'Ã', 'Ê', 'À'); 
	$repl = array('a', 'e', 'i', 'o', 'u', 'n', 'u', 'c', 'a', 'e', 'a'   ,   'A', 'E', 'I', 'O', 'U', 'N', 'U', 'C', 'A', 'E', 'A'); 
	return str_replace ($find, $repl, $str);
}

/**
 * Formatea un string para que corresponda con un nombre válido de archivo
 *
 * @param string $str
 * @return string
 * @author Andres Carizza
 * @version 1.1
 */
function format_valid_filename($str, $remplazarCaracteresLatinos=true, $conservarEspacios=false){
	// Eliminamos y Reemplazamos caracteres especiales 
	
	$str = str_replace('\\', '', $str);
	$str = str_replace('/', '', $str);
	$str = str_replace('*', '', $str);
	$str = str_replace(':', '', $str);
	$str = str_replace('?', '', $str);
	$str = str_replace('"', '', $str);
	$str = str_replace('<', '', $str);
	$str = str_replace('>', '', $str);
	$str = str_replace('|', '', $str);
	
	if($remplazarCaracteresLatinos) $str = remplazar_caracteres_latinos($str);
	if($conservarEspacios) $str = str_replace(" ", "-", $str);

	return $str; 
}

/**
 * Convierte de un array todas las entidades HTML para que sea seguro mostrar en pantalla strings ingresados por los usuarios
 * Ejemplo: $_REQUEST = limpiarEntidadesHTML($_REQUEST);
 *
 * @param Array o String $param Un array o un String
 * @return Depende del parametro recibido, un array con los datos remplazados o un String
 */
function limpiarEntidadesHTML($param) {
	global $sitio; //de mi framework 
    return is_array($param) ? array_map('limpiarEntidadesHTML', $param) : htmlentities($param, ENT_QUOTES, $sitio->charset);
}

/**
 * Escapa de un array todos los caracteres especiales de una cadena para su uso en una sentencia SQL
 * Ejemplo: $_REQUEST = limpiarParaSql($_REQUEST);
 *
 * @param Array o String $param Un array o un String
 * @return Depende del parametro recibido, un array con los datos remplazados o un String
 */
function limpiarParaSql($param){
	global $db;
	return is_array($param) ? array_map('limpiarParaSql', $param) : mysqli_real_escape_string($db->con, $param);
}

/**
 * Limpia un string para se usado en una busqueda 
 *
 * @param string $valor
 * @return string
 */
function limpiarParaBusquedaSql($valor){
	$valor = str_ireplace("%","",$valor);
	$valor = str_ireplace("--","",$valor);
	$valor = str_ireplace("^","",$valor);
	$valor = str_ireplace("[","",$valor);
	$valor = str_ireplace("]","",$valor);
	$valor = str_ireplace("\\","",$valor);
	$valor = str_ireplace("!","",$valor);
	$valor = str_ireplace("¡","",$valor);
	$valor = str_ireplace("?","",$valor);
	$valor = str_ireplace("=","",$valor);
	$valor = str_ireplace("&","",$valor);
	$valor = str_ireplace("'","",$valor);
	$valor = str_ireplace('"',"",$valor);
	return $valor;
}

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('remove_invisible_characters'))
{
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

/**
 * Lo mismo que utf8_encode() pero aplicado a todo el array
 *
 * @param array $array
 * @return array
 */
function utf8_encode_array($array){
	return is_array($array) ? array_map('utf8_encode_array', $array) : utf8_encode($array);
}

/**
 * Lo mismo que utf8_decode() pero aplicado a todo el array
 *
 * @param array $array
 * @return array
 */
function utf8_decode_array($array){
	return is_array($array) ? array_map('utf8_decode_array', $array) : utf8_decode($array);
}

//*****************************************************************************************************************//
// FUNCIONES DE FECHA
//*****************************************************************************************************************//

/**
* Formatea la fecha que usa el MySQL (YYYY-MM-DD) o (YYYY-MM-DD HH:MM:SS) a un formato de fecha más claro
* En caso de que falle el formateo retorna FALSE
* 
* @param String $mysqldate La fecha en formato YYYY-MM-DD o YYYY-MM-DD HH:MM:SS
* @param Boolean $conHora True si se quiere dejar la hora o false si se quiere quitar
* @return String La fecha formateada
* @version 1.1
**/
function mysql2date($mysqldate, $conHora=false){
	$fecha_orig = $mysqldate;

	if(strlen($fecha_orig) > 10){ //si es formato YYYY-MM-DD HH:MM:SS
		$hora = substr($mysqldate,11,strlen($mysqldate));
		$mysqldate = substr($mysqldate,0,10);
	}

	$datearray = explode("-", $mysqldate);

	if(count($datearray) != 3) return ""; //en caso de que no sean tres bloques de numeros falla

	$yyyy = $datearray[0];

	$mm = $datearray[1];

	$dd = $datearray[2];

	if(strlen($fecha_orig) > 10 and $conHora){ //si es formato YYYY-MM-DD HH:MM:SS
		return "$dd/$mm/$yyyy $hora";
	}else{
		return "$dd/$mm/$yyyy";
	}
}

/**
* Convierte el formato de fecha (DD/MM/YYYY) al que usa el MySQL (YYYY-MM-DD)
* Se pueden enviar dias y meses con un digito (ej: 3/2/1851) o así (ej: 03/02/1851)
* La fecha tiene que enviarse en el orden dia/mes/año 
* En caso de que falle el formateo retorna FALSE
* 
* @param String $date La fecha en formato DD/MM/YYYY o  D/M/YYYY
* @return String La fecha formateada o FALSE si el formato es invalido
* @version 1.3
**/
function date2mysql($date){
	if(!ereg('^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$', $date)) return false;
	
	$datearray = explode("/", $date);

	$dd = $datearray[0];
	if($dd > 0 and $dd <= 31){ $dd = sprintf("%02d",$dd); }else{ return false; } //un minimo chequeo del dia

	$mm = $datearray[1];
	if($mm > 0 and $mm <= 12){ $mm = sprintf("%02d",$mm); }else{ return false; } //un minimo chequeo del mes

	$yyyy = $datearray[2];
	if($yyyy > 0 and $yyyy <= 9999){ $yyyy = sprintf("%04d",$yyyy); }else{ return false; } //un minimo chequeo del año

	return "$yyyy-$mm-$dd";
}

/**
 * Retorna la representacion de una fecha (por ejemplo: Hace 3 días. o Ayer)
 * Para usar entre 0 minutos de diferencia hasta semanas
 *
 * @param Integer $ts Timestamp
 * @param String $formatoFecha El formato de fecha a mostrar para cuando es mayor a 31 días
 * @return String
 * @version 1.2
 */
function mysql2preety($ts, $formatoFecha="d/m/Y"){
	if(!ctype_digit($ts))
		$ts = strtotime($ts);

	$diff = time() - $ts;
	$day_diff = floor($diff / 86400);

	if($day_diff < 0) return date($formatoFecha, $ts); //fecha futura! no deberia pasar..

	if($day_diff == 0)
	{
		if($diff < 60) return "Recién";
		if($diff < 120) return "Hace un minuto";
		if($diff < 3600) return "Hace " . floor($diff / 60) . " minutos";
		if($diff < 7200) return "Hace una hora";
		if($diff < 86400) return "Hace " . floor($diff / 3600) . " horas";
	}
	
	if($day_diff == 1) return "Ayer";
	
	if($day_diff < 7) return "Hace " . $day_diff . " días";
	
	if($day_diff < 31) return "Hace " . ceil($day_diff / 7) . " semanas";

	return date($formatoFecha, $ts);
}


//*****************************************************************************************************************//
// FUNCIONES DE EMAIL
//*****************************************************************************************************************//

/**
 * Funcion mail extendida
 *
 * @param String $para Email del destinatario o en formato: "Juan Perez" <juan@hotmail.com>
 * @param String $asunto
 * @param String $mensaje
 * @param String $deEmail Email del remitente
 * @param String $deNombre Nombre del remitente
 * @param Boolean $html True si es en formato HTML (por defecto) o FALSE si es texto plano
 * @param String $prioridad Alta o Baja (por defecto es Normal)
 * @param String $xmailer X-Mailer ej: Mi Sistema 1.0.2
 * @param String $notificacion_lectura_a Email donde se envia la notificacion de lectura del mensaje en formato: "Juan Perez" <juan@hotmail.com>
 * @return Boolean
 * @version 1.1
 */
function enviar_mail($para, $asunto, $mensaje, $deEmail, $deNombre, $html=true, $prioridad="Normal", $xmailer="", $notificacion_lectura_a=""){
	$headers = "MIME-Version: 1.0 \n" ;
	if ($html) {
		$headers .= "Content-Type:text/html;charset=ISO-8859-1 \n";
	}else{
		$headers .= "Content-Type:text/plain;charset=ISO-8859-1 \n";
	}
	$headers .= "From: \"$deNombre\" <$deEmail> \n";
	
	if (strtolower($prioridad) == "alta") {
		$headers .= "X-Priority: 1 \n";
	}elseif (strtolower($prioridad) == "baja") {
		$headers .= "X-Priority: 5 \n";
	}
	
	if($xmailer != "") $headers .= "X-Mailer: $xmailer \n";
	
	if($notificacion_lectura_a != "") $headers .= "Disposition-Notification-To: $notificacion_lectura_a \n";

	if(@mail($para, $asunto, $mensaje, $headers)){
		return true;
	}else{
		return false;
	}	
}

/**
 * Funcion mail extendida con phpmailer
 *
 * @param array $para Destinatario/s  array(array("email@serv.com","Juan")) o array(array("email@serv.com","Juan") , array("otro@serv.com","Pablo"))
 * @param String $asunto
 * @param String $mensaje
 * @param String $deEmail Email del remitente
 * @param String $deNombre Nombre del remitente
 * @param Boolean $html True si es en formato HTML (por defecto) o FALSE si es texto plano
 * @param array $adjuntos Archivos adjuntos en el email. array(array($_FILES['archivo']['tmp_name'] , $_FILES['archivo']['name']))
 * @param string $charSet
 * @param string $mailer "mail" o "sendmail" o "smtp"
 * @return nada
 * @version 1.0
 */
function mail_ext($para, $asunto, $mensaje, $deEmail, $deNombre, $html=true, $adjuntos="", $charSet="iso-8859-1", $mailer="mail", $sendmail="/usr/sbin/sendmail", $smtpHost="localhost", $smtpPort=25, $smtpHelo="localhost.localdomain", $smtpTimeOut=10){
	$mail = new PHPMailer();
	$mail->IsHTML($html);
	$mail->Host = "localhost";
	$mail->From = $deEmail;
	$mail->FromName = $deNombre;
	$mail->Subject = $asunto;
	$mail->Body = $mensaje;
	$mail->CharSet = $charSet;
	
	foreach ($para as $item) {
		if(!is_array($item) or count($item) != 2) 
			throw new Exception('Parametro $para no es un array con el formato correcto en enviar_mail()');
		
		$mail->AddAddress($item[0], $item[1]);
	}

	if (is_array($adjuntos)) {
		foreach ($adjuntos as $adjunto) {
			$mail->AddAttachment($adjunto[0], $adjunto[1]);
		}
	}
	
	if ($mailer == "sendmail") {
		$mail->sendmail = $sendmail;
		
	}elseif ($mailer == "smtp") {
		$mail->Host = $smtpHost;
		$mail->Port = $smtpPort;
		$mail->Helo = $smtpHelo;
		$mail->Timeout = $smtpTimeOut;
	}
	
	$mail->Send();
}

//*****************************************************************************************************************//
// FUNCIONES VARIAS
//*****************************************************************************************************************//

/**
 * Imprime el META de HTML y hace Exit para redireccionar al usuario a $url
 * Esta función es util para cuando no se pueden mandar headers por haber impreso antes
 *
 * @param String $url
 * @param Integer $segundos Tiempo en segundos antes de hacer la redireccion
 * @param String $mensaje Un mensaje opcional a imprimir en pantalla
 * @version 1.0
 */
function redirect_http($url, $segundos=0, $mensaje=""){
	echo "<HTML><HEAD>";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$segundos; URL=$url\">";
	if ($mensaje!="") echo $mensaje;
	echo "</HEAD></HTML>";
	exit;
}

/**
 * Imprime el header Location y hace Exit para redireccionar al usuario a $url
 * Nota: en el caso de que la variable $debug esta seteada a TRUE en vez de mandar 
 * el header llama a la funcion redirect_http() porque al estar debugueando el header no 
 * se podria mandar por haber mandado contenido antes.
 *
 * @param String $url
 * @version 1.0
 */
function redirect($url){
	global $debug, $start_time;
	
	if ($debug) {
		redirect_http($url, 120, "<i>Transcurrieron ".(microtime()-$start_time)." segundos</i><br><a href='$url'>Haga click para continuar a: $url</a>");
	}else{
		header("Location:$url");
		exit();
	}
}

/**
 * Borra un directorio con todos sus archivos y sub directorios
 *
 * @param string $dir
 */
function delTree($dir) { 
  if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
		 if ($object != "." && $object != "..") { 
		   if (filetype($dir."/".$object) == "dir") delTree($dir."/".$object); else unlink($dir."/".$object); 
		 } 
		} 
		reset($objects); 
		rmdir($dir); 
	}  
}

/**
 * Retorna true si el valor es un numero. 
 * A diferencia de las funciones de PHP esta solo va a retornar TRUE si en el valor hay unicamente numeros.
 *
 * @param $valor
 * @return boolean
 */
function es_numerico($valor){
	if ($valor != "" and ereg( "^[0-9]+$", $valor ) ) {
	    return true;
	} else {
	    return false;
	}
}

/**
* Crea los tags <OPTION> numericos para un <SELECT>
* parametros: 
* $desde - desde que numero
* $hasta - hasta que numero
* $incremento - de a cuanto incrementa
* $selected - cual tiene que estar seleccionado (ninguno = "")
**/
function crear_opciones_select($desde,$hasta,$incremento=1,$selected=""){
	for($i=$desde; $i<=$hasta; $i=$i+$incremento){
		if($i==$selected){
			echo "<option value=$i selected='selected'>$i</option>\n";
		}else{
			echo "<option value=$i>$i</option>\n";
		}
	}
}


/**
 * Obtiene el hostname de una url. ej: http://www.google.com/adsense?u=232 retorna: google.com
 *
 * @param string $url
 * @param bool $stripWww
 * @return string
 */
function extractHostPart($url, $stripWww=true){
	$partes = parse_url($url);
  
  if ($partes === false) {
  	return false;
  }else{
  	$hostName = $partes['host'];
  	if($stripWww) $hostName = preg_replace("/www./i", "", $partes['host']);
  	return $hostName;
  }
}

/**
 * Obtiene el hostname de un email. ej: ejemplo@gmail.com retorna: gmail.com
 *
 * @param string $email
 * @return string o FALSE si no es un email valido
 */
function getHostNameEmail($email){
	if(eregi("^([_a-z0-9+-]+)(\.[_a-z0-9+-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)){
		preg_match('/^([_a-z0-9+-]+)(\.[_a-z0-9+-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $email, $matches);
		return $matches[3].$matches[5];
	}else{
		return false;
	}
}

/**
 * Genera un dump de una base de datos a un archivo en el servidor o para bajar directamente sin usar comandos externos
 *
 * @param string $host DB host
 * @param string $user DB user
 * @param string $pass DB pass
 * @param string $dbname DB name
 * @param string $tables Puede ser * para que sean todas las tablas, o las tablas separadas por comas o un array
 * @param string $fileName Nombre del archivo de destino, si $download = true es el nombre del archivo que baja, sino es el archivo que genera en el servidor
 * @param boolean $download True para que baje el archivo .sql generado
 */
function backup_db($host, $user, $pass, $dbname, $tables = '*', $fileName, $download=true){
	
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($dbname,$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	
	if ($download) {
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=".$fileName);		
		header("Content-Length: ".strlen($return)); //it is needed for the progress bar of the browser
		echo $return;
		
	}else{
		
		$handle = fopen($fileName, 'w+');
		fwrite($handle, $return);
		fclose($handle);
	}
	
	$return = "";
	mysql_close($link);
}


/**
 * Retorna true si el dominio del $email pertenece a un dominio de emails temporales anti spam
 *
 * @param string $email
 * @return boolean
 */
function dominioEmailBaneado($email){
	
	//Dominios de email baneados
	$hostNoValidosParaEmail = array("mailinator.com","binkmail.com","suremail.info","bobmail.info","anonymbox.com","deadaddress.com","spamcero.com","zippymail.info","sogetthis.com","safetymail.info","thisisnotmyrealemail.com","tradermail.info","nepwk.com","sharklasers.com","tempemail.net","temporaryemail.net","trashymail.com","maileater.com","spambox.us","spamhole.com","pookmail.com","mailslite.com","20minutemail.com","nwldx.com","makemetheking.com");
	
	if(in_array(getHostNameEmail(strtolower($email)), $hostNoValidosParaEmail)){
		return true;
	}else{
		return false;
	}
}

function is_ajax_request(){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}else{
		return false;
	}
}

/**
 * Para el admin, imprime el link para abrir el ligthbox para subir y recortar la foto de $idRegistro, $tabla que se llame $tipoFoto
 * Opcionalmente puede imprimir la imagen al lado del link
 * @param boolean $incluirImagen Imprime la imagen ademas del link
 * @param string $textoLink El texto del link para editar la imagen
 * @idRegistro int El id del registro al que pertenece la imagen (ver class_archivos)
 * @tabla string El nombre de la tabla al que pertenece el registro (ver class_archivos)
 * @tipoFoto string El identificador de la foto, que se usa para guardar y recuperar las diferentes imagenes de un registro (ver class_archivos)
 * */
function linkEditarFoto($incluirImagen, $textoLink, $tituloLigthbox="", $idRegistro, $tabla, $tipoFoto, $wMax, $hMax, $wMin, $hMin, $aspectRatio=0){
	global $cl_archivos, $db, $sitio;
	
	$rnd = rand(1,9999999);
	
	if($incluirImagen){
		$foto = $cl_archivos->getArchivoPrincipal($tabla, $idRegistro, $tipoFoto);
		
		//para el ancho o alto del <img> de vista previa
		if(is_array($foto)){
			$imgSize = @getimagesize(dirname(dirname(__FILE__))."/archivos/".$foto[archivo]);
			if(is_array($imgSize)){
				if($imgSize[1] > $imgSize[0]){
					$widthHeight = "height";
				}else{
					$widthHeight = "width";
				} 
			}
		}
		
		echo "<img $widthHeight='150' id='img$rnd' src='".(is_array($foto) ? $sitio->pathBase."archivos/".$foto[archivo] : "")."' style='".(is_array($foto) ? "" : "display:none")."' /><br/>";
	}
	?>
	<script>
	$(document).ready(function(){
		$(".lnk<?=$rnd?>").colorbox({iframe:true, rel:'<?=$rnd?>', width:"90%", height:"90%",
		onClosed: function(){
			<?if($incluirImagen){?>
				//actualiza la imagen por ajax
				$.ajax({
				   url: "ajax.php?getImgSrc=1",
				   data: "idRegistro=<?=$idRegistro?>&tabla=<?=$tabla?>&tipoFoto=<?=$tipoFoto?>",
				   success: function(r){
				   	 if(r != ''){
				     	$('#img<?=$rnd?>').css('display', 'inline');
				     	$('#img<?=$rnd?>').attr('src', '../archivos/'+r+'?'+Math.random());
			     	 }else{
			     		$('#img<?=$rnd?>').css('display', 'none');
			     	 }
				   }
				 });
			<?}?>
		}
		});	
	});
	</script>
	<a class='lnk<?=$rnd?>' href="editar_foto.php?idRegistro=<?=$idRegistro?>&tabla=<?=$tabla?>&tipoFoto=<?=$tipoFoto?>&wMax=<?=$wMax?>&hMax=<?=$hMax?>&wMin=<?=$wMin?>&hMin=<?=$hMin?>&aspectRatio=<?=$aspectRatio?>" rel="colorbox" title="<?=$tituloLigthbox?>"><?=$textoLink?></a>
	<?
}
?>