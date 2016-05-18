<html>
<head>
<link href="../../css/explorer_pc.css" rel="stylesheet" type="text/css">
<title>SENASA</title>
</head>

<body>
<?php

include_once('clases/class.loginwebuserVW.php');
//require_once('generic_class/LiteVerifyCode.class.php');

//LiteVerifyCode::Code();
//$codseg=$ROOT_URL."generic_class/LiteVerifyCode.class.php?code.gif";

$logon=new LoginwebuserVW();
//$logon->cambioClave($codseg);
$logon->cambioClave();
?>
</body>
</html>
