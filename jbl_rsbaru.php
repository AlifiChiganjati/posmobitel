<?php
$title = "DAFTAR OUTLET BARU";
session_start();
include("session_check.php");
include("connection_jbl.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$idsales   = $_SESSION['IDSALES'];
$depo      = $_SESSION['DEPO'];
$clustero  = $_SESSION['CLUSTER'];
$cluster   = "Kantor Pusat";

if ($_POST['daftar'] == "DAFTAR") {

    // Ambil data inputan
    $nama      = isset($_POST['nama']) ? cleaninput($_POST['nama']) : "";
    $level     = "1";
    $nohp      = isset($_POST['nohp']) ? cleaninput($_POST['nohp']) : "";
    $alamat    = isset($_POST['alamat']) ? cleaninput($_POST['alamat']) : "";
    $kabupaten = isset($_POST['kabupaten']) ? cleaninput($_POST['kabupaten']) : "";
    // $noeload   = isset($_POST['noeload']) ? cleaninput($_POST['noeload']) : "";
    $taxtype   = isset($_POST['taxtype']) ? cleaninput($_POST['taxtype']) : "";
    $document  = isset($_POST['document']) ? cleaninput($_POST['document']) : "";
    $no_ktp    = isset($_POST['no_ktp']) ? cleaninput($_POST['no_ktp']) : "0000000000000000";
    $idoutlet  = generateOutletId($nohp);
    $npwp      = isset($_POST['npwp']) ? cleaninput($_POST['npwp']) : "0000000000000000";
    $pkp       = isset($_POST['pkp']) ? cleaninput($_POST['pkp']) : "";
    $omob      = isset($_POST['omob']) ? cleaninput($_POST['omob']) : "";
    $umkm      = isset($_POST['umkm']) ? cleaninput($_POST['umkm']) : "";
    $noeload   = $nohp;
$latitude  = isset($_POST['latitude']) ? cleaninput($_POST['latitude']) : '';
$longitude = isset($_POST['longitude']) ? cleaninput($_POST['longitude']) : '';

$lokasi_outlet = "";
if (!empty($latitude) && !empty($longitude) && is_numeric($latitude) && is_numeric($longitude)) {
    $lokasi_outlet = $latitude . "," . $longitude;
}

    // Validasi NPWP
    if ($npwp != "" && (strpos($npwp, '-') !== false || strpos($npwp, '.') !== false || !preg_match('/^[0-9]{16}$/', $npwp))) {
        $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
                    <div class='offcanvas-body small'>
                        <div class='app-info'>
                            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                            <div class='content'>
                                <h3>Format NPWP Tidak Valid <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                                <a href='#'>NPWP harus 16 digit angka tanpa tanda titik (.) atau strip (-). Contoh: 0000000000000000</a>
                            </div>
                        </div>
                    </div>
                </div>";
    } else {

        if ($npwp == "") {
            $npwp = "0000000000000000";
        }

        // Validasi input wajib
        if ($idsales && $nama && $level && $nohp && $noeload && $alamat && $idoutlet && $kabupaten && $taxtype && $document && $no_ktp) {

            $sce = mysql_query("SELECT nohp FROM validasi_rs WHERE nohp ='$nohp'");
            if ($rce = mysql_fetch_object($sce)) {
                $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
                            <div class='offcanvas-body small'>
                                <div class='app-info'>
                                    <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                                    <div class='content'>
                                        <h3>Duplicate Data <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                                        <a href='#'>No Hp: $nohp sudah ada! Silahkan isi nomor yang lain!</a>
                                    </div>
                                </div>
                            </div>
                        </div>";
            } elseif (is_exist_customer($idoutlet)) {
                $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
                            <div class='offcanvas-body small'>
                                <div class='app-info'>
                                    <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                                    <div class='content'>
                                        <h3>Request diterima! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                                        <a href='#'>Nomor ini sudah terdaftar</a>
                                    </div>
                                </div>
                            </div>
                        </div>";
            } else {

                $transdate = date("d/m/Y");

                $neweload = $noeload;
                if (substr($neweload, 0, 3) == "088") {
                    $neweload = "62" . substr($neweload, 1);
                }
$uploadUrl = "https://nexacloud.id/"; 
function uploadToRemote($fileTmp, $fileName, $fileType, $folder, $uploadUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uploadUrl . "posjbl/upload_foto.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "file"   => new CURLFile($fileTmp, $fileType, $fileName),
        "folder" => $folder
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
//                if (curl_errno($ch)) {
//     echo "cURL Error: " . curl_error($ch);
// }
    curl_close($ch);
     
          // var_dump($response);
          // exit();
    $result = json_decode($response, true);
    if ($result && $result["status"] == "ok") {
        return $result["url"]; // URL lengkap
    }
    return "";
}

/* ===== Upload KTP ===== */
$dbPathKtp = "";
if (isset($_FILES["foto_ktp"]) && $_FILES["foto_ktp"]["error"] == 0) {
    $fileName = time() . "_ktp_" . basename($_FILES["foto_ktp"]["name"]);
    $fileType = $_FILES["foto_ktp"]["type"];
    $fileSize = $_FILES["foto_ktp"]["size"];
    $fileTmp  = $_FILES["foto_ktp"]["tmp_name"];

    // Jika > 2MB → kompres dulu
    if ($fileSize > 2097152) {
        $tempFile = sys_get_temp_dir() . "/" . $fileName;
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($ext == "png") {
            $image = imagecreatefrompng($fileTmp);
            imagepng($image, $tempFile, 6);
        } else {
            $image = imagecreatefromjpeg($fileTmp);
            imagejpeg($image, $tempFile, 85);
        }
        imagedestroy($image);
        $fileTmp = $tempFile;
    }

    $dbPathKtp = uploadToRemote($fileTmp, $fileName, $fileType, "ktp", $uploadUrl);
          if ($dbPathKtp) {
    $dbPathKtp = basename($dbPathKtp);
}
}


/* ===== Upload Foto Outlet ===== */
$dbPathOutlet = "";
if (isset($_FILES["foto_outlet"]) && $_FILES["foto_outlet"]["error"] == 0) {
    $fileName = time() . "_outlet_" . basename($_FILES["foto_outlet"]["name"]);
    $fileType = $_FILES["foto_outlet"]["type"];
    $fileSize = $_FILES["foto_outlet"]["size"];
    $fileTmp  = $_FILES["foto_outlet"]["tmp_name"];

    if ($fileSize > 2097152) {
        $tempFile = sys_get_temp_dir() . "/" . $fileName;
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($ext == "png") {
            $image = imagecreatefrompng($fileTmp);
            imagepng($image, $tempFile, 6);
        } else {
            $image = imagecreatefromjpeg($fileTmp);
            imagejpeg($image, $tempFile, 85);
        }
        imagedestroy($image);
        $fileTmp = $tempFile;
    }

    $dbPathOutlet = uploadToRemote($fileTmp, $fileName, $fileType, "outlet", $uploadUrl);
          if ($dbPathOutlet) {
    $dbPathOutlet = basename($dbPathOutlet);
}

}


/* ===== Simpan ke DB ===== */
if ($dbPathKtp != "") {
    $sql = "INSERT IGNORE INTO validasi_rs 
            (tanggal, idsales, nama, nohp, noeload, level, alamat, kabupaten, idoutlet, depo, cluster, transdate, status, category, category_harga, wpname, taxtype, document, npwp, pkp, omob, umkm, noktp, foto_ktp, foto_outlet, location) 
            VALUES 
            (NOW(), '$idsales', '$nama', '$nohp', '$neweload', '$level', '$alamat', '$kabupaten', '$idoutlet', '$depo', '$cluster', '$transdate', 0, 'DEALER PRICE LIST', 'DEALER PRICE LIST', '$nama', '$taxtype', '$document', '$npwp', '$pkp', '$omob', '$umkm', '$no_ktp', '$dbPathKtp', '$dbPathOutlet', '$lokasi_outlet')";

    if (!mysql_query($sql)) {
        $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1'>
                    <div class='offcanvas-body small'>
                        <div class='app-info'>
                            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                            <div class='content'>
                                <h3>Request diterima! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                                <a href='#'>Gagal dalam input data RS</a>
                            </div>
                        </div>
                    </div>
                </div>";
    } else {
        $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1'>
                    <div class='offcanvas-body small'>
                        <div class='app-info'>
                            <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                            <div class='content'>
                                <h3>Request diterima! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                                <a href='#'>Pendaftaran RS Baru sudah diterima</a>
                            </div>
                        </div>
                    </div>
                </div>";
    }
} else {
    $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1'>
                <div class='offcanvas-body small'>
                    <div class='app-info'>
                        <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                        <div class='content'>
                            <h3>Request diterima! <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                            <a href='#'>Foto KTP wajib diupload</a>
                        </div>
                    </div>
                </div>
            </div>";
           }
            }
        }
    }
} // ←


?>

<script>
  function validateForm() {
    // var x01 = document.forms["myForm"]["idoutlet"].value;
    var x02 = document.forms["myForm"]["nama"].value;
    var x03 = document.forms["myForm"]["nohp"].value;
    var x04 = document.forms["myForm"]["kabupaten"].value;
    var x05 = document.forms["myForm"]["document"].value;
    var x06 = document.forms["myForm"]["alamat"].value;
    var x07 = document.forms["myForm"]["no_ktp"].value;


    // if (x01 == "") {
    //   alert("ID Outlet harus diIsi");
    //   return false;
    // }

    if (x02 == "") {
      alert("Nama harus diisi");
      return false;
    }

    if (x03 == "") {
      alert("Nomor HP harus diisi");
      return false;
    }

    if (x04 == "") {
      alert("Kabupaten Harus diisi");
      return false;
    }

    if (x05 == "") {
      alert("Jenis Document harus diisi");
      return false;
    }

    if (x06 == "") {
      alert("Alamat harus diisi");
      return false;
    }

 if (x07 == "") {
      alert("KTP harus diisi");
      return false;
    }
  }
</script>

<script src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#kecamatan').change(function() {
      var inputValue = $(this).val();
      $.post('getlist.php', {
        dropdownValue: inputValue
      }, function(data) {
        document.getElementById("resultList").innerHTML = data;
      });
    });
  });
