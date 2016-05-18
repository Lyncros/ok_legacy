<?php
include_once("inc/encabezado.php");
include_once("clases/class.propiedadBSN.php");
//include_once("clases/class.zonapropBSN.php");
//include_once("clases/class.argenpropBSN.php");
//include_once("./inc/encabezado_html.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>O'Keefe Propiedades</title>
        <script LANGUAGE="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
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
        $pasar=0;

        if (isset($_GET['i'])) {
            $pasar=1;

            $id= $_GET['i'];
            $prop = new PropiedadBSN($id);
            if($prop->getObjeto()->getActiva()==0) {
                $chkWeb = '';
            }else {
                $chkWeb = 'checked';
            }
//	$zona=new ZonapropBSN($id);
// Buscar si esta publicada en ZonaProp, defniendo un metodo en ZonapropBSN() para este fin.
// hacer lo mismo con ArgeProp.	
            $chkZon = '';
            $chkArg = '';

            ?>
        <table align="left" cellpadding="5" cellspacing="0" width="350" style="height:230px;">
            <tr>
                <td class="pg_titulo">Estado de publicaci&oacute;n</td>
            </tr>
            <tr>
                <td valign="top" align="center">
                    <form id="lista" method="POST" action="publicar_propiedad.php">
                        <table align="center" cellpadding="5" cellspacing="0">
                            <tr>
                                <td width="80" class="cd_celda_texto">Web</td>
                                <td width="80" ><input  <?php echo $chkWeb; ?> class='campos' type='checkbox' name='web' id='web' /></td>
                            </tr>
                            <tr>
                                <td width="80" class="cd_celda_texto">Zonaprop</td>
                                <td width="80" ><input  <?php echo $chkZon; ?> class='campos' type='checkbox' name='zona' id='zona' /></td>
                            </tr>
                            <tr>
                                <td width="80" >Argenprop</td>
                                <td width="80" ><input  <?php echo $chkArg; ?> class='campos' type='checkbox' name='argen' id='argen' /></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right"><input class='boton_form' type='submit' value='Enviar' /><input type="hidden" name="id_prop" id="id_prop" value="<?php echo $id; ?>" /></td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
            <?php
        }elseif (isset($_POST['id_prop'])) {
            $pasar=1;
            $id=$_POST['id_prop'];

            $propBSN= new PropiedadBSN($id);
            if($_POST['web']=='on') {
                $propBSN->publicarPropiedad();
            }else {
                $propBSN->quitarPropiedad();
            }
// Replicar el bloque anterior para Zonaprop y Argenprop.
            echo "<span style=\"text-align: center; font-size:16px; color: #666666; padding: 20px;\">Se proceso la solicitud con exito.</span>";
            echo "<script type=\"text/javascript\">KillMe(); </script>\n";
        }

//include_once("./inc/pie.php");
        ?>

    </body>
</html>