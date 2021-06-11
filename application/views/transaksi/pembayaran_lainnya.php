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
        background-color: #d4d4d4;
    }

    .xform {
        margin-left: 5px;
    }
</style> <!-- Page content -->
<div id="page-content">
    <!-- Page Header -->
    <!-- <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-brush"></i>Page Title<br><small>Subtitle</small>
            </h1>
        </div>
    </div> -->
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <!-- END Page Header -->
    <div class="row">
        <div class="col-md-6">
            <div class="block full">
                <div class="block-title">
                    <!-- <div class="block-options pull-right">
                        <span id="dash-chart-live-info" class="label label-primary">%</span>
                        <span class="label label-danger animation-pulse">CPU Load</span>
                    </div> -->
                    <h2><strong>Data </strong>Mahasiswa</h2>
                </div>
                <!-- <div class="input-group">
                    <div class="form-outline">
                        <label class="form-label" for="form1">NIM</label>
                        <input type="search" id="form1" class="form-control" />
                    </div>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div> -->
                <form action="<?= base_url('admin/manajemen'); ?>/AddNewMenu" method="post" enctype="multipart/form-data">
                    <div class="md-form mb-5 row">
                        <div class="col-md-3">
                            <label data-error="wrong" data-success="right" for="nidn">NIM</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="nidn" name="nidn" class="form-control validate" placeholder="Cari NIM.." tabindex="-1" aria-hidden="true">
                        </div>
                    </div>
                    <div class="md-form mb-5 row">
                        <div class="col-md-3">
                            <label data-error="wrong" data-success="right" for="nama_mhs">Nama</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="nama_mhs" name="nama_mhs" class="form-control validate" value="Rizky Ardiansyah" disabled>
                        </div>
                    </div>
                    <div class="md-form mb-5 row">
                        <div class="col-md-3">
                            <label data-error="wrong" data-success="right" for="jurusan">Jurusan</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="jurusan" name="jurusan" class="form-control validate" value="TEKNIK INFORMATIKA" disabled>
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="jumbotron jumbotron-fluid">
                        <div class="container">
                            <h4><strong>Kewajiban Bayar</strong></h4>
                            <hr class="my-4">
                            <div class="md-form mb-5 row xform">
                                <div class="col-md-3">
                                    <label data-error="wrong" data-success="right" for="uang_bangunan">Uang Bangunan</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="uang_bangunan" name="uang_bangunan" class="form-control validate" value="1.000.000" disabled>
                                </div>
                            </div>
                            <div class="md-form mb-5 row xform">
                                <div class="col-md-3">
                                    <label data-error="wrong" data-success="right" for="tunggakan">Tunggakan</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="tunggakan" name="tunggakan" class="form-control validate" value="1.000.000" disabled>
                                </div>
                            </div>
                            <div class="md-form mb-5 row xform">
                                <div class="col-md-3">
                                    <label data-error="wrong" data-success="right" for="kmhs">Kemahasiswaan</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="kmhs" name="kmhs" class="form-control validate" value="150.000" disabled>
                                </div>
                            </div>
                            <div class="md-form mb-5 row xform">
                                <div class="col-md-3">
                                    <label data-error="wrong" data-success="right" for="CS">Cicilan Semester</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="CS" name="CS" class="form-control validate" value="4.050.000" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="block full">
                <div class="block-title">
                    <h2><strong>Kewajiban</strong> Pembayaran</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Example Block -->
    <div class="block">
        <!-- Example Title -->
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
        <!-- END Example Title -->

        <!-- Example Content -->
        <p>...</p>
        <!-- END Example Content -->
    </div>
    <!-- END Example Block -->
</div>
<!-- END Page Content -->