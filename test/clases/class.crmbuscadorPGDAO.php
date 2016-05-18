<?php
include_once("generic_class/class.PGDAO.php");
class CrmbuscadorPGDAO extends PGDAO {
	
	protected $INSERT="INSERT INTO #dbName#.crmbuscador (idcrm,crmpar,crmtxt,adjuntos) values ('#idcrm#','#crmpar#','#crmtxt#','#adjuntos#')";
	protected $DELETE="DELETE FROM #dbName#.crmbuscador WHERE idcrm='#idcrm#'";
	protected $UPDATE="UPDATE #dbName#.crmbuscador SET crmpar='#crmpar#',crmtxt='#crmtxt#',adjuntos='#adjuntos#' WHERE idcrm='#idcrm#' ";

	protected $FINDBYID="SELECT idcrm,crmpar,crmtxt,adjuntos FROM #dbName#.crmbuscador WHERE idcrm='#idcrm#'";

	protected $FINDBYCLAVE="SELECT idcrm,crmpar,crmtxt,adjuntos FROM #dbName#.crmbuscador WHERE crmtxt LIKE '#crmtxt#'";
	protected $COLECCION="SELECT idcrm,crmpar,crmtxt,adjuntos FROM #dbName#.crmbuscador ORDER BY idcrm";

} // Fin clase DAO?>