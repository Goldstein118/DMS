  <!-- Modal_divisi-->
  <div class="modal fade" id="modal_divisi"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Divisi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <label class="form-label">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="divisi_nama" name="divisi_nama" value="">
          <label class="form-label">Nama Bank:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="nama_bank">
          <label class="form-label">Nama Rekening:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="nama_rekening">
          <label class="form-label">Nomor Rekening:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="nomor_rekening">
        </div>
        <div class="modal-footer">
          <button id="submit_divisi" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
    <!-- Modal_divisi_update-->
  <div class="modal fade" id="modal_divisi_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Divisi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class = "form-label">Kode Divisi:</label>
          <input class="form-control" type ="text" id = "update_divisi_id" disabled>
          <label class="form-label">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="update_divisi_nama">
          <label class="form-label">Nama Bank:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="update_nama_bank">
          <label class="form-label">Nama Rekening:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="update_nama_rekening">
          <label class="form-label">Nomor Rekening:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="update_nomor_rekening">
        </div>
        <div class="modal-footer">
          <button id="submit_divisi_update" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
<main class="col-12 col-lg-10 ms-auto px-1">
      <!-- Main content -->
      <div id="main"  class="table-responsive">
        <h3>Data Divisi</h3>
          <div id="table_divisi"></div>
      </div>
  </main>