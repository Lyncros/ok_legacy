<?php 
session_start();
if($_SESSION['kt_login_id'] == "" || $_SESSION['kt_login_user']== ""){
	header("location: index.php");
}

?>