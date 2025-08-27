<!DOCTYPE html>
<!-- Html Start -->
<html lang="en">
  <!-- Head Start -->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SARA">
    <meta name="keywords" content="SARA">
    <meta name="author" content="SARA">
    <link rel="manifest" href="manifest.json">
    <title>POSMOBITEL LOGIN</title>
    <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="assets/images/logo/favicon.png">
    <meta name="theme-color" content="#0baf9a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="SARA">
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
  $user = "085339311603";
  
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
  
  if(isset($_POST['resend'])){
		$angka = rand(1000, 9999);
		$otp = str_shuffle($angka);
		if(substr($otp,0,1) == "0"){
			$depan = rand(1,9);
			$belakang = substr($otp,1);
			$otp = $depan.$belakang;
		}
		$datex = date("Y-m-d");
		$xx1 = "select id from otp where username = '$user' and date(tanggal) ='$datex'";
		$sqlcek = mysql_query($xx1);
		$jumcek = mysql_num_rows($sqlcek);
		if($jumcek > 2){
			$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				  <div class='offcanvas-body small'>
					<div class='app-info'>
					  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
					  <div class='content'>
						<h3>$nox <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						<a href='#'>Melebihi batas reques OTP max 2x, silahkan hubungi administrator</a>
					  </div>
					</div>
				  </div>
				</div>";
		}else{
			$qpi = postData($user, $otp);
			$sql ="insert into otp set username = '$user', tanggal=now(), no_otp = '$otp', status=0, response='$qpi '";
			if(mysql_query($sql)){			
				$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				  <div class='offcanvas-body small'>
					<div class='app-info'>
					  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
					  <div class='content'>
						<h3>$nox <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						<a href='#'>Request ulang OTP berhasil, silahkan cek Whatsapp Anda</a>
					  </div>
					</div>
				  </div>
				</div>";
			}else{
				echo $sql;
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

        <form class="custom-form" method="post">
		<br>
          <h1 class="font-md title-color fw-600">Verification Code</h1>

          <div class="countdown mb-md">
            <div class="input-box otp-input">
              <input class="otp form-controll" name="txt1" placeholder="0" type="tel" required="" oninput="digitValidate(this)" onkeyup="tabChange(1)" maxlength="1">
              <input class="otp form-controll" name="txt2" placeholder="0" type="tel" required="" oninput="digitValidate(this)" onkeyup="tabChange(2)" maxlength="1">
              <input class="otp form-controll" name="txt3" placeholder="0" type="tel" required="" oninput="digitValidate(this)" onkeyup="tabChange(3)" maxlength="1">
              <input class="otp form-controll" name="txt4" placeholder="0" type="tel" required="" oninput="digitValidate(this)" onkeyup="tabChange(4)" maxlength="1">
            </div>
          </div>
          <button type="submit" class="btn-solid" name='kirim'>Submit</button>
		  </form>
          <form method="post">
			  <div class="otp-countdown text-center">
				<div class="content-color">
				  <button class="resend-otp text-primary" name='resend'><u>Resend OTP</u></button>
				</div>
			  </div>
			</form>
        
      </section>
    </main>
    <?=$msg?>
    <div class='fixed-bottom bg-white'>
        <div class='input-box mx-2 text-center'>
          Created & developed by NX Solution
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/otp.js"></script>
    <script src="assets/js/theme-setting.js"></script>
    <script src="assets/js/script.js"></script>
  </body>
  <!-- Body End -->
</html>
<!-- Html End -->
