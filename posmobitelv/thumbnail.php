<?php
$merk = $_GET['merk'];
$cat = $_GET['cat'];
$ctx = strtoupper($cat);
$title = strtoupper($cat ." ".$merk);
$back_button = "home.php";
session_start();
//include("session_check.php");
if($merk == "xiaomi"){
	include("connection_rti.inc.php");
	$basez="https://nexa.my.id/posrti/";
}else if($merk == "poco"){
	include("connection_rai.inc.php");
	$basez="https://nexa.my.id/posrai/";
}else if($merk == "smartfren"){
	include("connection_cesa.inc.php");
	$basez="https://nexacloud.id/nexacesa/";
}else if($merk == "accessories"){
	include("connection_cw.inc.php");
	$basez="https://nexacloud.id/posretail/";
}else{
  include("connection_main.php");
}
include("function.inc.php");

$user = $_SESSION['USER'];
$xx = "select count(id) as jum from order_temp where user='$user' and kategori like '$cat%'";
$qryx = mysql_query($xx);
$arr_jum = mysql_fetch_object($qryx);
$jum_cart = $arr_jum->jum;

include("header_alt.inc.php");
if(isset($_POST['cari'])){
  $keyw = $_POST['search'];
  $sql = "select * from products where name_product like '%$keyw%' and category like '$ctx%' and image !='' and publish=1 limit 25";
  $qry = mysql_query($sql);
}else{
  $keyw = "";
  $qry = mysql_query("select * from products where category like '$ctx%' and image !='' and publish=1 limit 25");
}

?>
<!-- Main Start -->
<main class="main-wrap shop-page mb-xxl">
	<form method="post">
	<div class="search-box g-0">
        <div>
          <i class="iconly-Search icli search"></i>
          <input class="form-control" type="search" placeholder="Search here..." name='search' value='<?=$keyw?>'>
        </div>
        <button class="filter font-md" type="submit" name='cari'>Cari</button>
    </div>
    </form>
<div class='row g-1'>

<?php  
  $jum = mysql_num_rows($qry);
  if($jum == 0){
      echo "<div class='text-center'><h4>Produk tidak ditemukan</h4></div>";
  }else{
	
  	while($data=mysql_fetch_object($qry)){
  		$harga = number_format($data->unit_price);
  		$base = base64_encode(base64_encode($data->id_product));
  		$image=$data->image;
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
  		if($image == ""){
  			$src = "menu_icons/1.png";
  		}else{
  			$src = $basez."".$data->image;
  		}
  		$link = "detail.php?prd=$base&merk=$merk&cat=$cat";
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
  }
  ?>

  </div>
</main>
    <!-- Main End -->
  <?php include("footer_alt.inc.php");?>
