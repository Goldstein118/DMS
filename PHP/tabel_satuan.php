<!-- MODAL-->
<div class="modal fade" id="modal_satuan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Satuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label mb-0 mt-2" for="nama_satuan">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" id="nama_satuan" class="form-control">
                <label class="form-label mb-0 mt-2" for="id_referensi">Id Referensi</label>
                <select class="form-select" id="id_referensi"></select>

                <label class="form-label mb-0 mt-2" for="qty_satuan">Kuantitas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" id="qty_satuan" class="form-control">
            </div>
            <div class="modal-footer">
                <button id="submit_satuan" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal update -->
<div class="modal fade" id="modal_satuan_update" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Satuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label mb-0 mt-2" for="update_satuan_id">Kode User:</label>
                <input class="form-control" type="text" id="update_satuan_id" disabled>


                <label class="form-label mb-0 mt-2" for="update_nama_satuan">Nama:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" id="update_nama_satuan" class="form-control">
                <label class="form-label mb-0 mt-2" for="update_id_referensi">Id Referensi</label>
                <select class="form-select" id="update_id_referensi"></select>

                <label class="form-label mb-0 mt-2" for="update_qty_satuan">Kuantitas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" id="update_qty_satuan" class="form-control">
            </div>
            <div class="modal-footer">
                <button id="submit_satuan_update" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
        <h3>Data User</h3>
        <div id="table_satuan"></div>
    </div>
</main>