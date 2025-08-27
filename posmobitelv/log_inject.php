<?php
$title = "LOG INJECT";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");
$qry = "select * from inject_stok_transfer where idsalesman ='$idsales' order by id desc limit 10";

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $date = $rs->date_order;
    $product = $rs->id_product;
    $cust = $rs->id_customer;
    $note = $rs->detail_notes;

    $status = status_transaksi($rs->status);


    $res .= "<div class='offer-box'>
                <div class='media'>
                  <div class='icon-wrap bg-theme-orange-light'>
                    <i class='iconly-Bag icli'></i>
                  </div>
                  <div class='media-body'>
                    <h3 class='font-sm title-color'>$note</h3>
                    <span class='font-xs content-color'>$rs->date_order</span>
                    <br />
                    <small>$status</small>
                  </div>
                  <span class='badges font-theme'>".number_format($rs->total)."</span>
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
            <h2 class="font-sm content-color">LOG INJECT</h2>
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
