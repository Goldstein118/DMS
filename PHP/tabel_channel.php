<!-- Modal -->
<div class="modal fade" id="modal_channel"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Channel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="nama_channel">Nama:</label>
         <input class="form-control" type="text" id="nama_channel" name="nama_channel" value=""><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button  id="submit_channel" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_channel_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Channel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="update_channel_id">Kode Channel:</label>
        <input class="form-control" type="text" id="update_channel_id" disabled><br><br>
        <label class="form-label" for="update_nama_channel">Nama:</label>
        <input class="form-control" type="text" id="update_nama_channel" name="update_nama_channel" value=""><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button  id="submit_channel_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1" >
  <div id="main"  class="table-responsive">
    <h3>Tabel Channel</h3>
        <div id ="table_channel"></div>
  </div>
</main>