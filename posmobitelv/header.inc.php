<!DOCTYPE html>
<html lang="en">
  <!-- Head Start -->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="SARA">
    <meta name="keywords" content="SARA">
    <meta name="author" content="SARA">
    <title>Mobitel</title>
    <link rel="icon" href="assets/images/logo/faviconx.png" type="image/x-icon">
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
    <script src="assets/js/jquery-3.6.1.js"></script>
  </head>

  <?php
  header("Cache-Control: no-store, no-cache, must-revalidate");
  $idsales = $_SESSION['IDSALES'];
   $user = $_SESSION['USER'];
  $name = $_SESSION['NAME'];
  $phone = $_SESSION['PHONE'];
  $city = $_SESSION['CITY'];
  //$gudang = $_SESSION['STORE'];
  $role = $_SESSION['ROLE'];
	if($role == "RTI/RAI"){
		$str = $_SESSION['IDSTORE'];
		$arr_str = explode(" ",$str);
		$gudanga = $arr_str[2];
		$gudangx = "Gd. RTI/RAI ".$gudanga;
	}else if($role == "CUSTOMER"){
		$gudangx = $_SESSION['GUDANG_RTI'] ." / ".$_SESSION['GUDANG_RAI'];
	}else{
		$gudangx ="";
	}
  ?>
  <!-- Header Start -->
    <header class="header sticky-top bg-white">
      <div class="logo-wrap">

        <img src="menu_icons/log.jpeg" style="width:35px;height:35px;" class="nav-bar">
        <a href="home.php"> <img class="logo" src="menu_icons/MOBITEL.png" alt="logo" style="width:45%"></a>
      </div>
      <div class="avatar-wrap">
        <a href=""> <?php if($role=="CUSTOMER"){
								echo "DEALER";
							}else{
								echo "SALES";
							}?></a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Sidebar Start -->
    <a href="javascript:void(0)" class="overlay-sidebar"></a>
    <aside class="header-sidebar">
      <div class="wrap">
	  <br><br>
        <div class="user-panel">
          <div class="row media">
			<div class='text-center'>
				<a href=""> <img src="assets/images/avatar/avatar.jpg" alt="avatar" width='30%'></a>
			</div>
            <div class="media-body">
              <a href="home.php" class="title-color font-sm"><?=$user?>
				<span class="content-color font-xs"><small><?=$name?></small></span>
				<span class="content-color font-xs"><small><?=$_SESSION['ROLE']?></small></span>
				<span class="content-color font-xs"><small><?=$gudangx?></small></span>
              </a>
            </div>
          </div>
        </div>

        <!-- Navigation Start -->
        <nav class="navigation">
          <ul>
            <li>
              <a href="home.php" class="nav-link title-color font-sm">
                <img src="menu_icons/home.jpeg" style="width:20px;height:20px;">
                <span>Beranda</span>
              </a>
              <a class="arrow" href="home.php"><i data-feather="chevron-right"></i></a>
            </li>
			<li>
              <a href="profile.php" class="nav-link title-color font-sm">
                <img src="assets/images/avatar/avatar.jpg" style="width:20px;height:20px;">
                <span>Profile</span>
              </a>
              <a class="arrow" href="profile.php"><i data-feather="chevron-right"></i></a>
            </li>
			<li>
			<a href="printer_setup.php" class="nav-link title-color font-sm">
                <img src="menu_icons/printer.png" style="width:20px;height:20px;">
                <span>Pengaturan Printer</span>
              </a>
              <a class="arrow" href="printer_setup.php"><i data-feather="chevron-right"></i></a>
            </li>
			
			<li>
			<a href="https://cloudprint.nexa.id/mobitel/" class="nav-link title-color font-sm">
                <img src="menu_icons/printer.png" style="width:20px;height:20px;">
                <span>Test Print</span>
              </a>
              <a class="arrow" onClick="location.href='testprint1.php?cmd=print2web';"><i data-feather="chevron-right"></i></a>
            </li>
			
			
            <li>
            <a href="logout.php" class="nav-link title-color font-sm">
                <img src="menu_icons/logout.jpeg" style="width:20px;height:20px;">
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
