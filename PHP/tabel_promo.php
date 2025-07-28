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
                                        <label class="form-label mb-0 mt-2" for="quota">Quota:</label>
                                        <input class="form-control" type="number" min="0" id="quota">
                                        <label class="form-label mb-0 mt-2" for="status_promo">Status:</label>
                                        <select class="form-select" id="status_promo">
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Non Aktif</option>
                                        </select>

                                        <label class="form-label mb-0 mt-2" for="satuan_id">Satuan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="satuan_id">
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="jenis_bonus">Jenis Bonus:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="jenis_bonus">
                                            <option value="barang">Barang</option>
                                            <option value="nominal">Nominal</option>
                                        </select>

                                        <div id="toggle_jenis_bonus" style="display: none;">
                                            <label class="form-label mb-0 mt-2" for="jenis_diskon">Jenis Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                            <select class="form-select" id="jenis_diskon">
                                                <option value="nominal">Nominal</option>
                                                <option value="persen">Persen</option>
                                            </select>
                                            <label class="form-label mb-0 mt-2" for="jumlah_diskon">Jumlah Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                            <input type="text" class="form-control" id="jumlah_diskon" name="jumlah_diskon" />
                                        </div>


                                        <label class="form-label mb-0 mt-2" for="akumulasi">Akumulasi:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="akumulasi">
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                        <label class="form-label mb-0 mt-2" for="prioritas">Prioritas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="prioritas" name="prioritas" />


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Promo Kondisi</div>
                            <div class="card-body" id="promo_kondisi_card_body">
                                <button class="btn btn-primary btn-sm mb-2" id="promo_kondisi_button">
                                    <i class="bi bi-plus-circle"></i> Tambah
                                </button>

                                <table
                                    class="table table-hover table-bordered table-sm "
                                    id="jenis_promo_kondisi">
                                    <thead id="jenis_promo_kondisi_thead">
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Kriteria</th>
                                            <th>Kondisi</th>
                                            <th>Qty Min</th>
                                            <th>Qty Max</th>
                                            <th>Qty Akumulasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jenis_promo_kondisi_tbody">
                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div>
                    <div class="card" id="card_promo_3">
                        <div class="card-header">Promo Bonus Barang</div>
                        <div class="card-body" id="promo_bonus_card_body">
                            <button class="btn btn-primary btn-sm mb-2" id="promo_bonus_barang_button">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </button>
                            <table id="table_bonus_barang" class="table table-hover table-bordered table-sm ">
                                <thead id="table_bonus_barang_thead">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jumlah Qty</th>
                                        <th>Jenis Diskon</th>
                                        <th>Jumlah Diskon/Nominal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="table_bonus_barang_tbody">

                                </tbody>
                            </table>
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Update Promo</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col">

                                        <label class="form-label" for="update_promo_id">Kode Promo:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input class="form-control" type="text" id="update_promo_id" name="update_promo_id" value="" disabled>
                                        <label class="form-label" for="update_nama_promo">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input class="form-control" type="text" id="update_nama_promo" name="update_nama_promo" value="">
                                        <label class="form-label mb-0 mt-2" for="update_tanggal_berlaku">Tanggal Berlaku:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="update_tanggal_berlaku" name="update_tanggal_berlaku" />
                                        <label class="form-label mb-0 mt-2" for="update_tanggal_selesai">Tanggal Selesai:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="update_tanggal_selesai" name="update_tanggal_selesai" />
                                        <label class="form-label mb-0 mt-2" for="update_quota">Quota:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input class="form-control" type="number" min="0" id="update_quota">
                                        <label class="form-label mb-0 mt-2" for="update_status_promo">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="update_status_promo">
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Non Aktif</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="form-label mb-0 mt-2" for="update_jenis_bonus">Jenis Bonus:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="update_jenis_bonus">
                                            <option value="barang">Barang</option>
                                            <option value="nominal">Nominal</option>
                                        </select>
                                        <div id="update_toggle_jenis_bonus" style="display: none;">
                                            <label class="form-label mb-0 mt-2" for="update_jenis_diskon">Jenis Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                            <select class="form-select" id="update_jenis_diskon">
                                                <option value="nominal">Nominal</option>
                                                <option value="persen">Persen</option>
                                            </select>
                                            <label class="form-label mb-0 mt-2" for="update_jumlah_diskon">Jumlah Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                            <input type="text" class="form-control" id="update_jumlah_diskon" name="update_jumlah_diskon" />
                                        </div>
                                        <label class="form-label mb-0 mt-2" for="update_akumulasi">Akumulasi:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="update_akumulasi">
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                        <label class="form-label mb-0 mt-2" for="update_prioritas">Prioritas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="update_prioritas" name="prioritas" />

                                        <label class="form-label mb-0 mt-2" for="update_satuan_id">Satuan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="update_satuan_id">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Promo Kondisi</div>
                            <div class="card-body" id="update_promo_kondisi_card_body">


                                <table
                                    class="table table-hover table-bordered table-sm"
                                    id="update_jenis_promo_kondisi">

                                    <button class="btn btn-primary btn-sm mb-2" id="update_promo_kondisi_barang_button">
                                        <i class="bi bi-plus-circle"></i> Tambah
                                    </button>
                                    <thead id="update_jenis_promo_kondisi_thead">
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Kondisi</th>
                                            <th>Exclude/Include</th>
                                            <th>Qty Min</th>
                                            <th>Qty Max</th>
                                            <th>Qty Akumulasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>



                                    <tbody id="update_jenis_promo_kondisi_tbody">
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="card" id="update_card_promo_3">
                        <div class="card-header">Promo_3</div>
                        <div class="card-body" id="update_promo_bonus_card_body">
                            <button class="btn btn-primary btn-sm mb-2" id="update_promo_bonus_barang_button">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </button>
                            <table id="update_table_bonus_barang" class="table table-hover table-bordered table-sm">
                                <thead id="update_table_bonus_barang_thead">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Jumlah Qty</th>
                                        <th>Jenis Diskon</th>
                                        <th>Jumlah Diskon/Nominal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="update_table_bonus_barang_tbody">

                                </tbody>
                            </table>
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