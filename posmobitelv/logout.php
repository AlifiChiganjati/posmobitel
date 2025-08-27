<?php
	session_start();
	$_SESSION['IDSALES'] = "";
	$_SESSION['NAME'] = "";
	$_SESSION['PHONE'] = "";
	$_SESSION['IDSTORE'] = "";
	$_SESSION['DEPO'] = "";
	$_SESSION['CLUSTER'] = "";
	session_destroy();
	header("location: login.php");
	exit;
?>
