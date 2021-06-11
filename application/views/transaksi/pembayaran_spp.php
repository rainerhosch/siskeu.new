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
    <div class="row">
        <div class="col-md-3">
            <div class="block full">
                <div class="block-title">
                    <h2><strong>Data </strong>Mahasiswa</h2>
                </div>
                <!-- <form action="<?= base_url('admin/manajemen'); ?>/AddNewMenu" method="post" enctype="multipart/form-data"> -->
                <div class="md-form mb-5 row">
                    <div class="col-md-3">
                        <label data-error="wrong" data-success="right" for="nidn">NIM</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" id="nidn" name="nidn" class="form-control validate" placeholder="Cari NIM.." tabindex="-1" aria-hidden="true">
                    </div>
                    <div class="col-md-2">
                        <span id="cari_mhs" class="input-group-btn"><input type="image" src="<?= base_url('assets'); ?>/img/enter.png" width="35" height="35"></span>
                    </div>
                </div>
                <div class="md-form mb-5 row">
                    <div class="col-md-3">
                        <label data-error="wrong" data-success="right" for="nama_mhs">Nama</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="nama_mhs" name="nama_mhs" class="form-control validate" readonly>
                    </div>
                </div>
                <div class="md-form mb-5 row">
                    <div class="col-md-3">
                        <label data-error="wrong" data-success="right" for="jurusan">Jurusan</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="jurusan" name="jurusan" class="form-control validate" readonly>
                    </div>
                </div>
                <!-- </form> -->
                <!-- <hr> -->
                <hr class="my-4">
                <div class="jumbotron jumbotron-fluid data_kwajiban">
                    <div class="container">
                        <h4><strong>Kewajiban Bayar</strong></h4>
                        <!-- <hr class="my-4"> -->
                        <form action="<?= base_url('admin/manajemen'); ?>/AddNewSubmenu" method="post" enctype="multipart/form-data">
                            <table id="menu-datatable" class="table table-vcenter table-condensed">
                                <!-- <thead>
                                    <tr>
                                        <th class="text-center">Jenis Bayar</th>
                                        <th class="text-center">IDR</th>
                                        <th class="text-center">Opsi</th>
                                    </tr>
                                </thead> -->
                                <tbody id="data_kwajiban_tbody">
                                    <!-- <tr>
                                        <td><label data-error="wrong" data-success="right" for="tunggakan">Tunggakan</label></td>
                                        <td class="text-center"><input type="text" id="tunggakan" name="tunggakan" class="form-control validate text-right" value="1.000.000" readonly></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_tunggakan"></td>
                                    </tr>
                                    <tr>
                                        <td><label data-error="wrong" data-success="right" for="kmhs">Kemahasiswaan</label></td>
                                        <td class="text-center"><input type="text" id="kmhs" name="kmhs" class="form-control validate text-right" readonly></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_kmhs"></td>
                                    </tr>
                                    <tr>
                                        <td><label data-error="wrong" data-success="right" for="C1">Cicilan Ke-1</label></td>
                                        <td class="text-center"><input type="text" id="C1" name="C1" class="form-control validate text-right" value="1.350.000" readonly></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_C1"></td>
                                    </tr>
                                    <tr>
                                        <td><label data-error="wrong" data-success="right" for="C2">Cicilan Ke-2</label></td>
                                        <td class="text-center"><input type="text" id="C2" name="C2" class="form-control validate text-right" value="1.350.000" readonly></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_C2"></td>
                                    </tr>
                                    <tr>
                                        <td><label data-error="wrong" data-success="right" for="C3">Cicilan Ke-3</label></td>
                                        <td class="text-center"><input type="text" id="C3" name="C3" class="form-control validate text-right" value="1.350.000" readonly></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_C3"></td>
                                    </tr> -->
                                </tbody>
                            </table>
                            <hr class="my-5">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Proses</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- </form> -->
            </div>
        </div>

        <div class="col-md-9">
            <div class="block full">
                <div class="block-title">
                    <h2><strong>History</strong> Transaksi</h2>
                </div>

                <table id="riwayat_transaksi" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nomo Transaksi</th>
                            <th class="text-center">Tgl Transaksi</th>
                            <th class="text-center">NIM</th>
                            <th class="text-center">Nama Mahasiswa</th>
                            <th class="text-center">Keterangan Bayar</th>
                            <th class="text-center">Jumlah Storan</th>
                            <th class="text-center">Sisa Tagihan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="riwayat_transaksi_tbody">
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center"><a href="#">2021061001</a></td>
                            <td class="text-center">10 Juni 2021</td>
                            <td class="text-center">141351059</td>
                            <td class="text-center">Rizky Ardiansyah</td>
                            <td class="text-center">Kemahasiswaan, Cicilan-1, Cicilan-2, Cicilan-3</td>
                            <td class="text-center">4.200.000</td>
                            <td class="text-center">0</td>
                            <td class="text-center">L</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Example Block -->
    <!-- <div class="block">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" data-toggle="tooltip" title="Settings"><i class="fa fa-cog"></i></a>
                <div class="btn-group btn-group-sm">
                    <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Options"><span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                        <li>
                            <a href="javascript:void(0)"><i class="gi gi-cloud pull-right"></i>Simple Action</a>
                            <a href="javascript:void(0)"><i class="gi gi-airplane pull-right"></i>Another Action</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-wrench fa-fw pull-right"></i>Separated Action</a>
                        </li>
                    </ul>
                </div>
            </div>
            <h2>Block</h2>
        </div>
        <p>...</p>
    </div> -->
    <!-- END Example Block -->