</script>
<script>
function previewKTP(event) {
  const output = document.getElementById('preview_img_ktp');
  output.src = URL.createObjectURL(event.target.files[0]);
  output.classList.remove('d-none');
}

function previewOutlet(event) {
  const output = document.getElementById('preview_img_outlet');
  output.src = URL.createObjectURL(event.target.files[0]);
  output.classList.remove('d-none');
}


</script>
<main class="main-wrap setting-page mb-xxl">
   <!-- Form Section Start -->
      <form class="custom-form" method="post" enctype="multipart/form-data">
			<!--      <div class="form-group row"> -->
			<!-- <label class="col-sm-2 col-form-label">ID Outlet</label> -->
			<!-- <div class="col-sm-12"> -->
			<!-- 	 <input class="form-control" type="text" id="idoutlet" name="idoutlet" autocomplete="Off"> -->
			<!-- </div> -->
			<!--      </div> -->

        <div class="form-group row">
			<label class="col-sm-12 col-form-label">Nama Reseller</label>
			<div class="col-sm-12">
				<input class="form-control" type="text" id="nama" name="nama" autocomplete="Off">
			</div>
        </div>

        <div class="form-group row">
			<label class="col-sm-12 col-form-label">Nomor Reseller</label>
			<div class="col-sm-12">
				<input class="form-control" type="text" id="nohp" name="nohp" autocomplete="Off" value="">
			</div>
        </div>

			<!--      <div class="form-group row"> -->
			<!-- <label class="col-sm-12 col-form-label">Nomor Eload</label> -->
			<!-- <div class="col-sm-12"> -->
			<!-- 	<input class="form-control" type="text" id="noeload" name="noeload" autocomplete="Off" value=""> -->
			<!-- </div> -->
			<!--      </div> -->
