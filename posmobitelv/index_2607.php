<?php
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header.inc.php");

$nox = isset($_GET['nox']) ? $_GET['nox'] : "";
$rex1 = isset($_GET['rex1']) ? $_GET['rex1'] : ""; //berhasil

if($rex1){

  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>$nox <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'>$rex1</a>
          </div>
        </div>
      </div>
    </div>";
}

?>
    <!-- Main Start -->
    <main class="main-wrap index-page mb-xxl">
      <!-- Banner Section Start -->
      <section class="banner-section ratio2_1">
        <div class="h-banner-slider">
          <?php
          $qry = "select * from apps_banner limit 1";
          //echo  $qry;
          $sql = mysql_query($qry);
          $row = mysql_num_rows($sql);
          if($row > 0){
            if ($rso = mysql_fetch_object($sql)){
              $reso = "<div>
            <div class='banner-box'>
              <img src='banner/$rso->banner1' alt='banner' class='bg-img'>
            </div>
          </div>
          <div>
            <div class='banner-box'>
              <img src='banner/$rso->banner2' alt='banner' class='bg-img'>
            </div>
          </div>
          <div>
            <div class='banner-box'>
              <img src='banner/$rso->banner3' alt='banner' class='bg-img'>
            </div>
          </div>";
            }
          }
          ?>

          <?=$reso?>
        </div>
      </section>
      <!-- Banner Section End -->


      <!-- Shop By Category Start -->
      <section class="category pt-0">
        <h3 class="font-md"><span class="line"></span></h3>
        <br />
        <div class="row gy-sm-4 gy-2">

          <?php
          include("connection.inc.php");
          $qry = "select * from apps_menu where tipe='menu' order by id asc";
          //echo  $qry;

          $sql = mysql_query($qry);
          $row = mysql_num_rows($sql);
          if($row > 0){
            while ($rs = mysql_fetch_object($sql)){
              $res .= "<div class='col-3'>
                        <div class='category-wrap'>
                          <div class='bg-shape'></div>
                          <a href='$rs->link'> <img class='category img-fluid' src='menu_icons/$rs->img' alt='category'> </a>
                          <span class='title-color'>$rs->menu</span>
                        </div>
                      </div>";
            }
          }
          ?>

          <?=$res?>

        </div>
      </section>

    </main>
    <!-- Main End -->

    <?=$msg?>

<?php
include("footer.inc.php");
?>
