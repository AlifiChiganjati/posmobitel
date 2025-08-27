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
}else if($merk == "jbl"){
	include("connection_jbl.inc.php");
	$basez="https://nexacloud.id/posjbl/";
}else{
  include("connection_main.php");
}
include("function.inc.php");

$user = $_SESSION['USER'];
$xx = "select count(id) as jum from order_temp where user='$user' and kategori like '$cat%'";
$qryx = mysql_query($xx);
$arr_jum = mysql_fetch_object($qryx);
$jum_cart = $arr_jum->jum;

include("header_alt_v2.inc.php");
if(isset($_POST['cari'])){
  $keyw = $_POST['search'];
  $sql = "select * from product_mobitel where product_name like '%$keyw%' and product_category like '$ctx%' and product_image !='' limit 100";
  $qry = mysql_query($sql);
}else{
  $keyw = "";
  //$qry = mysql_query("select * from product_mobitel where category like '$ctx%' and image !='' and publish=1 limit 25");
  $qry = mysql_query("select * from product_mobitel where product_image !='' and product_category like '$ctx%' limit 100");
}
?>
<!-- Main Start -->
<main class="main-wrap shop-page mb-xxl">
	<form method="post">
	<div class="search-box g-0">
        <div>
          <i class="iconly-Search icli search"></i>
		  <input type='hidden' id='catx' value='<?=$ctx?>'>
          <input class="form-control" type="search" placeholder="Search here..." name='search' value='<?=$keyw?>' id='searchx' onkeyup="autocekProduk()"
			autocomplete='off'>
        </div>
        <button class="filter font-md" type="submit" name='cari'>Cari</button>
		
    </div>
	<div class='row mb-2'>
		<div id='hasilproduk'></div>
	</div>
    </form>
<div class='row g-1'>

<?php  
  $jum = mysql_num_rows($qry);
  if($jum == 0){
      echo "<div class='text-center'><h4>Produk tidak ditemukan</h4></div>";
  }else{
	
  	while($data=mysql_fetch_object($qry)){
  		//$harga = number_format($data->unit_price);
  		$base = base64_encode(base64_encode($data->product_reff));
  		$image=$data->product_image;
		$diskon4 = $data->discount_4;
  		if($image == ""){
  			$src = "menu_icons/1.png";
  		}else{
  			$src = $basez."".$image;
  		}
  		$link = "detail_v2.php?prd=$base&merk=$merk&cat=$cat";
  		echo "<div class='col-6 mb-1'>
              <a href='$link' class='product-card'>
                <div class='img-wrap text-center' style='height:7rem;overflow:hidden;'>
                    <img src='$src' alt='product' style='height:98%;'>
                </div>

                <div class='content-wrap text-center'>
                  <div class='font-sm title-color' tabindex='-1'><small>$data->product_name</small></div>
                </div>
              </a>
            </div>";
  	}
  }
  ?>

  </div>
</main>
    <!-- Main End -->
  <?php include("footer_alt.inc.php");?>
