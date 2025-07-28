<!-- modal_pembelian -->
<div class="modal fade" id="modal_pembelian" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="card">
                    <div class="card-header">Pembelian</div>
                    <div class="card-body" id="pembelian_card_body">


                        <div class="accordion mb-3" id="accordion_informasi_dasar">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Informasi Dasar
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse hide" data-bs-parent="#accordion_informasi_dasar">
                                    <div class="accordion-body">
                                        <div class="row gap-2">
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="tanggal_po">Tanggal PO:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <input class="form-control" type="text" id="tanggal_po" name="tanggal_po" value="">
                                                <label class="form-label mb-0 mt-2" for="status_pembelian">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <select class="form-select" id="status_pembelian">
                                                    <option value="proses">Proses PO</option>
                                                    <option value="delivery">Delivery</option>
                                                    <option value="selesai">Selesai</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label class="form-label mb-0 mt-2" for="supplier_id">Supplier:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                                <select class="form-select" id="supplier_id">
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion mb-3" id="accordion_detail_barang">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                        Informasi Detail Barang
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse hide" data-bs-parent="#accordion_detail_barang">
                                    <div class="accordion-body" id="detail_pembelian_card_body">
                                        <button class="btn btn-primary btn-sm mb-2" id="create_detail_pembelian">
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                        <table class="table  table-hover table-bordered table-sm" id="detail_pembelian">
                                            <thead id="detail_pembelian_thead">
                                                <tr>
                                                    <th scope="col">
                                                        Produk
                                                    </th>
                                                    <th scope="col">
                                                        Kuantitas
                                                    </th>
                                                    <th scope="col">
                                                        Harga
                                                    </th>
                                                    <th scope="col">Satuan</th>
                                                    <th>Diskon</th>
                                                    <th id="aksi_thead">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="create_detail_pembelian_tbody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>









                        <div class="accordion mb-3" id="accordion_pajak">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        Informasi Pajak
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse hide" data-bs-parent="#accordion_pajak">
                                    <div class="accordion-body">


                                        <label class="form-label mb-0 mt-2" for="ppn">PPN:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="ppn" name="ppn" />

                                        <label class="form-label mb-0 mt-2" for="nominal_ppn">Nominal PPN:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="nominal_ppn" name="nominal_ppn" />

                                        <label class="form-label mb-0 mt-2" for="nominal_pph">Nominal PPH:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="nominal_pph" name="nominal_pph" />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion mb-3" id="accordion_informasi_tambahan">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                        Informasi Tambahan
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse hide" data-bs-parent="#accordion_informasi_tambahan">
                                    <div class="accordion-body">

                                        <label class="form-label mb-0 mt-2" for="keterangan">Keterangan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" />


                                    </div>
                                </div>
                            </div>
                        </div>









                        <label class="form-label mb-0 mt-2" for="tanggal_invoice">Tanggal Invoice:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="tanggal_invoice" name="tanggal_invoice" />

                        <label class="form-label mb-0 mt-2" for="no_invoice">No Incvoice Supplier:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="no_invoice" name="no_invoice" />






                        <label class="form-label mb-0 mt-2" for="total_kuantitas">Total Kuantitas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="total_kuantitas" name="total_kuantitas" />


                        <label class="form-label mb-0 mt-2" for="diskon">Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="diskon" name="diskon" />



                        <label class="form-label mb-0 mt-2" for="biaya_tambahan">Biaya Tambahan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="biaya_tambahan" name="biaya_tambahan" />

                        <label class="form-label mb-0 mt-2" for="grand_total">Grand Total<i class="bi bi-asterisk text-danger align-middle "></i></label>
                        <input type="text" class="form-control" id="grand_total" name="grand_total" />




                    </div>
                </div>


                <div class="card ">
                    <div class="card-header">Detail Pembelian</div>
                    <div class="card-body" id="detail_pembelian_card_body">

                    </div>
                </div>




            </div>
            <div class="modal-footer">
                <button id="submit_pembelian" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="modal_pengiriman" tabindex="-1" aria-labelledby="modal_pengirimanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal_pengirimanLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <label class="form-label mb-0 mt-2" for="tanggal_pengiriman">Tanggal Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" />

                <label class="form-label mb-0 mt-2" for="no_pengiriman">No Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="no_pengiriman" name="no_pengiriman" />



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_terima" tabindex="-1" aria-labelledby="modal_terimaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal_terimaLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <label class="form-label mb-0 mt-2" for="tanggal_terima">Tanggal Terima:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="tanggal_terima" name="tanggal_terima" />



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal_terima" tabindex="-1" aria-labelledby="modal_terimaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal_terimaLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <label class="form-label mb-0 mt-2" for="tanggal_terima">Tanggal Terima:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                <input type="text" class="form-control" id="tanggal_terima" name="tanggal_terima" />



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>



