<?php
	if (strlen($_SESSION['IDSALES']) == 0)  {
		header("location: login.php");
		exit;
	}
?>
