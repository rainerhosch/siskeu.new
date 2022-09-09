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
                            <h2><strong>Data</strong> Penerimaan Kas</h2>
                        </div>
                        <div class="table-responsive">
                            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Tgl Transaksi</th>
                                        <th class="text-center">Nim</th>
                                        <th class="text-center">Uraian Penerimaan</th>
                                        <th class="text-center">Total</th>
                                        <!-- <th class="text-center">Sisa Tagihan</th> -->
                                        <!-- <th class="text-center">Semester</th>
                                        <th class="text-center">Status</th> -->
                                        <th class="text-center">Admin</th>
                                    </tr>
                                </thead>
                                <tbody id="data_kas_tbody">
                                </tbody>
                            </table>
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
                url: "<?= base_url() ?>laporan/getDataPenerimaanKasKmhs",
                data: {
                    data: 1
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    let htmlx = ``;
                    if (response.kas_yayasan != 0) {
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
                            });
                            // console.log(sum);
                            const total = sum;
                            htmlx += `</td><td class = "text-center"><i>Rp.${parseInt(total).toLocaleString()}</i></td>`;
                            // htmlx += `<td class = "text-center" >${value.semester}</td>`;
                            // htmlx += `<td class = "text-center" >${value.icon_status_tx}</td>`;
                            htmlx += `<td class = "text-center" >${value.nama_user}</td>`;
                            htmlx += `</tr>`;
                        });
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