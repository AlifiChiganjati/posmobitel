<?php
session_start();
$title = "FORM PESANAN";
include("connection.inc.php");
include("function.inc.php");
include("session_check.php");
include("header_mini.inc.php");
$idsales = $_SESSION['IDSALES'];
$idstore = $_SESSION['IDSTORE'];

$vscanned = isset($_POST['qrresux'])? cleanall($_POST['qrresux']) : "";
$barscanned = isset($_POST['qrresuxbar'])? cleanall($_POST['qrresuxbar']) : "";
$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";

if($vscanned){
	$vscanned = substr($vscanned, 5, 16); //S221X065000007794649800001082125003927
}

if($barscanned){
	$vscanned = trim($barscanned);
}

if($_POST['scan']){
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";

	echo "<script language=javascript> location.href = 'cam/index.php?idcustomer=$idcustomer&idoutlet=$idoutlet&namacustomer=$namacustomer'; </script>";
}

if($_POST['bcode']){
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$idoutlet = isset($_POST['idoutlet'])? cleanall($_POST['idoutlet']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";

	/*
	echo "<script language=javascript> location.href = 'barcode/live/index.php?idcustomer=$idcustomer&idoutlet=$idoutlet&namacustomer=$namacustomer'; </script>";
	*/

	echo "<script language=javascript> location.href = 'newbarcode/index.html?idcustomer=$idcustomer&idoutlet=$idoutlet&namacustomer=$namacustomer'; </script>";
}


$depo = $_SESSION['DEPO'];
$act = isset($_GET['act'])? cleanall($_GET['act']) : "";
$ix = isset($_GET['ix'])? cleanall($_GET['ix']) : ""; //id
$ip = isset($_GET['ip'])? cleanall($_GET['ip']) : ""; //idproduct
$im = isset($_GET['im'])? cleanall($_GET['im']) : ""; //imei

if($act == "del"){
	if($idsales && $ix && $ip){
		if(mysql_query("delete from sales_invoice where id='$ix' and id_product='$ip' and idsalesman='$idsales' and status=0 and sent=0")){
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Pragma: no-cache");
			echo "<script language=javascript> location.href = 'sales_order.php'; </script>";
		}
	}
}

if($_POST['update'] == "UPDATE"){
	$eid = isset($_POST['eid'])? cleanall($_POST['eid']) : "";
	$ediscount = isset($_POST['ediscount'])? intval(charreplace($_POST['ediscount'])) : "";

	$ux = mysql_query("update sales_invoice set discount1=$ediscount where id='$eid' and status=0 and opr = '$idsales'");
	if($ux){
		$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='3000' data-bs-autohide='true'>
			<div class='toast-body'>
				<div class='content d-flex align-items-center mb-2'>
					<h6 class='mb-0'>BERHASIL</h6>
					<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
				</div><span class='mb-0 d-block'>Pemberian Diskon produk berhasil</span>
			</div>
		</div>";
	}
}

if($_POST['cart'] == "TAMBAHKAN KE CART"){
	$imei = isset($_POST['imei'])? cleanall($_POST['imei']) : "-";
	$idcustomer = isset($_POST['idcustomer'])? cleanall($_POST['idcustomer']) : "";
	$namacustomer = isset($_POST['namacustomer'])? cleanall($_POST['namacustomer']) : "";
	$produk_name = isset($_POST['produk'])? cleanall($_POST['produk']) : "";
	$product = isset($_POST['idproduk'])? cleanall($_POST['idproduk']) : "";
	$idpromotor = isset($_POST['idpromotor'])? cleanall($_POST['idpromotor']) : "";

	/*
	if($imei == ""){
		$imei = date("YmdHi") . "-".generate_string(2);
	}
	*/



	if($idsales){ //$idsales && $imei
		$product = get_product_imei($imei, $idstore);


		if($product){
				if(is_queueing($imei)){
					$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='4000' data-bs-autohide='true'>
						<div class='toast-body'>
							<div class='content d-flex align-items-center mb-2'>
								<h6 class='mb-0'>WARNING</h6>
								<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
							</div><span class='mb-0 d-block'>No IMEI sudah ada dalam antrian</span>
						</div>
					</div>";
				}else{

					$harga = cek_harga_product($product);
					if($harga){
						//$is_stok_exist = is_stock_inwarehouse($imei, $gudang);
						$is_stok_exist = true; //temporary
						if($is_stok_exist){
							$salesman3 = ""; $salesman4 = "";



							$sql = "insert into sales_invoice (date_order, idsalesman, id_product, qty, unit_price, total,
							warehouse_name, id_customer, detail_notes, description, opr, tipe, salesman3, salesman4) values (now(), '$idsales', '$product', 1, $harga, $harga, '$gudang', '$idcustomer', '$imei', '$imei', '$idsales', 'SALES', '$salesman3', '$salesman4')";

							if(!mysql_query($sql)){
								$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='4000' data-bs-autohide='true'>
									<div class='toast-body'>
										<div class='content d-flex align-items-center mb-2'>
											<h6 class='mb-0'>Error Logic</h6>
											<button class='btn-close btn-danger ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
										</div><span class='mb-0 d-block'>Internal Logic Program!</span>
									</div>
								</div>";
							}else{
								$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='1500' data-bs-autohide='true'>
									<div class='toast-body'>
										<div class='content d-flex align-items-center mb-2'>
											<h6 class='mb-0'>OK</h6>
											<button class='btn-close btn-success ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
										</div><span class='mb-0 d-block'>Produk: $product, IMEI: $imei masuk ke Cart Antrian</span>
									</div>
								</div>";
							}
						}else{
							$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='1500' data-bs-autohide='true'>
								<div class='toast-body'>
									<div class='content d-flex align-items-center mb-2'>
										<h6 class='mb-0'>Pesan</h6>
										<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
									</div><span class='mb-0 d-block'>IMEI tidak ditemukan di Gudang anda</span>
								</div>
							</div>";
						}
					}else{
						$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='4000' data-bs-autohide='true'>
							<div class='toast-body'>
								<div class='content d-flex align-items-center mb-2'>
									<h6 class='mb-0'>INFO</h6>
									<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
								</div><span class='mb-0 d-block'>Harga produk tidak ditemukan</span>
							</div>
						</div>";
					}
				}

		}else{
			$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='4000' data-bs-autohide='true'>
				<div class='toast-body'>
					<div class='content d-flex align-items-center mb-2'>
						<h6 class='mb-0'>Warning</h6>
						<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
					</div><span class='mb-0 d-block'>IMEI ini tidak ada di Gudang anda! :  $idstore</span>
				</div>
			</div>";
		}


	}else{
		$msg = "<div class='toast pwa-install-alert shadow bg-white' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='4000' data-bs-autohide='true'>
			<div class='toast-body'>
				<div class='content d-flex align-items-center mb-2'>
					<h6 class='mb-0'>Pesan</h6>
					<button class='btn-close btn-warning ms-auto' type='button' data-bs-dismiss='toast' aria-label='Close'></button>
				</div><span class='mb-0 d-block'>Data tidak lengkap!</span>
			</div>
		</div>";
	}
}


