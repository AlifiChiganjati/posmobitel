<?php
$title = "CHECKOUT";
session_start();
//include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$idsales = $_SESSION['IDSALES'];

$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";
$saldo = isset($_POST['saldo'])? cleanall($_POST['saldo']) : "";
$noeload = isset($_POST['noeload'])? cleanall($_POST['noeload']) : "";
$discount = "1";


function discount($level){
	switch ($level) {
		case "1": $discount = "1"; break;
		case "2": $discount = "1"; break;
		case "3": $discount = "2"; break;
		case "4": $discount = "1.5"; break;
		case "5": $discount = "2.5"; break;
		default:
			 $discount = "1";
			break;
	}
	return $discount;
}


$arrCustomer = data_customer($idcustomer);

$dis = "";
$fee = 0;
if($saldo){
	$tinput = $saldo * 1000;
	$total = ($saldo * 1000);

	if($arrCustomer){

		//$level = $arrCustomer->level;
		//$discount = discount($level);
		$discount = $arrCustomer->disc_eload;
		$rpdiscount = $total * ($discount / 100);

		$pajak1 = 0;
		$pajak2 = 0;

		//kondisi jika jml req saldo > 2.220.000
		$npwp = $arrCustomer->npwp; // xxxx | 00.000.000.0-000.000 (ada npwp = 0.5% dari 1.11% saldo -- ga ada  = 1% dari 1.11%saldo )
		$umkm = $arrCustomer->umkm;

		$total = $total - $rpdiscount;

		if($total > 2220000){
		  $pajak1 = $total - ($total / 1.11);
			$dpp = $total - $pajak1;
		  if($umkm == "YA"){
		    $pajak2 = 0;
		  }else{
		    if($npwp == "00.000.000.0-000.000"){
		      $pajak2 = ($total / 1.11) * 0.01;
		    }else{
		      $pajak2 = ($total / 1.11) * 0.005;
		    }
		  }
			$total = $total + $pajak2;
		}
	}
}


else{
	
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'> Nilai saldo tidak boleh kosong! </a>
          </div>
        </div>
      </div>
    </div>";
	$dis = "disabled";
}

if(strlen($namacustomer) < 1){
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'> Data Pelanggan tidak lengkap </a>
          </div>
        </div>
      </div>
    </div>";
	$dis = "disabled";
}

if(strlen($noeload) < 8){
	
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'> Pastikan nomor ELOAD sudah benar! </a>
          </div>
        </div>
      </div>
    </div>";
	$dis = "disabled";
}



$depo = $_SESSION['DEPO'];

//Pengkondisian str_replace
if (stripos($depo, 'CTA PAMERAN') !== false) {
    $depo_replace = str_replace('CTA ', ' ', $depo);
} elseif (stripos($depo, 'CTA ') === 0) {
    $depo_replace = str_replace('CTA ', 'DC ', $depo);
} else {
    $depo_replace = $depo;
}

$via = '';
if(strlen($depo_replace) > 0){

	//$rs = mysql_query("select account_no, account_name from accounts where (account_name like '%$depo%' and (account_name = 'KAS BESAR - $depo' OR account_name LIKE '%$depo')) order by account_no");
	$rs = mysql_query("select account_no, account_name from accounts where (account_name = 'KAS BESAR - $depo_replace') order by account_no");

  $i=0;
	while ($r = mysql_fetch_object($rs)) {
		if(strpos($r->account_name, "Kas Besar") !== false){
			$via .= "<option value='TUNAI#$r->account_no'> TUNAI </option>";
		}else{
			$via .= "<option value='TRF#$r->account_no'> $r->account_name </option>";
		}
    $i++;
	}
mysql_free_result($rs);
}else{
	$via .= "<option value=''> TIPE TUNAI TIDAK DITEMUKAN </option>";
}


