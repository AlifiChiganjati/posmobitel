<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
session_start();
$title='Profile';
include("cek_login.inc.php");
include("connection_main.php");
include("function.inc.php");
include("header_alt2.inc.php");

$user = $_SESSION['USER'];
include("setting.inc.php");
if($_POST['btn_ganti_pin'] == "SIMPAN"){
	$pinlama = isset($_POST['old_pin'])? cleanall($_POST['old_pin']) : "";
	$pinbaru = isset($_POST['new_pin'])? cleanall($_POST['new_pin']) : "";
	$repin   = isset($_POST['re_pin'])? cleanall($_POST['re_pin']) : "";
	if($pinlama && $pinbaru && $repin && $user){
		if(is_numeric($pinbaru)){
			if($pinbaru == $repin){
				$ux = "update salesman set pinlogin = '$pinbaru' where idsales='$user'";
				if(is_numeric($user) == 1){
					if(strlen($user) > 8){
						//customer mobitel
						$sql = "select pinlogin from customer_mobitel where nohp='$user' and status='1'";
						$ux = "update customer_mobitel set pinlogin = '$pinbaru' where nohp='$user'";
					}else{
						mysql_close();
						include("connection_cesa.inc.php");
						$sql = "select pinlogin from salesman where idsales='$user'";
					}
				}else{
					mysql_close();
					include("connection_cesa.inc.php");
					$sql = "select pinlogin from salesman where idsales='$user'";
					$jum = mysql_num_rows(mysql_query($sql));
					if($jum == 0){
						mysql_close();
						include("connection_rti.inc.php");
						$sql = "select pinlogin from salesman where idsales='$user'";
					}
				}
      
				$msql = mysql_query($sql);
				if($rql = mysql_fetch_object($msql)){
					$pinlama_asli = $rql->pinlogin;
					if($pinlama == $pinlama_asli){
						if(mysql_query($ux)){
							$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							  <div class='offcanvas-body small'>
								<div class='app-info'>
								  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								  <div class='content'>
									<h3>Berhasil <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'> Ganti Password berhasil</a>
								  </div>
								</div>
							  </div>
							</div>";
						}
					}else{
						$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
							  <div class='offcanvas-body small'>
								<div class='app-info'>
								  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
								  <div class='content'>
									<h3>Berhasil <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
									<a href='#'> Password Lama Salah</a>
								  </div>
								</div>
							  </div>
							</div>";
					}
				}else{
				  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
					<div class='offcanvas-body small'>
					  <div class='app-info'>
						<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
						<div class='content'>
						  <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						  <a href='#'> Password lama anda tidak dikenal</a>
						</div>
					  </div>
					</div>
				  </div>";
				}
			}else{
				$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
				  <div class='offcanvas-body small'>
					<div class='app-info'>
					  <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
					  <div class='content'>
						<h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
						<a href='#'>Password Baru dan Konfirmasi PIN harus sama</a>
					  </div>
					</div>
				  </div>
				</div>";
			}
		}else{
		  $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
			<div class='offcanvas-body small'>
			  <div class='app-info'>
				<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
				<div class='content'>
				  <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				  <a href='#'> Password Baru harus berupa angka</a>
				</div>
			  </div>
			</div>
		  </div>";
		}
	}else{
		$msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
			<div class='offcanvas-body small'>
			  <div class='app-info'>
				<img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
				<div class='content'>
				  <h3>Warning <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
				  <a href='#'>Data Password tidak lengkap</a>
				</div>
			  </div>
			</div>
		  </div>";
	}
}
?>
<br>
<main class="main-wrap setting-page mb-xxl">
    <form class="custom-form" action="" method="post">
		<div class="row g-1">
			<div class='col-12 text-center'>Mobitel</div>
			<div class='col-4'>Username</div> <div class='col-8 text-end'><?=$user?></div>
			<div class='col-2'>Nama</div> <div class='col-10 text-end'><?=$_SESSION['NAME']?></div>
			<div class='col-4'>Email</div> <div class='col-8 text-end'></div>
			<div class='col-4'>Nomor HP</div> <div class='col-8 text-end'></div>
			<div class='col-4'>Alamat</div> <div class='col-8 text-end'></div>
			<a href='#' data-bs-toggle="offcanvas" data-bs-target="#filter" aria-controls="filter" class="btn-solid bg-dark text-center"> Ubah Password Login</a>
			<hr>
			<a href='testprint1.php' class="btn-solid bg-dark text-center" >TEST PRINT</a>
        </div>
		<hr>
		<?php
		$qry2 = mysql_query("select * from customers_digital where ");
		
		?>
		<div class="row g-1">
			<div class='col-12 text-center'>Digital</div>
			<div class='col-4'>ID</div> <div class='col-8 text-end'><?=$id_cnet?></div>
			<div class='col-4'>PIN</div> <div class='col-8 text-end'><?=$pin_cnet?></div>
			<a href='' class="btn-solid bg-dark text-center"> Ubah PIN</a>
        </div>
		<hr>
		<a href='#' class="btn-solid bg-dark text-center" data-bs-toggle="offcanvas" data-bs-target="#filter2" aria-controls="filter" >Hapus Akun</a>
	</form>
