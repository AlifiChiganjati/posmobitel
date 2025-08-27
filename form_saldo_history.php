<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
$title='History Saldo';
$back_button='form_saldo.php';
include("cek_login.inc.php");
include("connection_main.php");
include("function.inc.php");
include("header_alt3.inc.php");
include("setting.inc.php");
?>
 
<main class="main-wrap setting-page mb-xxl">
    <div class="row mb-2">
      <div class="col-6"><strong>25 Topup Terakhir</strong></div>
      <!--<div class="col-6 text-end"><a data-bs-toggle='offcanvas' data-bs-target='#filterTanggal' aria-controls='filter' class='btn btn-outline-danger btn-sm'><i class="iconly-Calendar icli"></i></a></div>-->
    </div>
        <?php 
        $qry = mysql_query("select * from validasi_saldo where user='$user' order by id desc limit 25");
		while($his_saldos = mysql_fetch_object($qry)) {
            $nominal = $his_saldos->jmldep;
            $tiket = $his_saldos->adm;
            $jumlah = $nominal+$tiket;
            $via = $his_saldos->respon;
            $status = $his_saldos->status;
            $bank = $his_saldos->via;
			$nf=$his_saldos->no_faktur;
              switch ($status) {
                case '4':
                  $sts = "SUKSES <i class='fa fa-check'></i>";
                  $info = "";
                  break;
                case '0':
                  $sts = "PENDING <i class='fa fa-timer'></i>";
                  $info ="<div class='col-12 text-center'><small>$via</small></div>";
                  break;
                case '2':
                  $sts = "GAGAL <i class='fa fa-alert'></i>";
                  $info = "";
                  break;
              } 
			  ?>
			  <a href='form_saldo_sukses.php?nf=<?=$nf?>'>
				<div class="row border  mb-2 mx-1 rounded p-2 shadow-sm">
				
					<div class="col-6"><strong><?=$sts?></strong></div><div class="col-6 text-end"><small><?=$his_saldos->tanggal?></small></div>
					<div class="row g-0">
						
					  <div class="col-2 align-self-center text-center"><img src="<?=ASSETS_DIR?>/menu_icons/7.png" width="85%"></div>
					  <div class="col-10 gap-0">
						<div class="row mx-1">
						  <div class="col-4"><small>Bank</small></div>
						  <div class="col-8 text-end"><small><?=$bank?></small></div>  
						  <div class="col-4"><small>Nominal</small></div>
						  <div class="col-8 text-end"><small><?=number_format($nominal)?></small></div>

						  <div class="col-4"><small>Admin</small></div>
						  <div class="col-8 text-end"><small><?=number_format($tiket)?></small></div>

						  <div class="col-4"><small>Total</small></div>
						  <div class="col-8 text-end"><small> <strong><?=number_format($jumlah)?></strong></small></div> 
						  
						</div>
					  </div>
					</div>
				</div>
			</a>
          <?php
          }
        ?>
        
  </main>
 <?=$msg?>
<?php
include("footer.inc.php");
?>
