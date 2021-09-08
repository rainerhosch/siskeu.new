<style>
    .row-sugest {
        height: 30px;
    }

    div.suggesstion-boxz {
        background-color: lightblue;
        width: 110px;
        height: 110px;
        overflow: scroll;
    }
</style>
<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <div class="row" id="alert_alert">
        <?= $this->session->flashdata('message'); ?>
    </div>
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <a href="#" class="widget widget-hover-effect1" data-toggle="modal" data-target="#FormAktivasiDispen">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Aktivasi <strong>Mahasiswa</strong><br>
                        <small>Aktivasi Mahasiswa Yang Mengajukan Dispensasi</small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="#<?php echo base_url(); ?>dosen/administrasi/dispen_list/1" class="widget widget-hover-effect1 button_not_active">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-spring animation-fadeIn">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Dispen<strong> Perwalian</strong><br>
                        <small>Laporan data dispensasi perwalian</small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="#<?php echo base_url(); ?>dosen/administrasi/dispen_list/1" class="widget widget-hover-effect1 button_not_active">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-amethyst animation-fadeIn">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Dispen<strong> UTS</strong><br>
                        <small>Laporan data dispensasi perwalian</small>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="#<?php echo base_url(); ?>dosen/administrasi/dispen_list/1" class="widget widget-hover-effect1 button_not_active">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-fire animation-fadeIn">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        Dispen<strong> UAS</strong><br>
                        <small>Laporan data dispensasi perwalian</small>
                    </h3>
                </div>
            </a>
        </div>
    </div>
    <div class="modal fade" id="FormAktivasiDispen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Aktivasi Dispen Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" id="form_dispen">
                        <!-- <form action="<?php echo base_url(); ?>aktivasi-mahasiswa/aktif_manual" method="POST"> -->

                        <div class="form-group">
                            <label for="exampleInputEmail1">Tahun Akademik</label>
                            <input type="text" class="form-control" id="tahun_akademik" name="tahun_akademik" value="<?= $tahun_akademik; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Jenis Dispen</label>
                            <select class="form-control" name="jenis_dispen" id="jenis_dispen" required>
                                <option value="x">-- Pilih Jenis Dispen --</option>
                                <option value="1">PERWALIAN</option>
                                <option value="3">UTS</option>
                                <option value="4">UAS</option>
                            </select>
                        </div>

                        <div class="form-group" id="form_cari" hidden>
                            <label for="exampleInputEmail1">Cari Nim/Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="search-box" name="nipd_mhs" placeholder="Cari NIM/Nama Mahasiswa yang akan diaktifkan.">
                            <div id="suggesstion-box">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">NIM</label>
                                <input type="text" class="form-control" id="nipd" name="nipd" aria-describedby="emailHelp" placeholder="" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputNama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" aria-describedby="namaHelp" placeholder="" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputJurusan">Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" aria-describedby="jurusanHelp" placeholder="" readonly>
                            </div>
                            <div class="text-right" id="btn_save_data" style="margin-bottom: 5px;">
                                <button type="submit" class="btn btn-primary" id="btn_aktivasi" disabled>Tombol Aktivasi</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn_aktivasi" data-dismiss="modal" disabled>Tombol Aktivasi</button>
                </div> -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.button_not_active').on('click', function() {
            alert("Modul Belum Dapat Digunakan!");
        });
        setTimeout(function() {
            $("#alert_alert").html("");
        }, 2000);
        $("#jenis_dispen").on('change', function() {
            $("#nipd").val('');
            $("#nama").val('');
            $("#jurusan").val('');
            $('#btn_aktivasi').attr('disabled', true);
            $('#btn_aktivasi').html('Tombol Aktivasi');
            let jenis_dispen = $("#jenis_dispen").val();
            if (jenis_dispen != 'x') {
                $('#form_cari').attr('hidden', false);
            } else {
                $("#nipd").val('');
                $("#nama").val('');
                $("#jurusan").val('');
                $('#btn_aktivasi').attr('disabled', true);
                $('#btn_aktivasi').html('Tombol Aktivasi');
                $('#form_cari').attr('hidden', true);
            }
        });
        $("#search-box").on("keypress", function(e) {
            if (e.which == 13) {
                let nipd = $("#search-box").val();
                let jenis_dispen = $("#jenis_dispen").val();
                let tahun_akademik = $("#tahun_akademik").val();
                let str = '';
                if (jenis_dispen === '3') {
                    str = 'UTS';
                } else if (jenis_dispen === '4') {
                    str = 'UAS';
                } else {
                    str = 'Perwalian';
                }
                // console.log(jenis_dispen)
                $.ajax({
                    type: "POST",
                    url: "aktivasi-mahasiswa/cari_mhs",
                    data: {
                        nipd: nipd,
                        tahun_akademik: tahun_akademik,
                        jenis_dispen: jenis_dispen
                    },
                    dataType: "json",
                    success: function(response) {
                        // console.log(response)
                        if (response.status === 200) {
                            $("#search-box").val('');
                            $("#nipd").val(response.data.nipd);
                            $("#nama").val(response.data.nm_pd);
                            $("#jurusan").val(response.data.nm_jur);
                            $('#btn_aktivasi').attr('disabled', false);
                            $('#btn_aktivasi').html('Aktifkan ' + str);
                        } else {
                            $("#search-box").val('');
                            $("#nipd").val('');
                            $("#nama").val('');
                            $("#jurusan").val('');
                            $('#btn_aktivasi').attr('disabled', true);
                            $('#btn_aktivasi').html('Tombol Aktivasi');
                            $('#suggesstion-box').html('<span><code>' + response.msg + '</code></span>');
                            setTimeout(function() {
                                $("#suggesstion-box").html("");
                            }, 2000);
                        }
                    }
                });
            }
        });

        $('#form_dispen').submit(function() {
            // get all the inputs into an array.
            let $inputs = $('#form_dispen :input');
            let values = {};
            $inputs.each(function() {
                values[this.name] = $(this).val();
            });
            $.ajax({
                type: "POST",
                url: "aktivasi-mahasiswa/aktif_manual",
                data: values,
                dataType: "json",
                success: function(response) {
                    // console.log(response)
                    if (response === true) {
                        swal.fire("Sukses!", `Aktivasi Dispen Berhasil.`, "success");
                        $('.swal2-confirm').click(function() {
                            location.reload();
                        });
                    } else if (response = 'exist') {
                        swal.fire("Sukses!", `Aktivasi Dispen Mahasiswa Tersebut Sudah Ada.`, "success");
                        $('.swal2-confirm').click(function() {
                            location.reload();
                        });
                    } else {
                        swal.fire("Gagal!", `Aktivasi Dispen Gagal.`, "error");
                        $('.swal2-confirm').click(function() {
                            location.reload();
                        });
                    }
                }
            })

        });

    });
</script>
</div>
<!-- END Page Content -->