<!-- Modal Pembayaran Lainnya-->
<div class="modal fade shadow-md" id="dataTransferMhs" tabindex="-1" role="dialog" aria-labelledby="formPembayaranTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #fff;">&times;</span>
                </button>
                <h5 class="modal-title" id="formPembayaranTitle">Data Transfer Mahasiswa</h5>
            </div>
            <div class="modal-body" id="modal_body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Keterangan Bayar</th>
                                        <th class="text-center">Tgl Transfer</th>
                                        <th class="text-center">Rekening Tujuan</th>
                                        <th class="text-center">Jumlah Transfer</th>
                                        <th class="text-center">Bukti Transfer</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_data_trf">
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