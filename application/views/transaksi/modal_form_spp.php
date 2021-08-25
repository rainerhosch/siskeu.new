<!-- Modal Pembayaran SPP-->
<div class="modal fade" id="formPembayaran" tabindex="-1" role="dialog" aria-labelledby="formPembayaranTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #fff;">&times;</span>
                </button>
                <h5 class="modal-title" id="formPembayaranTitle">Form Pembayaran SPP</h5>
            </div>
            <div class="modal-body" id="modal_body">
                <div class="row">
                    <div class="col-sm-3 form_invoice">
                        <div class="sm-form mb-5 row" style="margin-top: 5px;">
                            <div class="col-sm-12">
                                <input type="text" id="nipd" name="nipd" class="form-control validate" placeholder="Cari NIM..">
                                <span id="notif_search"></span>
                            </div>
                        </div>
                        <div class="sm-form mb-5 row text-left">
                            <div class="col-sm-12">
                                <input type="text" id="nama_mhs" name="nama_mhs" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="sm-form mb-5 row text-left">
                            <div class="col-sm-12">
                                <input type="text" id="jurusan" name="jurusan" class="form-control validate" readonly>
                            </div>
                        </div>
                        <form id="form_pembayaran" enctype="multipart/form-data">
                            <br>
                            <table id="menu-datatable" class="table table-vcenter table-condensed">
                                <tbody id="data_kwajiban_tbody">
                                </tbody>
                            </table>
                            <hr class="my-5">
                            <div class="text-right" style="margin-bottom: 5px;">
                                <button type="submit" id="btn_proses" class="btn btn-primary" disabled>Proses</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-9">
                        <div class="table-responsive">
                            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nomo Transaksi</th>
                                        <th class="text-center">Tgl Transaksi</th>
                                        <th class="text-center">Jam</th>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Keterangan Bayar</th>
                                        <th class="text-center">Jumlah Storan</th>
                                        <!-- <th class="text-center">Sisa Tagihan</th> -->
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayat_transaksi_modal">
                                </tbody>
                                <tfoot id="riwayat_transaksi_tfoot"></tfoot>
                            </table>
                        </div>

                        <div class="alert alert-success" id="alert_potongan" hidden>
                            <p id="text_info_potongan"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer hidden">
            </div>
        </div>
    </div>
</div>