<!-- modal_customer -->
<div class="modal fade" id="modal_customer" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_customer" enctype="multipart/form-data">
          <label class="form-label mb-0 mt-2" for="name">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="name_customer" name="name_customer" value="">
          <label class="form-label mb-0 mt-2" for="address">Alamat:</label>
          <input class="form-control" type="text" id="alamat_customer" name="alamat_customer" value="">
          <label class="form-label mb-0 mt-2" for="phone">Nomor Telepon:</label>
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1">+62</span>
            <input class="form-control" type="text" id="no_telp_customer" name="no_telp_customer" aria-describedby="basic-addon1" value="">
          </div>
          <label class="form-label mb-0 mt-2" for="nik">NIK:</label>
          <input class="form-control" type="text" id="nik_customer" name="nik_customer" value="">
          <label class="form-label mb-0 mt-2" for="npwp_customer">NPWP:</label>
          <input class="form-control" type="text" id="npwp_customer" name="npwp_customer" value="">
          <label class="form-label mb-0 mt-2" for="status_customer">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class="form-select" id="status_customer">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select>
          <label class="form-label mb-0 mt-2" for="nitko">Nitko:</label>
          <input class="form-control" type="text" id="nitko" name="nitko" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="term_payment">Term Payment (Hari):</label>
          <input class="form-control" type="number" id="term_payment" name="term_payment" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="max_invoice">Max Invoice:</label>
          <input class="form-control" type="number" id="max_invoice" name="max_invoice" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="max_piutang">Max Piutang:</label>
          <input class="form-control" type="text" id="max_piutang" name="max_piutang" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="latitude">Latidude:</label>
          <input class="form-control" type="text" id="latitude" name="latitude" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="longitude">Longitude:</label>
          <input class="form-control" type="text" id="longitude" name="longitude" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="channel_id">Kode Channel:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class="form-select" id="channel_id">
          </select>
          <label class="form-label mb-0 mt-2" for="pricelist_id">Kode Pricelist:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select  class="form-select" id="pricelist_id">
          </select>
          <div>
            <label for="ktp_image" class="form-label mb-0 mt-2">Upload Gambar KTP:</label>
            <input type="file" class="form-control" id="ktp_image" name="ktp_image" accept=".jpg,.jpeg,.png">
            <div id="ktp_link" class="form-text text-muted"></div>
          </div>
          <div>
            <label for="npwp_image" class="form-label mb-0 mt-2">Upload Gambar NPWP</label>
            <input type="file" class="form-control" id="npwp_image" name="npwp_image" accept=".jpg,.jpeg,.png">
            <div id="npwp_link" class="form-text text-muted"></div>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button id="submit_customer" type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- modal_customer_update-->
<div class="modal fade" id="modal_customer_update" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body">
        <form id="form_customer" enctype="multipart/form-data">
          <label class="form-label mb-0 mt-2" for="update_customer_id">Kode Customer:</label>
          <input class="form-control" type="text" id="update_customer_id" disabled>
          <label class="form-label mb-0 mt-2" for="update_name_customer">Name:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <input class="form-control" type="text" id="update_name_customer" name="name_customer_update">
          <label class="form-label mb-0 mt-2" for="update_address_customer">Alamat:</label>
          <input class="form-control" type="text" id="update_address_customer" name="address_customer_update">
          <label class="form-label mb-0 mt-2" for="update_phone_customer">Nomor Telepon:</label>
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1">+62</span>
            <input class="form-control" type="text" id="update_phone_customer" name="phone_customer_update" aria-describedby="basic-addon1" value="">
          </div>
          <label class="form-label mb-0 mt-2" for="update_nik_customer">NIK:</label>
          <input class="form-control" type="text" id="update_nik_customer" name="nik_customer_update">
          <label class="form-label mb-0 mt-2" for="update_npwp_customer">NPWP:</label>
          <input class="form-control" type="text" id="update_npwp_customer" name="npwp_customer" value="">
          <label class="form-label mb-0 mt-2" for="update_status_customer">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class="form-select" id="update_status_customer">
            <option value="aktif">Aktif</option>
            <option value="non aktif">Non Aktif</option>
          </select>
          <label class="form-label mb-0 mt-2" for="update_nitko">Nitko:</label>
          <input class="form-control" type="text" id="update_nitko" name="update_nitko" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="update_term_payment">Term Payment (Hari):</label>
          <input class="form-control" type="number" id="update_term_payment" name="update_term_payment" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="update_max_invoice">Max Invoice:</label>
          <input class="form-control" type="number" id="update_max_invoice" name="update_max_invoice" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="update_max_piutang">Max Piutang:</label>
          <input class="form-control" type="text" id="update_max_piutang" name="update_max_piutang" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="update_latitude">Latidude:</label>
          <input class="form-control" type="text" id="update_latitude" name="update_latitude" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="update_longitude">Longitude:</label>
          <input class="form-control" type="text" id="update_longitude" name="update_longitude" aria-describedby="basic-addon1" value="">
          <label class="form-label mb-0 mt-2" for="update_channel_id">Kode Channel:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select class="form-select" id="update_channel_id">
          </select>
          <label class="form-label mb-0 mt-2" for="update_pricelist_id">Kode Pricelist:<i class="bi bi-asterisk text-danger align-middle "></i></label>
          <select  class="form-select" id="update_pricelist_id">
          </select>
          <div>
            <label for="update_ktp_image" class="form-label mb-0 mt-2">Upload Gambar KTP:</label>
            <div class="d-flex align-items-center gap-2">
              <input type="file" class="form-control" id="update_ktp_image" name="ktp_image" accept=".jpg,.jpeg,.png">
              <button type="button" class="btn btn-sm btn-outline-danger" id="clear_ktp">Hapus</button>
            </div>
            <div id="update_ktp_link" class="form-text text-muted"></div>
          </div>

          <div>
            <label for="update_npwp_image" class="form-label mb-0 mt-2">Upload Gambar NPWP:</label>
            <div class="d-flex align-items-center gap-2">
              <input type="file" class="form-control" id="update_npwp_image" name="npwp_image" accept=".jpg,.jpeg,.png">
              <button type="button" class="btn btn-sm btn-outline-danger" id="clear_npwp">Hapus</button>
            </div>
            <div id="update_npwp_link" class="form-text text-muted"></div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button id="submit_customer_update" type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1">
  <div id="main" class="table-responsive">
    <h3>Data Customer</h3>
    <div id="table_customer"></div>
  </div>
</main>