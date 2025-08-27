<?php
session_start();
include("session_check.php");
$title = "LOG ORDER XIAOMI";
include("function.inc.php");
include("header_alt2.inc.php");

function logh($idsales,$filters){
	include("connection_rti.inc.php");
		if($filters == "all"){
			$qry = "select *, sum(harga * qty) as totals from order_det where sales='$idsales' group by no_faktur UNION ALL
			select *, sum(harga * qty) as totals from log_order_det where sales='$idsales' group by no_faktur";
		}else if($filters == "belum"){
			$qry = "select *, sum(harga * qty) as totals from order_det where sales='$idsales' and status=0 group by no_faktur UNION ALL
			select *, sum(harga * qty) as totals from log_order_det where sales='$idsales' and status=0 group by no_faktur";
		}else if($filters == "sudah"){
			$qry = "select *, sum(harga * qty) as totals from order_det where sales='$idsales' and status=4 group by no_faktur UNION ALL
			select *, sum(harga * qty) as totals from log_order_det where sales='$idsales' and status=4 group by no_faktur";
		}
  $sql = mysql_query($qry);
  $row = mysql_num_rows($sql);
  if($row > 0){
    while ($rs = mysql_fetch_object($sql)){
      $faktur = $rs->no_faktur;
      $tanggal = $rs->tanggal_order;
      //$qty = $rs->qtyx;
      //$harga = $rs->harga;
      //$total= $qty * $harga;
	  $total = $rs->totals;
	  $status = $rs->status;
	  if ($status <4){
		  $vstatus = "Pembayaran belum diterima";
	  }else{
		  $vstatus = "Pembayaran diterima";
	  }
      $res .= "<div class='offer-box'>
                      <div class='media'>
              <div class='icon-wrap'>
                <i class='iconly-Message icli'></i>
              </div>
                <a href='logorder_detail.php?nf=$faktur&b=logorder_rti'>
                <div class='media-body'>
                  <div class='row'>
                    <div class='col-6'><small>$tanggal</small></div>
                    <div class='col-6 text-end'>Rp<strong>".number_format($total)."</strong></div>
                    <div class='col-9'>$faktur <br />
					<small>$rs->via</small></div>
                    <div class='col-12 text-center mt-1'>$vstatus</div>
                  </div>
                </div>
                </a>
              </div>
           </div><hr>";
    }
  }else{
    $res = false;
  }
  return $res;  
}
include("logorder_menu.php");
$log_rti  = logh($idsales,$filters);   
if($log_rti == false and $log_rai == false and $log_cesa == false){
    echo "<main class='main-wrap empty-cart mb-xxl'>
          <div class='banner-box'>
            <img src='assets/images/empty.png' class='img-fluid' alt='404'>
          </div>
          <section class='error mb-large'>
            <h2 class='font-lg'>Data tidak ditemukan !!</h2>
            <p class='content-color font-md'>Tampaknya anda belum melakukan transaksi</p>
            <a href='home.php' class='btn-solid bg-dark'>Mulai Bertransaksi</a>
          </section>
        </main>";
}else{
  echo "<main class='main-wrap notification-page mb-xxl'>
        <section class='tab-content ratio2_1' id='pills-tabContent'>
          <div class='tab-pane fade show active' id='offer1' role='tabpanel' aria-labelledby='offer1-tab'>
            <div class='offer-wrap'>";
  echo $log_rti;
  
echo "      </div>
          </div>
        </section>
      </main>";
}
?>

<?php
include("footer.inc.php");
?>
