<style>
    .form_invoice {
        background-color: #bcbcbccf;
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
            <a href="#pembayaran-lain" class="widget widget-hover-effect1">
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
                        100 <strong>Transaksi</strong><br>
                        <small>Transaksi Harian</small>
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
                            <form action="<?= base_url('transaksi'); ?>/proses_bayar_spp" method="post" enctype="multipart/form-data">
                                <br>
                                <table id="menu-datatable" class="table table-vcenter table-condensed">
                                    <tbody id="data_kwajiban_tbody">
                                    </tbody>
                                </table>
                                <hr class="my-5">
                                <div class="text-right" style="margin-bottom: 5px;">
                                    <button type="submit" id="btn_proses" class="btn btn-primary">Proses</button>
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
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer hidden">
                    <!-- <div class="row">
                        <div class="col-sm-3">
                            <form action="<?= base_url('transaksi'); ?>/proses_bayar_spp" method="post" enctype="multipart/form-data">
                                <br>
                                <table id="menu-datatable" class="table table-vcenter table-condensed">
                                    <tbody id="data_kwajiban_tbody">
                                    </tbody>
                                </table>
                                <hr class="my-5">
                                <div class="text-right">
                                    <button type="submit" id="btn_proses" class="btn btn-primary">Proses</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6"></div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#riwayat_transaksi').hide();
            $('.data_kwajiban').hide();
            setTimeout(function() {
                $('#alert_tx').html('');
            }, 2000);
            // your code here 
            $.ajax({
                type: "POST",
                url: 'transaksi/getDataTransaksi',
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    let htmlx = ``;
                    $('#riwayat_transaksi').show();
                    if (response.data_transaksi != 0) {
                        $.each(response.data_transaksi, function(i, value) {
                            i++;
                            htmlx += `<tr>`;
                            htmlx += `<td class = "text-center" >${i}</td>`;
                            htmlx += `<td class="text-center"><a href="<?= base_url('transaksi/cetak_kwitansi/') ?>` + value.id_transaksi + `" data-toggle="tooltip" title="Cetak Kwitansi">${value.id_transaksi}</a></td>`;
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


            $('#nipd').on('keyup', function() {
                let nipd = $('#nipd').val();
                $.ajax({
                    type: "POST",
                    url: 'transaksi/cari_mhs',
                    data: {
                        nipd: nipd,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response != null) {
                            if (response.totalKewajiban != 0) {
                                $('.btn#btn_proses').prop('disabled', false);
                            } else {
                                $('.btn#btn_proses').prop('disabled', true);
                            }
                            let html = ``;
                            let html_3 = ``;
                            $('.data_kwajiban').show();
                            $('#riwayat_transaksi').show();
                            $("#nama_mhs").val(response.nm_pd);
                            $("#jurusan").val(response.nm_jur);
                            html += `<input type="hidden" id="nim_mhs_bayar" name="nim_mhs_bayar" value="${response.nipd}">`;
                            html += `<input type="hidden" id="nama_mhs_bayar" name="nama_mhs_bayar" value="${response.nm_pd}">`;
                            html += `<input type="hidden" id="jenjang_mhs_bayar" name="jenjang_mhs_bayar" value="${response.nm_jenj_didik}">`;
                            html += `<input type="hidden" id="angkatan_mhs_bayar" name="angkatan_mhs_bayar" value="${response.tahun_masuk}">`;

                            $.each(response.dataKewajiban, function(i, value) {
                                html += `<tr>
                                        <td><label data-error="wrong" data-success="right" for="${value.label}">${value.label}</label></td>
                                        <td class="text-center"><input type="text" id="${value.post_id}" name="${value.post_id}" class="form-control validate text-right input_${i}" value="${value.biaya}" disabled></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_${i}" ${value.biaya == 0 ? 'disabled' : ''}></td>
                                    </tr>`;
                            });
                            $("#data_kwajiban_tbody").html(html);
                            $.each(response.dataKewajiban, function(i, value) {
                                $("#checkcox_" + i).change(function() {
                                    if (this.checked === true) {
                                        $('#' + value.post_id).prop('disabled', false);
                                    } else {
                                        $('#' + value.post_id).prop('disabled', true);
                                    }
                                });
                            });
                            if (response.dataHistoriTX != null) {
                                $.each(response.dataHistoriTX, function(i, value) {
                                    // console.log(value);
                                    i++;
                                    html_3 += `<tr>`;
                                    html_3 += `<td class = "text-center" >${i}</td>`;
                                    html_3 += `<td class="text-center"><a href="<?= base_url('transaksi/cetak_kwitansi/') ?>` + value.id_transaksi + `">${value.id_transaksi}</a></td>`;
                                    html_3 += `<td class = "text-center" >${value.tanggal}</td>`;
                                    html_3 += `<td class = "text-center" >${value.jam}</td>`;
                                    html_3 += `<td class = "text-center" >${value.nim}</td>`;

                                    html_3 += `<td class = "text-center" >`;
                                    $.each(value.detail_transaksi, function(k, val) {
                                        html_3 += `<i style="font-size:1rem; font-weight: bold;">${val.nm_jenis_pembayaran}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.jml_bayar).toLocaleString()}</i><br>`;
                                    });
                                    html_3 += `</td>`;
                                    html_3 += `<td class = "text-center"><i>Rp.${parseInt(value.total_bayar).toLocaleString()}</i></td>`;
                                    html_3 += `<td class = "text-center" >${value.semester}</td>`;
                                    html_3 += `<td class = "text-center" >${value.icon_status_tx}</td>`;
                                    html_3 += `</tr>`;
                                });
                            } else {
                                html_3 += `<tr>`;
                                html_3 += `<td colspan="12" class="text-center"><br>`;
                                html_3 += `<div class='col-lg-12'>`;
                                html_3 += `<div class='alert alert-danger alert-dismissible'>`;
                                html_3 += `<h4><i class='icon fa fa-warning'></i> Belum Ada Histori Pembayaran!</h4>`;
                                html_3 += `</div>`;
                                html_3 += `</div>`;
                                html_3 += `</td>`;
                                html_3 += `</tr>`;
                            }
                            $("#riwayat_transaksi_modal").html(html_3);

                            // $(function() {
                            //     TablesModalDatatables.init();
                            // });

                        } else {
                            // $('#notif_search').html("<div class='alert alert-danger alert-dismissable'>Tidak ada mahsiswa dengan nim : " + nipd + "</div>");
                            $('#notif_search').html("<code>Tidak ada mahasiswa dengan nim : " + nipd + "</code>");
                            setTimeout(function() {
                                $('#notif_search').html('');
                            }, 2000);
                            // alert('Data mahasiswa tersebut tidak ditemukan, pastikan NIM sudah benar!');
                            // window.location.reload();
                        }


                    },
                    error: function(e) {
                        error_server();
                    },
                });
            });
        });
    </script>
</div>
<!-- END Page Content -->