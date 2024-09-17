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

    .leftPosition {
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
<script src="<?= base_url() ?>assets/js/hightcharts/highcharts.js"></script>
<script src="<?= base_url() ?>assets/js/hightcharts/accessibility.js"></script>
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
                    <button class="btn btn-success btn-xs px-10"><i class="fa fa-print"></i> | Data Belum Bayar</button>
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
                                style="font-size:1rem; font-weight: 700;">SUDAH MELUNASI</br>PEMBAYARAN SPP</small>
                        </th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;"></br><small
                                style="font-size:1rem; font-weight: 700;">MHS DISPEN</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;"></br><small
                                style="font-size:1rem; font-weight: 700;">BELUM MELAKUKAN</br>PEMBAYARAN SPP</small>
                        </th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;"></br><small
                                style="font-size:1rem; font-weight: 700;">SUDAH MELAKUKAN</br>PEMBAYARAN SPP</small>
                        </th>
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
        <div class="leftPosition">
            <select class="form-select" id="chartTypeSelect">
                <option value="bar">Bar</option>
                <option value="line">Line</option>
                <option value="area">Area</option>
                <option value="column">Column</option>
            </select>
        </div>
        <div>
            <!-- <canvas id="myChart"></canvas> -->
            <figure class="highcharts-figure">
                <div id="myChart"></div>
            </figure>
        </div>

    </div>
    <script>
        function createChart(type) {
            $.ajax({
                type: "POST",
                url: "dashboard_chart_v2/getDataPembayaranChart",
                dataType: "json",
                success: function (response) {
                    Highcharts.chart('myChart', {
                        chart: {
                            type: type
                        },
                        title: {
                            text: 'Histori Data Pembayaran Cicilan Per Semester',
                            style: {
                                fontFamily: 'poppins'
                            },
                            align: 'left'
                        },
                        subtitle: {
                            text: 'Source: <a href="/siskeu.new/transaksi" target="_blank">Transaksi</a>',
                            align: 'left',
                            style: {
                                fontFamily: 'poppins',
                                fontSize: '12px'
                            }
                        },
                        xAxis: {
                            categories: response.dataChart.labels,
                            labels: {
                                style: {
                                    fontSize: '15px',
                                    fontFamily: 'poppins'
                                }
                            },
                            title: {
                                text: null
                            },
                            gridLineWidth: 1,
                            lineWidth: 0
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Population (mahasiswa)',
                                align: 'high',
                                style: {
                                    fontFamily: 'poppins',
                                    fontSize: '10px'
                                }
                            },
                            labels: {
                                overflow: 'justify'
                            },
                            gridLineWidth: 0
                        },
                        tooltip: {
                            valueSuffix: ' mahasiswa',
                            style: {
                                fontSize: '12px'
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: '50%',
                                dataLabels: {
                                    enabled: true
                                },
                                groupPadding: 0.1
                            }
                        },
                        legend: {
                            enabled: true,
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: 0,
                            y: 0,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                            shadow: true,
                            style: {
                                fontFamily: 'poppins',
                                fontSize: '14px'
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                            name: '# Cicilan 1',
                            data: response.dataChart.datasets.C1
                        }, {
                            name: '# Cicilan 2',
                            data: response.dataChart.datasets.C2
                        }, {
                            name: '# Cicilan 3',
                            data: response.dataChart.datasets.C3
                        }]
                    });
                }
            });
        }

        // Initial chart load
        createChart('bar');

        // Update chart on select box change
        $('#chartTypeSelect').on('change', function () {
            var selectedType = $(this).val();
            createChart(selectedType);
        });
    </script>

    <script>
        var e = document.getElementById("single-select-field");
        var dataSelected = e.value;
        console.log(dataSelected);
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
                console.log(response)
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
                    if (val.trx > val.jml_mhs) {
                        val.jml_mhs = val.trx;
                    }
                    total_mhs += val.jml_mhs;
                    total_all_trx += val.trx;
                    total_dispen += val.data_dispen;
                    total_belum_bayar = (total_mhs - total_all_trx)
                    total_lunas_spp = (total_all_trx - total_dispen)
                    html += `<tr>`;
                    html += `<td class="text-center">${no}</td>`;
                    html += `<td class="text-center"><strong>${val.tahun_masuk}</strong><br><small style="font-size:0.85rem;;">${status}</small></td>`;
                    html += `<td class="text-center">${val.jml_mhs}</td>`;
                    html += `<td class="text-center">${val.trx - val.data_dispen}</td>`;
                    html += `<td class="text-center">${val.data_dispen}</td>`; //betul
                    html += `<td class="text-center">${val.jml_mhs - val.trx}</td>`;
                    html += `<td class="text-center">${val.trx}</td>`;
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