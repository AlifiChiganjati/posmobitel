<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
include("connection_cesa.inc.php");
include("function.inc.php");
include("session_check.php");
//include("header_mini.inc.php");
$idsales = $_SESSION['IDSALES'];

$det = isset($_GET['det'])? cleanall($_GET['det']) : "";
$nofaktur = base64_decode($det);
$sql = "select l.id_product, l.no_faktur, l.detail_notes, l.id_customer, p.name_product, l.id_customer, l.qty, l.unit_price, l.total, l.fee, l.discount from sales_invoice l
left join products p on (l.id_product = p.id_product) where l.idsalesman = '$idsales' and l.no_faktur='$nofaktur'";

//echo $sql;
$msq = mysql_query($sql);

if($re = mysql_fetch_object($msq)){
	$totals = number_format($re->total);
	$qty = number_format($re->qty);
	$fee = number_format($re->fee);
	$unit_price = $re->unit_price;
	$pro = $re->id_product;
	$nameproduct = $re->name_product;
	$cust = $re->id_customer;
	$no_faktur = $re->no_faktur;
	$no_hpcust = $re->detail_notes;
	$data_arr = data_salesman($idsales);
	$nama_cvs = $data_arr->name;
	//$nama_cust = name_customer($cust);
	$diskon = $re->discount;
  $total = $re->total;
	$rpdiscount = $re->total * ($diskon / 100);

	$pajak1 = 0;
	$pajak2 = 0;
	$arrCustomer = data_customer($cust);

	if($arrCustomer){
		$nama_cust = $arrCustomer->name;
		//kondisi jika jml req saldo > 2.220.000
		$npwp = $arrCustomer->npwp; // xxxx | 00.000.000.0-000.000 (ada npwp = 0.5% dari 1.11% saldo -- ga ada  = 1% dari 1.11%saldo )
		$umkm = $arrCustomer->umkm;

		$total = $total - $rpdiscount;

		$pajak1 = $total - ($total / 1.11);
		$no_cust = $arrCustomer->no_rs;

		if($total > 2220000){

		  if($umkm == "YA"){
		    $pajak2 = 0;
		  }else{
		    if($npwp == "00.000.000.0-000.000"){
		      $pajak2 = ($total / 1.11) * 0.01;
		    }else{
		      $pajak2 = ($total / 1.11) * 0.005;
		    }
		  }
			$total = $total + $pajak2;
		}
	}

	$totalbayar = $total;
	//$totalbayar = $re->total + $re->fee;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,700">
  </head>
<body>
	<style>
	body,
	html {
	  font-family: "Ubuntu", sans-serif;
		font-size: 11px;
	  overflow-x: hidden;
	  padding-right: 0 !important; }

	table {
		padding: 2px;
	  border-collapse: collapse;
		table-layout: fixed;
	}

	.center {
		margin-left: auto;
  	margin-right: auto;
		text-align: center;
	}

	.autosize {
		margin-left: auto;
		margin-right: auto;
	}

	.line{
    width:100%;
    height:0;
    border-bottom:1px dotted #111;
    float:left;
    margin:0;
    z-index:0;
    padding: 5px;
    left:0
}

	</style>
<script type='text/javascript'>
	Website2APK.printPage();
</script>
<table class="center" width="100%">
	<tr>
    <td>STOCK MUTATION REPORTS</td>
  </tr>
  <tr>
    <td>PT. CESA TEKNOLOGI ANGKASA</td>
  </tr>
	<tr>
    <td>NPWP: </td>
  </tr>

	<tr>
    <td>Denpasar Bali 80237</td>
  </tr>
	<tr>
    <td>Call Center : 088987008989</td>
  </tr>
	<tr>
    <td><?=$no_faktur?></td>
  </tr>
</table>
<br />

<table class="autosize" width="100%">
  <tr>
    <td width="25%">Kanvaser</td><td colspan="2" align="left" width="65%">: <?=$nama_cvs?></td>
  </tr>
	<tr>
		<td width="25%">Outlet</td><td colspan="2" align="left" width="65%">: <?=$nama_cust?></td>
  </tr>
	<tr>
		<td width="25%">ID Outlet</td><td colspan="2" align="left" width="65%">: <?=$cust?></td>
  </tr>
	<tr>
		<td width="25%">No HP</td><td colspan="2" align="left" width="65%">: <?=$no_cust?></td>
  </tr>
	<tr>
		<td colspan="3"><i class="line"></i></td>
  </tr>

	<tr>
		<td>Ket.</td> <td>Qty x Hrg</td> <td align="right">Jumlah</td>
  </tr>
	<tr>
		<td colspan="3"><i class="line"></i></td>
  </tr>
	<tr>
		<td colspan="3"><?=$pro .' - '. $nameproduct?></td>
  </tr>
	<tr>
		<td colspan="2"><?=$qty .' x Rp.' . $unit_price?></td><td align="right"> <?=$totals?></td>
  </tr>
	<tr>
		<td colspan="2">Diskon <?=$diskon?>%</td><td align="right"> <?=number_format($rpdiscount)?></td>
  </tr>
	<tr>
		<td colspan="2">include PPN 11%</td><td align="right"> <?=number_format(round($pajak1))?></td>
  </tr>

	<tr>
		<td colspan="2">PPH22</td><td align="right"> <?=number_format(round($pajak2))?></td>
  </tr>

	<tr>
		<td colspan="2"></td><td align="right">=======</td>
  </tr>
	<tr>
		<td colspan="2" align="right">Jumlah :</td><td align="right">Rp. <?=number_format($totalbayar)?></td>
  </tr>
	<tr>
		<td colspan="3" align="center">&nbsp;</td>
  </tr>
	<tr>
		<td colspan="3" align="center"><?=date("m-d-Y H:i:s")?></td>
  </tr>
	<tr>
		<td colspan="3" align="center">** TERIMA KASIH **</td>
  </tr>
	<tr>
		<td colspan="3" align="center"><a href="javascript:history.back()">BACK</a> </td>
  </tr>
</table>
</body>
</html>
