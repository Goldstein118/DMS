<!-- modal_pricelist -->
<div class="modal fade" id="modal_pricelist" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Pricelist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col">
                <label class="form-label mb-0 mt-2" for="name_pricelist">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input class="form-control" type="text" id="name_pricelist" name="name_pricelist" value="">

                <label class="form-label mb-0 mt-2" for="default_pricelist">Harga Default:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <select class="form-select" id="default_pricelist">
                    <option value="ya">Ya</option>
                    <option value="tidak">Tidak</option>
                </select>

                <label class="form-label mb-0 mt-2" for="tanggal_berlaku">Tanggal Berlaku:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="date" class="form-control" id="tanggal_berlaku" />

                <label class="form-label mb-0 mt-2" for="status_pricelist">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <select class="form-select" id="status_pricelist">
                    <option value="aktif">Aktif</option>
                    <option value="non aktif">Non Aktif</option>
                </select>
                </div>
                
                <div class="col">
                <label class="form-label mb-0 mt-2">Detail Pricelist<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Detail
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <table class="table  table-hover table-bordered table-sm" id="detail_pricelist">
                                <thead id ="detail_pricelist_thead">
                                    <tr>
                                        <th scope="col">
                                            Kode
                                        </th>
                                        <th scope="col">
                                            Pricelist
                                        </th>
                                        <th scope="col">
                                            Produk
                                        </th>
                                        <th scope="col">
                                            Harga
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="detail_pricelist_tbody">
                                <tr>
                                    <td scope="row">
                                    </td>
                                    <td>
                                        <select id="pricelist_select">
                                        </select>
                                    </td>
                                    <td>
                                        <select id ="produk_select">
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control">
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="submit_pricelist" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="update_modal_pricelist" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Pricelist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                <div class="col">
                
                <label class="form-label mb-0 mt-2" for="update_pricelist_id">Kode Pricelist</label>
                <input class="form-control"type="text" id="update_pricelist_id" disabled>

                <label class="form-label mb-0 mt-2" for="update_name_pricelist">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input class="form-control" type="text" id="update_name_pricelist" name="update_name_pricelist" value="">

                <label class="form-label mb-0 mt-2" for="update_default_pricelist">Harga Default:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <select class="form-select" id="update_default_pricelist">
                    <option value="ya">Ya</option>
                    <option value="tidak">Tidak</option>
                </select>

                <label class="form-label mb-0 mt-2" for="update_tanggal_berlaku">Tanggal Berlaku:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="date" class="form-control" id="update_tanggal_berlaku" />

                <label class="form-label mb-0 mt-2" for="update_status_pricelist">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <select class="form-select" id="update_status_pricelist">
                    <option value="aktif">Aktif</option>
                    <option value="non aktif">Non Aktif</option>
                </select>
                    </div>
                    <div class="col">
                <label class="form-label mb-0 mt-2">Detail Pricelist<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Detail
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <table class="table table-hover table-bordered table-sm" id ="update_detail_pricelist">
                                <thead id="update_details_pricelist_thead">
                                    <tr>
                                        <th scope="col">
                                            Kode
                                        </th>
                                        <th scope="col">
                                            Pricelist
                                        </th>
                                        <th scope="col">
                                            Produk
                                        </th>
                                        <th scope="col">
                                            Harga
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="update_details_pricelist_tbody">
                                <tr>
                                    <td scope="row">
                                    </td>
                                    <td>
                                        <select id="update_pricelist_select">
                                        </select>
                                    </td>
                                    <td>
                                        <select id ="update_produk_select">
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control">
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="update_submit_pricelist" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1">
      <div id="main" class="table-responsive" >
        <h3>Data Pricelist</h3>
        <div id ="table_pricelist"></div>
      </div>
</main>