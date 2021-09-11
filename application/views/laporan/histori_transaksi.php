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
</style>
<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>


    <div class="row">
        <div class="col-sm-12">
            <div class="row data_historiTX">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Histori Transaksi</h2>
                        </div>
                        <div class="table-responsive">
                            <div id="example-datatable_wrapper" class="dataTables_wrapper form-inline no-footer">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-5">
                                        <div class="dataTables_length">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-7">
                                        <div id="example-datatable_filter" class="dataTables_filter">
                                            <label>
                                                <div class="input-group">
                                                    <input type="search" class="form-control" placeholder="Search" aria-controls="example-datatable" id="form_cari">
                                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                <div class="row">
                                    <div class="col-sm-5 hidden-xs">
                                        <div class="dataTables_info" id="datatable_info" role="status" aria-live="polite"></div>
                                    </div>
                                    <!-- Paginate -->
                                    <div class="col-sm-7 col-xs-12 clearfix">
                                        <div class="dataTables_paginate paging_bootstrap" id='pagination'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type='text/javascript'>
        $(document).ready(function() {

            loadPagination(0);
            // console.log(limit);
            // Detect pagination click
            $(":input").on("keyup change", function(e) {
                e.preventDefault();
                let keyword = $(this).val();
                loadFilter(keyword);
            })
            $('#pagination').on('click', 'a', function(e) {
                e.preventDefault();
                let limit = $('#datatable_length').val();
                let offset = $(this).attr('data-ci-pagination-page');
                loadPagination(limit, offset);
            });

            // Load pagination
            function loadFilter(keyword) {
                $.ajax({
                    url: '<?= base_url() ?>laporan/loadRecord/',
                    type: 'POST',
                    data: {
                        keyword: keyword
                    },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        let data_transaksi = response.data_transaksi;
                        let total_data = response.total_result;
                        let offset = response.row;
                        $('#pagination').html(response.pagination);
                        createTable(data_transaksi, total_data, offset);
                    }
                });
            }
            // Load pagination
            function loadPagination(limit, offset) {
                $.ajax({
                    url: '<?= base_url() ?>laporan/loadRecord/' + offset,
                    type: 'POST',
                    data: {
                        offset: offset,
                        limit: limit
                    },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        let data_transaksi = response.data_transaksi;
                        let total_data = response.total_result;
                        let offset = response.row;
                        $('#pagination').html(response.pagination);
                        createTable(data_transaksi, total_data, offset);
                    }
                });
            }

            // Create table list
            function createTable(data_transaksi, total_data, offset) {
                let htmlx = ``;
                // console.log(data)
                offset = Number(offset);
                $('#example-datatable tbody').empty();
                if (data_transaksi != 0) {
                    let numEnd = offset + 10;
                    $('#datatable_info').html(`<strong>${offset+1}</strong>-<strong>${numEnd}</strong> dari <strong>${total_data}</strong> Record`);
                    no = offset;
                    $.each(data_transaksi, function(i, value) {
                        no++;
                        let total_bayarTrx = 0;
                        htmlx += `<tr>`;
                        htmlx += `<td class = "text-center" >${no}</td>`;
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
                            total_bayarTrx += parseInt(val.jml_bayar);
                        });
                        htmlx += `</td>`;
                        htmlx += `<td class = "text-center"><i>Rp.${parseInt(total_bayarTrx).toLocaleString()}</i></td>`;
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
            }
        });
    </script>
</div>
<!-- END Page Content -->