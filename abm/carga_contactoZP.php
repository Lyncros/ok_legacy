<?php
include_once("inc/encabezado.php");
include_once("clases/class.relacion.php");
include_once("generic_class/class.menu.php");
include_once("./inc/encabezado_html.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe Propiedades</title>
<script LANGUAGE="JavaScript" type="text/javascript"
	src="inc/funciones.js"></script>
<script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
<link href="css/agenda.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
            var StayAlive = 1;
            function KillMe(){
                setTimeout("self.close();window.opener.location.reload();",StayAlive * 1000);
            }
        </script>
</head>
<body>


<?php

$ingreso=true;
$id="";

$origen="lista_propiedad.php?i=";

if (isset($_GET['i']) && $_GET['i']!=0){
	$prop= $_GET['i'];
	$relVW= new RelacionVW(0,$prop,0);
} else {
	$relVW= new RelacionVW();
	if(isset($_POST['id_pc']) && isset($_POST['id_sc']) && isset($_POST['id_relacion'])){
		$relVW->leeDatosRelacionVW();
		$retorno=$relVW->grabaRelacion();
		if(!$retorno){
			echo "Fallo el registro de los datos";
		} //else {
			$ingreso=false;
		//}

	//	echo "<span style=\"text-align: center; font-size:16px; color: #666666; padding: 20px;\">Se proceso la solicitud con exito.</span>";
	//	echo "<script type=\"text/javascript\">KillMe(); </script>\n";

	}
}
if ($ingreso){
	//	$_SESSION['id_prop']=$prop;
	
	$menu = new Menu ( );
	$menu->dibujaMenu ();
	
	$relVW->vistaTablaRelacion(0,$prop,0);
	print "<br>";
	$relVW->cargaRelacionUsuarioPropiedad(0,$prop);
	print "<br>";
	$relVW->cargaRelacionClientePropiedad(0,$prop);
}else{
	$_SESSION['opcionMenu']=2;
	header('location:'.$origen.$prop);
	//echo "<script type=\"text/javascript\">KillMe(); </script>\n";

}

?>

</body>
</html>
