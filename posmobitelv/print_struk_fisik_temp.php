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
/*
$sql = "select l.id_product, l.no_faktur, l.detail_notes, l.id_customer, p.name_product, l.id_customer, l.qty, l.unit_price, l.total, l.fee from log_sales_invoice l
left join products p on (l.id_product = p.id_product) where l.idsalesman = '$idsales' and l.status = 4 and l.no_faktur='$nofaktur'";
*/
$sql = "select l.id_product, l.no_faktur, l.detail_notes, l.id_customer, p.name_product,
count(l.qty) AS qty, l.unit_price, sum(l.total) AS total, l.fee
from sales_invoice l
left join products p on (l.id_product = p.id_product) where l.idsalesman = '$idsales'
and l.no_faktur='$nofaktur'
GROUP BY l.id_product";
//echo $sql;
$msq = mysql_query($sql);

while($re = mysql_fetch_object($msq)){
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

	//$nama_cust = $arrcust->name;
	//$outlet = $arrcust->outlet_id;

	$totalbayar += $re->total + $re->fee;
	$totalxs += $re->total;

	$list .= "<tr>
		<td colspan='4'>$nameproduct</td>
  </tr>
	<tr>
		<td colspan='3'>$qty .' x Rp.' . $unit_price</td><td align='right'> $totals</td>
  </tr>";
}

$total = $totalxs;

$arrcust = data_customer($cust);

if($arrcust){
	$nama_cust = $arrcust->name;
	$outlet = $arrcust->outlet_id;
	//kondisi jika jml req saldo > 2.220.000
	$npwp = $arrcust->npwp; // xxxx | 00.000.000.0-000.000 (ada npwp = 0.5% dari 1.11% saldo -- ga ada  = 1% dari 1.11%saldo )
	$umkm = $arrcust->umkm;

	$pajak1 = $total - ($total / 1.11);
	$dpp = $totalxs - $pajak1;
	$no_cust = $arrcust->no_rs;

	if($total > 2220000){

		if($umkm == "YA"){
			$pajak2 = 0;
		}else{
			if($npwp == "00.000.000.0-000.000"){
				//$pajak2 = $pajak1 * 0.01;
				$pajak2 = $dpp * 0.01;
			}else{
				//$pajak2 = $pajak1 * 0.005;
				$pajak2 = $dpp * 0.005;
			}
		}
		$total = $total + $pajak2;
	}
}

$totalbayar = $total;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
		width="100%"
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
    <td>CV. CENTRUM SAKTI</td>
  </tr>
	<tr>
    <td>Denpasar, Bali. 80237</td>
  </tr>
	<tr>
    <td>Call Center : 088987008989</td>
  </tr>
	<tr>
    <td><?=$no_faktur?></td>
  </tr>
</table>
<br />

<table class="autosize">
  <tr>
    <td width="33%">Kanvaser</td><td width="2%" align="center">:</td><td colspan="2" align="left" width="65%"><?=$nama_cvs?></td>
  </tr>
	<tr>
		<td width="33%">Outlet</td><td width="2%" align="center">:</td><td colspan="2" align="left" width="65%"><?=$nama_cust?></td>
  </tr>
	<tr>
		<td width="33%">ID Outlet</td><td width="2%" align="center">:</td><td colspan="2" align="left" width="65%"><?=$cust?></td>
  </tr>
	<tr>
		<td width="33%">No HP</td><td width="2%" align="center">:</td><td colspan="2" align="left" width="65%"><?=$no_cust?></td>
  </tr>
	<tr>
		<td colspan="4"><i class="line"></i></td>
  </tr>

	<tr>
		<td width="25%">Ket.</td> <td width="25%">Qty</td> <td width="25%">Hrg</td> <td width="25%" align="right">Jumlah</td>
  </tr>
	<tr>
		<td colspan="4"><i class="line"></i></td>
  </tr>


	<?=$list?>
	<!--
	<tr>
		<td colspan="4">ADMINISTRASI</td>
  </tr>
	<tr>
		<td colspan="3">1 x Rp. <?=$fee?></td><td align="right"> <?=$fee?></td>
  </tr>
	-->
	<tr>
		<td colspan="4"><i class="line"></i></td>
  </tr>
	<tr>
		<td colspan="3">DPP</td><td align="right"> <?=number_format(round($dpp))?></td>
  </tr>

	<tr>
		<td colspan="3">include PPN 11%</td><td align="right"> <?=number_format(round($pajak1))?></td>
  </tr>

	<tr>
		<td colspan="3">PPH 22</td><td align="right"> <?=number_format(round($pajak2))?></td>
  </tr>

	<tr>
		<td colspan="3"></td><td align="right">=======</td>
  </tr>
	<tr>
		<td colspan="2" align="right">Jumlah :</td><td align="right" colspan="2">Rp. <?=number_format($totalbayar)?></td>
  </tr>
	<tr>
		<td colspan="4" align="center">&nbsp;</td>
  </tr>
	<tr>
		<td colspan="4" align="center"><?=date("m-d-Y H:i:s")?></td>
  </tr>
	<tr>
		<td colspan="4" align="center">** TERIMA KASIH **</td>
  </tr>
	<tr>
		<td colspan="4" align="center"><a href="index.php">BACK</a> </td>
  </tr>
</table>
</body>
</html>
