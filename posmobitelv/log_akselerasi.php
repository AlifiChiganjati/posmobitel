<?php
$title = "LOG AKSELERASI";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");
$qry = "select * from akselerasi where idsalesman = '$idsales' order by tanggal desc limit 5";

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $ixpro = $rs->id_product;
    $cxpro = $rs->id_customer;
    $qty = $rs->qty;
    $total = number_format($rs->total);

    $status = status_transaksi($rs->status);

    $res .= "<div class='offer-box'>
                <div class='media'>
                  <div class='icon-wrap bg-theme-orange-light'>
                    <i class='iconly-Bag icli'></i>
                  </div>
                  <div class='media-body'>
                    <h3 class='font-sm title-color'>$ixpro, $cxpro</h3>
                    <span class='font-xs content-color'>$rs->tanggal</span>
                    <br />
                    <small>$status</small>
                  </div>
                  <span class='badges font-theme'>".number_format($total)."</span>
                </div>
              </div>";
  }
}
?>
<!-- Main Start -->
    <main class="main-wrap notification-page mb-xxl">
      <!-- Tab Content Start -->
      <section class="tab-content ratio2_1" id="pills-tabContent">
        <!-- Offer Content Start -->
        <div class="tab-pane fade show active" id="offer1" role="tabpanel" aria-labelledby="offer1-tab">
          <!-- Yesterday Start -->
          <div class="offer-wrap">
            <h2 class="font-sm content-color">LOG AKSELERASI</h2>
            <!-- Offer Box Start -->
                <?=$res?>
           <!-- Offer Box End -->
          </div>
          <!-- Yesterday End -->
        </div>
        <!-- Offer Content End -->
      </section>
      <!-- Tab Content End -->
    </main>
    <!-- Main End -->
<?php
include("footer.inc.php");
?>
