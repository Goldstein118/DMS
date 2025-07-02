<!-- Modal -->
<div class="modal fade" id="modal_armada"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Armada</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="nama_armada">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <input class="form-control" type="text" id="nama_armada" name="nama_armada" value="">
         <label class="form-label mb-0 mt-2" for="karyawan_select">Karyawan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <select class="form-select" id="karyawan_select"></select>
      </div>
      <div class="modal-footer">
        <button  id="submit_armada" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_armada_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Armada</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="update_armada_id">Kode Armada:</label>
        <input class="form-control" type="text" id="update_armada_id" disabled>
        <label class="form-label" for="update_nama_armada">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <input class="form-control" type="text" id="update_nama_armada" name="update_nama_armada" value="">
        <label class="form-label mb-0 mt-2" for="update_karyawan_select">Karyawan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <select class="form-select" id="update_karyawan_select"></select>
      </div>
      <div class="modal-footer">
        <button  id="submit_armada_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1" >
  <div id="main"  class="table-responsive">
    <h3>Data Armada</h3>
        <div id ="table_armada"></div>
  </div>
</main>