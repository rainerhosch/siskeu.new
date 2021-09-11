<!-- Page content -->
<style>
    input[type='radio'] {
        -webkit-appearance: none;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        outline: none;
        border: 3px solid gray;
    }

    input[type='radio']:before {
        content: '';
        display: block;
        width: 60%;
        height: 60%;
        margin: 20% auto;
        border-radius: 50%;
    }

    input[type="radio"]:checked:before {
        background: navy;

    }

    /* input[type="radio"]:checked {
        border-color: navy;
    } */

    .role {
        margin-right: 80px;
        margin-left: 20px;
        font-weight: normal;
    }

    .checkbox label {
        margin-bottom: 20px !important;
    }

    /* .roles {
        margin-bottom: 40px;
    } */
</style>
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <div class="block" style="height: 500px;">
        <div class="block-title">
            <h2><?= $page; ?></h2>
        </div>
        <form class="form-inline" action="#" id="form_search">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><strong>NIM</strong></div>
                    <input type="text" class="form-control" id="key_cari" placeholder="Cari nim">
                </div>
            </div>
            <hr>
            <div class="form-check roles">
                <input class="form-check-input" type="radio" name="jns_aktifasi" id="cekAktifPerwalian" value="2">
                <label class="form-check-label" for="cekAktifPerwalian">
                    Perwalian
                </label>
                <input class="form-check-input" type="radio" name="jns_aktifasi" id="cekAktifUTS" value="3">
                <label class="form-check-label" for="cekAktifUTS">
                    UTS
                </label>
                <input class="form-check-input" type="radio" name="jns_aktifasi" id="cekAktifUAS" value="4">
                <label class="form-check-label" for="cekAktifUAS">
                    UAS
                </label>
            </div>
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
                    let nipd = $("#key_cari").val();
                    $.ajax({
                        type: "POST",
                        url: "cari_mhs",
                        data: {
                            nipd: nipd
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === 200) {
                                console.log(response.data);
                            } else {
                                console.log(response.msg);
                            }

                        }
                    });
                }
            });
        });
    </script>
</div>
<!-- END Page Content -->