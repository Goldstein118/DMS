<!-- Modal -->
<div class="modal fade" id="modal_gudang"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Gudang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <label class="form-label mb-0 mt-2" for="nama_gudang">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <input class="form-control" type="text" id="nama_gudang" name="nama_gudang" value="">
         <label class="form-label mb-0 mt-2">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <select class ="form-select" id ="gudang_status">
          <option value = "aktif">Aktif</option>
          <option value = "non aktif">Non Aktif</option>
        </select>
      </div>
      <div class="modal-footer">
        <button  id="submit_gudang" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_gudang_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Gudang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label mb-0 mt-2" for="update_gudang_id">Kode Gudang:</label>
        <input class="form-control" type="text" id="update_gudang_id" disabled>
        <label class="form-label mb-0 mt-2" for="update_nama_gudang">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <input class="form-control" type="text" id="update_nama_gudang" name="update_nama_gudang" value="">
        <label class="form-label mb-0 mt-2">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <select class ="form-select" id ="update_gudang_status">
          <option value = "aktif">Aktif</option>
          <option value = "non aktif">Non Aktif</option>
        </select>
      </div>
      <div class="modal-footer">
        <button  id="submit_gudang_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1" >
  <div id="main"  class="table-responsive">
    <h3>Data Gudang</h3>
        <div id ="table_gudang"></div>
  </div>
</main>