<?php

error_reporting(E_ALL);        // Report semua error
ini_set('display_errors', 1);

$title = "Form Penjualan JBL";
session_start();
include("session_check.php");
include("connection_jbl.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$idsales = $_SESSION['IDSALES'];
$gudang = $_SESSION['IDSTORE'];
$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";


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
			echo "<script language=javascript> location.href = 'jbl_penjualan.php'; </script>";
		}
	}
}
if($act == "del_all"){
	if($idsales){
		if(mysql_query("delete from sales_invoice where idsalesman='$idsales' and status=0 and sent=0")){
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Pragma: no-cache");
			echo "<script language=javascript> location.href = 'jbl_penjualan.php'; </script>";
		}
	}
}

$batal = isset($_GET['batal'])? cleanall($_GET['batal']) : "";
if($batal == "yes"){

	mysql_query("update sales_invoice set is_request= 0, sent_request=0, newprice=0 where status=0 and idsalesman='$idsales' and is_request=1");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
	echo "<script language=javascript> location.href = 'jbl_penjualan.php'; </script>";
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

function cek_data_in_form($gudang,$product){
  $qry = mysql_query("select qty as total from sales_invoice where warehouse_name='$gudang' and id_product='$product' and status=0");
  $jums = mysql_num_rows($qry);
  if($jums > 0){
    $dt = mysql_fetch_object($qry);
    $jum = $dt->total;
  }else{
    $jum = 0;
  }

  return $jum;
}

    function cek_harga_product_level_jbl($id_product, $level){

      $qry = "select unit_price, price_$level as harga from products where id_product='$id_product'";
      //echo $qry;
      $sql = mysql_query($qry);
      if($rs = mysql_fetch_object($sql)){
        if($rs->harga > 0){
          $hrgproduct = $rs->harga;
        }else{
          $hrgproduct = $rs->unit_price;
        }
        return $hrgproduct;
      }else{
        return false;
      }
    }

	function get_product_name($id_product, $level){
      	$qry = "select name_product from products where id_product='$id_product'";
		$sql = mysql_query($qry);
		if($rs = mysql_fetch_object($sql)){
			
			$hrgproduct = $rs->name_product;
			
			return $hrgproduct;
		}else{
			return false;
		}
    }

	
if($_POST['cart'] == "TAMBAHKAN KE CART"){
	$id_product = isset($_POST['no_imei'])? cleanall($_POST['no_imei']) : "";
	$qty = isset($_POST['range'])? cleanall($_POST['range']) : "";
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";

	if($idsales && $id_product && $idcustomer){
		//cek stok sales 
		$stok_sales = get_stok_jbl($id_product, $gudang);
		$in_cart = cek_data_in_form($gudang, $id_product);
		if($stok_sales >= ($qty + $in_cart)){
			$arrdata = data_customer_fisik_jbl($idcustomer);					
			$level = $arrdata->level;
			$harga = cek_harga_product_level_jbl($id_product, $level);
			$is_request = is_request_still_exist($idsales);
			if(!$is_request){
				if($harga){
					if($in_cart == 0){
						//insert
						$total_harga = $harga * $qty;
						$name_product = get_product_name($id_product);
						$sql = "insert into sales_invoice (date_order, idsalesman, id_product, qty, unit_price, total, warehouse_name, id_customer, detail_notes, description, opr, tipe, data2) 
													values (now(), '$idsales', '$id_product', $qty, $harga, $total_harga, '$gudang', '$idcustomer', '0', '$name_product', '$idsales', 'SN1','Kantor Pusat')";

						if(!mysql_query($sql)){
							$res = 0;
							//break;
						}
					}else{
						//update
						$total_qty = $qty + $in_cart;
						$total_harga = $harga * $total_qty;
						$sql = "update sales_invoice set date_order=now(), qty=$total_qty, total=$total_harga where id_product='$id_product' and idsalesman='$idsales' and status=0";

						if(!mysql_query($sql)){
							$res = 0;
							//break;
						}
					}
				}else{
					$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
						<div class='offcanvas-body small'>
							<div class='app-info'>
								<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								<div class='content'>
									<h3>INFO <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'>Harga produk tidak ditemukan  - $level</a>
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

		}else{
			$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					<div class='offcanvas-body small'>
						<div class='app-info'>
							<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
							<div class='content'>
								<h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
								<a href='#'>Stok barang anda tidak mencukupi $id_product - $stok_sales</a>
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
$jumdt = mysql_num_rows($qsql);
	$rex = false; $newharga = "";
	$next = "<a class='btn-solid bg-dark' href='jbl_penjualan_checkout.php'>PROSES KE PEMBAYARAN</a>";
	$batal = "";
	while($rs = mysql_fetch_object($qsql)){
		$unit_price = number_format($rs->unit_price);
		$qty = number_format($rs->qty);
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
			$harga = "<span class='title-color font-xs text-right'>$qty x $unit_price = $subtotal</span>";
		}

			$listdata .= "<div class='item-wrap'>
							<div class='media'>
								<div class='count'>
								  <span class='font-sm'><a href='?act=del&ix=$rs->id&ip=$rs->id_product&im=$rs->description'> X </a></span>
								</div>

								<div class=''>
								  <h4 class='title-color font-xs mb-0'><small>$rs->description [$rs->id_product]</small></h4>
								  $harga
								</div>
							</div>
							
						</div>";

			$unit += $rs->qty;
			$id_customer = $rs->id_customer;
			$rex = true;
	}

	if($rex){
		$arrdata = data_customer_fisik_jbl($id_customer);
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
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<!-- Main Start -->
<main class="main-wrap search-page">
	<br>
   <!-- Form Section Start -->
    <form class="custom-form" method="post">
        <div class="input-box">
          	<i class="iconly-Call icli"></i>
          	<input type="text" class="form-control" id="idcustomer" name="idcustomer" value="<?=$idcustomer?>" placeholder="Masukkan Nomor Reseller" onkeyup="autoCompletecustomer_imei_jbl();" autocomplete="Off" required>
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
	  	<hr />	

		<div class="input-box">
			<input class="form-control" type="text" id="no_imei" name="no_imei" placeholder="Ketik Kode / Nama Produk" value="" autocomplete="Off" required onkeyup="auto_product_jbl();">
			<i class="iconly-Filter icli"></i>
		</div>
	  	<div id="hasilproduct" class="input-box"> </div>
		<div class="input-box">
			<input class="form-control"  type="text" id="product_name" name="product_name" placeholder="Nama Produk" value="" autocomplete="Off" required readonly >
		</div>
		<div class="input-box">
			<input class="form-control" type="number" id="range" name="range" autocomplete="Off" placeholder="Kuantitas" required >
		</div>

		<div class="input-box">
			<input type="submit" class="btn-solid-se" name="cart" value="TAMBAHKAN KE CART">
		</div>
    </form>

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
				<?php 
				if($jumdt > 10){
					echo "<div class='item-wrap'>
								<div class='media'>
										<span class='text-start'><a href='?act=del_all' class='btn btn-danger btn-sm'> Hapus Semua </a></span>										
								</div>
							</div><br>";
				}
				?>
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
	</main>

		<?=$msg?>

		<?php include("footer_alt.inc.php");?>
