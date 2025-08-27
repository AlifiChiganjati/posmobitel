<?php
$title = "STOK GUDANG";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");
$mainstore = $_SESSION['STORE'];
$idstaff = $_SESSION['IDSALES'];

$arr_exclusive_fl = array("RJW01040", "RJW02100", "RJW40006");
$arr_exclusive_kt = array("RJW03125");

$special_id = "RJW03061";

if(in_array($idstaff, $arr_exclusive_fl)){
  $qry = "select id_product, sum(stock) as jmlstock, warehouse from stock_accurate where warehouse IN (SELECT CONCAT('GUDANG ', `department`) FROM department
WHERE branch = 'FLOSUM') and stock > 0 group by warehouse, id_product order by warehouse, id_product";
}else if(in_array($idstaff, $arr_exclusive_kt)){
  $qry = "select id_product, sum(stock) as jmlstock, warehouse from stock_accurate where warehouse IN (SELECT CONCAT('GUDANG ', `department`) FROM department
WHERE branch = 'KUTAI') and stock > 0 group by warehouse, id_product order by warehouse, id_product";
}else if($idstaff == $special_id){
  $qry = "select id_product, sum(stock) as jmlstock, warehouse
from stock_accurate where warehouse IN ('GUDANG ANGGANA', 'GUDANG MUARA BADAK')
and stock > 0 group by warehouse, id_product";
}else{
  $qry = "select id_product, sum(stock) as jmlstock, warehouse from stock_accurate where warehouse='$mainstore' and stock > 0 group by warehouse, id_product";
}

//echo $qry;

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  $gol = ""; $opr1 = ""; $opr= ""; $is_1st = true;
  while ($rs = mysql_fetch_object($sql)){
    $ixpro = strtoupper($rs->id_product);
    $jml = number_format($rs->jmlstock);

    if($is_1st){
      $opr = $rs->warehouse;
      //$res .= "<tr><td colspan=2><a href='#'><u>$rs->warehouse</u></a></td></tr>";
      $res .= "<h3 class='font-theme font-md'><u>$rs->warehouse</u></h3>";
      $is_1st = false;
    }


    $opr1 = $rs->warehouse;
    if ($opr != $opr1){

      $opr = $opr1;
      //$res .= "<tr><td colspan=2><a href='#'><u>$opr</u></a></td></tr>";
      $res .= "<hr><h3 class='font-theme font-md'>$opr</h3>";
      $sub_jml = $rs->jmlstock;
    }else{
      $sub_jml += $rs->jmlstock;
    }

    $res .= "<div class='offer-box mb-1 mt-1'>
                  <div class='media'>
                    <div class='icon-wrap'>
                      <i class='iconly-Tick-Square icli'></i>
                    </div>
                    <div class=''>
                      <small>$ixpro</small>
                    </div>
                    <span class='badges font-theme'>".$jml."</span>
                  </div>
                </div>";


    $total += $rs->jmlstock;
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
            <?=$res?>
          </div>

        </div>
        <!-- Offer Content End -->
      </section>
      <!-- Tab Content End -->
      </main>
<?php
include("footer.inc.php");
?>
