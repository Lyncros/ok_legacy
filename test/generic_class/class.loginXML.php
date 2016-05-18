<?php
include_once("generic_class/class.cargaConfiguracion.php");

class LoginXML{
	
	private $usr="";
	private $clave="";
	
	public function __construct(){
		$conf= CargaConfiguracion::getInstance();
		LoginXML::setAdmUsr($conf->leeParametro('admusr'));
		LoginXML::setAdmPass($conf->leeParametro('admpass'));
	}
	
	public function setAdmUsr($_usr){
		$this->usr=$_usr;
	}
	
	public function setAdmPass($_clave){
		$this->clave=$_clave;
	}
	
	public function validaLogin($_usr,$_clave){
		$retorno=false;
		if($this->clave != "" && $this->clave==$_clave && $this->usr != "" && $this->usr == $_usr){
			$_SESSION['acceso']=1;
			$retorno=true;
		} else {
			$_SESSION['accceso']=0;
		}
		if(!isset($_SESSION['round'])){
			$_SESSION['round']=0;
		} else {
			if($_SESSION['round'] < 3 ){
				$_SESSION['round']=$_SESSION['round']+1;
			} else {
				header("location:http://www.google.com.ar");
			}
		}
		return $retorno;
	}
	
	public function cargaLogin($msg=""){
		if($msg!=""){
			echo "<b><span style='text-color:#005500;'>Error al ingresar el usuario y la clave. Verifique sus datos.</span></b>";
		}
		echo "<form name='login' id='login' method='POST' action='login.php'>";
		echo "<table>";
		echo "<tr>";
		echo "<td class='titulos-01'> Usuario :</td><td><input type='text' name='usuario' id='usuario'></td>";
		echo "</tr><tr>";
		echo "<td class='titulos-01'> Clave :</td><td><input type='Password' name='password' id='password'></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td colspan='2'><input type='Submit' value=' Ingreso ... '></td>";
//		echo "<td colspan='2'><input type='Submit' value=' Ingreso ... ' onClick='enviaMD5(calculaMD5())'></td>";
		echo "</tr><tr>";
		echo "<td colspan='2' class='titulos-01'></td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
	}
	
	public function leeLogin(){
		if (isset($_POST['usuario'])){
			$usr=$_POST['usuario'];
			$psw=$_POST['password'];
		} else {
				$usr="";
				$psw="";
		}
		$arrayUsr=array($usr,$psw);
		return $arrayUsr;
	}

}