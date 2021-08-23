<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <?php if ($this->session->flashdata('success')) {
        echo '<div class="alert alert-success" role="alert">' . $this->session->flashdata('success') . '</div>';
    } elseif ($this->session->flashdata('error')) {
        echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata('error') . '</div>';
    }
    ?>
    <div class="block">
        <div class="block-title">
            <h2><?= $page; ?></h2>
        </div>
        <button type="button" class="btn btn-primary btnAdd" data-toggle="modal" data-target="#addData">
            Tambah Baru
        </button>
        <!-- Example Content -->
        <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Pembayaran</th>
                    <th class="text-center">Biaya</th>
                    <th class="text-center">Potongan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="biayalainnya_tbody">
                <!-- Load Data by Ajax -->
            </tbody>
        </table>
    </div>


    <!-- modal -->
    <div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-warning" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title white-text w-100 font-weight-bold py-2">Tambah Biaya Angkatan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/masterdata'); ?>/insertBiayaLainnya" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="nm_jp">Nama Pembayaran</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="nm_jp" name="nm_jp" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="biaya">Biaya</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="biaya" name="biaya" class="form-control validate">
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-center text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal add-->



    <!-- modal edit -->
    <div class="modal" tabindex="-1" role="dialog" id="editDataBiaya">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Biaya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/masterdata'); ?>/UpdateBiayaLainnya" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_nm_jp">Nama Pembayaran</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_nm_jp" name="edit_nm_jp" class="form-control validate">
                                <input type="hidden" id="edit_id_jp" name="edit_id_jp" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_biaya">Biaya</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_biaya" name="edit_biaya" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_potongan_biaya">Potongan</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_potongan_biaya" name="edit_potongan_biaya" class="form-control validate">
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
    <!-- end modal edit -->

    <!-- Delete Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="hapusDataLainnya">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content2">
                <div class="modal-body">
                    <form action="<?= base_url('masterdata'); ?>/deleteDataPembayaranLain" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="hapus_id_biaya" id="hapus_id_biaya">
                        <h1>Hapus Data Lainnya</h1>
                        <p>Apakah anda yakin, ingin menghapus data tersebut?</p>

                        <div class="clearfix text-right">
                            <button type="button" onclick="document.getElementById('hapusDataLainnya').style.display='none'" class="btn btn-warning">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete Modal -->


    <script>
        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: 'GetAllBiayaLainnya',
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    let html = ``;
                    $.each(response, function(i, value) {
                        i++;
                        html += `<tr>`;
                        html += `<td class="text-center">${i}</td>`;
                        html += `<td class="text-center"><strong>${value.nm_jp}</strong></td>`;
                        html += `<td class="text-center"><i>Rp.${parseInt(value.biaya).toLocaleString()}</i></td>`;
                        html += `<td class="text-center"><i>Rp.${parseInt(value.potongan_biaya).toLocaleString()}</i></td>`;
                        html += `<td class="text-center">` +
                            `<a href="#" class="badge badge-warning edit-biaya" id="btn_edit_biaya" value="${value.id_jp}"><i class="far fa-edit"></i></a> | ` +
                            `<a href="#" onclick="document.getElementById('hapusDataLainnya').style.display='block'" class="badge badge-danger btn-hapus" id="btn_hapus_biaya" value="${value.id_jp}"><i class="fas fa-trash-alt"></i></a>` +
                            `</td>`;
                        html += `</tr>`;
                    });
                    $("#biayalainnya_tbody").html(html);
                    // $(function() {
                    //     TablesDatatables.init();
                    // });
                }
            });

            $(this).on("click", "#btn_hapus_biaya", function(e) {
                e.preventDefault();
                let id_jp = $(this).attr("value");
                // console.log(id_jp);
                if (id_jp == "") {
                    alert("Error in id");
                } else {
                    $("#hapus_id_biaya").val(id_jp);
                }
            });

            $(this).on("click", "#btn_edit_biaya", function(e) {
                e.preventDefault();
                let id_jp = $(this).attr("value");
                if (id_jp == "") {
                    alert("Error in id");
                } else {
                    $.ajax({
                        type: "post",
                        url: "GetAllBiayaLainnya",
                        data: {
                            id_jp: id_jp,
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            $("#editDataBiaya").modal("show");
                            $("#edit_id_jp").val(response.id_jp);
                            $("#edit_nm_jp").val(response.nm_jp);
                            $("#edit_biaya").val(response.biaya);
                            $("#edit_potongan_biaya").val(response.potongan_biaya);
                        }
                    });
                }
            });
        });
    </script>
</div>
<!-- END Page Content -->