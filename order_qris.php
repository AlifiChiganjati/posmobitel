<?php
session_start();
include("session_check.php");
include("connection_cw.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
include('php-qrcode/qrlib.php');

$depo = "GROSIR";
$idsales = $_SESSION['IDSALES'];
$curdate = date("ymdHis");
$transdate = date("y-m-d H:i:s");
$nofaktur = isset($_GET['no_faktur'])? cleanall($_GET['no_faktur']) : "";
$totalbyr = isset($_GET['totalbyr'])? cleanall($_GET['totalbyr']) : "";
$idcustomer = isset($_GET['idcustomer'])? cleanall($_GET['idcustomer']) : "";
$coa = "1136930100"; 
$via = "QRIS PAYMENT"; //CELLULAR WORLD

if($nofaktur && $totalbyr) {
	$link = "https://api.xendit.co/qr_codes";
			$data = '{
						"external_id": "'.$nofaktur.'",
						"type": "DYNAMIC",
						"callback_url": "https://nexa.my.id/callback/qris.php",
						"amount": "'.$totalbyr.'",
						"metadata": {
							"branch_area": "MOBITEL",
							"branch_city": "ACCESSORIES",
							"ptipe": "accessories",
							"pcode": "accessories",
							"pname": "accessories",
							"poutlet": "'.$idsales.'",
							"tid": "'.$curdate.'"
						}
					}';
			
			$header=array(
				"accept: application/json",
				"Authorization: Basic eG5kX3Byb2R1Y3Rpb25fd3FNVXhIYVZ5cUJvNGVJNDNBWUlmdTRTc2Rlejcwc25VR0dOcnJtMFNPQ0NHdzBDYmpSckdZcVZWbXpPZ3E6Og==",
				"content-type: application/json"
			  );
			$curl = curl_init();

			  curl_setopt_array($curl, array(
			  CURLOPT_URL => $link,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_SSL_VERIFYHOST => 0,
			  CURLOPT_SSL_VERIFYPEER => 0,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => $data,
			  CURLOPT_HTTPHEADER => $header
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			$rs = json_decode($response,true);
			$qr_string = $rs['qr_string'];
	        $tempdir="qris/cw/";
			$file_name= $nofaktur.".png";
	        $files = $tempdir.$file_name;

			if (!file_exists($tempdir))
			mkdir($tempdir, 0755);
			chmod($tempdir, 0755);

			QRcode::png($qr_string, $files, 4, 6, 4);
			
			
			echo "<div class='text-center m-1'>
			<h2>Total Bayar</h2><br><h3># $totalbyr #</h3>
			
			<img src='$files' width='50%'><br>
			Pembayaran maksimal 2 jam setelah order.</div>";	


			$xx = "select * from order_temp where user='$idsales'";
			$qryx = mysql_query($xx);
			while($datax=mysql_fetch_object($qryx)){
				$qryz = "insert into order_det set no_faktur='$nofaktur', tanggal_order = now(), kode_produk='$datax->id_produk', nama_produk='$datax->nama_produk', 
							qty = $datax->qty, harga=$datax->harga, sales='$idsales', via = '$via', coa='$coa', id_customer='$idcustomer', diskon=$datax->diskon";
				if(mysql_query($qryz)){
					mysql_query("delete from order_temp where id_produk='$datax->id_produk' and user='$idsales'");
					$sts = true;
				}else{
					$sts = false;
					$msg .= "error $datax->id; ";
				}
							
			}
			

}else{
	echo "<div class='text-center m-1'><h2>Order Gagal!</h2><br>
	<img src='menu_icons/approval.png' width='50%'><br>
	Tampaknya ada kendala internal. Mohon dicoba kembali</div>";
	
}


include("footer.inc.php");
?>
