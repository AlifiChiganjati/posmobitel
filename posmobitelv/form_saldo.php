<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
$title='Saldo';
include("cek_login.inc.php");
include("connection_main.php");
include("function.inc.php");
include("header_alt2.inc.php");

$user = $_SESSION['USER'];
include("setting.inc.php");


?>
<script type="text/javascript">
  function autonumber(idtext){
      if (idtext == ""){
      }else{
              val_rp = document.getElementById(idtext);
                  val_rp.addEventListener('keyup', function(e){
                  val_rp.value = formatRupiah(this.value, '');
              });
      }
  }

  /* Fungsi formatRupiah */
  function formatRupiah(angka, prefix){
      var number_string = angka.replace(/[^.\d]/g, '').toString(),
      split           = number_string.split(','),
      sisa            = split[0].length % 3,
      rupiah          = split[0].substr(0, sisa),
      ribuan          = split[0].substr(sisa).match(/\d{3}/gi);

     if(ribuan){
          separator = sisa ? ',' : '';
          rupiah += separator + ribuan.join(',');
      }
      rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
      return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
  }
</script> 
<main class="main-wrap setting-page mb-xxl">
<br>
    <div class='row'>
      <div class='col-6'>Saldo saat ini:</div>
      <div class='col-6 text-end'><a href='form_saldo_history.php' class='btn btn-outline-secondary btn-sm'><i class="iconly-Activity icli"></i></a></div>
          <?php 
            
               echo "<div class='text-center col-12 h1'><strong>".number_format($saldo_cnet)."</strong></div>";
            
          ?>
    </div>
    <hr>
      <form class="custom-form" action="form_saldo_confirm.php" method="post">
        <div class="input-box">
          <label>Topup Poin</label>
          <input type="tel" placeholder="Masukkan Jumlah Saldo" id="nominal" name="nominal" class="form-control" required="" onkeyup="autonumber('nominal');">
        </div>
        <div class="input-box">
          <label>Metode Pembayaran</label>
          <select name='via' class="form-control" required>
            <option value=''>-- Pilih metode bayar --</option>
            <option value='QRIS'>QRIS</option>
            <?php
              foreach ($bank as $banks) {
                $value = $banks->code.";".$banks->tipe.";".$banks->fee;
                echo "<option value='$value' name='via'>$banks->bank</option>";
              }
            ?>
          </select>
        </div>
        <div class="fixed-bottom bg-white">
      <div class="input-box mx-2">
        <button type="submit" class="btn-solid bg-dark"> Lanjut</button>
      </div>
    </div>
  </form>
</main>
 <?=$msg?>
<?php
include("footer.inc.php");
?>
