    <!-- Modal_role-->
  <div class="modal fade" id="modal_role" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
          <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="name_role">Nama:</label>
                    <input class="form-control" type="text" id="name_role" name="name_role"><br><br>
                    <div class="accordion" id="accordion_main">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading_main">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse_main" aria-expanded="false" aria-controls="collapse_main">
                                    Data
                                </button>
                            </h2>
                            <div id="collapse_main" class="accordion-collapse collapse"
                                data-bs-parent="#accordion_main">
                                <div class="accordion-body">
                                  <div class="accordion accordion_anak" id="accordion_karyawan" >
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_karyawan">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_karyawan" aria-expanded="false" aria-controls="collapse_karyawan">
                                        Table Karyawan
                                      </button>
                                    </h2>
                                    <div id="collapse_karyawan" class="accordion-collapse collapse" data-bs-parent="#accordion_karyawan">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_karyawan"  autocomplete="off">
                                        <label class="form-check-label" for="check_all_karyawan">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_karyawan" >
                                        <label class="form-check-label" for="check_view_karyawan">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_karyawan" >
                                        <label class="form-check-label" for="check_create_karyawan">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_karyawan" >
                                        <label class="form-check-label" for="check_edit_karyawan">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_karyawan" >
                                        <label class="form-check-label" for="check_delete_karyawan">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                  <div class="accordion accordion_anak" id="accordion_user">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_user">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_user" aria-expanded="false" aria-controls="collapse_user">
                                        Table User
                                      </button>
                                    </h2>
                                    <div id="collapse_user" class="accordion-collapse collapse" data-bs-parent="#accordion_user">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_user" autocomplete="off">
                                        <label class="form-check-label" for="check_all_user">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_user">
                                        <label class="form-check-label" for="check_view_user">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_user">
                                        <label class="form-check-label" for="check_create_user">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_user">
                                        <label class="form-check-label" for="check_edit_user">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_user">
                                        <label class="form-check-label" for="check_delete_user">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>

                                  <div class="accordion accordion_anak" id="accordion_role">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_role">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_role" aria-expanded="false" aria-controls="collapse_role">
                                        Table Role
                                      </button>
                                    </h2>
                                    <div id="collapse_role" class="accordion-collapse collapse" data-bs-parent="#accordion_role">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_role" autocomplete="off">
                                        <label class="form-check-label" for="check_all_role">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_role">
                                        <label class="form-check-label" for="check_view_role">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_role">
                                        <label class="form-check-label" for="check_create_role">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_role">
                                        <label class="form-check-label" for="check_edit_role">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_role">
                                        <label class="form-check-label" for="check_delete_role">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>

                                  <div class="accordion accordion_anak" id="accordion_supplier">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_supplier">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_supplier" aria-expanded="false" aria-controls="collapse_supplier">
                                        Table Supplier
                                      </button>
                                    </h2>
                                    <div id="collapse_supplier" class="accordion-collapse collapse" data-bs-parent="#accordion_supplier">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_supplier" autocomplete="off">
                                        <label class="form-check-label" for="check_all_supplier">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_supplier">
                                        <label class="form-check-label" for="check_view_supplier">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_supplier">
                                        <label class="form-check-label" for="check_create_supplier">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_supplier">
                                        <label class="form-check-label" for="check_edit_supplier">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_supplier">
                                        <label class="form-check-label" for="check_delete_supplier">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                  <div class="accordion accordion_anak" id="accordion_customer">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_customer">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_customer" aria-expanded="false" aria-controls="collapse_customer">
                                        Table Customer
                                      </button>
                                    </h2>
                                    <div id="collapse_customer" class="accordion-collapse collapse" data-bs-parent="#accordion_customer">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_customer" autocomplete="off">
                                        <label class="form-check-label" for="check_all_customer">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_customer">
                                        <label class="form-check-label" for="check_view_customer">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_customer">
                                        <label class="form-check-label" for="check_create_customer">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_customer">
                                        <label class="form-check-label" for="check_edit_customer">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_customer">
                                        <label class="form-check-label" for="check_delete_customer">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>

                                  <div class="accordion accordion_anak" id="accordion_channel">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_channel">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_channel" aria-expanded="false" aria-controls="collapse_channel">
                                        Table Channel
                                      </button>
                                    </h2>
                                    <div id="collapse_channel" class="accordion-collapse collapse" data-bs-parent="#accordion_channel">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_channel" autocomplete="off">
                                        <label class="form-check-label" for="check_all_channel">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_channel">
                                        <label class="form-check-label" for="check_view_channel">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_channel">
                                        <label class="form-check-label" for="check_create_channel">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_channel">
                                        <label class="form-check-label" for="check_edit_channel">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_channel">
                                        <label class="form-check-label" for="check_delete_channel">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button id="submit_role" type="button" class="btn btn-primary">Submit</button>
                </div>
          </div>
      </div>
  </div>


    <!-- Modal_role_update-->
    <div class="modal fade" id="modal_role_update" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Kode Role :</label>
                    <input class="form-control" type="text" id="update_role_ID" disabled><br><br>
                    <label class="form-label">Nama:</label>
                    <input class="form-control" type="text" id="update_role_name"><br><br>

                    <div class="accordion" id="accordion_main_update">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading_main">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse_main" aria-expanded="false" aria-controls="collapse_main">
                                    Data
                                </button>
                            </h2>
                            <div id="collapse_main" class="accordion-collapse collapse"
                                data-bs-parent="#accordion_main">
                                <div class="accordion-body">
                                  <div class="accordion accordion_anak" id="accordion_karyawan_update" >
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_karyawan_update">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_karyawan_update" aria-expanded="false" aria-controls="collapse_karyawan_update">
                                        Table Karyawan
                                      </button>
                                    </h2>
                                    <div id="collapse_karyawan_update" class="accordion-collapse collapse" data-bs-parent="#accordion_karyawan_update">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_karyawan_update" autocomplete="off">
                                        <label class="form-check-label" for="check_all_karyawan_update">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_karyawan_update">
                                        <label class="form-check-label" for="check_view_karyawan_update">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_karyawan_update">
                                        <label class="form-check-label" for="check_create_karyawan_update">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_karyawan_update">
                                        <label class="form-check-label" for="check_edit_karyawan_update">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_karyawan_update">
                                        <label class="form-check-label" for="check_delete_karyawan_update">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                  <div class="accordion accordion_anak" id="accordion_user_update">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_user_update">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_user_update" aria-expanded="false" aria-controls="collapse_user_update">
                                        Table User
                                      </button>
                                    </h2>
                                    <div id="collapse_user_update" class="accordion-collapse collapse" data-bs-parent="#accordion_user_update">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_user_update" autocomplete="off">
                                        <label class="form-check-label" for="check_all_user_update">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_user_update">
                                        <label class="form-check-label" for="check_view_user_update">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_user_update">
                                        <label class="form-check-label" for="check_create_user_update">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_user_update">
                                        <label class="form-check-label" for="check_edit_user_update">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_user_update">
                                        <label class="form-check-label" for="check_delete_user_update">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>

                                  <div class="accordion accordion_anak" id="accordion_role_update">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_role_update">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_role_update" aria-expanded="false" aria-controls="collapse_role_update">
                                        Table Role
                                      </button>
                                    </h2>
                                    <div id="collapse_role_update" class="accordion-collapse collapse" data-bs-parent="#accordion_role_update">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_role_update" autocomplete="off">
                                        <label class="form-check-label" for="check_all_role_update">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_role_update">
                                        <label class="form-check-label" for="check_view_role_update">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_role_update">
                                        <label class="form-check-label" for="check_create_role_update">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_role_update">
                                        <label class="form-check-label" for="check_edit_role_update">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_role_update">
                                        <label class="form-check-label" for="check_delete_role_update">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>

                                  <div class="accordion accordion_anak" id="accordion_supplier_update">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_supplier_update">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_supplier_update" aria-expanded="false" aria-controls="collapse_supplier_update">
                                        Table Supplier
                                      </button>
                                    </h2>
                                    <div id="collapse_supplier_update" class="accordion-collapse collapse" data-bs-parent="#accordion_supplier_update">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_supplier_update" autocomplete="off">
                                        <label class="form-check-label" for="check_all_supplier_update">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_supplier_update">
                                        <label class="form-check-label" for="check_view_supplier_update">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_supplier_update">
                                        <label class="form-check-label" for="check_create_supplier_update">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_supplier_update">
                                        <label class="form-check-label" for="check_edit_supplier_update">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_supplier_update">
                                        <label class="form-check-label" for="check_delete_supplier_update">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                  <div class="accordion accordion_anak" id="accordion_customer_update">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_customer_update">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_customer_update" aria-expanded="false" aria-controls="collapse_customer_update">
                                        Table Customer
                                      </button>
                                    </h2>
                                    <div id="collapse_customer_update" class="accordion-collapse collapse" data-bs-parent="#accordion_customer_update">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_customer_update" autocomplete="off">
                                        <label class="form-check-label" for="check_all_customer_update">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_customer_update">
                                        <label class="form-check-label" for="check_view_customer_update">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_customer_update">
                                        <label class="form-check-label" for="check_create_customer_update">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_customer_update">
                                        <label class="form-check-label" for="check_edit_customer_update">Edit</label>
                                      </div>
                                      <div class="form-check mb-2"> 
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_customer_update">
                                        <label class="form-check-label" for="check_delete_customer_update">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>

                                  <div class="accordion accordion_anak" id="accordion_channel_update">
                                  <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_channel_update">
                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_channel_update" aria-expanded="false" aria-controls="collapse_channel_update">
                                        Table Channel
                                      </button>
                                    </h2>
                                    <div id="collapse_channel_update" class="accordion-collapse collapse" data-bs-parent="#accordion_channel_update">
                                      <div class="accordion-body">
                                        <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="check_all_channel_update" autocomplete="off">
                                        <label class="form-check-label" for="check_all_channel_update">Select All</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_view_channel_update">
                                        <label class="form-check-label" for="check_view_channel_update">View</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_create_channel_update">
                                        <label class="form-check-label" for="check_create_channel_update">Create</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_edit_channel_update">
                                        <label class="form-check-label" for="check_edit_channel_update">Edit</label>
                                      </div>
                                      <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox" type="checkbox" id="check_delete_channel_update">
                                        <label class="form-check-label" for="check_delete_channel_update">Delete</label>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button id="submit_role_update" type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <main class="col-12 col-lg-10 ms-auto px-1">
        <!-- Main content -->
        <div id="main" class="table-responsive">
          <h3>Tabel Role</h3>
            <div id="table_role"></div>
        </div>
    </main>