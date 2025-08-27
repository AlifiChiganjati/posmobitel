<?php
session_start();
if (isset($_SESSION['IDSALES']) && strlen($_SESSION['IDSALES']) > 0)  {
		header("location: home.php");
		exit;
}
?>

<!DOCTYPE html>
<!-- Html Start -->
<html lang="en">
  <!-- Head Start -->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="MOBITEL">
    <meta name="keywords" content="SARA">
    <meta name="author" content="MOBITEL">
    <link rel="manifest" href="manifest.json">
    <title>POSMOBITEL LOGIN</title>
    <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="assets/images/logo/favicon.png">
    <meta name="theme-color" content="#0baf9a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="MOBITEL">
    <meta name="msapplication-TileImage" content="assets/images/logo/favicon.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" id="rtl-link" type="text/css" href="assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/iconly.css">
    <link rel="stylesheet" id="change-link" type="text/css" href="assets/css/style.css">
  </head>
  <body>
  <?php
  include("function.inc.php");
  include("connection_main.php");
  $username = cleanall($_POST['username']);

function postData($phone, $otp){
	if(substr($phone, 0, 1) == "0"){
	  $hp = "+62". substr($phone,  1);
	}else if(substr($phone, 0, 2) == "62"){
		$hp = "+". $phone;
	}
	$newurl = "https://conversations.messagebird.com/v1/send";

	$headers      = array(
	  'Content-Type: application/json; charset=utf-8',
	 'Authorization: AccessKey FdTykqPJw81C9PgHZnghOGP8E'
	);
	
		$request_body = '{
			"content": {
				"hsm": {
					"language": {
						"code": "id"
					},
					"components": [{
							"type": "header",
							"parameters": [{
								"type": "image",
								"image": {
									"url": "https://nexa.id/wp-content/uploads/2023/07/MOBITELx.png"
								}
							}]
						},
				{
				  "type": "body",
				  "parameters": [
					{
					  "type": "text",
					  "text": "'.$otp.'"
					}
				  ]
				}
			  ],
					"namespace": "abb06920_3fc1_4856_ae2a_e024d62a6350",
					"templateName": "otpmobitel01"
				}
			},
			"to": "'.$hp.'",
			"type": "hsm",
			"from": "bcda3ff5-1a8b-4dc7-adf9-5a85e2ba920b"
		}';
				  
	$c = curl_init ($newurl);
	curl_setopt ($c, CURLOPT_URL, $newurl);
	curl_setopt ($c, CURLOPT_POST, true);
	curl_setopt ($c, CURLOPT_HTTPHEADER, $headers);
	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $request_body);

	$response = curl_exec ($c);
	curl_close ($c);

	return $response;
}

  $password = cleanall($_POST['password']);
  $from =  $_SERVER['REMOTE_ADDR'];
  //$jum_usr = strlen($username);
  //echo $jum_usr;
    if ($username != "" ) {
      //if (strpos($username, "@") !== false){
	  	//customer login dengan no HP
		if(is_numeric($username) == 1){
			if(strlen($username) > 8){
				$oRs = mysql_query("select * from customer_mobitel where nohp='$username' and status='1'");
				$passEnkrip = $password; 
				
					if ($row_oRs = mysql_fetch_object($oRs)) {
					  if ($password != "") {
						$curr_pin = $row_oRs->pinlogin;
						if($curr_pin == $passEnkrip){
							
							if($passEnkrip == 123456){
								//otp
								$angka = rand(1000, 9999);
								$otp = str_shuffle($angka);
								if(substr($otp,0,1) == "0"){
									$depan = rand(1,9);
									$belakang = substr($otp,1);
									$otp = $depan.$belakang;
								}
								$datex = date("Y-m-d");
								$xx1 = "select id from otp where username = '$username' and date(tanggal) ='$datex'";
								$sqlcek = mysql_query($xx1);
								$jumcek = mysql_num_rows($sqlcek);
								if($jumcek > 2){
									$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
										  <div class='offcanvas-body small'>
											<div class='app-info'>
											  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
											  <div class='content'>
												<h3>$nox <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
												<a href='#'>Melebihi batas reques OTP max 2x</a>
											  </div>
											</div>
										  </div>
										</div>";
								}else{
									if(substr($username,0,2) == "08" or substr($username,0,3) == "628"){
										$qpi = postData($username, $otp);
									}else{
										$qpi = "no hp salah";
									}
									$sql ="insert into otp set username = '$username', tanggal=now(), no_otp = '$otp', status=0, response='$qpi'";
									if(mysql_query($sql)){
										echo "<script language=javascript> location.href = 'otp.php?usr=$username'; </script>";
									}else{
										echo $sql;
									}
								}
							}else{
								
								session_set_cookie_params(28800);
								session_start();
    
								$_SESSION['USER'] = $row_oRs->nohp;
								$_SESSION['NAME'] = $row_oRs->nama;
								$_SESSION['ROLE'] = "CUSTOMER";
								$_SESSION['ROLES'] = "CUSTOMER";
								$_SESSION['IDSALES'] = $row_oRs->idsales_rti;
								$_SESSION['IDSALES_CESA'] = $row_oRs->idsales_cesa;
								$_SESSION['CITY'] = $row_oRs->city;
								$_SESSION['PHONE'] = $row_oRs->nohp;
								$_SESSION['GUDANG_RTI'] = $row_oRs->gudang_rti;
								$_SESSION['GUDANG_RAI'] = $row_oRs->gudang_rai;
								$_SESSION['IDOUTLET_RTI'] = $row_oRs->id_outlet_rti;
								$_SESSION['IDOUTLET_RAI'] = $row_oRs->id_outlet_rai;

								$uxdata = ['uxname' => $username, 'pxwd' => $password];
								$user = serialize($uxdata);
								setcookie('mobitelx', $user, time() + (86400 *30));

								header("Cache-Control: no-store, no-cache, must-revalidate");
								header("Pragma: no-cache");
								echo "<script language=javascript> location.href = 'home.php'; </script>";
							}
							
							
						}else{
						  echo "<script language=javascript> alert('Password Gagal!'); </script>";
					   }
						mysql_free_result($oRs);
					  }
					}else{
					  echo "<script language=javascript> alert('Login Gagal! $username'); </script>";
					}
			}else{
				mysql_close();
				include("connection_cesa.inc.php");
				$oRs = mysql_query("select * from salesman where idsales='$username'");
				$passEnkrip = $password;      
				if ($row_oRs = mysql_fetch_object($oRs)) {
				  if ($password != "") {
					$curr_pin = $row_oRs->pinlogin;
					if($curr_pin == $passEnkrip){
					  session_set_cookie_params(28800);
					  session_start();
					  $_SESSION['USER'] = $row_oRs->idsales;
					  $_SESSION['NAME'] = $row_oRs->name;
					  $_SESSION['ROLE'] = "CESA";
					  $_SESSION['ROLES'] = $row_oRs->role;
					  $_SESSION['IDSALES'] = $row_oRs->idsales;
					  $_SESSION['DEPO'] = $row_oRs->depo;
					  $_SESSION['IDSTORE'] = $row_oRs->id_store;
					  $_SESSION['STORE'] = $row_oRs->id_store;
					  $_SESSION['CITY'] = $row_oRs->depo;
					  $_SESSION['PHONE'] = $row_oRs->phone;

					  $uxdata = ['uxname' => $username, 'pxwd' => $password];
					  $user = serialize($uxdata);
					  setcookie('mobitelx', $user, time() + (86400 *30));

					  header("Cache-Control: no-store, no-cache, must-revalidate");
					  header("Pragma: no-cache");
					  echo "<script language=javascript> location.href = 'home.php'; </script>";
					}else{
					  echo "<script language=javascript> alert('Password Gagal!'); </script>";
				   }
					mysql_free_result($oRs);
				  }
				}
			}
		
      	}else{
			mysql_close();
			if(stripos($username,"JBL") !== false){
				include("connection_jbl.inc.php");
				$oRs = mysql_query("select * from salesman where idsales='$username'");
				$passEnkrip = $password;      
				if ($row_oRs = mysql_fetch_object($oRs)) {
					if ($password != "") {
						$curr_pin = $row_oRs->pinlogin;
						if($curr_pin == $passEnkrip){
							session_set_cookie_params(28800);
							session_start();
    					$_SESSION['IDEMPLOYEE'] = $row_oRs->idemployee;
							$_SESSION['USER'] = $row_oRs->idsales;
							$_SESSION['NAME'] = $row_oRs->name;
							$_SESSION['ROLE'] = "JBL SF";
							$_SESSION['ROLES'] = $row_oRs->role;
							$_SESSION['IDSALES'] = $row_oRs->idsales;
							$_SESSION['DEPO'] = $row_oRs->depo;
							$_SESSION['IDSTORE'] = $row_oRs->id_store;
							$_SESSION['STORE'] = $row_oRs->id_store;
							$_SESSION['CITY'] = $row_oRs->depo;
							$_SESSION['PHONE'] = $row_oRs->phone;

							$uxdata = ['uxname' => $username, 'pxwd' => $password];
							$user = serialize($uxdata);
							setcookie('mobitelx', $user, time() + (86400 *30));

							header("Cache-Control: no-store, no-cache, must-revalidate");
							header("Pragma: no-cache");
							if($curr_pin==123456){
								echo "<script language=javascript> location.href = 'ganti_pin.php'; </script>";
							}else{
								echo "<script language=javascript> location.href = 'home.php'; </script>";
							}
						}else{
							echo "<script language=javascript> alert('Password Gagal!'); </script>";
						}
						mysql_free_result($oRs);
					}
				}
			}else{
				include("connection_cesa.inc.php");
				$oRs = mysql_query("select * from salesman where idsales='$username'");
				$passEnkrip = $password;      
				if ($row_oRs = mysql_fetch_object($oRs)) {
					if ($password != "") {
						$curr_pin = $row_oRs->pinlogin;
						if($curr_pin == $passEnkrip){
							session_set_cookie_params(28800);
							session_start();
							$_SESSION['USER'] = $row_oRs->idsales;
							$_SESSION['NAME'] = $row_oRs->name;
							$_SESSION['ROLE'] = "CESA";
							$_SESSION['ROLES'] = $row_oRs->role;
							$_SESSION['IDSALES'] = $row_oRs->idsales;
							$_SESSION['DEPO'] = $row_oRs->depo;
							$_SESSION['IDSTORE'] = $row_oRs->id_store;
							$_SESSION['STORE'] = $row_oRs->id_store;
							$_SESSION['CITY'] = $row_oRs->depo;
							$_SESSION['PHONE'] = $row_oRs->phone;

							$uxdata = ['uxname' => $username, 'pxwd' => $password];
							$user = serialize($uxdata);
							setcookie('mobitelx', $user, time() + (86400 *30));

							header("Cache-Control: no-store, no-cache, must-revalidate");
							header("Pragma: no-cache");
							if($curr_pin==123456){
								echo "<script language=javascript> location.href = 'ganti_pin.php'; </script>";
							}else{
								echo "<script language=javascript> location.href = 'home.php'; </script>";
							}
						}else{
							echo "<script language=javascript> alert('Password Gagal!'); </script>";
						}
						mysql_free_result($oRs);
					}
				}else{
					mysql_close();
					include("connection_rti.inc.php");
					$tt = "select * from salesman where idsales='$username'";
					$oRs = mysql_query($tt);
					$passEnkrip = $password;      
					if ($row_oRs = mysql_fetch_object($oRs)) {
						if ($password != "") {
							$curr_pin = $row_oRs->pinlogin;
							if($curr_pin == $passEnkrip){
								session_set_cookie_params(28800);
								session_start();
								$_SESSION['USER'] = $row_oRs->idsales;
								$_SESSION['NAME'] = $row_oRs->name;
								$_SESSION['ROLE'] = "RTI/RAI";
								$_SESSION['ROLES'] = $row_oRs->role;
								$_SESSION['IDSALES'] = $row_oRs->idsales;
								$_SESSION['DEPO'] = $row_oRs->depo;
								$_SESSION['CITY'] = $row_oRs->depo;
								$_SESSION['PHONE'] = $row_oRs->phone;
								$_SESSION['IDSTORE'] = $row_oRs->id_store;

								$uxdata = ['uxname' => $username, 'pxwd' => $password];
								$user = serialize($uxdata);
								setcookie('mobitelx', $user, time() + (86400 *30));

								header("Cache-Control: no-store, no-cache, must-revalidate");
								header("Pragma: no-cache");
								if($curr_pin==123456){
									echo "<script language=javascript> location.href = 'ganti_pin.php'; </script>";
								}else{
									echo "<script language=javascript> location.href = 'home.php'; </script>";
								}
							}else{
								echo "<script language=javascript> alert('Password Gagal!'); </script>";
							}
							mysql_free_result($oRs);
						}
					}else{
						echo "<script language=javascript> alert('Login Gagal! $username'); </script>";
					}
				}
			}
		
      	}  
    }
  ?>

  <?php
   if(!isset($_COOKIE['mobitelx'])) {
     $username = "";
     $password = "";
   } else {
      $user = unserialize($_COOKIE['mobitelx']);
      $username = $user['uxname'];
      $password = $user['pxwd'];
    }
  ?>
    <div class="bg-pattern-wrap ratio2_1">
      <!-- Background Image -->
      <div class="bg-patter">
        <img src="menu_icons/bg-pattern2.png" class="bg-img" alt="pattern" width='10%'>
      </div>
    </div>
    <!-- Main Start -->
    <main class="main-wrap login-page mb-xxl">
      <!--<img class="logo" src="assets/images/logo/logo.png" alt="logo">
      <img class="logo logo-w" src="assets/images/logo/logo-w.png" alt="logo">
       Login Section Start -->
       <div class='text-center'><img src="menu_icons/MOBITEL.png" width='50%'></div>

      <section class="login-section p-0">
        <!-- Login Form Start -->
        <form class="custom-form" method="Post">
          <!-- Email Input start -->
            <br />
          <div class="input-box">
            <input type="text" placeholder="Username" name="username" required="" class="form-control" value="<?=$username?>" autocomplete="Off">
          </div>
          <!-- Email Input End -->
          <!-- Password Input start -->
          <div class="input-box">
            <input type="password" placeholder="Password" name="password" required="" class="form-control" value="<?=$password?>">
            <i class="iconly-Hide icli showHidePassword"></i>
          </div>
          <!-- Password Input End -->
          <button type="submit" class="btn-solid bg-dark">Masuk</button>
          </form>
        <!-- Login Form End -->
      </section>
      <!-- Login Section End -->
    </main>
    <div class='fixed-bottom bg-white'>
        <div class='input-box mx-2 text-center'>
          Created & developed by NX Solution
        </div>
    </div>
	<?=$msg?>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/theme-setting.js"></script>
    <script src="assets/js/script.js"></script>
  </body>
</html>
