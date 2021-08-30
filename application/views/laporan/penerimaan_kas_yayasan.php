<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <div class="row">
        <div class="col-sm-6 col-lg-12">
            <a class="widget widget-hover-effect1" data-toggle="modal">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-spring animation-fadeIn">
                        <i class="fa fa-usd"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Kas Diterima Per<strong> <?= $bln_transaksi; ?></strong><br>
                        <small><?= 'Rp.' . number_format($total_uang_masuk_bulan_ini); ?> </small>
                    </h3>
                </div>
            </a>
        </div>
        <!-- <div class="col-sm-6 col-lg-3">
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
        </div> -->
        <!-- <div class="col-sm-6 col-lg-3">
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
        </div> -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="row data_historiTX">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Penerimaan Kas</h2>
                        </div>
                        <div class="table-responsive">
                            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                <!-- <div class="col-md-12">
                                    <div class="col-md-4 pull-left">
                                        <div class="input-group" id="date_filter">
                                            <input type="text" id="datepicker_from" class="form-control date_range_filter" data-date-format="yyyy-mm-dd" placeholder="From:">
                                            <div class="input-group-addon">to</div>
                                            <input type="text" id="datepicker_to" class="form-control date_range_filter" data-date-format="yyyy-mm-dd" placeholder="To:">
                                        </div>
                                    </div>
                                </div> -->
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
                            </di>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>laporan/getDataPenerimaanKasYayasan",
                data: {
                    data: 1
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    let htmlx = ``;
                    if (response.kas_yayasan != 0) {
                        let total_bayar = 0;
                        $.each(response.kas_yayasan, function(i, value) {
                            let sum = 0;
                            i++;
                            htmlx += `<tr>`;
                            htmlx += `<td class = "text-center" >${i}</td>`;
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
                            htmlx += `<td class = "text-center" >${value.uang_masuk}</td>`;
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
                        htmlx += `<h4><i class='icon fa fa-warning'></i> Belum Ada Data Pada Semester Ini!</h4>`;
                        htmlx += `</div>`;
                        htmlx += `</div>`;
                        htmlx += `</td>`;
                        htmlx += `</tr>`;
                    }
                    $("#data_kas_tbody").html(htmlx);
                    $(function() {
                        TablesDatatables.init();
                    });

                }
            });


        });
    </script>
</div>
<!-- END Page Content -->