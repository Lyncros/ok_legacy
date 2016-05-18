<?php
session_start();
if($_SESSION['kt_login_id'] == "" || $_SESSION['kt_login_user']== ""){
	header("location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Okeefe - Administración</title>
<frameset rows="33,*">
  <frame src="menu.php" name="menu" frameborder="0" noresize="noresize" scrolling="no">
  <frame src="centro.html" name="centro" frameborder="0" scrolling="auto">
</frameset>
</head>

<noframes><body>
</body></noframes>
</html>
