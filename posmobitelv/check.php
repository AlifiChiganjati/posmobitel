<?php
$title = "CEK STOK";
session_start();
include("session_check.php");
include("connection_rti.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

if(isset($_POST['cek_stok'])){
	if($idsales){
		$prdx = $_POST['idproduk'];
		$sqlprd = mysql_query("select id_aolitem from products where id_product='$prdx'");
		$qryprd = mysql_fetch_object($sqlprd);
		$idaolprd = $qryprd->id_aolitem;
		$arr = aol_auth();
		$headers = array(
			'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
			'Authorization: Bearer '. $arr->bearer,
			'X-Session-ID: '.$arr->session
		);

		$path = "/accurate/api/item/detail.do?id=".$idaolprd;
		$newurl = trim($arr->baseurl . $path);
		  $c = curl_init ($newurl);
          curl_setopt ($c, CURLOPT_URL, $newurl);
          curl_setopt ($c, CURLOPT_HTTPHEADER, $headers);
          curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
          curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt ($c, CURLOPT_SSL_VERIFYHOST, false);

          $response = curl_exec ($c);
          //echo $response;

          curl_close ($c);
			$jsons = json_decode($response, TRUE);
			$datas = $jsons['d']['detailWarehouseData'];
			$jdata = count($datas);
		  
			//$hasil = $response;
			$hasil .= "<div class='col-8'>Gudang</div><div class='col-4 text-end'>Stok</div>";
			for($i=0; $i < $jdata; $i++){
				$whs = $datas[$i]['warehouseName'];
				$stok = $datas[$i]['balance'];
				if($stok != 0){
					$hasil .= "<div class='col-8'>$whs</div><div class='col-4 text-end'>$stok</div>";
				}
			}
			
	}else{
		$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='4000' data-bs-autohide='true'>
							<div class='toast-body'>
								<div class='content d-flex align-items-center mb-2'>
									<h6 class='mb-0'>Pesan</h6>
									<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
								</div><span class='mb-0 d-block'>ID anda tidak valid, silahkan login ulang</span>
							</div>
						</div>";
	}
}
?>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
<br>
      <form class="custom-form" method="post">
        <div class="input-box">
          <i class="iconly-Phone icli"></i>
		  Nama Produk
          <input class="form-control" type="text" id="produk" name="produk" value="" onkeyup="autoCompleteproduct2();" autocomplete="Off" placeholder='Masukkan Nama Produk'>
			
        </div>
        <div id="hasilproduk" class="input-box"> </div>
		
		<div class="input-box">
          <i class="iconly-Phone icli"></i>
		  ID Produk
          <input class="form-control" type="text" id="idproduk" name="idproduk" value="<?=$idproduk?>" autocomplete="Off" readonly required>
        </div>
		
		<button type='submit' class='btn-solid bg-dark' name='cek_stok'>Check</button>
      </form>
	  <div class='row mt-2'>
		<?=$hasil?>
	  </div>
    </main>
	<!-- Main End -->
  <?php include("footer_alt.inc.php");?>
