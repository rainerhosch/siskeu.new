<style>
    .form_invoice2 {
        /* background-color: #110d0dcf; */
        border-radius: 5px;
    }

    .form_invoice {
        /* background-color: #bcbcbccf; */
        border-radius: 5px;
    }

    .modal-dialog {
        width: 70%;
        height: 70%;
    }

    .modal-content {
        height: auto;
        min-height: 70%;
        border-radius: 0;
        justify-content: center;
    }

    .modal-header {
        background-color: #1d2132;
        border-radius: 0%;
    }

    .modal-title {
        font-weight: 900;
        color: #fff;
    }

    .row {
        margin-bottom: 5px;
    }

    .jumbotron {
        padding-top: 30px;
        padding-bottom: 30px;
        margin-bottom: 10px;
        color: inherit;
        border-radius: 5px;
        /* background-color: #d4d4d4; */
        background-color: #eeeeee;
    }

    /* .xform {
        margin-left: 5px;
    } */
    .modal_datatable {
        font-size: 8px;
    }

    /* Modal Content/Box */
    .modal-content2 {
        text-align: center;
        background-color: #fefefe;
        margin: 5% auto 15% auto;
        border: 1px solid #888;
        width: 80%;
    }

    /* Style the horizontal ruler */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    .modal-notify .modal-header {
        border-radius: 3px 3px 0 0;
    }

    .modal-notify .modal-content {
        border-radius: 3px;
    }
