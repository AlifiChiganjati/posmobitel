<?php
$merk = $_GET['merk'];
$cat = $_GET['cat'];
$prd = $_GET['prd'];
$ctx = strtoupper($cat);
$prdx = base64_decode(base64_decode($prd));
$title = "DETAIL";
$back_button = "thumbnail_v2.php?merk=$merk&cat=$cat";
session_start();
include("session_check.php");
$role = $_SESSION['ROLE'];
if($role == "RTI/RAI"){
	$str = $_SESSION['IDSTORE'];
	$arr_str = explode(" ",$str);
	$gudanga = $arr_str[2];
}
if($merk == "xiaomi"){
	include("connection_rti.inc.php");
	$basez="https://nexa.my.id/posrti/";
	if($role == "RTI/RAI"){
		$gudangx = "Gd. RTI ".$gudanga;
	}else{
		$gudangx = $_SESSION['GUDANG_RTI'];
	}
}else if($merk == "smartfren"){
	include("connection_cesa.inc.php");
	$basez="https://nexacloud.id/nexacesa/";
}else if($merk == "accessories"){
	include("connection_cw.inc.php");
	$basez="https://nexacloud.id/posretail/";
}else if($merk == "jbl"){
	include("connection_jbl.inc.php");
	$basez="https://nexacloud.id/posjbl/";
}else{
	include("connection_rai.inc.php");
	$basez="https://nexa.my.id/posrai/";
	if($role == "RTI/RAI"){
		$gudangx = "Gd. RAI ".$gudanga;
	}else{
		$gudangx =$_SESSION['GUDANG_RAI'];
	}
}
include("function.inc.php");

$user = $_SESSION['USER'];

if(isset($_POST['cart'])){
	$qtyx = $_POST['qty'];
	$qty_awal = $_POST['qty_awal'];
	$id_produk = $_POST['idp'];
	
	$jum = count($id_produk);
	for($i=0; $i<$jum; $i++){
		if($qtyx[$i] != 0){
			$aa = "select category, name_product, unit_price, image, discount_4 from products where id_product = '$id_produk[$i]'";
			$qdt_prd = mysql_query($aa);
			$data = mysql_fetch_object($qdt_prd);
			
			if($qty_awal[$i] != 0){
				$cc = "update order_temp set qty='$qtyx[$i]' where user='$user' and id_produk='$id_produk[$i]'";
			}else{
				$cc = "insert into order_temp set tanggal_add=now(), kategori='$data->category', id_produk='$id_produk[$i]', nama_produk='$data->name_product',
										harga=$data->unit_price, qty='$qtyx[$i]', user='$user', status=0, images='$data->image', diskon=$data->discount_4";
			}
			$sql_cart = mysql_query($cc);
				
			if($sql_cart){
				$pesan .= "$data->name_product successfully, ";
			}else{
				$pesan .= "$data->name_product failed,";
			}
		}
	}
	$msg ="<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				  <div class='offcanvas-body small'>
					<div class='app-info'>
					  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
					  <div class='content'>
						<h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						<a href='#'>Added $pesan</a>
					  </div>
					</div>
				  </div>
				</div>";
}

$xx = "select count(id) as jum from order_temp where user='$user' and kategori like '$cat%'";
$qryx = mysql_query($xx);
$arr_jum = mysql_fetch_object($qryx);
$jum_cart = $arr_jum->jum;

include("header_alt_v2.inc.php");