$sqli = "select * from sales_invoice where idsalesman='$idsales' and status =0 order by id desc";
//echo $sqli;
$qsql = mysql_query($sqli);
	$rex = false;
	$i=0;
	while($rs = mysql_fetch_object($qsql)){
		$unitprice = number_format($rs->unit_price);
		$diskon1 = number_format($rs->discount1);
		$nama_product = data_product($rs->id_product);
		$link = "<a data-toggle='modal'
		data-target='#smallDiscount' data-myid='$rs->id' data-mypro='$rs->id_product' data-myprice='$unitprice'
		title='Edit' class='btn btn-sm btn-outline-warning'><i class='lni lni-minus'></i></a>";

		$listdata .= "
		<tr>
			<td colspan='3'><small>$nama_product</small></td>
		</tr>
		<tr>
				<th scope='row'>
					<a class='remove-product' href='?act=del&ix=$rs->id&ip=$rs->id_product&im=$rs->detail_notes'>
					<i class='lni lni-close'></i>
					</a>
				</th>
					<td>
					<a href='#'><small> $rs->id_product <br />$rs->detail_notes </small></a>

					$link
					</td>
				<td>
					<div class='quantity'>
						<input class='qty-text' type='text' name='qty' value='$unitprice'>
						<input class='qty-text' type='text' id='discount1' name='discount1' value='$diskon1' placeholder='diskon'>

					</div>
				</td>
			</tr>";
			$unit += $rs->qty;
			$totals += $rs->unit_price;
			$id_customer = $rs->id_customer;
			$rex = true;
			$i++;
	}


	if($rex){
		$arrdata = data_customer($id_customer);
		$idcustomer = $id_customer;
		$namacustomer = $arrdata->name;
	}


