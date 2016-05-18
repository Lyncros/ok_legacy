<?php
/**
 * Clase utilizada para definir operaciones entre fechas.
 * Toma como parametros seteables los valores del:
 * 					tipo de separador 			-> por default tiene /
 * 					tipo de calendario 			-> por default tiene CAL_GREGORIAN
 * 					formato de visualizacion	-> por default tiene dmy, acepta aparte mdy - ymd
 *
 */
class Fechas {
	
	private $separador="/";
	private $calendario=CAL_GREGORIAN;
	private $formato="dmy";
	
	public function __construct($separador="",$calendario="",$formato=""){
		if ($separador!=""){
			Fechas::setSeparador($separador);
		}
		if ($calendario!=""){
			Fechas::setCalendario($calendario);
		}
		if ($formato!=""){
			Fechas::setFormato($formato);
		}
	}
	
/**
 * Setea el tipo de separador utilizado para mostrar una fecha fomateada
 * Toma unicamente el primer caracter no blanco del separador pasado como parametro
 *
 * @param string $separador
 */
	public function setSeparador($separador){
		$this->separador=trim(substr($separador,0,1));
	}
	
	
/**
 * Setea el tipo de calendario a tomar en cuenta para los calculos de fechas
 *
 * @param constante de tipo de calendario o entero representado por la misma $calendario
 */
	public function setCalendario($calendario){
		$this->calendario=$calendario;
	}
	
/**
 * Setea el formato de visualizacion de la fecha
 *
 * @param string $formato
 */
	public function setFormato($formato){
		switch ($formato){
			case "dmy":
			case "DMY":
				$this->formato="dmy";
				break;
			case "mdy":
			case "MDY":
				$this->formato="mdy";
				break;
			case "ymd":
			case "YMD":
				$this->formato="ymd";
				break;
			default:
				echo "Imposible definir el presente formato";
		}
	}
	
	
/**
 * Calcula nueva fecha a partir de la fecha que se pasa y la cantidad de dias de offset
 * Si no se pasa fecha, toma como base la fecha del día
 *
 * @param int $dias
 * @param fecha juliana $fecha
 * @return string formateado con la fecha
 */
	public function jdfecha_postdata($dias=0,$fecha=""){
		if ($fecha==""){
			$fecha=cal_to_jd($this->calendario,date("m"),date("d"),date("Y"));
		}
		$fecha+=$dias;
		return $this->jd2fecha($fecha);
	}

	
/**
 * Calcula la diferencia existente en días entre la primer fecha y la segunda
 *
 * @param fecha juliana $fecha1
 * @param fecha juliana $fecha2
 * @return int diferencia entre fecha1 - fecha2
 */
	public function jdfecha_dif($fecha1="",$fecha2=""){
		if ($fecha1==""){
			$fecha1=cal_to_jd($this->calendario,date("m"),date("d"),date("Y"));
		}
		if ($fecha2==""){
			$fecha2=cal_to_jd($this->calendario,date("m"),date("d"),date("Y"));
		}
		return $fecha1-$fecha2;
	}

	
/**
 * Compara dos fechas indicando retornando segun sea 
 * 				-1 si fecha1 es menor que fecha2
 * 				 0 si son iguales
 * 				 1 si fecha1 es mayor que fecha2
 *
 * @param fecha juliana $fecha1
 * @param fecha juliana $fecha2
 * @return int
 */
	public function jdfecha_cmp($fecha1="",$fecha2=""){
		$retorno=$this->jdfecha_dif($fecha1,$fecha2);
		if ($retorno<0){
			$retorno=-1;
		} elseif ($retorno>0){
			$retorno=1;
		}
		return $retorno;
	}
	
	
/**
* Calcula nueva fecha a partir de la fecha que se pasa y la cantidad de dias de offset
 * Si no se pasa fecha, toma como base la fecha del día.
 * El formato de la fecha lo toma en base al formato y separador definido.
 *
 * @param int $dias
 * @param string fecha formateada $fecha
 * @return string formateado con la fecha
  */
	public function fecha_postdata($dias=0,$fecha=""){
		$retorno="";
/*		if ($fecha != ""){
			$jdfecha=$this->fecha2jd($fecha);
			if ($jdfecha!=""){
				$retorno=$this->jdfecha_postdata($dias,$jdfecha);
			} 
		} else {
			$retorno=$this->jdfecha_postdata($dias,$fecha);
		}*/
		$jdfecha=$this->prevalidaFecha($fecha);
		if ($jdfecha){
			$retorno=$this->jdfecha_postdata($dias,$jdfecha);
		}
		return $retorno;
	}

/**
 * Calcula la diferencia existente en días entre la primer fecha y la segunda
 *
 * @param string fecha formateado $fecha1
 * @param string fecha formateado $fecha2
 * @return int diferencia entre fecha1 - fecha2
 */
	public function fecha_dif($fecha1="", $fecha2=""){
		$retorno="";
		$jdfecha1=$this->prevalidaFecha($fecha1);
		$jdfecha2=$this->prevalidaFecha($fecha2);
		if (($jdfecha1=="" || $jdfecha2=="") && (!is_bool($jdfecha1) && !is_bool($jdfecha2))){
			$retorno=$this->jdfecha_dif($jdfecha1,$jdfecha2);
		}
		return $retorno;
	}

	
/**
 * Compara dos fechas indicando retornando segun sea 
 * 				-1 si fecha1 es menor que fecha2
 * 				 0 si son iguales
 * 				 1 si fecha1 es mayor que fecha2
 *
 * @param fecha juliana $fecha1
 * @param fecha juliana $fecha2
 * @return int
 */
	public function fecha_cmp($fecha1="", $fecha2=""){
		$retorno="";
		$jdfecha1=$this->prevalidaFecha($fecha1);
		$jdfecha2=$this->prevalidaFecha($fecha2);
		if ( ($jdfecha1=="" || $jdfecha2=="") && (!is_bool($jdfecha1) && !is_bool($jdfecha2)) ){
			$retorno=$this->jdfecha_cmp($jdfecha1,$jdfecha2);
		}
		return $retorno;
	}
	

/**
 * Prevalida que se pase algun valor a la fecha y retorna una fecha juliana
 * En el caso que la fecha sea incorrecta retorna un falso
 *
 * @param string fecha formateado $fecha
 * @return falso o fecha juliana
 */
	private function prevalidaFecha($fecha){
		$retorno=false;
		if ($fecha != ""){
			$jdfecha=$this->fecha2jd($fecha);
			if ($jdfecha!=""){
				$retorno=$jdfecha;
			} 
		} else {
			$retorno=$fecha;
		}
		return $retorno;
	}
	
	
/**
 * Retorna la fecha juliana segun el calendario especificado para la fecha pasada como parametro
 * Valida que la fecha sea correcta según separador, calendario y formato.
 * En caso que la fecha sea invalida retorna un ""
 *
 * @param string fecha formateado$fecha
 * @return fecha juliana
 */
	public function fecha2jd($fecha){
		$retorno="";
		if ($this->validaFecha($fecha)){
			$func="parseaFecha_".$this->formato;
			$vector=$this->{$func}($fecha);
			$retorno=cal_to_jd($this->calendario,$vector["mes"],$vector["dia"],$vector["anio"]);
		}
		return $retorno;
	}
	
	
	private function parsefecha($fecha,$pos){
		$posant=0;
		$string=$fecha;
		for($x=1;$x<=$pos;$x++){
//			$possep=strpos($string,"/");
			$possep=strpos($string,$this->separador);
			if (!$possep){
				$dato=$string;
			} else {
				$dato=substr($string,0,$possep);
				$string=substr($string,$possep+1);
			}
		}
		return $dato;
	}
	
/**
 * Retorna una fecha juliana con el formato definido y el separador indicado
 *
 * @param fecha juliana $fecha
 * @return fecha formateada
 */
	public function jd2fecha($fecha){
		$func="jd2fecha_".$this->formato;
		return $this->{$func}($fecha);
	}
	
	
	private function jd2fecha_dmy($fecha){
		$vector=cal_from_jd($fecha,$this->calendario);
		return $vector["day"].$this->separador.$vector["month"].$this->separador.$vector["year"];
	}

	
	private function jd2fecha_mdy($fecha){
		$vector=cal_from_jd($fecha,$this->calendario);
		return $vector["month"].$this->separador.$vector["day"].$this->separador.$vector["year"];
	}

	
	private function jd2fecha_ymd($fecha){
		$vector=cal_from_jd($fecha,$this->calendario);
		return $vector["year"].$this->separador.$vector["month"].$this->separador.$vector["day"];
	}

/**
 * Toma una fecha de entrada con formato YMD y lo retorna con formato DMY
 *
 * @param string $fecha_YMD
 * @return string $fecha_DMY 
 */
	public function ymd2dmy($fecha){
		$vector=$this->parseaFecha_ymd($fecha)	;
		$retorno=false;
		$retorno=$this->validaVectorFecha($vector);
		if($retorno){
			return $vector["dia"].$this->separador.$vector["mes"].$this->separador.$vector["anio"];
		}
	}

/**
 * Toma una fecha de entrada con formato DMY y lo retorna con formato YMD
 *
 * @param string $fecha_DMY
 * @return string $fecha_YMD 
 */
	public function dmy2ymd($fecha){
		$vector=$this->parseaFecha_dmy($fecha)	;
		$retorno=false;
		$retorno=$this->validaVectorFecha($vector);
		if($retorno){
			return $vector["anio"].$this->separador.$vector["mes"].$this->separador.$vector["dia"];
		}
	}
	
	
/**
 * Valida una fecha dada como string segun el calendario, el formato y el separador definidos
 *
 * @param string $fecha
 * @return boolean
 */
	public function validaFecha($fecha){
		$func="parseaFecha_".$this->formato;
		$vector=$this->{$func}($fecha);
		$retorno=false;
		if ((is_numeric($vector["dia"]) || is_numeric($vector["mes"]) || is_numeric($vector["anio"]))){
			if ($vector["mes"]<=12){
				if ($vector["dia"] <= cal_days_in_month($this->calendario,$vector["mes"],$vector["anio"])){
					$retorno=true;
				}
			} 
		} 
		return $retorno;
	}
	

