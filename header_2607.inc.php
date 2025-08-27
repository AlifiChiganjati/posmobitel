<!DOCTYPE html>
<html lang="en">
  <!-- Head Start -->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SARA">
    <meta name="keywords" content="SARA">
    <meta name="author" content="SARA">
    <title>SARA App</title>
    <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="assets/images/logo/favicon.png">
    <meta name="theme-color" content="#0baf9a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="SARA">
    <meta name="msapplication-TileImage" content="assets/images/logo/favicon.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" id="rtl-link" type="text/css" href="assets/css/vendors/bootstrap.css">
    <!-- Iconly Icon css -->
    <link rel="stylesheet" type="text/css" href="assets/css/iconly.css">
    <!-- Slick css -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/slick.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/slick-theme.css">

    <!-- Style css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="assets/css/style.css">
  </head>

  <?php
  header("Cache-Control: no-store, no-cache, must-revalidate");
  $idsales = $_SESSION['IDSALES'];
  $gudang = $_SESSION['IDSTORE'];
  $depo = $_SESSION['DEPO'];
  $clustero = $_SESSION['CLUSTER'];
  $cluster = cluster($clustero);
  $role = $_SESSION['ROLE'];
  ?>
  <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">

        <img src="menu_icons/nav.png" style="width:20px;height:20px;" class="nav-bar">
        <a href="index.php"> <img class="logo logo-w" src="assets/images/logo/logo-w.png" alt="logo"></a>
        <a href="index.php"> <img class="logo" src="assets/images/logo/logo.png" alt="logo"></a>
      </div>
      <div class="avatar-wrap">
        <a href=""> <img class="avatar" src="assets/images/avatar/avatar.jpg" alt="avatar"></a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Sidebar Start -->
    <a href="javascript:void(0)" class="overlay-sidebar"></a>
    <aside class="header-sidebar">
      <div class="wrap">
        <div class="user-panel">
          <div class="media">
            <a href=""> <img src="assets/images/avatar/avatar.jpg" alt="avatar"></a>
            <div class="media-body">
              <a href="index.php" class="title-color font-sm"><?=$gudang?>
                <span class="content-color font-xs"><?=$cluster?></span>
              </a>
            </div>
          </div>
        </div>

        <!-- Navigation Start -->
        <nav class="navigation">
          <ul>
            <li>
              <a href="index.php" class="nav-link title-color font-sm">
                <img src="menu_icons/home.png" style="width:20px;height:20px;">
                <span>Beranda</span>
              </a>
              <a class="arrow" href="index.php"><i data-feather="chevron-right"></i></a>
            </li>

            <li>
              <a href="detail_fisik.php" class="nav-link title-color font-sm">
                <img src="menu_icons/gudang.png" style="width:20px;height:20px;">
                <span>Gudang</span>
              </a>
              <a class="arrow" href="detail_fisik.php"><i data-feather="chevron-right"></i></a>
            </li>

            <li>
            <a href="index.php" class="nav-link title-color font-sm">
                <img src="menu_icons/printer.png" style="width:20px;height:20px;">
                <span>Pengaturan Printer</span>
              </a>
              <a class="arrow" href="printer_setup.php"><i data-feather="chevron-right"></i></a>
            </li>

            <li>
              <a href="log_inject.php" class="nav-link title-color font-sm">
                <img src="menu_icons/logs.png" style="width:20px;height:20px;">
                <span>Log Injek</span>
              </a>
              <a class="arrow" href="index.php"><i data-feather="chevron-right"></i></a>
            </li>

            <li>
              <a href="log_akselerasi.php" class="nav-link title-color font-sm">
                <img src="menu_icons/logs.png" style="width:20px;height:20px;">
                <span>Log Akselerasi</span>
              </a>
              <a class="arrow" href="index.php"><i data-feather="chevron-right"></i></a>
            </li>

            <li>
              <a href="log_hvc.php" class="nav-link title-color font-sm">
                <img src="menu_icons/logs.png" style="width:20px;height:20px;">
                <span>Log HVC</span>
              </a>
              <a class="arrow" href="index.php"><i data-feather="chevron-right"></i></a>
            </li>

            <li>
              <a href="gantipin.php" class="nav-link title-color font-sm">
                <img src="menu_icons/password.png" style="width:20px;height:20px;">
                <span>Ganti Password</span>
              </a>
              <a class="arrow" href="index.php"><i data-feather="chevron-right"></i></a>
            </li>


            <li>
              <a href="logout.php" class="nav-link title-color font-sm">
                <img src="menu_icons/logout.png" style="width:20px;height:20px;">
                <span>Keluar</span>
              </a>
              <a class="arrow" href="logout.php"><i data-feather="chevron-right"></i></a>
            </li>


          </ul>
        </nav>
        <!-- Navigation End -->
      </div>

      <div class="contact-us">
        <span class="title-color">Contact Support</span>
        <a class="font-md title-color fw-600" href="whatsapp://send?phone=6281338740740&text=Halo..!" data-action="share/whatsapp/share"><img src="menu_icons/wa.png" style="width:20px;height:20px;"> Whatsapp</a>
      </div>
    </aside>
    <!-- Sidebar End -->
