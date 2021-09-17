<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <div class="row" id="alert_tg">
        <?= $this->session->flashdata('message'); ?>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="row data_historiTX">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Tabel</strong> <?= $page; ?></h2>
                        </div>
                        <button class="btn btn-sm btn-primary btn_add_tg" style="margin-bottom: 5px;">Tambah Data Tunggakan</button>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal add -->
    <div class="modal fade" id="addTunggakan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-warning" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header text-center">
                    <h4 class="modal-title white-text w-100 font-weight-bold py-2">Tambah Tunggakan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <form action="#" id="form_add_tg">
                        <div class="md-form mb-5 row" id="row_nim_add">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nim_add">Nim</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nim_add" name="nim_add" class="form-control validate">
                                <span id="notif_search2"></span>
                            </div>
                        </div>
                        <div class="md-form mb-5 row" id="row_nama_add">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nm_mhs_add">Nama</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nm_mhs_add" name="nm_mhs_add" class="form-control validate" readonly>
                                <!-- <input type="hidden" id="nim_mhs_add_hd" name="nim_mhs_add_hd" class="form-control validate"> -->
                            </div>
                        </div>
                        <div class="md-form row" id="row_jns_tg" hidden>
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="jns_tg">Jenis Tunggakan</label>
                            </div>
                            <div class="col-md-9">
                                <select id="jns_tg" name="jns_tg" class="select-select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option value="x">-- Pilih Jenis Tunggakan --</option>
                                </select>
                            </div>
                        </div>

                        <div class="md-form mb-5 row" id="row_jml_tg" hidden>
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="jml_tg_add">Jumlah Tunggakan</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="jml_tg_add" name="jml_tg_add" class="form-control validate">
                            </div>
                        </div>
                </div>

                <!--Footer-->
                <div class="modal-footer justify-content-center text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn_save_add" disabled>Simpan</button>
                    </form>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!-- end modal add -->


    <!-- modal edit -->
    <div class="modal" tabindex="-1" role="dialog" id="editTunggakan">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tunggakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('laporan'); ?>/updateTunggakan" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="id_tunggakan" id="id_tunggakan">
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nim_mhs">NIM</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nim_mhs" name="nim_mhs" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nm_mhs">Nama</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nm_mhs" name="nm_mhs" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nm_jur">Jurusan</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nm_jur" name="nm_jur" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nama_tunggakan">Jenis Tunggakan</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nama_tunggakan" name="nama_tunggakan" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="jml_tunggakan">Jumlah</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="jml_tunggakan" name="jml_tunggakan" class="form-control validate" placeholder="Masukan Jumlah Tunggakan">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#alert_tg").html("");
            }, 3000);

            $('.btn_add_tg').on('click', function() {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>masterdata/getAllJenisPembayaran",
                    dataType: "json",
                    success: function(response) {
                        $('#addTunggakan').modal('show');
                        // console.log(response)
                        $.each(response, function(i, val) {
                            $('#jns_tg').append($('<option>', {
                                value: val.id_jp,
                                text: val.nm_jp
                            }));
                        });

                    }
                });
            });

            $('#nim_add').on("keypress", function(e) {
                $('#nm_mhs_add').val('');
                $('#row_jns_tg').prop('hidden', true);
                if (e.which == 13) {
                    let nipd = $('#nim_add').val();
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url() ?>aktivasi-mahasiswa/cari_mhs",
                        data: {
                            nipd: nipd,
                        },
                        serverside: true,
                        dataType: "json",
                        success: function(response) {
                            // console.log(response)
                            if (response.data != null) {
                                $('#nm_mhs_add').val(response.data.nm_pd);
                                // $('#nim_mhs_add_hd').val(response.data.nipd)
                                $('#row_jns_tg').prop('hidden', false);
                            } else {
                                $("#notif_search2").html(
                                    `<code>${response.msg}</code>`
                                );
                                setTimeout(function() {
                                    $("#notif_search2").html("");
                                }, 2000);
                            }
                        }
                    });
                }
            });

            $('#jns_tg').on('change', function() {
                let jenis = $('#jns_tg').val();
                if (jenis != 'x') {
                    $('#row_jml_tg').prop('hidden', false);
                } else {
                    $('#row_jml_tg').prop('hidden', true);
                }
            });

            $('#jml_tg_add').on('change', function() {
                let jml = $('#jml_tg_add').val();
                if (jml != '') {
                    $('.btn_save_add').prop('disabled', false);
                } else {
                    $('.btn_save_add').prop('disabled', true);
                }
            });

            $('.btn_save_add').on('click', function() {
                // get all the inputs into an array.
                let $inputs = $('#form_add_tg :input');
                let values = {};
                $inputs.each(function() {
                    values[this.name] = $(this).val();
                });
                $.ajax({
                    type: "POST",
                    url: "addTunggakan",
                    data: values,
                    dataType: "json",
                    success: function(response) {
                        if (response.status == true) {
                            swal.fire("Success!", response.msg, "success");
                            $('.swal2-confirm').click(function() {
                                location.reload();
                            });
                        } else {
                            swal.fire("Error!", response.msg, "error");
                            $('.swal2-confirm').click(function() {
                                location.reload();
                            });
                        }
                    }
                });
            });



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
                            html += `<a href="#" class="btn btn-xs btn-warning btn_edit_tg" id="btn_edit_${tg.id_tunggakan}">Edit</a> | <a href="#"  onclick="deleteTunggakan(${tg.id_tunggakan},'${tg.nm_pd}')" class="btn btn-xs btn-danger btn-hapus-tg" value="${tg.nm_pd}">Hapus</a>`;
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
                    $.each(response.tunggakan, function(i, tg) {
                        $('#btn_edit_' + tg.id_tunggakan).on('click', function() {
                            let id_tg = tg.id_tunggakan;
                            $.ajax({
                                type: "POST",
                                url: 'getDataTunggakan',
                                data: {
                                    id_tg: id_tg
                                },
                                dataType: "json",
                                success: function(response) {
                                    $('#editTunggakan').modal('show');
                                    $('#id_tunggakan').val(response.tunggakan.id_tunggakan);
                                    $('#nim_mhs').val(response.tunggakan.nim);
                                    $('#nm_mhs').val(response.tunggakan.nm_pd);
                                    $('#nm_jur').val(response.tunggakan.nm_jur);
                                    $('#nama_tunggakan').val(response.tunggakan.nm_jenis_pembayaran);
                                    $('#jml_tunggakan').val(response.tunggakan.jml_tunggakan);
                                    // console.log(response)
                                }
                            })
                        });
                    });

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