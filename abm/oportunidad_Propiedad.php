<?php
include_once ("inc/encabezado.php");
include_once ("clases/class.propiedadBSN.php");

include_once ("./inc/encabezado_pop.php");

$ingreso = true;
$id = "";

if (isset ( $_GET ['i'] ) && $_GET ['i'] != 0) {
	$prop = $_GET ['i'];
        $propBSN=new PropiedadBSN($prop);
        if(isset ($_GET['e']) && $_GET['e']==-1){  // si es -1 viene directo de la pagina y no se opto todavia
            if($propBSN->getObjeto()->getOportunidad()==1){
                $msg='<br /><br />La propiedad esta marcada como una oportunidad.<br />Desea quitar esta identificacion?<br /><br />';
                $marca=0;
                $txtbtn='Quitar';
            }else{
                $marca=1;
                $msg='<br /><br />Desea identificar esta propiedad como una oportunidad ?<br /><br />';
                $txtbtn='Marcar';
            }
            
            print "<form action='oportunidad_Propiedad.php' method='post'>\n";
            print "<input type='hidden' name='id_prop' id='id_prop' value='$prop'>";
            print "<input type='hidden' name='oportunidad' id='oportunidad' value='$marca'>";
        
            print "<div align='center'>\n";
            print $msg;
            print "<input class='boton_form' type='button' value='Cancel' onclick='KillMe();'> &nbsp;&nbsp;&nbsp;<input class='boton_form' type='submit' value='$txtbtn'>\n";
            print "";
            print "</div>\n";
            
        }
        
} else {
    if(isset ($_POST['id_prop']) && isset ($_POST['oportunidad'])){
        $propBSN=new PropiedadBSN($_POST['id_prop']);
        if($_POST['oportunidad']==0){
            $propBSN->desactivaOportunidad();
        }else{
            $propBSN->activaOportunidad();
        }
    }
    echo "<script type=\"text/javascript\">KillMe(); </script>\n";
}

include_once ("./inc/pie.php");
?>
