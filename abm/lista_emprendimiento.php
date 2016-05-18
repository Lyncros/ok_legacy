<?php
include_once("inc/encabezado.php");
include_once("clases/class.emprendimientoVW.php");
include_once("inc/encabezado_html.php");

if (isset($_GET['i'])){
	$id= $_GET['i'];
	if(!isset($_POST['pagina']) && !isset($_GET['pag'])){
		$pag=1;
	}else{
            if(isset($_GET['pag'])){
                $pag=$_GET['pag'];
            }else{
		$pag=$_POST['pagina'];
            }
	}
        if($_POST['filtro']==1){
            $arrayFiltro=array();
            $arrayFiltro['fid_tipo_emp']=$_POST['fid_tipo_emp'];
            $arrayFiltro['festado']=$_POST['festado'];
            $arrayFiltro['aux_ubica']=$_POST['aux_ubica'];
            $arrayFiltro['id_ubicaPrincipal']=$_POST['id_ubicaPrincipal'];
            $arrayFiltro['filtro']=1;
            $_SESSION['filtroEmp']=$arrayFiltro;
        }elseif (isset($_POST['filtro']) && $_POST['filtro']==0) {
            unset ($_SESSION['filtroEmp']);
        }
	$postreVW= new EmprendimientoVW();
	$postreVW->vistaTablaVW($pag);
}

include_once("inc/pie.php");
?>
