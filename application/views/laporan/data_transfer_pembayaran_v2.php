<!-- Page content -->
<link rel="stylesheet" href="<?= base_url() ?>assets/template/dataTable/2.1.8/css/dataTables.dataTables.css" />
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" /> -->
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
                                    </tr>
                                </thead>
                                <!-- <tbody id="tbody_data_trf">
                                </tbody> -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/template/dataTable/2.1.8/js/dataTables.js"></script>
    <!-- <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script> -->
    <script>
        $(document).ready(function () {
            var cekLogUser = '<?php echo $_SESSION['username'] ?>';
            var authority = '';
            if (cekLogUser != 'devstt') {
                authority = 'disabled';
            }

            $('#example-datatable').DataTable({
                "columnDefs": [
                    { "className": 'dt-center', targets: '_all' } // Memusatkan semua kolom
                    // Atau, untuk memusatkan kolom tertentu:
                    // { "className": 'dt-center', "targets": [0, 2] } // Memusatkan kolom pertama
                ],
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "ajax": {
                    "url": "<?= base_url() ?>transaksi/get_data_trf_online_v2",
                    "type": "POST",
                    // "data": function (d) {
                    //     d.filter = null; // Tambahkan filter jika diperlukan
                    // },
                    // "success": function (response) {
                    //     console.log(response)
                    // }
                },
                "columns": [
                    {
                        "data": null, "searching": false, "render": function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "nipd", "searching": true,},
                    {
                        "data": "pembayaran", "render": function (data, type, row) {
                            let keterangan = '';
                            if (data == null || data.length === 0) {
                                keterangan = 'Pembayaran tidak valid.';
                            } else {
                                data.forEach(function (item) {
                                    keterangan += `<i style="font-size:1rem; font-weight: bold;">${item.nm_jenis_pembayaran}</i><br>`;
                                });
                                keterangan = keterangan.slice(0, -2); // Menghapus koma terakhir
                            }
                            return keterangan;
                        }
                    },
                    { "data": "tgl_trf" },
                    {
                        "data": "bank_penerima", "render": function (data, type, row) {
                            let bank_account = data;
                            return `<i style="font-size:1rem; font-weight: bold;">BANK ${bank_account.bank}</i><br>
                                <i style="font-size:1rem; font-weight: bold;">${bank_account.no_rek}</i><br>
                                <i style="font-size:1rem; font-weight: bold;">A/N ${bank_account.nama_rekening}</i>`;
                        }
                    },
                    {
                        "data": "jumlah_bayar", "render": function (data) {
                            return 'Rp.' + parseInt(data).toLocaleString();
                        }
                    },
                    {
                        "data": null, "render": function (data, type, row) {
                            return `<td class="text-center ${parseInt(row.status) === 0 ? 'bg-info' : parseInt(row.status) === 1 ? 'bg-success' : 'bg-danger'}"><i><a href="#" data-id_trf="${row.id_bukti_trf}" data-type_bayar="${row.jenis_bayar}" class="btn btn-xs btn-info btn_show_bukti_trf" data-trf="${row.img_trf}" data-smt="${row.smt}" data-nipd="${row.nipd}" data-jns="${row.nm_jenis_pembayaran}"><i class="fa fa-fw fa-eye"></i></a></i></td>`;
                        }
                    },
                    {
                        "data": null, "render": function (data, type, row) {
                            let statusText = '';
                            switch (parseInt(row.status)) {
                                case 0:
                                    statusText = 'BELUM DIVALIDASI';
                                    break;
                                case 1:
                                    statusText = 'SUDAH DIVALIDASI';
                                    break;
                                case 2:
                                    statusText = 'DITOLAK';
                                    break;
                            }
                            return `<select class="form-control select2" data-trf="${row.id_bukti_trf}">
                                    <option value="0" ${parseInt(row.status) === 0 ? 'selected' : ''}>BELUM DIVALIDASI</option>
                                    <option value="1" ${parseInt(row.status) === 1 ? 'selected' : ''}>SUDAH DIVALIDASI</option>
                                    <option value="2" ${parseInt(row.status) === 2 ? 'selected' : ''}>DITOLAK</option>
                                </select>`;
                        }
                    },
                ],
                "createdRow": function (row, data, dataIndex) {
                    let colorBG = '';
                    if (data.status == 0) {
                        colorBG = 'bg-info';
                    } else if (data.status == 1) {
                        colorBG = 'bg-success';
                    } else {
                        colorBG = 'bg-danger';
                    }
                    $(row).addClass(colorBG);
                },
            });
            $(".dataTables_filter input").attr("placeholder", "Cari by nim");

            $(document).on("click", ".btn_show_bukti_trf", function () {
                let img_trf = $(this).data("trf");
                let smt = $(this).data("smt");
                let nipd = $(this).data("nipd");
                Swal.fire({
                    title: "Bukti Transfer " + nipd,
                    text: "Silahkan Cek Data Transfer Tersebut",
                    imageUrl: `https://simak.wastu.digital/assets/${smt}/mahasiswa/bukti_trf/${img_trf}`,
                    imageWidth: 370,
                    imageHeight: 650,
                });
            });

            $(document).on("change", ".select2", function () {
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
                        Swal.fire({
                            icon: `${response.status}`,
                            title: `${response.msg}`,
                            html: `<span>id:${idTrf}</span></br><span>data:${data}</span>`,
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(function () {
                            $('#example-datatable').DataTable().ajax.reload();
                        });
                    }
                });
            });
        });
    </script>
</div>
<!-- END Page Content -->