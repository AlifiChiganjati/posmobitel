<?php
$title = "LOG RENTAL MIFI";
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

$qry = mysql_query("select * from rental_mifi where user='$user' order by id desc limit 25");
$jum = mysql_num_rows($qry);
if($jum > 0){
	while($data = mysql_fetch_object($qry)){
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
		$tgl = $data->date_input;
		$status =$data->status;
		switch($status){
			case "0":
				$sts = "<button class='btn btn-primary btn-sm'>New</button>";
				break;
			case "1":
				$sts = "<button class='btn btn-warning btn-sm'>Proses</button>";
				break;
			case "2":
				$sts = "<button class='btn btn-danger btn-sm'>Batal</button>";
				break;
			case "4":
				$sts = "<button class='btn btn-success btn-sm'>Selesai</button>";
				break;
		}
		$log .= "<a class='row border rounded my-3 mx-0 py-2' href='rental_mifi_detail.php?reff=$reff'>
					<div class='col-6'><small>$tgl</small></div><div class='col-6 text-end'><small>ID: $reff</small></div>
					<div class='col-6'><small>$email</small></div><div class='col-6 text-end'><small>$nama</small></div>
					<div class='col-6'><small>$nomor_device</small></div><div class='col-6 text-end'>$sts</div>
				</a>";
	}
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
		<?=$log?>
    </main>
<?php include("footer_alt.inc.php");?>
