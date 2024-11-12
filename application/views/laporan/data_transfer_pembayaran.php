<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <div class="row data_historiTX">
                <div class="col-sm-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Data</strong> <?= $page; ?></h2>
                        </div>
                        <div class="table-responsive">
                            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Keterangan Bayar</th>
                                        <th class="text-center">Tgl Transfer</th>
                                        <th class="text-center">Rekening Tujuan</th>
                                        <th class="text-center">Jumlah Transfer</th>
                                        <th class="text-center">Bukti Transfer</th>
                                        <th class="text-center">Status</th>
                                        <!-- <th class="text-center">Status</th> -->
                                    </tr>
                                </thead>
                                <tbody id="tbody_data_trf">
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
    <script>
        $(document).ready(function () {
            var cekLogUser = '<?php echo $_SESSION['username'] ?>';
            var authority = '';
            if (cekLogUser != 'devstt') {
                authority = 'disabled';
            }
            $.ajax({
                url: "<?= base_url() ?>transaksi/get_data_trf_online",
                type: "POST",
                dataType: "JSON",
                data: {
                    filter: null,
                    length: 10,
                    start: 0,
                },
                success: function (response) {
                    let html = ``;
                    let jenis_bayar = ``;
                    let status = ``;
                    let optionSelect = ``;
                    let colorBG = ``;
                    let dataStatus = [
                        {
                            'status': 0,
                            'data': 'BELUM DIVALIDASI'
                        },
                        {
                            'status': 1,
                            'data': 'SUDAH DIVALIDASI'
                        },
                        {
                            'status': "2",
                            'data': 'DITOLAK'
                        }
                    ];
                    console.log(response);
                    if (response != null) {
                        $.each(response.data, function (i, value) {
                            let bank_account = value.bank_penerima;
                            let no = i + 1;
                            if (value.status != 0) {
                                if (value.status != 1) {
                                    colorBG = 'bg-danger';
                                } else {
                                    colorBG = 'bg-success';
                                }
                            } else {
                                colorBG = 'bg-info';
                            }
                            html += `<tr>`;
                            html += `<td class="text-center ${colorBG}" >${no}</td>`;
                            html += `<td class="text-center ${colorBG}" >${value.nipd}</td>`;
                            html += `<td class="text-center ${colorBG}" >`;
                            $.each(value.pembayaran, function (i, val) {
                                if (val != null) {
                                    html += `<i>${val.nm_jenis_pembayaran}</i><br>`;
                                } else {
                                    html += `<i class="text-danger">Pembayaran tidak valid.</i>`;
                                }
                            });
                            html += `</td>`;
                            html += `<td class="text-center ${colorBG}" >${value.tgl_trf}</td>`;
                            html += `<td class="text-center ${colorBG}">`;
                            html += `<i style="font-size:1rem; font-weight: bold;">BANK ${bank_account.bank}</i><br>`;
                            html += `<i style="font-size:1rem; font-weight: bold;">${bank_account.no_rek}</i><br>`;
                            html += `<i style="font-size:1rem; font-weight: bold;">A/N ${bank_account.nama_rekening}</i>`;
                            html += `</td>`;
                            html += `<td class="text-center ${colorBG}">Rp.${parseInt(
                                value.jumlah_bayar
                            ).toLocaleString()}</td>`;
                            html += `<td class="text-center ${colorBG}"><i><a href="#" data-id_trf="${value.id_bukti_trf}" data-type_bayar="${value.jenis_bayar}" class="btn btn-xs btn-info btn_show_bukti_trf" data-trf="${value.img_trf}" data-smt="${value.smt}" data-nipd="${value.nipd}" data-jns="${value.nm_jenis_pembayaran}"><i class="fa fa-fw fa-eye"></i></a></i></td>`;
                            // html += `<td class="text-center ${colorBG} text-white" style="font-size:1rem; font-weight: bold;">${status}</td>`;
                            html += `<td class="text-center ${colorBG} text-white" style="font-size:1rem; font-weight: bold;">
                    <select class="form-control select2" ${authority} style="font-size:1rem; font-weight: bold;" data-trf="${value.id_bukti_trf}">`;
                            $.each(dataStatus, function (j, ds) {
                                if (value.status != ds.status) {
                                    html += `<option value="${ds.status}">${ds.data}</option>`;
                                } else {
                                    html += `<option selected="selected" value="${ds.status}">${ds.data}</option>`;
                                }
                            });
                            html += `</select>
                    </td>`;
                            html += `</tr>`;
                        });


                        $("#tbody_data_trf").html(html);
                        $(".btn_show_bukti_trf").click(function () {
                            let img_trf = $(this).data("trf");
                            let smt = $(this).data("smt");
                            let nipd = $(this).data("nipd");
                            let jns = $(this).data("jns");
                            let type_bayar = $(this).data("type_bayar");
                            let id_bukti_trf = $(this).data("id_trf");

                            Swal.fire({
                                title: "Bukti Transfer " + nipd,
                                text: "Silahkan Cek Data Transfer Tersebut",
                                imageUrl: `https://simak.wastu.digital/assets/${smt}/mahasiswa/bukti_trf/${img_trf}`,
                                imageWidth: 370,
                                imageHeight: 650,
                                // showDenyButton: true,
                                // showCancelButton: true,
                                // confirmButtonText: "Confirm",
                                // confirmButtonColor: "#8bc34a",
                                // denyButtonText: `Reject`,
                                // denyButtonColor: "#d33",
                            });
                        });

                        $(function () {
                            TablesDatatables.init();
                            // $('#example-datatable').DataTable().page.info().recordsTotal = response.total_data;
                            // Update dataTables_info with total_data
                            console.log($('#example-datatable').DataTable().page.info())
                        });

                        $(".select2").on("change", function () {
                            let data = this.value;
                            let idTrf = $(this).data("trf");
                            $.ajax({
                                url: "<?= base_url() ?>transaksi/updateStatusTrf",
                                type: "POST",
                                dataType: "JSON",
                                data: {
                                    data_id: idTrf,
                                    data_update: data
                                },
                                success: function (response) {
                                    console.log(response)
                                    Swal.fire({
                                        icon: `${response.status}`,
                                        title: `${response.msg}`,
                                        html: `<span>id:${idTrf}</span></br><span>data:${data}</span>`,
                                        showConfirmButton: false,
                                        timer: 1500,
                                    }).then(function () {
                                        location.reload();
                                    });
                                }
                            });
                        });
                    }
                },
            });
        });
    </script>
</div>
<!-- END Page Content -->