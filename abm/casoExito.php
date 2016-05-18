<?php

include_once ("inc/encabezado.php");
include_once ("clases/class.casoexito.php");

include_once ("./inc/encabezado_pop.php");

$ingreso = true;
$id = "";

if (isset($_GET ['i']) && $_GET ['i'] != 0 && isset($_GET['t']) && $_GET['t'] != '') {
    if ($_GET['t'] == 'p' || $_GET['t'] == 'e') {
        $id_elemento = $_GET ['i'];
        $tipo = $_GET['t'];
        $publicar = 0;
        $comentario = '';
        $id_caso = 0;
        $msg = '<br /><br />Desea identificar esta propiedad como un CASO DE EXITO ?<br /><br />';

        $caso = new Casoexito(0, $id_elemento, $tipo);
        $casoBSN = new CasoexitoBSN($caso);
        $orden = $casoBSN->proximoOrden();

        $arrayDatos = $casoBSN->cargaByClave();
        if (sizeof($arrayDatos)>0) {   // Verifico que venga algun dato en el mismo
            if ($arrayDatos[0]['publicar'] == 1) {
                $msg = '<br /><br />La propiedad esta marcada como un CASO DE EXITO.<br /><br />';
            }

            $publicar = $arrayDatos[0]['publicar'];
            $comentario = $arrayDatos[0]['comentario'];
            $orden = $arrayDatos[0]['orden'];
            $id_caso = $arrayDatos[0]['id_caso'];
        }
		print "<div><div>\n";
        print "<form action='casoExito.php' method='post'>\n";
        print "<input type='hidden' name='id_caso' id='id_caso' value='$id_caso'>";
        print "<input type='hidden' name='id_elemento' id='id_elemento' value='$id_elemento'>";
        print "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";
        print "<table width='100%' align='center'>\n";
        print "<tr>\n";
        print "<td colspan='2' class='cd_celda_texto'>$msg</td>";
        print "</tr>";
        print "<tr>\n";
        print "<td class='cd_celda_texto'>Publicar</td>";
        print "<td class='cd_celda_texto'>";
        print "<input type='checkbox' name='publicar' id='publicar' ";
        if ($publicar == 1 || $id_caso == 0) {
            print "checked>";
        } else {
            print ">";
        }
        print "</td></tr>";

        print "<tr>\n";
        print "<td class='cd_celda_texto'>Comentario</td>";
        print "<td class='cd_celda_texto'>";
        print "<input class='campos' type='text' name='comentario' id='comentario' value='$comentario' maxlength='250' size='80'>";
        print "</td></tr>";
        print "<tr>\n";
        print "<td class='cd_celda_texto'>Orden</td>";
        print "<td class='cd_celda_texto'>";
        print "<input class='campos' type='text' name='orden' id='orden' value='$orden' maxlength='3' size='10'> ";
        print "</td></tr>";

        print "<tr>\n";
        print "<td class='cd_celda_texto'><input class='boton_form' type='button' value='Cancel' onclick='KillMe();'></td>";
        print "<td class='cd_celda_texto'>";
        print "<input class='boton_form' type='submit' value='Enviar'>";
        print "</td></tr>";
        print "</table>\n";
    }
} else {
    if($_POST['publicar']=='on'){
        $_POST['publicar']=1;
    }else{
        $_POST['publicar']=0;
    }
    $caso= new Casoexito($_POST['id_caso'],$_POST['id_elemento'],$_POST['tipo'],$_POST['comentario'],$_POST['publicar'],$_POST['orden']);
    $casoBSN=new CasoexitoBSN($caso);
    if($_POST['id_caso']==0){
        $casoBSN->insertaDB();
    }else{
        $casoBSN->actualizaDB();
    }
    
    echo "<script type=\"text/javascript\">KillMe(); </script>\n";
}

include_once ("./inc/pie.php");
?>
