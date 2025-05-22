<!-- Modal -->
<div class="modal fade" id="modal_user_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit user</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="update_user_ID">Kode User:</label>
        <input class="form-control" type="text" id="update_user_ID" disabled><br><br>
        <label class="form-label" for="update_karyawan_ID">Kode Karyawan:</label>
        <select class="form-label" id="update_karyawan_ID">
            <option value=""></option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button  id="submit_user_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>
<main class="col-12 col-lg-10 ms-auto px-3" >
  <div id="main"  class="table-responsive">
        <button type="button" id ="logout" class="btn btn-outline-danger"> <i class="bi bi-box-arrow-in-right"></i> Logout</button>
        <div id ="table_user"></div>
  </div>
</main>