<!-- Modal -->
<div class="modal fade" id="modal_brand"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Brand</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="nama_brand">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <input class="form-control" type="text" id="nama_brand" name="nama_brand" value=""><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button  id="submit_brand" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_brand_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Brand</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="update_brand_id">Kode Brand:</label>
        <input class="form-control" type="text" id="update_brand_id" disabled><br><br>
        <label class="form-label" for="update_nama_brand">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <input class="form-control" type="text" id="update_nama_brand" name="update_nama_brand" value=""><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button  id="submit_brand_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1" >
  <div id="main"  class="table-responsive">
    <h3>Tabel Brand</h3>
        <div id ="table_brand"></div>
  </div>
</main>