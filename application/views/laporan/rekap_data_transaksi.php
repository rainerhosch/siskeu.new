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
                            <h2><strong>Data</strong> Rekap</h2>
                        </div>
                        <div class="table-responsive">
                            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="font-size: x-small;">Transaksi</th>
                                        <th class="text-center" style="font-size: x-small;">Jan</th>
                                        <th class="text-center" style="font-size: x-small;">Feb</th>
                                        <th class="text-center" style="font-size: x-small;">Mar</th>
                                        <th class="text-center" style="font-size: x-small;">Apr</th>
                                        <th class="text-center" style="font-size: x-small;">Mei</th>
                                        <th class="text-center" style="font-size: x-small;">Jun</th>
                                        <th class="text-center" style="font-size: x-small;">Jul</th>
                                        <th class="text-center" style="font-size: x-small;">Agu</th>
                                        <th class="text-center" style="font-size: x-small;">Sep</th>
                                        <th class="text-center" style="font-size: x-small;">Okt</th>
                                        <th class="text-center" style="font-size: x-small;">Nov</th>
                                        <th class="text-center" style="font-size: x-small;">Des</th>
                                        <!-- <?php foreach ($field as $i => $val) : ?>
                                            <th class="text-center" style="font-size: x-small;"><?= $val['nm_jp']; ?></th>
                                        <?php endforeach; ?> -->
                                    </tr>
                                </thead>
                                <tbody id="data_rekap_tbody">
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
                type: "GET",
                url: "<?= base_url() ?>/transaksi/getDataForRekap",
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    let htmlx = ``;
                    if (response.pembayaran != 0) {
                        $.each(response.pembayaran, function(i, value) {
                            htmlx += `<tr>`;
                            htmlx += `<th style="font-size: x-small;" width="200">${value.nm_jp}</th>`;
                            htmlx += `</tr>`;
                        });
                    } else {
                        htmlx += `<tr>`;
                        htmlx += `<td colspan="12" class="text-center"><br>`;
                        htmlx += `<div class='col-lg-12'>`;
                        htmlx += `<div class='alert alert-danger alert-dismissible'>`;
                        htmlx += `<h4><i class='icon fa fa-warning'></i> Tidak Ada Data!</h4>`;
                        htmlx += `</div>`;
                        htmlx += `</div>`;
                        htmlx += `</td>`;
                        htmlx += `</tr>`;
                    }
                    $("#data_rekap_tbody").html(htmlx);
                }
            });
        });
    </script>
</div>
<!-- END Page Content -->