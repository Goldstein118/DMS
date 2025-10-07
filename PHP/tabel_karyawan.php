  <!-- Modal_karyawan -->
  <div class="modal fade" id="modal_karyawan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <label class="form-label mb-0 mt-2" for="name_karyawan">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="name_karyawan" name="name_karyawan" value="">
          <label class="form-label mb-0 mt-2" for="role_select">Role:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class="form-select" id="role_select">
          </select>
          <label class="form-label mb-0 mt-2" for="divisi_karyawan">Departement:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class ="form-select" id="divisi_karyawan">
          <option value="sales">Sales</option>
          <option value="admin">Admin</option>
          <option value="gudang">Gudang</option>
          <option value="finance">Finance</option>
          <option value="lainnya">Lainnya</option>
          </select>
          <label class="form-label mb-0 mt-2" for="phone_karyawan">Nomor Telepon:</label>
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1">+62</span>
            <input class="form-control" type="text" id="phone_karyawan" name="phone_karyawan" aria-describedby="basic-addon1" value="">
          </div>
          <label class="form-label mb-0 mt-2" for="address_karyawan">Alamat:</label>
          <input class="form-control" type="text" id="address_karyawan" name="address_karyawan" value="">
          <label class="form-label mb-0 mt-2" for="nik_karyawan">NIK:</label>
          <input class="form-control" type="text" id="nik_karyawan" name="nik_karyawan" value="">
          <label class="form-label mb-0 mt-2" for="npwp_karyawan">NPWP:</label>
          <input class="form-control" type="text" id="npwp_karyawan" name="npwp_karyawan" value="">
          <label class="form-label mb-0 mt-2" for="status_karyawan">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class="form-select" id="status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select>
        </div>
        <div class="modal-footer">
          <button id="submit_karyawan" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal_karyawan_update-->
  <div class="modal fade" id="modal_karyawan_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Edit Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>
        <div class="modal-body">
          <label class="form-label mb-0 mt-2" for="update_karyawan_ID">Kode Karyawan:</label>
          <input class="form-control" type="text" id="update_karyawan_ID" disabled>
          <label class="form-label mb-0 mt-2" for="update_name_karyawan">Name:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="update_name_karyawan" name="name_karyawan_update">
          <label class="form-label mb-0 mt-2" for="update_role_select">Role:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class="form-select" id="update_role_select">
            <option value="">Pilih Role</option>
          </select>
          <label class="form-label mb-0 mt-2" for="update_divisi_karyawan">Departement:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class ="form-select" id="update_divisi_karyawan">
          <option value="sales">Sales</option>
          <option value="admin">Admin</option>
          <option value="gudang">Gudang</option>
          <option value="finance">Finance</option>
          <option value="lainnya">Lainnya</option>
          </select>
          
          <label class="form-label mb-0 mt-2" for="update_phone_karyawan">Nomor Telepon:</label>
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1">+62</span>
            <input class="form-control" type="text" id="update_phone_karyawan" name="update_phone_karyawan" aria-describedby="basic-addon1" value="">
          </div>
          <label class="form-label mb-0 mt-2" for="update_address_karyawan">Alamat:</label>
          <input class="form-control" type="text" id="update_address_karyawan" name="update_address_karyawan">
          <label class="form-label mb-0 mt-2" for="update_nik_karyawan">NIK:</label>
          <input class="form-control" type="text" id="update_nik_karyawan" name="update_nik_karyawan">
          <label class="form-label mb-0 mt-2" for="update_npwp_karyawan">NPWP:</label>
          <input class="form-control" type="text" id="update_npwp_karyawan" name="update_npwp_karyawan" value="">
          <label class="form-label mb-0 mt-2" for="update_status_karyawan">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label> 
          <select class="form-select" id="update_status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select>
        </div>
        <div class="modal-footer">
          <button id="submit_karyawan_update" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
      <h3>Data Karyawan</h3>
      <div id="table_karyawan"></div>
    </div>
    </div>
    </div>
  </main>