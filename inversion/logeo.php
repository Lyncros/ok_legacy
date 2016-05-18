<?php
session_start ();

if (isset ($_REQUEST["pass"]))
{
	if ($_REQUEST["pass"] == "okeefeLandings2016")
	{
		$_SESSION['iniciada'] = "ok";
		header ('Location: exportar/index.php');
	}
	else
	{
		session_destroy ();
		
		header ('Location: login.html#1');
	}
}
?>