<?php
$title = "INBOX";
session_start();
//include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$qry = "select * from sales_invoice where idsalesman ='$idsales' order by date_order desc limit 10";
$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $ixpro = strtoupper($rs->id_product);
    //$dxpro = strtoupper($rs->name_product);
    $total = $rs->total;
    $unit = ($rs->unit_price == "1")? $total : $rs->unit_price;
    $name = name_customer($rs->id_customer);
    $status = status_transaksi($rs->status);

    if($rs->sent_request == "0"){
      $approve = "";
    }else if($rs->sent_request == "1"){
      $approve = "<br />MENUNGGU APPROVAL";
    }else if($rs->sent_request == "1"){
      $approve = "<br />DISETUJUI";
    }else{
      $approve = "<br />DITOLAK";
    }
    $res .= "<div class='offer-box'>
                <div class='media'>
                  <div class='icon-wrap bg-theme-orange-light'>
                    <i class='iconly-Ticket-Star icli'></i>
                  </div>
                  <div class='media-body'>
                    <h3 class='font-sm title-color'>$ixpro, $rs->detail_notes</h3>
					<span class='font-xs content-color'>$name</span>
                    <span class='font-xs content-color'>$rs->date_order</span>
					
                  </div>
                  <span class='badges font-theme'>".number_format($total)."</span>
                </div>
              </div>";

    $totals += $rs->total;
  }
}else{
  $res = "<div class='offer-box'>
              <div class='media'>
                <div class='icon-wrap'>
                  <i class='iconly-Ticket icli'></i>
                </div>
                <div class='media-body'>
                  <h3 class='font-sm title-color'>Data tidak ditemukan!</h3>
                </div>

              </div>
            </div>";
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
            <h2 class="font-sm content-color">Antrian Penjualan</h2>
            <!-- Offer Box Start -->
                <?=$res?>
           <!-- Offer Box End -->
          </div>
          <!-- Yesterday End -->

<?php
          $qry = "select * from inject_stok_transfer where idsalesman ='$idsales' order by date_order desc limit 5";
          $sql = mysql_query($qry);
          $row = mysql_num_rows($sql);
          if($row > 0){
            while ($rs = mysql_fetch_object($sql)){
              $ixpro = strtoupper($rs->id_product);
              //$dxpro = strtoupper($rs->name_product);
              $total = $rs->total;
              $unit = ($rs->unit_price == "1")? $total : $rs->unit_price;
              $name = name_customer($rs->id_customer);
              $status = status_transaksi($rs->status);
              $reso .= "<div class='offer-box'>
                          <div class='media'>
                            <div class='icon-wrap'>
                              <i class='iconly-Message icli'></i>
                            </div>
                            <div class='media-body'>
								<div class='row'>
									<div class='col-6'><strong>$ixpro</strong></div>
									<div class='col-6 text-end'><strong>".number_format($total)."</strong></div>
								</div>
								
								<small>$rs->detail_notes</small><br>
								<div class='row'>
									<div class='col-6'><small>$name</small></div>
									<div class='col-6 text-end'><small>$status</small></div>
								</div>
								<div class='row'>
									<div class='col-6'><small>$rs->date_order</small></div>
								</div>
                            </div>
                          </div>
                        </div>";

              $totals += $rs->total;
            }
          }

          ?>

          <!-- Last 7 Days Start -->
          <div class="offer-wrap section-p-t">
            <h2 class="font-sm content-color">Inject Saldo</h2>
            <!-- Offer Box Start -->

                <?=$reso?>

          </div>
          <!-- Last 7 Days End -->
        </div>
        <!-- Offer Content End -->
      </section>
      <!-- Tab Content End -->
    </main>
    <!-- Main End -->
<?php
include("footer.inc.php");
?>
