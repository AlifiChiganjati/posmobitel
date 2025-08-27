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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
  //$cluster = cluster($clustero);
  $role = $_SESSION['ROLE'];
  $user = $_SESSION['USER'];
  $name = $_SESSION['NAME'];
  $phone = $_SESSION['PHONE'];
  ?>
  <!-- Header Start -->
    <header class="header sticky-top bg-white">
      <div class="logo-wrap">
      <a href="<?=$back_button?>"><img src="iconm/21.jpeg" style="width:25px;"></a>
      <?=$title?>
      </div>
      <div class="">
        
      </div>
    </header>
    

    <br />
