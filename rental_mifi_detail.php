<?php
$title = "DETAIL RENTAL MIFI";
session_start();
include("session_check.php");
include("connection_rti.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
$user=$_SESSION['USER'];
$msg="";
if(isset($_POST['submit'])){
	$reff = isset($_POST['reffx']) ? $_POST['reffx']:"";
	$tandaTanganData = $_POST['signatureData'];
	$tandaTanganData = str_replace('data:image/png;base64,', '', $tandaTanganData);
	$tandaTanganData = base64_decode($tandaTanganData);

	$name = $reff."_".rand().".png";
	$file = "rental_mifi/signature/".$name;
	file_put_contents($file, $tandaTanganData);
	$sql = "update rental_mifi set foto_signature='$file', status = 1 where no_referensi='$reff' and user='$user'";
	$qry = mysql_query($sql);
	if(!$qry){
		$msg = "<div class='row mt-3 mx-1 p-2 rounded border'>$sql</div>";
	}else{
		$msg = "<div class='row mt-3 mx-1 p-2 rounded border'><strong>Pengajuan berhasil...</strong></div>";
	}
}

if(isset($_POST['batal'])){
	$reffx = $_POST['reffx'];
	mysql_query("update rental_mifi set status = 2 where no_referensi = '$reffx'");
}

$no_reff = isset($_GET['reff']) ? cleanall($_GET['reff']) : "";
$qry = mysql_query("select * from rental_mifi where user='$user' and no_referensi='$no_reff' ");
$jum = mysql_num_rows($qry);
if($jum > 0){
	$data = mysql_fetch_object($qry);
	$form_pos = $data->form_position;
	$email = $data->email;
	$nama = $data->nama;
	$asal_negara = $data->asal_negara;
	$alamat_bali = $data->alamat_bali;
	$reff = $data->no_referensi;
	$foto_pelanggan = $data->foto_pelanggan;
	$nomor_paspor = $data->nomor_paspor;
	$foto_paspor = $data->foto_paspor;
	$negara_tujuan = $data->negara_tujuan;
	$nomor_device = $data->nomor_device;
	$tanggal_aktif_device = $data->tanggal_aktif_device;
	$tanggal_pengembalian = $data->tanggal_pengembalian;
	$periode_sewa = $data->periode_sewa;
	$no_hp = $data->no_hp;
	$deposit = ($data->deposit == "1") ? "Setuju":"Tidak Setuju";
	$tnc = ($data->tnc == "1") ? "Setuju":"Tidak Setuju";
	$ttd = $data->foto_signature;
}
?>
<style>
.italic {
    font-style: italic;
}
</style>
<body id='myElement'>
<main class="main-wrap setting-page mb-xxl">
	
	<?=$msg?>
	</div>
			<form class="custom-form" method="post" enctype="multipart/form-data">
				<br>
				<div class='row input-box'>
					<div class='col-9'>
						Summary
					</div>
					<div class='col-3'>
						<input type='hidden' value='<?=$reff?>' name='reffx'>
						<!--<button type='submit' class='btn btn-sm btn-danger' name='batal'>Batal</button>-->
					</div>
				</div>
				<div class="input-box">
					Email
					<input class="form-control" type="email" readonly value='<?=$email?>'>
				</div>
				
				<div class="input-box">
					Nama <small class='italic'>(Name)</small>
					<input class="form-control" type="text" readonly value='<?=$nama?>'>
				</div>
				<div class="input-box">
					Asal Negara <small class='italic'>(Country Of Origin)</small>
					<input class="form-control" type="text" readonly value='<?=$asal_negara?>'>
				</div>
				<div class="input-box">
					Alamat Di Bali <small class='italic'>(Address in Bali)</small>
					<input class="form-control" type="text" readonly value='<?=$alamat_bali?>'>
				</div>
				<div class="input-box">
				Foto Pelanggan Saat Sewa dengan Paspor <br>
					<small class='italic'>(Customer photos during rental with passport)</small>
					<img src='<?=$foto_pelanggan?>' width='40%'>
				</div>
				<div class="input-box">
					Nomor Passport <small class='italic'>(Passport Number)</small>
					<input class="form-control" type="text" readonly value='<?=$nomor_paspor?>'>
				</div>
				<div class="input-box">
					Foto Passport <small class='italic'>(Passport Photo)</small><br>
					<img src='<?=$foto_paspor?>' width='40%'>
				</div>
				<div class="input-box">
					Negara Tujuan <small class='italic'>(Country of Destination)</small>
					<input class="form-control" type="text" readonly value='<?=$negara_tujuan?>'>
				</div>
				<div class="input-box">
					Nomor Device <small class='italic'>(Device Number)</small>
					<input class="form-control" type="text" readonly value='<?=$nomor_device?>'>
				</div>
				<div class="input-box">
					Tanggal Aktif Device
					<input class="form-control" type="text" readonly value='<?=$tanggal_aktif_device?>'>
				</div>
				<div class="input-box">
					Periode Sewa <small class='italic'>(Rent period)</small>
					<input class="form-control" type="text" readonly value='<?=$periode_sewa?>'>
				</div>
				<div class="input-box">
					Tanggal Pengembalian <small class='italic'>(Date of return)</small>
					<input class="form-control" type="text" readonly value='<?=$tanggal_pengembalian?>'>
				</div>
				<div class="input-box">
					No Telepon Aktif <small class='italic'>(Step 3)</small>
					<input class="form-control" type="text" value="<?=$no_hp?>" readonly>
				</div>
				<div class="input-box">
					Deposit Rp 750.000 <small class='italic'>(Agree to pay the deposit Rp. 750.000)</small>
					<input class="form-control" type="text" value="<?=$deposit?>" readonly>
				</div>
				<div class="input-box">
					Term and condition
					<input class="form-control" type="text" value="<?=$tnc?>" readonly>
				</div>
				<div class="input-box">
					Tanda Tangan<br>
					<img src='<?=$ttd?>' width='60%'>
				</div>
			</form>
    </main>
	
  <?php include("footer_alt.inc.php");?>
