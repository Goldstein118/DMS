  <!-- modal_produk -->
  <div class="modal fade" id="modal_produk" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Produk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label" for="name">Nama:</label>
          <input class="form-control" type="text" id="name_produk" name="name_produk" value="">
          <label class="form-label" for="kategori">Kategori:</label>
          <select class="form-select" id="kategori"></select>
          <label class="form-label" for="brand">Brand:</label>
          <select class="form-select" id="brand"></select>
          <label class="form-label" for="no_sku">No SKU:</label>
          <input class ="form-control" type ="text" id ="no_sku" name="no_sku"aria-describedby="basic-addon1"value="">
          <label class="form-label" for="status_produk">Status:</label>
          <select class="form-select" id="status_produk">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select>
          <label class="form-label" for="harga_minimal">Harga Minimal:</label>
          <input class ="form-control" type ="text" id ="harga_minimal" name="harga_minimal"aria-describedby="basic-addon1"value="">
        </div>
        <div class="modal-footer">
          <button id="submit_produk" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- modal_produk_update-->
  <div class="modal fade" id="update_modal_produk" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Produk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label" for="update_produk_id">Kode Produk:</label>
          <input class="form-control" type="text" id="update_produk_id" name="update_produk_id" value="" disabled>
          <label class="form-label" for="update_name">Nama:</label>
          <input class="form-control" type="text" id="update_name_produk" name="update_name_produk" value="">
          <label class="form-label" for="update_kategori">Kategori:</label>
          <select class="form-select" id="update_kategori"></select>
          <label class="form-label" for="update_brand">Brand:</label>
          <select class="form-select" id="update_brand"></select>
          <label class="form-label" for="update_no_sku">No SKU:</label>
          <input class ="form-control" type ="text" id ="update_no_sku" name="update_no_sku"aria-describedby="basic-addon1"value="">
          <label class="form-label" for="update_status_produk">Status:</label>
          <select class="form-select" id="update_status_produk">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select>
          <label class="form-label" for="update_harga_minimal">Harga Minimal:</label>
          <input class ="form-control" type ="text" id ="update_harga_minimal" name="update_harga_minimal"aria-describedby="basic-addon1"value="">
        </div>
        <div class="modal-footer">
          <button id="update_submit_produk" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

<main class="col-12 col-lg-10 ms-auto px-1" >
      <div id="main" class="table-responsive" >
        <h3>Data Produk</h3>
          <div id ="table_produk"></div>
      </div>
</main>