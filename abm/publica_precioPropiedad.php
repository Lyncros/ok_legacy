<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
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

$id="";
$publica=0;


if (isset($_GET['i']) && isset($_GET['pub'])){
	$id= $_GET['i'];
	$publica=$_GET['pub'];

	if($id!=0 && ($publica==0 || $publica==1)){
		$propBSN= new PropiedadBSN($id);
		if($publica==1){
			$propBSN->publicarPrecioPropiedad();
			echo "Se activo la publicacion Web del precio de la propiedad";
		}else{
			$propBSN->quitarPrecioPropiedad();
			echo "Se quito de la publicacion Web del precio de la propiedad";
		}

	}
}
echo "<script type=\"text/javascript\">KillMe(); </script>\n";

?>

</body>
</html>
