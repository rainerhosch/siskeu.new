<!-- Modal Pembayaran Lainnya-->
<div class="modal fade shadow-md" id="formPembayaranLain" tabindex="-1" role="dialog" aria-labelledby="formPembayaranTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #fff;">&times;</span>
                </button>
                <h5 class="modal-title" id="formPembayaranTitle">Form Pembayaran Lainnya</h5>
            </div>
            <div class="modal-body" id="modal_body">
                <div class="row">
                    <div class="col-sm-4 form_invoice2">
                        <div class="sm-form mb-5 row" style="margin-top: 5px;">
                            <div class="col-sm-12">
                                <input type="text" id="nipd_2" name="nipd_2" class="form-control validate" placeholder="Cari NIM..">
                                <span id="notif_search2"></span>
                            </div>
                        </div>
                        <div class="sm-form mb-5 row text-left">
                            <div class="col-sm-12">
                                <input type="text" id="nama_mhs_2" name="nama_mhs_2" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="sm-form mb-5 row text-left">
                            <div class="col-sm-12">
                                <input type="text" id="jurusan_2" name="jurusan_2" class="form-control validate" readonly>
                            </div>
                        </div>
                        <form action="<?= base_url('transaksi'); ?>/proses_bayar_lainnya" method="post" enctype="multipart/form-data">
                            <br>
                            <table id="tabel_pembayaranLain" class="table table-vcenter table-condensed">
                                <tbody id="tbody_pembayaran_lain">
                                </tbody>
                            </table>
                            <hr class="my-5">
                            <div class="col-sm-9 text-left">
                                <button type="button" id="add_rows" class="btn btn-success text-left div_btn_row"><i class="gi gi-plus"></i></button>
                                <button type="button" id="delete_rows" class="btn btn-danger div_btn_row" disabled><i class="hi hi-minus"></i></button>
                            </div>
                            <div class="col-sm-3 text-right" style="margin-bottom: 5px;">
                                <button type="submit" id="btn_proses_2" class="btn btn-primary" disabled>Proses</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-8">
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
                                <tbody id="riwayat_transaksi_modal_2">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer hidden">
            </div>
        </div>
    </div>
</div>