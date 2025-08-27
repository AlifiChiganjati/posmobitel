<?php
$merk = $_GET['merk'];
$cat = $_GET['cat'];
$prd = $_GET['prd'];
$ctx = strtoupper($cat);
$prdx = base64_decode(base64_decode($prd));
$title = "DETAIL";
$back_button = "thumbnail.php?merk=$merk&cat=$cat";
session_start();
include("session_check.php");
if($merk == "xiaomi"){
	include("connection_rti.inc.php");
	$basez="https://nexa.my.id/posrti/";
}else if($merk == "smartfren"){
	include("connection_cesa.inc.php");
	$basez="https://nexacloud.id/nexacesa/";
}else if($merk == "accessories"){
	include("connection_cw.inc.php");
	$basez="https://nexacloud.id/posretail/";
}else{
	include("connection_rai.inc.php");
	$basez="https://nexa.my.id/posrai/";
}
include("function.inc.php");

$user = $_SESSION['USER'];
$xx = "select count(id) as jum from order_temp where user='$user' and kategori like '$cat%'";
$qryx = mysql_query($xx);
$arr_jum = mysql_fetch_object($qryx);
$jum_cart = $arr_jum->jum;

include("header_alt.inc.php");
//cek di temp order""
$produk_ada = false;
$qty = 1;
$sql_cek ="select qty from order_temp where id_produk='$prdx' and user='$idsales'";
$qry_cek = mysql_query($sql_cek);
$jum_cek = mysql_num_rows($qry_cek);
if($jum_cek > 0){
  $produk_ada = true;
  $data_cek = mysql_fetch_object($qry_cek);
  $qty = $data_cek->qty;
}

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
?>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
<div class='row g-1'>
<form method="post">
<?php  
	$qry = mysql_query("select * from products where id_product='$prdx'");
	$data=mysql_fetch_object($qry);
		$harga = number_format($data->unit_price);
		$image=$data->image;
		$diskon4 = $data->discount_4;
		if($diskon4 != 0){
			$harga1 = $harga;
			$harga2 = number_format($data->unit_price - $diskon4);
			$tampil_harga1 = "<span class='content-color font-xs' style='text-decoration: line-through;'><strong>Rp$harga1</strong></span>";
			$tampil_harga2 = "<span class='content-color font-xs'><strong>Rp$harga2</strong></span>";
		}else{
			$harga1 = $harga;
			$tampil_harga2 = "";
			$tampil_harga1 = "<span class='content-color font-xs'><small>Rp$harga1</small></span>";
		}
		if($image == ""){
			$src = "menu_icons/1.png";
		}else{
			$src = $basez."".$data->image;
		}
		/*
		echo "<div class='col-12 mb-1'>
            <div class='product-card'>
              <div class='img-wrap text-center'>
                <a href='#' tabindex='-1'><img src='$src' class='img-fluid' alt='product'> </a>
              </div>
              <div class=''>
                <a href='#' class='font-lg title-color'>$data->name_product</a>
					<div class='row mt-2'>
						<div class='col-6'>$tampil_harga1</div>
						<div class='col-6 text-end'><strong>$tampil_harga2</strong></div>
					</div>
                <br>
				<div class='row'>
					<div class='col-6'>
						Detail: 
					</div>
					<div class='col-6 text-end'>
						<button type='submit' class='btn btn-sm btn-secondary' name='cek_stok'> Cek Stok</button>
					</div>
					$hasil
				</div>
                <br>
              </div>
            </div>
          </div>";
		  */
		  
		echo "<div class='col-12 mb-1'>
            <div class='product-card'>
              <div class='img-wrap text-center'>
                <a href='#' tabindex='-1'><img src='$src' class='img-fluid' alt='product'> </a>
              </div>
              <div class=''>
                <a href='#' class='font-lg title-color'>$data->name_product</a>
					<div class='row mt-2'>
						<div class='col-6'>$tampil_harga1</div>
						<div class='col-6 text-end'><strong>$tampil_harga2</strong></div>
					</div>
                
              </div>
            </div>
          </div>";
		  
		$id_produk = $data->id_product;
		$nama_produk = $data->name_product;
		$harga = $data->unit_price;
		$user = $idsales;
		$status = 0;
		$image = $data->image;

