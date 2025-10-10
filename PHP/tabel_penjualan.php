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
                                                    <option value="belumlunas">Belum Lunas</option>
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
                        <div id="list-container"></div>

                        <div class="card" id="cek_promo_card">
                            <div class="card-header">Promo Bonus Barang</div>
                            <div class="card-body" id="cek_promo_card_body">

                                <table id="table_bonus_barang" class="table table-hover table-bordered table-sm ">
                                    <thead id="table_bonus_barang_thead">
                                        <tr>
                                            <th style="max-width: 9px; text-align:center">No</th>
                                            <th>Promo</th>
                                            <th>Produk</th>
                                            <th>Jumlah Qty</th>
                                            <th>Jenis Diskon</th>
                                            <th>Jumlah Diskon/Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_bonus_barang_tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="submit_penjualan" type="button" class="btn btn-primary">Submit</button>
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
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="card">
                    <div class="card-header">Penjualan</div>
                    <div class="card-body" id="update_penjualan_card_body">

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
                                                <label class="form-label mb-0 mt-2" for="update_tanggal_penjualan">Tanggal Penjualan:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input class="form-control" type="text" id="update_tanggal_penjualan" name="update_tanggal_penjualan" value="">
                                                <label class="form-label mb-0 mt-2" for="update_status_penjualan">Status:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_status_penjualan" disabled>
                                                    <option value="belumlunas">Belum Lunas</option>
                                                    <option value="lunas">Lunas</option>
                                                </select>

                                            </div>
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="update_customer_id">Customer:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_customer_id"></select>
                                                <label class="form-label mb-0 mt-2" for="update_gudang_id">Gudang:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_gudang_id"></select>
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
                                    <div class="accordion-body" id="update_detail_penjualan_card_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="update_detail_penjualan_button">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table table-hover table-bordered table-sm" id="update_detail_penjualan">
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
                                                <label class="form-label mb-0 mt-2" for="update_ppn">PPN:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <select class="form-select" id="update_ppn">
                                                    <option value="0.11">11%</option>
                                                    <option value="0.1">10%</option>

                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="update_nominal_pph">Nominal PPH:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="update_nominal_pph" name="update_nominal_pph" />
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
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="update_keterangan_penjualan">Keterangan:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="update_keterangan_penjualan" name="update_keterangan_penjualan" />
                                            </div>
                                            <div class="col"> <label class="form-label mb-0 mt-2" for="update_diskon">Diskon:<i class="bi bi-asterisk text-danger align-middle"></i></label>
                                                <input type="text" class="form-control" id="update_diskon" name="diskon" />
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>



                        <button class="btn btn-primary btn-sm mb-2" id="update_cek_promo_button">
                            <i class="bi bi-eyeglasses"></i> Cek Promo
                        </button>
                        <div id="list-container"></div>

                        <div class="card" id="update_cek_promo_card">
                            <div class="card-header">Promo Bonus Barang</div>
                            <div class="card-body" id="update_cek_promo_card_body">

                                <table id="update_table_bonus_barang" class="table table-hover table-bordered table-sm ">
                                    <thead id="update_table_bonus_barang_thead">
                                        <tr>
                                            <th style="max-width: 9px; text-align:center">No</th>
                                            <th>Promo</th>
                                            <th>Produk</th>
                                            <th>Jumlah Qty</th>
                                            <th>Jenis Diskon</th>
                                            <th>Jumlah Diskon/Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="update_table_bonus_barang_tbody">

                                    </tbody>
                                </table>
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