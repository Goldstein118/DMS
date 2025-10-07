<!-- Modal -->
<div class="modal fade" id="modal_kategori"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Kategori</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label mb-0 mt-2" for="nama_kategori">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <input class="form-control" type="text" id="nama_kategori" name="nama_kategori" value="">
      </div>
      <div class="modal-footer">
        <button  id="submit_kategori" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_kategori_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Kategori</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label mb-0 mt-2" for="update_kategori_id">Kode Kategori:</label>
        <input class="form-control" type="text" id="update_kategori_id" disabled>
        <label class="form-label mb-0 mt-2" for="update_nama_kategori">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <input class="form-control" type="text" id="update_nama_kategori" name="update_nama_kategori" value="">
      </div>
      <div class="modal-footer">
        <button  id="submit_kategori_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1" >
  <div id="main"  class="table-responsive">
    <h3>Data Kategori</h3>
        <div id ="table_kategori"></div>
  </div>
</main>