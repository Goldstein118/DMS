  <!-- Modal_role-->
  <div class="modal fade" id="modal_role"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label" for="name_role">Nama:</label>
          <input class="form-control" type="text" id="name_role" name="name_role"><br><br>
          <label class="form-label" for="akses">Akses</label>
          <input class="form-control" type="text" id="akses_role" name="akses"><br><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_role" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal_role_update-->
  <div class="modal fade" id="modal_role_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label" >Kode Role :</label>
          <input class="form-control" type="text" id="update_role_ID" disabled><br><br>
          <label class="form-label">Nama:</label>
          <input class="form-control" type="text" id="update_role_name"><br><br>
          <label class="form-label">Akses</label>
          <input class="form-control" type="text" id="update_role_akses"><br><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_role_update" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
<main class="col-12 col-lg-10 ms-auto px-3">
      <!-- Main content -->
      <div id="main"  class="table-responsive">
        <button type="button" id ="logout" class="btn btn-outline-danger"> <i class="bi bi-box-arrow-in-right"></i> Logout</button>
          <div id="table_role"></div>
      </div>
  </main>