<input type="hidden" name="latitude" id="latitude">
<input type="hidden" name="longitude" id="longitude">

        <div class="form-group row">
			<label class="col-sm-12 col-form-label">Alamat </label>
			<div class="col-sm-12">
				 <input class="form-control" type="text" id="alamat" name="alamat" autocomplete="Off">
			</div>
        </div>
		
        <div class="form-group row">
			<label class="col-sm-12 col-form-label">Kabupaten</label>
			<div class="col-sm-12">
				<select id="kabupaten" name="kabupaten" class="form-control">
                <option value="" selected>Pilih Kabupaten</option>
                <option value="BADUNG">BADUNG</option>
                <option value="BANGLI">BANGLI</option>
                <option value="BULELENG">BULELENG</option>
                <option value="DENPASAR">DENPASAR</option>
                <option value="GIANYAR">GIANYAR</option>
                <option value="JEMBRANA">JEMBRANA</option>
		<option value="KARANGASEM">KARANGASEM</option>
                <option value="KLUNGKUNG">KLUNGKUNG</option>
                <option value="KOTA DENPASAR">KOTA DENPASAR</option>
                <option value="NEGARA">NEGARA</option>
                <option value="SINGARAJA">SINGARAJA</option>
                <option value="TABANAN">TABANAN</option>
                <option value="KOTA MATARAM">KOTA MATARAM</option>
				<option value="LOMBOK BARAT">LOMBOK BARAT</option>
				<option value="LOMBOK UTARA">LOMBOK UTARA</option>
				<option value="LOMBOK TENGAH">LOMBOK TENGAH</option>
				<option value="LOMBOK TIMUR">LOMBOK TIMUR</option>
              </select>
			</div>
        </div>
		

  <!-- tambahkan image outlet -->
    <div class="mb-3">
  <label for="foto_outlet" class="form-label">Upload Foto OUTLET</label>
  <input class="form-control" type="file" id="foto_outlet" name="foto_outlet" accept="image/*" required onchange="previewOutlet(event)">
  <div class="form-text">Format: JPG, JPEG, PNG (maks. 2MB)</div>
  <img id="preview_img_outlet" src="" alt="Preview OUTLET" class="mt-2 img-thumbnail d-none" style="max-height:200px;">
    </div>

    <div class="form-group row">
			<label class="col-sm-12 col-form-label">Nomor Ktp </label>
			<div class="col-sm-12">
				 <input class="form-control" type="text" id="no_ktp" name="no_ktp" autocomplete="Off">
			</div>
    </div>

    <!-- tambahkan image ktp -->
    <div class="mb-3">
  <label for="foto_ktp" class="form-label">Upload Foto KTP</label>
  <input class="form-control" type="file" id="foto_ktp" name="foto_ktp" accept="image/*" required onchange="previewKTP(event)">
  <div class="form-text">Format: JPG, JPEG, PNG (maks. 2MB)</div>
  <img id="preview_img_ktp" src="" alt="Preview KTP" class="mt-2 img-thumbnail d-none" style="max-height:200px;">
    </div>
 
    <div class="form-group row">
			<label class="col-sm-12 col-form-label">NPWP </label>
			<div class="col-sm-12">
				 <input class="form-control" type="text" id="npwp" name="npwp" autocomplete="Off">
			</div>
        </div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-form-label">Jenis Pajak</label>
			<div class="col-sm-12">
				 <select id="taxtype" name="taxtype" class="form-control">
                <option value="BKN_PEMUNGUT_PPN">BKN_PEMUNGUT_PPN</option>
                <option value="DEALER PRICE LIST">DEALER PRICE LIST</option>
                <option value="DPP_NILAILAIN">DPP_NILAILAIN</option>
              </select>
			</div>
        </div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-form-label">Jenis Dokumen</label>
			<div class="col-sm-12">
				<select id="document" name="document" class="form-control">
                <option value="Digunggung">Digunggung</option>
                <option value="Faktur Pajak">Faktur Pajak</option>
              </select>
			</div>
        </div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-form-label">PKP </label>
			<div class="col-sm-12">
				<input class="form-control" type="text" id="pkp" name="pkp" autocomplete="Off">
			</div>
        </div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-form-label">Detail OM OB </label>
			<div class="col-sm-12">
				  <input class="form-control" type="text" id="omob" name="omob" autocomplete="Off">
			</div>
        </div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-form-label">Ijin UMKM</label>
			<div class="col-sm-12">
				<select id="umkm" name="umkm" class="form-control">
                <option value="">Pilih UMKM</option>
                <option value="YA">YA</option>
                <option value="">TIDAK</option>
              </select>
			</div>
        </div>
		
		<div class="form-group row">
			<div class="col-sm-12">
				<br>
				<input type="submit" class="btn-solid" name="daftar" value="DAFTAR">
			</div>
		</div>
      </form>
      <!-- Form Section End -->
    </main>
	


<?=$msg?>

<?php include("footer_alt.inc.php");?>
