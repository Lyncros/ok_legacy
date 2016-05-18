<?php
include_once ("generic_class/class.menu.php");
include_once ("generic_class/class.logBSN.php");
include_once ("generic_class/class.log.php");
include_once("generic_class/class.fechas.php");
include_once("generic_class/class.cargaConfiguracion.php");

//include_once ("inc/funciones.inc");

class LogVW {
	private $log;
	private $arrayForm;

	public function __construct($_log = 0) {
		LogVW::creaLog ();
		if ($_log instanceof Log) {
			LogVW::seteaLog ( $_log );
		}
		if (is_numeric( $_log )) {
			if ($_log != 0) {
				LogVW::cargaLog ( $_log );
			}
		}
	}

	public function cargaLog($_log) {
		$log = new LogBSN ( $_log );
		$this->seteaLog ( $log->getObjeto () );
	}

	public function getIdVW() {
		return $this->log->getId_log();
	}

	public function getId_logbean() {
		return $this->log->getId_log();
	}

	protected function creaLog() {
		$this->log = new Log();
	}

	protected function seteaLog($_log) {
		$this->log = $_log;
		$log = new LogBSN ( $_log );
		$this->arrayForm = $log->getObjetoView();
	}


	/**
	 * Muestra una tabla con los datos del log y una barra de herramientas o menu
	 * conde se despliegan las opciones ingresables para cada item
	 */
	public function vistaTablaVW($vista=0,$complemento=0,$campocomp=0,$contdatos='') {
		$fila=0;
		if($vista==0){
			print "<script type='text/javascript' language='javascript'>\n";
			print "function envia(opcion,id){\n";
			print "     document.forms.lista.id_log.value=id;\n";
			print "   	submitform(opcion);\n";
			print "}\n";
			print "</script>\n";
			print "<span class='pg_titulo'>Log de procesamiento</span><br><br>\n";
			$menu = new Menu ( );
			$menu->dibujaMenu ( 'lista', 'opcion' );
			print "<form name='lista' method='POST' action='respondeMenu.php'>";
			print "  <table class='cd_tabla' width='100%'>\n";
			print "    <tr>\n";
//			print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
		}else{
			print "<span class='pg_titulo'>Log de procesamiento</span><br><br>\n";
			print "  <table class='cd_tabla' width='100%'>\n";
		}
		print "    <tr>\n";
		print "     <td class='cd_lista_titulo'>Fecha</td>\n";
		print "     <td class='cd_lista_titulo'>Usuario</td>\n";
		print "     <td class='cd_lista_titulo'>Tarea</td>\n";
		print "     <td class='cd_lista_titulo'>Proceso</td>\n";
		print "     <td class='cd_lista_titulo'>Estado</td>\n";
		print "     <td class='cd_lista_titulo'>Observacion</td>\n";
		print "	  </tr>\n";
		$evenBSN = new LogBSN ();
		if($vista==0){
			$arrayEven = $evenBSN->cargaColeccionForm ();
		}else{
			$arrayEven=$evenBSN->cargaRelacionLog($complemento,$campocomp);
		}
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
				if($vista==0){
//					print "	 <td align='center' width='25' class='row" . $fila . "'>";
//					print "    <a href='javascript:envia(871,\"" . $Even ['id_log'] . "\");' border='0'>";
//					print "       <img src='images/lupa.png' alt='Ver' title='Ver' border=0></a></td>";
				}else{
					$onclick=" onclick='javascript:muestraDatos(".$Even ['id_log'].",\"$contdatos\",\"l\");'";
				}
				print "	 <td  class='row" . $fila . "' $onclick>" . $Even['fecha'] . "</td>\n";
				print "	 <td  class='row" . $fila . "' $onclick>" . $Even['id_user'] . "</td>\n";
				print "	 <td  class='row" . $fila . "' $onclick>" . $Even['tarea'] . "</td>\n";
				print "	 <td  class='row" . $fila . "' $onclick>" . $Even ['proceso'] . "</td>\n";
				print "	 <td  class='row" . $fila . "' $onclick>" . $Even ['estado'] . "</td>\n";
				print "	 <td  class='row" . $fila . "' $onclick>" . $Even ['observacion'] . "</td>\n";
				print "	</tr>\n";
			}
		}
		print "  </table>\n";
		if($vista==0){
			print "<input type='hidden' name='id_logbean' id='id_logbean' value=''>";
			print "<input type='hidden' name='opcion' id='opcion' value=''>";
			print "</form>";
		}
	}

	public function vistaDatosLog(){
		print "<table width='95%' align='center' bgcolor='#FFFFFF'>\n";
		print "<tr><td class='cd_celda_titulo' colspan='2'>Datos del Log</td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Fecha</td>";
		print "<td width='85%'>";
		print $this->arrayForm['fecha'];

		print "<tr><td class='cd_celda_texto' width='15%'>Proceso</td>";
		print "<td width='85%'> " . $this->arrayForm ['proceso'] . " </td></tr>\n";
		print "<tr><td class='cd_celda_texto' width='15%'>Estado</td>";
		print "<td width='85%'> " . $this->arrayForm ['estado'] . " </td></tr>\n";

		print "<tr><td class='cd_celda_texto' width='15%'>Observacion</td>";
		print "<td width='85%'> " . $this->arrayForm ['observacion'] . " </td></tr>\n";
		print "</table>\n";
	}


}

// fin clase
?>