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
                            <h2><strong>Tabel</strong> <?= $page; ?></h2>
                        </div>
                        <div class="table-responsive">
                            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nim</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Jurusan</th>
                                        <th class="text-center">Jenis Tunggakan</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayat_transaksi_tbody">
                                    <?php foreach ($tunggakan as $i => $tg) :
                                        $total_tg[] = $tg['jml_tunggakan'];
                                        $i++; ?>
                                        <tr>
                                            <td class="text-center"><?= $i ?></td>
                                            <td class="text-center"><?= $tg['nipd']; ?></td>
                                            <td class="text-center"><?= $tg['nm_pd']; ?></td>
                                            <td class="text-center"><?= $tg['nm_jur']; ?></td>
                                            <td class="text-center"><?= $tg['nm_jenis_pembayaran']; ?></td>
                                            <td class="text-center"><?= number_format($tg['jml_tunggakan']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <!-- <td class="text-center"><?= array_sum($total_tg); ?></td> -->
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
            $(function() {
                TablesDatatables.init();
            });
        });
    </script>
</div>
<!-- END Page Content -->