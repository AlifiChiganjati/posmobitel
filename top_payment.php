<?php
$title = "TOP PAYMENT";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");

$idsales = $_SESSION['IDSALES'];
$depo = $_SESSION['DEPO'];
$clustero = $_SESSION['CLUSTER'];
$cluster = cluster($clustero);



if($_POST['cek_status'] == "CEK STATUS"){
  $no_faktur = isset($_POST['no_faktur'])? cleaninput($_POST['no_faktur']) : "";

  if($no_faktur){

		$arrData = cek_nofaktur($no_faktur);
		if($arrData){
				$department = $arrData->depo;
				$cluster = $arrData->cluster;

				//$department = "";
				//$cluster = "";

				$arr = aol_auth();
		    $headers = array(
		        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
		        'Authorization: Bearer '. $arr->bearer,
		        'X-Session-ID: '.$arr->session
		        );

		      $path = "/accurate/api/sales-invoice/list.do?fields=number,id,primeReceipt,totalAmount,status,primeOwing,customer&filter.keywords=".$no_faktur;
		      $newurl = $arr->baseurl . $path;

		      $c = curl_init ($newurl);
		      curl_setopt ($c, CURLOPT_URL, $newurl);
		      curl_setopt ($c, CURLOPT_HTTPHEADER, $headers);
		      curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
		      curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, false);

		      $response = curl_exec ($c);
		      curl_close ($c);
		      $jsons = json_decode($response, TRUE);

		        if($jsons['s'] == "true"){
							$jmldata = count($jsons['d']);
							if($jmldata > 0){
								for($i=0; $i < count($jsons['d']); $i++){
		              $totalAmount = $jsons['d'][$i]['totalAmount'];
		              $primeOwing = $jsons['d'][$i]['primeOwing'];
		              $primeReceipt = $jsons['d'][$i]['primeReceipt'];

									$totalAmount_real = $totalAmount;
		              $primeOwing_real = $primeOwing;
									$primeReceipt_real = $primeReceipt;

		              $totalAmount = number_format($totalAmount);
		              $primeOwing = number_format($primeOwing);
		              $primeReceipt = number_format($primeReceipt);

									$customer_name = $jsons['d'][$i]['customer']['name'];
									$customer = $jsons['d'][$i]['customer']['customerNo'];

                  $listdata .= "<li>
                    <span>Nama Customer</span>
                    <span>$customer_name</span>
                  </li>";

                  $listdata .= "<li>
                    <span>No Customer</span>
                    <span>$customer</span>
                  </li>";

                  $listdata .= "<li>
                    <span>Total Penjualan</span>
                    <span>$totalAmount</span>
                  </li>";

                  $listdata .= "<li>
                    <span>Terbayar</span>
                    <span>$primeReceipt</span>
                  </li>";

                  $listdata .= "<li>
                    <span>Sisa Utang</span>
                    <span>$primeOwing</span>
                  </li>";


		            }
							}else{

               $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
           				<div class='offcanvas-body small'>
           					<div class='app-info'>
           						<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
           						<div class='content'>
           							<h3>No Faktur <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
           							<a href='#'>Rincian tagihan tidak ditemukan. Pastikan nomor yang anda input sudah benar!</a>
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
           							<h3>Gagal <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
           							<a href='#'>Rincian tagihan tidak ditemukan. Silahkan koordinasi ke admin untuk lebih lanjut</a>
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
               <h3>Gagal <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
               <a href='#'>Data faktur tidak ditemukan!</a>
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
             <h3>Gagal <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
             <a href='#'>Nomor faktur tidak boleh kosong!</a>
           </div>
         </div>
       </div>
     </div>";
  }

	if($primeOwing == 0){
		$dis = "disabled";
	}
}


if($_POST['bayar'] == "BAYAR"){
  $no_faktur = isset($_POST['no_faktur'])? cleaninput($_POST['no_faktur']) : "";
	$jml_bayar = isset($_POST['jml_bayar'])? intval($_POST['jml_bayar']) : "";
	$amount = isset($_POST['amount'])? intval($_POST['amount']) : "";
	$owing = isset($_POST['owing'])? intval($_POST['owing']) : "";
	$receipt = isset($_POST['receipt'])? intval($_POST['receipt']) : "";
	$via = isset($_POST['via'])? cleaninput($_POST['via']) : "";

	$customer = isset($_POST['customer'])? cleaninput($_POST['customer']) : "";
	$department = isset($_POST['department'])? cleaninput($_POST['department']) : "";
	$cluster = isset($_POST['cluster'])? cleaninput($_POST['cluster']) : "";

	if($no_faktur && $owing && $jml_bayar && $amount && $via && $customer && $department && $cluster){
		$sql = "insert into sales_top (tanggal, idsalesman, id_customer, no_faktur, total_amount, total_owing, total_receipt, total_bayar, via, department, cluster)
		values (now(), '$idsales', '$customer', '$no_faktur', $amount, $owing, $receipt, $jml_bayar, '$via', '$department', '$cluster')";

		//echo $sql;

		if(mysql_query($sql)){

      $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
         <div class='offcanvas-body small'>
           <div class='app-info'>
             <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
             <div class='content'>
               <h3>Berhasil <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
               <a href='#'>Pembayaran TOP sudah kami terima & akan segera diproses</a>
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
               <h3>Internal Error <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
               <a href='#'>Ada kesalahan proses di internal, mohon info ke Admin.</a>
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
            <h3>DATA TIDAK LENGKAP <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'>Data tidak lengkap, pastikan nilai tagihan faktur anda tersedia .. $no_faktur && $owing && $jml_bayar && $amount && $via && $customer && $department && $cluster</a>
          </div>
        </div>
      </div>
    </div>";
	}


}

?>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">

   <!-- Form Section Start -->
      <form class="custom-form" method="post">
        <div class="input-box">
          <i class="iconly-Folder icli"></i>
          <input class="form-control" type="text" id="no_faktur" name="no_faktur" value="<?=$no_faktur?>" autocomplete="Off">
        </div>

        <input type="submit" name="cek_status" class="btn-solid" value="CEK STATUS">
      </form>
  </main>
  <main class="main-wrap order-detail cart-page mb-xxl">
    <section class="order-detail pt-0">
      <h3 class="title-2">Detail Penjualan</h3>
      <ul>
        <?=$listdata?>
      </ul>
    </section>

    <form class="custom-form" method="post">
    <div class="input-box">
        <select name="via" id="via" class="form-control">
           <option value="">Pilih metode pembayaran</option>
           <?php
						 $rs = mysql_query("select account_no, account_name from accounts where (account_name like 'TOP $depo%') order by account_no");
					   if(mysql_num_rows($rs) > 0){
					     while ($r = mysql_fetch_object($rs)) {
					       echo "<option value='$r->account_no'> $r->account_name </option>";
					   	}
					   }else{
					     echo "<option value=''> TOP Depo anda tidak ditemukan!</option>";
					   }
					 ?>
         </select>
    </div>

    <div class="input-box">
       <input class="form-control" type="hidden" id="no_faktur" name="no_faktur" value="<?=$no_faktur?>">
       <input class="form-control" type="hidden" id="amount" name="amount" value="<?=$totalAmount_real?>">
  		 <input class="form-control" type="hidden" id="receipt" name="receipt" value="<?=$primeReceipt_real?>">
  		 <input class="form-control" type="hidden" id="owing" name="owing" value="<?=$primeOwing_real?>">
  		 <input class="form-control" type="hidden" id="customer" name="customer" value="<?=$customer?>">
  		 <input class="form-control" type="hidden" id="department" name="department" value="<?=$department?>">
  		 <input class="form-control" type="hidden" id="cluster" name="cluster" value="<?=$cluster?>">
  		 <input class="form-control" type="number" id="jml_bayar" name="jml_bayar" value="<?=$primeOwing_real?>" placeholder="<?=$primeOwing_real?>">
       <label><small>Jumlah Bayar</small></label>
    </div>

    <div class="input-box">
      <input type="submit" name="bayar" value="BAYAR" class="btn-solid" <?=$dis?>>
    </div>

  </form>
  </main>

  <?=$msg?>
  <?php include("footer.inc.php");?>
