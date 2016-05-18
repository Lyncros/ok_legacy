<?php
/**
 * Lee un archivo de configuracion definido en un archivo XML.
 * Los parametros estan definidos por los nombres de los TAGS 
 * y sus valores asociados como el contenido del TAG en cuestion.
 * Permite que los valores sean agrupados en estructuras de Arbol teniendo como 
 * unica condicion que los parametros son aquellos terminales de las ramas.
 * Ante una duplicidad en la identificacion de un parametro, arroja un error
 * y finaliza la aplicacion.
 * 
 * Ej:
 * <?xml version="1.0" encoding="iso-8859-1"?>
 *  <globales>
 *	<entorno>
 *		<path>c:\prueba</path>
 *		<extension>exe</extension>
 * 		<key>prueba</key>
 *	</entorno>
 *  <db>
 * 		<user></user>
 * 		<dbpass></dbpass>
 *  </db>
 *	<propio>
 *		<dato1>este es el dato 1</dato1>
 *		<dato2>este es el dato 2</dato2>
 *		<dato3>este es el dato 3</dato3>
 *	</propio>
 * </globales>
 *
 *  Soporta como parametro de invocaci�n la identificacion del nombre del archivo de 
 * configuracion del cual tomar los valores ( incluyendo en el mismo el path ).
 * En caso de no proporcionarse el mismo, tomo por default el archivo definido por las
 * variables CONFPATH y CONFARCH relativos al valor del DOCUMENT_ROOT del sitio.
 * 
 * Agrega como medida de seguridad la Clase Encriptador, que se utiliza para leer parametros 
 * que se deseen mantener encriptados por medio de una LLAVE.
 * Para ello se debe incluir como tag en la configuraci�n un campo 'key' que contendra la llave de encripcion
 * y todos los campos encriptados que se desee.
 * Para identificar que campos estan encriptados hay que incluir en el nombre de 
 * los mismos el substring 'pass' y someterlos al proceso de encripci�n con la llave deseada
 * Ej.:
 *				$crip=new Encriptador();
 * 				$_llave="llave";	
 * 				$cod=$crip->encripta($_llave,"clave");
 *				echo $cod."<br>";
 * El codigo retornado por dicho proceso sera el que se incluira como parametro en el 
 * archivo de configuracion.
 */

class CargaConfiguracion {
	
	private $indice;
	private $valores;
	private static $_instance;
	
	static private $CONFPATH='/config/';
	static private $CONFARCH='conf.xml';

