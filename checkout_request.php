<?php
$title = "CHECKOUT";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");
$idsales = $_SESSION['IDSALES'];

$depo = $_SESSION['DEPO'];

if($_POST['request'] == "MINTA PERSETUJUAN"){
	$data1 = $depo; //depo
	$data2 = $cluster; //cluster

	mysql_query("update sales_invoice set sent_request = 1, data1='$data1', data2='$data2' where status = 0 and idsalesman = '$idsales' and is_request=1 and tipe = 'SN1'");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
	echo "<script language=javascript> location.href = 'vfisik_imei.php?remsg=Request perubahan harga sudah dikirim, pastikan kembali ke halaman ini lagi untuk update nya'; </script>";
}


$sql = "select sum(qty) as jml, sum(newprice) as total, id_product, id_customer from sales_invoice where idsalesman = '$idsales' and status = 0 and tipe='SN1' and is_request = 1 group by id_product";
$msq = mysql_query($sql);
//echo $sql;

if($re = mysql_fetch_object($msq)){
	$totals = number_format($re->total);
	$arrdata = data_customer($re->id_customer);
	$idcustomer = $re->id_customer;
	$idoutlet = $arrdata->outlet_id;
	$namacustomer = $arrdata->name;

	$so .= "<div class='single-profile-data d-flex align-items-center justify-content-between'>
                  <div class='title d-flex align-items-center'><i class='lni lni-minus'></i><span>Produk</span></div>
                  <div class='data-content'>$re->id_product
                      <input type='hidden' id='product_e' name='product_e' value='$re->id_product'>
                  </div>
                </div>
					<div class='single-profile-data d-flex align-items-center justify-content-between'>
					                  <div class='title d-flex align-items-center'><i class='lni lni-minus'></i><span>Quantity</span></div>
					                  <div class='data-content'>$re->jml
					                      <input type='hidden' id='qty_e' name='qty_e' value='$re->jml'>
					                  </div>
					                </div>
					<div class='single-profile-data d-flex align-items-center justify-content-between'>
				                  <div class='title d-flex align-items-center'><i class='lni lni-minus'></i><span>Total</span></div>
				                  <div class='data-content'>$totals
				                      <input type='hidden' id='total_e' name='total_e' value='$re->total'>
				                  </div>
				                </div>";

			$product = $re->id_product;
			$jml = $re->jml;
			$total = $re->total;
}
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
                <span class="font-md">Approval Request</span>
              </div>
            </div>
          </div>
        </div>

      <!-- Tab Content Start -->
      <section class="order-detail pt-0">
        <h3 class="title-2">Order Details</h3>

        <!-- Detail list Start -->
        <ul>
          <li>
            <span>No Reseller</span>
            <span><?=$idcustomer?></span>
            <input type="hidden" id="idcustomer" name="idcustomer" value="<?=$idcustomer?>">
          </li>
          <li>
          <span>ID Outlet</span>
            <span><?=$idoutlet?></span>
             <input type="hidden" id="idoutlet" name="idoutlet" value="<?=$idoutlet?>">
          </li>

					<li>
          <span>Nama</span>
            <span><?=$namacustomer?></span>
             <input type="hidden" id="namacustomer" name="namacustomer" value="<?=$namacustomer?>">
          </li>

          <li>
            <span>Product</span>
            <span><?=$product?></span>
            <input type="hidden" id="product_e" name="product_e" value="<?=$saldo?>">
          </li>
          <li>
            <span>Total Qty</span>
            <span><?=number_format($jml)?></span>
						<input type="hidden" id="qty_e" name="qty_e" value="<?=$jml?>">
          </li>

          <li>
            <span>Total</span>
            <span><?=number_format($total)?></span>
						<input type="hidden" id="total_e" name="total_e" value="<?=$total?>">
          </li>
        </ul>
        <!-- Detail list End -->
      </section>


			<div class="input-box">
					<a href="vfisik_imei.php" class="d-block btn-outline-grey"><i class="iconly-Arrow-Left icli"></i> Edit Orderan</a>
			</div>

		<!-- Tab Content End -->
    </main>

		<footer class="footer-wrap footer-button">
    	 <input type="submit" name="request" value="MINTA PERSETUJUAN" class="btn-solid">
    </footer>
		</form>

<?=$msg?>

    <!-- Main End -->
<?php
include("footer_alt.inc.php");
?>
