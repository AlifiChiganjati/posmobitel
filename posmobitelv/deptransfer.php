<?php
$title = "HISTORY REFUND";
session_start();
//include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
$username = $_SESSION['USER'];
$qry = "SELECT *, DATE_FORMAT(tanggal, '%Y%m%d-%H%i%s') as norf from refund where iduserlogin = '$username'";
$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
$status = "";
if($row > 0){
  
  $i=0;
  while ($rs = mysql_fetch_object($sql)){
    switch ($rs->status){
      case "0":
        $status = "Diproses";
        break;
      case "1":
        $status = "Berhasil";
        break;
      case "2":
          $status = "Ditolak";
          break;
      case "9":
            $status = "Batal";
            break;
    }
     $res .= "<div class='offer-box'>
                    <div class='media'>
                      <div class='icon-wrap'>
                        <i class='iconly-Message icli'></i>
                      </div>
                      <div class='media-body'>
                        <a href='detail_refund.php?i=$rs->id'>
                          <h3 class='font-sm title-color'>RFND-$rs->norf</h3>
                          <small>Klaim ".$status."</small>
                        </a>
                      </div>
                    </div>
                  </div>";
  }
}else{
  $res = "<div class='offer-box'>
              <div class='media'>
                <div class='icon-wrap bg-theme-blue'>
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
            <!-- Offer Box Start -->
                <?=$res?>
           <!-- Offer Box End -->
          </div>

        </div>
        <!-- Offer Content End -->
      </section>
      <!-- Tab Content End -->
    </main>
    <!-- Main End -->
<?php
include("footer.inc.php");
?>
