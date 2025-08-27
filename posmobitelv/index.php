<?php
session_start();
include("session_check.php");
include("connection_main.php");
include("function.inc.php");
include("header.inc.php");

$nox = isset($_GET['nox']) ? $_GET['nox'] : "";
$rex1 = isset($_GET['rex1']) ? $_GET['rex1'] : ""; //berhasil

if($_SESSION['ROLE'] == "CESA"){
	$sql = "select * from menu_group where nama_group != 'SMARTFREN'";
}else{
	$sql = "select * from menu_group where nama_group != 'SMARTFREN SF'";
}

//cek id cnet
$sql_cek = mysql_query("select name from customers_digital where user ='$user'");
$ceks = mysql_num_rows($sql_cek);
if($ceks == 2){
	function cnetapi_regis($nohp,$nama,$kota){
	     $url = 'http://210.210.163.78:8092/registration';
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
	          $res = "1;$agenid;$pasword;$pin;$h2h_user;$h2h_pass;$response";
	     }else{
	          $res = "0;0;0;registrasi ke cnet gagal $response;$postfield;0;0";
	     }
	     return $res;
	}

	$regis = cnetapi_regis($phone,$name,$city);
	$hasil = explode(";",$regis);
	if($hasil[0] == 1){
		$cnet_id = $hasil[1];
		$cnet_passreg = $hasil[2];
		$pin = $hasil[3];
		$h2h_user = $hasil[4];
		$h2h_pass = $hasil[5];
		$resp = $hasil[6];

		//save ke customers digital
		mysql_query("insert into customers_digital set user='$user', cnet_id = '$cnet_id', cnet_passreg = '$cnet_passreg', pin = '$pin', h2h_user = '$h2h_user', 
					h2h_pass = '$h2h_pass', resp = '$resp',email='',no_hp='$phone',name='$name'");
		$pesan='Selamat datang, <strong>PIN TRANSAKSI ANDA: '.$hasil[3].'</strong> <br> Mohon diingat untuk bisa melakukan transaksi selanjutnya!';
	}else{
		$pesan=$regis;
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
}
?>
<style>
#loading {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background-color: white;
}
.textLoader{
  position: fixed;
  top: 40%;
}
</style>
<div id="loading">
          <span class="loader"></span>
          <div class="textLoader">
              <center>
                <img src="menu_icons/mobitels.gif" width="50%">
                <!--<br><br>
                <b>Please Wait ... </b>-->
              </center>
          </div>
    </div>    <!-- Main Start -->
    <main class="main-wrap index-page mb-xxl">
	    <section class="banner-section ratio2_1">
	        <div class="h-banner-slider">
	          <div>
	            <div class='banner-box'>
	              <img src='banner/image1.png' alt='banner' class='bg-img'>
	            </div>
	          </div>
	          <div>
	            <div class='banner-box'>
	              <img src='banner/image2.jpg' alt='banner' class='bg-img'>
	            </div>
	          </div>
	          <div>
	            <div class='banner-box'>
	              <img src='banner/image1.jpg' alt='banner' class='bg-img'>
	            </div>
	          </div>
	        </div>
      	</section>

		<a class='btn btn-dark mb-2 btn-sm' href='form_saldo.php'><small>Saldo 12.000</small></a>

		<div class="accordion" id="accordionExample">
			<?php
				
				$qry = mysql_query($sql);
				while($data=mysql_fetch_object($qry)){
					$id = $data->id;
					echo "<div class='accordion-item'>
							<h2 class='accordion-header' id='heading$id'>
							<button class='accordion-button font-md title-color collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse$id' aria-expanded='false' aria-controls='collapseOneXX'>
								$data->nama_group
							</button>
							</h2>
							<div id='collapse$id' class='accordion-collapse collapse' aria-labelledby='heading$id' data-bs-parent='#accordionExample' style=''>
							<div class='accordion-body'>
								<section class='category pt-0'>
									<div class='mb-2'>
										<div class='row gy-sm-4 gy-2 mb-1'>";

										$qry2 = mysql_query("select * from apps_menu where id in ($data->id_menu) and status=1");
										while($data2=mysql_fetch_object($qry2)){	
											$link = $data2->link;
											$img = $data2->img;
											$menu = $data2->menu;
											echo "<div class='col-3'>
													<div class='category-wrap'>
														<div class='bg-shape'></div>
															<a href='$link'> <img class='category img-fluid' src='iconm/$img' alt='category'> </a>
														<span class='title-color'><small>$menu</small></span>
													</div>
												</div>";	
										}

									echo "</div>
									</div>
								</section>
							</div>
							</div>
			          	</div>";

				}
			?>
        </div>
    </main>
    <!-- Main End -->

    <?=$msg?>
<script>
var delay = 1500;
$(window).on('load', function() {
    setTimeout(function(){
        $("#loading").hide();
    },delay);
});
</script>
<?php
include("footer.inc.php");
?>
