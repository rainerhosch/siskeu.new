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
    <div class="alert alert-warning text-dark" role="alert">
        Info :
        <p>- Menu Aktivasi Manual, Hanya bisa digunakan untuk mahasiswa yang sudah melakukan pembayaran, namun statusnya belum aktif.</p>
        <p>- Mahasiswa yang bisa di aktifkan, adalah mahasiswa yang pembayaranya dibawah Rp. 500.000, atau sudah lunas.</p>
    </div>
    <div class="block" style="height: 500px;">
        <div class="block-title">
            <h2><?= $page; ?></h2>
        </div>
        <!-- <form class="form-inline" action="#"> -->
        <span id="notif_search2"></span>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><strong>NIM</strong></div>
                <input type="text" class="form-control" id="key_cari" placeholder="Cari nim">
            </div>
        </div>
        <hr>
        <input class="form-group" type="hidden" name="nipd_hidden" id="nipd_hidden">
        <input class="form-group" type="hidden" name="biaya_cs" id="biaya_cs">
        <div class="form-check roles" hidden>
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
        <!-- </form> -->
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
                                            <th class="text-center">Sisa Kewajiban</th>
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
                    $("#data_mhs_tbody").empty();
                    $("input:radio:checked").removeAttr("checked");
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
                                // console.log(response.data);
                                $('.roles').prop('hidden', false);
                                $('#nipd_hidden').val(response.data.nipd);
                                $('#biaya_cs').val(response.data.biaya_cs);
                            } else {
                                // console.log(response.msg);
                                $("#notif_search2").html(
                                    `<code>${response.msg}</code>`
                                );
                                setTimeout(function() {
                                    $("#notif_search2").html("");
                                }, 2000);
                            }

                        }
                    });
                }
            });

            $('.roles').on('change', function() {
                $('#key_cari').val('');
                let jns_aktifasi = $('input[name="jns_aktifasi"]:checked').val();
                let biaya_cs = $('#biaya_cs').val();
                let nim = $('#nipd_hidden').val();
                // console.log(jns)
                $.ajax({
                    type: "POST",
                    url: "cek_satus_aktif",
                    data: {
                        nipd: nim,
                        jns_aktifasi: jns_aktifasi,
                        biaya_cs: biaya_cs
                    },
                    dataType: "json",
                    success: function(response) {
                        // console.log(response)
                        let html = ``;
                        html += `<tr>`;
                        html += `<td class = "text-center" >${response.nipd}</td>`;
                        html += `<td class = "text-center" >${response.nama}</td>`;
                        html += `<td class = "text-center" >${response.jurusan}</td>`;
                        html += `<td class = "text-center" >`;
                        if (response.aktif != 0) {
                            html += `<strong>${response.status}</strong>`;
                        } else {
                            if (response.status != 1) {
                                html += `<strong>${response.status}</strong>`;
                            } else {
                                html += `<a class="btn btn-xs btn-success" id="btn_aktifasi_manual">Aktifkan</a>`;
                            }
                        }
                        html += `</td>`;
                        html += `<td class = "text-center" ><i>`;
                        if (response.kewajiban === 0) {
                            html += `Lunas`;
                        }else{
                            html += `Rp.${parseInt(response.kewajiban).toLocaleString()}`;
                        }
                        html += `</i></td>`;
                        html += `</tr>`;
                        $("#data_mhs_tbody").html(html);
                        $('#btn_aktifasi_manual').on('click', function() {
                            $.ajax({
                                type: "POST",
                                url: "aktifkan_manual",
                                data: {
                                    nipd: nim,
                                    jns_aktifasi: jns_aktifasi
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.status != 200) {
                                        // error
                                        swal.fire("Error!", response.msg, "error");
                                        $('.swal2-confirm').click(function() {
                                            location.reload();
                                        });
                                    } else {
                                        // success
                                        swal.fire("Sukses!", response.msg, "success");
                                        $('.swal2-confirm').click(function() {
                                            location.reload();
                                        });
                                    }
                                }
                            });
                        });
                    }
                });
            });

        });
    </script>
</div>
<!-- END Page Content -->