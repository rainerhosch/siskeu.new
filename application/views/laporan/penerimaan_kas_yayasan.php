<style>
    .table thead > tr > th {
        font-size: 14px;
        font-weight: 600;
    }

    /* Style the horizontal ruler */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    table {
        background-color: #ffffff;
    }
    .widget-simple
    .widget-content {
        font-size: 14px;
        margin: 12px 0;
    }
    .widget-simple .widget-content small {
        display: block;
        margin-top: 7px;
        font-size: 10px;
        font-weight: 400;
        font-style: oblique;
    }
</style>
<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <div class="row">
        <div class="col-sm-4 col-lg-4" id="btn_cetak_laporan_bulan_lalu">
            <a class="widget widget-hover-effect1" data-toggle="modal">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                        <i class="gi gi-print"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Buat Laporan<strong> Excel</strong><br>
                        <small><strong>Laporan Bulan Kas Bulan Lalu.</strong></small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-4 col-lg-4" id="btn_creat_laporan">
            <a class="widget widget-hover-effect1" data-toggle="modal">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                        <i class="gi gi-print"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Buat Laporan<strong> Excel</strong><br>
                        <small><strong>Membuat Laporan Per tanggal.</strong></small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-4 col-lg-4">
            <a class="widget widget-hover-effect1" data-toggle="modal">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-spring animation-fadeIn">
                        <i class="fa fa-usd"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Kas Diterima Per <?= $bln_berjalan; ?> <strong><?= 'Rp.' . number_format($total_uang_masuk_bulan_ini); ?></strong><br>
                        <small><strong>(Data tersebut pure uang masuk, data input potongan tidak di hitung.)</strong></small>
                    </h3>
                </div>
            </a>
        </div>
    </div>

    <div class="row table_content">
        <div class="col-sm-12">
            <div class="row data_historiTX">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Penerimaan Kas</h2>
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
                                                <th class="text-center">Tgl Transaksi</th>
                                                <th class="text-center">Nim</th>
                                                <th class="text-center">Rincian</th>
                                                <th class="text-center">Total</th>
                                                <!-- <th class="text-center">Sisa Tagihan</th> -->
                                                <th class="text-center">Semester</th>
                                                <th class="text-center">Keterangan</th>
                                                <th class="text-center">Admin</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_kas_tbody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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


    <script>
        $(document).ready(function() {
            // pagination setup
            loadPagination(0);
            $('#pagination').on('click', 'a', function(e) {
                e.preventDefault();
                let limit = $('#datatable_length').val();
                let offset = $(this).attr('data-ci-pagination-page');
                loadPagination(limit, offset);
            });

            $("#form_cari").on("keyup change", function(e) {
                e.preventDefault();
                let keyword = $(this).val();
                loadFilter(keyword);
            });

            //=========================== Load pagination ================================
            function loadFilter(keyword) {
                let jenis_kas = 1;
                $.ajax({
                    url: '<?= base_url() ?>laporan/loadRecord/',
                    type: 'POST',
                    data: {
                        keyword: keyword,
                        jenis_kas: jenis_kas,
                        url_pagination: 'DataKasYBB'
                    },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        let user_log = response.user_loged;
                        let data_transaksi = response.data_transaksi;
                        let total_data = response.total_result;
                        let offset = response.row;
                        $('#pagination').html(response.pagination);
                        // $('#datatable_info').html(`<strong>1</strong>-<strong>10</strong> of <strong>${total_data}</strong>`);
                        createTable(user_log, data_transaksi, total_data, offset);
                    }
                });
            }

            function loadPagination(limit, offset) {
                let jenis_kas = 1;
                $.ajax({
                    url: '<?= base_url() ?>laporan/loadRecord/' + offset,
                    type: 'POST',
                    data: {
                        offset: offset,
                        limit: limit,
                        jenis_kas: jenis_kas,
                        url_pagination: 'DataKasYBB'
                    },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        let user_log = response.user_loged;
                        let data_transaksi = response.data_transaksi;
                        let total_data = response.total_result;
                        let offset = response.row;
                        $('#pagination').html(response.pagination);
                        createTable(user_log, data_transaksi, total_data, offset);
                    }
                });
            }


            function createTable(user_log, data_transaksi, total_data, offset) {
                let htmlx = ``;
                let uang_masuk = ``;
                offset = Number(offset);
                $('#example-datatable tbody').empty();

                if (data_transaksi != 0) {
                    console.log(data_transaksi)
                    let total_bayar = 0;
                    
                    let numEnd = offset + 10;
                    $('#datatable_info').html(`<strong>${offset+1}</strong>-<strong>${numEnd}</strong> dari <strong>${total_data}</strong> Record`);
                    no = offset;
                    $.each(data_transaksi, function(i, value) {
                        let sum = 0;
                        no++;
                        htmlx += `<tr>`;
                        htmlx += `<td class = "text-center" >${no}</td>`;
                        htmlx += `<td class = "text-center" >${value.tanggal}</td>`;
                        htmlx += `<td class = "text-center" >${value.nim}</td>`;
                        htmlx += `<td class = "text-center" >`;
                        $.each(value.detail_transaksi, function(k, val) {
                            htmlx += `<i style="font-size:1rem; font-weight: bold;">${val.nm_jenis_pembayaran}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.jml_bayar).toLocaleString()}</i><br>`;
                            sum += Number(val.jml_bayar);
                            total_bayar += parseInt(val.jml_bayar);
                        });
                            // console.log(sum);
                        const total = sum;
                        htmlx += `</td><td class = "text-center"><i>Rp.${parseInt(total).toLocaleString()}</i></td>`;
                        htmlx += `<td class = "text-center" >${value.semester}</td>`;
                        if(value.uang_masuk != 1){
                            uang_masuk = `Potongan SPP/Beasiswa`;
                        }else{
                            uang_masuk = ``;
                        }
                        htmlx += `<td class = "text-center" >${uang_masuk}</td>`;
                        htmlx += `<td class = "text-center" >${value.nama_user}</td>`;
                        htmlx += `</tr>`;
                        });

                        // htmlx += `<tr>`;
                        // htmlx += `<td colspan="4" class="text-center"><i>TOTAL JUMLAH PEMBAYARAN PADA SEMESTER INI</i></td>`;
                        // htmlx += `<td colspan="6" class="text-center"><i id="total">Rp.${total_bayar.toLocaleString()}</i></td>`;
                        // htmlx += `</tr>`;
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
                $("#data_kas_tbody").html(htmlx);
            }

            $('#btn_cetak_laporan_bulan_lalu').on('click', function(e) {
                e.preventDefault();
                let hrf = `<?= base_url('laporan/BuatLaporanBulanan') ?>?jenis_laporan=1`;
                window.open(hrf, '_blank');
            });
        });
    </script>
</div>
<!-- END Page Content -->