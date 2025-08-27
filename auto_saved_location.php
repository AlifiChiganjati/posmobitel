<?php
session_start();
include("connection_rti.inc.php");
include("function.inc.php");
$user= $_SESSION['USER'];
$role = $_SESSION['ROLE'];
$name = $_SESSION['NAME'];

$idsales = $user;
$longlat = isset($_POST['longlat'])? cleanall($_POST['longlat']) : "";
$address = isset($_POST['address'])? cleanall($_POST['address']) : "";

if($idsales && $longlat && $address){
	
	$sql = "SELECT * from sales_check_in_log where id_salesman = '$idsales' and (DATE_ADD(date_in, INTERVAL 2 MINUTE) > NOW()) order by id desc limit 1";
	$mql = mysql_query($sql);
	$roo = mysql_fetch_object($mql);
	
	if($roo){
		//abaikan krn data udh ada .. tunggu 5 menit lg
		echo " -- existed --";
	}else{
		mysql_query("insert into sales_check_in_log set date_in= now(), date_on= now(), id_salesman='$idsales', name='$name', unit_bisnis='$role', description='AUTO', location='$longlat', address='$address'");
		echo " --- OK --- ";
	}	
	
	//proses ke sales_check_in
	$sqlo = "SELECT * from sales_check_in where id_salesman = '$idsales'";
	$mqlo = mysql_query($sqlo);
	$sooo = mysql_fetch_object($mqlo);
	
	if($sooo){
		mysql_query("update sales_check_in set date_in= now(), location='$longlat', address='$address' where id=$sooo->id");		
	}else{
		mysql_query("insert ignore into sales_check_in set date_in= now(), id_salesman='$idsales', name='$name', unit_bisnis='$role', description='AUTO', location='$longlat', address='$address'");
	}	
	
} else {
	echo " -- e --";
}

?>