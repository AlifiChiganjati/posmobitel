<?php
$merk = $_GET['merk'];
$title = strtoupper("Alokasi ".$merk);
$back_button = "home.php";
session_start();
include("session_check.php");
if($merk == "xiaomi"){
	include("connection_rti.inc.php");
}else if($merk == "poco"){
	include("connection_rai.inc.php");
}else{
  include("connection_main.php");
}
include("function.inc.php");

include("header_alt3.inc.php");


?>
<!-- Main Start -->
<main class="main-wrap shop-page mb-xxl">

<div class='row g-1'>

<?php  
	$userx = "MOBI00011";
	$nf = base64_decode($_GET['nf']);
	$qry = mysql_query("select *,count(qty) as qtys, sum(total) as totals from sales_invoice where idsalesman = '$userx' and status=1 and no_faktur = '$nf' group by id_product");
  	$jum = mysql_num_rows($qry);
  	if($jum == 0){
		echo $merk;
      	echo "<div class='text-center'><h4>Alokasi tidak ditemukan</h4></div>";
 	}else{
	
  	while($data=mysql_fetch_object($qry)){
		$no_faktur = $data->no_faktur;
		$nf = base64_encode($no_faktur);
  		$link = "alokasi_detail.php?merk=$merk&nf=$nf";
  		echo "<div class='col-12 mb-1'>
              <div class='product-card'>
                <div class=''>
                  	<a href='$link'>
						<div class='row'>
							<div class='col-6'>$data->date_order</div>
							<div class='col-6 text-end'>$no_faktur</div>
							<div class='col-6'>$data->id_customer</div>
							<div class='col-6 text-end'>Rp".number_format($data->totals)."</div>
							<div class='col-6'>$data->warehouse_name</div>
						</div>
					</a>
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
