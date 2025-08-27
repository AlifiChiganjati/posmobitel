<?php
$title = "ELOAD";
session_start();
include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
?>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">

   <!-- Form Section Start -->
      <form class="custom-form" action="checkout.php" method="post">
        <div class="input-box">
          <i class="iconly-Call icli"></i>
          <input type="text" class="form-control" id="idcustomer" name="idcustomer" placeholder="Masukkan Nomor Customer" onkeyup="autoCompletecustomer_cesa_eload();" autocomplete="Off">

        </div>
        <div id="hasilcustomer" class="input-box"> </div>

        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input type="text" placeholder="Nama Customer" id="namacustomer" name="namacustomer" class="form-control" readonly>
        </div>

        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input type="text" placeholder="Eload Customer" id="noeload" name="noeload" class="form-control" readonly>
        </div>

        <div class="input-box">
          <i class="iconly-Plus icli"></i>
          <input type="number" placeholder="Jumlah Saldo" id="saldo" name="saldo" class="form-control" required="">
        </div>

        <div class="input-box">
        <button type="submit" class="btn-solid"> <i class="iconly-Arrow-Right-2 icli"></i> Berikutnya</button>
        </div>

      </form>
      <!-- Form Section End -->
    </main>
    <!-- Main End -->
  <?php include("footer.inc.php");?>
