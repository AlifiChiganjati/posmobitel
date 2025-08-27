<?php
$title = "KUNJUNGAN SALES";
$back_button = "jbl_sales_visit.php";
session_start();
include("session_check.php");
include("connection_jbl.inc.php");
include("function.inc.php");
include("header_alt3.inc.php");
$pesan = "";
$datenow=date("Y-m-d");
$idsales=$_SESSION['IDSALES'];
$iddata = explode("#",base64_decode($_GET['ck']));
$id = $iddata[0];
$namaoutlet = $iddata[1];
$idoutlet = $iddata[2];


if(isset($_POST['proses'])){
  $long = isset($_POST['longi']) ? $_POST['longi']:"" ;
  $lat = isset($_POST['lati']) ? $_POST['lati'] : "";
  $reff_number = date("ymd").str_shuffle(date("His"));
  $gstatus = false;
  $sts = isset($_POST['status']) ? $_POST['status'] : "";
  $ket = isset($_POST['keterangan']) ? $_POST['keterangan'] : "";
  $target_dir = "sales_visit/";
  $filename_new = $idsales."_".$idoutlet."_".date('YmdHis');
  
  if(!$long && !$lat){
    $res = "Lokasi tidak terdeteksi";
  }else{
    if($_FILES["gambar1"]["size"] > 0 ){
      $nama_file_asli = basename($_FILES["gambar1"]["name"]);
      $extension = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
      $filename_new1 = $filename_new."_1.".$extension;
      $target_file = $target_dir . $filename_new1;
      $check = getimagesize($_FILES["gambar1"]["tmp_name"]);
      if($check !== false) {
        if (move_uploaded_file($_FILES["gambar1"]["tmp_name"], $target_file)) {
          if($_FILES["gambar2"]["size"] > 0 ){
            $nama_file_asli2 = basename($_FILES["gambar2"]["name"]);
            $extension2 = pathinfo($nama_file_asli2, PATHINFO_EXTENSION);
            $filename_new2 = $filename_new."_2.".$extension2;
            $target_file2 = $target_dir . $filename_new2;
            $check2 = getimagesize($_FILES["gambar2"]["tmp_name"]);
            if($check2 !== false) {
              if (!move_uploaded_file($_FILES["gambar2"]["tmp_name"], $target_file2)) {
                $filename_new2= "gambar 2 gagal diupload";
              }
            }else{
              $filename_new2= "gambar 2 bukan gambar";
            }
          }else{
            $filename_new2 = "gambar belum dipilih";
          }
          $qry = "update sales_visit set visit_status=$sts, visit_time=now(), reff_number='$reff_number', status='VISITED'
                    where jc_date='$datenow' and employee_id='$idsales' 
                    and id='$id' and partner_name='$namaoutlet' and visit_status=0";
          if(mysql_query($qry)){
            $klx = mysql_query("insert into sales_visit_detail set reff_number='$reff_number', image1='$filename_new1', 
                          image2='$filename_new2', latitude='$lat', longitude='$long', date_created=now(),keterangan='$ket', status=$sts");
            if($klx){
              $res = "Checkin berhasil";
            }else{
              $res .= "[402]Proses gagal silahkan ulangi beberapa saat lagi";
            }
          }else{
            $res .="[401]Proses gagal silahkan ulangi beberapa saat lagi $qry";
          }
        }else{
          $res .= "Gambar 1 gagal diupload";
        }
      }else{
        $res .= "Gambar 1 bukan format gambar";
      }
    }else{
      $res .= "Gambar 1 belum dipilih";
    }   
  } 
}
$pesan = $res;

$qry = "select * from sales_visit where jc_date='$datenow' and employee_id='$idsales' 
        and id='$id' and partner_name='$namaoutlet' and visit_status=0 order by order_number";

$sql = mysql_query($qry);
$row = mysql_num_rows($sql);
if($row > 0){
  while ($rs = mysql_fetch_object($sql)){
	  $dat .= "<div class='col-4'>Tanggal</div><div class='col-8'>: $datenow</div>
            <div class='col-4'>ID Outlet</div><div class='col-8'>: $rs->partner_id</div>
            <div class='col-4'>Nama Outlet</div><div class='col-8'>: $rs->partner_name</div>";
  }
}else{
  echo "<script>window.location.replace('sales_visit.php')</script>";
}
?>
   <main class="main-wrap notification-page mb-xxl">
      <section class="tab-content ratio2_1" id="pills-tabContent">
        <div class="tab-pane fade show active" id="offer1" role="tabpanel" aria-labelledby="offer1-tab">
          <div class="offer-wrap">
            <?=$pesan?>
            <form class="row mt-3 g-1" method="post" enctype="multipart/form-data">
                <?=$dat?>
                <div class='col-4'>Lokasi</div>
                <div class='col-12'>
                  <div class="row">
                    <div class="col-6">
                      <label>Longitude</label>
                      <input type="text" id="longi" name="longi" readonly class="form-control">
                    </div>
                    <div class="col-6">
                      <label>Latitude</label>
                      <input type="text" id="lati" name="lati" readonly class="form-control">
                    </div>
                  </div>
                </div>
                
                <div class='col-4'>Foto 1</div>
                  <div class='col-12 text-center'>
                    <input type="file" class="form-control" id="fileInput" accept="image/*" name="gambar1" onchange="previewImage('fileInput','imagePreview');" required>
                  <div id="imagePreview" class="mt-1"></div>
                </div>
                <div class='col-4 mt-3'>Foto 2</div>
                  <div class='col-12 text-center'>
                    <input type="file" class="form-control" id="fileInput2" accept="image/*" name="gambar2" onchange="previewImage('fileInput2','imagePreview2');">
                  <div id="imagePreview2" class="mt-1"></div>
                </div>
                <div class='col-4 mt-3'>Status</div>
                  <div class='col-12 text-center'>
                    <select name="status" class="form-control" required>
                      <option value="">Pilih status kunjungan</option>
                      <option value="1">Kunjungan Selesai</option>
                      <option value="2">Outlet Tutup Sementara</option>
                      <option value="3">Outlet Tutup Permanen</option>
                    </select>
                </div>
                <div class='col-4 mt-3'>Keterangan</div>
                  <div class='col-12 text-center'>
                    <textarea class="form-control" name="keterangan" maxlength="150"></textarea>
                </div>
                
                <div id="div_isi">
                </div>
                <div class='col-12 text-center mt-5 d-flex'><button class="btn btn-dark btn-sm w-100" type="submit" name="proses">Check In</button></div>
            </form>
          </div>
        </div>
      </section>
    </main>
<script>
function previewImage(var1, var2) {
    var fileInput = document.getElementById(var1);
    var imagePreview = document.getElementById(var2);
    if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            imagePreview.innerHTML = '';
            imagePreview.appendChild(img);
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
}
</script>
<script src="geo-location/js/geo-min.js" type="text/javascript" charset="utf-8"></script>
    <script>
        if(geo_position_js.init()){
            geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
        }
        else{
            div_isi=document.getElementById("div_isi");
            div_isi.innerHTML ="Tidak ada fungsi geolocation";
        }

        function success_callback(p)
        {
            latitude=p.coords.latitude ;
            longitude=p.coords.longitude;
            pesan='posisi:'+latitude+','+longitude;
            document.getElementById("lati").value=latitude;
            document.getElementById("longi").value=longitude; 
        }
        
        function error_callback(p)
        {
            div_isi=document.getElementById("div_isi");
            div_isi.innerHTML ='error='+p.message;
        }        
    </script>
<?php include("footer_alt.inc.php");?>
