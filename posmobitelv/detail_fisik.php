<?php
$title = "LIST IMEI";
session_start();
include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$gudang = $_SESSION['IDSTORE'];

$jml = (isset($_GET['jml'])) ? $_GET['jml'] : 10;
$pg = (isset($_GET['pg'])) ? $_GET['pg'] : 1;
$from = $pg * $jml - $jml;

$idproduk = (isset($_GET['idproduk']))? cleanall($_GET['idproduk']) : "";

$q = "select count(id) as jml from imei_gudang where warehouse ='$gudang' and status = 0 and id_product='$idproduk'";
$rsjml = mysql_query($q);
$rjml = mysql_fetch_object($rsjml);
$rCount = $rjml->jml;
mysql_free_result($rsjml);

$qry = "select * from imei_gudang where warehouse ='$gudang' and status = 0 and id_product='$idproduk' order by id desc limit $from, $jml";
$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
while ($rs = mysql_fetch_object($sql)){
  $ixpro = strtoupper($rs->id_product);

  $res .= "<div class='offer-box'>
			  <div class='media'>
				<div class='icon-wrap bg-theme-orange-light'>
				  <i class='iconly-Tick-Square icli'></i>
				</div>
				<div class=''>
				  <h3 class='font-sm title-color'><small>$ixpro</small></h3>
				  <span class='font-xs content-color'>$rs->imei</span>
				</div>

			  </div>
			</div>";

  $totals += $rs->total;
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
            <h2 class="font-sm content-color">BARANG CO</h2>
            <!-- Offer Box Start -->
                <?=$res?>
           <!-- Offer Box End -->
          </div>

        </div>
        <!-- Offer Content End -->
      </section>
      <!-- Tab Content End -->

      <div align="center">
        <?php
        TabelFooter($_SERVER['PHP_SELF'], $rCount, $pg, $jml, "idproduk=$idproduk&keyword=$keyword&jml=$jml");
        ?>
       </div>
    </main>
    <!-- Main End -->


<?php
include("footer.inc.php");
?>
