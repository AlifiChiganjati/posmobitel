<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
$title='Confirmasi Topup Saldo';
$back_button='form_saldo.php';
include("cek_login.inc.php");
include("connection_main.php");
include("function.inc.php");
include("header_alt3.inc.php");
include("setting.inc.php");

$adm =0;
$saldo_p = isset($_POST['nominal'])? cleanall($_POST['nominal']) : "";
$nominal = str_replace(",","",$saldo_p);
$via = isset($_POST['via'])? cleanall($_POST['via']) : "";
if($via == "QRIS"){
  $adm = $nominal * 0.007;
}

$totalbyr = $nominal + $adm;

if(isset($_POST['order'])){
  $pin = isset($_POST['pin'])? cleanall($_POST['pin']) : "";

  if($pin == $pin_cnet){
		if(intval($totalbyr) < 10000000){
			$datex=date("Ymdhis");
			$no_faktur = "DEP-".$datex."-".str_shuffle($datex);
			//request payment
			$link ="https://api.xendit.co/qr_codes";
			$data = '{"external_id": "'.$no_faktur.'",
					"type": "DYNAMIC",
					"callback_url": "https://nexa.my.id/callback/qris.php",
					"amount": '.$totalbyr.',
					"metadata": { 
						"branch_area": "MOBITEL",
						"branch_city": "DIGITAL",
						"ptipe": "SALDO",
						"pcode": "S001",
						"pname": "SALDO",
						"poutlet": "'.$user.'",
						"tid": "'.$nomor.'"
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
			if($qr_string != ""){
				$sql = mysql_query("insert into validasi_saldo set tanggal=now(), user='$user', via='$via', nama='$name', jmldep='$nominal', 
							adm='$adm', no_faktur='$no_faktur', respon='$response', qrstring='$qr_string'");
				if($sql){
					echo "<script language=javascript> location.href = 'form_saldo_sukses.php?nf=$no_faktur'; </script>";
				}else{
					$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					  <div class='offcanvas-body small'>
						<div class='app-info'>
						  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						  <div class='content'>
							<h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							<a href='#'> Maaf sedang gangguan! </a>
						  </div>
						</div>
					  </div>
					</div>";
				}
			}else{
				$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					  <div class='offcanvas-body small'>
						<div class='app-info'>
						  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						  <div class='content'>
							<h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							<a href='#'> Maaf sedang gangguan! </a>
						  </div>
						</div>
					  </div>
					</div>";
			}
		}else{
			$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					  <div class='offcanvas-body small'>
						<div class='app-info'>
						  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						  <div class='content'>
							<h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							<a href='#'> Total maksimal 10.000.000! </a>
						  </div>
						</div>
					  </div>
					</div>";
		}
  }else{
    $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'> PIN Salah! </a>
          </div>
        </div>
      </div>
    </div>";
  }
}
?>

<main class="main-wrap setting-page mb-xxl">

      <form method="post">
       <section class="order-detail pt-0">
          <h3 class="title-2 text-center">Order Details</h3>
          <hr>
          <!-- Detail list <S</S>tart -->
          <div class="row">
            <div class="col-6">Nominal Saldo</div><div class="col-6 text-end"><?=number_format($nominal)?></div>
            <div class="col-6">Biaya Adm</div><div class="col-6 text-end"><?=number_format($adm)?></div>
            <div class="col-6">Total Bayar</div><div class="col-6 text-end"><strong><?=number_format($adm + $nominal)?></strong></div>
            <div class="col-6">Via</div><div class="col-6 text-end"><?=$via?></div>
            <input type="hidden" readonly="" value="<?=$adm?>" name="adm">
            <input type="hidden" readonly="" value="<?=$nominal?>" name="nominal">
            <input type="hidden" readonly="" value="<?=$via?>" name="via">
            <input type="hidden" name="token" required value="<?=$token?>">
          </div>
          
        </section>
        <div class="input-box">
          <label>PIN Transaksi</label>
          <input type="password" placeholder="Masukkan PIN" id="pin" name="pin" class="form-control" required="" autocomplete="off">
        </div>
        <div class="fixed-bottom bg-white">
      <div class="input-box mx-2 mb-2">
        <button type="submit" class="btn-solid bg-dark" name='order'> Order</button>
      </div>
    </div>
  </form>
</main>
 <?=$msg?>
<?php
include("footer_alt.inc.php");
?>
