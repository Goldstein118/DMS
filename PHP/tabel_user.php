<!-- MODAL-->
<div class="modal fade" id="modal_user"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="karyawan_ID">Kode Karyawan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <select class="form-select" id="karyawan_ID"></select> 
        <label class="form-label" for="level">Level:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <select class="form-select" id="level">
          <option value="user">User</option>
        </select>
      </div>
      <div class="modal-footer">
        <button  id="submit_user" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal update -->
<div class="modal fade" id="modal_user_update"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="update_user_ID">Kode User:</label>
        <input class="form-control" type="text" id="update_user_ID" disabled>
        <label class="form-label" for="update_karyawan_ID">Kode Karyawan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <select class="form-select" id="update_karyawan_ID">
          <option value=""></option>
        </select> 
        <label class="form-label" for="update_level">Level:<i class="bi bi-asterisk text-danger align-middle "></i></label>
        <select class="form-select" id="update_level">
          <option value="user">User</option>
        </select>
      </div>
      <div class="modal-footer">
        <button  id="submit_user_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>
<main class="col-12 col-lg-10 ms-auto px-1" >
  <div id="main"  class="table-responsive">
        <h3>Data User</h3>
        <div id ="table_user"></div>
  </div>
</main>