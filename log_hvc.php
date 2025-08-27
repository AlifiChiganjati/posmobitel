<?php
$title = "LOG HVC";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");
$qry = "select * from hvc where idsalesman = '$idsales' order by tanggal desc limit 20";

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  $res = "
  <!-- Main Start -->
    <main class='main-wrap notification-page mb-xxl'>
      <!-- Tab Content Start -->
      <section class='tab-content ratio2_1' id='pills-tabContent'>
        <!-- Offer Content Start -->
        <div class='tab-pane fade show active' id='offer1' role='tabpanel' aria-labelledby='offer1-tab'>
          <!-- Yesterday Start -->
          <div class='offer-wrap'>
            <h2 class='font-sm content-color'>LOG HVC</h2>
  ";

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
                    <h3 class='font-sm title-color'>$ixpro</h3>
                    <span class='font-xs content-color'>$rs->tanggal</span>
                    <br />
                    <small>$status</small>
                  </div>
                  <span class='badges font-theme'>".number_format($total)."</span>
                </div>
              </div>";
  }

  $res .= " </div>
  </div>
  </section>
  </main>";
}else{
  $res = "<main class='main-wrap empty-cart mb-xxl'>
      <!-- Banner Start -->
      <div class='banner-box'>
        <img src='assets/images/empty.png' class='img-fluid' alt='404'>
      </div>
      <!-- Banner End -->

      <!-- Error Section Start -->
      <section class='error mb-large'>
        <h2 class='font-lg'>Data tidak ditemukan !!</h2>
        <p class='content-color font-md'>Tampaknya anda belum melakukan transaksi</p>
        <a href='hvc.php' class='btn-solid'>Mulai Order</a>
      </section>
      <!-- Error Section End -->
    </main>";
}
?>

  <?=$res?>
<?php
include("footer.inc.php");
?>