	public function __construct($_conf_file=''){

		if(is_null($_conf_file) || $_conf_file == ''){
			$_conf_file=$_SERVER['DOCUMENT_ROOT'].self::$CONFPATH.self::$CONFARCH;
		}
		if(file_exists($_conf_file)){
			$this->leeConfiguracion($_conf_file);
		} else {
			die("No se encuentra archivo de configuracion.".$_conf_file);
		}
	}

	
	static public function getInstance($_conf_file=""){
		if(is_null(self::$_instance)){
			self::$_instance = new self($_conf_file);
		}
		return self::$_instance;
	}

	
	private function leeConfiguracion($_archivo){
/*		if($da = fopen($_archivo,"r")){
			while ($aux= fgets($da,1024))	{
				$contenido.=$aux;
			}
			fclose($da);
		} else {
			die("Error: no se ha podido leer el archivo <strong>$_archivo</strong>");
		}
*/

		$contenido="";
		$contenido = $this->leeXML($_archivo);
		
		$contenido=ereg_replace("�","a",$contenido);
		$contenido=ereg_replace("�","e",$contenido);
		$contenido=ereg_replace("�","i",$contenido);
		$contenido=ereg_replace("�","o",$contenido);
		$contenido=ereg_replace("�","u",$contenido);
		$contenido=ereg_replace("�","A",$contenido);
		$contenido=ereg_replace("�","E",$contenido);
		$contenido=ereg_replace("�","I",$contenido);
		$contenido=ereg_replace("�","O",$contenido);
		$contenido=ereg_replace("�","U",$contenido);
		$contenido=ereg_replace("�","NI",$contenido);
		$contenido=ereg_replace("�","ni",$contenido);

		$p = xml_parser_create();
		xml_parse_into_struct($p,$contenido,$this->valores,$this->indice);
		xml_parser_free($p);
	}

/**
 * Graba el archivo de configuracion con el XML definido
 *
 * @param string $_archivo -> nombre del archivo de configuracion
 * @param strign $_contenido -> XML conformado con los valores de la configuracion
 * @return FALSO ente un error o la cantidad de bytes grabados
 */
	private function grabaConfiguracion($_archivo,$_contenido){
		if($da = fopen($_archivo,"w")){
			$retorno=fwrite($da,$_contenido);
			fclose($da);
		}
		return $retorno;
	}
	
/**
 * Lee el XML definido y lo carga en un string para ser parseado
 *
 * @param string $_arhivo -> nombre del archivo de configuracion
 * @return string con el XML leido
 */
	private function leeXML($_archivo){
		$contenido="";
		if($da = fopen($_archivo,"r")){
			while ($aux= fgets($da,1024))	{
				$contenido.=$aux;
			}
			fclose($da);
		} else {
			die("Error: no se ha podido leer el archivo <strong>$_archivo</strong>");
		}
		return $contenido;
	}

	
	public function muestraXML($_archivo){
		$contenido="";
		$contenido=$this->leeXML($_archivo);
		echo $contenido;
	}
	
/**
 * Retorna el valor de un parametro del Archivo de Configuracion.
 * En caso que el mismo no exista o se encuentre duplicado,
 *  arroja un mensaje de error y finaliza la aplicacion.
 *
 * @param (String) nombre del parametro
 * @return (String) valor del parametro
 */
	public function leeParametro($_param){
		$retorno=null;
		$clave=strtoupper($_param);
		if(array_key_exists($clave,$this->indice)){
			$cant=sizeof($this->indice[$clave]);
			if($cant==1){
//si el campo incluye el substring 'pass' desencripta el parametro
				if(stristr($clave, 'pass') === FALSE){
					$retorno=$this->valores[$this->indice[$clave][0]]['value'];
				} else {
                    if(array_key_exists('value', $this->valores[$this->indice[$clave][0]])){ 
					$crip=new Encriptador();
					$retorno=$crip->desencripta($this->leeParametro('key'),$this->valores[$this->indice[$clave][0]]['value']);
                    }else{
                        $retorno='';
				}
				}
			} else {
				die("No es un parametro de Configuracion, o es una clave Duplicada");
			}
		}
		return $retorno;
	}
}


class Encriptador {
	
	public function encripta($_llave,$_clave){
		$llave=crypt($_llave,$_llave);
		$llave.=$llave;
		$codigo="";
		for( $x=0; $x<strlen($_clave);$x++){
//			$suma=hexdec(ord(substr($llave,$x*2,1)))+hexdec(ord(substr($_clave,$x,1)));
			$suma=ord(substr($llave,$x*2,1))+ord(substr($_clave,$x,1));
			if($suma>255){
				$possig=dechex($suma-255);
				$pospri=dechex(510-$suma);
			} else {
				$pospri=dechex($suma);
				$possig=dechex(ord(substr($llave,($x*2)+1,1)));
			}
			$codigo.=$pospri.$possig;
		}
//		$codigo.=substr($llave,strlen($_clave)*2);
		return $codigo;
	}
	
	public function desencripta($_llave,$_codigo){
		$llave=crypt($_llave,$_llave);
		$llave.=$llave;
		$codigo=$_codigo;
		$clave="";
		for ($x=0;$x<strlen($codigo);$x+=4){
			$pospri=substr($codigo,$x,2);
			$possig=substr($codigo,$x+2,2);
			$pclave=dechex(ord(substr($llave,($x/2),1)));
			if($possig==dechex(ord(substr($llave,($x/2)+1,1)))){
				$dato=chr(hexdec($pospri) - hexdec($pclave));
			} else {
				$dato=chr(510 - hexdec($pospri) - hexdec($pclave));
			}
			$clave.=$dato;
		}
		return $clave;
	}
	
}


$conf=CargaConfiguracion::getInstance();
/*
$valor=$conf->leeParametro("DATO2");
echo $valor."\n";
$valor=$conf->leeParametro("dbpass");
echo $valor."\n";
*/

/*
$crip=new Encriptador();
$_llave=$conf->leeParametro("key");//"RrtzCzcCmXBZSCZz";	
$cod=$crip->encripta($_llave,"oke10abm");
echo $cod."<br>";
*/

/*
echo "DESENC ---------<br>";
$crip=new Encriptador();
$cod=$conf->leeParametro("dbpass");
echo $cod."<br>";
$_llave=$conf->leeParametro("key");//"RrtzCzcCmXBZSCZz";	
//echo $crip->desencripta($_llave,$cod);
*/


?>
