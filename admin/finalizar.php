<?php
session_start();
//print_r($_SESSION); 
//die();
session_unregister('kt_login_user');
session_destroy();
header("location: http://www.okeefe.com.ar");
?>