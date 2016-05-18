<?php
include_once("inc/encabezado.php");
include_once('clases/class.loginwebuserBSN.php');
include_once("clases/class.perfilesBSN.php");
include_once("clases/class.cucicbaBSN.php");

include_once("inc/funciones.inc");

$perf = new PerfilesBSN();

$perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
$perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

$usrBSN = new LoginwebuserBSN();
$usrBSN->cargaById($_SESSION['UserId']);
$miUser=$usrBSN->getObjeto();

$id_prop=$_GET['i'];

$zp= new CucicbaBSN();
$prop = $zp->consultaRegistroPropiedad($id_prop);

include_once("./inc/encabezado_pop.php");
?>
<script language="javascript" type="text/javascript">
var StayAlive = 1;
function KillMe(){
   	window.opener.location.reload();
    setTimeout("self.close();",StayAlive * 1000);
}
function muestraResp(val){
	if(val == 1){
		document.getElementById('responsable').style.display = 'block';
		document.getElementById('id_resp').options[0].value = 0;
	}else{
		document.getElementById('responsable').style.display = 'none';
		document.getElementById('id_resp').options[0].value = '';
	}
}
</script>
<script language="JavaScript" src="inc/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<div id="container" style="width: 400px;">
<div class="pg_titulo">Publicaciones en CUCICBA</div>
<form action="publicaCucicba.php" method="post" id="zp" name="zp">
<div style="padding:5px 20px;width: 360px;">
	<div style="padding: 10px; float: left; width: 150px;">Usuario<br /><input type="text" name="operador" value="<?php echo $miUser->getEmail();?>" /></div>
	<div style="padding: 10px; float: right; width:150px;">Password<br /><input type="password" name="clave" value="" /></div>
	<div style="clear:both;"></div>
</div>
<div style="padding:5px 20px;">
<?php if($prop[0]['fec_ini'] == '' || ($prop[0]['fec_ini'] != '' && $prop[0]['fec_fin'] != '')){ ?>
	<div style="padding: 10px; float: left; width: 150px;"><input type="radio" name="accion" value="1" onclick="muestraResp(1);" checked="checked" /> Publicar</div>
	<div style="clear:both;"></div>
<div id="responsable" style="width: 300px; padding: 10px;">
Responsable de la Publicaci&oacute;n:
<?php $usrBSN-> comboUsuarios(0,'id_resp');?>
</div>

<?php }else{?>
	<div style="padding: 10px; float: left; width: 150px;">Publicado el <?php echo date('d-m-Y', strtotime($prop[0]['fec_ini'])); ?></div>
<?php 
		if($prop[0]['fec_ini'] != '' && $prop[0]['fec_fin'] == ''){
?>
	<div style="padding: 10px; float: right; width:150px;"><input type="radio" name="accion" value="0" onclick="muestraResp(0);" checked="checked" /> Retirar</div>
<?php 	}
	}?>

<div style="padding:5px 20px 5px 300px;width: 360px; display: block;">
    <input type="submit" value="Enviar" class="boton_form_filtro" /></div>
<!-- <input type="hidden" name="operador" value="<?php echo $_SESSION['UserId'];?>" />  -->
<input type="hidden" name="id" value="<?php echo $id_prop;?>" />
</div>
</form>

<script type="text/javascript">//KillMe(); </script>
</div>
<script language="JavaScript" type="text/javascript" xml:space="preserve">//<![CDATA[
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("zp");
  frmvalidator.addValidation("operador","req", "Ingrese una cuenta de email");
  frmvalidator.addValidation("operador","email", "Ingrese una cuenta de email válida");
  frmvalidator.addValidation("clave","req","Debe ingresar su password");
//  frmvalidator.addValidation("accion","selone","Debe seleccionar una opcion: Publicar o Retirar");
  frmvalidator.addValidation("id_resp","dontselect=0", "Elija una persona responsable de la publicacion");   
//]]></script>
</body>
</html>
