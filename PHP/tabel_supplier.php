  <!-- Modal_supplier-->
  <div class="modal fade" id="modal_supplier"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <label class="form-label">Nama:</label>
          <input class="form-control" type="text" id="supplier_nama" name="supplier_nama" value=""><br><br>
          <label class="form-label">Alamat:</label>
          <input class="form-control" type="text" id="supplier_alamat"><br><br>
          <label class="form-label" for="phone">Nomor Telepon:</label>
          <div class = "input-group mb-3">
          <span class="input-group-text" id="basic-addon1">+62</span>
          <input class="form-control" type="text" id="supplier_no_telp" name="supplier_no_telp" aria-describedby="basic-addon1"value="">
          </div><br><br>
          <label class="form-label">NIK:</label>
          <input class="form-control" type="text" id="supplier_ktp"><br><br>
          <label class="form-label">NPWP:</label>
          <input class="form-control" type="text" id="supplier_npwp"><br><br>
          <label class="form-label">Status:</label>
          <select class ="form-select" id ="supplier_status">
          <option value = "aktif">Aktif</option>
          <option value = "non aktif">Non Aktif</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_supplier" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
    <!-- Modal_supplier_update-->
  <div class="modal fade" id="modal_supplier_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Supplier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class = "form-label">Kode Supplier</label>
          <input class="form-control" type ="text" id = "update_supplier_id" disabled><br><br>
          <label class="form-label">Nama:</label>
          <input class="form-control" type="text" id="update_supplier_nama"><br><br>
          <label class="form-label">Alamat:</label>
          <input class="form-control" type="text" id="update_supplier_alamat"><br><br>          
          <label class="form-label" for="phone">Nomor Telepon:</label>
          <div class = "input-group mb-3">
          <span class="input-group-text" id="basic-addon1">+62</span>
          <input class="form-control" type="text" id="update_supplier_no_telp" name="update_supplier_no_telp" aria-describedby="basic-addon1"value="">
          </div><br><br>
          <label class="form-label">NIK:</label>
          <input class="form-control" type="text" id="update_supplier_ktp"><br><br>
          <label class="form-label">NPWP:</label>
          <input class="form-control" type="text" id="update_supplier_npwp"><br><br>
          <label class="form-label">Status:</label>
          <select class ="form-select" id ="update_supplier_status">
          <option value = "aktif">Aktif</option>
          <option value = "non aktif">Non Aktif</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_supplier_update" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
<main class="col-12 col-lg-10 ms-auto px-1">
      <!-- Main content -->
      <div id="main"  class="table-responsive">
        <h3>Tabel Supplier</h3>
          <div id="table_supplier"></div>
      </div>
  </main>