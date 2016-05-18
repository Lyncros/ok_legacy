<?php
include_once("inc/encabezado.php");
include_once("clases/class.emprendimientoBSN.php");
include_once('inc/encabezado_pop.php');

if (isset($_GET['i'])){
	$id= $_GET['i'];
	$pag=$_GET['pag'];
        
	$notiBSN= new EmprendimientoBSN($id);
	$notiBSN->publicarEmprendimiento();

}
?>
<script type="text/javascript">
window.parent.opener.location.href='lista_emprendimiento.php?i=0&pag='+<?php echo $pag; ?>;
window.parent.focus();
self.close(); 
</script>

<?php
	include_once("./inc/pie.php");

?>
