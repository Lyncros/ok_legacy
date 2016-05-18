<?php

include_once ("inc/encabezado.php");
include_once ("clases/class.fotoBSN.php");
include_once ("clases/class.fotoVW.php");
include_once ("./inc/encabezado_pop.php");

$ingreso = true;
$id = "";

if (isset($_GET ['i']) && $_GET ['i'] != 0) {
    $prop = $_GET ['i'];
//    if (isset($_GET ['acc']) && $_GET ['acc'] == 'b' && isset($_GET ['f']) && $_GET ['f'] != 0) {
    if (isset($_GET ['acc']) && isset($_GET ['f']) && $_GET ['f'] != 0) {
        $fotoBSN = new fotoBSN($_GET['f']);
        switch ($_GET ['acc']) {
            case 'b':
                $fotoBSN->borraDB();
                break;
            case 'u':
                $fotoBSN->subirFoto();
                break;
            case 'd':
                $fotoBSN->bajarFoto();
                break;
            case 'm':
                $fotoBSN->mostrarFoto();
                break;
            case 'o':
                $fotoBSN->ocultarFoto();
                break;
        }
        $ingreso = false;
    }
    if (isset($_GET ['f']) && $_GET ['f'] == 0) {
        $fotoVW = new FotoVW ();
        $fotoVW->setIdPropiedad($prop);
    }
} else {
    $fotoVW = new FotoVW ();

    if (isset($_POST ['id_foto'])) {
        $fotoVW->leeDatosFotoVW();
        $prop = $fotoVW->getIdPropiedad();
        if ($_POST ['id_foto'] == 0) {
            $retorno = $fotoVW->grabaFoto();
        }
        if (!$retorno) {
            echo "Fallo el registro de los datos";
        } else {
            $ingreso = false;
        }
    }
}

if ($ingreso) {
    $_SESSION ['id_prop'] = $prop;
    $fotoVW->vistaTablaVW($prop);
    print "<br />";
    $fotoVW->cargaDatosVW($prop);
} else {
    echo "<script type=\"text/javascript\">window.parent.opener.location.reload();window.parent.focus();self.close(); </script>\n";
}
include_once ("./inc/pie.php");
?>


<script type='text/javascript' language='javascript'>

function envia(nameForm,opcion,id){
    document.forms.lista.id_foto.value=id;
    submitform(nameForm,opcion);
}

function borra(prop,foto){
    window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=b', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');
}

function subir(prop,foto){
    window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=u', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');
}

function bajar(prop,foto){
    window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=d', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');
}

function mostrar(prop, foto) {
    window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=m', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');
}

function ocultar(prop, foto) {
    window.open('carga_Foto.php?i='+prop+'&f='+foto+'&acc=o', 'ventanaFoto', 'menubar=1,resizable=1,width=950,height=550');
}
            
</script>