	private function validaVectorFecha($vector){
		$retorno=false;
		if ((is_numeric($vector["dia"]) || is_numeric($vector["mes"]) || is_numeric($vector["anio"]))){
			if ($vector["mes"]<=12){
				if ($vector["dia"] <= cal_days_in_month($this->calendario,$vector["mes"],$vector["anio"])){
					$retorno=true;
				}
			} 
		} 
		return $retorno;
	}
	
	private function parseaFecha_dmy($fecha){
		$retorno=array();
		$retorno["dia"]=$this->parsefecha($fecha,1);
		$retorno["mes"]=$this->parsefecha($fecha,2);
		$retorno["anio"]=$this->parsefecha($fecha,3);
		return $retorno;
	}
	
	private function parseaFecha_mdy($fecha){
		$retorno=array();
		$retorno["dia"]=$this->parsefecha($fecha,2);
		$retorno["mes"]=$this->parsefecha($fecha,1);
		$retorno["anio"]=$this->parsefecha($fecha,3);
		return $retorno;
	}
	
	private function parseaFecha_ymd($fecha){
		$retorno=array();
		$retorno["dia"]=$this->parsefecha($fecha,3);
		$retorno["mes"]=$this->parsefecha($fecha,2);
		$retorno["anio"]=$this->parsefecha($fecha,1);
		return $retorno;
	}
	
	public function mesTxt($mes){
		$retorno="Mes Inexistente";
		if(is_numeric($mes)){
			switch ($mes) {
				case 1:
					$retorno="Enero";
					break;
				case 2:
					$retorno="Febrero";
					break;
				case 3:
					$retorno="Marzo";
					break;
				case 4:
					$retorno="Abril";
					break;
				case 5:
					$retorno="Mayo";
					break;
				case 6:
					$retorno="Junio";
					break;
				case 7:
					$retorno="Julio";
					break;
				case 8:
					$retorno="Agosto";
					break;
				case 9:
					$retorno="Setiembre";
					break;
				case 10:
					$retorno="Octubre";
					break;
				case 11:
					$retorno="Noviembre";
					break;
				case 12:
					$retorno="Diciembre";
					break;
				default:
					break;
			}
		}
		return $retorno;
	}
	
} // FIN CLASE FECHA

?>
