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
                                        <label class="form-label mb-0 mt-2" for="dibuat_pada">Dibuat Pada:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="dibuat_pada" name="dibuat_pada" />
                                        <label class="form-label mb-0 mt-2" for="jumlah_diskon">Jumlah Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="jumlah_diskon" name="jumlah_diskon" />
                                        <label class="form-label mb-0 mt-2" for="jenis_diskon">Jenis Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <select class="form-select" id="jenis_diskon">
                                            <option value="flat cut">Flat Cut</option>
                                            <option value="persen">Persen</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Promo Kondisi</div>
                            <div class="card-body">
                                <select class="js-example-basic-multiple" id="jenis_brand" name="states[]" multiple="multiple">
                                </select>
                            </div>
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
                <label class="form-label" for="update_promo_id">Kode Promo:</label>
                <input class="form-control" type="text" id="update_promo_id" disabled>
                <label class="form-label" for="update_nama_promo">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input class="form-control" type="text" id="update_nama_promo" name="update_nama_promo" value="">
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
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target=" #modal_promo">Add</button>
        <div id="table_promo"></div>

    </div>
</main>

