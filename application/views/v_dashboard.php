<style>
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
    .heder-table{
        display: flex;
    }
    .leftHeader{
        /* margin-top: 3px;
        margin-left: 60%; */
        position: absolute;
        right: 35px;
        margin: 3px;
    }
</style>
<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>
    <?php // $this->load->view('layout/stat_row'); 
    ?>
    <?php $this->load->view('layout/row_sync_data'); ?>
    <!-- End -->


    <!-- Example Block -->
    <div class="block">
        <div class="block-title">
            <div class="heder-table">
                <h2>Data Semester <span id="smt_aktif"></span></h2>
                <div class="leftHeader">
                    <select class="form-select" id="single-select-field" data-placeholder="Choose one thing">
                        <option value="">-- Pilih --</option>
                        <option value="2">Cicilan 1</option>
                        <option value="3">Cicilan 2</option>
                        <option value="4">Cicilan 3</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">TAHUN ANGKATAN</th>
                        <th class="text-center">TOTAL</br><small style="font-size:1rem; font-weight: bold;">MAHSISWA YANG DITERIMA</small></th>
                        <th class="text-center">TOTAL</br><small style="font-size:1rem; font-weight: bold;">MAHSISWA AKTIF</small></th>
                        <th class="text-center">TOTAL</br><small style="font-size:1rem; font-weight: bold;">SUDAH MELAKUKAN PEMBAYARAN SPP</small></th>
                        <th class="text-center">TOTAL</br><small style="font-size:1rem; font-weight: bold;">DISPENSASI</small></th>
                        <th class="text-center">TOTAL</br><small style="font-size:1rem; font-weight: bold;">BELUM MELAKUKAN PEMBAYARAN SPP</small></th>
                        <th class="text-center">PERSENTASE</th>
                    </tr>
                </thead>
                <tbody id="data_pembayaran_angkatan_modal">
                </tbody>
                <tfoot id="data_pembayaran_angkatan_tfoot"></tfoot>
            </table>
        </div>
    </div>

    <script>
        $('#single-select-field' ).select2({
            // theme: "bootstrap-3",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
        $('.form-select').on('change', function(){
            let data = $( this ).val();
            // console.log( data );
            $.ajax({
                type: "POST",
                url: "masterdata/getDataPembayaranDashboard",
                data:{
                    filter: data
                },
                dataType: "json",
                success: function(response) {
                console.log(response);
                $('#smt_aktif').html(response.smt_aktif);
                html = ``;
                let no = 1;
                let total_mhs=0;
                let total_all_trx=0;
                let ttl_belum_bayar_spp=0;
                $.each(response.data, function(i, val) {
                    let total_trx = val.data_trx.length;
                    total_mhs += val.jml_mhs;
                    total_all_trx += total_trx;
                    let jml_belum_bayar_spp = (val.jml_mhs-total_trx);
                    ttl_belum_bayar_spp += jml_belum_bayar_spp;
                    html += `<tr>`;
                    html += `<td class="text-center">${no}</td>`;
                    html += `<td class="text-center">${val.tahun_masuk}</td>`;
                    html += `<td class="text-center">${val.jml_mhs}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${total_trx}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${jml_belum_bayar_spp}</td>`;
                    //   html += `<td class="text-center">${Math.ceil((val.trx / val.jml_mhs)*100) }%</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
            <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_trx / val.jml_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((val.trx / val.jml_mhs) * 100) + `%</div>
            </div>`;
                    html += `</td>`;
                    html += `</tr>`;
                    no++;
                });
                html += `<tr>`;
                html += `<td class="text-center">Total</td>`;
                    html += `<td class="text-center"></td>`;
                    html += `<td class="text-center">${total_mhs}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${total_all_trx}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${ttl_belum_bayar_spp}</td>`;
                    html += `<td class="text-center"></td>`;
                    html += `</tr>`;
                    $("#data_pembayaran_angkatan_modal").html(html);

                }
            })
        })
        $.ajax({
            type: "POST",
            url: "masterdata/getDataPembayaranDashboard",
            data:{
                filter: 0
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                $('#smt_aktif').html(response.smt_aktif);
                html = ``;
                let no = 1;
                let total_mhs=0;
                let total_all_trx=0;
                let ttl_belum_bayar_spp=0;
                $.each(response.data, function(i, val) {
                    let total_trx = val.data_trx.length;
                    total_mhs += val.jml_mhs;
                    total_all_trx += total_trx;
                    let jml_belum_bayar_spp = (val.jml_mhs-total_trx);
                    ttl_belum_bayar_spp += jml_belum_bayar_spp;
                    html += `<tr>`;
                    html += `<td class="text-center">${no}</td>`;
                    html += `<td class="text-center">${val.tahun_masuk}</td>`;
                    html += `<td class="text-center">${val.jml_mhs}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${total_trx}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${jml_belum_bayar_spp}</td>`;
                    //   html += `<td class="text-center">${Math.ceil((val.trx / val.jml_mhs)*100) }%</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
            <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_trx / val.jml_mhs) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((val.trx / val.jml_mhs) * 100) + `%</div>
            </div>`;
                    html += `</td>`;
                    html += `</tr>`;
                    no++;
                });
                html += `<tr>`;
                html += `<td class="text-center">Total</td>`;
                    html += `<td class="text-center"></td>`;
                    html += `<td class="text-center">${total_mhs}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${total_all_trx}</td>`;
                    html += `<td class="text-center">-</td>`;
                    html += `<td class="text-center">${ttl_belum_bayar_spp}</td>`;
                    html += `<td class="text-center"></td>`;
                    html += `</tr>`;
                $("#data_pembayaran_angkatan_modal").html(html);

            }
        })
    </script>
    <!-- END Example Block -->
</div>
<!-- END Page Content -->