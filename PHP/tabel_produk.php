  <!-- modal_produk -->
  <div class="modal fade" id="modal_produk" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row gap-3">
            <div class="col">
              <div class="card">
                <div class="card-header"> Produk</div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col">
                      <label class="form-label mb-0 mt-2" for="name">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <input class="form-control" type="text" id="name_produk" name="name_produk" value="">
                      <label class="form-label mb-0 mt-2" for="kategori">Kategori:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <select class="form-select" id="kategori"></select>
                      <label class="form-label mb-0 mt-2" for="brand">Brand:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <select class="form-select" id="brand"></select>
                    </div>
                    <div class="col">
                      <label class="form-label mb-0 mt-2" for="no_sku">No SKU:</label>
                      <input class="form-control" type="text" id="no_sku" name="no_sku" aria-describedby="basic-addon1" value="">
                      <label class="form-label mb-0 mt-2" for="status_produk">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <select class="form-select" id="status_produk">
                        <option value="aktif">Aktif</option>
                        <option value="non aktif">Non Aktif</option>
                      </select>
                      <label class="form-label mb-0 mt-2" for="harga_minimal">Harga Minimal:</label>
                      <input class="form-control" type="text" id="harga_minimal" name="harga_minimal" aria-describedby="basic-addon1" value="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <div class="card-header">Pricelist</div>
                <div class="card-body">
                  <table class="table  table-hover table-bordered table-sm" id="detail_pricelist_produk_table">
                    <thead id="detail_pricelist_produk_thead">
                      <tr>
                        <th scope="col">
                          Pricelist
                        </th>
                        <th scope="col">
                          Harga
                        </th>
                        <th scope="col">
                          Aksi
                        </th>
                      </tr>
                    </thead>
                    <tbody id="create_detail_pricelist_produk_tbody">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="submit_produk" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- modal_produk_update-->
  <div class="modal fade" id="update_modal_produk" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col">
              <div class="card">
                <div class="card-header">Produk</div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col">
                      <label class="form-label mb-0 mt-2" for="update_produk_id">Kode Produk:</label>
                      <input class="form-control" type="text" id="update_produk_id" name="update_produk_id" value="" disabled>
                      <label class="form-label mb-0 mt-2" for="update_name">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <input class="form-control" type="text" id="update_name_produk" name="update_name_produk" value="">
                      <label class="form-label mb-0 mt-2" for="update_kategori">Kategori:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <select class="form-select" id="update_kategori"></select>
                      <label class="form-label mb-0 mt-2" for="update_brand">Brand:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <select class="form-select" id="update_brand"></select>
                    </div>
                    <div class="col">
                      <label class="form-label mb-0 mt-2" for="update_no_sku">No SKU:</label>
                      <input class="form-control" type="text" id="update_no_sku" name="update_no_sku" aria-describedby="basic-addon1" value="">
                      <label class="form-label mb-0 mt-2" for="update_status_produk">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                      <select class="form-select" id="update_status_produk">
                        <option value="aktif">Aktif</option>
                        <option value="non aktif">Non Aktif</option>
                      </select>
                      <label class="form-label mb-0 mt-2" for="update_harga_minimal">Harga Minimal:</label>
                      <input class="form-control" type="text" id="update_harga_minimal" name="update_harga_minimal" aria-describedby="basic-addon1" value="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <div class="card-header">Pricelist</div>
                <div class="card-body">
                  <table class="table  table-hover table-bordered table-sm" id="update_detail_pricelist_produk">
                    <thead id="update_detail_pricelist_produk_thead">
                      <tr>
                        <th scope="col">
                          Pricelist
                        </th>
                        <th scope="col">
                          Harga
                        </th>
                        <th scope="col">
                          Aksi
                        </th>
                      </tr>
                    </thead>
                    <tbody id="update_detail_pricelist_produk_tbody">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="update_submit_produk" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
      <h3>Data Produk</h3>
      <div id="table_produk"></div>
    </div>
  </main>