<?php
$title = "FISIK";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");

$idsales = $_SESSION['IDSALES'];
$vscanned = isset($_POST['qrresux'])? cleanall($_POST['qrresux']) : "";
$barscanned = isset($_POST['qrresuxbar'])? cleanall($_POST['qrresuxbar']) : "";
$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";

if($vscanned){
	$vscanned = substr($vscanned, 5, 16); //S221X065000007794649800001082125003927
}

if($barscanned){
	$vscanned = trim($barscanned);
}

if($_POST['scan']){
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";

	echo "<script language=javascript> location.href = 'cam/index.php?idcustomer=$idcustomer&idoutlet=$idoutlet&namacustomer=$namacustomer'; </script>";
}

if($_POST['bcode']){
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";

	//echo "<script language=javascript> location.href = 'barcode/live/index.php?idcustomer=$idcustomer&idoutlet=$idoutlet&namacustomer=$namacustomer'; </script>";
	echo "<script language=javascript> location.href = 'newbarcode/index.php?idcustomer=$idcustomer&idoutlet=$idoutlet&namacustomer=$namacustomer'; </script>";
}


$depo = $_SESSION['DEPO'];
$act = isset($_GET['act'])? cleanall($_GET['act']) : "";
$ix = isset($_GET['ix'])? cleanall($_GET['ix']) : ""; //id
$ip = isset($_GET['ip'])? cleanall($_GET['ip']) : ""; //idproduct
$im = isset($_GET['im'])? cleanall($_GET['im']) : ""; //imei

if($act == "del"){
	if($idsales&& $ix && $ip){
		if(mysql_query("delete from sales_invoice where id='$ix' and id_product='$ip' and idsalesman='$idsales' and status=0 and sent=0")){
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Pragma: no-cache");
			echo "<script language=javascript> location.href = 'vfisik_imei.php'; </script>";
		}
	}
}

$batal = isset($_GET['batal'])? cleanall($_GET['batal']) : "";
if($batal == "yes"){

	mysql_query("update sales_invoice set is_request= 0, sent_request=0, newprice=0 where status=0 and idsalesman='$idsales' and is_request=1");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
	echo "<script language=javascript> location.href = 'vfisik_imei.php'; </script>";
}

$remsg = isset($_GET['remsg'])? cleanall($_GET['remsg']) : "";
if($remsg){

  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Pesan <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'> $remsg </a>
          </div>
        </div>
      </div>
    </div>";
}

function is_request_still_exist($idsalesman){
	$res = false;
	$qry = "select id from sales_invoice where sent_request= 1 and idsalesman='$idsalesman'";
	$sql = mysql_query($qry);
	if($rs = mysql_fetch_object($sql)){
		$res = true;
	}
	mysql_free_result($sql);
	return $res;
}

function cek_produk_ksy($imei,$gudang){
	$res = false;
	$qry = "select reff from imei_gudang where imei= '$imei' and warehouse='$gudang' and reff like 'KSY%'";
	$sql = mysql_query($qry);
	$ada = mysql_num_rows($sql);
	if($ada > 0){
		$res = true;
	}
	mysql_free_result($sql);
	return $res;
}