</style> <!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <!-- END Page Header -->
    <div class="row" id="alert_tx">
        <?= $this->session->flashdata('message'); ?>
    </div>

    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <a href="#pembayaran-spp" class="widget widget-hover-effect1" data-toggle="modal" data-target="#formPembayaran">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-spring animation-fadeIn">
                        <i class="fa fa-bitcoin"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Pembayaran <strong>SPP</strong><br>
                        <small>Buat Transaksi Baru</small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="#pembayaran-lain" class="widget widget-hover-effect1" data-toggle="modal" data-target="#formPembayaranLain">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Pembayaran <strong>Lain</strong><br>
                        <small>Buat Transaksi Baru</small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="#" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-amethyst animation-fadeIn">
                        <i class="gi gi-circle_info"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        0 <strong>Pembayaran</strong><br>
                        <small>Transaksi Online</small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="#" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background animation-fadeIn">
                        <!-- <i class="gi gi-wallet"></i> -->
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <?= $jumlah_tx_hari_ini; ?> <strong>Transaksi</strong><br>
                        <small>Transaksi Hari Ini</small>
                    </h3>
                </div>
            </a>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <div class="row data_historiTX">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Transaksi</h2>
                        </div>
                        <div class="table-responsive">
                            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nomo Transaksi</th>
                                        <th class="text-center">Tgl Transaksi</th>
                                        <th class="text-center">Jam</th>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Rincian Transaksi</th>
                                        <th class="text-center">Jumlah</th>
                                        <!-- <th class="text-center">Sisa Bayar</th> -->
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Admin</th>
                                        <th class="text-center">Tools</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayat_transaksi_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal edit -->
    <div class="modal" tabindex="-1" role="dialog" id="editTrx">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Transaksi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <form action="<?= base_url('manajemen'); ?>/update-menu" method="post" enctype="multipart/form-data"> -->
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="id_trx">ID TRANSAKSI</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="id_trx" name="id_trx" class="form-control validate" readonly>
                            </div>
                        </div>

                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nim_trx">NIM</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nim_trx" name="nim_trx" class="form-control validate" readonly>
                            </div>
                        </div>

                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="mhs_trx">NAMA MAHASISWA</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="mhs_trx" name="mhs_trx" class="form-control validate" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="md-form mb-5 row text-center">
                            <strong>Detail Transaksi</strong>
                        </div>
                        <br>

                        <table class="table table-vcenter table-condensed">
                            <tbody id="tabel_form_edit_trx">
                            </tbody>
                        </table>
                        <br>
                        <div class="md-form row text-right" style="margin-right: 2px;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div> -->
            </div>
        </div>
    </div>
    <!-- end modal edit -->

    <!-- Modal Form -->
    <?php $this->load->view('transaksi/modal_form_spp'); ?>
    <?php $this->load->view('transaksi/modal_form_pembayaran_lain'); ?>
    <script>
        $(document).ready(function() {
            $('.div_btn_row').hide();
            $("#riwayat_transaksi").hide();
            $(".data_kwajiban").hide();
            setTimeout(function() {
                $("#alert_tx").html("");
            }, 2000);
            // your code here
            $.ajax({
                type: "POST",
                url: "transaksi/getDataTransaksi",
                data: {
                    data: 1
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    let htmlx = ``;
                    $("#riwayat_transaksi").show();
                    if (response.data_transaksi != 0) {
                        $.each(response.data_transaksi, function(i, value) {
                            i++;

                            var total_bayarTrx = 0;
                            htmlx += `<tr>`;
                            htmlx += `<td class = "text-center" >${i}</td>`;
                            htmlx +=
                                `<td class="text-center"><a target="_blank" rel="noopener noreferrer" href="<?= base_url('transaksi/cetak_ulang_kwitansi/') ?>` +
                                value.id_transaksi +
                                `" data-toggle="tooltip" title="Cetak Kwitansi">${value.id_transaksi}</a></td>`;
                            htmlx += `<td class = "text-center" >${value.tanggal}</td>`;
                            htmlx += `<td class = "text-center" >${value.jam}</td>`;
                            htmlx += `<td class = "text-center" >${value.nim}</td>`;
                            htmlx += `<td class = "text-center" >${value.nm_pd}</td>`;

                            htmlx += `<td class = "text-center" >`;
                            $.each(value.detail_transaksi, function(k, val) {
                                htmlx += `<i style="font-size:1rem; font-weight: bold;">${val.nm_jenis_pembayaran}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.jml_bayar).toLocaleString()}</i><br>`;
                                total_bayarTrx += parseInt(val.jml_bayar);
                            });
                            htmlx += `</td>`;
                            htmlx += `<td class = "text-center"><i>Rp.${parseInt(total_bayarTrx).toLocaleString()}</i></td>`;
                            // htmlx += `<td class = "text-center" >`;
                            // $.each(value.detail_transaksi, function(k, val) {
                            //     htmlx += `<i style="font-size:1rem; font-weight: bold;">${val.nm_jenis_pembayaran}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.sisa_bayar).toLocaleString()}</i><br>`;
                            // });
                            // htmlx += `</td>`;
                            htmlx += `<td class = "text-center" >${value.semester}</td>`;
                            if (total_bayarTrx < value.kewajiban_Semester_ini) {
                                htmlx += `<td class = "text-center" >BL</td>`;
                            } else {
                                htmlx += `<td class = "text-center" >L</td>`;
                            }
                            htmlx += `<td class = "text-center" >${value.nama_user}</td>`;
                            // htmlx += `<td class = "text-center" >${value.nama_user}</td>`;
                            htmlx += `<td class="text-center">`;
                            if (value.user_id !== response.user_loged) {
                                // htmlx += `<a href="#" onclick="" class="btn btn-xs btn-info btn-edit-transaksi" id="btn_edit_transaksi" value="" disabled>Edit</a> | `;
                                htmlx += `<a href="#" onclick="" class="btn btn-xs btn-danger btn-hapus-transaksi" id="btn_hapus_transaksi" value="" disabled>Hapus</a>`;
                            } else {
                                // htmlx += `<a href="#"  onclick="editTransaksi(${value.id_transaksi})" class="btn btn-xs btn-info btn-edit-transaksi" id="btn_edit_transaksi" value="${value.id_transaksi}">Edit</a> | `;
                                htmlx += `<a href="#"  onclick="deleteTransaksi(${value.id_transaksi})" class="btn btn-xs btn-danger btn-hapus-transaksi" id="btn_hapus_transaksi" value="${value.id_transaksi}">Hapus</a>`;
                            }
                            htmlx += `</td>`;
                            htmlx += `</tr>`;
                        });
                    } else {
                        htmlx += `<tr>`;
                        htmlx += `<td colspan="12" class="text-center"><br>`;
                        htmlx += `<div class='col-lg-12'>`;
                        htmlx += `<div class='alert alert-danger alert-dismissible'>`;
                        htmlx += `<h4><i class='icon fa fa-warning'></i> Belum Transaksi Pada Semester Ini!</h4>`;
                        htmlx += `</div>`;
                        htmlx += `</div>`;
                        htmlx += `</td>`;
                        htmlx += `</tr>`;
                    }
                    $("#riwayat_transaksi_tbody").html(htmlx);
                    $(function() {
                        TablesDatatables.init();
                    });
                },
                error: function(e) {
                    error_server();
                },
            });
            // end hapus menu
        });

        function editTransaksi(id_transaksi) {
            $.ajax({
                type: "POST",
                url: "transaksi/getDataTransaksi",
                data: {
                    data: id_transaksi
                },
                dataType: "json",
                success: function(response) {
                    $("#editTrx").modal("show");
                    let htmlz = ``;
                    $.each(response.data_transaksi, function(i, val) {
                        let id_trx = val.id_transaksi;
                        let nim = val.nim;
                        let nm_pd = val.nm_pd;
                        $.each(val.detail_transaksi, function(k, detailTx) {
                            htmlz += `<tr>`;
                            htmlz += `<td><label data-error="wrong" data-success="right" for="${detailTx.nm_jenis_pembayaran}">${detailTx.nm_jenis_pembayaran}</label></td>`;
                            htmlz += `<td class="text-center"><input type="text" id="input_${detailTx.id_detail_transaksi}" name="${detailTx.id_detail_transaksi}" class="form-control validate text-right input_${k}" value="${detailTx.jml_bayar}"></td>`;
                            htmlz + `</tr>`;
                        });

                        htmlz += `<tr>`;
                        htmlz += `<td><label data-error="wrong" data-success="right" for="${val.id_transaksi}">Total Bayar</label></td>`;
                        htmlz += `<td class="text-center"><input type="text" id="total_${val.id_transaksi}" name="${val.id_transaksi}" class="form-control validate text-right input_${i}" value="${val.total_bayar}" readonly></td>`;
                        htmlz + `</tr>`;
                        $('#id_trx').val(id_trx);
                        $('#mhs_trx').val(nm_pd);
                        $('#nim_trx').val(nim);

                    });
                    $("#tabel_form_edit_trx").html(htmlz);

                    let data = 0;
                    $.each(response.data_transaksi, function(i, val) {
                        $.each(val.detail_transaksi, function(k, detailTx) {
                            $('#input_' + detailTx.id_detail_transaksi).on('input', function() {
                                data += $('#input_' + detailTx.id_detail_transaksi).val();
                                $('#total_' + detailTx.id_detail_transaksi).val(data);
                            });
                        });
                    });

                    console.log(response);

                }
            });
        }

        function deleteTransaksi(id_transaksi) {
            // console.log(id_transaksi);
            Swal.fire({
                title: "Delete Transaksi",
                text: `Apakah anda ingin menghapus data transaski ${id_transaksi}?.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                closeOnConfirm: false,
                closeOnCancel: false,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    // cetak
                    window.location.replace(`transaksi/hapus_transaksi/${id_transaksi}`);
                    // window.focus();

                    // location.reload();
                } else {
                    // refresh page
                    location.reload();
                }
            });
        }
    </script>
    <script src="<?= base_url() ?>assets/js/pembayaran_lainnya.js"></script>
    <script src="<?= base_url() ?>assets/js/pembayaran_spp.js"></script>
</div>
<!-- END Page Content -->