<!-- modal_penjualan -->
<div class="modal fade" id="modal_penjualan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="card">
                    <div class="card-header">Penjualan</div>
                    <div class="card-body" id="penjualan_card_body">

                        <!-- Informasi Dasar -->
                        <div class="accordion mb-3" id="accordion_informasi_dasar_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseInformasiDasar_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapseInformasiDasar_penjualan">
                                        Informasi Dasar
                                    </button>
                                </h2>
                                <div id="collapseInformasiDasar_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_informasi_dasar_penjualan">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="tanggal_penjualan">Tanggal Penjualan:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input class="form-control" type="text" id="tanggal_penjualan" name="tanggal_penjualan" value="">
                                                <label class="form-label mb-0 mt-2" for="status_penjualan">Status:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="status_penjualan" disabled>
                                                    <option value="proses">Proses PO</option>
                                                </select>

                                            </div>
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="customer_id">Customer:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="customer_id"></select>
                                                <label class="form-label mb-0 mt-2" for="gudang_id">Gudang:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="gudang_id"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Barang -->
                        <div class="accordion mb-3" id="accordion_detail_barang_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseDetailBarang_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapseDetailBarang_penjualan">
                                        Informasi Detail Barang
                                    </button>
                                </h2>
                                <div id="collapseDetailBarang_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_detail_barang_penjualan">
                                    <div class="accordion-body" id="detail_penjualan_card_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="create_detail_penjualan_button">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table table-hover table-bordered table-sm" id="detail_penjualan">
                                            <thead id="detail_penjualan_thead">
                                                <tr>
                                                    <th scope="col">Produk</th>
                                                    <th scope="col">Kuantitas</th>
                                                    <th scope="col">Satuan</th>
                                                    <th scope="col">Harga</th>
                                                    <th>Diskon</th>
                                                    <th id="aksi_thead">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="create_detail_penjualan_tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pajak -->
                        <div class="accordion mb-3" id="accordion_pajak_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapsePajak_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapsePajak_penjualan">
                                        Informasi Pajak
                                    </button>
                                </h2>
                                <div id="collapsePajak_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_pajak_penjualan">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="ppn">PPN:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="ppn">
                                                    <option value="0.11">11%</option>
                                                    <option value="0.1">10%</option>

                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="nominal_pph">Nominal PPH:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="nominal_pph" name="nominal_pph" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="accordion mb-3" id="accordion_tambahan_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseTambahan_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapseTambahan_penjualan">
                                        Informasi Tambahan
                                    </button>
                                </h2>
                                <div id="collapseTambahan_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_tambahan_penjualan">
                                    <div class="accordion-body">

                                        <div class="row g-2">
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="keterangan_penjualan">Keterangan:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="keterangan_penjualan" name="keterangan_penjualan" />
                                            </div>
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="diskon">Diskon:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="diskon" name="diskon" />
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>



                        <button class="btn btn-primary btn-sm mb-2" id="cek_promo_button">
                            <i class="bi bi-eyeglasses"></i> Cek Promo
                        </button>
                        <div id="cek_promo"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="submit_penjualan" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="modal_pengiriman" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenterTitle">Pengiriman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="text" class="form-control d-none" id="pengiriman_penjualan_id" />

                <label class="form-label mb-0 mt-2" for="tanggal_pengiriman">Tanggal Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" />

                <label class="form-label mb-0 mt-2" for="no_pengiriman">No Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="no_pengiriman" name="no_pengiriman" />
            </div>
            <div class="modal-footer">
                <button id="submit_pengiriman_button" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_terima" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog   modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenterTitle">Penerimaan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <input type="text" class="form-control" id="terima_penjualan_id" />

                <label class="form-label mb-0 mt-2" for="tanggal_terima">Tanggal Terima:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="tanggal_terima" name="tanggal_terima" />
            </div>
            <div class="modal-footer">
                <button id="submit_terima_button" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal_cancel" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenterTitle">Cancel</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <label class="form-label mb-0 mt-2" for="keterangan_cancel">Keterangan Cancel:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="keterangan_cancel" name="keterangan_cancel" />

            </div>
            <div class="modal-footer">
                <button id="submit_cancel_button" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>