if(isset($_POST['cek_stok'])){
	if($idsales){
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
		  
			$hasil .= "<div class='col-8'>Gudang</div><div class='col-4 text-end'>Stok</div>";
			for($i=0; $i < $jdata; $i++){
				$whs = $datas[$i]['warehouseName'];
				$stok = $datas[$i]['balance'];
				//if($stok != 0){
					$hasil .= "<div class='col-8'>$whs</div><div class='col-4 text-end'>$stok</div>";
				//}
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

function cek_cart($idprd, $user){
	$qqry = mysql_query("select qty from order_temp where user='$user' and id_produk='$idprd'");
	$dataz = mysql_fetch_object($qqry);
	if($dataz->qty > 0){
		$x = $dataz->qty;
	}else{
		$x = 0;
	}
	return($x);
}
function cek_imei_master($id,$gudang){
	$sql ="select count(id) as jml from imei_master where id_product='$id' and warehouse = '$gudang' and STATUS=0";
	$qry = mysql_query($sql);
	$data = mysql_fetch_object($qry);
	$jums = $data->jml;
	return $jums;
}

function cek_stok_jbl($id,$gudang){
	$sql ="select qty as jml from acc_master where id_product='$id'";
	$qry = mysql_query($sql);
	$data = mysql_fetch_object($qry);
	$jums = $data->jml;
	return $jums;
}
?>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
<div class='row g-1'>

<?php  
	$qry = mysql_query("select * from product_mobitel where product_reff='$prdx'");
	$data=mysql_fetch_object($qry);
		$harga = number_format($data->unit_price);
		$image = $data->product_image;
		$desc = $data->product_description;
		if($image == ""){
			$src = "menu_icons/1.png";
		}else{
			$src = $basez."".$image;
		}
		echo "<div class='col-12 mb-0'>
            <div class='product-card'>
              <div class='text-center'>
                <a href='#' tabindex='-1'><img src='$src' class='img-fluid' alt='product' width='50%'> </a>
              </div>
              <div class='mt-2'>
                <a href='#' class='font-lg title-color'>$data->product_name</a>
					
				<div class='row'>
					<div class='col-12'>
						Detail: <br>$desc
					</div>
					$hasil
				</div>
              </div>
            </div>
          </div>";


?>  

<form method="post">
<?php  
	$sql ="select a.*,b.name_product, b.unit_price, b.image, b.discount_4, b.srp_price
						from product_mobitel_detail a join products b 
						on a.id_product=b.id_product where a.reff='$prdx'";
	$qry = mysql_query($sql);
	while($data=mysql_fetch_object($qry)){
		//cek qty di keranjang
		$qty_in_chart = cek_cart($data->id_product, $user);
		$harga = number_format($data->unit_price);
		$base = base64_encode(base64_encode($data->id_product));
		$link = "detail.php?prd=$base&merk=$merk&cat=$cat";
		$diskon4 = $data->discount_4;
		$colors = ($data->product_color == "") ? "..." : ucwords(strtolower($data->product_color));
		$color = "<span class='content-color font-xs'><small>".$colors."</small></span>";
		if($diskon4 != 0){
			$harga1 = $harga;
			$harga2 = number_format($data->unit_price - $diskon4);
			$tampil_harga1 = "<span class='content-color font-xs' style='text-decoration: line-through;'><small>Rp$harga1</small></span>";
			$tampil_harga2 = "<span class='content-color font-xs'><small>DTP: Rp$harga2</small></span>";
		}else{
			$harga1 = $harga;
			$tampil_harga2 = "";
			$tampil_harga1 = "<span class='content-color font-xs'><small>DTP: Rp$harga1</small></span>";
		}
		$harga_srp = $data->srp_price;
		$tampil_srp = "";
		if($harga_srp > 0){
			$tampil_srp = "<span class='content-color font-xs'><small>SRP: Rp".number_format($harga_srp)."</small></span>";
		}
		$image=$data->image;
		if($image == ""){
			$src = "menu_icons/1.png";
		}else{
			$src = $basez."".$data->image;
		}
		//cek imei_master
		
		$cek_im = cek_imei_master($data->id_product, $gudangx);
		if($merk == "jbl"){
			$cek_im = cek_stok_jbl($data->id_product, $gudangx);
		}
		if($cek_im == 0){
			$btn_qty = "<div class='plus-minus'>
							<input type='hidden' value='0' min='0' max='100' name='qty[]'>
							Out of stock</div>";
		}else{
			$btn_qty = "<div class='plus-minus'>
						<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-minus sub'><line x1='5' y1='12' x2='19' y2='12'></line></svg>
					  
						<input type='number' value='$qty_in_chart' min='0' max='100' name='qty[]'>
						<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-plus add'><line x1='12' y1='5' x2='12' y2='19'></line><line x1='5' y1='12' x2='19' y2='12'></line></svg>
					</div>";
		}
		echo "<div class='product-list media'>
				<input type='hidden' value='$data->id_product' name='idp[]'>
				<a href='#'><img src='$src' alt='offer'></a>
				<div class='media-body' style='width:100%'>
					<span class='content-color font-xs'><small>$data->product_size </small> </span>
				  	$color
				  	$tampil_harga1 $tampil_harga2 $tampil_srp
					<input type='hidden' value='$qty_in_chart' name='qty_awal[]'>
					$btn_qty
				</div>
			</div>";
  }
?>
            

  <footer class="footer-wrap shop bg-dark">
  <ul class="footer">
    <li class="footer-item">
      
    </li>
    <li class="footer-item">
      <button type="submit" name ='cart' class="font-md text-white">Add to Cart <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
    </li>
  </ul>
</footer>
</form> 
  </div>
</main>
<?=$msg?>
    <!-- Main End -->
  <?php include("footer_alt.inc.php");?>
