<div class="row">
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1">
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
                                <strong><span></span></strong> Data<br>
                                <small>Data Lokal</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-left"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown mhs_simak_label">
                                <strong><span></span></strong> Data<br>
                                <small>Data Simak</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="progress progress-striped active" id="progress_sync_mhs" style="display: none;">
                            <div class="progress-bar progress-bar-info" id="bar_sync_mhs" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style=""></div>
                        </div>
                        <button class="btn btn-primary" id="btn_sync_mhs" disabled><i class="fa fa-sync" id="icon_sync_mhs"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->


    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1">
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
                        <button class="btn btn-primary" id="btn_sync_smt_aktif" disabled><i class="fa fa-sync" id="icon_sync_smt_aktif"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1">
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
                            <h3 class="widget-content text-center animation-pullDown">
                                Database<strong> Local</strong><br>
                                <small>Data Mahasiswa</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-right"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown">
                                Database<strong> Simak</strong><br>
                                <small>Data Mahasiswa</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" id="btn_sync_reg_mhs" disabled><i class="fa fa-sync" id="icon_sync_reg_mhs"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->
    <div class="col-sm-6 col-lg-3">
        <!-- Widget -->
        <a href="#" class="widget widget-hover-effect1">
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
                            <h3 class="widget-content text-center animation-pullDown">
                                Database<strong> Local</strong><br>
                                <small>Data Mahasiswa</small>
                            </h3>
                        </div>
                        <div class="col-sm-2">
                            <h2><i class="fa fa-arrow-circle-right"></i></h2>
                        </div>
                        <div class="col-sm-5">
                            <h3 class="widget-content text-center animation-pullDown">
                                Database<strong> Simak</strong><br>
                                <small>Data Mahasiswa</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" id="btn_sync_reg_ujian" disabled><i class="fa fa-sync" id="icon_sync_reg_ujian"></i> Sinkron Data</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- end widget -->
</div>
<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: 'sync-simak/getCountData',
            dataType: "json",
            success: function(response) {
                // console.log(response);
                $('.mhs_local_label span').text(response.count_mhs_local);
                $('.mhs_simak_label span').text(response.count_mhs_simak);
                $('.sm_active_local_label span').text(response.semester_aktif_local);
                $('.sm_active_simak_label span').text(response.semester_aktif_simak);
                // console.log($('.mhs_simak_label span').text());
                if ($('.mhs_local_label span').text() != $('.mhs_simak_label span').text()) {
                    if ($('.mhs_local_label span').text() < $('.mhs_simak_label span').text()) {
                        $('.btn#btn_sync_mhs').attr('disabled', false);
                    }
                } else {
                    $('.btn#btn_sync_mhs').attr('disabled', true);
                }
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
            }
        });
    });
</script>