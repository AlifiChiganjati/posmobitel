<?php
session_start();
//include("session_check.php");
$user = $_SESSION['IDSALES'];
$id_prod = base64_decode(base64_decode($_GET['prd']));
$merk = $_GET['merk'];
$cat = $_GET['cat'];
if($merk == "xiaomi"){
	include("connection_rti.inc.php");
}else if($merk == "smartfren"){
	include("connection_cesa.inc.php");
}else if($merk == "accessories"){
	include("connection_cw.inc.php");
}else{
	include("connection_rai.inc.php");
}
if(mysql_query("delete from order_temp where id_produk='$id_prod' and user ='$user'")){
	echo "<script language=javascript> location.href = 'cart.php?merk=$merk&cat=$cat'; </script>";
	//echo "oke";
}else{
	echo "gagal hapus";
}


?>