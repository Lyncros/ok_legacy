<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_config = "localhost";
$database_config = "okeefe_web";
#$username_config = "abmAdmin";
#$password_config = "oke10abm";
$username_config = "da_admin";
$password_config = "jLfTEmu9rZ";
$config = mysql_connect($hostname_config, $username_config, $password_config) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
