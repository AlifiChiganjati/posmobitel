<?php
session_start();
$title = "Setup Printer";
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");
?>

    <main class="main-wrap notification-page mb-xxl">
      <!-- Tab Content Start -->
      <section class="tab-content ratio2_1" id="pills-tabContent">
        <!-- Offer Content Start -->
        <div class="tab-pane fade show active" id="offer1" role="tabpanel" aria-labelledby="offer1-tab">
          <!-- Yesterday Start -->
          <div class="offer-wrap">
  
            <!-- Offer Box Start -->
         <h6>LANGKAH PERTAMA</h6>
         <p>Download App printer disini <a href="printer/RawBT_v5.0.2.apk" class="btn btn-danger btn-sm"> RawBT </a>, Install Aplikasi ini dan pilih GRANT</p>
			<div class='col-12'>
			 <img src="printer/rawbt1.jpg" alt="Printer 1" width='100%'>
			 <p>Allow / Izinkan perangkat ini diperangkat anda</p>
			 <hr />
			 <h6>HIDUPKAN PRINTER DAN SAMBUNGKAN KE PERANGKAT ANDA via BLUETOOTH</h6>
			 <p>Pastikan Mobile Printer sudah tersambung ke perangkat anda, cth Printer telah tersambung dengan nama MPT II</p>
			 <img src="printer/rawbt2.jpg" alt="Printer 2" width='100%'>
			 <hr />
			 <h6>BUKA APP RAWBT di Perangkat</h6>
			 <p>Pastikan di Connection Method = Bluetooth, dan pilih nama perangkat yang tersambung pada Bluetooth Printer = MPT II (sesuaikan dengan nama Perangkat anda). Contoh lihat gambar dibawah</p>
			 <img src="printer/rawbt4.jpg" alt="Printer 4" width='100%'>
			 <p>Hasil akhir</p>
			 <img src="printer/rawbt5.jpg" alt="Printer 5" width='100%'>
			 <p>Test Print jika diperlukan</p>
			 <hr />
			 <h6>CONTOH HALAMAN PREVIEW / TINJAUAN SESAAT SETELAH TRANSAKSI</h6>
			 <p>Tekan ICON Printer untuk mencetak struk ke pelanggan, seperti gambar dibawah</p>
			 <img src="printer/rawbt7.jpg" alt="Printer 7" width='100%'>
			</div>
         </div>

        </div>
        <!-- Offer Content End -->
      </section>
      <!-- Tab Content End -->
    </main>

<?php
include("footer.inc.php");
 ?>
