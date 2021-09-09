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
                        <small>Input Pengajukan Dispensasi</small>
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
                        Laporan Dispen<strong> Perwalian</strong><br>
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
                        Laporan Dispen<strong> UTS</strong><br>
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
                        Laporan Dispen<strong> UAS</strong><br>
                        <small>Laporan data dispensasi perwalian</small>
                    </h3>
                </div>
            </a>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <div class="row data_dispen">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Dispen Mahasiswa</h2>
                        </div>
                        <div class="table-responsive">
                            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Tgl Pengajuan</th>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Rincian Tagihan</th>
                                        <th class="text-center">Total Tagihan</th>
                                        <th class="text-center">Tgl Perjanjian Pelunasan</th>
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">No Tlp</th>
                                        <th class="text-center">Tools</th>
                                    </tr>
                                </thead>
                                <tbody id="data_dispen_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
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
                        <div class="form-group input-group-sm">
                            <label for="exampleInputEmail1">Tahun Akademik</label>
                            <input type="text" class="form-control" id="tahun_akademik" name="tahun_akademik" value="<?= $tahun_akademik; ?>" readonly>
                        </div>

                        <div class="form-group input-group-sm">
                            <label for="exampleInputJurusan">Tanggal Pelunasan</label>
                            <input type="date" class="form-control" id="tgl_pelunasan" name="tgl_pelunasan" aria-describedby="tgl_pelunasanHelp" placeholder="">
                        </div>
                        <div class="form-group input-group-sm" id="jenis_dispen_form" hidden>
                            <label for="exampleInputEmail1">Jenis Tunggakan</label>
                            <select class="form-control" name="jenis_dispen" id="jenis_dispen" required>
                                <option value="x">-- Pilih Jenis Dispen --</option>
                                <option value="1">Ciciclan 1 | PERWALIAN</option>
                                <option value="3">Ciciclan 2 | UTS</option>
                                <option value="4">Ciciclan 3 | UAS</option>
                            </select>
                        </div>
                        <div class="form-group input-group-sm" id="form_cari" hidden>
                            <label for="exampleInputEmail1">Cari Nim</label>
                            <input type="text" class="form-control form_input_data" id="search-box" name="nipd_mhs" placeholder="Cari NIM.">
                            <div id="suggesstion-box">
                            </div>
                        </div>
                        <div id="form_data_mhs" hidden>
                            <div class="form-group input-group-sm">
                                <label for="exampleInputEmail1">NIM</label>
                                <input type="text" class="form-control form_input_data" id="nipd" name="nipd" aria-describedby="emailHelp" placeholder="" readonly>
                            </div>
                            <div class="form-group input-group-sm">
                                <label for="exampleInputNama">Nama</label>
                                <input type="text" class="form-control form_input_data" id="nama" name="nama" aria-describedby="namaHelp" placeholder="" readonly>
                                <input type="hidden" class="form-control form_input_data" id="id_reg_pd" name="id_reg_pd" aria-describedby="namaHelp" placeholder="" readonly>
                                <input type="hidden" class="form-control form_input_data" id="id_jur" name="id_jur" aria-describedby="namaHelp" placeholder="" readonly>
                            </div>
                            <div class="form-group input-group-sm">
                                <label for="exampleInputJurusan">Jurusan</label>
                                <input type="text" class="form-control form_input_data" id="jurusan" name="jurusan" aria-describedby="jurusanHelp" placeholder="" readonly>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="exampleInputTunggakan">Tunggakan Semester Lalu</label>
                                <input type="text" class="form-control form_input_data" id="tg_smt_lalu" name="tg_smt_lalu" aria-describedby="tg_smt_laluHelp" placeholder="" readonly>
                            </div>
                            <div class="form-group input-group-sm">
                                <label for="exampleInputTunggakan" id="lbl_tg_dispen"></label>
                                <input type="text" class="form-control form_input_data" id="tg_dispen" name="tg_dispen" aria-describedby="tg_dispenHelp" placeholder="" readonly>
                            </div>
                            <div class="form-group input-group-sm">
                                <label for="exampleInput">No Tlp Mahasiswa</label>
                                <input type="text" class="form-control form_input_data" id="no_tlp" name="no_tlp" aria-describedby="tg_dispenHelp" placeholder="">
                            </div>
                            <div class="text-right" id="btn_save_data" style="margin-bottom: 5px;">
                                <button type="button" class="btn btn-primary" id="btn_aktivasi" disabled>Tombol Aktivasi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal Dispen -->
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "aktivasi-mahasiswa/get_data_dispen_mhs",
                dataType: "json",
                success: function(response) {
                    console.log(response.data)
                    html = ``;
                    if (response.data != 0) {
                        $.each(response.data, function(i, value) {
                            i++;
                            var total_Tagihan = 0;
                            let no_tlp = value.no_tlp;
                            let format_no = '0' + no_tlp.substring(2, 11);
                            html += `<tr>`;
                            html += `<td class = "text-center" >${i}</td>`;
                            html += `<td class = "text-center" >${value.tanggal_input}</td>`;
                            html += `<td class = "text-center" >${value.nipd}</td>`;
                            html += `<td class = "text-center" >${value.nm_pd}</td>`;
                            html += `<td class = "text-center" >`;
                            $.each(value.rincian, function(j, val) {
                                html += `<i style="font-size:1rem; font-weight: bold;">${val.label}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.jumlah).toLocaleString()}</i><br>`;
                                total_Tagihan += parseInt(val.jumlah);
                            });
                            html += `</td>`;
                            html += `<td class = "text-center"><i>Rp.${parseInt(total_Tagihan).toLocaleString()}</i></td>`;
                            html += `<td class = "text-center" >${value.tanggal_lunas}</td>`;
                            html += `<td class = "text-center" >${value.tahun_akademik}</td>`;
                            html += `<td class = "text-center" >${format_no}</td>`;
                            html += `<td class = "text-center" ><a target="blank" onclick="window.open('https://wa.me/${value.no_tlp}?text=Saudara%20${value.nm_pd}, %0AMohon%20untuk%20segera%20melunasi%20tagihan%20semester%20perkuliahan.%0AKarena%20sudah%20melawati%20tanggal%20perjanjian%20pelunasan%20yaitu%20%28${value.tanggal_lunas}%29.%20Adapun%20nominal%20pembayarannya%20Rp.${parseInt(total_Tagihan).toLocaleString()}.%0ATerima%20Kasih', '_blank');" class="btn btn-xs btn-success">Chat WA</a></td>`;
                            // html += `<td class = "text-center" ><a target="blank" onclick="window.open('https://wa.me/${value.no_tlp}', '_blank');" class="btn btn-xs btn-success">Chat WA</a> | <a class="btn btn-xs btn-danger">Hapus</a></td>`;
                            html += `</tr>`;
                        });
                    } else {
                        html += `<tr>`;
                        html += `<td colspan="12" class="text-center"><br>`;
                        html += `<div class='col-lg-12'>`;
                        html += `<div class='alert alert-danger alert-dismissible'>`;
                        html += `<h4><i class='icon fa fa-warning'></i> Data Kosong!</h4>`;
                        html += `</div>`;
                        html += `</div>`;
                        html += `</td>`;
                        html += `</tr>`;
                    }
                    $("#data_dispen_tbody").html(html);
                    $(function() {
                        TablesDatatables.init();
                    });
                }
            })






            $('.button_not_active').on('click', function() {
                alert("Modul Belum Dapat Digunakan!");
            });
            setTimeout(function() {
                $("#alert_alert").html("");
            }, 2000);
            $('#tgl_pelunasan').on('change', function() {
                let tgl_pelunasan = $("#tgl_pelunasan").val();
                if (tgl_pelunasan != '') {
                    $("#jenis_dispen_form").attr('hidden', false);
                } else {
                    $(".form_input_data").val('');
                    $("#jenis_dispen_form").attr('hidden', true);
                    $('#form_cari').attr('hidden', true);
                    $('#form_data_mhs').attr('hidden', true);
                    $('#btn_aktivasi').attr('disabled', true);
                    $('#btn_aktivasi').html('Tombol Aktivasi');
                }
            });
            $("#jenis_dispen").on('change', function() {
                $(".form_input_data").val('');
                $('#btn_aktivasi').attr('disabled', true);
                $('#btn_aktivasi').html('Tombol Aktivasi');
                let jenis_dispen = $("#jenis_dispen").val();
                // console.log(jenis_dispen)
                if (jenis_dispen != 'x') {
                    $('#form_cari').attr('hidden', false);
                } else {
                    $(".form_input_data").val('');
                    $('#btn_aktivasi').attr('disabled', true);
                    $('#form_cari').attr('hidden', true);
                    $('#form_data_mhs').attr('hidden', true);
                    $('#btn_aktivasi').html('Tombol Aktivasi');
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
                            console.log(response)
                            if (response.status === 200) {
                                $('#form_data_mhs').attr('hidden', false);
                                $("#search-box").val('');
                                $("#nipd").val(response.data.nipd);
                                $("#nama").val(response.data.nm_pd);
                                $("#id_reg_pd").val(response.data.id_pd);
                                $("#id_jur").val(response.data.id_jur);
                                $("#jurusan").val(response.data.nm_jur);
                                $("#tg_smt_lalu").val(response.data.tg_smt_lalu);
                                $("#tg_dispen").val(response.data.pengajuan_dispen);
                                $('#lbl_tg_dispen').html('Sisa ' + response.data.nm_kewajiban + ' Semester Ini');
                                $('#btn_aktivasi').attr('disabled', false);
                                $('#btn_aktivasi').html('Aktifkan ' + str);
                            } else {
                                $("#search-box").val('');
                                $(".form_input_data").val('');
                                $('#btn_aktivasi').attr('disabled', true);
                                $('#btn_aktivasi').html('Tombol Aktivasi');
                                if (response.data === null) {
                                    swal.fire("Error!", response.msg, "error");
                                    $('.swal2-confirm').click(function() {
                                        location.reload();
                                    });
                                } else {
                                    swal.fire("Info!", response.msg, "info");
                                    $('.swal2-confirm').click(function() {
                                        location.reload();
                                    });
                                }
                            }
                        }
                    });
                }
            });

            $('#btn_aktivasi').on('click', function() {
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
                        if (response.status === true) {
                            swal.fire("Sukses!", `Aktivasi Dispen Berhasil.`, "success");
                            $('.swal2-confirm').click(function() {
                                location.reload();
                            });
                        } else if (response.status === false && response.msg === 'exist') {
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