<?php
include_once ("clases/class.ubicacionpropiedadBSN.php");
if(isset($_GET['cemp']) && isset($_GET['emp'])){
    $campoEmp=$_GET['cemp'];
    $id_emp=$_GET['emp'];
}else{
    $campoEmp='';
}
$campoValor=$_GET['v'];
$campoZona=$_GET['z'];
if(isset($_GET['t']) && $_GET['t']=='r'){
	$tipo='r';
}else{
	$tipo='c';
}
if(isset($_GET['ncu'])){
	$nomCampoUbica=trim($_GET['ncu']);
}else{
	$nomCampoUbica='id_ubica';
}
if($campoZona==0){
	$campoDestino='id_padre';
}else{
	$campoDestino=$nomCampoUbica;
}
print "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Listado de Localidades</title>
<script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
<script type="text/javascript" language="JavaScript">
	

	function expandirLista(elem){
		if(document.getElementById('dd_'+elem).style.display=='none'){
			document.getElementById('dd_'+elem).style.display='block';
			document.getElementById('sp_'+elem).src='images/table_delete.png';
		}else{
			document.getElementById('dd_'+elem).style.display='none';
			document.getElementById('sp_'+elem).src='images/table_add.png';

		}
	}
	function enviarSeleccion(f){
<?php
	if($tipo=='r'){
            if($campoEmp!=''){
                print "window.opener.comboEmprendimiento(document.getElementById('aux_campo').value,$id_emp,'$campoEmp');\n";
            }
            print "window.opener.document.getElementById('$campoDestino').value=document.getElementById('aux_campo').value;\n";
            print "window.opener.document.getElementById('txtUbica').innerHTML=document.getElementById('txt_campo').value;\n";
	}else{
		print "retorno=leeCheckbox(f);\n";
		print "window.opener.document.getElementById('$campoDestino').value=retorno[0];\n";
		print "window.opener.document.getElementById('txtUbica').innerHTML=retorno[1];\n";
	}
 ?>
		window.close();
	}
	
	function leeCheckbox(f) {
		seleccion='';
		campos='';
		retorno=new Array(2);
		elementos=f.elements.length;
		for ( var n= 0 ; n < elementos; n++ ) {
			largo=f.elements[n].name.length;
			pref=f.elements[n].name.substring(0,2);
			pid=f.elements[n].name.substring(3,largo);
		    if(f.elements[n].checked && pref=='sz'){
				if(seleccion.length>0){
					seleccion+=',';
					campos+=', ';
				}
				seleccion+=pid;
				campos+=f.elements[n].title;
			}
		}
 		retorno[0]=seleccion;
		retorno[1]=campos;
		return retorno;
	}

	</script>
<link href="css/agenda.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<form name="listaSubZonas" id="listaSubZonas">
	<?php
	$ubiBSN = new UbicacionpropiedadBSN();
	$ubiBSN->checkboxUbicacionpropiedad($campoValor, $campoZona,$tipo)
	?>

		<input type='button' value='Seleccionar...'
			onclick='enviarSeleccion(this.form);' />
	</form>
</body>
</html>
