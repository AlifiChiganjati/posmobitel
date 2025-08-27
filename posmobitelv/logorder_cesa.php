<?php
session_start();
include("session_check.php");
$title = "LOG ORDER SMARTFREN";
include("function.inc.php");
include("header_alt2.inc.php");

function logh($idsales){
	include("connection_cesa.inc.php");
	  
	$qry = "select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from log_sales_invoice where idsalesman ='$idsales' and date(date_order) = date(now()) group by no_faktur order by date_order desc limit 10";

	$datex = date("Y-m-d");
	$datey = date("Y-m-d", strtotime("-1 days"));
	$qry = "select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from log_sales_invoice where idsalesman ='$idsales' and date(date_order) between '$datey' and '$datex' group by no_faktur order by date_order desc limit 100";

	$qry = "select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from log_sales_invoice where idsalesman ='$idsales' and date(date_order) between '$datey' and '$datex' group by no_faktur UNION
	select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from sales_invoice where idsalesman ='$idsales' and date(date_order) between '$datey' and '$datex' and status > 0 group by no_faktur
	order by date_order desc limit 100";


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

		/*
		if($rs->tipe == "SN1"){		  
		  $linkprinto = "print_struk_fisik.php?det=$faktur";
		}else{
		  $linkprinto = "print_struk.php?det=$faktur";
		}
		*/
		
		 if($rs->tipe == "SN1"){
		  $linkprint = "<a href='print_struk_fisik.php?det=$faktur'><i class='iconly-Paper icli'></i></a>";
		  $urlprint = "print_struk_fisik.php?det=$faktur";
		}else{
		  $linkprint = "<a href='print_struk.php?det=$faktur'><i class='iconly-Paper icli'></i></a>";
		  $urlprint = "print_struk.php?det=$faktur";
		}
				   
		 $res .= "<div class='offer-box'>
                    <div class='media'>
                     
					 
					   <div class='icon-wrap'>
							$linkprint
                      </div>
						  <div class='media-body'>
							<h3 class='font-sm title-color'>$rs->id_customer<br/>
							<small>$name<br />$rs->date_order</small></h3>
						  </div>
					  
                      <span class='badges font-theme text-end'> 
					  <span class='font-xs content-color'>
						<a href='$urlprint'>
							<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-printer' viewBox='0 0 16 16'>
							  <path d='M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z'/>
							  <path d='M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z'/>
							</svg>
						</a>
						<small>$tipe<br>$status</small></span>
						<br>".number_format($total)."</span>

                    </div>
                  </div><hr>";
				  
		//$totals += $rs->total;
	  }
	  return $res;
	} 
}
include("logorder_menu.php");
$log_cesa = logh($idsales); 
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
  echo $log_cesa;
  
echo "      </div>
          </div>
        </section>
      </main>";
}
include("footer.inc.php");
?>
