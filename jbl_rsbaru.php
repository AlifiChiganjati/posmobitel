<?php
$title = "DAFTAR OUTLET BARU";
session_start();
//include("session_check.php");
include("connection_jbl.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
$idsales = $_SESSION['IDSALES'];
$depo = $_SESSION['DEPO'];
$clustero = $_SESSION['CLUSTER'];
$cluster = "Kantor Pusat";

if ($_POST['daftar'] == "DAFTAR") {
  $nama = isset($_POST['nama']) ? cleaninput($_POST['nama']) : "";
  $level = "1";
  $nohp = isset($_POST['nohp']) ? cleaninput($_POST['nohp']) : "";
  $alamat = isset($_POST['alamat']) ? cleaninput($_POST['alamat']) : "";
  $kabupaten = isset($_POST['kabupaten']) ? cleaninput($_POST['kabupaten']) : "";
  $idoutlet = isset($_POST['idoutlet']) ? cleaninput($_POST['idoutlet']) : "";
  $noeload = isset($_POST['noeload']) ? cleaninput($_POST['noeload']) : "";
  $taxtype = isset($_POST['taxtype']) ? cleaninput($_POST['taxtype']) : "";
  $document = isset($_POST['document']) ? cleaninput($_POST['document']) : "";
  
  //NPWP Versi Baru 16 Digit (Apabila Tidak Diisi Oleh Sales)
  // $npwp = isset($_POST['npwp']) ? cleaninput($_POST['npwp']) : "00.000.000.0-000.000";

  //NPWP Versi Baru 16 Digit (Apabila Tidak Diisi Oleh Sales)
  $npwp = isset($_POST['npwp']) ? cleaninput($_POST['npwp']) : "0000000000000000";

  $pkp = isset($_POST['pkp']) ? cleaninput($_POST['pkp']) : "";
  $omob = isset($_POST['omob']) ? cleaninput($_POST['omob']) : "";
  $umkm = isset($_POST['umkm']) ? cleaninput($_POST['umkm']) : "";

  //Validasi NPWP Kalau Ada Tanda (.) Titik Ataupun (-) Strip
  if ($npwp != "" && (strpos($npwp, '-') !== false || strpos($npwp, '.') !== false ||!preg_match('/^[0-9]{16}$/', $npwp))) {
    $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
    <div class='offcanvas-body small'>
      <div class='app-info'>
        <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
        <div class='content'>
          <h3>Format NPWP Tidak Valid <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
          <a href='#'>NPWP harus 16 digit angka tanpa tanda titik (.) atau strip (-). Contoh yang benar: 0000000000000000</a>
        </div>
      </div>
    </div>
  </div>";
  } else{
     if ($npwp == "") {
      $npwp = "0000000000000000";
    } 
  
  if ($idsales && $nama && $level && $nohp && $noeload && $alamat && $kabupaten && $idoutlet && $taxtype && $document) {

    $sce = mysql_query("select nohp from validasi_rs where nohp ='$nohp'");
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
    } else {

      if (is_exist_customer($idoutlet)) {
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
		
    //NPWP Versi Lama 15 Digit (Apabila Tidak Diisi Oleh Sales)
		// $npwp = (strlen($npwp) > 5)? $npwp : "00.000.000.0-000.000";

    //NPWP Versi Baru 16 Digit (Apabila Tidak Diisi Oleh Sales)
    $npwp = (strlen($npwp) > 5)? $npwp : "0000000000000000";

        $sql = "insert ignore into validasi_rs (tanggal, idsales, nama, nohp, noeload, level, alamat, kabupaten,  idoutlet, depo, cluster, transdate, status, category, category_harga, wpname, taxtype, document, npwp, pkp, omob, umkm) values
          (now(), '$idsales', '$nama', '$nohp', '$neweload', '$level', '$alamat', '$kabupaten', '$idoutlet', '$depo' , '$cluster', '$transdate', 1, 'DEALER PRICE LIST', 'DEALER PRICE LIST', '$nama', '$taxtype', '$document', '$npwp', '$pkp', '$omob', '$umkm')";

        if (!mysql_query($sql)) {
          $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
              <div class='offcanvas-body small'>
                <div class='app-info'>
                  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                  <div class='content'>
                    <h3>Internal Logic Error <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                    <a href='#'>Gagal dalam input data RS</a>
                  </div>
                </div>
              </div>
            </div>";
        } else {
          $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
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
      }
    }
  } else {
     $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Data Tidak Lengkap<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'>$idsales - $nama - $level - $nohp - $ktp - $alamat - $kabupaten - $kecamatan - $idoutlet - $depo</a>
          </div>
        </div>
      </div>
    </div>";
    }
  }
}

?>

<script>
  function validateForm() {
    var x01 = document.forms["myForm"]["idoutlet"].value;
    var x02 = document.forms["myForm"]["nama"].value;
    var x03 = document.forms["myForm"]["nohp"].value;
    var x04 = document.forms["myForm"]["kabupaten"].value;
    var x05 = document.forms["myForm"]["document"].value;
    var x06 = document.forms["myForm"]["alamat"].value;


    if (x01 == "") {
      alert("ID Outlet harus diIsi");
      return false;
    }

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

<main class="main-wrap setting-page mb-xxl">
   <!-- Form Section Start -->
      <form class="custom-form" method="post">
        <div class="form-group row">
			<label class="col-sm-2 col-form-label">ID Outlet</label>
			<div class="col-sm-12">
				 <input class="form-control" type="text" id="idoutlet" name="idoutlet" autocomplete="Off">
			</div>
        </div>

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

        <div class="form-group row">
			<label class="col-sm-12 col-form-label">Nomor Eload</label>
			<div class="col-sm-12">
				<input class="form-control" type="text" id="noeload" name="noeload" autocomplete="Off" value="">
			</div>
        </div>

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