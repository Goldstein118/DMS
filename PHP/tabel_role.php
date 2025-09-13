    <!-- Modal_role-->
    <div
      class="modal fade"
      id="modal_role"
      role="dialog"
      aria-labelledby="exampleModalCenterTitle"
      aria-hidden="true">
      <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Role</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label class="form-label mb-0 mt-2" for="name_role">Nama:<i class="bi bi-asterisk text-danger align-middle"></i></label>
            <input class="form-control" type="text" id="name_role" name="name_role" />
            <label class="form-label mb-0 mt-2" for="name_role">Data Akses:<i class="bi bi-asterisk text-danger align-middle "></i></label>
            <div class="accordion" id="accordion_main">
              <div class="accordion-item">
                <h2 class="accordion-header" id="heading_main">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse_main"
                    aria-expanded="false"
                    aria-controls="collapse_main">
                    Data
                  </button>
                </h2>
                <div
                  id="collapse_main"
                  class="accordion-collapse collapse"
                  data-bs-parent="#accordion_main">
                  <div class="accordion-body">
                    <div class="permission-group">
                      <p class="label-check-box">Data Karyawan</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="check_all_karyawan" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_karyawan" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_karyawan" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_karyawan" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_karyawan" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data User</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_user" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_user" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_user" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_user" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_user" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Role</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_role" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_role" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_role" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_role" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_role" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>
                    <div class="permission-group">
                      <p class="label-check-box">Data Supplier</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_supplier" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_supplier" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_supplier" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_supplier" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_supplier" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Customer</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_customer" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_customer" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_customer" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_customer" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_customer" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>
                    <div class="permission-group">
                      <p class="label-check-box">Data Channel</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_channel" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_channel" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_channel" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_channel" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_channel" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Kategori</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_kategori" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_kategori" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_kategori" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_kategori" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_kategori" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Brand</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_brand" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_brand" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_brand" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_brand" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_brand" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Produk</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_produk" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_produk" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_produk" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_produk" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_produk" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Divisi</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_divisi" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_divisi" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_divisi" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_divisi" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_divisi" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Gudang</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_gudang" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_gudang" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_gudang" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_gudang" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_gudang" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Pricelist</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_pricelist" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_pricelist" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_pricelist" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_pricelist" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_pricelist" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Armada</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_armada" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_armada" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_armada" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_armada" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_armada" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Frezzer</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_frezzer" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_frezzer" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_frezzer" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_frezzer" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_frezzer" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Promo</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_promo" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_promo" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_promo" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_promo" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_promo" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Satuan</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_satuan" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_satuan" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_satuan" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_satuan" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_satuan" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Pembelian</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_pembelian" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_pembelian" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_pembelian" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_pembelian" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_pembelian" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data biaya</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_data_biaya" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_data_biaya" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_data_biaya" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_data_biaya" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_data_biaya" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>


                    <div class="permission-group">
                      <p class="label-check-box">Data invoice</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_invoice" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_invoice" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_invoice" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_invoice" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_invoice" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>


                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button id="submit_role" type="button" class="btn btn-primary">
              Submit
            </button>
          </div>
        </div>
      </div>
    </div>



    <!-- Modal_role_update-->
    <div
      class="modal fade"
      id="modal_role_update"
      role="dialog"
      aria-labelledby="exampleModalCenterTitle"
      aria-hidden="true">
      <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Edit Role</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label class="form-label mb-0 mt-2">Kode Role:</label>
            <input
              class="form-control"
              type="text"
              id="update_role_ID"
              disabled />
            <label class="form-label mb-0 mt-2">Nama:<i class="bi bi-asterisk text-danger align-middle"></i></label>
            <input
              class="form-control"
              type="text"
              id="update_role_name" />
            <label class="form-label mb-0 mt-2" for="name_role">Data Akses:<i class="bi bi-asterisk text-danger align-middle "></i></label>
            <div class="accordion" id="accordion_main_update">
              <div class="accordion-item">
                <h2 class="accordion-header" id="heading_main">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse_main"
                    aria-expanded="false"
                    aria-controls="collapse_main">
                    Data
                  </button>
                </h2>
                <div
                  id="collapse_main"
                  class="accordion-collapse collapse"
                  data-bs-parent="#accordion_main">
                  <div class="accordion-body">
                    <div class="permission-group">
                      <p class="label-check-box">Data Karyawan</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_karyawan_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_karyawan_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_karyawan_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_karyawan_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_karyawan_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data User</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_user_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_user_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_user_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_user_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_user_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Role</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_role_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_role_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_role_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_role_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_role_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Supplier</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_supplier_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_supplier_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_supplier_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_supplier_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_supplier_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Customer</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_customer_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_customer_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_customer_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_customer_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_customer_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Channel</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_channel_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_channel_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_channel_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_channel_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_channel_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Kategori</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="check_all_kategori_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_kategori_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_kategori_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_kategori_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_kategori_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Brand</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_brand_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_brand_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_brand_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_brand_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_brand_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Produk</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_produk_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_produk_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_produk_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_produk_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_produk_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Divisi</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="check_all_divisi_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_divisi_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_divisi_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_divisi_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_divisi_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Gudang</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="check_all_gudang_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_gudang_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_gudang_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_gudang_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_gudang_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Pricelist</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="check_all_pricelist_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_pricelist_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_pricelist_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_pricelist_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_pricelist_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Armada</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_armada_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_armada_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_armada_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_armada_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_armada_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Frezzer</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_frezzer_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_frezzer_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_frezzer_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_frezzer_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_frezzer_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Promo</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_promo_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_promo_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_promo_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_promo_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_promo_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>


                    <div class="permission-group">
                      <p class="label-check-box">Data Satuan</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_satuan_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_satuan_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_satuan_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_satuan_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_satuan_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data Pembelian</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_pembelian_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_pembelian_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_pembelian_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_pembelian_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_pembelian_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data biaya</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_data_biaya_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_data_biaya_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_data_biaya_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_data_biaya_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_data_biaya_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>

                    <div class="permission-group">
                      <p class="label-check-box">Data invoice</p>

                      <div class="permission-row">
                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input "
                            type="checkbox"
                            id="check_all_invoice_update" />
                          Select All
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_view_invoice_update" />
                          View
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_create_invoice_update" />
                          Create
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_edit_invoice_update" />
                          Edit
                        </label>

                        <label class="form-check form-check-inline">
                          <input
                            class="form-check-input perm-checkbox"
                            type="checkbox"
                            id="check_delete_invoice_update" />
                          Delete
                        </label>
                      </div>
                      <hr />
                    </div>


                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button id="submit_role_update" type="button" class="btn btn-primary">
              Simpan
            </button>
          </div>
        </div>
      </div>
    </div>

    <main class="col-12 col-lg-10 ms-auto px-1">
      <!-- Main content -->
      <div id="main" class="table-responsive">
        <h3>Data Role</h3>
        <div id="table_role"></div>
      </div>
    </main>