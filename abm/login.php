<?php

session_start();
include_once('clases/class.loginwebuserBSN.php');
include_once('clases/class.perfileswebuserBSN.php');
include_once('clases/class.loginwebuserVW.php');

$error='';
if (isset($_POST['usuario']) && $_POST['usuario'] <> '') {

    $usuario = $_POST['usuario'];
    $clave = $_POST['password'];
    $loginBSN = new LoginwebuserBSN();
    $retorno = $loginBSN->controlLogin($usuario, $clave);
    $loginBSN=null;
    if ($retorno > 0) {
        $perBSN = new PerfileswebuserBSN();
        $perfil = $perBSN->coleccionPerfilesUsuario($retorno);
        $_SESSION['Userrole'] = $perfil;
        $_SESSION['UserId'] = $retorno;
        header("location:lista_propiedad.php?i=");
        
    } else {
        $_SESSION['Userrole'] = '';
        $_SESSION['UserId'] = '';
        switch ($retorno) {
            case -3:
                $error= "Usuario Bloqueado por intentos Fallidos<br>";
                break;
            case -2:
                $error="Clave expirada por fecha caduca<br>";
                break;
            case -1:
            case -0:
                $error= "Error al ingresar la clave o usuario inexistente<br>";
                break;
        }
//        print "<br><a href='login.php'>Regresar al login</a>";
    }
}

    include_once 'inc/encabezado_html.php';
    echo "<div align=\"center\">";
    $logon = new LoginwebuserVW();
    $logon->logon();
    echo "</div>";
    echo "<div align=\"center\" style='padding:10px;'>";
    print "<span style='color:red'>$error</span>";
    echo "</div>";
    
    include_once 'inc/pie.php';
?>
