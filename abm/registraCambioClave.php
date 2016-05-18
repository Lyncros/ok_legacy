<?php
include_once("inc/encabezado.php");
include_once('clases/class.loginwebuserBSN.php');
include_once ('generic_class/securimage.php');
include_once("inc/encabezado_pop.php");

$usuario=$_POST['usuario'];
$clave=$_POST['password'];
$nclave=$_POST['npassword'];
$rclave=$_POST['rpassword'];
//echo $clave."<br>";
$securimage = new Securimage();

        $captcha=$_POST['captcha'];
        echo $captcha."<br>";
        if ($securimage->check($captcha) == false) {
            echo 'Fallo la carga del codigo de seguridad. Por favor reintente.<br />';
        } else {
// valido usuario y clave		
		$login=new LoginwebuserBSN();
		$retorno=$login->controlLogin($usuario,$clave);

		if($retorno){
			$login->ingresocambioClave($nclave);
			echo "Se proceso correctamente el cambio de Clave";
		} else {
			echo "FALLO el cambio de Clave";
		}		
        }
include_once("./inc/pie.php");
?>