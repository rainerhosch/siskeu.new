<style>
    .select2-container .select2-selection--single {
        height: 30px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 25px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #e5e5e5;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-left: 8px;
        line-height: 30px;
    }

    .progress {
        overflow: hidden;
        height: 20px;
        margin-bottom: 0px;
        background-color: #cdcdcd;
        border-radius: 4px;
    }

    .table thead>tr>th {
        font-size: 14px;
        font-weight: 600;
    }

    .row {
        margin-bottom: 5px;
    }

    table {
        background-color: #ffffff;
    }

    .heder-table {
        display: flex;
    }

    .leftHeader {
        position: absolute;
        right: 35px;
        margin: 7px;
    }

    .div_loading_table {
        font-size: 20px;
        position: absolute;
        right: 95px;
        margin: 5px;
    }

    .span_loading_table {
        color: #1b598f;
        margin-right: 5px;
        font-size: 20px;
    }

    .table_grouping>thead>tr>th,
    .table_grouping>tbody>tr>th,
    .table_grouping>tfoot>tr>th,
    .table_grouping>thead>tr>td,
    .table_grouping>tbody>tr>td,
    .table_grouping>tfoot>tr>td {
        padding: 4px;
    }
</style>
<script src="<?= base_url() ?>assets/js/chart.js/chart.js"></script>
<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <!-- Example Block -->
    <div class="block">
        <div class="block-title">
            <div class="heder-table">
                <h2>Data Semester <span class="smt_aktif"></span></h2>
                <div class="leftHeader">
                    <span class="span_loading_table"><i class="fa fa-sync icon_load_table" hi></i></span>
                    <select class="form-select" id="single-select-field" data-placeholder="Choose one thing">
                        <option value="">-- Pilih --</option>
                        <!-- <option value="0" selected>Select All</option> -->
                        <option value="2" selected>Cicilan 1</option>
                        <option value="3">Cicilan 2</option>
                        <option value="4">Cicilan 3</option>
                    </select>
                </div>
            </div>
            <!-- <div class="div_loading_table1">
                    <i class="fa fa-sync icon_load_table"  hi></i>
                </div> -->
        </div>
        <div class="table-responsive">
            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">No</th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TAHUN</br>ANGKATAN</th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">JUMLAH</br><small
                                style="font-size:1rem; font-weight: 700;">MHS AKTIF BY KRS</br>
                                <!-- <span class="smt_befor"></span> -->
                            </small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;"></br><small
                                style="font-size:1rem; font-weight: 700;">SUDAH MELAKUKAN</br>PEMBAYARAN SPP</small>
                        </th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;"></br><small
                                style="font-size:1rem; font-weight: 700;">MHS DISPEN</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;"></br><small
                                style="font-size:1rem; font-weight: 700;">BELUM MELAKUKAN</br>PEMBAYARAN SPP</small>
                        </th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;"></br><small
                                style="font-size:1rem; font-weight: 700;">SUDAH MELUNASI</br>PEMBAYARAN SPP</small></th>
                        <th class="text-center" style="font-size:1.2rem;font-weight: 700;">PERSENTASE</br><small
                                style="font-size:1rem; font-weight: 700;">SUDAH MELAKUKAN PEMBAYARAN</small></th>
                        <th class="text-center" style="font-size:1.2rem;font-weight: 700;">PERSENTASE</br><small
                                style="font-size:1rem; font-weight: 700;">SUDAH MELUNASI CICILAN</small></th>
                    </tr>
                </thead>
                <tbody id="data_pembayaran_angkatan_modal">
                </tbody>
                <tfoot id="data_pembayaran_angkatan_tfoot"></tfoot>
            </table>
        </div>
    </div>
    <div class="block">
        <canvas id="myChart"></canvas>
    </div>
    <script>
        $.ajax({
            type: "POST",
            url: "dashboard_chart_v2/getDataPembayaranYear",
            dataType: "json",
            success: function (response) {
                console.log(response)
            }
        })
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            // type: 'line',
            type: 'bar',
            data: {
                labels: ['20201', '20202', '20221', '20222', '20231', '20232'],
                datasets: [
                    {
                        label: '# Cicilan 1',
                        data: [1276, 1235, 1015, 1356, 1401, 1145],
                        borderWidth: 1
                    },
                    {
                        label: '# Cicilan 2',
                        data: [1350, 1221, 1084, 1212, 1053, 1321],
                        borderWidth: 1
                    },
                    {
                        label: '# Cicilan 3',
                        data: [1052, 1025, 1221, 1421, 1126, 1285],
                        borderWidth: 1
                    },

                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart Pembayaran SPP'
                    },
                    legend: {
                        labels: {
                            // This more specific font property overrides the global property
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>

        var e = document.getElementById("single-select-field");
        var dataSelected = e.value;

        $('#single-select-field').select2({
            // theme: "bootstrap-3",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });

        $.ajax({
            type: "POST",
            url: "dashboard_chart_v2/getDataPembayaran",
            data: {
                filter: dataSelected
            },
            dataType: "json",
            beforeSend: function () {
                $('.icon_load_table').attr('class', 'fa fa-sync fa-spin');
                // Swal.fire({
                //     title: 'Proses Pengumpulan Data . . .',
                //     imageUrl: `${baseUrl}/assets/img/loading.gif`,
                //     // imageHeight: 1500,
                //     allowOutsideClick: false,
                //     showConfirmButton: false,  
                //     imageAlt: 'Loading image'
                // })
            },
            success: function (response) {
                // console.log(response)
                $(`.span_loading_table`).attr('hidden', true);
                swal.close()
                $('.smt_aktif').html(response.smt_aktif);
                // $('.smt_befor').html(response.smt_befor);
                // dataSelected === '2' ? $('.smt_befor').html(response.smt_befor) : $('.smt_befor').html(response.smt_aktif)
                html = ``;
                let no = 1;
                let total_mhs = 0;
                let total_all_trx = 0;
                let total_lunas_spp = 0;
                let total_dispen = 0;
                let total_belum_bayar = 0;

                $.each(response.data, function (i, val) {
                    total_mhs += val.jml_mhs;
                    total_all_trx += val.trx;
                    total_dispen += val.data_dispen;
                    total_belum_bayar = (total_mhs - total_all_trx)
                    total_lunas_spp = (total_all_trx - total_dispen)
                    html += `<tr>`;
                    html += `<td class="text-center">${no}</td>`;
                    html += `<td class="text-center"><strong>${val.tahun_masuk}</strong><br><small style="font-size:0.85rem;;">${status}</small></td>`;
                    html += `<td class="text-center">${val.jml_mhs}</td>`;
                    html += `<td class="text-center">${val.trx}</td>`;
                    html += `<td class="text-center">${val.data_dispen}</td>`; //betul
                    html += `<td class="text-center">${val.jml_mhs - val.trx}</td>`;
                    html += `<td class="text-center">${val.trx - val.data_dispen}</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((val.trx / val.jml_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((val.trx / val.jml_mhs) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((val.trx - val.data_dispen) / val.jml_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((val.trx - val.data_dispen) / val.jml_mhs) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `</tr>`;
                    no++;
                });
                html += `<tr>`;
                html += `<td class="text-center" style="font-weight: 700;">Total</td>`;
                html += `<td class="text-center"></td>`;
                html += `<td class="text-center" style="font-weight: 700;">${total_mhs}</td>`;
                html += `<td class="text-center" style="font-weight: 700;">${total_all_trx}</td>`;
                html += `<td class="text-center" style="font-weight: 700;">${total_dispen}</td>`;//betul
                html += `<td class="text-center" style="font-weight: 700;">${total_belum_bayar}</td>`;
                html += `<td class="text-center" style="font-weight: 700;">${total_lunas_spp}</td>`;
                html += `<td class="text-center">`;
                html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_all_trx / total_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((total_all_trx / total_mhs) * 100) + `%</div></div>`;
                html += `</td>`;
                html += `<td class="text-center">`;
                html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((total_all_trx - total_dispen) / total_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((total_all_trx - total_dispen) / total_mhs) * 100) + `%</div></div>`;
                html += `</td>`;
                html += `</tr>`;
                $("#data_pembayaran_angkatan_modal").html(html);
            }
        });

        $('.form-select').on('change', function () {
            let data = $(this).val();
            console.log(data);
            $.ajax({
                type: "POST",
                url: "dashboard_chart_v2/getDataPembayaran",
                // url: "dashboard_chart/getDataPembayaranV2",
                data: {
                    filter: data
                },
                dataType: "json",
                beforeSend: function () {
                    $(`.span_loading_table`).attr('hidden', false);
                    $('.icon_load_table').attr('class', 'fa fa-sync fa-spin');
                },
                success: function (response) {
                    console.log(response)
                    $(`.span_loading_table`).attr('hidden', true);
                    swal.close()
                    $('.smt_aktif').html(response.smt_aktif);
                    // $('.smt_befor').html(response.smt_befor);
                    // dataSelected === 2 ? $('.smt_befor').html(response.smt_befor) : $('.smt_befor').html(response.smt_aktif)
                    html = ``;
                    let no = 1;
                    let total_mhs = 0;
                    let total_all_trx = 0;
                    let total_lunas_spp = 0;
                    let total_dispen = 0;
                    let total_belum_bayar = 0;

                    $.each(response.data, function (i, val) {
                        total_mhs += val.jml_mhs;
                        total_all_trx += val.trx;
                        total_dispen += val.data_dispen;
                        total_belum_bayar = (total_mhs - total_all_trx)
                        total_lunas_spp = (total_all_trx - total_dispen)
                        html += `<tr>`;
                        html += `<td class="text-center">${no}</td>`;
                        html += `<td class="text-center"><strong>${val.tahun_masuk}</strong><br><small style="font-size:0.85rem;;">${status}</small></td>`;
                        html += `<td class="text-center">${val.jml_mhs}</td>`;
                        html += `<td class="text-center">${val.trx}</td>`;
                        html += `<td class="text-center">${val.data_dispen}</td>`; //betul
                        html += `<td class="text-center">${val.jml_mhs - val.trx}</td>`;
                        html += `<td class="text-center">${val.trx - val.data_dispen}</td>`;
                        html += `<td class="text-center">`;
                        html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((val.trx / val.jml_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((val.trx / val.jml_mhs) * 100) + `%</div></div>`;
                        html += `</td>`;
                        html += `<td class="text-center">`;
                        html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((val.trx - val.data_dispen) / val.jml_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((val.trx - val.data_dispen) / val.jml_mhs) * 100) + `%</div></div>`;
                        html += `</td>`;
                        html += `</tr>`;
                        no++;
                    });
                    html += `<tr>`;
                    html += `<td class="text-center" style="font-weight: 700;">Total</td>`;
                    html += `<td class="text-center"></td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_mhs}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_all_trx}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_dispen}</td>`;//betul
                    html += `<td class="text-center" style="font-weight: 700;">${total_belum_bayar}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_lunas_spp}</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_all_trx / total_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((total_all_trx / total_mhs) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((total_all_trx - total_dispen) / total_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((total_all_trx - total_dispen) / total_mhs) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `</tr>`;
                    $("#data_pembayaran_angkatan_modal").html(html);
                }
            })
        })



    </script>
</div>