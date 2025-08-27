<?php
$title = "RENTAL MIFI";
session_start();
include("session_check.php");
include("connection_rti.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
$user=$_SESSION['USER'];
$msg="";
if(isset($_POST['next1'])){
	$reff = date("YmdHis");
	$email = isset($_POST['email']) ? $_POST['email']:"";
	$nama = isset($_POST['nama']) ? $_POST['nama']:"";
	$asal_negara = isset($_POST['asal_negara']) ? $_POST['asal_negara']:"";
	$alamat_bali  = isset($_POST['alamat_bali']) ? $_POST['alamat_bali']:"";

	$targetDirectory = "rental_mifi/pelanggan/";
	$basename = $_FILES["foto_pelanggan"]["name"];
	$fileInfo = pathinfo($basename);
	$extension = $fileInfo['extension'];
	$new_name = $reff."_".rand().".".$extension;
    $targetFile = $targetDirectory . $new_name;
	
        if (file_exists($targetFile)) {
            $msg =  "<div class='row mt-3 mx-1 p-2 rounded border'>File already exists.</div>";
        } else {
            if (move_uploaded_file($_FILES["foto_pelanggan"]["tmp_name"], $targetFile)) {
				mysql_query("insert into rental_mifi set date_input=now(), no_referensi='$reff', email='$email', nama='$nama', asal_negara='$asal_negara',
							alamat_bali='$alamat_bali', foto_pelanggan='$targetFile', form_position=1, user='$user'");
            } else {
                $msg = "<div class='row mt-3 mx-1 p-2 rounded border'>Error uploading image.</div>";
            }
        }

}
if(isset($_POST['next2'])){
	$reff = isset($_POST['reffx']) ? $_POST['reffx']:"";
	$nomor_paspor = isset($_POST['nomor_paspor']) ? $_POST['nomor_paspor']:"";
	$negara_tujuan = isset($_POST['negara_tujuan']) ? $_POST['negara_tujuan']:"";
	$nomor_device = isset($_POST['nomor_device']) ? $_POST['nomor_device']:"";
	$tanggal_aktif_device  = isset($_POST['tgl_aktif_device']) ? $_POST['tgl_aktif_device']:"";
	$periode_sewa = isset($_POST['periode_sewa']) ? $_POST['periode_sewa']:"";
	$tanggal_pengembalian = isset($_POST['tgl_pengembalian']) ? $_POST['tgl_pengembalian']:"";
		
	$targetDirectory = "rental_mifi/paspor/";
	$basename = $_FILES["foto_paspor"]["name"];
	$fileInfo = pathinfo($basename);
	$extension = $fileInfo['extension'];
	$new_name = $reff."_".rand().".".$extension;
	$targetFile = $targetDirectory . $new_name;
	
		if (file_exists($targetFile)) {
			$msg =  "<div class='row mt-3 mx-1 p-2 rounded border'>File already exists.</div>";
		} else {
			if (move_uploaded_file($_FILES["foto_paspor"]["tmp_name"], $targetFile)) {
				$sql = "update rental_mifi set nomor_paspor='$nomor_paspor', foto_paspor='$targetFile', negara_tujuan='$negara_tujuan',
							nomor_device = '$nomor_device', tanggal_aktif_device='$tanggal_aktif_device', tanggal_pengembalian='$tanggal_pengembalian', 
							periode_sewa='$periode_sewa', form_position=2 where
							no_referensi='$reff' and user='$user'";
				$qry = mysql_query($sql);
				if(!$qry){
					$msg = "<div class='row mt-3 mx-1 p-2 rounded border'>$sql</div>";
				}
			} else {
				$msg = "<div class='row mt-3 mx-1 p-2 rounded border'>Error uploading image</div>";
			}
		}

}

if(isset($_POST['next3'])){
	$reff = isset($_POST['reffx']) ? $_POST['reffx']:"";
	$no_hp = isset($_POST['hp']) ? $_POST['hp']:"";
	$deposit = isset($_POST['ch_deposit']) ? "1":"0";
	$tnc = isset($_POST['ch_tnc']) ? "1":"0";
	$sql = "update rental_mifi set no_hp='$no_hp', deposit='$deposit', tnc='$tnc', form_position=3
	where no_referensi='$reff' and user='$user'";
	$qry = mysql_query($sql);
	if(!$qry){
		$msg = "<div class='row mt-3 mx-1 p-2 rounded border'>$sql</div>";
	}

}

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

$form_pos =0;
$qry = mysql_query("select * from rental_mifi where user='$user' and status = 0 limit 1 ");
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

	$tnc = ($data->tnc == "1") ? "Setuju":"Tidak Setuju";;
}
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.italic {
    font-style: italic;
}
</style>
<body id='myElement'>
<main class="main-wrap setting-page mb-xxl">
	
	<?=$msg?>
	</div>
		<?php
		if($form_pos == 0){

		?>
			<form class="custom-form" method="post" enctype="multipart/form-data">
				<br>
				<div class='row input-box'>
					<div class='col-9'>
						Langkah 1 <small class='italic'>(Step 1)</small>
					</div>
					<div class='col-3'>
						<button type='submit' class='btn btn-sm btn-danger' name='batal'>Batal</button>
					</div>
				</div>
				<div class="input-box">
					Email
					<input class="form-control" type="email" id="produk" name="email" value="<?=$email?>" autocomplete="Off" placeholder='Masukkan Email' required>
				</div>
				
				
				<div class="input-box">
					Nama <small class='italic'>(Name)</small>
					<input class="form-control" type="text" id="idproduk" name="nama" value="<?=$nama?>" autocomplete="Off" placeholder='Masukkan Nama' required>
				</div>
				<div class="input-box">
					Asal Negara <small class='italic'>(Country Of Origin)</small>
					<input class="form-control" type="text" id="asal_negara" name="asal_negara" value="<?=$asal_negara?>" autocomplete="Off" placeholder='Pilih Asal Negara' required onkeyup='ceknegara("asal_negara");'>
				</div>
				<div id="hasilnegara" class="input-box"> </div>

				<div class="input-box">
					Alamat Di Bali <small class='italic'>(Address in Bali)</small>
					<input class="form-control" type="text" id="produk" name="alamat_bali" value="<?=$alamat_bali?>" autocomplete="Off" placeholder='Masukkan Alamat di Bali' required>
				</div>
				<div class="input-box">
					Foto Pelanggan Saat Sewa dengan Paspor <br>
					<small class='italic'>(Customer photos during rental with passport)</small>
					<input class="form-control" type="file" id="produk" name="foto_pelanggan" required>
				</div>
				<button type='submit' class='btn-solid bg-dark' name='next1'>Next</button>
			</form>
		<?php
		}else if($form_pos == 1){
		?>
		<form class="custom-form" method="post"  enctype="multipart/form-data">
			<br>
			<div class='row'>
				<div class='col-9'>
					Langkah 2 <small class='italic'>(Step 2)</small>
				</div>
				<div class='col-3 '>
					<input type='hidden' value='<?=$reff?>' name='reffx'>
					<button type='submit' class='btn btn-sm btn-danger' name='batal'>Batal</button>
				</div>
			</div>
			<div class="input-box">
				Nomor Passport <small class='italic'>(Passport Number)</small>
				<input class="form-control" type="text" id="produk" name="nomor_paspor" value="" autocomplete="Off" placeholder='Masukkan nomor paspor'>
			</div>
			<div class="input-box">
				Foto Passport <small class='italic'>(Passport Photo)</small>
				<input class="form-control" type="file" id="produk" name="foto_paspor">
			</div>
			<div class="input-box">
				Negara Tujuan <small class='italic'>(Country of Destination)</small>
				<input class="form-control" type="text" id="negara_tujuan" name="negara_tujuan" value="" autocomplete="Off" placeholder='Masukkan negara tujuan' onkeyup='ceknegara("negara_tujuan");'>
			</div>
			<div id="hasilnegara" class="input-box"> </div>
			<div class="input-box">
				Nomor Device <small class='italic'>(Device Number)</small>
				<input class="form-control" type="text" id="produk" name="nomor_device" value="" autocomplete="Off" placeholder='Masukkan nomor device'>
			</div>
			<div class="input-box">
				Tanggal Aktif Device <small class='italic'>(Date of activation)</small>
				<input class="form-control" type="text" id='datepicker' name="tgl_aktif_device" value="" autocomplete="Off" placeholder='Pilih tanggal aktif device'>
			</div>
			<div class="input-box">
				Periode Sewa <small class='italic'>(Rent period)</small>
				<input class="form-control" type="text" id="produk" name="periode_sewa" value="" autocomplete="Off" placeholder='Masukkan periode sewa dalam hari'>
			</div>
			<div class="input-box">
				Tanggal Pengembalian <small class='italic'>(Date of return)</small>
				<input class="form-control" type="text" id="datepicker2" name="tgl_pengembalian" value="" autocomplete="Off" placeholder='Pilih tanggal pengembalian'>
			</div>
			<button type='submit' class='btn-solid bg-dark' name='next2'>Next</button>
		</form>	
		<?php
		}else if($form_pos == 2){
		?>		`
		<form class="custom-form" method="post">
			<div class='row'>
				<div class='col-9'>
					Langkah 3 <small class='italic'>(Step 3)</small>
				</div>
				<div class='col-3'>
					<input type='hidden' value='<?=$reff?>' name='reffx'>
					<button type='submit' class='btn btn-sm btn-danger' name='batal'>Batal</button>
				</div>
			</div>
			<div class="input-box">
				No Telepon Aktif <small class='italic'>(Active phone number)</small>
				<input class="form-control" type="text" id="produk" name="hp" value="" autocomplete="Off" placeholder='Masukkan No HP Aktif'>
			</div>
			<div class="input-box">
				Deposit Rp 750.000<br><small class='italic'>(Agree to pay the deposit Rp. 750.000)</small>
				<div class='row mt-1'>
					<div class='col-2 text-center'>
						<input type="checkbox" name="ch_deposit" id='myCheckbox' style='width:20px;height:20px'> 
					</div>
					<div class='col-10'>
						Saya Setuju <small class='italic'>(I Agree)</small>
					</div> 
				</div>
			</div>
			<div class="input-box">
				Terms and conditions
				
				<div class='row mt-1'>
					<div class='col-12 text-center'><img src='rental_mifi/TNC.png' width='100%'></div>
					<div class='col-2 text-center pt-4'>
						<input type="checkbox" name="ch_tnc" id='myCheckbox2' style='width:20px;height:20px' onchange='checkbx();'> 
					</div>
					<div class='col-10'>
						Saya telah membaca dan setuju dengan Syarat dan Ketentuan<br> <small class='italic'>(I have read and agree to the Terms and Conditions)</small>
					</div> 
				</div>
			</div>
			<button type='submit' disabled class='btn-solid bg-secondary' id='btn_next3' name='next3'>Next</button>
      	</form>
		<?php
		}else if($form_pos == 3){
			//review
			//tanda tangan
		?>
			<form class="custom-form" method="post" enctype="multipart/form-data">
				<br>
				<div class='row input-box'>
					<div class='col-9'>
						Summary
					</div>
					<div class='col-3'>
						<input type='hidden' value='<?=$reff?>' name='reffx'>
						<button type='submit' class='btn btn-sm btn-danger' name='batal'>Batal</button>
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
					<img src='<?=$foto_pelanggan?>' width='30%'>
				</div>
				<div class="input-box">
					Nomor Passport <small class='italic'>(Passport Number)</small>
					<input class="form-control" type="text" readonly value='<?=$nomor_paspor?>'>
				</div>
				<div class="input-box">
					Foto Passport <small class='italic'>(Passport Photo)</small><br>
					<img src='<?=$foto_paspor?>' width='30%'>
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
				<div class='row' id="canvasContainer">
					<div class='col-7'>
						Tanda Tangan
					</div>
					<div class='col-5 mb-2'>
						<button id="clearButton" type="button" class='btn btn-success btn-sm'>Clear Signature</button>
					</div>
					<div class='col-12'>
						<canvas id="signatureCanvas" height="220" style="border: 1px solid #000; width:100%;"></canvas>
						<br>
						<input type="hidden" id="signatureData" name="signatureData"> 
					</div>
					
				</div>
				<button type='submit' class='btn-solid bg-dark' name='submit'>Submit</button>
			</form>
		<?php
		}
		?>
    </main>
	<!-- Main End -->
	<script>
		$(function() {
			$("#datepicker").datepicker({
			dateFormat: 'yy-mm-dd', // Format tanggal (contoh: 2023-10-31)
			minDate: '-1Y', // Tanggal minimum yang dapat dipilih (0 untuk hari ini)
			maxDate: '+1Y', // Tanggal maksimum yang dapat dipilih (1 tahun dari sekarang)
			});
		});
		$(function() {
			$("#datepicker2").datepicker({
			dateFormat: 'yy-mm-dd', // Format tanggal (contoh: 2023-10-31)
			minDate: '-1Y', // Tanggal minimum yang dapat dipilih (0 untuk hari ini)
			maxDate: '+1Y', // Tanggal maksimum yang dapat dipilih (1 tahun dari sekarang)
			});
		});
		function checkbx(){
			var checkbox = document.getElementById("myCheckbox2");
			var btnNext3 = document.getElementById("btn_next3");
			if (checkbox.checked) {
				btnNext3.disabled = false;
				btnNext3.classList.remove("bg-secondary");
				btnNext3.classList.add("bg-dark");
			} else {
				btnNext3.disabled = true;
				btnNext3.classList.add("bg-secondary");
				btnNext3.classList.remove("bg-dark");
			}
		}

		document.addEventListener('DOMContentLoaded', function() {
			var canvas = document.getElementById('signatureCanvas');
			var context = canvas.getContext('2d');
			var isDrawing = false;
			var elem = document.getElementById("myElement");

			canvas.addEventListener('touchstart', function(e) {
				isDrawing = true;
				elem.style.overflow='hidden';
				context.beginPath();
				context.moveTo(e.touches[0].clientX - canvas.getBoundingClientRect().left, e.touches[0].clientY - canvas.getBoundingClientRect().top);
			});

			canvas.addEventListener('touchmove', function(e) {
				elem.style.overflow='hidden';
				if (!isDrawing) return;
				context.lineTo(e.touches[0].clientX - canvas.getBoundingClientRect().left, e.touches[0].clientY - canvas.getBoundingClientRect().top);
				context.stroke();
			});

			canvas.addEventListener('touchend', function(e) {
				elem.style.overflow='auto';
				updateSignatureData()
				isDrawing = false;
			});
			function updateSignatureData() {
				document.getElementById('signatureData').value = canvas.toDataURL();
			}
			const clearButton = document.getElementById('clearButton');
			clearButton.addEventListener('click', () => {
				context.clearRect(0, 0, canvas.width, canvas.height);
				updateSignatureData()
			});
		});
	</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <?php include("footer_alt.inc.php");?>
