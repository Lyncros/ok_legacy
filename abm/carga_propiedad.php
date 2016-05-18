<?php
include_once("inc/encabezado.php");
include_once("generic_class/class.cargaConfiguracion.php");
include_once("generic_class/class.menu.php");

include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedadVW.php");

include_once("clases/class.datospropBSN.php");
include_once("clases/class.datospropVW.php");

include_once("clases/class.operacionBSN.php");
include_once("clases/class.tasacionBSN.php");

$conf = CargaConfiguracion::getInstance();
$timezone = $conf->leeParametro('timezone');
date_default_timezone_set($timezone);

if((isset($_GET['i']) && $_GET['i'] != '') || isset($_POST['id_prop'])){
	include_once("./inc/encabezado_pop.php");
}else{
	include_once("./inc/encabezado_html.php");
}

$ingreso=true;
$id="";
$tipo_prop=0;
$modi=0;
$origen="lista_propiedad.php?i=";

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$propVW= new PropiedadVW($id);
	$tipo_prop=$propVW->getIdTipoProp();
	if($tipo_prop==''){
		$tipo_prop=0;
	}
	$datosVW = new DatospropVW();
} else {
	$propVW= new PropiedadVW($id);
	$datosVW = new DatospropVW();
	if(isset($_POST['id_prop'])){
		$propVW->leeDatosPropiedadVW();
		$datosVW->leeDatosDatospropVW();
		$id=$propVW->getId();
		if ($_POST['id_prop']==0){
			$retorno=$propVW->grabaPropiedad();
			if($retorno){
				$id=$propVW->getId();
				$retDP=$datosVW->grabaDatosprop($id);
			}
		} else {
                    include_once('clases/class.loginwebuserBSN.php');
                    include_once("clases/class.perfilesBSN.php");
                    include_once("clases/class.zonapropBSN.php");
//                    include_once("clases/class.mercadoBSN.php");
                    include_once("clases/class.argenpropBSN.php");
                    include_once("generic_class/class.cargaParametricos.php");

                    $zpConsulta= new ZonapropBSN($id);
                    $propZP = $zpConsulta->consultaRegistroPropiedad();

//                    $mlConsulta=new MercadoBSN($id);
//                    $propML = $mlConsulta->consultaRegistroPropiedad();

                    $apConsulta= new ArgenpropBSN($id);
                    $propAP = $apConsulta->consultaRegistroPropiedad();


                    $modi=1;
                    $retorno=$propVW->grabaModificacion();
                    $retDP=$datosVW->grabaDatosprop($id);


                    if($propZP[0]['fec_ini'] != '' && ($propZP[0]['fec_fin'] == '' || is_null($propZP[0]['fec_fin']))){

                        $usrBSN = new LoginwebuserBSN();
                        $usrBSN->cargaById($propZP[0]['id_user']);
                        $miUser=$usrBSN->getObjeto();

                        $id_resp=$propZP[0]['id_resp'];
                        $id_user=$propZP[0]['id_user'];
                        $usrMail = $propZP[0]['cuenta'];
        //                print_r($propZP);//die();
                        $clave='';
                        $parametricos = new CargaParametricos('usuariosZp.xml');
                        $arraUsers = $parametricos->getParametros();
                        if (array_key_exists($usrMail, $arraUsers)) {
                            $datosUser = $arraUsers[$usrMail];
                            $arrayDatos = explode("|", $datosUser);
                            $clave = $arrayDatos[1];
                        }
                        $zp= new ZonapropBSN($id,$id_resp);
                        $zp->publicoPropiedad($usrMail,$clave,'m');
                    }

/*                    if($propML[0]['fec_ini'] != '' && ($propML[0]['fec_fin'] == '' || is_null($propML[0]['fec_fin']))){

                        $usrBSN = new LoginwebuserBSN();
                        $usrBSN->cargaById($propML[0]['id_user']);
                        $miUser=$usrBSN->getObjeto();

                        $id_resp=$propML[0]['id_resp'];
                        $id_user=$propML[0]['id_user'];
                        $usrMail = $propML[0]['cuenta'];

                        $clave='';
                        $parametricos = new CargaParametricos('usuariosZp.xml');
                        $arraUsers = $parametricos->getParametros();
                        if (array_key_exists($usrMail, $arraUsers)) {
                            $datosUser = $arraUsers[$usrMail];
                            $arrayDatos = explode("|", $datosUser);
                            $clave = $arrayDatos[1];
                        }
                        $ml= new MercadoBSN($id,$id_resp);
                        $ml->modificoPropiedad($usrMail,$clave);
                    }
*/
                    if($propAP[0]['fec_ini'] != '' && ($propAP[0]['fec_fin'] == '' || is_null($propAP[0]['fec_fin']))){

                        $usrBSN = new LoginwebuserBSN();
                        $usrBSN->cargaById($propAP[0]['id_user']);
                        $miUser=$usrBSN->getObjeto();

                        $id_resp=$propAP[0]['id_resp'];
                        $id_user=$propAP[0]['id_user'];
                        $usrMail = $propAP[0]['cuenta'];
        //                print_r($propZP);//die();
                        $clave='';
                        $parametricos = new CargaParametricos('usuariosZp.xml');
                        $arraUsers = $parametricos->getParametros();
                        if (array_key_exists($usrMail, $arraUsers)) {
                            $datosUser = $arraUsers[$usrMail];
                            $arrayDatos = explode("|", $datosUser);
                            $clave = $arrayDatos[1];
                        }
                        $ap= new ArgenpropBSN($id,$id_resp);
                        $ap->publicoPropiedad($usrMail,$clave,'m');
                    }
		}


		if(!$retorno){
			echo "Fallo el registro de los datos";
		} else {
			$ingreso=false;
		}
	}
}
if ($ingreso){
	if(isset($_GET['c'])){
		$cli=$_GET['c'];
	} else {
		$cli=0;
	}
	print "<form action='carga_propiedad.php' name='carga' id='carga' enctype='multipart/form-data' method='post' target='ventanaProp' onSubmit='javascript: return ValidaPropiedad(this);'>\n";

	$propVW->cargaDatosPropiedadDiv($cli);
	$datosVW->cargaDatosDatospropDiv($id, $tipo_prop);

	print "</form>\n";
	$_SESSION['opcionMenu']=2;

}  else {
	$id=$propVW->getId();
	if($modi==0){
		$operacion=new Operacion();
		$operacion->setId_prop($id);
		$operacion->setIntervino($_SESSION['UserId']);
		$operacion->setCfecha(date("d-m-Y"));
		$operacion->setOperacion($_POST['operacion']);
		$operacionBSN= new OperacionBSN($operacion);
		$ret=$operacionBSN->insertaDB();

		if($_POST['operacion']=='Tasacion'){
			$tasacion=new Tasacion();
			$tasacion->setId_prop($id);
			$tasacion->setTasador('');
			$tasacion->setCfecha(date("d-m-Y"));
			$tasacion->setEstado('Pendiente');
			$tasacion->setValor('');
			$tasacionBSN = new TasacionBSN($tasacion);
			$ret=$tasacionBSN->insertaDB();
		}
	}
	$_SESSION['opcionMenu']=2;
        if($modi==0){
        	if($_POST['operacion']=='Tasacion'){
                        $location=$origen.$id.'&t=1';
        	}else{
                        $location=$origen.$id;
        	}
                
            echo "<script type=\"text/javascript\">window.parent.opener.location.href='$location';alert('Propiedad: $id');self.close();</script>\n";
        }else{
            echo "<script type=\"text/javascript\">window.parent.opener.location.reload();self.close(); </script>\n";
        }


}

include_once("./inc/pie.php");
?>
