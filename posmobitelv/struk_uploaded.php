<?php
$title = "STRUK UPLOAD";
session_start();
include("session_check.php");
include("connection.inc.php");
include("function.inc.php");
include("header_alt.inc.php");

$idsales = $_SESSION['IDSALES'];
$gudang = $_SESSION['IDSTORE'];
if($_POST['btn_hapus'] == 'Hapus'){
	$gb_name = $_POST['img_del'];
	if(mysql_query("delete from settlement_image where nama_file='$gb_name'")){
		$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
		  <div class='offcanvas-body small'>
			<div class='app-info'>
			  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
			  <div class='content'>
				<h3>Pesan<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				<a href='#'>Sukses hapus $gb_name</a>
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
				<h3>Pesan<i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				<a href='#'>Gagal hapus $gb_name</a>
			  </div>
			</div>
		  </div>
		</div>";
	}
	
}
?>
 <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<!-- Main Start -->
    <main class="main-wrap setting-page mb-xxl">

          <!-- Form Section Start -->
		<form class="custom-form" method="post" enctype='multipart/form-data'>
			
				<div class='row m-2'>
					<?php
					$qry_struk = mysql_query("select * from settlement_image where sales = '$idsales' and date(tanggal)='".date("Y-m-d")."'");
						while($data= mysql_fetch_object($qry_struk)){
							echo "<div class='col-3 mb-3'>
										<a data-bs-toggle='offcanvas' data-bs-target='#filterImg' aria-controls='filter' data-imgx='$data->nama_file' data-imgdel='$data->nama_file'><img src='struk/$data->nama_file' width='100%'></a>
								</div>";
						}
					?>
			</div>
		</form>
    </main>
	<div class="shop-fillter offer-filter offcanvas offcanvas-bottom" tabindex="-1" id="filterImg" aria-labelledby="filter">
      <div class="offcanvas-header">
      </div>
	  <form method="post">
      <div class="offcanvas-body small">
        <div class="pack-size mt-0">
          <div class="row g-3">
              <div class="input-box text-center">
				<img src='struk/' width='100%' id='imgx'>
              </div>
          </div>
		  <div class="row g-3">
            <div class="col-12">
              <div class="input-box">
                <input class="form-control" type="hidden" id="img_del" name="img_del" placeholder='.....'>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas-footer">
			<div class='row '>
				<div class='col-3'>
					<button class="btn-solid bg-danger" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>
				</div>
				<div class='col-6'>
				</div>
				<div class='col-3 text-end'>
					<input class="btn-solid font-md bg-danger text-center" type='submit' value='Hapus' name='btn_hapus'>
				</div>
			</div>
      </div>
	  </form>
    </div>

    <?=$msg?>
<script>
	$(function () {
	  $('#filterImg').on('show.bs.offcanvas', function (event) {
		var button = $(event.relatedTarget); // Button that triggered the modal
		var imgx = button.data('imgx');
		var img_del = button.data('imgdel');
		var modal = $(this);
		//modal.find('#imgx').src(imgx);
		const img = document.getElementById('imgx');
		img.src = `struk/${imgx}`;
		modal.find('#img_del').val(img_del);
		
	  });
	})
</script>
<?php
include("footer.inc.php");
?>