</div>
<!-- END Page Content -->
<script>
    $(document).ready(function() {
        $('#riwayat_transaksi').hide();
        $('.data_kwajiban').hide();
        $("#cari_mhs").click(function() {
            let nipd = $('#nidn').val();
            $.ajax({
                type: "POST",
                url: 'cari_mhs',
                data: {
                    nipd: nipd,
                },
                dataType: "json",
                success: function(response) {
                    let html = ``;
                    $('.data_kwajiban').show();
                    $('#riwayat_transaksi').show();
                    console.log(response);
                    $("#nama_mhs").val(response.nama);
                    $("#jurusan").val(response.prodi);
                    // $("#kmhs").val(response.kmhs);
                    // $("#jurusan").val(response.prodi);
                    // $("#jurusan").val(response.prodi);
                    // $("#jurusan").val(response.prodi);
                    if (response.tg != null) {
                        html += `<tr>
                                    <td><label data-error="wrong" data-success="right" for="tunggakan">Tunggakan</label></td>
                                    <td class="text-center"><input type="text" id="tunggakan" name="tunggakan" class="form-control validate text-right" value="${response.tg}" readonly></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_tunggakan"></td>
                                </tr>`;
                    }
                    if (response.kmhs != null) {
                        html += `<tr>
                                    <td><label data-error="wrong" data-success="right" for="kmhs">Kemahasiswaan</label></td>
                                    <td class="text-center"><input type="text" id="kmhs" name="kmhs" class="form-control validate text-right" value="${response.kmhs}" readonly></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_kmhs"></td>
                                </tr>`;
                    }
                    if (response.c1 != null) {
                        html += `<tr>
                                    <td><label data-error="wrong" data-success="right" for="C1">Cicilan Ke-1</label></td>
                                    <td class="text-center"><input type="text" id="C1" name="C1" class="form-control validate text-right" value="${response.c1}" readonly></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_C1"></td>
                                </tr>`;
                    }
                    if (response.c2 != null) {
                        html += `<tr>
                                    <td><label data-error="wrong" data-success="right" for="C2">Cicilan Ke-2</label></td>
                                    <td class="text-center"><input type="text" id="C2" name="C2" class="form-control validate text-right" value="${response.c2}" readonly></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_C2"></td>
                                </tr>`;
                    }
                    if (response.c3 != null) {
                        html += `<tr>
                                    <td><label data-error="wrong" data-success="right" for="C3">Cicilan Ke-3</label></td>
                                    <td class="text-center"><input type="text" id="C3" name="C3" class="form-control validate text-right" value="${response.c3}" readonly></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_C3"></td>
                                </tr>`;
                    }
                    $("#data_kwajiban_tbody").html(html);
                },
                error: function(e) {
                    error_server();
                },
            });
        })
    });
</script>