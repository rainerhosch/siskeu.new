<style>
    .form_invoice2 {
        /* background-color: #110d0dcf; */
        border-radius: 5px;
    }

    .form_invoice {
        /* background-color: #bcbcbccf; */
        border-radius: 5px;
    }

    .modal-dialog {
        width: 70%;
        height: 70%;
    }

    .modal-content {
        height: auto;
        min-height: 70%;
        border-radius: 0;
        justify-content: center;
    }

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
    .modal_datatable {
        font-size: 8px;
    }

    /* Modal Content/Box */
    .modal-content2 {
        text-align: center;
        background-color: #fefefe;
        margin: 5% auto 15% auto;
        border: 1px solid #888;
        width: 80%;
    }

    /* Style the horizontal ruler */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    .modal-notify .modal-header {
        border-radius: 3px 3px 0 0;
    }

    .modal-notify .modal-content {
        border-radius: 3px;
    }

    table {
        background-color: #ffffff;
    }
</style> <!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <div class="row" id="alert_mhs">
        <?= $this->session->flashdata('message'); ?>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> Mahasiswa</h2>
                        </div>
                        <div class="table-responsive">
                            <div id="example-datatable_wrapper" class="dataTables_wrapper form-inline no-footer">
                                <div class="row">
                                    <div class="col-sm-6 col-xs-5">
                                        <div class="dataTables_length">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-7">
                                        <div id="example-datatable_filter" class="dataTables_filter">
                                            <label>
                                                <div class="input-group">
                                                    <input type="search" class="form-control" placeholder="Search" aria-controls="example-datatable" id="form_cari">
                                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                        <!-- table table-vcenter table-condensed table-bordered mb-5 dataTable no-footer -->
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">NIM</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">Jurusan</th>
                                                <th class="text-center">Jenjang Didik</th>
                                                <th class="text-center">Tahun Masuk</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_mhs_tbody">
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-sm-5 hidden-xs">
                                        <div class="dataTables_info" id="datatable_info" role="status" aria-live="polite"></div>
                                    </div>
                                    <!-- Paginate -->
                                    <div class="col-sm-7 col-xs-12 clearfix">
                                        <div class="dataTables_paginate paging_bootstrap" id='pagination'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            loadPagination(0);
            setTimeout(function() {
                $("#alert_mhs").html("");
            }, 2000);

            $("#form_cari").on("keyup change", function(e) {
                e.preventDefault();
                let keyword = $(this).val();
                loadFilter(keyword);
            });
            $('#pagination').on('click', 'a', function(e) {
                e.preventDefault();
                let limit = $('#datatable_length').val();
                let offset = $(this).attr('data-ci-pagination-page');
                loadPagination(limit, offset);
            });

            // Load pagination
            function loadFilter(keyword) {
                $.ajax({
                    url: 'GetDataMhs',
                    type: 'POST',
                    data: {
                        keyword: keyword
                    },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        let user_log = response.user_loged;
                        let data_mhs = response.data_mhs;
                        let total_data = response.total_result;
                        let offset = response.row;
                        $('#pagination').html(response.pagination);
                        // $('#datatable_info').html(`<strong>1</strong>-<strong>10</strong> of <strong>${total_data}</strong>`);
                        createTable(user_log, data_mhs, total_data, offset);
                    }
                });
            }
            // Load pagination
            function loadPagination(limit, offset) {
                $.ajax({
                    url: 'GetDataMhs/' + offset,
                    type: 'POST',
                    data: {
                        offset: offset,
                        limit: limit
                    },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        let user_log = response.user_loged;
                        let data_mhs = response.data_mhs;
                        let total_data = response.total_result;
                        let offset = response.row;
                        $('#pagination').html(response.pagination);
                        createTable(user_log, data_mhs, total_data, offset);
                    }
                });
            }

            function createTable(user_log, data_mhs, total_data, offset) {
                let html = ``;
                offset = Number(offset);
                $('#example-datatable tbody').empty();

                if (data_mhs != 0) {
                    let numEnd = offset + 10;
                    $('#datatable_info').html(`<strong>${offset+1}</strong>-<strong>${numEnd}</strong> dari <strong>${total_data}</strong> Record`);
                    no = offset;
                    $.each(data_mhs, function(i, value) {
                        no++;
                        html += `<tr>`;
                        html += `<td class = "text-center" >${no}</td>`;
                        html += `<td class="text-center">${value.nipd}</td>`;
                        html += `<td class="text-center">${value.nm_pd}</td>`;
                        html += `<td class="text-center">${value.nm_jur}</td>`;
                        html += `<td class="text-center">${value.nm_jenj_didik}</td>`;
                        html += `<td class="text-center">${value.tahun_masuk}</td>`;
                        html += `<td class="text-center">`;
                        html += `<a href="#" class="badge badge-warning btn_cek_update" id="btn_cek_update_${value.id_pd}" value="${value.id_pd}" nipd="${value.nipd}"><i class="fa fa-sync" id="icon_sync_mhs_${value.id_pd}"></i></a>`;
                        html += `</td>`;
                        html += `</tr>`;
                    });
                } else {
                    html += `<tr>`;
                    html += `<td colspan="12" class="text-center"><br>`;
                    html += `<div class='col-lg-12'>`;
                    html += `<div class='alert alert-danger alert-dismissible'>`;
                    html += `<h4><i class='icon fa fa-warning'></i> Tidak Ada Data Mahasiswa!</h4>`;
                    html += `</div>`;
                    html += `</div>`;
                    html += `</td>`;
                    html += `</tr>`;
                }
                $("#data_mhs_tbody").html(html);
                $('.btn_cek_update').on('click', function() {
                    let id_pd = $(this).attr("value");
                    let nipd = $(this).attr("nipd");
                    $('#icon_sync_mhs' + id_pd).attr('class', 'fa fa-sync fa-spin');
                    // console.log(id_pd);
                    $.ajax({
                        url: '<?= base_url() ?>sync-simak/syncUpdateMhsById',
                        type: 'POST',
                        data: {
                            id_pd: id_pd,
                            nipd: nipd
                        },
                        serverSide: true,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status !== 200) {
                                $('#icon_sync_reg_ujian').attr('class', 'fa fa-sync');
                                swal.fire("Error!", response.msg, "error");
                                $('.swal2-confirm').click(function() {
                                    location.reload();
                                });
                            } else {
                                // success
                                $('#icon_sync_reg_ujian').attr('class', 'fa fa-sync');
                                swal.fire("Sukses!", response.msg, "success");
                                $('.swal2-confirm').click(function() {
                                    location.reload();
                                });
                            }
                        }
                    })
                });

            }


            // $.ajax({
            //     type: "POST",
            //     url: 'GetDataMhs',
            //     data: {
            //         keyword: keyword
            //     },
            //     dataType: "json",
            //     success: function(response) {
            //         console.log(response);
            //         let html = ``;
            //         $.each(response, function(i, value) {
            //             i++;
            //             html += `<tr>`;
            //             html += `<td class="text-center">${i}</td>`;
            //             html += `<td class="text-center"><strong>${value.nipd}</strong></td>`;
            //             html += `<td class="text-center"><strong>${value.nm_pd}</strong></td>`;
            //             html += `<td class="text-center"><strong>${value.nm_jur}</strong></td>`;
            //             html += `<td class="text-center"><strong>${value.nm_jenj_didik}</strong></td>`;
            //             html += `<td class="text-center"><strong>${value.tahun_masuk}</strong></td>`;
            //             html += `<td class="text-center">`;
            //             html += `<a href="#" class="badge badge-warning edit-biaya" id="btn_edit_biaya" value="${value.id_pd}"><i class="far fa-edit"></i></a>`;
            //             html += `</td>`;
            //             html += `</tr>`;
            //         })
            //         $("#data_mhs_tbody").html(html);
            //         $(function() {
            //             TablesDatatables.init();
            //         });
            //     }
            // });
        });
    </script>
</div>
<!-- END Page Content -->