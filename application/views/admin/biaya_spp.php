<!-- Page content -->
<style>
    .dataTables_wrapper {
        margin-bottom: 10px;
    }
</style>
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
        <hr>
        <button type="button" class="btn btn-primary btnAdd" data-toggle="modal" data-target="#addData">
            Tambah Biaya Baru
        </button>
        <!-- <button type="button" class="btn btn-danger btnEditPotongan" data-toggle="modal" data-target="#addEditPotongan">
            Potongan Biaya
        </button> -->
        <!-- Example Content -->
        <table id="example-datatable" class="table table-vcenter table-condensed table-bordered mb-5">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Angkatan</th>
                    <th class="text-center">Biaya Bangunan S1</th>
                    <th class="text-center">Biaya Bangunan D3</th>
                    <th class="text-center">Biaya Kemahasiswaan</th>
                    <th class="text-center">Biaya Semester S1</th>
                    <th class="text-center">Biaya Semester D3</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="biayaspp_tbody">
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
                    <form action="<?= base_url('masterdata'); ?>/insertBiayaSpp" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="tahun_angkatan">Tahun Angkatan</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="tahun_angkatan" name="tahun_angkatan" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="biaya_bangunan">Uang Bangunan</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="biaya_bangunan" name="biaya_bangunan" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="biaya_bangunan_D3">Uang Bangunan D3</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="biaya_bangunan_D3" name="biaya_bangunan_D3" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="biaya_CS">Cicilan Semester S1</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="biaya_CS" name="biaya_CS" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="biaya_CS_D3">Cicilan Semester D3</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="biaya_CS_D3" name="biaya_CS_D3" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="biaya_kmhs">Kemahasiswaan</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="biaya_kmhs" name="biaya_kmhs" class="form-control validate">
                            </div>
                        </div>
                        <!-- <div class="md-form row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="biaya_kmhs_D3">Kemahasiswaan D3</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="biaya_kmhs_D3" name="biaya_kmhs_D3" class="form-control validate">
                            </div>
                        </div> -->
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
    <div class="modal fade" tabindex="-1" role="dialog" id="editDataBiaya">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Biaya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/masterdata'); ?>/UpdateBiayaSpp" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_tahun_angkatan">Tahun Angkatan</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="edit_tahun_angkatan" name="edit_tahun_angkatan" class="form-control validate">
                                <input type="hidden" id="id_biaya" name="id_biaya" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_biaya_bangunan">Uang Bangunan</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_biaya_bangunan" name="edit_biaya_bangunan" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_biaya_bangunan_D3">Uang Bangunan D3</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_biaya_bangunan_D3" name="edit_biaya_bangunan_D3" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_biaya_CS">Cicilan Semester S1</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_biaya_CS" name="edit_biaya_CS" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_biaya_CS_D3">Cicilan Semester D3</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_biaya_CS_D3" name="edit_biaya_CS_D3" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_biaya_kmhs">Kemahasiswaan</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_biaya_kmhs" name="edit_biaya_kmhs" class="form-control validate">
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


    <!-- modal edit potongan -->
    <div class="modal fade" id="addEditPotongan" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Potongan Biaya CS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/masterdata'); ?>/UpdatePotonganBiayaCS" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_pot_c1">Cicilan Ke-1</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_pot_c1" name="edit_pot_c1" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_pot_c2">Cicilan Ke-2</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_pot_c2" name="edit_pot_c2" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form row">
                            <div class="col-md-5">
                                <label data-error="wrong" data-success="right" for="edit_pot_c3">Cicilan Ke-3</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="edit_pot_c3" name="edit_pot_c3" class="form-control validate">
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
    <!-- end edit potongan -->


    <!-- Delete Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="hapusDataSpp">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content2">
                <div class="modal-body">
                    <form action="<?= base_url('masterdata'); ?>/deleteDataSpp" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="hapus_id_biaya" id="hapus_id_biaya">
                        <h1>Hapus Data Spp</h1>
                        <p>Apakah anda yakin, ingin menghapus data spp tersebut?</p>

                        <div class="clearfix text-right">
                            <button type="button" onclick="document.getElementById('hapusDataSpp').style.display='none'" class="btn btn-warning">Batal</button>
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
                url: 'GetBiayaSPP',
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    let html = ``;
                    $.each(response, function(i, value) {
                        i++;
                        html += `<tr>`;
                        html += `<td class="text-center">${i}</td>`;
                        html += `<td class="text-center"><strong>${value.angkatan}</strong></td>`;
                        html += `<td class="text-center"><i>Rp.${parseInt(value.PK).toLocaleString()}</i></td>`;
                        html += `<td class="text-center"><i>Rp.${parseInt(value.PK_D3).toLocaleString()}</i></td>`;
                        html += `<td class="text-center"><i>Rp.${parseInt(value.kmhs).toLocaleString()}</i></td>`;
                        html += `<td class="text-center"><i>Rp.${parseInt(value.CS).toLocaleString()}</i></td>`;
                        html += `<td class="text-center"><i>Rp.${parseInt(value.CS_D3).toLocaleString()}</i></td>`;
                        html += `<td class="text-center">` +
                            `<a href="#" class="badge badge-warning edit-biaya" id="btn_edit_biaya" value="${value.id_biaya}"><i class="far fa-edit"></i></a>|` +
                            `<a href="#" onclick="document.getElementById('hapusDataSpp').style.display='block'" class="badge badge-danger btn-hapus" id="btn_hapus_biaya" value="${value.id_biaya}"><i class="fas fa-trash-alt"></i></a>` +
                            `</td>`;
                        html += `</tr>`;
                    })
                    $("#biayaspp_tbody").html(html);
                    $(function() {
                        TablesDatatables.init();
                    });
                }
            });

            $("#tahun_angkatan").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });

            $(this).on("click", "#btn_hapus_biaya", function(e) {
                e.preventDefault();
                let id_biaya = $(this).attr("value");
                if (id_biaya == "") {
                    alert("Error in id");
                } else {
                    $("#hapus_id_biaya").val(id_biaya);
                }
            });

            $(this).on("click", "#btn_edit_biaya", function(e) {
                e.preventDefault();
                let id_biaya = $(this).attr("value");
                if (id_biaya == "") {
                    alert("Error in id");
                } else {
                    $.ajax({
                        type: "post",
                        url: "GetBiayaSPP",
                        data: {
                            id_biaya: id_biaya,
                        },
                        dataType: "json",
                        success: function(response) {
                            // console.log(response);
                            $("#editDataBiaya").modal("show");
                            $("#id_biaya").val(response.id_biaya);
                            $("#edit_tahun_angkatan").val(response.angkatan);
                            $("#edit_biaya_bangunan").val(response.PK);
                            $("#edit_biaya_bangunan_D3").val(response.PK_D3);
                            $("#edit_biaya_CS").val(response.CS);
                            $("#edit_biaya_CS_D3").val(response.CS_D3);
                            $("#edit_biaya_kmhs").val(response.kmhs);
                        }
                    });
                }
            });

            $('.btnEditPotongan').on("click", function() {
                let id_potongan = 1;
                $.ajax({
                    type: "post",
                    url: "GetPotonganBiayaSPP",
                    data: {
                        id_potongan: id_potongan,
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        // $("#editDataBiaya").modal("show");
                        $("#edit_pot_c1").val(response.potongan_C1);
                        $("#edit_pot_c2").val(response.potongan_C2);
                        $("#edit_pot_c3").val(response.potongan_C3);
                    }
                });
            });


        });
    </script>
</div>
<!-- END Page Content -->