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
        <div class="col-sm-12">
            <div class="row data_dispen">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Dispen Mahasiswa</h2>
                        </div>
                        <!-- <div class="row" style="margin-bottom: 5px;">
                            <button class="btn btn-sm btn-info" id="btn_aktivasi_kip">Aktivasi KIP</button>
                        </div> -->

                        <div class="row">
                            <div class="table-responsive">
                                <table id="example-datatable"
                                    class="table table-vcenter table-condensed table-bordered">
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
                                            <th class="text-center">Jenis Dispen</th>
                                            <th class="text-center">No Tlp</th>
                                            <th class="text-center">Pemberitahuan</th>
                                            <th class="text-center">Status</th>
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
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajax({
                type: "GET",
                url: `<?= base_url('aktivasi-mahasiswa/get_data_dispen_mhs'); ?>`,
                dataType: "json",
                success: function (response) {
                    $('.mhs_lunas_label span').text(response.mhs_lunas);
                    $('.mhs_belum_lunas_label span').text(response.mhs_belum_lunas);
                    console.log(response)
                    html = ``;
                    if (response.data != 0) {
                        $.each(response.data, function (i, value) {
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
                            $.each(value.rincian, function (j, val) {
                                html += `<i style="font-size:1rem; font-weight: bold;">${val.label}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.jumlah).toLocaleString()}</i><br>`;
                                total_Tagihan += parseInt(val.jumlah);
                            });
                            html += `</td>`;
                            html += `<td class="text-center"><i>Rp.${parseInt(total_Tagihan).toLocaleString()}</i></td>`;
                            html += `<td class="text-center" >${value.tgl_janji_lunas}</td>`;
                            html += `<td class="text-center" >${value.tahun_akademik}</td>`;
                            // html += `<td class="text-center" >${value.jenis_dispen}</td>`;
                            if (value.jenis_dispen != 1) {
                                if (value.jenis_dispen != 3) {
                                    html += `<td class="text-center" >UAS</td>`;
                                } else {
                                    html += `<td class="text-center" >UTS</td>`;
                                }
                            } else {
                                html += `<td class="text-center" >PERWALIAN</td>`;
                            }
                            html += `<td class="text-center" >${format_no}</td>`;
                            html += `<td class="text-center" >`;
                            html += `<i style="font-size:1.5rem; font-weight: bold;">${value.jml_kirim_pesan}<i><br>`;
                            html += `<i style="font-size:1rem; font-weight: bold;">Pesan Terkirim<i>`;
                            html += `</td>`;
                            if (value.status == 1) {
                                html += `<td class="text-center">`;
                                html += `<i style="font-size:1rem; font-weight: bold;">Sudah Bayar</i><br>`
                                html += `<i style="font-size:1rem; font-weight: bold;">Tgl : ${value.tgl_pelunasan}</i>`;
                                html += `</td>`;
                            } else {
                                html += `<td class="text-center">`;
                                html += `<i style="font-size:1rem; font-weight: bold;">Belum Dibayar</i>`
                                html += `</td>`;
                            }

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

                    $('.btn_edit').on('click', function () {
                        let id_dispen_edit = $(this).attr("id-Edit")
                        console.log(id_dispen_edit);
                        $.ajax({
                            type: "POST",
                            url: "get_data_dispen_by_id",
                            dataType: "json",
                            data: {
                                id_dispen: id_dispen_edit
                            },
                            success: function (response) {
                                if (response.status === true) {
                                    $('#FormEditDispen').modal('show');
                                    $('#tgl_pelunasan_edit').val(response.data.tgl_janji_lunas);
                                    $('#jenis_dispen_edit').val(response.data.jenis_dispen);
                                    $("#nipd_edit").val(response.data.nipd);
                                    $("#nama_edit").val(response.data.nm_pd);
                                    $("#id_dispen_edit").val(response.data.id_dispensasi);
                                    $("#id_reg_pd_edit").val(response.data.id_pd);
                                    $("#id_jur_edit").val(response.data.id_jur);
                                    $("#jurusan_edit").val(response.data.nm_jur);
                                    $("#tg_dispen_edit").val(response.data.tg_dispen);
                                    $("#no_tlp_edit").val(response.data.no_tlp);
                                    console.log(response.data);
                                    // location.reload();
                                } else {
                                    location.reload();
                                }
                            }
                        });
                    });
                    $('.btn_delete').on('click', function () {
                        let id_dispen_delete = $(this).attr("id-Delete")
                        let nm_pd = $(this).data("nama")
                        // console.log(id_dispen_delete);
                        Swal.fire({
                            title: 'Hapus data dispen?',
                            text: `data dispen ${nm_pd} akan dihapus.`,
                            showCancelButton: true,
                            confirmButtonText: 'Delete',
                            confirmButtonColor: '#d30000',
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                // Swal.fire('Saved!', '', 'success')
                                $.ajax({
                                    type: "POST",
                                    url: "delete_data_dispen",
                                    dataType: "json",
                                    data: {
                                        id_dispen: id_dispen_delete
                                    },
                                    success: function (response) {
                                        if (response.status === true) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: response.msg,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(
                                                location.reload()
                                            );
                                        } else {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: response.msg,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(
                                                location.reload()
                                            );
                                        }
                                    }
                                });
                            } else {
                                Swal.fire('Data tidak dihapus', '', 'info')
                                // location.reload();
                            }
                        })
                    });
                    $('.btn_WA').on('click', function () {
                        let id_dispen = $(this).attr("id-dispen")
                        // console.log(id_dispen);
                        $.ajax({
                            type: "POST",
                            url: "update_jml_pesan",
                            data: {
                                id_dispen: id_dispen
                            },
                            success: function (response) {
                                if (response.status === true) {
                                    location.reload();
                                } else {
                                    location.reload();
                                }
                            }
                        });
                    });

                    $(function () {
                        TablesDatatables.init();
                    });
                }
            });
            $('.btn_cetak_laporan').on('click', function (e) {
                e.preventDefault();
                let hrf = `<?= base_url('laporan/CetakLaporanDataDispenV2') ?>`; //requset pak jae
                window.open(hrf, '_blank');
            });


            $('.button_not_active').on('click', function () {
                alert("Modul Belum Dapat Digunakan!");
            });
            setTimeout(function () {
                $("#alert_alert").html("");
            }, 2000);
            $('#tgl_pelunasan').on('change', function () {
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
            $("#jenis_dispen").on('change', function () {
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
            $("#search-box").on("keypress", function (e) {
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
                        url: "cari_mhs",
                        data: {
                            nipd: nipd,
                            tahun_akademik: tahun_akademik,
                            jenis_dispen: jenis_dispen
                        },
                        dataType: "json",
                        success: function (response) {
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
                                    $('.swal2-confirm').click(function () {
                                        location.reload();
                                    });
                                } else {
                                    swal.fire("Info!", response.msg, "info");
                                    $('.swal2-confirm').click(function () {
                                        location.reload();
                                    });
                                }
                            }
                        }
                    });
                }
            });

            $('#btn_save_edit').on('click', function () {
                // get all the inputs into an array.
                let inputs = $('#form_edit_dispen :input');
                // console.log(inputs);
                let values = {};
                inputs.each(function () {
                    values[this.name] = $(this).val();
                });
                $.ajax({
                    type: "POST",
                    url: "edit_dispen",
                    data: values,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response)
                        if (response.status === true) {
                            swal.fire("Sukses!", `Data Dispen Berhasil Di Edit.`, "success");
                            $('.swal2-confirm').click(function () {
                                location.reload();
                            });
                        } else {
                            swal.fire("Gagal!", `EEdit Dispen Gagal.`, "error");
                            $('.swal2-confirm').click(function () {
                                location.reload();
                            });
                        }
                    }
                })

            });
            $('#btn_aktivasi').on('click', function () {
                // get all the inputs into an array.
                let $inputs = $('#form_dispen :input');
                let values = {};
                $inputs.each(function () {
                    values[this.name] = $(this).val();
                });
                $.ajax({
                    type: "POST",
                    url: "aktif_dispen",
                    data: values,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === true) {
                            swal.fire("Sukses!", `Aktivasi Dispen Berhasil.`, "success");
                            $('.swal2-confirm').click(function () {
                                location.reload();
                            });
                        } else if (response.status === false && response.msg === 'exist') {
                            swal.fire("Sukses!", `Aktivasi Dispen Mahasiswa Tersebut Sudah Ada.`, "success");
                            $('.swal2-confirm').click(function () {
                                location.reload();
                            });
                        } else {
                            swal.fire("Gagal!", `Aktivasi Dispen Gagal.`, "error");
                            $('.swal2-confirm').click(function () {
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