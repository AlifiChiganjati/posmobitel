<main class="">
      <section class="recently p-0">
        <div class="recently-wrap bg-white py-0">
          <div class="recently-list-slider recently-list">
              <div class="item bg-secondary">
                <a href="logorder_rti.php" class='text-white'>XIAOMI</a>
              </div>
              <div class="item bg-secondary">
                <a href="logorder_rai.php" class='text-white'>POCO</a>
              </div>
              <div class="item bg-secondary">
                <a href="logorder_cesa.php" class='text-white'>SMARTFREN</a>
              </div>
              <div class="item bg-secondary">
                <a href="logorder_digital.php" class='text-white'>DIGITAL</a>
              </div>
              <div class="item bg-secondary">
                <a href="logorder_cw.php" class='text-white'>ACCESSORIES</a>
              </div>
            
          </div>
		  <div class='row mt-2'>
			<div class='col-6 text-end'>
			</div>
			<div class='col-6 text-end'>
				<button class="btn btn-outline-dark btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter" aria-controls="filter">Filter</button>
			</div>
		  </div>
        </div>
		
      </section>
	  
</main>
<?php
	if(isset($_POST['belum'])){
		$filters = "belum";
	}else if(isset($_POST['sudah'])){
		$filters = "sudah";
	}else if(isset($_POST['all'])){
		$filters = "all";
	}else{
		$filters = "belum";
	}
?>
<div class="shop-fillter offcanvas offcanvas-bottom" tabindex="-1" id="filter" aria-labelledby="filter" aria-modal="true" role="dialog" style="visibility: visible;">
      <div class="offcanvas-header">
        <div class="catagories">
          <h5 class="title-color font-md">Filter Pembayaran</h5>
          <button class="font-md reset"></button>
        </div>
      </div>
      <div class="offcanvas-footer">
		<form method='post'>
        <div class='row g-1'>
			<div class='col-6'>
				<button type='submit' class='btn btn-solid bg-dark' name='belum'>Belum diterima</button>
			</div>
			<div class='col-6'>
				<button type='submit' class='btn btn-solid bg-dark' name='sudah'>Sudah diterima</button>
			</div>
			<div class='col-6'>
				<button type='submit' class='btn btn-solid bg-dark' name='all'>Semua</button>
			</div>
			<div class='col-6'>
				
			</div>
		</div>
		</form>
      </div>
    </div>