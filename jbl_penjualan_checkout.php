<?php
$title = "CHECKOUT";
session_start();
include("session_check.php");
include("connection_jbl.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
$idsales = $_SESSION['IDSALES'];
$depo = $_SESSION['DEPO'];

//Pengkondisian str_replace
if (stripos($depo, 'CTA PAMERAN') !== false) {
    $depo_replace = str_replace('CTA ', ' ', $depo);
} elseif (stripos($depo, 'CTA ') === 0) {
    $depo_replace = str_replace('CTA ', 'DC ', $depo);
} else {
    $depo_replace = $depo;
}

function discount($level){
	switch ($level) {
		case "1": $discount = "1"; break;
		case "2": $discount = "1"; break;
		case "3": $discount = "2"; break;
		case "4": $discount = "1.5"; break;
		case "5": $discount = "2.5"; break;
		default:
			 $discount = "1";
			break;
	}
	return $discount;
}

$viax = '';
	$qry = "select account_no, account_name from accounts where (account_name like 'Kas Besar - $depo_replace%' OR account_name LIKE '%$depo_replace') order by account_no";
	//echo $qry;
	$rs = mysql_query($qry);
  $i=0;
	while ($r = mysql_fetch_object($rs)) {
		if(strpos($r->account_name, "Kas Besar") !== false){
			$viax .= "<option value='$r->account_no'> TUNAI </option>";
		}else{
			$viax .= "<option value='$r->account_no'> $r->account_name </option>";
		}
    $i++;
	}
	$viax .= "<option value='TOP'> TOP</option>";
mysql_free_result($rs);

if($_POST['proses'] == "PROSES KE PEMBAYARAN"){
	$product_e = isset($_POST['product_e'])? cleanall($_POST['product_e']) : "";
	$qty_e = isset($_POST['qty_e'])? cleanall($_POST['qty_e']) : "";
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$namacustomer = "";
	$via = isset($_POST['via'])? cleanall($_POST['via']) : "";
	$tempo = isset($_POST['tempo'])? cleanall($_POST['tempo']) : date("d/m/Y");

	if($idsales && $idcustomer && $qty_e && $product_e && $via){
			$arrsales = data_salesman($idsales);
				if($arrsales){
					$taxdate = date("d/m/Y");
					//$nofaktur = $arrsales->id_department ."-A12/". $idsales ."-". date("mY-dHis");
					if($via == "TOP"){
						$nofaktur = $arrsales->id_department ."-K05/". $idsales ."-". date("mY-dHis");
					}else{
						$nofaktur = $arrsales->id_department ."-FKT/". $idsales ."-". date("mY-dHis");
						$tempo = $taxdate;
					}

					$data1 = $depo; //depo

					$data2 = "Kantor Pusat"; //cluster

					$transdate = $taxdate;
					//$whousename = "GUDANG " . $data1;
					$whousename = $arrsales->id_store;
					
					$arrdata = data_customer_fisik($idcustomer);
					$npwp = $arrdata->npwp;
					$umkm = $arrdata->umkm;

					$isnpwp = ($npwp == "00.000.000.0-000.000")? "0" : "1";
					$isumkm = ($umkm == "YA")? "1" : "0";

					$sql = mysql_query("select * from sales_invoice where status = 0 and idsalesman = '$idsales' and tipe = 'SN1'");
					$row = mysql_num_rows($sql);
					if($row > 0){ 
						while($sx = mysql_fetch_object($sql)){
							mysql_query("update sales_invoice set id_customer='$idcustomer', via='$via', status = 1, no_faktur='$nofaktur', taxdate='$taxdate', transdate='$transdate', duedate= '$tempo', data1='$data1', data2='$data2', npwp='$isnpwp', umkm='$isumkm' where id='$sx->id'");
							$id_product=$sx->id_product;
							$gudang = $sx->warehouse_name;

							//update stok
							$qty = $sx->qty;
							$stok_sales = get_stok_jbl($id_product, $gudang);

							$final_stok = $stok_sales-$qty;

							$mts = mysql_query("insert into mutasi_stok set date_process=now(), warehouse='$gudang', warehouse_reference='$idcustomer', id_product='$id_product', description='Penjualan', reff='$nofaktur', source='sales invoice',
										qty_in='0', qty_out='$qty', last_qty='$stok_sales', curr_qty='$final_stok'");
							if($mts && mysql_affected_rows() > 0){
								mysql_query("update stock_master set qty='$final_stok' where id_product='$id_product' and warehouse='$gudang'");
							}
						}

						$enfisik = base64_encode($nofaktur);

						mysql_query("insert into sales_order (date_order, no_faktur, idsalesman, tipe, cluster) values (now(), '$nofaktur', '$idsales', 'SN1', 'OTHER')");

						header("Cache-Control: no-store, no-cache, must-revalidate");
						header("Pragma: no-cache");
						echo "<script language=javascript> location.href = 'jbl_print_struk.php?det=$enfisik'; </script>";

					}
				}else{

					$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							<div class='offcanvas-body small'>
								<div class='app-info'>
									<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
									<div class='content'>
										<h3>Settlement <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
										<a href='#'>ID Anda tidak dapat melakukan transaksi / sudah settlement</a>
									</div>
								</div>
							</div>
						</div>";
				}

	}else{

		$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				<div class='offcanvas-body small'>
					<div class='app-info'>
						<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						<div class='content'>
							<h3>Incomplete Data <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
							<a href='#'>Data tidak boleh kosong!</a>
						</div>
					</div>
				</div>
			</div>";
	}
}

$sql = "select qty as jml, unit_price, total, id_product, id_customer, description from sales_invoice where idsalesman = '$idsales' and status = 0 and tipe='SN1'";
$msq = mysql_query($sql);
//echo $sql;

while($re = mysql_fetch_object($msq)){
	$totals = number_format($re->total);
	$arrdata = data_customer_fisik_jbl($re->id_customer);
	$idcustomer = $re->id_customer;
	$idoutlet = $arrdata->outlet_id;
	$namacustomer = $arrdata->name;
	$unit_price = number_format($re->unit_price);
	$so .= "<li></li>
			<li>
            	<span>$re->description [$re->id_product]</span>
            	<input type='hidden' id='product_e' name='product_e' value='$re->id_product'>
          	</li>
          	<li>
            	<span>$re->jml x $unit_price</span>
				<span>$totals</span>
				<input type='hidden' id='qty_e' name='qty_e' value='$re->jml'>
				<input type='hidden' id='total_e' name='total_e' value='$re->total'>
          </li>";

		$product = $re->id_product;
		$jml = $re->jml;
		$total += $re->total;
}

$so .= "<li>
					<span>Total</span>
					<span>".number_format($total)."</span>
				</li>";

	$start_date = date("Y/m/d");
	$date = strtotime($start_date);
	$date = strtotime("+7 day", $date);
	$date = date('d/m/Y', $date);

	$datex = strtotime($start_date);
	$datex = strtotime("+10 day", $datex);
	$datex = date('d/m/Y', $datex);

	$datez = strtotime($start_date);
	$datez = strtotime("+14 day", $datez);
	$datez = date('d/m/Y', $datez);


?>
<!-- Main Start -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
	$(function() {
	    $('#opsitempo').hide();
	    $('#via').change(function(){
	        if($('#via').val() == 'TOP') {
	            $('#opsitempo').show();
	        } else {
	            $('#opsitempo').hide();
	        }
	    });
	});
</script>


<form method="post" class="custom-form">
    <main class="main-wrap order-detail cart-page mb-xxl">

			<div class="section-p-b">
          <div class="banner-box">
            <div class="media">
              <div class="img"><img src="assets/icons/svg/box.svg" alt="box"></div>
              <div class="media-body">
                <span class="font-sm">Order ID: <?=$idsales?></span>
                <span class="font-md">Belum diproses</span>
              </div>
            </div>
          </div>
        </div>

      <!-- Tab Content Start -->
      <section class="order-detail pt-0">
        <h3 class="title-2">Order Details</h3>

        <!-- Detail list Start -->
        <ul>
          <li>
            <span>No Reseller</span>
            <span><?=$idcustomer?></span>
            <input type="hidden" id="idcustomer" name="idcustomer" value="<?=$idcustomer?>">
          </li>
          <li>
          <span>ID Outlet</span>
            <span><?=$idoutlet?></span>
             <input type="hidden" id="idoutlet" name="idoutlet" value="<?=$idoutlet?>">
          </li>

					<li>
          <span>Nama</span>
            <span><?=$namacustomer?></span>
             <input type="hidden" id="namacustomer" name="namacustomer" value="<?=$namacustomer?>">
          </li>

				 	<?=$so?>
        </ul>
        <!-- Detail list End -->
      </section>

      <div class="input-box">
          <select name="via" id="via" class="form-control">
						 <option value="">Pilih metode pembayaran</option>
						 <?=$viax?>
					 </select>
      </div>

			<div class="input-box" id="opsitempo">
					<h3 class="title-2">Tempo</h3>
					<div class="accordion-body net-banking">
					  <div class="row">
								<div class="input-box col-4">
									<input id="tempo1" type="radio" name="tempo" value="<?=$date?>">
									<label class="form-check-label" for="c-bank"><?=$date?></label>
								</div>

								<div class="input-box col-4">
									<input id="tempo2" type="radio" name="tempo" value="<?=$datex?>">
									<label class="form-check-label" for="c-bank"><?=$datex?></label>
								</div>

								<div class="input-box col-4">
									<input id="tempo3" type="radio" name="tempo" value="<?=$datez?>">
									<label class="form-check-label" for="c-bank"><?=$datez?></label>
								</div>
							 </div>
						</div>
				</div>

			<div class="input-box">
					<a href="jbl_penjualan.php" class="d-block btn-outline-grey"><i class="iconly-Arrow-Left icli"></i> Edit Orderan</a>
			</div>

		<!-- Tab Content End -->
    </main>

		<footer class="footer-wrap footer-button">
      <input type="submit" class="btn-solid" name="proses" value="PROSES KE PEMBAYARAN">
    </footer>
		</form>

<?=$msg?>

    <!-- Main End -->
<?php
include("footer_alt.inc.php");
?>
