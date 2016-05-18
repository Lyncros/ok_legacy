<?php
include_once("inc/encabezado.php");
include_once("clases/class.datospropBSN.php");
include_once("clases/class.datospropVW.php");
include_once("./inc/encabezado_html.php");
include_once("clases/class.propiedadVW.php");


if (isset($_GET['i'])) {
    $id= $_GET['i'];
    if(!isset($_POST['pagina'])) {
        $pag=1;
    }else {
        $pag=$_POST['pagina'];
    }
    //$postreVW= new PropiedadVW();
    //$postreVW->vistaTablaPropiedad($pag);

    //print_r($_POST);
    $prop = new PropiedadVW();
    $prop->vistaTablaBuscador($pag);


}




include_once("./inc/pie.php");
?>

