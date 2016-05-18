<?php
include_once("generic_class/class.PGDAO.php");
include_once("generic_class/class.BSN.php");
include_once("generic_class/class.maper.php");

include_once("clases/class.cliente.php");
include_once("clases/class.clientePGDAO.php");
include_once("clases/class.fechas.php");

class ClienteBSN  extends BSN {
	
	protected $clase = "Cliente";
	protected $nombreId = "Id_cli";
	protected $cliente;
	
	public function __construct($_login=""){
		ClienteBSN::seteaMapa();
		if ($_login  instanceof Cliente ){
			ClienteBSN::creaObjeto();
			ClienteBSN::seteaBSN($_login);
		} else {
			if (is_string($_login)){
				ClienteBSN::creaObjeto();
				if($_login!=""){
					if(is_numeric($_login)){
						ClienteBSN::cargaById($_login);
					} else {
						ClienteBSN::cargaByUsuario($_login);
					}
				}
			}
		}	
	}

	
	public function getId(){
		return $this->cliente->getId_cli();
	}

	public function setId($id){
		$this->cliente->setId_cli($id);
	}
	
	public function cargaByUsuario($_usuario){
		$login= new Cliente();
		$login->setUsuario($_usuario);
		$loginBSN = new ClienteBSN($login);
		$datoDB = new ClientePGDAO($loginBSN->getArrayTabla());
		$arrayDB=$this->leeDBArray($datoDB->findByClave());
		$this->cliente=$this->mapa->tablaTOobj($arrayDB[0]);
	}
	
	public function controlLogin($_usuario,$_clave){
		$retorno=0;
		$this->cargaByUsuario($_usuario);	
		
		if ($this->cliente->getUsuario()=="" || $this->cliente->getActiva()==0){
			echo "Usuario Inexistente";
		} else {
			$log= new Cliente();
			$log=$this->cliente;
			$result = $log->validaLogin($_clave);
/*
 * -3 	clave bloqueada por exceso de intentos fallidos
 * -2 	clave expirada por fecha
 * -1 	error de ingreso
 *  0	ingreso valido con clave original
 *  1 	ingreso valido con clave modificada
*/
			switch ($result){
				case -3:
//					echo "Usuario Bloqueado por intentos Fallidos";
					break;
				case -2:
//					echo "Clave expirada por fecha caduca";
					break;
				case -1:
//					echo "Error al ingresar la clave";
					break;
				case 0:
					$retorno=$log->getId_cli();
//					echo "Ingreso al sistema autorizado";
					break;
				case 1:
					$retorno=$log->getId_cli();
//					echo "Bien venido al sistema";
			}
			$logBSN = new ClienteBSN($log);
			if(!$logBSN->actualizaDB()){
				echo "fallo la actualizacion de cambios";
			}
		}
		return $retorno;
	}
	
/**
 * Metodo que registra una solicitud de cambio de clave por Olvido
 *
 * @return boolean
 */
	public function solicitudcambioClave(){
		$_clave=$this->generaClave();
		$log = new Cliente();
		$log=$this->cliente;
//		print_r($log);echo "<br>";
		
//		$_claveEnc=$this->encriptaClave($_clave);
//		$this->cliente->setNueva_clave($_claveEnc);
		$log->setFecha_nueva(date("d-m-Y"));
		$log->setNueva_clave($_clave);
		$this->cliente = $log;
		$logBSN = new ClienteBSN($this->cliente);
		$retorno=$logBSN->actualizaDB();
		if($retorno){
			$retorno=$_clave;
		}
		return $retorno;
	}
	
/**
 * Metodo que registra un cambio de clave por por el usuario
 *
 * @return boolean
 */
	public function ingresocambioClave($_clave){
		$log = new Cliente();
		$log=$this->cliente;
		$log->setClave($_clave);
		$log->setFecha_base(date("d-m-Y"));
		$log->limpiaNuevaClave();
		$this->cliente = $log;
		$logBSN = new ClienteBSN($this->cliente);
		$retorno=$logBSN->actualizaDB();
		return $retorno;
	}

	
/**
 * Metodo que confirma la modificacion de la clave
 *
 * @return boolean
 */
	protected function confirmacambioClave(){
		$retorno=$this->actualizaDB();
		return $retorno;
		}

	public function controlDuplicado(){
		$retorno=false;
		$datoDB=new ClientePGDAO($this->getArrayTabla());
		$arrayDB=$this->leeDBArray($datoDB->findByClave());
		if(sizeof($arrayDB[0])>0){
			$retorno=true;		
		}
		return $retorno;
	}
	
	private function buscaID_usuario($_usuario){
		$retorno=false;
		$login=new Cliente();
		$login->setUsuario($_usuario);
		$arrayTabla=$this->mapa->objTOtabla($login);
		$loginDB=new ClientePGDAO($arrayTabla);
		$array = $this->leeDBArray($loginDB->findByNombre());
		$retorno=$array[0]["id_cli"];
		return $retorno;
	}
	
	public function retornaClave($_id){
		return $this->cliente->getId_cli();
	}
	
	public function activarUsuario(){
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new ClientePGDAO($arrayTabla);
		$retorno=$propDB->activar();
		return $retorno;
	}


	public function desactivarUsuario(){
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new ClientePGDAO($arrayTabla);
		$retorno=$propDB->desactivar();
		return $retorno;
	}

	
	protected function generaClave(){
		$_clave="";
		for ($x=0;$x<8;$x++){
			$rand=rand(1,3);
			switch ($rand) {
				case 1:
					$min=48;
					$max=57;
					break;
				case 2:
					$min=65;
					$max=90;
					break;
				case 3:
					$min=97;
					$max=122;
					break;
			}
			$_clave.=chr(rand($min,$max));
		}
		return $_clave;
	}

	
	protected function encriptaClave($_clave){
		return md5($_clave);
	}
	
/**
 * Metodo para la carga de datos privados de la aplicaci�n de login, que no son 
 * mostrados al usuario pero deben ser mantenidos ante una modificaci�n de datos b�sicos
 *
 * @return estado de finalizacion de la operacion
 */
	public function cargaPrivados(){
		$retorno=false;
		$arrayTabla=$this->getArrayTabla();
		$propDB=new ClientePGDAO($arrayTabla);
		$result=$propDB->findById();
		$array = $this->leeDBArray($result);
		$this->cliente->setClave($array[0]["clave"]);
		$this->cliente->setFecha_base($array[0]["fecha_base"]);
		$this->cliente->setNueva_clave($array[0]["nueva_clave"]);
		$this->cliente->setFecha_nueva($array[0]["fecha_nueva"]);
		$this->cliente->setErrores($array[0]["errores"]);
		return $retorno;
	}
	
	public function comboUsuarios($valor='',$campo="id_cli",$class="campos_btn"){
		$perfil=$this->cargaColeccionForm();
		print "<select name='".$campo."' id='".$campo."' class='".$class."'>\n";
		print "<option value='0'";
		if ($valor==''){
			print " SELECTED ";
		}
		print ">Seleccione una opcion</option>\n";
		
		for ($pos=0;$pos<sizeof($perfil);$pos++){
			print "<option value='".$perfil[$pos]['id_cli']."'";
			if ($perfil[$pos]['id_cli']==$valor){
				print " SELECTED ";
			}
			print ">".$perfil[$pos]['id_cli']." - ".$perfil[$pos]['nombre']." ".$perfil[$pos]['apellido']."</option>\n";
		}
		print "</select>\n";
	}	
	
} // Fin clase

?>