<!--update_modal_penjualan -->
<div class="modal fade" id="update_modal_penjualan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="card">
                    <div class="card-header">Update Purchase Order</div>
                    <div class="card-body" id="update_penjualan_card_body">

                        <!-- Informasi Dasar -->
                        <div class="accordion mb-3" id="update_accordion_informasi_dasar_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseInformasiDasar_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapseInformasiDasar_penjualan">
                                        Informasi Dasar
                                    </button>
                                </h2>
                                <div id="collapseInformasiDasar_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_informasi_dasar_penjualan">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="update_tanggal_po">Tanggal PO:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input class="form-control" type="text" id="update_tanggal_po" name="update_tanggal_po" value="">
                                                <label class="form-label mb-0 mt-2" for="update_status_penjualan">Status:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_status_penjualan" disabled>
                                                    <option value="proses">Proses Purchase Order</option>
                                                    <option value="pengiriman">Pengiriman Barang</option>
                                                    <option value="terima">Penerimaan Barang</option>
                                                    <option value="invoice">Invoice</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <!-- <label class="form-label mb-0 mt-2" for="update_penjualan_id">update_penjualan_id :<i class="bi bi-asterisk text-danger align-middle"></i></label> -->
                                                <input class="form-control d-none" type="text" id="update_penjualan_id" name="update_penjualan_id" value="" disabled>
                                                <label class="form-label mb-0 mt-2" for="update_supplier_id">Supplier:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_supplier_id"></select>
                                                <label class="form-label mb-0 mt-2" for="update_gudang_id">Gudang:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_gudang_id"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Barang -->
                        <div class="accordion mb-3" id="update_accordion_detail_barang_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseUpdateDetailBarang_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapseUpdateDetailBarang_penjualan">
                                        Informasi Detail Barang
                                    </button>
                                </h2>
                                <div id="collapseUpdateDetailBarang_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_detail_barang_penjualan">
                                    <div class="accordion-body" id="update_detail_penjualan_card_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="update_detail_penjualan_button">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table table-hover table-bordered table-sm" id="update_detail_penjualan_table">
                                            <thead id="update_detail_penjualan_thead">
                                                <tr>
                                                    <th scope="col">Produk</th>
                                                    <th scope="col">Kuantitas</th>
                                                    <th scope="col">Satuan</th>
                                                    <th scope="col">Harga</th>
                                                    <th>Diskon</th>
                                                    <th id="update_aksi_thead">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="update_detail_penjualan_tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pajak -->
                        <div class="accordion mb-3" id="update_accordion_pajak_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapsePajak_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapsePajak_penjualan">
                                        Informasi Pajak
                                    </button>
                                </h2>
                                <div id="collapsePajak_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_pajak_penjualan">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="update_ppn">PPN:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_ppn">
                                                    <option value="0.11">11%</option>
                                                    <option value="0.1">10%</option>

                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="update_nominal_pph">Nominal PPH:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="update_nominal_pph" name="nominal_pph" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="accordion mb-3" id="update_accordion_tambahan_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseTambahan_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapseTambahan_penjualan">
                                        Informasi Tambahan
                                    </button>
                                </h2>
                                <div id="collapseTambahan_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_tambahan_penjualan">
                                    <div class="accordion-body">
                                        <div class="row g-2">
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="update_keterangan">Keterangan:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="update_keterangan" name="update_keterangan" />
                                            </div>
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="update_diskon">Diskon:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="update_diskon" name="update_diskon" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Biaya Tambahan -->
                        <div class="accordion mb-3" id="update_accordion_biaya_tambahan_penjualan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseBiayaTambahan_penjualan"
                                        aria-expanded="true"
                                        aria-controls="collapseBiayaTambahan_penjualan">
                                        Informasi Biaya Tambahan
                                    </button>
                                </h2>
                                <div id="collapseBiayaTambahan_penjualan"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_biaya_tambahan_penjualan">
                                    <div class="accordion-body" id="update_biaya_tambahan_accordion_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="update_biaya_tambahan_button">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table table-hover table-bordered table-sm" id="update_biaya_tambahan">
                                            <thead id="update_biaya_tambahan_thead">
                                                <tr>
                                                    <th scope="col">Biaya</th>
                                                    <th scope="col">Jumlah</th>
                                                    <th scope="col">Keterangan</th>
                                                    <th id="update_aksi_thead">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="update_biaya_tambahan_tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="update_submit_penjualan" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>







<main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
        <h3>Data Penjualan</h3>
        <div id="table_penjualan"></div>
    </div>
</main>