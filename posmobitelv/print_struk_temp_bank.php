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
$sql = "select l.id_product, l.no_faktur, l.detail_notes, l.id_customer, p.name_product, l.id_customer, l.qty, l.unit_price, l.total, l.fee from sales_invoice l
left join products p on (l.id_product = p.id_product) where l.idsalesman = '$idsales' and l.no_faktur='$nofaktur'";
*/
$sql = "select l.id, l.date_order, DATE_ADD(now(), interval 2 hour) as limittime, l.id_product, l.no_faktur, l.detail_notes, l.id_customer, p.name_product, l.id_customer, l.qty, l.unit_price, l.total, l.fee, l.totalpay, l.via, l.ticket from validasi_sales_invoice l
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
	$nama_cust = name_customer($cust);
	$totalbayar = $re->total + $re->fee;

	$nama_bank = get_bank_name($re->via);
	//$jumlah = $re->total + $re->fee + (int) substr($re->id, -3);
	//$ticket = substr($re->id, -3);
	//$jumlah = number_format($jumlah);
	$jumlah = number_format($re->totalpay);
	//$ticket = $re->totalpay - $re->total - $re->fee;
	$ticket = $re->ticket;

	$date_order = $re->date_order;
	$limittime = $re->limittime;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
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
    <td>CV. Rajawali Cellular</td>
  </tr>
	<tr>
    <td>NPWP: 02.047.184.3-904.000</td>
  </tr>
	<tr>
    <td>JL. Teuku Umar No. 14A Dauh Puri Kelod Denpasar Barat</td>
  </tr>
	<tr>
    <td>Denpasar Bali 80114</td>
  </tr>
	<tr>
    <td>Call Center : 081338740740</td>
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
		<td width="25%">No HP</td><td colspan="2" align="left" width="65%">: <?=$no_hpcust?></td>
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
		<td colspan="3">BIAYA ADMINISTRASI</td>
  </tr>
	<tr>
		<td colspan="2">1 x Rp. <?=$fee?></td><td align="right"> <?=$fee?></td>
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
		<td colspan="3"><u>Mohon transfer ke Rekening</u></td>
  </tr>

	<tr>
		<td colspan="3"><?=$nama_bank?></td>
  </tr>
	<tr>
		<td colspan="3">Mohon melakukan pembayaran sebelum <?=$limittime?></td>
	</tr>
	<tr>
		<td colspan="3">-----------------------------------------------</td>
  </tr>
	<tr>
		<td colspan="2">Nominal Transfer</td><td align="right"> Rp. <?=$jumlah?></td>
  </tr>
	<tr>
		<td colspan="2">Kode Unik Anda</td><td align="right"> <?=$ticket?></td>
  </tr>
	<tr>
		<td colspan="3" align="center"></td>
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
