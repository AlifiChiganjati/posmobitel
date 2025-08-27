<?php
$title = "GANTI PIN";
session_start();
include("session_check.php");
include("connection_main.php");
include("function.inc.php");
$back_button = "#";
include("header_alt3.inc.php");

if(isset($_POST['kirim'])){
  $pinlama = isset($_POST['old_pin'])? cleanall($_POST['old_pin']) : "";
  $pinbaru = isset($_POST['new_pin'])? cleanall($_POST['new_pin']) : "";
  $repin   = isset($_POST['re_pin'])? cleanall($_POST['re_pin']) : "";
	$user = $_SESSION['USER'];
  if($pinlama && $pinbaru && $repin){
    if($pinbaru == $repin){
      if(is_numeric($pinbaru)){
        $sql = "select pinlogin from customer_mobitel where nohp = '$user' and pinlogin='$pinlama'";
        $msql = mysql_query($sql);
        if($rql = mysql_fetch_object($msql)){
            $ux = "update customer_mobitel set pinlogin = '$pinbaru' where nohp = '$user'";
            if(mysql_query($ux)){
                echo "<script language=javascript> location.href = 'home.php?pesan=pin'; </script>";
            }
        }else{

          $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
            <div class='offcanvas-body small'>
              <div class='app-info'>
                <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                <div class='content'>
                  <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                  <a href='#'> PIN lama anda tidak dikenal $sql</a>
                </div>
              </div>
            </div>
          </div>";
        }
      }else{

        $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
          <div class='offcanvas-body small'>
            <div class='app-info'>
              <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
              <div class='content'>
                <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                <a href='#'> PIN Baru harus berupa angka</a>
              </div>
            </div>
          </div>
        </div>";
      }
    }else{

      $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
        <div class='offcanvas-body small'>
          <div class='app-info'>
            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
            <div class='content'>
              <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
              <a href='#'>PIN Baru dan Konfirmasi PIN harus sama</a>
            </div>
          </div>
        </div>
      </div>";

    }
  }else{
     $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
        <div class='offcanvas-body small'>
          <div class='app-info'>
            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
            <div class='content'>
              <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
              <a href='#'>Data PIN tidak lengkap</a>
            </div>
          </div>
        </div>
      </div>";
  }
}

?>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
      <form class="custom-form" method="post">
		<span>PIN Lama</span>
        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input class="form-control" type="password" id="" name="old_pin" value="">
        </div>

		<span>PIN Baru</span>
        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input class="form-control" type="password" id="" name="new_pin" value="">
        </div>

		<span>Ulang PIN Baru</span>
        <div class="input-box">
          <i class="iconly-Bag icli"></i>
          <input class="form-control" type="password" id="" name="re_pin" value="">
        </div>

        <div class="input-box">
        <button class="btn-solid" name='kirim'><i class="iconly-Arrow-Right-2 icli"></i>GANTI PIN</button>
          </div>
      </form>
    </main>

    <?=$msg?>
    <!-- Main End -->
  <?php include("footer_alt.inc.php");?>
