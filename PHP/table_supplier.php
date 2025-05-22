  <!-- Modal_supplier-->
  <div class="modal fade" id="modal_supplier"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Supplier</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label class="form-label">Nama:</label>
          <input class="form-control" type="text" id="supplier_name"><br><br>
          <label class="form-label">Alamat:</label>
          <input class="form-control" type="text" id="supplier_alamat"><br><br>
          <label class="form-label">No Telepon:</label>
          <input class="form-control" type="text" id="supplier_no_telp"><br><br>
          <label class="form-label">NIK:</label>
          <input class="form-control" type="text" id="supplier_ktp"><br><br>
          <label class="form-label">NPWP:</label>
          <input class="form-control" type="text" id="supplier_npwp"><br><br>
          <label class="form-label">Status:</label>
          <input class="form-control" type="text" id="supplier_status"><br><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_supplier" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
<main class="col-12 col-lg-10 ms-auto px-3">
      <!-- Main content -->
      <div id="main"  class="table-responsive">
        <button type="button" id ="logout" class="btn btn-outline-danger"> <i class="bi bi-box-arrow-in-right"></i> Logout</button>
          <div id="table_supplier"></div>
      </div>
  </main>