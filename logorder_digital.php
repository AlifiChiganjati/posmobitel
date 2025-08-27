<?php
session_start();
include("session_check.php");
$title = "LOG ORDER DIGITAL";
include("function.inc.php");
include("header_alt2.inc.php");

function logh($databases,$idsales){
  if($databases == "rti"){
		include("connection_rti.inc.php");
  }else if($databases == "cesa"){
	  include("connection_cesa.inc.php");
  }else{
    include("connection_rai.inc.php");
  }
  
  if($databases == "cesa"){
	  
	$qry = "select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from log_sales_invoice where idsalesman ='$idsales' and date(date_order) = date(now()) group by no_faktur order by date_order desc limit 10";

	$datex = date("Y-m-d");
	$datey = date("Y-m-d", strtotime("-1 days"));
	$qry = "select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from log_sales_invoice where idsalesman ='$idsales' and date(date_order) between '$datey' and '$datex' group by no_faktur order by date_order desc limit 10";

	//echo $qry;

	$sql = mysql_query($qry);
	$row = mysql_num_rows($sql);
	if($row > 0){
	  while ($rs = mysql_fetch_object($sql)){
		$ixpro = strtoupper($rs->id_product);
		//$dxpro = strtoupper($rs->name_product);
		$total = $rs->total;
		$unit = ($rs->unit_price == "1")? $total : $rs->unit_price;
		$arrcust = data_customer($rs->id_customer);
		$name = $arrcust->name;
		$status = status_transaksi($rs->status_server);
		$faktur = base64_encode($rs->no_faktur);

		if($rs->tipe == "SN1"){		  
		  $linkprinto = "print_struk_fisik.php?det=$faktur";
		}else{
		  $linkprinto = "print_struk.php?det=$faktur";
		}
				   
		 $res .= "<div class='offer-box'>
                    <div class='media'>
                     
					  <a href='$linkprinto'>
					   <div class='icon-wrap'>
                        <i class='iconly-Message icli'></i>
                      </div>
					  </a>
						  <div class='media-body'>
							<h3 class='font-sm title-color'>$rs->id_customer<br/>
							<small>$name<br />$rs->date_order</small></h3>
						  </div>
					  
                      <span class='badges font-theme text-end'> <span class='font-xs content-color'><small>$tipe<br>$status</small></span><br>".number_format($total)."</span>

                    </div>
                  </div><hr>";
				  
		//$totals += $rs->total;
	  }
	  return $res;
	}else{
		$res = false;
	}
  }else{
	  
	   $qry = "select *, sum(harga * qty) as totals from order_det where sales='$idsales' group by no_faktur";
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
      $res .= "<div class='offer-box'>
                      <div class='media'>
              <div class='icon-wrap'>
                <i class='iconly-Message icli'></i>
              </div>
                <a href='logorder_detail.php?nf=$faktur'>
                <div class='media-body'>
                  <div class='row'>
                    <div class='col-6'><small>$tanggal</small></div>
                    <div class='col-6 text-end'>Rp<strong>".number_format($total)."</strong></div>
                    <div class='col-9'>$faktur <br />
					<small>$rs->via</small></div>
                    <div class='col-12 text-center mt-1'>Pembayaran belum diterima</div>
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
  
}
include("logorder_menu.php");
$log_cesa = logh("cesa",$idsales);
$log_rti  = logh("rti",$idsales);      
$log_rai  = logh("rai",$idsales);   
if($log_rti == false and $log_rai == false and $log_cesa == false){
    echo "<main class='main-wrap empty-cart mb-xxl'>
          <div class='banner-box'>
            <img src='assets/images/empty.png' class='img-fluid' alt='404'>
          </div>
          <section class='error mb-large'>
            <h2 class='font-lg'>Data tidak ditemukan !!</h2>
            <p class='content-color font-md'>Tampaknya anda belum melakukan transaksi</p>
            <a href='index.php' class='btn-solid'>Mulai Bertransaksi</a>
          </section>
        </main>";
}else{
  echo "<main class='main-wrap notification-page mb-xxl'>
        <section class='tab-content ratio2_1' id='pills-tabContent'>
          <div class='tab-pane fade show active' id='offer1' role='tabpanel' aria-labelledby='offer1-tab'>
            <div class='offer-wrap'>";
  echo $log_cesa;
  echo $log_rti;
  echo $log_rai;
  
echo "      </div>
          </div>
        </section>
      </main>";
}
include("footer.inc.php");
?>
