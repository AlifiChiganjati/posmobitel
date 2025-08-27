<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$back_button = "home.php";
session_start();
$title = "REGISTRASI DIGITAL";
include("connection.inc.php");
include("function.inc.php");
include("session_check.php");
include("header_alt.inc.php");
$name = $_SESSION['NAME'];
$phone = $_SESSION['PHONE'];
$city = $_SESSION['CITY'];
$phone = $_SESSION['PHONE'];
	function cnetapi_regis($nohp,$nama,$kota){
		 $url = 'http://210.210.163.78:8092/registration2';
		 $postfield ="id=CN0025&pin=79400&user=CN0025&pass=55566&hp=$nohp&nama=$nama&kota=$kota";

		 $curl = curl_init();
						
		 curl_setopt_array($curl, array(
		   CURLOPT_URL => $url,
		   CURLOPT_RETURNTRANSFER => true,
		   CURLOPT_ENCODING => '',
		   CURLOPT_MAXREDIRS => 10,
		   CURLOPT_TIMEOUT => 0,
		   CURLOPT_FOLLOWLOCATION => true,
		   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		   CURLOPT_CUSTOMREQUEST => 'POST',
		   CURLOPT_POSTFIELDS => $postfield,
		   CURLOPT_HTTPHEADER => array(
			  'Content-Type: application/x-www-form-urlencoded',
			  'Cookie: PHPSESSID=b7dprt5lev82qtldgldbf9rln4'
		   ),
		 ));

		 $response = curl_exec($curl);

		 curl_close($curl);
		 $rs= json_decode($response, true);

		 $status = $rs['status'];
		 $pesan= $rs['message'];
		 if($status){
			  $agenid = $rs['agenid'];
			  $pasword = $rs['password'];
			  $pin = $rs['pin_transaksi'];
			  $h2h_user = $rs['h2h_user'];
			  $h2h_pass = $rs['h2h_password'];
			  $res = "1;$agenid;$pasword;$pin;$h2h_user;$h2h_pass;$response;$pesan";
		 }else{
			  $res = "0;0;0;registrasi ke cnet gagal $response;$postfield;0;0;$pesan";
		 }
		 return $res;
	}
if(strlen($phone) > 0){
	$regis = cnetapi_regis($phone,$name,$city);
	mysql_query("insert into callback set response = '$regis', sender='cnet', time=now()");
	$hasil = explode(";",$regis);
	if($hasil[0] == 1){
		$cnet_id = $hasil[1];
		$cnet_passreg = $hasil[2];
		$pin = $hasil[3];
		$h2h_user = $hasil[4];
		$h2h_pass = $hasil[5];
		$resp = $hasil[6];
		$notif = $hasil[7];

		//save ke customers digital
		$rrt = "insert into customers_digital set user='$user', cnet_id = '$cnet_id', cnet_passreg = '$cnet_passreg', pin = '$pin', h2h_user = '$h2h_user', 
					h2h_pass = '$h2h_pass', resp = '$resp', email='', no_hp='$phone', name='$name'";
		if(mysql_query($rrt)){
			$pesan='Selamat datang, <strong>PIN TRANSAKSI ANDA: '.$hasil[3].'</strong> <br> Mohon diingat untuk bisa melakukan transaksi selanjutnya!';
		}else{
			$pesan = $notif;
		}
	}else{
		$notif = $hasil[7];
		$pesan=$notif;
	}
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
	  <div class='offcanvas-body small'>
		<div class='app-info'>
		  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
		  <div class='content'>
			<h3>$nox <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
			<a href='#'>$pesan</a>
		  </div>
		</div>
	  </div>
	</div>";
}else{
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
	  <div class='offcanvas-body small'>
		<div class='app-info'>
		  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
		  <div class='content'>
			<h3>$nox <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
			<a href='#'>No HP masih kosong!</a>
		  </div>
		</div>
	  </div>
	</div>";
}
?>
﻿<?=$msg?>

<?php
include("footer.inc.php");
?>
