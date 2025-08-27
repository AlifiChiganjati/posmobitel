<?php
$merk = $_GET['merk'];
$title = "Notifikasi Saldo";
session_start();
include("session_check.php");
include("connection_main.php");
include("function.inc.php");
include("header_alt2.inc.php");
$no_faktur = $_GET['nf'];
$qryx = "select * from validasi_saldo where no_faktur='$no_faktur'";
$sqlx = mysql_query($qryx);
$datax = mysql_fetch_object($sqlx);
$qr_string = $datax->qrstring;
include('assets/phpqrcode/qrlib.php'); 
$files = "qr/".$no_faktur.".png";
QRcode::png($qr_string,$files);

echo "<div class='text-center m-1'><h2>Order Saldo Berhasil!</h2><br><h3># $no_faktur #</h3><img src='$files' width='50%'><br>
Silahkan scan qr code di atas untuk pembayaran.</div>";
include("footer.inc.php");
?>
