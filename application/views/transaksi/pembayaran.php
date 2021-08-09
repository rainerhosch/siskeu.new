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
                                        <th class="text-center">Keterangan Bayar</th>
                                        <th class="text-center">Jumlah Storan</th>
                                        <!-- <th class="text-center">Sisa Tagihan</th> -->
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Admin</th>
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
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    let htmlx = ``;
                    $("#riwayat_transaksi").show();
                    if (response.data_transaksi != 0) {
                        $.each(response.data_transaksi, function(i, value) {
                            i++;
                            htmlx += `<tr>`;
                            htmlx += `<td class = "text-center" >${i}</td>`;
                            htmlx +=
                                `<td class="text-center"><a href="<?= base_url('transaksi/cetak_kwitansi/') ?>` +
                                value.id_transaksi +
                                `" data-toggle="tooltip" title="Cetak Kwitansi">${value.id_transaksi}</a></td>`;
                            htmlx += `<td class = "text-center" >${value.tanggal}</td>`;
                            htmlx += `<td class = "text-center" >${value.jam}</td>`;
                            htmlx += `<td class = "text-center" >${value.nim}</td>`;

                            htmlx += `<td class = "text-center" >`;
                            $.each(value.detail_transaksi, function(k, val) {
                                htmlx += `<i style="font-size:1rem; font-weight: bold;">${val.nm_jenis_pembayaran}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.jml_bayar).toLocaleString()}</i><br>`;
                            });
                            htmlx += `</td>`;
                            htmlx += `<td class = "text-center"><i>Rp.${parseInt(value.total_bayar).toLocaleString()}</i></td>`;
                            htmlx += `<td class = "text-center" >${value.semester}</td>`;
                            htmlx += `<td class = "text-center" >${value.icon_status_tx}</td>`;
                            htmlx += `<td class = "text-center" >${value.nama_user}</td>`;
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
        });
    </script>
    <script src="<?= base_url() ?>assets/js/pembayaran_lainnya.js"></script>
    <script src="<?= base_url() ?>assets/js/pembayaran_spp.js"></script>
</div>
<!-- END Page Content -->