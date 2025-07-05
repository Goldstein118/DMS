<!-- Modal -->
<div class="modal fade" id="modal_promo" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="col">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Promo</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col">
                                        <label class="form-label" for="nama_promo">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input class="form-control" type="text" id="nama_promo" name="nama_promo" value="">
                                        <label class="form-label mb-0 mt-2" for="tanggal_berlaku">Tanggal Berlaku:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="tanggal_berlaku" name="tanggal_berlaku" />
                                        <label class="form-label mb-0 mt-2" for="tanggal_selesai">Tanggal Selesai:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="tanggal_selesai" name="tanggal_selesai" />
                                        <label class="form-label mb-0 mt-2" for="jenis_bonus">Jenis Bonus:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="jenis_bonus">
                                            <option value="barang">Barang</option>
                                            <option value="nominal">Nominal</option>
                                        </select>
                                        <label class="form-label mb-0 mt-2" for="akumulasi">Akumulasi:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="akumulasi">
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="prioritas">Prioritas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="prioritas" name="prioritas" />

                                        <label class="form-label mb-0 mt-2" for="jenis_diskon">Jenis Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="jenis_diskon">
                                            <option value="nominal">Nominal</option>
                                            <option value="persen">Persen</option>
                                        </select>
                                        <label class="form-label mb-0 mt-2" for="jumlah_diskon">Jumlah Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="jumlah_diskon" name="jumlah_diskon" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Promo Kondisi</div>
                            <div class="card-body">

                                <label class="form-label mb-0 mt-2" for="jenis_brand">Jenis Brand:</label>
                                <select class="js-example-basic-multiple form-select" id="jenis_brand" name="brand[]" multiple="multiple">
                                </select>
                                <label class="form-label mb-0 mt-2" for="jenis_customer">Jenis Customer:</label>

                                <select class="js-example-basic-multiple form-select" id="jenis_customer" name="customer[]" multiple="multiple"></select>

                                <label class="form-label mb-0 mt-2" for="jenis_produk">Jenis Produk:</label>

                                <select class="js-example-basic-multiple form-select" id="jenis_produk" name="produk[]" multiple="multiple"></select>
                                <div class="row g-2">
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="status_promo">Status:</label>
                                        <select class="form-select" id="status_promo">
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Non Aktif</option>
                                        </select>

                                        <label class="form-label mb-0 mt-2" for="qty_akumulasi">Kuantitas Akumulasi:</label>
                                        <input class="form-control" type="number" id="qty_akumulasi">

                                        <label class="form-label mb-0 mt-2" for="qty_min">Kuantitas Minimal:</label>
                                        <input class="form-control" type="number" id="qty_min">
                                    </div>
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="qty_max">Kuantitas Maksimum:</label>
                                        <input class="form-control" type="number" id="qty_max">

                                        <label class="form-label mb-0 mt-2" for="quota">Quota:</label>
                                        <input class="form-control" type="number" id="quota">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="card_promo_3">
                        <div class="card-header">Promo_3</div>
                        <div class="card-body">
                            <label class="form-label mb-0 mt-2" for="qty_bonus">Kuantitas Bonus:</label>
                            <input class="form-control" id="qty_bonus" type="number">
                            <label class="form-label mb-0 mt-2" for="diskon_bonus_barang">Jumlah Diskon:</label>
                            <input class="form-control" id="diskon_bonus_barang">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="submit_promo" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_promo_update" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Promo</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col">
                                        <label class="form-label" for="update_nama_promo">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input class="form-control" type="text" id="update_nama_promo" name="update_nama_promo" value="">
                                        <label class="form-label mb-0 mt-2" for="update_tanggal_berlaku">Tanggal Berlaku:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="update_tanggal_berlaku" name="update_tanggal_berlaku" />
                                        <label class="form-label mb-0 mt-2" for="update_tanggal_selesai">Tanggal Selesai:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="update_tanggal_selesai" name="update_tanggal_selesai" />
                                        <label class="form-label mb-0 mt-2" for="update_jenis_bonus">Jenis Bonus:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="update_jenis_bonus">
                                            <option value="barang">Barang</option>
                                            <option value="nominal">Nominal</option>
                                        </select>
                                        <label class="form-label mb-0 mt-2" for="update_akumulasi">Akumulasi:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="update_akumulasi">
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="update_prioritas">Prioritas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="update_prioritas" name="prioritas" />

                                        <label class="form-label mb-0 mt-2" for="update_jenis_diskon">Jenis Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="update_jenis_diskon">
                                            <option value="nominal">Nominal</option>
                                            <option value="persen">Persen</option>
                                        </select>
                                        <label class="form-label mb-0 mt-2" for="update_jumlah_diskon">Jumlah Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="update_jumlah_diskon" name="update_jumlah_diskon" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Promo Kondisi</div>
                            <div class="card-body">

                                <label class="form-label mb-0 mt-2" for="update_jenis_brand">Jenis Brand:</label>
                                <select class="js-example-basic-multiple form-select" id="update_jenis_brand" name="brand[]" multiple="multiple">
                                </select>
                                <label class="form-label mb-0 mt-2" for="update_jenis_customer">Jenis Customer:</label>

                                <select class="js-example-basic-multiple form-select" id="update_jenis_customer" name="customer[]" multiple="multiple"></select>

                                <label class="form-label mb-0 mt-2" for="update_jenis_produk">Jenis Produk:</label>

                                <select class="js-example-basic-multiple form-select" id="update_jenis_produk" name="produk[]" multiple="multiple"></select>
                                <div class="row g-2">
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="update_status_promo">Status:</label>
                                        <select class="form-select" id="update_status_promo">
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Non Aktif</option>
                                        </select>

                                        <label class="form-label mb-0 mt-2" for="update_qty_akumulasi">Kuantitas Akumulasi:</label>
                                        <input class="form-control" type="number" id="update_qty_akumulasi">

                                        <label class="form-label mb-0 mt-2" for="update_qty_min">Kuantitas Minimal:</label>
                                        <input class="form-control" type="number" id="update_qty_min">
                                    </div>
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="update_qty_max">Kuantitas Maksimum:</label>
                                        <input class="form-control" type="number" id="update_qty_max">

                                        <label class="form-label mb-0 mt-2" for="update_quota">Quota:</label>
                                        <input class="form-control" type="number" id="update_quota">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="card_promo_3">
                        <div class="card-header">Promo_3</div>
                        <div class="card-body">
                            <label class="form-label mb-0 mt-2" for="update_qty_bonus">Kuantitas Bonus:</label>
                            <input class="form-control" id="update_qty_bonus" type="number">
                            <label class="form-label mb-0 mt-2" for="update_diskon_bonus_barang">Jumlah Diskon:</label>
                            <input class="form-control" id="update_diskon_bonus_barang">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="submit_promo_update" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
        <h3>Data Promo</h3>
        <div id="table_promo"></div>

    </div>
</main>