?>
<style>
#reader {
    width: 380px;
    background-color: #f1f1f1;
}
@media(max-width: 600px) {
	#reader {
		width: 300px;
		height: 200px;
	}
}
.empty {
    display: block;
    width: 100%;
    height: 20px;
}
#backcamera{
	display: none;
	width: 0%;
    height: 0%;
}
</style>

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
            split           = number_string.split('.'),
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

<script>
$(function () {
  $('#smallDiscount').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var recid = button.data('myid');
		var recpro = button.data('mypro');
		var recprice = button.data('myprice');

    var modal = $(this);
    modal.find('#eid').val(recid);
		modal.find('#eproduct').val(recpro);
		modal.find('#eprice').val(recprice);
		modal.find('#ediscount').focus();

  });
})

</script>

﻿<div class="page-content-wrapper">
      <div class="container">
        <?=$msg?>
        <!-- Cart Wrapper-->
        <form method="post">
        <div class="checkout-wrapper-area">
          <!-- Billing Address-->
          <div class="billing-information-card mb-3">
            <div class="card billing-information-title-card bg-warning">
              <div class="card-body">
                <h6 class="text-center mb-0 text-white">PESANAN PENJUALAN</h6>
              </div>
            </div>
            <div class="card user-data-card">
              <div class="card-body">

								<div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-frame-expand"></i><span>No Customer</span></div>
                  <div class="data-content">
										<input class="form-control" type="text" id="idcustomer" name="idcustomer" onkeyup="autoCompletecustomer();"
										autocomplete="Off" value="<?=$idcustomer?>">
									</div>
                </div>
								<div id="hasilcustomer"> </div>

                <div class="single-profile-data d-flex align-items-center justify-content-between">
                  <div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Nama Customer</span></div>
                  <div class="data-content">
											<textarea class="form-control" id="namacustomer" name="namacustomer" autocomplete="Off" rows="2" readonly><?=$namacustomer?>
											</textarea>
									</div>
                </div>


								<div class="accordian-area-wrapper mt-3">

								 <div class="card accordian-card">
									 <div class="card-body">
										  <div class="accordion" id="accordion1">

											 <div class="accordian-header" id="headingOne">
												 <button class="d-flex align-items-center justify-content-between w-100 collapsed btn" type="button"
												 data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
												 <span><i class="lni lni-camera"></i>Produk dengan Imei</span><i class="lni lni-chevron-right"></i></button>
											 </div>
											 <div class="collapse" id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordion1">
												 <div class="card-body d-flex align-items-center justify-content-between">
												 	<button class="btn button-flat-outline w-50" type="button" id="start" name="start" value="start">START SCAN</button>
			 										
			 			            			 </div>

			 			            			 <div id="reader"></div>			 										
			 										<div id="scanned-result"></div>
												    <div id="backcamera">
												        <select id="facingMode">
												            <option value="user">user</option>
												            <option value="environment" selected>environment</option>
												        </select>
												   </div>
											 </div>

											 <div class="accordian-header" id="headingTwo">
												 <button class="d-flex align-items-center justify-content-between w-100 collapsed btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
													 <span><i class="lni lni-camera"></i>Produk Non Imei</span><i class="lni lni-chevron-right"></i></button>
											 </div>
											 <div class="collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordion1">
												 <div class="card-body d-flex align-items-center justify-content-between">
			 										<button class="btn button-flat-outline w-50" type="submit" id="scan" name="scan" value="scan">QRCODE</button>
			 			              &nbsp;
			 										<button class="btn button-flat-outline w-50" type="submit" id="bcode" name="bcode" value="bcode">BARCODE</button>
			 			            </div>
											 </div>
										 </div>
									 </div>
								 </div>
							 </div>



						 	<!--
								<div class="mb-3">

									<div class="card-body d-flex align-items-center justify-content-between">
										<button class="btn dangerx w-50" type="submit" id="scan" name="scan" value="scan">QRCODE</button>
			              &nbsp;
										<button class="btn warningx w-50" type="submit" id="bcode" name="bcode" value="bcode">BARCODE</button>
			            </div>


									<div class="coupon-form">
											<input class="form-control" type="text" id="no_imei" name="no_imei" placeholder="SN" value="<?=$vscanned?>" autocomplete="Off">
									</div>
                </div>
							-->

							  <!--
								<div class="single-profile-data d-flex align-items-center justify-content-between">
									<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Nama Produk</span></div>
									<div class="data-content">
											<input class="form-control" type="text" id="produk" name="produk" value="<?=$produk?>" onkeyup="autoCompleteproduct();" autocomplete="Off">
									</div>
								</div>

									<div id="hasilproduk"> </div>

								<div class="single-profile-data d-flex align-items-center justify-content-between">
									<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>Id Produk</span></div>
									<div class="data-content">
											<input class="form-control" type="text" id="idproduk" name="idproduk" value="<?=$idproduk?>" autocomplete="Off" readonly>
									</div>
								</div>
							-->

								<div class="single-profile-data d-flex align-items-center justify-content-between">
									<div class="title d-flex align-items-center"><i class="lni lni-plus"></i><span>IMEI</span></div>
									<div class="data-content">
											<input class="form-control" type="text" id="imei" name="imei" value="" autocomplete="Off">
									</div>
								</div>


								<input type="submit" name="cart" value="TAMBAHKAN KE CART" class="btn btn-sm btn-warning w-100">

              </div>
            </div>
          </div>

        </div>
      </form>

			<div class="cart-wrapper-area py-3">
				<div class="cart-table card mb-3">
					<div class="table-responsive card-body">
						<table class="table mb-0">
							<tbody>
								<?=$listdata?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="card cart-amount-area">
				 <div class="card-body d-flex align-items-center justify-content-between">
					 <a class="btn btn-sm btn-warning" href="checkout.php">BERIKUTNYA</a>
				 </div>

			 </div>
			</div>

      </div>
    </div>

		<div class="modal fade" id="smallDiscount" tabindex="-1" role="dialog">
	    <div class="modal-dialog modal-sm" role="document">
	        <div class="modal-content">
			<div class="modal-header">
	        <h4 class="modal-title">Edit</h4>
	            <button type="button" id="buttonclosemsg" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
	        </div>
	        <div class="modal-body">
						<form method="post">
	            <div class="form-group row">
								<div class="form-group text-start mb-4"><span>Kode Produk</span>
								 <input type="hidden" class="form-control" id="eid" name="eid" readonly>
				 			 	 <input class="form-control" id="eproduct" name="eproduct" type="text" placeholder="Produk" value="" readonly>
							 </div>
	            </div>

							<div class="form-group row">
								<div class="form-group text-start mb-4"><span>Harga</span>
				 			 	<input type="text" class="form-control" id="eprice" name="eprice" placeholder="Harga" value="" readonly>
							 </div>
	            </div>

							<div class="form-group row">
								<div class="form-group text-start mb-4"><span>Diskon</span>
								<input class="form-control" id="ediscount" name="ediscount" type="text" placeholder="Discount" onkeyup="autonumber('ediscount');" value="" autocomplete="Off">
							 </div>
	            </div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default btn-sm waves-effect" data-dismiss="modal">CLOSE</button>
							 <input type="submit" name="update" value="UPDATE" class="btn btn-warning btn-sm waves-effect waves-light ">
					 </div>
				 </form>
	         </div>
			</div>
	    </div>
	</div>

	<script src="https://nexa.my.id/posmobile/newbarcode/html5-qrcode/html5-qrcode.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.3/highlight.min.js"></script>
	<script src="https://nexa.my.id/posmobile/html5-qrcode-demo.js"></script>
	

    <?php
    include("footer.inc.php");
     ?>
