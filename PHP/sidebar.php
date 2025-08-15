<!-- Offcanvas Sidebar for mobile (visible on small screens) -->

<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" style="width: 200px;">
  <div class="offcanvas-header">
    <img src="../images/8.jpg" class="img-fluid d-block mx-auto my-3" style="max-height: 60px;" alt="DMS"><br>
    <div><span id="welcomeText" class="fw-bold">Selamat Datang,</span><br>
      <span id="username_mobile" class="text-muted small"></span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <hr>
  <div class="offcanvas-body d-flex flex-column h-100">
    <ul class="list-unstyled ps-0 flex-grow-1">
      <li class="mb-1">
        <button
          class="btn btn-toggle d-inline-flex align-items-center rounded border-0"
          data-bs-toggle="collapse"
          data-bs-target="#data-collapse"
          aria-expanded="true">
          <i
            class="bi bi-chevron-down toggle-icon"
            data-bs-target="#data-collapse"></i>
          Data
        </button>
        <div class="collapse show" id="data-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_karyawan"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_karyawan' ? 'active' : ''; ?>"> Karyawan</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_user "
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_user' ? 'active' : ''; ?>"> User</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_role"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_role' ? 'active' : ''; ?>"> Role</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_supplier"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_supplier' ? 'active' : ''; ?>"> Supplier</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_customer"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_customer' ? 'active' : ''; ?>"> Customer</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_channel"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_channel' ? 'active' : ''; ?>"> Channel</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_kategori"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_kategori' ? 'active' : ''; ?>"> Kategori</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_brand"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_brand' ? 'active' : ''; ?>"> Brand</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_produk"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_produk' ? 'active' : ''; ?>"> Produk</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_divisi"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_divisi' ? 'active' : ''; ?>"> Divisi</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_gudang"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_gudang' ? 'active' : ''; ?>"> Gudang</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_pricelist"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_pricelist' ? 'active' : ''; ?>"> Pricelist</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_armada"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_armada' ? 'active' : ''; ?>"> Armada</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_frezzer"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_frezzer' ? 'active' : ''; ?>"> Frezzer</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_satuan"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_satuan' ? 'active' : ''; ?>"> Satuan</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_data_biaya"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_data_biaya' ? 'active' : ''; ?>"> data biaya</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="mb-1">
        <button
          class="btn btn-toggle d-inline-flex align-items-center rounded border-0"
          data-bs-toggle="collapse"
          data-bs-target="#penjualan-collapse"
          aria-expanded="true">
          <i
            class="bi bi-chevron-down toggle-icon"
            data-bs-target="#penjualan-collapse"></i>
          Pembelian
        </button>
        <div class="collapse show" id="penjualan-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_promo"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_promo' ? 'active' : ''; ?>"> Promo</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_pembelian"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_pembelian' ? 'active' : ''; ?>"> Pembelian</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_data_biaya"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_data_biaya' ? 'active' : ''; ?>"> data biaya</a>
            </li>

          </ul>
        </div>
      </li>
    </ul>
    <button type="button" id="logout" class="logout-button mt-auto"> <i class="bi bi-box-arrow-in-right"></i> Logout</button>
  </div>
</div>


<div class="sidebar col-lg-2 d-none d-lg-block bg-white p-2 d-flex flex-column ">
  <div class="d-flex align-items-center justify-content-center mb-3 border-bottom py-2 flex-column text-center">
    <img src="../images/8.jpg" alt="DMS" class="img-fluid d-block mb-2" style="max-height: 60px;">
    <span id="welcomeText" class="fw-bold">Selamat Datang,</span>
    <span id="username" class="text-muted small"></span>
  </div>
  <div class="sidebar_body d-flex flex-column flex-grow-1">
    <ul class="list-unstyled ps-0 flex-grow-1">
      <li class="mb-1">
        <button
          class="btn btn-toggle d-inline-flex align-items-center rounded border-0"
          data-bs-toggle="collapse"
          data-bs-target="#data-collapse"
          aria-expanded="true">
          <i
            class="bi bi-chevron-down toggle-icon"
            data-bs-target="#data-collapse"></i>
          Data
        </button>
        <div class="collapse show" id="data-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_karyawan"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_karyawan' ? 'active' : ''; ?>"> Karyawan</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_user"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_user' ? 'active' : ''; ?>"> User</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_role"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_role' ? 'active' : ''; ?>"> Role</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_supplier"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_supplier' ? 'active' : ''; ?>"> Supplier</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_customer"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_customer' ? 'active' : ''; ?>"> Customer</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_channel"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_channel' ? 'active' : ''; ?>"> Channel</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_kategori"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_kategori' ? 'active' : ''; ?>"> Kategori</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_brand"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_brand' ? 'active' : ''; ?>"> Brand</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_produk"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_produk' ? 'active' : ''; ?>"> Produk</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_divisi"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_divisi' ? 'active' : ''; ?>"> Divisi</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_gudang"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_gudang' ? 'active' : ''; ?>"> Gudang</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_pricelist"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_pricelist' ? 'active' : ''; ?>"> Pricelist</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_armada"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_armada' ? 'active' : ''; ?>"> Armada</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_frezzer"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_frezzer' ? 'active' : ''; ?>"> Frezzer</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_satuan"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_satuan' ? 'active' : ''; ?>"> Satuan</a>
            </li>


          </ul>
        </div>
      </li>

      <li class="mb-1">
        <button
          class="btn btn-toggle d-inline-flex align-items-center rounded border-0"
          data-bs-toggle="collapse"
          data-bs-target="#penjualan-collapse"
          aria-expanded="true">
          <i
            class="bi bi-chevron-down toggle-icon"
            data-bs-target="#penjualan-collapse"></i>
          Pembelian
        </button>
        <div class="collapse show" id="penjualan-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_promo"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_promo' ? 'active' : ''; ?>"> Promo</a>
            </li>

            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_pembelian"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_pembelian' ? 'active' : ''; ?>"> Pembelian</a>
            </li>
            <li>
              <a
                href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_data_biaya"
                class="link-dark d-inline-flex text-decoration-none rounded <?php echo ($_GET['page'] ?? '') === 'tabel_data_biaya' ? 'active' : ''; ?>"> data biaya</a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
    <button type="button" id="logout" class="logout-button mt-auto"> <i class="bi bi-box-arrow-in-right"></i> Logout</button>
  </div>
</div>