</main>
	<div class="shop-fillter offer-filter offcanvas offcanvas-bottom" tabindex="-1" id="filter" aria-labelledby="filter">
		<div class='row m-2'>
			<div class='col-7'>
				<h5 class="title-color font-md"><b>Ganti Password</b></h5>
			</div>
		</div>
	  <form method="post">
      <div class="offcanvas-body small">
        <div class="mt-0">
          <div class="row mb-1">
            <div class="col-5">
              <div class="size active">
                <span class="font-xs">Isi Password Lama</span>
              </div>
            </div>

            <div class="col-7">
              <div class="input-box">
                <input class="form-control" type="number" id="old_pin" name="old_pin" autocomplete="Off" placeholder="Password Lama">
              </div>
            </div>
          </div>
		  <div class="row mb-1">
            <div class="col-5">
              <div class="size active">
                <span class="font-xs">Isi Password Baru</span>
              </div>
            </div>
            <div class="col-7">
              <div class="input-box">
                <input class="form-control" type="number" id="new_pin" name="new_pin" autocomplete="Off" placeholder="Password Baru">
              </div>
            </div>
          </div>
		  <div class="row mb-1">
            <div class="col-5">
              <div class="size active">
                <span class="font-xs">Ulang Password Baru</span>
              </div>
            </div>

            <div class="col-7">
              <div class="input-box">
                <input class="form-control" type="number" id="re_pin" name="re_pin" autocomplete="Off" placeholder="Ulang Password Baru">
				
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas-footer">
        <div class="btn-box">
          <input class="btn-solid font-md bg-dark" type='submit' value='SIMPAN' name='btn_ganti_pin'>
        </div>
      </div>
	  </form>
    </div>
	
	<div class="shop-fillter offer-filter offcanvas offcanvas-bottom" tabindex="-1" id="filter2" aria-labelledby="filter">
		<div class='row m-2'>
			<div class='col-12'>
				<h5 class="title-color font-md"><b>Anda yakin HAPUS AKUN MOBITEL?</b></h5>
			</div>
		</div>
	  <form method="post">
      <div class="offcanvas-body small">
        <div class="mt-0">
          <div class="row mb-1 mt-2">
            <div class="col-5">
              <div class="size active">
                <span class="font-xs">Isi Password</span>
              </div>
            </div>

            <div class="col-7">
              <div class="input-box">
                <input class="form-control" type="number" id="old_pin" name="old_pin" autocomplete="Off" placeholder="Password">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas-footer">
        <div class="btn-box">
          <input class="btn-solid font-md bg-dark" type='submit' value='HAPUS' name='btn_off'>
        </div>
      </div>
	  </form>
    </div>
 <?=$msg?>
<?php
include("footer.inc.php");
?>
