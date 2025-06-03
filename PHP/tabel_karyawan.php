  <!-- Modal_karyawan -->
  <div class="modal fade" id="modal_karyawan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label" for="name">Nama:</label>
          <input class="form-control" type="text" id="name_karyawan" name="name_karyawan" value=""><br>
          <label class="form-label" for="role_select">Role:</label>
          <select class ="form-select" id="role_select">
          </select><br><br>
          <label class="form-label" for="divisi">Divisi:</label>
          <input class="form-control" type="text" id="divisi_karyawan" name="divisi_karyawan" value=""><br>
          <label class="form-label" for="phone">Nomor Telepon:</label>
          <div class = "input-group mb-3">
          <span class="input-group-text" id="basic-addon1">+62</span>
          <input class="form-control" type="text" id="phone_karyawan" name="phone_karyawan" aria-describedby="basic-addon1"value="">
          </div><br>
          <label class="form-label" for="address">Alamat:</label>
          <input class="form-control" type="text" id="address_karyawan" name="address_karyawan" value=""><br>
          <label class="form-label" for="nik">NIK:</label>
          <input class="form-control" type="text" id="nik_karyawan" name="nik_karyawan" value=""><br>
          <label class="form-label" for="npwp_karyawan">NPWP:</label>
          <input class="form-control" type="text" id="npwp_karyawan" name="npwp_karyawan" value=""><br>
          <label class="form-label" for="status_karyawan">Status:</label>
          <select class="form-select" id="status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
          <label class="form-label" for="update_karyawan_ID">Kode Karyawan:</label>
          <input class="form-control" type="text" id="update_karyawan_ID" disabled><br>
          <label class="form-label" for="update_name_karyawan">Name:</label>
          <input class="form-control" type="text" id="update_name_karyawan" name="name_karyawan_update"><br>
          <label class="form-label" for="update_role_select">Role:</label>
          <select class="form-select" id="update_role_select">
            <option value="">Pilih Role</option>
          </select><br><br>
          <label class="form-label" for="update_divisi_karyawan">Divisi:</label>
          <input class="form-control" type="text" id="update_divisi_karyawan" name="divisi_karyawan_update"><br>

          <label class="form-label" for="phone">Nomor Telepon:</label>
          <div class = "input-group mb-3">
          <span class="input-group-text" id="basic-addon1">+62</span>
          <input class="form-control" type="text" id="update_phone_karyawan" name="phone_karyawan_update" aria-describedby="basic-addon1"value="">
          </div><br>
          <label class="form-label" for="update_address_karyawan">Alamat:</label>
          <input class="form-control" type="text" id="update_address_karyawan" name="address_karyawan_update"><br>
          <label class="form-label" for="update_nik_karyawan">NIK:</label>
          <input class="form-control" type="text" id="update_nik_karyawan" name="nik_karyawan_update"><br>
          <label class="form-label" for="update_npwp_karyawan">NPWP:</label>
          <input class="form-control" type="text" id="update_npwp_karyawan" name="npwp_karyawan" value=""><br>
          <label class="form-label" for="status_karyawan">Status:</label> <br>
          <select class="form-select" id="update_status_karyawan">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select><br><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button id="submit_karyawan_update" type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
     </div>
  </div>

<main class="col-12 col-lg-10 ms-auto px-1" >
      <div id="main" class="table-responsive" >
              <h3>Tabel Karyawan</h3>
          <div id ="table_karyawan"></div>
      </div>
      </div>
    </div>
</main>