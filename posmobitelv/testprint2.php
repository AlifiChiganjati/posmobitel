<?php
session_start();
//include("session_check.php");
$title = "LOG ORDER SMARTFREN";
include("function.inc.php");
include("header_alt2.inc.php");

$idsales = "CSA08879";
function logh($idsales){
	include("connection_cesa.inc.php");
	  
	$qry = "select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from log_sales_invoice where idsalesman ='$idsales' and date(date_order) = date(now()) group by no_faktur order by date_order desc limit 10";

	$datex = date("Y-m-d");
	$datey = date("Y-m-d", strtotime("-1 days"));
	$qry = "select date_order, id_product, sum(qty) as qty, sum(total) as total, unit_price, id_customer, status_server, no_faktur, tipe
	from log_sales_invoice where idsalesman ='$idsales' and date(date_order) between '$datey' and '$datex' group by no_faktur order by date_order desc limit 100";

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
						<a href='$urlprint'>PRINT</a>
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
