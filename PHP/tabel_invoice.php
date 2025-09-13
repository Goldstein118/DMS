<div class="modal fade" id="modal_invoice" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenterTitle">Pembelian</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="card mt-3">
                    <div class="card-header">
                        Tambah Pembelian
                    </div>
                    <div class="card-body">
                        <label class="form-label mb-0 mt-2" for="purchase_order">Purchase Order:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <select class="form-select" id="purchase_order">
                        </select>

                        <label class="form-label mb-0 mt-2" for="tanggal_invoice">Tanggal Invoice:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="tanggal_invoice" name="tanggal_invoice" />

                        <label class="form-label mb-0 mt-2" for="no_invoice">No Incvoice Supplier:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="no_invoice" name="no_invoice" />
                    </div>
                </div>



                <div class="card mt-3" style="display: none;" id="purchase_order_card">
                    <div class="card-header">Update Purchase Order</div>
                    <div class="card-body" id="update_pembelian_card_body">

                        <!-- Informasi Dasar -->
                        <div class="accordion mb-3" id="update_accordion_informasi_dasar_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseInformasiDasar_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseInformasiDasar_pembelian">
                                        Informasi Dasar
                                    </button>
                                </h2>
                                <div id="collapseInformasiDasar_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_informasi_dasar_pembelian">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <input class="form-control d-none" type="text" id="update_pembelian_id" name="update_pembelian_id" value="" disabled>
                                                <label class="form-label mb-0 mt-2" for="update_tanggal_po">Tanggal PO:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input class="form-control" type="text" id="update_tanggal_po" name="update_tanggal_po" value="">

                                                <label class="form-label mb-0 mt-2" for="update_tanggal_pengiriman">Tanggal Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <input type="text" class="form-control" id="update_tanggal_pengiriman" name="update_tanggal_pengiriman" />

                                                <label class="form-label mb-0 mt-2" for="update_no_pengiriman">No Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <input type="text" class="form-control" id="update_no_pengiriman" name="update_no_pengiriman" />

                                            </div>
                                            <div class="col">

                                                <label class="form-label mb-0 mt-2" for="update_tanggal_terima">Tanggal Terima:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <input type="text" class="form-control" id="update_tanggal_terima" name="update_tanggal_terima" />

                                                <label class="form-label mb-0 mt-2" for="update_status_pembelian">Status:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_status_pembelian" disabled>
                                                    <option value="proses">Proses Purchase Order</option>
                                                    <option value="pengiriman">Pengiriman Barang</option>
                                                    <option value="terima">Penerimaan Barang</option>
                                                    <option value="invoice">Invoice</option>
                                                </select>

                                                <label class="form-label mb-0 mt-2" for="update_supplier_id">Supplier:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_supplier_id"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Barang -->
                        <div class="accordion mb-3" id="update_accordion_detail_barang_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseDetailBarang_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseDetailBarang_pembelian">
                                        Informasi Detail Barang
                                    </button>
                                </h2>
                                <div id="collapseDetailBarang_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_detail_barang_pembelian">
                                    <div class="accordion-body" id="update_detail_pembelian_card_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="update_detail_pembelian_button">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table table-hover table-bordered table-sm" id="create_detail_pembelian">
                                            <thead id="create_detail_pembelian_thead">
                                                <tr>
                                                    <th scope="col">Produk</th>
                                                    <th scope="col">Kuantitas</th>
                                                    <th scope="col">Satuan</th>
                                                    <th scope="col">Harga</th>
                                                    <th>Diskon</th>
                                                    <th id="create_aksi_thead">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="create_detail_pembelian_tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pajak -->
                        <div class="accordion mb-3" id="update_accordion_pajak_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapsePajak_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapsePajak_pembelian">
                                        Informasi Pajak
                                    </button>
                                </h2>
                                <div id="collapsePajak_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_pajak_pembelian">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="update_ppn">PPN:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_ppn">
                                                    <option value="0.11">11%</option>
                                                    <option value="0.10">10%</option>

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
                        <div class="accordion mb-3" id="update_accordion_tambahan_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseTambahan_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseTambahan_pembelian">
                                        Informasi Tambahan
                                    </button>
                                </h2>
                                <div id="collapseTambahan_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_tambahan_pembelian">
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
                        <div class="accordion mb-3" id="update_accordion_biaya_tambahan_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseBiayaTambahan_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseBiayaTambahan_pembelian">
                                        Informasi Biaya Tambahan
                                    </button>
                                </h2>
                                <div id="collapseBiayaTambahan_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#update_accordion_biaya_tambahan_pembelian">
                                    <div class="accordion-body" id="update_biaya_tambahan_accordion_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="update_biaya_tambahan_button">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table table-hover table-bordered table-sm" id="create_biaya_tambahan">
                                            <thead id="create_biaya_tambahan_thead">
                                                <tr>
                                                    <th scope="col">Biaya</th>
                                                    <th scope="col">Jumlah</th>
                                                    <th scope="col">Keterangan</th>
                                                    <th id="create_aksi_thead">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="create_biaya_tambahan_tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button id="submit_invoice_button" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="update_modal_invoice" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalCenterTitle">Pembelian</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="card mt-3">
                    <div class="card-header">
                        Tambah Pembelian
                    </div>
                    <div class="card-body">
                        <input class="form-control d-none" type="text" id="update_invoice_id" name="update_invoice_id" value="" disabled>
                        <label class="form-label mb-0 mt-2" for="update_purchase_order">Purchase Order:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input class="form-control" id="update_purchase_order" disabled />

                        <label class="form-label mb-0 mt-2" for="update_tanggal_invoice">Tanggal Invoice:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="update_tanggal_invoice" />

                        <label class="form-label mb-0 mt-2" for="update_no_invoice">No Invoice Supplier:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="update_no_invoice" />
                    </div>
                </div>





                <div class="card mt-3" id="update_purchase_order_card">
                    <div class="card-header">Update Purchase Order</div>
                    <div class="card-body" id="pembelian_card_body">

                        <!-- Informasi Dasar -->
                        <div class="accordion mb-3" id="accordion_informasi_dasar_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseInformasiDasar_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseInformasiDasar_pembelian">
                                        Informasi Dasar
                                    </button>
                                </h2>
                                <div id="collapseInformasiDasar_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_informasi_dasar_pembelian">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="tanggal_po">Tanggal PO:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input class="form-control" type="text" id="tanggal_po" name="tanggal_po" value="">

                                                <label class="form-label mb-0 mt-2" for="tanggal_pengiriman">Tanggal Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <input type="text" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" />

                                                <label class="form-label mb-0 mt-2" for="no_pengiriman">No Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <input type="text" class="form-control" id="no_pengiriman" name="no_pengiriman" />

                                            </div>
                                            <div class="col">


                                                <label class="form-label mb-0 mt-2" for="tanggal_terima">Tanggal Terima:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <input type="text" class="form-control" id="tanggal_terima" name="tanggal_terima" />

                                                <label class="form-label mb-0 mt-2" for="status_pembelian">Status:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="status_pembelian" disabled>
                                                    <option value="proses">Proses Purchase Order</option>
                                                    <option value="pengiriman">Pengiriman Barang</option>
                                                    <option value="terima">Penerimaan Barang</option>
                                                    <option value="invoice">Invoice</option>
                                                </select>

                                                <label class="form-label mb-0 mt-2" for="supplier_id">Supplier:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="supplier_id"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Barang -->
                        <div class="accordion mb-3" id="accordion_detail_barang_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseDetailBarang_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseDetailBarang_pembelian">
                                        Informasi Detail Barang
                                    </button>
                                </h2>
                                <div id="collapseDetailBarang_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_detail_barang_pembelian">
                                    <div class="accordion-body" id="detail_pembelian_card_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="detail_pembelian_button">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table table-hover table-bordered table-sm" id="update_detail_pembelian">
                                            <thead id="update_detail_pembelian_thead">
                                                <tr>
                                                    <th scope="col">Produk</th>
                                                    <th scope="col">Kuantitas</th>
                                                    <th scope="col">Satuan</th>
                                                    <th scope="col">Harga</th>
                                                    <th>Diskon</th>
                                                    <th id="update_aksi_thead">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="update_detail_pembelian_tbody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pajak -->
                        <div class="accordion mb-3" id="accordion_pajak_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapsePajak_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapsePajak_pembelian">
                                        Informasi Pajak
                                    </button>
                                </h2>
                                <div id="collapsePajak_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_pajak_pembelian">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="ppn">PPN:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="ppn">
                                                    <option value="0.11">11%</option>
                                                    <option value="0.10">10%</option>

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
                        <div class="accordion mb-3" id="accordion_tambahan_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseTambahan_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseTambahan_pembelian">
                                        Informasi Tambahan
                                    </button>
                                </h2>
                                <div id="collapseTambahan_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_tambahan_pembelian">
                                    <div class="accordion-body">
                                        <div class="row g-2">
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="keterangan">Keterangan:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="keterangan" name="keterangan" />
                                            </div>
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="diskon">Diskon:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="diskon" name="diskon" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Biaya Tambahan -->
                        <div class="accordion mb-3" id="accordion_biaya_tambahan_pembelian">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseBiayaTambahan_pembelian"
                                        aria-expanded="true"
                                        aria-controls="collapseBiayaTambahan_pembelian">
                                        Informasi Biaya Tambahan
                                    </button>
                                </h2>
                                <div id="collapseBiayaTambahan_pembelian"
                                    class="accordion-collapse collapse show"
                                    data-bs-parent="#accordion_biaya_tambahan_pembelian">
                                    <div class="accordion-body" id="biaya_tambahan_accordion_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="biaya_tambahan_button">
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
                <button id="update_invoice_button" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
        <h3> Data Pembelian</h3>
        <div id="table_invoice"></div>
    </div>
</main>