<!-- Offcanvas Sidebar for mobile (visible on small screens) -->

<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" style="width: 200px;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Navigasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column h-100">
    <ul class="list-unstyled ps-0">
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
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Karyawan</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_user"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel User</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_role"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Role</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_supplier"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Supplier</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_customer"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Customer</a>
                </li>
                  <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_channel"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Channel</a>
                </li>
              </ul>
            </div>
          </li>
    </ul>
    <button type="button" id="logout" class="btn btn-outline-danger btn-sm mt-auto"> <i class="bi bi-box-arrow-in-right"></i> Logout</button>
  </div>
</div>


<div class="sidebar col-lg-2 d-none d-lg-block bg-light p-2 d-flex flex-column vh-100">
        <a
          href="#"
          class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
          <svg class="bi pe-none me-2" width="30" height="24">
            <use xlink:href="#bootstrap" />
          </svg>
          <span class="fs-5 fw-semibold">Navigasi</span>
        </a>
        <div class="d-flex flex-column flex-grow-1">
                <ul class="list-unstyled ps-0">
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
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Karyawan</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_user"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel User</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_role"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Role</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_supplier"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Supplier</a>
                </li>
                <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_customer"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Customer</a>
                </li>
                  <li>
                  <a
                    href="<?php echo $_ENV['BASE_URL']; ?>index.php?page=tabel_channel"
                    class="link-dark d-inline-flex text-decoration-none rounded">Tabel Channel</a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
        <button type="button" id="logout" class="btn btn-outline-danger btn-sm mt-auto"> <i class="bi bi-box-arrow-in-right"></i> Logout</button>
        </div>
        
</div>