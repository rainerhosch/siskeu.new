<style>
    .row {
        margin-bottom: 5px;
    }

    .jumbotron {
        padding-top: 30px;
        padding-bottom: 30px;
        margin-bottom: 10px;
        color: inherit;
        border-radius: 5px;
        /* background-color: #d4d4d4; */
        background-color: #eeeeee;
    }

    /* .xform {
        margin-left: 5px;
    } */
</style> <!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <!-- END Page Header -->
    <?php if ($this->session->flashdata('success')) {
        echo '<div class="alert alert-success" role="alert">' . $this->session->flashdata('success') . '</div>';
    } elseif ($this->session->flashdata('error')) {
        echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata('error') . '</div>';
    }
    ?>
    <div class="row">
        <div class="col-sm-3">
            <div class="block full">
                <div class="block-title">
                    <h2><strong>Data </strong>Mahasiswa</h2>
                </div>
                <div class="sm-form mb-5 row">
                    <div class="col-sm-3">
                        <label data-error="wrong" data-success="right" for="nipd">NIM</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="nipd" name="nipd" class="form-control validate" placeholder="Cari NIM..">
                    </div>
                    <div class="col-sm-2">
                        <span id="cari_mhs" class="input-group-btn"><input type="image" src="<?= base_url('assets'); ?>/img/enter.png" width="35" height="35" hidden></span>
                    </div>
                </div>
                <div class="sm-form mb-5 row">
                    <div class="col-sm-3">
                        <label data-error="wrong" data-success="right" for="nama_mhs">Nama</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="nama_mhs" name="nama_mhs" class="form-control validate" readonly>
                    </div>
                </div>
                <div class="sm-form mb-5 row">
                    <div class="col-sm-3">
                        <label data-error="wrong" data-success="right" for="jurusan">Jurusan</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="jurusan" name="jurusan" class="form-control validate" readonly>
                    </div>
                </div>
                <hr class="my-4">
                <div class="jumbotron jumbotron-fluid data_kwajiban">
                    <div class="container">
                        <h4><strong>Kewajiban Bayar</strong></h4>
                        <!-- <hr class="my-4"> -->
                        <form action="<?= base_url('transaksi'); ?>/proses_bayar_spp" method="post" enctype="multipart/form-data">
                            <table id="menu-datatable" class="table table-vcenter table-condensed">
                                <!-- <thead>
                                    <tr>
                                        <th class="text-center">Jenis Bayar</th>
                                        <th class="text-center">IDR</th>
                                        <th class="text-center">Opsi</th>
                                    </tr>
                                </thead> -->
                                <tbody id="data_kwajiban_tbody">
                                </tbody>
                            </table>
                            <hr class="my-5">
                            <div class="text-right">
                                <button type="submit" id="btn_proses" class="btn btn-primary">Proses</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="block full">
                <div class="block-title">
                    <h2><strong>History</strong> Transaksi</h2>
                </div>
                <div class="table-responsive">
                    <table id="riwayat_transaksi" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nomo Transaksi</th>
                                <th class="text-center">Tgl Transaksi</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">NIM</th>
                                <th class="text-center">Keterangan Bayar</th>
                                <th class="text-center">Jumlah Storan</th>
                                <!-- <th class="text-center">Sisa Tagihan</th>
                                <th class="text-center">Status</th> -->
                                <th class="text-center">Semester</th>
                            </tr>
                        </thead>
                        <tbody id="riwayat_transaksi_tbody">
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#riwayat_transaksi').hide();
            $('.data_kwajiban').hide();
            $('#nipd').keypress((e) => {
                if (e.which === 13) {
                    $("#cari_mhs").click();
                }
            })
            $("#cari_mhs").click(function() {
                let nipd = $('#nipd').val();
                $.ajax({
                    type: "POST",
                    url: 'cari_mhs',
                    data: {
                        nipd: nipd,
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        if (response != null) {
                            let html = ``;
                            let htmlx = ``;
                            $('.data_kwajiban').show();
                            $('#riwayat_transaksi').show();
                            $("#nama_mhs").val(response.nama);
                            $("#jurusan").val(response.prodi);
                            html += `<input type="hidden" id="nim_mhs_bayar" name="nim_mhs_bayar" value="${response.nipd}">`;
                            html += `<input type="hidden" id="nama_mhs_bayar" name="nama_mhs_bayar" value="${response.nama}">`;
                            html += `<input type="hidden" id="jenjang_mhs_bayar" name="jenjang_mhs_bayar" value="${response.jenjang}">`;
                            html += `<input type="hidden" id="angkatan_mhs_bayar" name="angkatan_mhs_bayar" value="${response.tahun_masuk}">`;

                            $.each(response.dataKewajiban, function(i, value) {
                                html += `<tr>
                                        <td><label data-error="wrong" data-success="right" for="${value.label}">${value.label}</label></td>
                                        <td class="text-center"><input type="text" id="${value.post_id}" name="${value.post_id}" class="form-control validate text-right" value="${value.biaya}" disabled></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_${i}" ${value.biaya == 0 ? 'disabled' : ''}></td>
                                    </tr>`;
                            });
                            $("#data_kwajiban_tbody").html(html);
                            $.each(response.dataKewajiban, function(i, value) {
                                $("#checkcox_" + i).change(function() {
                                    if (this.checked === true) {
                                        $('#' + value.post_id).prop('disabled', false);
                                    } else {
                                        $('#' + value.post_id).prop('disabled', true);
                                    }
                                });
                            });
                            if (response.dataHistoriTX != null) {
                                $.each(response.dataHistoriTX, function(i, value) {
                                    // console.log(value);
                                    i++;
                                    htmlx += `<tr>`;
                                    htmlx += `<td class = "text-center" >${i}</td>`;
                                    htmlx += `<td class="text-center"><a href="#">${value.id_transaksi}</a></td>`;
                                    htmlx += `<td class = "text-center" >${value.tanggal}</td>`;
                                    htmlx += `<td class = "text-center" >${value.jam}</td>`;
                                    htmlx += `<td class = "text-center" >${value.nim}</td>`;
                                    htmlx += `<td class = "text-center" >${value.nm_jenis_pembayaran}</td>`;
                                    htmlx += `<td class = "text-center" >${value.jml_bayar}</td>`;
                                    htmlx += `<td class = "text-center" >${value.semester}</td>`;
                                    // htmlx = `< td class = "text-center" >${value.id_transaksi}</td>`;
                                    // htmlx = `< td class = "text-center" >${value.id_transaksi}</td>`;
                                    htmlx += `</tr>`;
                                });
                            } else {
                                htmlx += `<tr>`;
                                htmlx += `<td colspan="12" class="text-center"><br>`;
                                htmlx += `<div class='col-lg-12'>`;
                                htmlx += `<div class='alert alert-danger alert-dismissible'>`;
                                htmlx += `<h4><i class='icon fa fa-warning'></i> Belum Ada Histori Pembayaran!</h4>`;
                                htmlx += `</div>`;
                                htmlx += `</div>`;
                                htmlx += `</td>`;
                                htmlx += `</tr>`;
                            }
                            $("#riwayat_transaksi_tbody").html(htmlx);

                        } else {
                            alert('Data mahasiswa tersebut tidak ditemukan, pastikan NIM sudah benar!');
                            window.location.reload();
                        }

                    },
                    error: function(e) {
                        error_server();
                    },
                });
            })
        });
    </script>
</div>
<!-- END Page Content -->