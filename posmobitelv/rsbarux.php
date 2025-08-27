<?php
$title = "FORM RS BARU";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");

$depo = $_SESSION['DEPO'];
$clustero = $_SESSION['CLUSTER'];
$cluster = cluster($clustero);

if($_POST['daftar'] == "DAFTAR"){
  $nama = isset($_POST['nama'])? cleaninput($_POST['nama']) : "";
  $level = "1";
  $nohp = isset($_POST['nohp'])? cleaninput($_POST['nohp']) : "";
  $ktp = isset($_POST['ktp'])? cleaninput($_POST['ktp']) : "";
  $alamat = isset($_POST['alamat'])? cleaninput($_POST['alamat']) : "";
  $kabupaten = isset($_POST['kabupaten'])? cleaninput($_POST['kabupaten']): "";
  $kecamatan = isset($_POST['kecamatan'])? cleaninput($_POST['kecamatan']) : "";
  $idoutlet = isset($_POST['idoutlet'])? cleaninput($_POST['idoutlet']) : "";

  $sd = ($cluster == "FLOSUM") ? "82145975758" : "85237112233";

  if($idsales && $nama && $level && $nohp && $ktp && $alamat && $kabupaten && $kecamatan && $idoutlet && $depo && $cluster){

      $sce = mysql_query("select nohp from validasi_rs where nohp ='$nohp'");
      if($rce = mysql_fetch_object($sce)){

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

      }else{

        if(is_exist_customer($nohp)){

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
        }else{
          $transdate = date("d/m/Y");
          $sql = "insert ignore into validasi_rs (tanggal, idsales, nama, nohp, noktp, level, no_sd, alamat, kabupaten, kecamatan, idoutlet, depo, cluster, transdate, status, category, category_harga) values
          (now(), '$idsales', '$nama', '$nohp', '$ktp', '$level', '$sd', '$alamat', '$kabupaten', '$kecamatan', '$idoutlet', '$depo' , '$cluster', '$transdate', 1, 'ELEKTRIK', 'ELEKTRIK - RS $cluster')";

          if(!mysql_query($sql)){

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
          }else{

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

  }else{

    $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
      <div class='offcanvas-body small'>
        <div class='app-info'>
          <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
          <div class='content'>
            <h3>Data Tidak Lengkap<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
            <a href='#'>$idsales && $nama && $level && $nohp && $ktp && $alamat && $kabupaten && $kecamatan && $idoutlet && $depo</a>
          </div>
        </div>
      </div>
    </div>";
  }
}


?>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
           $(document).ready(function(){
           $('#kecamatan').change(function(){
               var inputValue = $(this).val();
               $.post('getlist.php', { dropdownValue: inputValue }, function(data){
                   document.getElementById("resultList").innerHTML = data;
               });
           });
       });
</script>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
   <!-- Form Section Start -->
      <form class="custom-form" method="post">
        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input class="form-control" type="text" id="idoutlet" name="idoutlet" autocomplete="Off" placeholder="ID Outlet">
        </div>
        <div id="hasilcustomer" class="input-box"> </div>

        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input class="form-control" type="text" id="nama" name="nama" autocomplete="Off" placeholder="Nama Reseller">
        </div>

        <div class="input-box">
          <i class="iconly-Call icli"></i>
          <input class="form-control" type="text" id="nohp" name="nohp" autocomplete="Off" placeholder="Nomor HP">
        </div>

        <div class="input-box">
          <i class="iconly-Folder icli"></i>
          <input class="form-control" type="number" id="ktp" name="ktp" autocomplete="Off" placeholder="Nomor KTP">
        </div>

        <div class="input-box">
          <i class="iconly-Folder icli"></i>
           <input class="form-control" type="text" id="alamat" name="alamat" autocomplete="Off" placeholder="Alamat sesuai di KTP">
        </div>

        <div class="input-box">
            <select id="kecamatan" name="kecamatan" class="form-control">
               <option value="">Pilih Kecamatan</option>
                <?php
                  $rs = mysql_query("select kecamatan from lokasi where cluster='$cluster' order by kecamatan");
                  $i=0;
                  while ($r = mysql_fetch_object($rs)) {
                    echo "<option value='$r->kecamatan'> ".strtoupper($r->kecamatan)." </option>";
                  }
                mysql_free_result($rs);
                ?>
             </select>
        </div>

        <div id="resultList"> </div>
            <input type="submit" class="btn-solid" name="daftar" value="DAFTAR">
      </form>
      <!-- Form Section End -->
    </main>
    <!-- Main End -->
    <br />
    <?=$msg?>
  <?php include("footer.inc.php");?>
