<html><head></head>
<body>
prueba
<?
echo $_GET['p'];
include_once("clases/class.partido.php");

		$part=new Partido($_GET['p']);
		$part->comboPartido();

?>
</body>
</html>