if(isset($_POST['cart'])){
	$qtyx = $_POST['qty'];
	$qty = $qtyx;
  if($qtyx > 0){
    if($produk_ada == TRUE){
      $sql_cart = mysql_query("update order_temp set qty = '$qtyx' where id_produk='$id_produk' and user='$user'");
      $pesan = "Update qty of $nama_produk to $qty unit(s) successfully";
    }else{
      $sql_cart = mysql_query("insert into order_temp set tanggal_add=now(), kategori='$data->category', id_produk='$id_produk', nama_produk='$nama_produk',
								harga=$harga, qty='$qtyx', user='$user', status=0, images='$image', diskon=$diskon4");
      $pesan = "Added $nama_produk $qty unit(s) to chart successfully";
    }
  }else{
    $qry = "delete from order_temp where id_produk='$id_produk' and user='$user'";
    $sql_cart = mysql_query($qry);
    $pesan = "Delete $nama_produk in cart successfully";
  }
	if($sql_cart){
		$msg ="<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
              <div class='offcanvas-body small'>
                <div class='app-info'>
                  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                  <div class='content'>
                    <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                    <a href='#'>$pesan</a>
                  </div>
                </div>
              </div>
            </div>";
	}
}
?>
<footer class="footer-wrap shop bg-dark">
  <ul class="footer">
    <li class="footer-item">
      <div class="plus-minus">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus sub"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <input type="number" value="<?=$qty?>" name='qty'>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus add"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      </div>
    </li>
    <li class="footer-item">
      <button type="submit" name ='cart' class="font-md text-white">Add to Cart <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
    </li>
  </ul>
</footer>
</form>    

<div class='row mt-3'>
    <div class='col-12'>
      Produk lainnya
    </div>
</div>
<?php  
  $qry = mysql_query("select * from products  where category like '$ctx%' and image !='' and id_product !='$id_produk' limit 10 ");
  while($data=mysql_fetch_object($qry)){
    $harga = number_format($data->unit_price);
    $base = base64_encode(base64_encode($data->id_product));
    $link = "detail.php?prd=$base&merk=$merk&cat=$cat";
	$diskon4 = $data->discount_4;
	if($diskon4 != 0){
		$harga1 = $harga;
		$harga2 = number_format($data->unit_price - $diskon4);
		$tampil_harga1 = "<span class='content-color font-xs' style='text-decoration: line-through;'><small>Rp$harga1</small></span>";
		$tampil_harga2 = "<span class='content-color font-xs'><small>Rp$harga2</small></span>";
	}else{
		$harga1 = $harga;
		$tampil_harga2 = "";
		$tampil_harga1 = "<span class='content-color font-xs'><small>Rp$harga1</small></span>";
	}
	$image=$data->image;
		if($image == ""){
			$src = "menu_icons/1.png";
		}else{
			$src = $basez."".$data->image;
		}
    echo "<div class='col-6 mb-1'>
            <div class='product-card'>
              <div class='img-wrap text-center'>
                <a href='$link' tabindex='-1'><img src='$src' class='' alt='product' height='100rem'> </a>
              </div>
              <div class='content-wrap'>
                <a href='$link' class='font-sm title-color' tabindex='-1'><small>$data->name_product</small></a>
					<div class='row'>
						<div class='col-6'>$tampil_harga1</div>
						<div class='col-6 text-end'>$tampil_harga2</div>
					</div>
            
              </div>
            </div>
          </div>";
  }
?>
            
  </div>
</main>
<?=$msg?>
    <!-- Main End -->
  <?php include("footer_alt.inc.php");?>
