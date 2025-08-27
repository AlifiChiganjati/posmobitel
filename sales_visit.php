<?php
$title = "KUNJUNGAN SALES";
session_start();
//include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$datenow=date("Y-m-d");

$idsales=$_SESSION['IDSALES'];
$qry = "select * from sales_visit where jc_date='$datenow' and employee_id='$idsales' order by order_number";

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $icon = ($rs->visit_status == 0) ? "Bookmark":"Tick-Square";
    $timev = ($rs->visit_status == 0) ? "": $rs->visit_time;
    $id = base64_encode($rs->id."#".$rs->partner_name."#".$rs->partner_id);
    $url = ($rs->visit_status == 0) ? "sales_visit_checkin.php?ck=$id": "#";
	$res .= "<div class='offer-box'>
                  <a class='media' href='$url'>
                    <div class='icon-wrap'>
                      <i class='iconly-$icon icli'></i>
                    </div>
                    <div class='media-body'>
                      <h3 class='font-sm title-color'>$rs->partner_id <br />
                      <small>$rs->partner_name </small></h3>
                    </div>
                    <span class='badges font-theme text-end'>$rs->order_number<br>$timev </span>
                  </a>
                </div>";
  }
}

?>
   <main class="main-wrap notification-page mb-xxl">
      <!-- Tab Content Start -->
      <section class="tab-content ratio2_1" id="pills-tabContent">
        <!-- Offer Content Start -->
        <div class="tab-pane fade show active" id="offer1" role="tabpanel" aria-labelledby="offer1-tab">
          <!-- Yesterday Start -->
          <div class="offer-wrap"><br>
            Tanggal <?=$datenow?>
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
