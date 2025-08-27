<?php
$merk = $_GET['merk'];
$title = "Notifikasi Order";
session_start();
include("session_check.php");
if($merk == "xiaomi"){
	include("connection_rti.inc.php");
	$basez="https://nexa.my.id/posrti/";
	$ub = "RTI";
}else if($merk == "smartfren"){
	include("connection_cesa.inc.php");
	$basez="https://nexacloud.id/nexacesa/";
	$ub = "CESA";
}else if($merk == "accessories"){
	include("connection_cw.inc.php");
	$basez="https://nexacloud.id/posretail/";
	$ub = "CW";
}else{
	include("connection_rai.inc.php");
	$basez="https://nexa.my.id/posrai/";
	$ub="RAI";
}
include("function.inc.php");
include("header_alt2.inc.php");
$no_faktur = $_GET['nf'];
$qry = "select * from order_det where no_faktur='$no_faktur'";
$sql = mysql_query($qry);
while($data = mysql_fetch_object($sql)){
	$norek = $data->via;
}
echo "<div class='text-center m-1'><h2>Order Berhasil!</h2><br><h3># $no_faktur #</h3><img src='menu_icons/approval.png' width='50%'><br>
Silahkan transfer ke rekening<br><strong>$norek</strong><br>maksimal 2 jam setelah order.</div>";
include("footer.inc.php");
?>
