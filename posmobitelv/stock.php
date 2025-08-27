<?php
$title = "FISIK";
session_start();
//include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

//$qry = "select w.id_product, w.stock, p.name_product from warehouse_stocks w left join products p on (w.id_product = p.id_product) where w.warehousename ='$gudang' and w.stock > 0 and date(w.tanggal_request) = date(now())";
//$qry = "select w.id_product, w.stock, p.name_product from warehouse_stocks w left join products p on (w.id_product = p.id_product) where w.warehousename ='$gudang' and date(w.tanggal_request) = date(now())";
$qry = "select w.id_product, count(w.id) as jml, p.name_product from imei_gudang w left join products p on (w.id_product = p.id_product) where w.warehouse ='$gudang' and w.status = '0' GROUP BY w.id_product";
//echo  $qry;

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $ixpro = strtoupper($rs->id_product);
    $dxpro = strtoupper($rs->name_product);
	$res .= "<div class='offer-box'>
                  <div class='media'>
                    <div class='icon-wrap'>
                      <i class='iconly-Tick-Square icli'></i>
                    </div>
                    <div class='media-body'>
                      <h3 class='font-sm title-color'>$ixpro <br />
                      <small>$dxpro</small></h3>
                    </div>
                    <a href='detail_fisik.php?idproduk=$rs->id_product'><span class='badges font-theme'>".number_format($rs->jml)."</span></a>
                  </div>
                </div>";

    $total += $rs->jml;
  }
}

?>
﻿
   <main class="main-wrap notification-page mb-xxl">
      <!-- Tab Content Start -->
      <section class="tab-content ratio2_1" id="pills-tabContent">
        <!-- Offer Content Start -->
        <div class="tab-pane fade show active" id="offer1" role="tabpanel" aria-labelledby="offer1-tab">
          <!-- Yesterday Start -->
          <div class="offer-wrap">
                <?=$res?>
          </div>

        </div>
        <!-- Offer Content End -->
      </section>
      <!-- Tab Content End -->
    </main>
    <!-- Main End -->

<?=$msg?>

<?php include("footer_alt.inc.php");?>
