<?php
$title = "SETTLEMENT";
session_start();
//include("session_check.php");
include("connection_cesa.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$idsales = $_SESSION['IDSALES'];
$gudang = $_SESSION['IDSTORE'];

$totalxx = 0;
$qry = "select id_product, count(id) as jml, sum(qty) as tqty, sum(total) as ttotal, sum(fee) as tfee, sum(total + fee) as tbayar from log_sales_invoice where (status=4) and idsalesman = '$idsales' and date(date_order) = date(now())";
//echo $qry;

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $ixpro = strtoupper($rs->id_product);
    // $dxpro = strtoupper($rs->name_product);
    $stotal = number_format($rs->ttotal);
    $totalxx += $rs->ttotal;
	$tfee += $rs->tfee;
	$tbayarx += $rs->tbayar;
  }
}

$totalxx = number_format($totalxx);
$tfee = number_format($tfee);
$tbayarx = number_format($tbayarx);

$totali = 0;
$qry = "select count(id) as jml,
            sum(qty) as tqty, sum(total) as ttotal, sum(fee) as tfee,
            sum(total + fee) as tbayar
            from sales_invoice where idsalesman = '$idsales'";
//echo $qry;

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
    $totali += $rs->tbayar;
  }
}

$totali = number_format($totali);


if($_POST['proses'] == "YA"){
  $so = mysql_query("update salesman set settlement = 1 where idsales='$idsales'");
  if($so){

    //$qry = "select w.id_product, count(w.id) as jml, p.name_product from imei_gudang w left join products p on (w.id_product = p.id_product) where w.warehouse ='$gudang' and w.status = '0'";
    $qry = "select id_product, count(id) as jml,
                sum(qty) as tqty, sum(total) as ttotal, sum(fee) as tfee,
                sum(total + fee) as tbayar
                from log_sales_invoice
                where (status=4) and idsalesman = '$idsales' and date(date_order) = date(now()) group by id_product";

	
    $sql = mysql_query($qry);
    $row = mysql_num_rows($sql);
    if($row > 0){
      while ($rs = mysql_fetch_object($sql)){
        $ixpro = strtoupper($rs->id_product);
        // $dxpro = strtoupper($rs->name_product);
        $stotal = number_format($rs->ttotal);
        //$res .= "$ixpro : $rs->jml = $stotal";
        $msg .= "<span class='mb-0 d-block'>&#9642; $ixpro $rs->jml TRX, Total = <b>$stotal</b></span>";
        $dax .= "<div class='single-profile-data d-flex align-items-center justify-content-between'>
             <div class='title d-flex align-items-center'><i class='lni lni-minus'></i><span>$ixpr $rs->jml</span></div>
             <div class='data-content'>
                 <input class='form-control' type='text' id='total' name='total' autocomplete='Off' value='$stotal' readonly>
             </div>
           </div>";

        $total += $rs->ttotal;
      }
    }else{
      $msg = "<span class='mb-0 d-block'>Oops!! Maaf transaksi tidak ditemukan</span>";
      $dax .= "<div class='single-profile-data d-flex align-items-center justify-content-between'>
           <div class='title d-flex align-items-center'><i class='lni lni-minus'></i><span>NO Data</span></div>
           <div class='data-content'>
               <input class='form-control' type='text' id='total' name='total' autocomplete='Off' value='0' readonly>
           </div>
         </div>";
    }

/*
    $msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='90000' data-bs-autohide='true'>
      <div class='toast-body'>
        <div class='content d-flex align-items-center mb-2'>
          <h6 class='mb-0'>Settlement</h6>
          <button class='btn-close btn-success ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
        </div>
        <span class='mb-0 d-block'>Anda telah melakukan settlement. Terima Kasih.</span>
        <span class='mb-0 d-block'>Berikut Report transaksi anda hari ini</span>
        <hr />
        $msg
        <hr />
        <span class='mb-0 d-block'>Atau silahkan lihat di menu Report</span>
        <br />
        <a href='report.php' class='btn btn-sm btn-warning'>REPORT</a>
      </div>
    </div>";
*/
    $rincian = "<div class='card-body'>
    <div class='card cart-amount-area'>
      $dax
    </div></div>";

    $sx = "select idsalesman from sales_invoice where idsalesman = '$idsales' and tipe = 'SN1'";
    $mx = mysql_query($sx);
    if($rx = mysql_fetch_object($mx)){
      
	  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
        <div class='offcanvas-body small'>
          <div class='app-info'>
            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
            <div class='content'>
              <h3>Stok Fisik<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
              <a href='#'>Pengembalian Stok gagal karena masih ada transaksi anda yang sedang diproses / cek menu Fisik anda</a>
            </div>
          </div>
        </div>
      </div>";
    }else{

      $is_settle = cek_status_settlement($idsales);
      if($is_settle){
        
		$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
          <div class='offcanvas-body small'>
            <div class='app-info'>
              <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
              <div class='content'>
                <h3>Pesan<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                <a href='#'>Settlement anda sudah ada dalam antrian, Silahkan diulang jika request sebelumnya dibatalkan!</a>
              </div>
            </div>
          </div>
        </div>";
      }else{
        $qw = "select id_product, count(imei) as jml, warehouse, warehouse_from from imei_gudang where warehouse='$gudang' and status=0 and id_product NOT LIKE '%KSY%' group by id_product";
        $sw = mysql_query($qw);
        $rw = mysql_num_rows($sw);
        if($rw > 0){
          $reffo = generate_string(4);
          $reff = date("YmdHis") . $reffo;
          $ie = mysql_query("insert into settlement (date_settle, idsalesman, gudang, reff, status)
          values (now(), '$idsales', '$gudang', '$reff', 0)");

          while($dw = mysql_fetch_object($sw)){
            $io = mysql_query("insert into settlement_rincian (date_settle, idsalesman, warehouse, warehouse_reference, id_product, qty, reff, status)
            values (now(), '$idsales', '$dw->warehouse', '$dw->warehouse_from', '$dw->id_product', $dw->jml, '$reff', 0)");
          }

         
		  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
            <div class='offcanvas-body small'>
              <div class='app-info'>
                <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                <div class='content'>
                  <h3>Pesan<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                  <a href='#'>Sisa Stok di gudang anda sudah disimpan, silahkan ke Bagian Admin untuk mulai pengecekan</a>
                </div>
              </div>
            </div>
          </div>";
        }
      }
    }
  }else{
    
	$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Pesan<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'>Settlement gagal dilakukan. Internal Error</a>
          </div>
        </div>
      </div>
    </div>";
  }
}

?>
﻿
<main class="main-wrap setting-page mb-xxl">

          <!-- Form Section Start -->
      <form class="custom-form" method="post">

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
        <input type="submit" class="btn-solid" name="proses" value="YA">
      </form>
    </main>
    <!-- Main End -->
	

<?=$msg?>

<?php include("footer_alt.inc.php");?>
