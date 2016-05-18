<?php
include_once("clases/class.fotoBSN.php");

$id_prop = 0;
if(isset($_GET['id'])){
    $id_prop = $_GET['id'];
}

$fotoBSN = new FotoBSN();
$fotos = $fotoBSN->cargaColeccionFormByPropiedad($id_prop);

setlocale(LC_ALL, "es_ES");
echo "<?xml version='1.0' encoding='UTF-8'?>"."\n";
echo "<galeria>\n";

for($i=0; $i <count($fotos); $i++){
	$data = getimagesize("fotos/" . str_replace(" ", "%20", $fotos[$i]['hfoto']));
	echo "\t<imagen img='" . str_replace(" ", "%20", $fotos[$i]['hfoto']) . "' ancho='$data[0]' alto='$data[1]' />\n";
}

echo "</galeria>";

?>