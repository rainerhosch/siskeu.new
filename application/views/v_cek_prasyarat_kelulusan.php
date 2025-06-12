<style>
    .table thead>tr>th {
        font-size: 14px;
        font-weight: 600;
    }

    .form_invoice2 {
        /* background-color: #110d0dcf; */
        border-radius: 5px;
    }

    .form_invoice {
        /* background-color: #bcbcbccf; */
        border-radius: 5px;
    }

    .modal-dialog {
        width: 90%;
        height: 90%;
    }

    .modal-content {
        height: auto;
        min-height: 90%;
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

    table {
        background-color: #ffffff;
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
    <?php if ($this->session->userdata('role') != 4): ?>
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <a href="#pembayaran-spp" class="widget widget-hover-effect1 rounded" data-toggle="modal"
                    data-target="#formPembayaran">
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
                <a href="#pembayaran-lain" class="widget widget-hover-effect1" data-toggle="modal"
                    data-target="#formPembayaranLain">
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
            <div class="col-sm-6 col-lg-3 btn_trf_online">
                <a href="#data-transfer" class="widget widget-hover-effect1">
                    <!-- <a href="#data-transfer" class="widget widget-hover-effect1" data-toggle="modal" data-target="#dataTransferMhs"> -->
                    <div class="widget-simple">
                        <div class="widget-icon pull-left themed-background-amethyst animation-fadeIn">
                            <i class="gi gi-circle_info"></i>
                        </div>
                        <h3 class="widget-content text-right animation-pullDown">
                            <strong id="label-jmlpembayaran">Pembayaran</strong><br>
                            <small>Mahasiswa Transfer</small>
                        </h3>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="#" class="widget widget-hover-effect1">
                    <div class="widget-simple">
                        <div class="widget-icon pull-left themed-background animation-fadeIn">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <h3 class="widget-content text-right animation-pullDown">
                            <strong>Transaksi</strong><br>
                            <small>Transaksi Hari Ini</small>
                        </h3>
                    </div>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="row data_historiTX">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Transaksi</h2>
                        </div>
                        <div class="table-responsive">
                            <div id="example-datatable_wrapper" class="dataTables_wrapper form-inline no-footer">
                                <div class="row">
                                    <table id="example-datatable"
                                        class="table table-vcenter table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">NIM</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">Tahun Angkatan</th>
                                                <th class="text-center">Rincian Transaksi</th>
                                                <th class="text-center">Tools</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_list_payment">
                                            <!-- isi -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '<?= base_url() ?>cek_prasyarat_kelulusan/show_data',
                type: 'POST',
                data: {
                    'key': 'value' // jika ada data yang ingin dikirim
                },
                serverSide: true,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    let no = 0;
                    let htmlx = '';
                    $.each(response, function(i, value) {
                        no++;
                        htmlx += `<tr>`;
                        htmlx += `<td class="text-center">${no}</td>`;
                        htmlx += `<td class="text-center">${value.nipd}</td>`;
                        htmlx += `<td class="text-center">${value.nm_pd}</td>`;
                        htmlx += `<td class="text-center">${value.tahun_masuk}</td>`;
                        htmlx += `<td class = "text-left" >`;
                        $.each(value.data_trx, function(ii, value2) {
                            htmlx += `<i style="font-size:1.2rem; font-weight: bold;">${value2.nm_jenis_pembayaran} <i class="fa fa-check-circle-o text-success"></i></i><br>`;
                            // if (ii === 0) {
                            //     htmlx += `<i style="font-size:1rem; font-weight: bold;">${value2.nm_jenis_pembayaran} (Rp.${parseInt(value2.jml_bayar).toLocaleString()})</i><br>`;
                            // } else {
                            //     htmlx += `<i style="font-size:1rem; font-weight: bold;">${value2.nm_jenis_pembayaran} (Rp.${parseInt(value2.jml_bayar).toLocaleString()})</i><br>`;
                            // }
                        });
                        htmlx += `</td>`;
                        // htmlx += `<td class="text-center">${value.rincian_transaksi}</td>`;
                        htmlx += `<td class="text-center">
                                    <button class="btn btn-primary btn-sm edit_trx" data-id="${value.id_trx}" data-nipd="${value.nipd}" data-nama="${value.nm_pd}">Edit</button>
                                </td>`;
                    })
                    $("#tbody_list_payment").empty(); // Kosongkan tbody sebelum menambahkan data baru
                    $("#tbody_list_payment").html(htmlx);

                    // pagination
                    $(function () {
                        TablesDatatables.init();
                    });
                }
            });
        });
    </script>
</div>
<!-- END Page Content -->