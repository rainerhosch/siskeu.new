<style>
    .widget-simple,
    h4 {
        font-size: 14px;
    }

    .widegt-box {
        border-radius: 5px;
    }

    .widget-simple .widget-content {
        font-size: 14px;
    }

    .widget-simple .widget-content small {
        display: block;
        margin-top: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .widget-content>h1,
    h2,
    h3 {
        text-align: center;
        margin-bottom: 0px;
    }
</style>
<div class="row">
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Data Mahasiswa</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown mhs_local_label">
                                <strong><span></span></strong>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-left"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown mhs_simak_label">
                                <strong><span></span></strong>
                                <small>Data Simak</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="progress progress-striped active" id="progress_sync_mhs" style="display: none;">
                            <div class="progress-bar progress-bar-info" id="bar_sync_mhs" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <button class="btn btn-primary btn-xs" id="btn_sync_mhs" disabled><i class="fa fa-sync" id="icon_sync_mhs"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Data KRS</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown krs_local_label">
                                <strong><span></span></strong>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-left"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown krs_simak_label">
                                <strong><span></span></strong>
                                <small>Data Simak</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="progress progress-striped active" id="progress_sync_krs" style="display: none;">
                            <div class="progress-bar progress-bar-info" id="bar_sync_krs" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <button class="btn btn-primary btn-xs" id="btn_sync_krs" disabled><i class="fa fa-sync" id="icon_sync_krs"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->


    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Data Tahun Akademik Aktif</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown sm_active_local_label">
                                <strong><span></span></strong><br>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-left"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown sm_active_simak_label">
                                <strong><span></span></strong><br>
                                <small>Data Simak</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <!-- <div class="progress">
                                <div class="bar"></div>
                                <div class="percent">0%</div>
                            </div> -->
                        <button class="btn btn-primary btn-xs" id="btn_sync_smt_aktif" disabled><i class="fa fa-sync" id="icon_sync_smt_aktif"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Data Reg Mhs</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown reg_mhs_local_label">
                                <strong><span></span></strong>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-right"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown reg_mhs_simak_label">
                                <strong><span></span></strong>
                                <small>Data Simak</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary btn-xs" id="btn_sync_reg_mhs" disabled><i class="fa fa-sync" id="icon_sync_reg_mhs"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Data Reg Ujian</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown reg_ujian_local_label">
                                <strong><span></span></strong>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-right"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown reg_ujian_simak_label">
                                <strong><span></span></strong>
                                <small>Data Simak</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary btn-xs" id="btn_sync_reg_ujian" disabled><i class="fa fa-sync" id="icon_sync_reg_ujian"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>


    <!-- Data Transaksi -->
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Data Transaksi</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown dataTrx_local_label">
                                <strong><span></span></strong>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-right"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown dataTrx_simak_label">
                                <strong><span></span></strong>
                                <small>Data Simak</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary btn-xs" id="btn_sync_dataTrx" disabled><i class="fa fa-sync" id="icon_sync_dataTrx"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Dashboard Simak -->
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Dashboard SIMAK</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown dashboardSimak_local_label">
                                <strong><span></span></strong>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-right"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown dashboardSimak_simak_label">
                                <strong><span></span></strong>
                                <small>Data Simak</small>
                            </h3>
                        </div> -->
                        <small>Portal untuk melihat data laporan keuangan SIMAK</small>
                        <ul>
                            <li>Data 1</li>
                            <!-- <li>Data 2</li> -->
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary btn-xs" id="btn_sync_dashboardSimak">Go to DASHBOARD</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->

    <!-- excel report -->
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1 widegt-box">
            <div class="widget-simple">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4><strong>Cetak Laporan</strong></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <ul>
                            <li id="btn_excel_kms"><button class="btn btn-success btn-xs">Kemahasiswaan</button></li>
                            <li id="btn_excel_cicilan"><button class="btn btn-danger btn-xs">Cicilan 1,2,3</button></li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary btn-xs">Format Excel</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->

</div>
<script>
    $(document).ready(function() {
        $('#btn_sync_dashboardSimak').on('click', function() {
            // var data = `<?= $this->session->userdata('username'); ?>`
            let url = `https://simak.wastu.digital/admin/DashboardSiskeu/Auth?token=tehpoci`;
            window.open(url);
        });

        $('#btn_excel_kms').on('click', function() {
            let url = `/siskeu.new/excel-kms`;
            window.open(url);
        });
        $('#btn_excel_cicilan').on('click', function() {
            alert('Feature not active.')
        });
        $.ajax({
            type: "GET",
            url: 'sync-simak/getCountData',
            dataType: "json",
            success: function(response) {
                console.log(response);
                $('.mhs_local_label span').text(response.count_mhs_local);
                $('.mhs_simak_label span').text(response.count_mhs_simak);
                $('.krs_local_label span').text(response.count_krs_local);
                $('.krs_simak_label span').text(response.count_krs_simak);
                $('.sm_active_local_label span').text(response.semester_aktif_local);
                $('.sm_active_simak_label span').text(response.semester_aktif_simak);
                $('.reg_mhs_local_label span').text(response.reg_mhs_local);
                $('.reg_mhs_simak_label span').text(response.reg_mhs_simak);
                $('.reg_ujian_local_label span').text(response.reg_ujian_local);
                $('.reg_ujian_simak_label span').text(response.reg_ujian_simak);
                $('.dataTrx_simak_label span').text(response.siskeu_trx_simak);
                $('.dataTrx_local_label span').text(response.siskeu_trx_local);
                // console.log($('.mhs_simak_label span').text());
                if ($('.mhs_local_label span').text() != $('.mhs_simak_label span').text()) {
                    if ($('.mhs_local_label span').text() < $('.mhs_simak_label span').text()) {
                        $('.btn#btn_sync_mhs').attr('disabled', false);
                    }
                } else {
                    $('.btn#btn_sync_mhs').attr('disabled', true);
                }


                if ($('.krs_local_label span').text() != $('.krs_simak_label span').text()) {
                    if ($('.krs_local_label span').text() < $('.krs_simak_label span').text()) {
                        $('.btn#btn_sync_krs').attr('disabled', false);
                    }
                } else {
                    $('.btn#btn_sync_krs').attr('disabled', true);
                }

                $('.btn#btn_sync_krs').click(function() {
                    $('#icon_sync_krs').attr('class', 'fa fa-sync fa-spin');
                    let awal = $('.krs_local_label span').text();
                    let total = $('.krs_simak_label span').text();
                    let percentage = (awal / total) * 100;

                    $.ajax({
                        type: 'POST', //Method type
                        url: 'sync-simak/SyncDataKrs',
                        dataType: 'json',
                        success: function(data) {
                            // console.log(data);
                            if (data.data == 'success') {
                                $('#icon_sync_krs').attr('class', 'fa fa-sync');
                                // $('#progress_sync_krs').css('display', 'none');
                                // $('.btn#btn_sync_krs').show();
                                $('#success_message').html("<div class='alert alert-success alert-dismissable'><h4><i class='fa fa-check-circle'></i> Success</h4> Syncron <a href='javascript:void(0)' class='alert-link'>data</a>!</div>");
                                setTimeout(function() {
                                    $('#success_message').html('');
                                }, 5000);
                                $('.krs_local_label span').text(data.count_krs_local_update);
                                if ($('.mhs_local_label span').text() === $('.mhs_simak_label span').text()) {
                                    $('.btn#btn_sync_krs').prop('disabled', true);
                                }
                            }
                        }
                    });
                });


                $('.btn#btn_sync_mhs').click(function() {
                    $('#icon_sync_mhs').attr('class', 'fa fa-sync fa-spin');

                    let awal = $('.mhs_local_label span').text();
                    let total = $('.mhs_simak_label span').text();
                    let percentage = (awal / total) * 100;

                    $.ajax({
                        type: 'POST', //Method type
                        url: 'sync-simak/SyncDataMhs',
                        dataType: 'json',
                        success: function(data) {
                            // console.log(data);
                            if (data.data == 'success') {
                                $('#icon_sync_mhs').attr('class', 'fa fa-sync');
                                // $('#progress_sync_mhs').css('display', 'none');
                                // $('.btn#btn_sync_mhs').show();
                                $('#success_message').html("<div class='alert alert-success alert-dismissable'><h4><i class='fa fa-check-circle'></i> Success</h4> Syncron <a href='javascript:void(0)' class='alert-link'>data</a>!</div>");
                                setTimeout(function() {
                                    $('#success_message').html('');
                                }, 5000);
                                $('.mhs_local_label span').text(data.count_mhs_local_update);
                                $('.btn#btn_sync_mhs').prop('disabled', true);
                            }
                        }
                    });
                });

                if (response.semester_aktif_local != response.semester_aktif_simak) {
                    if (response.semester_aktif_local < response.semester_aktif_simak) {
                        $('.btn#btn_sync_smt_aktif').attr('disabled', false);
                    }
                } else {
                    $('.btn#btn_sync_smt_aktif').attr('disabled', true);
                }
                $('.btn#btn_sync_smt_aktif').click(function() {
                    // lest code...
                    $('#icon_sync_smt_aktif').attr('class', 'fa fa-sync fa-spin');
                    $.ajax({
                        type: 'POST', //Method type
                        url: 'sync-simak/SyncTahunAkademik',
                        dataType: 'json',
                        success: function(data) {
                            // console.log(data);
                            if (data.data == 'success') {
                                $('#icon_sync_smt_aktif').attr('class', 'fa fa-sync');
                                $('#success_message').html("<div class='alert alert-success alert-dismissable'><h4><i class='fa fa-check-circle'></i> Success</h4> Syncron <a href='javascript:void(0)' class='alert-link'>data</a>!</div>");
                                setTimeout(function() {
                                    $('#success_message').html('');
                                }, 5000);
                                $('.sm_active_local_label span').text(data.semester_aktif_local_update);
                                $('.btn#btn_sync_smt_aktif').prop('disabled', true);
                            }
                        }
                    });
                });

                if ($('.reg_mhs_local_label span').text() != $('.reg_mhs_simak_label span').text()) {
                    $('.btn#btn_sync_reg_mhs').attr('disabled', false);
                }
                $('.btn#btn_sync_reg_mhs').click(function() {
                    $('#icon_sync_reg_mhs').attr('class', 'fa fa-sync fa-spin');
                    $.ajax({
                        type: 'POST', //Method type
                        url: 'sync-simak/SyncRegMhs',
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);
                            if (response.status == 200) {
                                $('#icon_sync_reg_mhs').attr('class', 'fa fa-sync');
                                $('#success_message').html("<div class='alert alert-success alert-dismissable'><h4><i class='fa fa-check-circle'></i> Success</h4> Syncron <a href='javascript:void(0)' class='alert-link'>data</a>!</div>");
                                setTimeout(function() {
                                    $('#success_message').html('');
                                }, 5000);
                                if (response.tipe == 'simak') {
                                    $('.reg_mhs_simak_label span').text(response.data);
                                } else {
                                    $('.reg_mhs_local_label span').text(response.data);
                                }
                                $('.btn#btn_sync_reg_mhs').prop('disabled', true);
                            }
                        }
                    });
                });

                // ====================== Reg Ujian ===========================
                if ($('.reg_ujian_local_label span').text() != $('.reg_ujian_simak_label span').text()) {
                    $('.btn#btn_sync_reg_ujian').attr('disabled', false);
                }
                $('.btn#btn_sync_reg_ujian').click(function() {
                    $('#icon_sync_reg_ujian').attr('class', 'fa fa-sync fa-spin');
                    $.ajax({
                        type: 'POST', //Method type
                        url: 'sync-simak/SyncRegUjian',
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);
                            if (response.status == 200) {
                                $('#icon_sync_reg_ujian').attr('class', 'fa fa-sync');
                                $('#success_message').html("<div class='alert alert-success alert-dismissable'><h4><i class='fa fa-check-circle'></i> Success</h4> Syncron <a href='javascript:void(0)' class='alert-link'>data</a>!</div>");
                                setTimeout(function() {
                                    $('#success_message').html('');
                                }, 5000);
                                if (response.tipe == 'simak') {
                                    $('.reg_ujian_simak_label span').text(response.data);
                                } else {
                                    $('.reg_ujian_local_label span').text(response.data);
                                }
                                $('.btn#btn_sync_reg_ujian').prop('disabled', true);
                            }
                        }
                    });
                });

                // ====================== Data Trx ===========================
                if ($('.dataTrx_local_label span').text() != $('.dataTrx_simak_label span').text()) {
                    $('.btn#btn_sync_dataTrx').attr('disabled', false);
                }
                $('.btn#btn_sync_dataTrx').click(function() {
                    $('#icon_sync_dataTrx').attr('class', 'fa fa-sync fa-spin');
                    $.ajax({
                        type: 'POST', //Method type
                        url: 'sync-simak/DataTrxSiskeuV2',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.status == true) {
                                $('#icon_sync_dataTrx').attr('class', 'fa fa-sync');
                                $('#success_message').html("<div class='alert alert-success alert-dismissable'><h4><i class='fa fa-check-circle'></i> Success</h4> Syncron <a href='javascript:void(0)' class='alert-link'>data</a>!</div>");
                                setTimeout(function() {
                                    $('#success_message').html('');
                                }, 5000);
                                if (response.action == 'simak') {
                                    $('.dataTrx_simak_label span').text(response.data);
                                } else {
                                    $('.dataTrx_local_label span').text(response.data);
                                }
                                $('.btn#btn_sync_dataTrx').prop('disabled', true);
                            }
                        }
                    });
                });


            }
        });
    });
</script>