<?php

class LoginVW {
	
/*
	private $maxfallos=3;
	private $usuario;
	private $clave;
	private $validez=90;
	private $fecha_base;
	private $errores;
	private $nueva_clave;
	private $fecha_nueva;
	private $validez_nueva=15;
*/
	
	public function logon(){

	//	print "<script type='text/javascript' src='../../js/md5.js'></script>\n";

		print "<script type='text/javascript' >\n";
		print "function calculaMD5() {\n";
		print "var pw = document.forms['login'].elements['password'].value;\n";
		print "return hex_md5(pw);\n";
		print "}\n";

		print "function enviaMD5(hash) {\n";
//		print "document.forms['login'].elements['password'].value = hash;\n";
		print "document.forms['login'].submit();\n";
		print "}\n";
		
		print "function olvido(){\n";
		print "var url='olvidoClave.php?usr='+document.forms['login'].elements['usuario'].value;\n";
		print "window.open(url);\n";
		print "}\n";
		print "</script>\n";


//		echo "<form name='login' id='login' method='POST' action='valida_login.php'>";
		echo "<form name='login' id='login' method='POST' action='login.php'>";
		echo "<table>";
		echo "<tr>";
		echo "<td class='titulos-01'> Usuario :</td><td><input type='text' name='usuario' id='usuario'></td>";
		echo "</tr><tr>";
		echo "<td class='titulos-01'> Clave :</td><td><input type='Password' name='password' id='password'></td>";
		echo "</tr><tr>";
		echo "<td colspan='2'><input type='Submit' value=' Ingreso ... ' onClick='enviaMD5(calculaMD5())'></td>";
		echo "</tr><tr>";
		echo "<td colspan='2' class='titulos-01'></td>";
		echo "</tr><tr>";
		echo "<td colspan='2' class='titulos-01'><a href='#' onClick='olvido();'>Me olvide la Clave!!!</a><br> Ingrese su usuario para remitirle una nueva clave.</td>";
		echo "</tr></table>";
		echo "</form>";
	}
	
	
	public function logoff($_usuario){

		print "<script type='text/javascript' >\n";
		
		print "function cambio(){\n";
		print "var url='cambioClave.php';\n";
		print "window.open(url);\n";
		print "}\n";

		print "function cierro(){\n";
		print "alert('PASA');";
		print "window.close();\n";
		print "}\n";
		print "</script>\n";

		
		echo "<table>";
		echo "<tr>";
		echo "<td class='titulos-01'> Usuario :</td><td>$_usuario</td>";
		echo "</tr><tr>";
		echo "<td colspan='2' class='titulos-01'></td>";
		echo "</tr><tr>";
		echo "<td colspan='2' class='titulos-01'><a href='#' onClick='cierro();'>Cerrar Sesi&oacute;n</a></td>";
		echo "</tr><tr>";
		echo "<td colspan='2' class='titulos-01'><a href='#' onClick='cambio();'>Cambiar la Clave</a><br> Complete los datos en el formulario para cambiar la misma.</td>";
		echo "</tr></table>";
	}

	
	
	
//	public function cambioClave($_codseg){
	public function cambioClave(){

//		print "<script type='text/javascript' src='../../js/md5.js'></script>\n";

		print "<script type='text/javascript' >\n";
		
		print "function calculaMD5(elem) {\n";
		print "var pw = elem;\n";
		print "var enc = hex_md5(pw);\n";
		print "return (hex_md5(pw));\n";
		print "}\n";
		
		print "function encripta() {\n";
		print "var ep=calculaMD5(document.forms['login'].elements['password'].value);\n";
		print "var en=calculaMD5(document.forms['login'].elements['npassword'].value);\n";
		print "var er=calculaMD5(document.forms['login'].elements['rpassword'].value);\n";
		print "document.forms['login'].elements['password'].value = ep;\n";					
		print "document.forms['login'].elements['npassword'].value = en;\n";					
		print "document.forms['login'].elements['rpassword'].value = er;\n";					
		print "}\n";

		print "function valida() {\n";
		print "var np=document.forms['login'].elements['npassword'].value;\n";
		print "var rp=document.forms['login'].elements['rpassword'].value;\n";
		print "if (np == rp){\n";
//		print "	encripta();\n";
		print "return (true);\n";
		print "} else {\n";
		print "	alert('Error al reingresar la nueva clave. Por favor reingrese los valores');\n";
		print "return (false);";
		print "}\n";
		print "}\n";
		
		print "</script>";
		
//		print "<script>\n";
//		print "window.resizeTo(527 , 450);\n";
//		print "window.moveTo( ((screen.width)-500)/2 ,0 );\n";
//		print "</script>\n";
	
		echo "<form name='login' id='login' method='POST' enctype='multipart/form-data' action='registraCambioClave.php' onsubmit='return valida();'>";
		echo "<table bgcolor='D7E6EA'>";
		echo "<tr>";
		echo "<td class='titulos-01'> Usuario :</td><td><input type='text' name='usuario' id='usuario'></td>";
		echo "</tr><tr>";
		echo "<td class='titulos-01'> Clave :</td><td><input type='Password' name='password' id='password'></td>";
		echo "</tr><tr>";
		echo "<td class='titulos-01'> Ingrese Nueva Clave :</td><td><input type='Password' name='npassword' id='npassword'></td>";
		echo "</tr><tr>";
		echo "<td class='titulos-01'> Confirme Nueva Clave :</td><td><input type='Password' name='rpassword' id='rpassword'></td>";
		echo "</tr>";
//		echo "</tr><tr>";
//		echo '<td colspan="2" align ="center">Codigo de Verificaci�n <img src="'.$_codseg.'" alt="" width="150" height="50" /><br /><br /><span class="titulos-01">Ingrese el Codio de Verificaci�n de la Imagen:</span> <input type="text" size="8" maxlength="8" name="code" id="code" value="" /></td>';
//		echo "</tr><tr>";
		echo "</tr>";
		echo "<td colspan='2' align='center'><input type='Submit' value=' Ingreso ... '></td>";
		echo "</tr></table>";
		echo "</form>";
	}
	

	
} // Fin Clase

?>