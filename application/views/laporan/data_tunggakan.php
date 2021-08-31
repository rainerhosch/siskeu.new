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
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tunggakan_tbody">
                                    <!-- <?php foreach ($tunggakan as $i => $tg) :
                                                $total_tg[] = $tg['jml_tunggakan'];
                                                $i++; ?>
                                        <tr>
                                            <td class="text-center"><?= $i ?></td>
                                            <td class="text-center"><?= $tg['nipd']; ?></td>
                                            <td class="text-center"><?= $tg['nm_pd']; ?></td>
                                            <td class="text-center"><?= $tg['nm_jur']; ?></td>
                                            <td class="text-center"><?= $tg['nm_jenis_pembayaran']; ?></td>
                                            <td class="text-center"><?= number_format($tg['jml_tunggakan']); ?></td>
                                            <td class="text-center">
                                                <a href="#" class="btn-sm btn-danger btn-hapus" id="btn_hapus_biaya" value="<?= $tg['id_tunggakan']; ?>"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?> -->
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
                url: 'getDataTunggakan',
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    let html = ``;
                    if (response.tunggakan != 0) {
                        $.each(response.tunggakan, function(i, tg) {
                            i++;
                            html += `<tr>`;
                            html += `<td class = "text-center" >${i}</td>`;
                            html += `<td class = "text-center" >${tg.nipd}</td>`;
                            html += `<td class = "text-center" >${tg.nm_pd}</td>`;
                            html += `<td class = "text-center" >${tg.nm_jur}</td>`;
                            html += `<td class = "text-center" >${tg.nm_jenis_pembayaran}</td>`;
                            html += `<td class = "text-center" >${parseInt(tg.jml_tunggakan).toLocaleString()}</td>`;
                            html += `<td class="text-center">`;
                            html += `<a href="#"  onclick="deleteTunggakan(${tg.id_tunggakan},'${tg.nm_pd}')" class="btn btn-xs btn-danger btn-hapus-tg" value="${tg.nm_pd}">Hapus</a>`;
                            html += `</td>`;
                            html += `</tr>`;
                        });
                    } else {
                        html += `<tr>`;
                        html += `<td colspan="12" class="text-center"><br>`;
                        html += `<div class='col-lg-12'>`;
                        html += `<div class='alert alert-danger alert-dismissible'>`;
                        html += `<h4><i class='icon fa fa-warning'></i> Tidak Ada Data Tunggakan!</h4>`;
                        html += `</div>`;
                        html += `</div>`;
                        html += `</td>`;
                        html += `</tr>`;
                    }
                    $("#tunggakan_tbody").html(html);
                    $(function() {
                        TablesDatatables.init();
                    });
                }
            });
        });

        function deleteTunggakan(id_tunggakan, nm_pd) {
            swal.fire({
                title: "Hapus Tunggakan",
                text: `Apakah anda ingin menghapus data tunggakan ${nm_pd}?.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Hapus",
            }).then(result => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: 'hapus_tunggakan',
                        data: {
                            id_tg: id_tunggakan
                        },
                        dataType: "json",
                        success: function(response) {
                            // console.log(response)
                            if (response.status === true) {
                                swal.fire("Deleted!", `Tunggakan ${nm_pd}, ${response.msg}`, "success");
                                $('.swal2-confirm').click(function() {
                                    location.reload();
                                });
                            } else {
                                swal.fire("Error!", `${response.msg}`, "error");
                                $('.swal2-confirm').click(function() {
                                    location.reload();
                                });
                            }
                        }
                    })
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swal.fire("Pembatalan", `Tunggakan ${nm_pd}, tidak dihapus.`, "error");
                    $('.swal2-confirm').click(function() {
                        location.reload();
                    });
                }
            });
        }
    </script>
</div>
<!-- END Page Content -->