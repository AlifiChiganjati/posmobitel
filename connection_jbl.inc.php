<?php
error_reporting(0);
	if (substr($_SERVER['SCRIPT_NAME'], 1)== "connection.inc.php")
		header("HTTP/1.0 404 Not Found");

	//$connect = mysql_connect("localhost", "root", "");
	$connect = mysql_connect("35.219.45.126", "nexaclient", "NXSolution2@2023");
	if (!$connect) {
		die("Error: Problem Connection !!!".mysql_error());
	}else{
		$db_selected = mysql_select_db("posjbl", $connect);
		if (!$db_selected) {
			die("Can't Open Database !!!".mysql_error());
		}
	}

?>
