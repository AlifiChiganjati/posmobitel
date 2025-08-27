<?php
$merk = $_GET['merk'];
$cat = $_GET['cat'];
$prd = $_GET['prd'];
$ctx = strtoupper($cat);
$prdx = base64_decode(base64_decode($prd));
$title = "DETAIL";
$back_button = "thumbnail_cw.php?merk=$merk&cat=$cat";
session_start();
include("session_check.php");
if($merk == "accessories"){
	include("connection_cw.inc.php");
	$basez="https://nexacloud.id/posretail/";
}
include("function.inc.php");
include("header_alt_cw.inc.php");

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
		if($image == ""){
			$src = "menu_icons/1.png";
		}else{
			$src = $basez."".$data->image;
		}
		echo "<div class='col-12 mb-1'>
            <div class='product-card'>
              <div class='img-wrap text-center'>
                <a href='#' tabindex='-1'><img src='$src' class='img-fluid' alt='product'> </a>
              </div>
              <div class='content-wrap'>
                <a href='#' class='font-sm title-color' tabindex='-1'>$data->name_product</a>
                <span class='content-color font-xs'><strong>Rp$harga</strong></span>
                <br>
                Detail: 
                <br>
                <br>
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
		  $sql_cart = mysql_query("insert into order_temp set tanggal_add=now(), kategori='$data->category', id_produk='$id_produk', nama_produk='$nama_produk', harga=$harga, qty='$qtyx', user='$user', status=0, images='$image'");
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
    $link = "detail_cw.php?prd=$base&merk=$merk&cat=$cat";
	$image=$data->image;
		if($image == ""){
			$src = "menu_icons/1.png";
		}else{
			$src = $basez."".$data->image;
		}
    echo "<div class='col-6 mb-1'>
            <div class='product-card'>
              <div class='img-wrap text-center'>
                <a href='$link' tabindex='-1'><img src='$src' class='img-fluid' alt='product'> </a>
              </div>
              <div class='content-wrap'>
                <a href='$link' class='font-sm title-color' tabindex='-1'><small>$data->name_product</small></a>
                <span class='content-color font-xs'><small>Rp$harga</small></span>
            
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
