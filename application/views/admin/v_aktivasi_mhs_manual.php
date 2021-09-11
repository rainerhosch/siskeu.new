<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <div class="block" style="height: 500px;">
        <div class="block-title">
            <h2><?= $page; ?></h2>
        </div>
        <form class="form-inline" id="form_cari_mhs">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><strong>NIM</strong></div>
                    <input type="text" class="form-control" id="key_cari" placeholder="Cari nim">
                </div>
            </div>
            <!-- <button type="button" class="btn btn-primary btn_cari"><i class="gi gi-search"></i></button> -->
        </form>
        <div class="row" style="margin-top: 5px;">
            <div class="col-sm-12">
                <div class="row data_dispen">
                    <div class="col-sm-12">
                        <div class="block full">
                            <div class="block-title">
                                <h2><strong>Data</strong> Mahasiswa</h2>
                            </div>
                            <div class="table-responsive">
                                <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NIM</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Jurusan</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_mhs_tbody">
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
        $(document).ready(function() {
            $('#key_cari').on("keypress", function(e) {
                if (e.which == 13) {
                    let nipd = $("#key_cari-box").val();
                    console.log(nipd);
                    // $.ajax({
                    //     type: "POST",
                    //     url: "cari",
                    //     data: values,
                    //     dataType: "json",
                    //     success: function(response) {}
                    // });
                }
            });
        });
    </script>
</div>
<!-- END Page Content -->