if($_POST['cart'] == "TAMBAHKAN KE CART"){
	//$product = isset($_POST['paket'])? cleanall($_POST['paket']): "";
	$imei = isset($_POST['no_imei'])? cleanall($_POST['no_imei']) : "";
	$range = isset($_POST['range'])? cleanall($_POST['range']) : "";
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";
	//$gudang = "GUDANG ". $depo;

	if($idsales && $imei){
		//cek kode produk dari nomor imei yg di input

		$product = get_product_imei($imei, $gudang);
		if($product){
				if(is_queueing($imei)){

					$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						<div class='offcanvas-body small'>
							<div class='app-info'>
								<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								<div class='content'>
									<h3>WARNING <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'>No IMEI sudah ada dalam antrian</a>
								</div>
							</div>
						</div>
					</div>";
				}else{
					$arrdata = data_customer_fisik($idcustomer);
					$level = $arrdata->level;

					//cek harga level
					//$harga = cek_harga_product($product);
					$harga = cek_harga_product_level($product, $level);
					$is_request = is_request_still_exist($idsales);
					if(!$is_request){
						if($harga){
							if($range > 0) {
								if($range <= 100){
									//$imei = gmp_add($sn_c, $i);
									$imei = "1".$imei;
									$res = true;
									for ($i=0;$i<$range;$i++) {
										$s = gmp_add($imei, $i);
										$no_imei = substr($s, 1); //hapus depan nya 1
										if(is_queueing($no_imei)){
											$res = false;
										}else if(cekstatus_imei($idsales, $no_imei)){
											$res = false;
										}else if(!is_stock_inwarehouse($no_imei, $gudang)){
											$res = false;
										}else{
											if($res){
													$product = get_product_imei($no_imei, $gudang);
													if($product){
														$harga = cek_harga_product_level($product, $level);
														$sql = "insert into sales_invoice (date_order, idsalesman, id_product, qty, unit_price, total, warehouse_name, id_customer,
														 detail_notes, description, opr, tipe) values (now(), '$idsales', '$product', 1, $harga, $harga, '$gudang', '$idcustomer', '$no_imei', '$no_imei', '$idsales', 'SN1')";

														if(!mysql_query($sql)){
															$res = false;
															//break;
														}
													}

											}
										}
									}

									if($res){

										$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
											<div class='offcanvas-body small'>
												<div class='app-info'>
													<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
													<div class='content'>
														<h3>SUKSES <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
														<a href='#'>Nomor IMEI Berhasil di Input</a>
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
														<h3>WARNING <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
														<a href='#'>Ada nomor yang tidak dapat diinput (sudah/tidak ada di cart/gudang)</a>
													</div>
												</div>
											</div>
										</div>";
									}

								}else{
									//over range
									$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
										<div class='offcanvas-body small'>
											<div class='app-info'>
												<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
												<div class='content'>
													<h3>WARNING <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
													<a href='#'>Maximum Range hanya sampai 100</a>
												</div>
											</div>
										</div>
									</div>";
								}
							}else{
								//cuma 1 nomor
								//cek jika stok digudang nya ada?
								$is_stok_exist = is_stock_inwarehouse($imei, $gudang);
								$is_product_ksy = cek_produk_ksy($imei, $gudang);

								if($is_stok_exist){
									$sql = "insert into sales_invoice (date_order, idsalesman, id_product, qty, unit_price, total, warehouse_name,
									 id_customer,
									 detail_notes, description, opr, tipe) values (now(), '$idsales', '$product', 1, $harga, $harga, '$gudang', '$idcustomer', '$imei', '$imei', '$idsales', 'SN1')";
									if($is_product_ksy){
										$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
												<div class='offcanvas-body small'>
													<div class='app-info'>
														<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
														<div class='content'>
															<h3>Error Logic <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
															<a href='#'>$imei - $product terdeteksi produk KONSINYASI, silahkan hubungi admin</a>
														</div>
													</div>
												</div>
											</div>";
									}else{
										if(!mysql_query($sql)){
											$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
												<div class='offcanvas-body small'>
													<div class='app-info'>
														<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
														<div class='content'>
															<h3>Error Logic <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
															<a href='#'>Internal Logic Program!</a>
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
															<h3>OK <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
															<a href='#'>Produk: $product, IMEI: $imei masuk ke Cart Antrian</a>
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
													<h3>INFO <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
													<a href='#'>IMEI tidak ditemukan di Gudang anda</a>
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
											<h3>INFO <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
											<a href='#'>Harga produk tidak ditemukan</a>
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
											<h3>INFO <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
											<a href='#'>Tidak diperkenankan menambah data, Request harga sebelumnya masih dalam antrian. Batalkan/Request ulang kembali</a>
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
								<h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
								<a href='#'>SN ini tidak ada di Gudang anda!</a>
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
							<h3>Pesan <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							<a href='#'>Data tidak lengkap!</a>
						</div>
					</div>
				</div>
			</div>";
	}
}

if($_POST['update'] == "UPDATE"){

	$eproduct = isset($_POST['eproduct'])? $_POST['eproduct'] : "";
	$enewprice = isset($_POST['enewprice'])? intval($_POST['enewprice']) : "0";

		if($enewprice > 0){
			if($eproduct && $enewprice){
				$se = "update sales_invoice set is_request=1, newprice=$enewprice where id_product='$eproduct' and status=0 and idsalesman='$idsales'";

				if(mysql_query($se)){
					$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							<div class='offcanvas-body small'>
								<div class='app-info'>
									<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
									<div class='content'>
										<h3>Request <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
										<a href='#'>Ubah harga berhasil diproses</a>
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
								<h3>Request Gagal <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
								<a href='#'>Harga anda tidak valid</a>
							</div>
						</div>
					</div>
				</div>";
		}

}


$sqli = "select * from sales_invoice where idsalesman='$idsales' and status =0 and tipe= 'SN1' order by id desc";
$qsql = mysql_query($sqli);
	$rex = false; $newharga = "";
	$next = "<a class='btn-solid' href='checkout_imei.php'>PROSES KE PEMBAYARAN</a>";
	$batal = "";
	while($rs = mysql_fetch_object($qsql)){
		$unit_price = number_format($rs->unit_price);
		$subtotal = number_format($rs->total);

		if($rs->is_request == 1){
			$newharga = number_format($rs->newprice);
			$curharga =
			$hargax = "
			<s>$subtotal</s>
			<input class='qty-text' type='text' name='$newharga' value='$newharga' readonly>
			";

			$harga = "<s>$subtotal</s> <span class='title-color font-xs'>$newharga</span>";
			if($rs->sent_request == 1){
				$next = "<a class='btn-solid' href='#'>MENUNGGU</a>";
				//$batal = "<a class='btn btn-sm btn-primary' href=?batal=yes>BATALKAN REQUEST</a>";
				$batal = "<a href=?batal=yes><i iconly-Shield-Fail icli></i></a>";
			}else{
				$next = "<a class='btn-solid' href='checkout_request.php'>NEXT KE APPROVAL <i class='iconly-Arrow-Right-2 icli'></i> </a>";
				//$batal = "<a class='btn btn-sm btn-primary' href=?batal=yes>BATALKAN REQUEST</a>";
				$batal = "<a href=?batal=yes><i iconly-Shield-Fail icli></i></a>";
			}

		}else{
			$harga = "<span class='title-color font-xs'>$subtotal</span>";
		}

			$listdata .= "<div class='item-wrap'>
						<div class='media'>
            <div class='count'>
              <span class='font-sm'><a href='?act=del&ix=$rs->id&ip=$rs->id_product&im=$rs->description'> X </a></span>
            </div>

            <div class='media-body'>
              <h4 class='title-color font-xs'>$rs->description</h4>
              <span class='content-color font-xs'>$rs->id_product </span>
            </div>
						$harga
						</div>
					</div>";

			$unit += $rs->qty;
			$id_customer = $rs->id_customer;
			$rex = true;
	}

	if($rex){
		$arrdata = data_customer_fisik($id_customer);
		$idcustomer = $id_customer;
		$idoutlet = $arrdata->outlet_id;
		$namacustomer = $arrdata->name;
	}

	$idpx = '';
	  $rs = mysql_query("select idsalesman, id_product from sales_invoice where idsalesman ='$idsales' and status=0 group by id_product");
	  while ($r = mysql_fetch_object($rs)) {
	     $idpx .= "<option value='$r->id_product' $sel>$r->id_product</option>";
	  }
	mysql_free_result($rs);

?>

<!-- Main Start -->
<main class="main-wrap search-page">

   <!-- Form Section Start -->
      <form class="custom-form" method="post">
        <div class="input-box">
          <i class="iconly-Call icli"></i>
          <input type="text" class="form-control" id="idcustomer" name="idcustomer" value="<?=$idcustomer?>" placeholder="Masukkan Nomor Reseller" onkeyup="autoCompletecustomer_imei();" autocomplete="Off">

        </div>
        <div id="hasilcustomer" class="input-box"> </div>

        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input class="form-control" placeholder="Id Outlet" type="text" id="idoutlet" name="idoutlet" autocomplete="Off" value="<?=$idoutlet?>" readonly>
        </div>

        <div class="input-box">
          <i class="iconly-Profile icli"></i>
        <input class="form-control" type="text" placeholder="Nama Customer" id="namacustomer" name="namacustomer" autocomplete="Off" value="<?=$namacustomer?>" readonly>
        </div>

				<div class="order-success-page">
					<div class="order-id-section section-p-tb">

						<button class="media" type="submit" id="scan" name="scan" value="scan">
								<div class="media">
		            <span><i class="iconly-Scan icli"></i></span>
		            <div class="media-body">
		              <h2 class="font-sm color-title">QR Code</h2>
		              <span class="content-color">scanner</span>
		            </div>
		          </div>
						</button>

						<button class="media" type="submit" id="bcode" name="bcode" value="bcode">
							<span><i class="iconly-Scan icli"></i></span>
								<div class="media-body">
									<h2 class="font-sm color-title">Barcode</h2>
									<span class="content-color">scanner</span>
								</div>
						</button>
	        </div>
				</div>

      <div class="input-box">
          <input class="form-control" type="text" id="no_imei" name="no_imei" placeholder="SN" value="<?=$vscanned?>" autocomplete="Off">
          <i class="iconly-Filter icli"></i>
      </div>

			<div class="input-box">
			  <input class="form-control" type="number" id="range" name="range" autocomplete="Off" placeholder="x - Generate SN">
			</div>

			<div class="input-box">
      	<input type="submit" class="btn-solid-se" name="cart" value="TAMBAHKAN KE CART">
			</div>
      </form>
    </main>

		<hr />

		<div class="main-wrap product-page">
		<div class="product-review section-p-t">
          <div class="top-content">
						<a class="doublesize_icon" href="?batal=yes"><i class="iconly-Shield-Fail icli"></i></a>
            <button class="doublesize_icon" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#quantity"><i class="iconly-Edit icli"></i></button>

          </div>
		</div>
		</div>

		<main class="order-detail mb-xxl">
      <section class="item-section p-0">
				<?=$listdata?>
      </section>
    </main>

    <!-- Main End -->
		<?php
			if($rex){
				echo "<footer class='footer-wrap footer-button'>
		      $next
		    </footer>";
			}
		 ?>


		 <div class="offcanvas select-offcanvas offcanvas-bottom" tabindex="-1" id="quantity" aria-labelledby="quantity">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Ubah Harga</h5>
      </div>
			<form method="post">
      <div class="offcanvas-body small">
        <ul class="row filter-row g-3">

					<li class="col-4">
            <div class="filter-col">
            Produk<span class="check"><img src="assets/icons/svg/active.svg" alt="active"></span>
            </div>
          </li>

          <li class="col-8">
            <div class="filter-col">
							<select name="eproduct" id="eproduct" class="form-control">
									<?=$idpx?>
							 </select>
            </div>
          </li>

          <li class="col-4">
						<div class="filter-col">
            Harga<span class="check"><img src="assets/icons/svg/active.svg" alt="active"></span>
            </div>
          </li>

					<li class="col-8">
            <div class="filter-col">
               <input class="form-control" id="enewprice" name="enewprice" type="number" placeholder="Harga Baru" value="" autocomplete="Off">
            </div>
          </li>


        </ul>
      </div>

      <div class="offcanvas-footer">
        <button class="btn-outline" data-bs-dismiss="offcanvas" aria-label="reset">Cancel</button>
        <input type="submit" name="update" value="UPDATE" class="btn-solid">
      </div>
		</form>
    </div>

		<?=$msg?>

		<?php include("footer_alt.inc.php");?>
