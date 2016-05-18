<?php

include_once("inc/encabezado.php");
include_once("./inc/encabezado_pop.php");
//$ingreso = true;
include_once("clases/class.zonapropBSN.php");

//$id_prop=$_POST['id'];
//$id_resp=$_POST['id_resp'];
//$id_user=$_POST['operador'];
//$accion=$_POST['accion'];
//$clave=$_POST['clave'];
?>
<script type="text/javascript">
window.onload = addRowHandlers;
function addRowHandlers() {
    var table = document.getElementById("rowHandlers");
    var rows = table.getElementsByTagName("tr");
    for (i = 1; i < rows.length; i++) {
        var row = table.rows[i];
        row.style.cursor = 'pointer';
        row.onclick = function(myrow){
                          return function() { 
                             var cell = myrow.getElementsByTagName("td")[0];
                             var id = cell.innerHTML;
                             //window.open('muestra_datosprop.php?i='+ id );
                             ventana('muestra_datosprop.php?i=' + id , 'Datos de la propiedad', 980, 900);
                      };
                  }(row);
    }
}
</script>
<?php
$zp= new ZonapropBSN();

$zp->muestraColeccionActivas();


?>
</body>
</html>
