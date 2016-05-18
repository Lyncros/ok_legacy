<?php

if(isset($_POST['tipo']) && is_numeric($_POST['tipo'])){
	switch ($_POST['tipo']){
		case 1:
			$destino = "fichaTasacion.php";
			break;
		case 2:
			$destino = "ficha_prop_pdf.php";
			break;
	}
	header("location:".$destino."?id=".$_POST['id']);
}
include_once ("inc/encabezado.php");

include_once ("./inc/encabezado_pop.php");

if (isset ( $_GET ['i'] ) && $_GET ['i'] != 0) {
	print "<div>\n";
	print "<div style=\"width:200px; text-align:left;margin:20px;\"><div>\n";
	print "<form action='imprimirFicha.php' method='post'>\n";
	print "<input type=\"hidden\" name=\"id\" value=\"".$_GET['i']."\" />\n";
	print "<table width='250' align='center'>\n";
	print "<tr>\n";
	print "<td colspan='2' class='pg_titulo'>Tipo de Ficha a Imprimir</td>\n";
	print "</tr>";
	print "<tr>\n<td class='cd_celda_texto'>Tasaci&oacute;n</td>\n";
	print "<td class='cd_celda_texto'>";
	print "<input type='radio' name='tipo' value='1' />";
	print "</td></tr>";
	print "<tr>\n";
	print "<td class='cd_celda_texto'>Informaci&oacute;n</td>";
	print "<td class='cd_celda_texto'>";
	print "<input type='radio' name='tipo' value='2' checked />";
	print "</td></tr>";
	
	print "<tr>\n";
	print "<td class='cd_celda_texto'><input class='boton_form' type='button' value='Cancel' onclick='KillMe();' /></td>";
	print "<td class='cd_celda_texto'>";
	print "<input class='boton_form' type='submit' value='Enviar' onsubmit=\"KillMe();\" />";
	print "</td></tr>";
	print "</table>\n";
	print "</form>\n";
}
include_once ("./inc/pie.php");

?>