<!-- modal_pembelian -->
<div class="modal fade" id="update_modal_pembelian" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="card">
                    <div class="card-header">Update Pembelian</div>
                    <div class="card-body" id="update_pembelian_card_body">
                        <div class="row g-3">
                            <div class="col">
                                <label class="form-label mb-0 mt-2" for="tanggal_po">Tanggal PO:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input class="form-control" type="text" id="tanggal_po" name="tanggal_po" value="">

                                <label class="form-label mb-0 mt-2" for="tanggal_pengiriman">Tanggal Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" />

                                <label class="form-label mb-0 mt-2" for="tanggal_terima">Tanggal Terima:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="tanggal_terima" name="tanggal_terima" />

                                <label class="form-label mb-0 mt-2" for="tanggal_invoice">Tanggal Invoice:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="tanggal_invoice" name="tanggal_invoice" />

                                <label class="form-label mb-0 mt-2" for="supplier_id">Supplier:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <select class="form-select" id="supplier_id">
                                </select>

                                <label class="form-label mb-0 mt-2" for="keterangan">Keterangan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan" />

                                <label class="form-label mb-0 mt-2" for="no_invoice">No Incvoice Supplier:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="no_invoice" name="no_invoice" />

                                <label class="form-label mb-0 mt-2" for="no_pengiriman">No Pengiriman:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="no_pengiriman" name="no_pengiriman" />
                            </div>
                            <div class="col">


                                <label class="form-label mb-0 mt-2" for="total_kuantitas">Total Kuantitas:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="total_kuantitas" name="total_kuantitas" />

                                <label class="form-label mb-0 mt-2" for="ppn">PPN:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="ppn" name="ppn" />

                                <label class="form-label mb-0 mt-2" for="nominal_ppn">Nominal PPN:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="nominal_ppn" name="nominal_ppn" />

                                <label class="form-label mb-0 mt-2" for="diskon">Diskon:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="diskon" name="diskon" />

                                <label class="form-label mb-0 mt-2" for="nominal_pph">Nominal PPH:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="nominal_pph" name="nominal_pph" />

                                <label class="form-label mb-0 mt-2" for="biaya_tambahan">Biaya Tambahan:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="biaya_tambahan" name="biaya_tambahan" />

                                <label class="form-label mb-0 mt-2" for="grand_total">Grand Total<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <input type="text" class="form-control" id="grand_total" name="grand_total" />

                                <label class="form-label mb-0 mt-2" for="status_pembelian">Status:<i class="bi bi-asterisk text-danger align-middle "></i></label>
                                <select class="form-select" id="status_pembelian">
                                    <option value="proses">Proses PO</option>
                                    <option value="delivery">Delivery</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card ">
                    <div class="card-header">Detail Pembelian</div>
                    <div class="card-body" id="detail_pembelian_card_body">
                        <button class="btn btn-primary btn-sm mb-2" id="create_detail_pembelian">
                            <i class="bi bi-plus-circle"></i> Tambah
                        </button>
                        <table class="table  table-hover table-bordered table-sm" id="detail_pembelian">
                            <thead id="detail_pembelian_thead">
                                <tr>
                                    <th scope="col">
                                        Produk
                                    </th>
                                    <th scope="col">
                                        Kuantitas
                                    </th>
                                    <th scope="col">
                                        Harga
                                    </th>
                                    <th scope="col">Satuan</th>
                                    <th>Diskon</th>
                                    <th id="aksi_thead">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="create_detail_pembelian_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
            <div class="modal-footer">
                <button id="submit_pembelian" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>




<main class="col-12 col-lg-10 ms-auto px-1">
    <div id="main" class="table-responsive">
        <h3>Data Pembelian</h3>
        <div id="table_pembelian"></div>
    </div>
</main>