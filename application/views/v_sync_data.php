<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
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
                            <div class="col-sm-6">
                                <h3 class="widget-content text-center animation-pullDown mhs_local_label">
                                    <strong><span></span></strong> Data<br>
                                    <small>Data Lokal</small>
                                </h3>
                            </div>
                            <div class="col-sm-6">
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
                            <button class="btn btn-primary" id="btn_sync_mhs"><i class="fa fa-sync" id="icon_sync_mhs"></i> Sinkron Data</button>
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
                            <div class="col-sm-6">
                                <h3 class="widget-content text-center animation-pullDown sm_active_local_label">
                                    <strong><span></span></strong><br>
                                    <small>Data Lokal</small>
                                </h3>
                            </div>
                            <div class="col-sm-6">
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
                            <button class="btn btn-primary" id="btn_sync_smt_aktif"><i class="fa fa-sync" id="icon_sync_smt_aktif"></i> Sinkron Data</button>
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
                            <div class="col-sm-6">
                                <h3 class="widget-content text-center animation-pullDown">
                                    Database<strong> Local</strong><br>
                                    <small>Data Mahasiswa</small>
                                </h3>
                            </div>
                            <div class="col-sm-6">
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
                            <button class="btn btn-primary"><i class="fa fa-sync"></i> Sinkron Data</button>
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
                            <div class="col-sm-6">
                                <h3 class="widget-content text-right animation-pullDown">
                                    Database<strong> Local</strong><br>
                                    <small>Data Mahasiswa</small>
                                </h3>
                            </div>
                            <div class="col-sm-6">
                                <h3 class="widget-content text-right animation-pullDown">
                                    Database<strong> Simak</strong><br>
                                    <small>Data Mahasiswa</small>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-primary"><i class="fa fa-sync"></i> Sinkron Data</button>
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

                    if (response.count_mhs_local != response.count_mhs_simak) {
                        $('.btn#btn_sync_mhs').attr('disabled', false);
                    } else {
                        $('.btn#btn_sync_mhs').attr('disabled', true);
                    }
                    $('.btn#btn_sync_mhs').click(function() {
                        $('#icon_sync_mhs').attr('class', 'fa fa-sync fa-spin');
                        $.ajax({
                            type: 'POST', //Method type
                            url: 'sync-simak/SyncDataMhs',
                            dataType: 'json',
                            success: function(data) {
                                // console.log(data);
                                if (data.data == 'success') {

                                    $('#icon_sync_mhs').attr('class', 'fa fa-sync');
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
</div>
<!-- END Page Content -->