if($_POST['kirim'] == "KIRIM"){
	$idcustomer_e = isset($_POST['idcustomer_e'])? cleanall($_POST['idcustomer_e']) : "";
	$namacustomer_e = isset($_POST['namacustomer_e'])? cleanall($_POST['namacustomer_e']) : "";
	$saldo_e = isset($_POST['saldo_e'])? cleanall($_POST['saldo_e']) : "";
	$noeload_e = isset($_POST['noeload_e'])? cleanall($_POST['noeload_e']) : "";
	$pajak1_e = isset($_POST['pajak1_e'])? cleanall($_POST['pajak1_e']) : 0;
	$pajak2_e = isset($_POST['pajak2_e'])? cleanall($_POST['pajak2_e']) : 0;
	$via = isset($_POST['via'])? cleanall($_POST['via']) : "";
	$tempo = isset($_POST['tempo'])? cleanall($_POST['tempo']) : date("d/m/Y");
	$diskon_e = isset($_POST['diskon_e'])? cleanall($_POST['diskon_e']) : 0;

	$product = "ELOAD-15";

	$arrDepo = array("DC DENPASAR", "DC KLUNGKUNG", "DC KARANGASEM", "DC GIANYAR");
	if (in_array($depo, $arrDepo)){
		$product = "ELOAD-16";
	}
	if($depo == "DC LOMBOK"){
		$product = "ELOAD-20";
	}

	if($idsales && $idcustomer_e && $saldo_e && $noeload_e && $via){
		if($product){

			$qry = "select id from sales_invoice where id_customer = '$idcustomer_e' and id_product='$product' and qty = '$saldo_e' and tipe = 'LINKAJA'
				and (DATE_ADD(date_order, INTERVAL 12 HOUR) > NOW()) union
			select id from log_sales_invoice where id_customer = '$idcustomer_e' and id_product='$product' and qty = '$saldo_e' and tipe = 'LINKAJA'
			and (DATE_ADD(date_order, INTERVAL 12 HOUR) > NOW())";

			$sql = mysql_query($qry);

			if(mysql_num_rows($sql) > 0){
							  
			  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					  <div class='offcanvas-body small'>
						<div class='app-info'>
						  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						  <div class='content'>
							<h3>Double Transaction <i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
							<a href='eload.php'> Transaksi Double untuk Nomor, Produk dan Qty yang sama! </a>
						  </div>
						</div>
					  </div>
					</div>";
	
			}else{
				$arrsales = data_salesman($idsales);

				if($arrsales){
					//$hp = substr($idcustomer_e, 2);
					$hp = $idcustomer_e;
					//KODE DEPO- KODE PENJUALAN MKIOS SARA TUNAI/KODE KARYAWAN-BULAN TAHUN-NOMOR TRX

					$viatop = $via;
					$taxdate = date("d/m/Y");
					$via_arr = explode("#", $via);
					$via = $via_arr[1];
					$bankname = $via_arr[0];

					$nofaktur = $arrsales->id_department ."-A12/". $idsales ."-". date("mY-dHis");
					$tempo = $taxdate;


					$total = $saldo_e * 1000;
					$data1 = $depo; //depo
					$data2 = $arrsales->cluster; //cluster

					$transdate = $taxdate;
					//$whousename = $gudang;
					//$whousename = "GUDANG ELEKTRIK - HQ";
					$whousename = "GUDANG ELEKTRIK - TT2";

					$fee = $pajak2_e;

					if(strpos($via_arr[0], "TUNAI") !== false){
						$sql = "insert ignore into sales_invoice (date_order, idsalesman, id_product, qty, unit_price, total, fee, discount, warehouse_name, id_customer, detail_notes, description, no_faktur, via, tipe_bayar, data1, data2, taxdate, transdate, duedate, opr, tipe, status, sent)
						values (now(), '$idsales', '$product', $saldo_e, 1000, $total, $fee, $diskon_e, '$whousename', '$idcustomer_e', '$noeload_e', '$noeload_e', '$nofaktur', '$via', 'TUNAI', '$data1', '$data2', '$taxdate', '$transdate', '$tempo', '$idsales', 'LINKAJA', 1, 0)";

						$iql = "insert ignore into sales_order (date_order, no_faktur, idsalesman, tipe, cluster) values (now(), '$nofaktur', '$idsales', 'LINKAJA', 'OTHER')";
						//echo $iql;

							if(mysql_query($iql)){
								if(mysql_query($sql)){
									insertlog("Input Penjualan = $nofaktur, $idsales", $idsales);
																	  
								  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
									  <div class='offcanvas-body small'>
										<div class='app-info'>
										  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										  <div class='content'>
											<h3>Konfirmasi <i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'> Order dikirim </a>
										  </div>
										</div>
									  </div>
									</div>";
					
									$noinvoice = base64_encode($nofaktur);
									//header('location: checkout_info.php?nors=0$hp&namars=$namacustomer&qty=$saldo_e&total=$total&fee=$fee&bank=TUNAI');
									echo "<script language=javascript> location.href = 'checkout_info.php?nors=$hp&namars=$namacustomer_e&qty=$saldo_e&total=$total&fee=$fee&diskon=$diskon_e&bank=TUNAI&faktur=$noinvoice'; </script>";

								}else{
																	  
								  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
									  <div class='offcanvas-body small'>
										<div class='app-info'>
										  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										  <div class='content'>
											<h3>Internal Error <i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'> Internal Logic Error for sales invoice </a>
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
											<h3>Logic Error <i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'> Error Logic Data!</a>
										  </div>
										</div>
									  </div>
									</div>";
							}

					}else if (strpos($via_arr[0], "TRF") !== false){
						$sql = "insert ignore into sales_invoice (date_order, idsalesman, id_product, qty, unit_price, total, fee, discount, warehouse_name, id_customer, detail_notes, description, no_faktur, via, tipe_bayar, data1, data2, taxdate, transdate, duedate, opr, tipe, status, sent)
						values (now(), '$idsales', '$product', $saldo_e, 1000, $total, $fee, $diskon_e, '$whousename', '$idcustomer_e', '$noeload_e', '$noeload_e', '$nofaktur', '$via', 'TRF', '$data1', '$data2', '$taxdate', '$transdate', '$tempo', '$idsales', 'LINKAJA', 1, 0)";

						$iql = "insert ignore into sales_order (date_order, no_faktur, idsalesman, tipe, cluster) values (now(), '$nofaktur', '$idsales', 'LINKAJA', 'OTHER')";
						//echo $iql;

							if(mysql_query($iql)){
								if(mysql_query($sql)){
									insertlog("Input Penjualan = $nofaktur, $idsales", $idsales);
									
									$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
									  <div class='offcanvas-body small'>
										<div class='app-info'>
										  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										  <div class='content'>
											<h3>Konfirmasi<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'>Order dikirim</a>
										  </div>
										</div>
									  </div>
									</div>";
									
									$noinvoice = base64_encode($nofaktur);
									//header('location: checkout_info.php?nors=0$hp&namars=$namacustomer&qty=$saldo_e&total=$total&fee=$fee&bank=TUNAI');
									echo "<script language=javascript> location.href = 'checkout_info.php?nors=$hp&namars=$namacustomer_e&qty=$saldo_e&total=$total&fee=$fee&diskon=$diskon_e&bank=TRF&faktur=$noinvoice'; </script>";

								}else{									
									
									$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
									  <div class='offcanvas-body small'>
										<div class='app-info'>
										  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										  <div class='content'>
											<h3>Internal Error<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'>Internal Logic Error for sales invoice</a>
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
											<h3>Logic Error<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'>Error Logic Data!</a>
										  </div>
										</div>
									  </div>
									</div>";
									
							}
					}else{

						$nofaktur = $arrsales->id_department ."-K12/". $idsales ."-". date("mY-dHis");

						$bank = get_bank_name($via); //detail nama bank
						$sql = "insert ignore into validasi_sales_invoice (date_order, idsalesman, id_product, qty, unit_price, total, fee, discount, warehouse_name, id_customer, detail_notes, description, no_faktur, via, tipe_bayar, bank, data1, data2, taxdate, transdate, opr, tipe, status)
						values (now(), '$idsales', '$product', $saldo_e, 1000, $total, $fee, $diskon_e, '$whousename', '$idcustomer_e', '$noeload_e', '$noeload_e', '$nofaktur', '$via', 'BANK $bankname', '$bankname', '$data1', '$data2', '$taxdate', '$transdate', '$idsales', 'LINKAJA', 0)";

						//echo $sql;
						if(mysql_query($sql)){
							$sqlo = mysql_query("select * from validasi_sales_invoice where idsalesman='$idsales' and no_faktur='$nofaktur' order by id desc limit 1");
							if ($ro = mysql_fetch_object($sqlo)) {
									$digit = rand(0,300);
									$tiket = ticket();
									if($tiket){
										$digit = $tiket;
									}

									$rpdiscount = $ro->total * ($ro->discount / 100);

									$jumlah = ($ro->total - $rpdiscount) + $ro->fee + (int) $digit;

									mysql_query("update validasi_sales_invoice set totalpay = $jumlah, ticket=$digit where id=$ro->id");

									$ticket = $digit;
									$jumlah = number_format($jumlah);
									
									$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
									  <div class='offcanvas-body small'>
										<div class='app-info'>
										  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										  <div class='content'>
											<h3>Order diterima!<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'>Nomor tiket: $ticket, Silahkan transfer sejumlah <h4>$jumlah</h4> ke Rekening $bank (Nilai harus sama persis), lihat link History untuk status</a>
										  </div>
										</div>
									  </div>
									</div>";

									$noinvoice = base64_encode($nofaktur);
									$pajak1 = round($pajak1_e);
									$pajak2 = round($pajak2_e);
									echo "<script language=javascript> location.href = 'checkout_info_bank.php?nors=$noeload_e&namars=$namacustomer_e&qty=$saldo_e&total=$total&fee=$pajak2_e&bank=$via&faktur=$noinvoice&tiket=$ticket&diskon=$ro->discount&pajak1=$pajak1&pajak2=$pajak2_e'; </script>";

							}else{
															
									$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
									  <div class='offcanvas-body small'>
										<div class='app-info'>
										  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
										  <div class='content'>
											<h3>Error Logic<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
											<a href='eload.php'>Order lewat Bank gagal, silahkan diulangi kembali!</a>
										  </div>
										</div>
									  </div>
									</div>";
									
							}
						}
					}
				}else{
					
					$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					  <div class='offcanvas-body small'>
						<div class='app-info'>
						  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						  <div class='content'>
							<h3>Settlement<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
							<a href='eload.php'>ID Anda tidak dapat melakukan transaksi, status sudah settlement</a>
						  </div>
						</div>
					  </div>
					</div>";
				}


			}
		}else{
			
			$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					  <div class='offcanvas-body small'>
						<div class='app-info'>
						  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						  <div class='content'>
							<h3>Product tidak dikenal<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
							<a href='eload.php'>Product tidak terdaftar disistem!</a>
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
							<h3>Data tidak lengkap<i data-feather='x' data-bs-dismiss='offcanvas'></i> </h3>
							<a href='eload.php'>Data tidak boleh kosong!</a>
						  </div>
						</div>
					  </div>
					</div>";
	}

$total = 0;
}


$start_date = date("Y/m/d");
$date = strtotime($start_date);
$date = strtotime("+7 day", $date);
$date = date('d/m/Y', $date);

$datex = strtotime($start_date);
$datex = strtotime("+10 day", $datex);
$datex = date('d/m/Y', $datex);

$datez = strtotime($start_date);
$datez = strtotime("+14 day", $datez);
$datez = date('d/m/Y', $datez);


?>
<!-- Main Start -->

<form method="post" class="custom-form">
    <main class="main-wrap order-detail cart-page mb-xxl">

			<div class="section-p-b">
          <div class="banner-box">
            <div class="media">
              <div class="img"><img src="assets/icons/svg/box.svg" alt="box"></div>
              <div class="media-body">
                <span class="font-sm">Order ID: <?=$idsales?></span>
                <span class="font-md">Belum diproses</span>
              </div>
            </div>
          </div>
        </div>

      <!-- Tab Content Start -->
      <section class="order-detail pt-0">

        <h3 class="title-2">Order Details
		</h3>

        <!-- Detail list Start -->
        <ul>
          <li>
            <span>No Reseller</span>
            <span><?=$idcustomer?></span>
            <input type="hidden" id="idcustomer_e" name="idcustomer_e" value="<?=$idcustomer?>">
          </li>
          <li>
            <span>Nama</span>
            <span><?=$namacustomer?></span>
             <input type="hidden" id="namacustomer_e" name="namacustomer_e" value="<?=$namacustomer?>">
          </li>
		  <li>
            <span>Nomor Eload</span>
            <span><?=$noeload?></span>
             <input type="hidden" id="noeload_e" name="noeload_e" value="<?=$noeload?>">
          </li>
          <li>
            <span>Nilai Saldo</span>
            <span><?=$saldo?></span>
            <input type="hidden" id="saldo_e" name="saldo_e" value="<?=$saldo?>">
          </li>
          <li>
            <span>Total Saldo Rupiah</span>
            <span><?=number_format($tinput)?></span>
          </li>
		  <li>
            <span>Diskon <?=$discount?> %</span>
            <span><?=number_format($rpdiscount)?></span>
			<input type="hidden" id="diskon_e" name="diskon_e" value="<?=$discount?>">
          </li>
		  <li>
            <span>Inc.PPN 11%</span>
            <span><?=number_format($pajak1)?></span>
			<input type="hidden" id="pajak1_e" name="pajak1_e" value="<?=round($pajak1)?>">
          </li>
		  <li>
            <span>PPH 22</span>
            <span><?=number_format($pajak2)?></span>
			<input type="hidden" id="pajak2_e" name="pajak2_e" value="<?=round($pajak2)?>">
          </li>
		  
         
          <li>
            <span>Total Bayar</span>
            <span><?=number_format($total)?></span>
          </li>
        </ul>
        <!-- Detail list End -->
      </section>

      <div class="input-box">
          <select name="via" class="form-control">
			 <option value="">Pilih metode pembayaran</option>
			 <?=$via?>
			 <option value='BCA#11300015'> BCA INSTAN</option>
			 <option value='MANDIRI#11300001'> TRANSFER BANK MANDIRI </option>
		 </select>
      </div>
	  
	  

		<!-- Tab Content End -->
    </main>

		<footer class="footer-wrap footer-button">
      <input type="submit" class="btn-solid" name="kirim" value="KIRIM">
    </footer>
		</form>

<?=$msg?>

    <!-- Main End -->
<?php
include("footer_alt.inc.php");
?>
