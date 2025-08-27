<?php
$title = "SETTLEMENT LIST";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");

$idsales = $_SESSION['IDSALES'];
$gudang = $_SESSION['IDSTORE'];
$tanggal = date("Ymd-his");

$totalxx = 0;
$qry = "select * from settlement where date(date_settle) = '".date("Y-m-d")."'";

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $date = $rs->date_settle;
    $idsales = $rs->idsalesman;
    $gudang = $rs->gudang;
  }
}

?>
 <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<!-- Main Start -->
    <main class="main-wrap setting-page mb-xxl">

          <!-- Form Section Start -->
	 <form class="custom-form" method="post" enctype='multipart/form-data'>
		<div class="type-password">
          <label class="font-sm" for="password">Upload Struk</label>
          <div class="input-box mb-0">
			<div class='row m-2'>
						<div class='col-9' >
							<input type="file" name="listGambar[]" accept="image/*" multiple id="actual-btn" required>
						</div>
						<div class='col-3 pt-1'>
							<input type='submit' class='btn-solid bg-danger' name='uploadimg' value='Kirim' style="text-align:right;">
						</div>
						<div class='col-12'>
							<strong><a href='struk_uploaded.php'><?=$jum_data?> Gambar diupload</a></strong>
						</div>
			</div>
          </div>
        </div>
        <hr />
		</form>
      <form class="custom-form" method="post" >

        <div class="type-password">
          <label class="font-sm" for="password">Total Penjualan</label>
          <div class="input-box mb-0">
            <i class="iconly-Ticket-Star icli"></i>
            <input id="text" type="text" value="<?=$totalxx?>" autocomplete="off" class="form-control">
			&emsp;Total Fee: <?=$tfee?><br>
			&emsp;Total Bayar: <?=$tbayarx?>
          </div>
        </div>
        <hr />
		
        <div class="type-password">
          <label class="font-sm" for="password">Total dalam Antrian</label>
          <div class="input-box mb-0">
            <i class="iconly-Notification icli"></i>
            <input id="text" type="text" value="<?=$totali?>" autocomplete="off" class="form-control">
          </div>
        </div>
        <hr />
        <div class="type-password">
          <label class="font-sm" for="password">Settlement</label>
          <div class="input-box mb-0">
            <i class="iconly-Lock icli"></i>
            <input id="text" type="text" placeholder="Anda yakin melakukan settlement?" autocomplete="off" class="form-control">
          </div>
        </div>
        <input type="submit" class="btn-solid" name="proses" value="YA" <?=$dis?>>
      </form>
    </main>
    <!-- Main End -->

    <br />
    <?=$msg?>
<?php
include("footer.inc.php");
?>
