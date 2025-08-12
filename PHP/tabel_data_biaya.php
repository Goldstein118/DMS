<!-- Modal -->
<div class="modal fade" id="modal_data_biaya" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Data biaya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label mb-0 mt-2" for="nama_data_biaya">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input class="form-control" type="text" id="nama_data_biaya" name="nama_data_biaya" value="">

            </div>
            <div class="modal-footer">
                <button id="submit_data_biaya" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_data_biaya_update" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Data biaya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label mb-0 mt-2" for="update_data_biaya_id">Kode Data biaya:</label>
                <input class="form-control" type="text" id="update_data_biaya_id" disabled>
                <label class="form-label mb-0 mt-2" for="update_nama_data_biaya">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input class="form-control" type="text" id="update_nama_data_biaya" name="update_nama_data_biaya" value="">

            </div>
            <div class="modal-footer">
                <button id="submit_data_biaya_update" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
        <h3>Data Data biaya</h3>
        <div id="table_data_biaya"></div>
    </div>
</main>