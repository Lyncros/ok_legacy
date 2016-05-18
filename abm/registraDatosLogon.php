<html>
<head>
<title>Administracion</title>
</head>

<body>
<?php

include_once('clases/class.loginwebuserVW.php');

$logon=new LoginwebuserVW();
$logon->leeDatosLoginView();
?>
</body>
</html>
