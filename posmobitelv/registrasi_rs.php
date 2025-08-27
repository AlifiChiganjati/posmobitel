<?php
$title = "Registrasi RS";
session_start();
include("session_check.php");
$connec = include("connection_rti.inc.php");
include("function.inc.php");
include("header_alt2.inc.php");

$msg = '';
if (isset($_POST['tambah_data'])) {
    $nama = $_POST['nama'];
    $nama_pemilik = $_POST['nama_pemilik'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $npwp = $_POST['npwp'];
    $nik = $_POST['nik'];
    $alamat = $_POST['alamat'];
    $area = $_POST['area'];
    $unit_bisnis = $_POST['unit_bisnis'];
    $depo_rti = $_POST['depo_rti'];
    $depo_rai = $_POST['depo_rai'];
    $gudang_rti = $_POST['gudang_rti'];
    $gudang_rai = $_POST['gudang_rai'];
    $id_sales_rti = $_POST['id_sales_rti'];
    $id_sales_rai = $_POST['id_sales_rai'];

    $sql = "insert into validasi_customer (nama, nama_pemilik, nohp, email, npwp, nik, alamat, idsales_rti, idsales_rai, area, unit_bisnis, depo_rti, depo_rai, gudang_rti, gudang_rai, status) values ('$nama', '$nama_pemilik', '$no_hp', '$email', '$npwp', '$nik', '$alamat', '$area', '$unit_bisnis', '$depo_rti', '$depo_rai', '$gudang_rti', '$gudang_rai', '$id_sales_rti', '$id_sales_rai', '0' )";

    if (mysql_query($sql)) {
        $connec = include("connection_rai.inc.php");
        $sql2 = "insert into validasi_customer (nama, nama_pemilik, nohp, email, npwp, nik, alamat, idsales_rti, idsales_rai, area, unit_bisnis, depo_rti, depo_rai, gudang_rti, gudang_rai, status) values ('$nama', '$nama_pemilik', '$no_hp', '$email', '$npwp', '$nik', '$alamat', '$area', '$unit_bisnis', '$depo_rti', '$depo_rai', '$gudang_rti', '$gudang_rai', '$id_sales_rti', '$id_sales_rai', 0 )";

        if (mysql_query($sql2)) {
            $connec = include("connection_main.php");
            $sql3 = "insert into customer_mobitel (nama, nama_pemilik, nohp, email, npwp, nik, alamat, idsales_rti, idsales_rai, area, unit_bisnis, depo_rti, depo_rai, gudang_rti, gudang_rai, status) values ('$nama', '$nama_pemilik', '$no_hp', '$email', '$npwp', '$nik', '$alamat', '$area', '$unit_bisnis', '$depo_rti', '$depo_rai', '$gudang_rti', '$gudang_rai', '$id_sales_rti', '$id_sales_rai', '0' )";
            if (mysql_query($sql3)) {
                $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
                <div class='offcanvas-body small'>
                    <div class='app-info'>
                        <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                        <div class='content'>
                            <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                            <a href='#'>Data Berhasil Disimpan</a>
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
                            <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                            <a href='#'>Error menambahkan data!</a>
                        </div>
                    </div>
                </div>
            </div>";
            }
        } else {
            $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
            <div class='offcanvas-body small'>
                <div class='app-info'>
                    <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                    <div class='content'>
                        <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                        <a href='#'>Error menambahkan data!</a>
                    </div>
                </div>
            </div>
        </div>";
        }
    } else {

        $msg = "<div class='offcanvas offcanvas-bottom addtohome-popup show' tabindex='-1' id='offcanvas'>
        <div class='offcanvas-body small'>
            <div class='app-info'>
                <img src='assets/images/logo/mail.png' class='img-fluid' alt=''>
                <div class='content'>
                    <h3 class='mb-1'>Notification <i data-feather='x' data-bs-dismiss='offcanvas'></i></h3>
                    <a href='#'>Error menambahkan data!</a>
                </div>
            </div>
        </div>
    </div>";
    }
}


?>

<main class="main-wrap setting-page mb-xxl">

    <form class="custom-form" method="post">
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Nama
            <input class="form-control" type="text" id="nama" name="nama" value="" autocomplete="Off" placeholder='Masukkan Nama'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Nama Pemilik
            <input class="form-control" type="text" id="nama_pemilik" name="nama_pemilik" value="" autocomplete="Off" placeholder='Masukkan Nama Pemilik'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            No. HP
            <input class="form-control" type="text" id="no_hp" name="no_hp" value="" autocomplete="Off" placeholder='Masukkan No. HP'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Email
            <input class="form-control" type="text" id="email" name="email" value="" autocomplete="Off" placeholder='Masukkan Email'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            NPWP
            <input class="form-control" type="text" id="npwp" name="npwp" value="" autocomplete="Off" placeholder='Masukkan NPWP'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            NIK
            <input class="form-control" type="text" id="nik" name="nik" value="" autocomplete="Off" placeholder='Masukkan NIK'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Alamat
            <input class="form-control" type="text" id="alamat" name="alamat" value="" autocomplete="Off" placeholder='Masukkan Alamat'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Area
            <input class="form-control" type="text" id="area" name="area" value="" autocomplete="Off" placeholder='Masukkan Area'>

        </div>
        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Unit Bisnis
            <input class="form-control" type="text" id="unit_bisnis" name="unit_bisnis" value="" autocomplete="Off" placeholder='Masukkan Unit Bisnis'>

        </div>


        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Depo RTI
            <input class="form-control" type="text" id="depo_rti" name="depo_rti" value="" autocomplete="Off" placeholder='Masukkan Unit Depo RTI'>

        </div>

        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Depo RAI
            <input class="form-control" type="text" id="depo_rai" name="depo_rai" value="" autocomplete="Off" placeholder='Masukkan Unit Depo RTI'>

        </div>

        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Gudang RTI
            <input class="form-control" type="text" id="gudang_rti" name="gudang_rti" value="" autocomplete="Off" placeholder='Masukkan Unit Gudang RTI'>

        </div>

        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            Gudang RAI
            <input class="form-control" type="text" id="gudang_rai" name="gudang_rai" value="" autocomplete="Off" placeholder='Masukkan Unit Gudang RAI'>

        </div>

        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            ID Sales RTI
            <input class="form-control" type="text" id="id_sales_rti" name="id_sales_rti" value="" autocomplete="Off" placeholder='Masukkan Unit ID Sales RTI'>

        </div>

        <div class="input-box">
            <i class="iconly-Phone icli"></i>
            ID Sales RAI
            <input class="form-control" type="text" id="id_sales_rai" name="id_sales_rai" value="" autocomplete="Off" placeholder='Masukkan Unit ID Sales RAI'>

        </div>




        <button type='submit' class='btn-solid bg-dark' name='tambah_data'>Registrasi</button>
    </form>
    <div class='row mt-2'>

    </div>
</main>

<?= $msg ?>

<!-- Main End -->
<?php include("footer_alt.inc.php"); ?>