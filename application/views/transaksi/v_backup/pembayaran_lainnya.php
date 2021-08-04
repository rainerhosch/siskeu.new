<style>
    .modal-header {
        background-color: #1d2132;
        border-radius: 0%;
    }

    .modal-title {
        font-weight: 900;
        color: #fff;
    }

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
                        <!-- <input type="text" id="nipd" name="nipd" class="form-control validate" placeholder="Cari NIM.."> -->
                        <input type="text" id="nipd" name="nipd" class="form-control validate" placeholder="Cari NIM..">
                        <span id="notif_search"></span>
                    </div>
                    <!-- <div class="col-sm-2">
                        <span id="cari_mhs" class="input-group-btn"><input type="image" src="<?= base_url('assets'); ?>/img/enter.png" width="35" height="35" hidden></span>
                    </div> -->
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
                <!-- <div class="text-right" style="margin-bottom: 5px;">
                    <button id="add_trx_lain" class="btn-sm btn-success">Tamabah Transaksi</button>
                </div> -->


                <!-- Button trigger modal -->
                <div class="text-right" style="margin-bottom: 5px;">
                    <button type="button" id="add_trx" class="btn btn-primary" data-toggle="modal" data-target="#formPembayaran">
                        Input Transaksi
                    </button>
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="row data_historiTX">
                <div class="col-sm-12">
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
                                        <!-- <th class="text-center">Sisa Tagihan</th> -->
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayat_transaksi_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="formPembayaran" tabindex="-1" role="dialog" aria-labelledby="formPembayaranTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #fff;">&times;</span>
                    </button>
                    <h5 class="modal-title" id="formPembayaranTitle">Form Transaksi</h5>
                </div>
                <div class="modal-body" id="modal_body">
                    <form>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-3">
                                    NIM
                                </div>
                                <div class="col-sm-1">
                                    :
                                </div>
                                <div class="col-sm-8">
                                    <span>141351059</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-3">
                                    NAMA
                                </div>
                                <div class="col-sm-1">
                                    :
                                </div>
                                <div class="col-sm-8">
                                    <span>Rizky Ardiansyah</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-3">
                                    Jurusan
                                </div>
                                <div class="col-sm-1">
                                    :
                                </div>
                                <div class="col-sm-8">
                                    <span>Teknik Informatika</span>
                                </div>
                            </div>
                        </div>
                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                    </form>
                </div>
                <div class="modal-footer">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <button type="button" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#riwayat_transaksi').hide();
            // $('#add_trx').hide();
            $('.form_pembayaran').hide();
            $('#nipd').on('keyup', function() {
                // your code here 
                let nipd = $('#nipd').val();
                $.ajax({
                    type: "POST",
                    url: 'cari_mhs',
                    data: {
                        nipd: nipd,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response != null) {
                            if (response.totalKewajiban != 0) {
                                $('.btn#btn_proses').prop('disabled', false);
                            } else {
                                $('.btn#btn_proses').prop('disabled', true);
                            }
                            let html = ``;
                            let htmlx = ``;
                            $('#add_trx').show();
                            $('.form_pembayaran').show();
                            $('#riwayat_transaksi').show();
                            $("#nama_mhs").val(response.nm_pd);
                            $("#jurusan").val(response.nm_jur);
                            html += `<input type="hidden" id="nim_mhs_bayar" name="nim_mhs_bayar" value="${response.nipd}">`;
                            html += `<input type="hidden" id="nama_mhs_bayar" name="nama_mhs_bayar" value="${response.nm_pd}">`;
                            html += `<input type="hidden" id="jenjang_mhs_bayar" name="jenjang_mhs_bayar" value="${response.nm_jenj_didik}">`;
                            html += `<input type="hidden" id="angkatan_mhs_bayar" name="angkatan_mhs_bayar" value="${response.tahun_masuk}">`;

                            // $('#add_trx').click(function() {
                            //     let form = ``;
                            //     form += `<h4><strong>Form Pembayaran</strong></h4>
                            //     <!-- <hr class="my-4"> -->
                            //     <form action="<?= base_url('transaksi'); ?>/proses_bayar_spp" method="post" enctype="multipart/form-data">
                            //     <table id="table_transaksi_lainnya" class="table table-vcenter table-condensed">
                            //     <tbody id="form_transaksi_lainnya">
                            //     </tbody>
                            //     </table>
                            //     <hr class="my-5">
                            //     <div class="text-right">
                            //     <button type="submit" id="btn_proses" class="btn btn-primary">Proses</button>
                            //     </div>
                            //     </form>`;
                            //     $(".modal-body").html(html);
                            // });

                            $('#add_row').click(function() {
                                let tds = '<tr>';
                                size = jQuery('#table_transaksi_lainnya >tbody >tr').length + 1,
                                    tds += '<td class="text-center">' + size + '</td>';
                                tds += '<td class="text-center" width="30%">';
                                $('.select2').select2({});


                                tds += '<select name="pilihNamaMhs[]" id="mk' + size + '" style="text-align: center;text-align-last: center;" class="form-control select2 select2Cus">';

                                tds += '</select>';
                                tds += '</td>';
                                tds += '<td class="text-center" id="namaMhs' + size + '"></td>';
                                tds += '<td class="text-center"><input type="checkbox" namex="chmNonHome" name="chm" id="cb-' + size + '" class="form-control cekbox_mhs chmNonHome" value="" ><input type="hidden" name="customNIM"/></td>';

                                tds += '</tr>';
                                if ($('tbody', this).length > 0) {
                                    $('tbody', this).append(tds);
                                } else {
                                    $(this).append(tds);
                                }

                                // $.ajax({
                                //     type: "GET",
                                //     url: "<?= base_url('dosen/absen/get_mhs_luar_homebase') ?>",
                                //     data: {
                                //         'id_matkul': id_matkul,
                                //         'kode_kelas': kode_kelas,
                                //         'arrayNim': splitee
                                //     },
                                //     dataType: "json",
                                //     beforeSend: function() {
                                //         $('#modal-id').modal({
                                //             backdrop: 'static',
                                //             keyboard: false
                                //         });
                                //     }
                                // });
                            });
                            $("#form_transaksi_lainnya").html(html);
                            // $.each(response.dataKewajiban, function(i, value) {
                            //     $("#checkcox_" + i).change(function() {
                            //         if (this.checked === true) {
                            //             $('#' + value.post_id).prop('disabled', false);
                            //         } else {
                            //             $('#' + value.post_id).prop('disabled', true);
                            //         }
                            //     });
                            // });
                            if (response.dataHistoriTX != null) {
                                $.each(response.dataHistoriTX, function(i, value) {
                                    // console.log(value);
                                    i++;
                                    htmlx += `<tr>`;
                                    htmlx += `<td class = "text-center" >${i}</td>`;
                                    htmlx += `<td class="text-center"><a href="<?= base_url('transaksi/cetak_kwitansi/') ?>` + value.id_transaksi + `">${value.id_transaksi}</a></td>`;
                                    htmlx += `<td class = "text-center" >${value.tanggal}</td>`;
                                    htmlx += `<td class = "text-center" >${value.jam}</td>`;
                                    htmlx += `<td class = "text-center" >${value.nim}</td>`;

                                    htmlx += `<td class = "text-center" >`;
                                    $.each(value.detail_transaksi, function(k, val) {
                                        htmlx += `<i style="font-size:1rem; font-weight: bold;">${val.nm_jenis_pembayaran}</i> : <i style="font-size:1rem;">Rp.${parseInt(val.jml_bayar).toLocaleString()}</i><br>`;
                                    });
                                    htmlx += `</td>`;
                                    htmlx += `<td class = "text-center"><i>Rp.${parseInt(value.total_bayar).toLocaleString()}</i></td>`;
                                    htmlx += `<td class = "text-center" >${value.semester}</td>`;
                                    htmlx += `<td class = "text-center" >${value.icon_status_tx}</td>`;
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
                            $('#notif_search').html("<code>Tidak ada mahasiswa dengan nim : " + nipd + "</code>");
                            setTimeout(function() {
                                $('#notif_search').html('');
                            }, 2000);
                        }
                    },
                    error: function(e) {
                        error_server();
                    },
                });
            });

        });
    </script>
</div>
<!-- END Page Content -->