<?php
$title = "FOTO KUNJUNGAN";
$back_button = "sales_visit_checkin.php";
include("header_alt3.inc.php");

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<main class="main-wrap notification-page mb-xxl">
  <div class="row g-2">
    <div class="col-3">
      Pilih Kamera
    </div>
    <div class="col-9">
      <select id="cameraDropdown" class="form-control">
      </select>
      
    </div>
      <video id="video" width="640px" height="480px"></video>
      <canvas id="canvas" width="100%" style="display: none;"></canvas>
    
    <div class="col-12">
      <div class="row g-4">
        <div class="col-4"><button id="repeat" class="btn btn-sm btn-dark text-center w-100" disabled>Ulang</button></div>
        <div class="col-4"><button id="capture" class="btn btn-sm btn-dark text-center w-100">Ambil Foto</button></div>
        <div class="col-4"><button id="send" class="btn btn-sm btn-dark text-center w-100" disabled>Simpan</button></div>
      </div>
    </div>
  </div>
</main>
<script>
$(document).ready(function() {
  function getAvailableCameras() {
    return navigator.mediaDevices.enumerateDevices()
      .then(function(devices) {
        var cameras = devices.filter(function(device) {
          return device.kind === 'videoinput';
        });
        return cameras;
      });
  }

  // Fungsi untuk menambahkan opsi kamera ke dalam dropdown
  function populateCameraDropdown(cameras) {
    var dropdown = document.getElementById('cameraDropdown');
    cameras.forEach(function(camera, index) {
      var option = document.createElement('option');
      option.value = camera.deviceId;
      option.text = camera.label || 'Kamera ' + (index + 1);
      dropdown.appendChild(option);
    });
  }

  // Fungsi untuk mengubah sumber video berdasarkan pilihan kamera
  function changeCamera() {
    var selectedCameraId = document.getElementById('cameraDropdown').value;
    var constraints = { video: { deviceId: { exact: selectedCameraId } } };
    navigator.mediaDevices.getUserMedia(constraints)
      .then(function(stream) {
        var video = document.getElementById('video');
        video.srcObject = stream;
        video.play();
      })
      .catch(function(err) {
        alert("Tidak dapat mengakses kamera: " + err);
      });
  }

  // Inisialisasi: dapatkan daftar kamera yang tersedia dan populasi dropdown
  getAvailableCameras().then(function(cameras) {
    populateCameraDropdown(cameras);
    if (cameras.length > 1) {
      document.getElementById('cameraDropdown').selectedIndex = 1;
      changeCamera();
    }
  });

  document.getElementById('cameraDropdown').addEventListener('change', changeCamera);

  document.getElementById('capture').addEventListener('click', function() {
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    video.style.display="none";
    canvas.style.display="block";
    document.getElementById('capture').disabled=true;
    document.getElementById('repeat').disabled=false;
    document.getElementById('send').disabled=false;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
  });

  document.getElementById('repeat').addEventListener('click', function() {
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    video.style.display="block";
    canvas.style.display="none";
    document.getElementById('capture').disabled=false;
    document.getElementById('repeat').disabled=true;
    document.getElementById('send').disabled=true;
  });
});
</script>
<?php include("footer_alt.inc.php");?>