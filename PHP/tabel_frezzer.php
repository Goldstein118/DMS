<!-- Modal -->
<div class="modal fade" id="modal_frezzer"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Frezzer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <label class="form-label mb-0 mt-2" for="kode_barcode">Kode Barcode:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <input class="form-control" type="text" id="kode_barcode" name="kode_barcode" value="">
         <label class="form-label mb-0 mt-2" for="tipe">Tipe:</label>
         <input class="form-control" type="text" id="tipe" name="tipe" value="">
         <label class="form-label mb-0 mt-2">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <select class ="form-select" id ="frezzer_status">
          <option value = "ready">Ready</option>
          <option value = "dipakai">Dipakai</option>
          <option value="proses claim">Proses Claim</option>
          <option value="rusak">Rusak</option>
        </select>

        <label class="form-label mb-0 mt-2" for="merek">Merek:</label>
        <input class="form-control" type="text" id="merek" name="merek" value="">
        <label class="form-label mb-0 mt-2" for="size">Size:</label>
        <input class="form-control" type="text" id="size" name="size" value="">
      </div>
      <div class="modal-footer">
        <button  id="submit_frezzer" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_frezzer_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Frezzer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label mb-0 mt-2" for="update_frezzer_id">Kode Frezzer:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <input class="form-control" type="text" id="update_frezzer_id" name="update_frezzer_id" value="">
         <label class="form-label mb-0 mt-2" for="update_kode_barcode">Kode Barcode:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <input class="form-control" type="text" id="update_kode_barcode" name="update_kode_barcode" value="">
         <label class="form-label mb-0 mt-2" for="update_tipe">Tipe:</label>
         <input class="form-control" type="text" id="update_tipe" name="update_tipe" value="">
         <label class="form-label mb-0 mt-2">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
         <select class ="form-select" id ="update_frezzer_status">
          <option value = "ready">Ready</option>
          <option value = "dipakai">Dipakai</option>
          <option value="proses claim">Proses Claim</option>
          <option value="rusak">Rusak</option>
        </select>

        <label class="form-label mb-0 mt-2" for="update_merek">Merek:</label>
        <input class="form-control" type="text" id="update_merek" name="update_merek" value="">
        <label class="form-label mb-0 mt-2" for="update_size">Size:</label>
        <input class="form-control" type="text" id="update_size" name="update_size" value="">
      </div>
      <div class="modal-footer">
        <button  id="submit_frezzer_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1" >
  <div id="main"  class="table-responsive">
    <h3>Data Frezzer</h3>
        <div id ="table_frezzer"></div>
  </div>
</main>