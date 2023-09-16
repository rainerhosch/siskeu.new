<style>
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
    <!-- Example Block -->
    <div class="block">
        <!-- <img id="loading-image" src="https://www.boasnotas.com/img/loading2.gif" style="display:none;"/> -->
        <div class="block-title">
            <div class="heder-table">
                <h2>Data Semester <span id="smt_aktif"></span></h2>
                <div class="leftHeader">
                    <select class="form-select" id="single-select-field" data-placeholder="Choose one thing">
                        <option value="">-- Pilih --</option>
                        <option value="0" selected>Select All</option>
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
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">No</th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TAHUN</br>ANGKATAN</th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS DITERIMA</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS DAFTAR ULANG</br>(Aktif KRS)</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS LULUS</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS</br>CUTI/PINDAH/DO</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS AKTIF</br><span class="smt_befor"></span></small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS LULUS</br><span class="smt_befor"></span></small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS AKTIF</br></small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">SUDAH MELAKUKAN</br>PEMBAYARAN SPP</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">MHS DISPEN</small></th>
                        <th class="text-center" style="font-size:1.2rem; font-weight: 700;">TOTAL</br><small style="font-size:1rem; font-weight: 700;">BELUM MELAKUKAN</br>PEMBAYARAN SPP</small></th>
                        <th class="text-center"  style="font-size:1.2rem;font-weight: 700;">PERSENTASE</br><small style="font-size:1rem; font-weight: 700;">SUDAH MELAKUKAN PEMBAYARAN</small></th>
                        <th class="text-center"  style="font-size:1.2rem;font-weight: 700;">PERSENTASE</br><small style="font-size:1rem; font-weight: 700;">SUDAH MELUNASI CICILAN</small></th>
                    </tr>
                </thead>
                <tbody id="data_pembayaran_angkatan_modal">
                </tbody>
                <tfoot id="data_pembayaran_angkatan_tfoot"></tfoot>
            </table>
        </div>
    </div>

    <script>
        let year_now = new Date().getFullYear();
        var getUrl = window.location;
        var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
        // console.log(year_now)

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
                url: "dashboard_chart/getDataPembayaran",
                // url: "dashboard_chart/getDataPembayaranV2",
                data:{
                    filter: data
                },
                dataType: "json",
                beforeSend: function() {
                    Swal.fire({
                        title: 'Proses Pengumpulan Data . . .',
                        imageUrl: `${baseUrl}/assets/img/loading.gif`,
                        // imageHeight: 1500,
                        allowOutsideClick: false,
                        showConfirmButton: false,  
                        imageAlt: 'Loading image'
                    })
                // Swal.fire({
                //     title: 'Loading . . .',
                //     // position: 'top',
                //     width: 300,
                //     allowOutsideClick: false,
                //     showConfirmButton: false,  
                //     color: '#716add',
                //     // background: `#fff url(${baseUrl}/assets/images/trees.png)`,
                //     backdrop: `
                //         rgba(0,0,123,0.4)
                //         url('${baseUrl}/assets/img/loading.gif')
                //         center
                //         no-repeat
                //     `
                // })
            },
            success: function(response) {
                console.log(response);
                swal.close()
                $('#smt_aktif').html(response.smt_aktif);
                $('.smt_befor').html(response.smt_befor);
                html = ``;
                let no = 1;
                let total_mhs=0;
                let total_all_trx=0;
                let total_trx = 0;
                let ttl_belum_bayar_spp=0;
                let ttl_dispen = 0;
                let ttl_mhs_daftar_ulang = 0;
                let ttl_mhs_aktif_smtlalu = 0;
                let ttl_mhs_lulus = 0;
                let ttl_mhs_tanpa_keterangan = 0;
                let ttl_mhs_aktif = 0;
                let ttl_lulus_smt_lalu = 0;
                $.each(response.data, function(i, val) {
                    let jml_mhs_lulus = val.jml_mhs;
                    let jml_mhs_aktif = 0;
                    let jml_mhs_aktif_smt_lalu = 0;
                    let jml_mhs_lulus_smt_lalu = 0;
                    let status = '(LULUS)';
                    let jml_belum_bayar_spp=0;
                    // console.log(val.tahun_masuk)
                    // console.log(year_now-val.tahun_masuk)
                    // total_trx = val.data_trx.length;
                    total_trx = val.trx;
                    ttl_dispen += val.data_dispen;
                    total_mhs += val.jml_mhs;
                    total_all_trx = total_all_trx + total_trx;

                    $.each(val.list_mhs, function(j, lm){
                        if(lm.no_transkip_nilai == null){
                            jml_mhs_lulus -= 1;
                        }
                        if(lm.krs != null){
                            jml_mhs_aktif += 1;
                        }
                        if(lm.krs_befor != null){
                            jml_mhs_aktif_smt_lalu += 1;
                            if(lm.no_transkip_nilai != null){
                                jml_mhs_lulus_smt_lalu ++;
                            }
                        }
                        
                        // if((lm.no_seri_ijazah != null || lm.no_seri_ijazah != '') && lm.krs_befor != null){
                        //     jml_mhs_lulus_smt_lalu ++;
                        // }
                    });
                    ttl_lulus_smt_lalu = ttl_lulus_smt_lalu + jml_mhs_lulus_smt_lalu;
                    
                    let jml_mhs_tanpa_keterangan = (jml_mhs_aktif-jml_mhs_lulus-jml_mhs_aktif_smt_lalu);
                    // console.log('jml_mhs_krs ' + val.tahun_masuk + ' : ' + jml_mhs_aktif);
                    // console.log('jml_lulus ' + val.tahun_masuk + ' : ' + jml_mhs_lulus);
                    // console.log('jml_lulus ' + val.tahun_masuk + ' : ' + jml_mhs_lulus);
                    // console.log('jml_lulus_smt_lalu ' + val.tahun_masuk + ' : ' + jml_mhs_lulus_smt_lalu);
                    
                    ttl_mhs_daftar_ulang = ttl_mhs_daftar_ulang + jml_mhs_aktif;
                    ttl_mhs_aktif_smtlalu = ttl_mhs_aktif_smtlalu + jml_mhs_aktif_smt_lalu;
                    ttl_mhs_lulus = ttl_mhs_lulus + jml_mhs_lulus;
                    ttl_mhs_tanpa_keterangan = ttl_mhs_tanpa_keterangan + jml_mhs_tanpa_keterangan;
                    jml_mhs_aktif_now = jml_mhs_aktif_smt_lalu-jml_mhs_lulus_smt_lalu;
                    ttl_mhs_aktif = ttl_mhs_aktif + (jml_mhs_aktif_smt_lalu - jml_mhs_lulus_smt_lalu);
                    if((year_now-val.tahun_masuk) <= 0){
                            ttl_mhs_aktif= ttl_mhs_aktif + total_trx;
                            jml_mhs_aktif=total_trx;
                            jml_mhs_tanpa_keterangan = 0;
                            jml_mhs_aktif_now = total_trx;
                    }

                    
                    if((year_now-val.tahun_masuk) < 7){
                        jml_belum_bayar_spp = (jml_mhs_aktif_now-total_trx);
                        ttl_belum_bayar_spp += jml_belum_bayar_spp;
                        status = '';
                    }

                    html += `<tr>`;
                    html += `<td class="text-center">${no}</td>`;
                    html += `<td class="text-center">${val.tahun_masuk} <br><small style="font-size:0.85rem;;">${status}</small></td>`;
                    html += `<td class="text-center">${val.jml_mhs}</td>`;
                    html += `<td class="text-center">${jml_mhs_aktif}</td>`;
                    html += `<td class="text-center">${jml_mhs_lulus}</td>`;
                    html += `<td class="text-center">${jml_mhs_tanpa_keterangan}</td>`;
                    html += `<td class="text-center">${jml_mhs_aktif_smt_lalu}</td>`;
                    html += `<td class="text-center">${jml_mhs_lulus_smt_lalu}</td>`;
                    html += `<td class="text-center">${jml_mhs_aktif_now}</td>`;
                    html += `<td class="text-center">${total_trx}</td>`; //betul
                    html += `<td class="text-center">${val.data_dispen}</td>`; //betul
                    html += `<td class="text-center">${jml_belum_bayar_spp}</td>`; //betul
                    //   html += `<td class="text-center">${Math.ceil((val.trx / val.jml_mhs)*100) }%</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_trx / jml_mhs_aktif_now) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((val.trx / jml_mhs_aktif_now) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((total_trx-val.data_dispen) / jml_mhs_aktif_now) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((val.trx-val.data_dispen) / jml_mhs_aktif_now) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `</tr>`;
                    no++;
                });
                html += `<tr>`;
                html += `<td class="text-center" style="font-weight: 700;">Total</td>`;
                    html += `<td class="text-center"></td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_mhs}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_daftar_ulang}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_lulus}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_tanpa_keterangan}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_aktif_smtlalu}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_lulus_smt_lalu}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_aktif}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_all_trx}</td>`;//betul
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_dispen}</td>`;//betul
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_belum_bayar_spp}</td>`;//betul
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_all_trx / ttl_mhs_aktif) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((total_all_trx/ ttl_mhs_aktif) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((total_all_trx-ttl_dispen) / ttl_mhs_aktif) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((total_all_trx-ttl_dispen)/ ttl_mhs_aktif) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `</tr>`;
                $("#data_pembayaran_angkatan_modal").html(html);
                }
            })
        })
        $.ajax({
            type: "POST",
            // url: "dashboard_chart/getDataMhsPerangkatan",
            url: "dashboard_chart/getDataPembayaran",
            // url: "dashboard_chart/getDataPembayaranV2",
            data:{
                filter: 0
            },
            dataType: "json",
            beforeSend: function() {
                Swal.fire({
                    title: 'Proses Pengumpulan Data . . .',
                    imageUrl: `${baseUrl}/assets/img/loading.gif`,
                    // imageHeight: 1500,
                    allowOutsideClick: false,
                    showConfirmButton: false,  
                    imageAlt: 'Loading image'
                })
            },
            success: function(response) {
                console.log(response);
                swal.close()
                $('#smt_aktif').html(response.smt_aktif);
                $('.smt_befor').html(response.smt_befor);
                html = ``;
                let no = 1;
                let total_mhs=0;
                let total_all_trx=0;
                let total_trx = 0;
                let ttl_belum_bayar_spp=0;
                let ttl_dispen = 0;
                let ttl_mhs_daftar_ulang = 0;
                let ttl_mhs_aktif_smtlalu = 0;
                let ttl_mhs_lulus = 0;
                let ttl_mhs_tanpa_keterangan = 0;
                let ttl_mhs_aktif = 0;
                let ttl_lulus_smt_lalu = 0;
                $.each(response.data, function(i, val) {
                    let jml_mhs_lulus = val.jml_mhs;
                    let jml_mhs_aktif = 0;
                    let jml_mhs_aktif_smt_lalu = 0;
                    let jml_mhs_lulus_smt_lalu = 0;
                    let status = '(LULUS)';
                    let jml_belum_bayar_spp=0;
                    // console.log(val.tahun_masuk)
                    // console.log(year_now-val.tahun_masuk)
                    // total_trx = val.data_trx.length;
                    total_trx = val.trx;
                    ttl_dispen += val.data_dispen;
                    total_mhs += val.jml_mhs;
                    total_all_trx = total_all_trx + total_trx;

                    $.each(val.list_mhs, function(j, lm){
                        if(lm.no_transkip_nilai == null){
                            jml_mhs_lulus -= 1;
                        }
                        if(lm.krs != null){
                            jml_mhs_aktif += 1;
                        }
                        if(lm.krs_befor != null){
                            jml_mhs_aktif_smt_lalu += 1;
                            if(lm.no_transkip_nilai != null){
                                jml_mhs_lulus_smt_lalu ++;
                            }
                        }
                        
                        // if((lm.no_seri_ijazah != null || lm.no_seri_ijazah != '') && lm.krs_befor != null){
                        //     jml_mhs_lulus_smt_lalu ++;
                        // }
                    });
                    ttl_lulus_smt_lalu = ttl_lulus_smt_lalu + jml_mhs_lulus_smt_lalu;
                    
                    let jml_mhs_tanpa_keterangan = (jml_mhs_aktif-jml_mhs_lulus-jml_mhs_aktif_smt_lalu);
                    // console.log('jml_mhs_krs ' + val.tahun_masuk + ' : ' + jml_mhs_aktif);
                    // console.log('jml_lulus ' + val.tahun_masuk + ' : ' + jml_mhs_lulus);
                    // console.log('jml_lulus ' + val.tahun_masuk + ' : ' + jml_mhs_lulus);
                    // console.log('jml_lulus_smt_lalu ' + val.tahun_masuk + ' : ' + jml_mhs_lulus_smt_lalu);
                    
                    ttl_mhs_daftar_ulang = ttl_mhs_daftar_ulang + jml_mhs_aktif;
                    ttl_mhs_aktif_smtlalu = ttl_mhs_aktif_smtlalu + jml_mhs_aktif_smt_lalu;
                    ttl_mhs_lulus = ttl_mhs_lulus + jml_mhs_lulus;
                    ttl_mhs_tanpa_keterangan = ttl_mhs_tanpa_keterangan + jml_mhs_tanpa_keterangan;
                    jml_mhs_aktif_now = jml_mhs_aktif_smt_lalu-jml_mhs_lulus_smt_lalu;
                    ttl_mhs_aktif = ttl_mhs_aktif + (jml_mhs_aktif_smt_lalu - jml_mhs_lulus_smt_lalu);
                    if((year_now-val.tahun_masuk) <= 0){
                            ttl_mhs_aktif= ttl_mhs_aktif + total_trx;
                            jml_mhs_aktif=total_trx;
                            jml_mhs_tanpa_keterangan = 0;
                            jml_mhs_aktif_now = total_trx;
                    }

                    
                    if((year_now-val.tahun_masuk) < 7){
                        jml_belum_bayar_spp = (jml_mhs_aktif_now-total_trx);
                        ttl_belum_bayar_spp += jml_belum_bayar_spp;
                        status = '';
                    }

                    html += `<tr>`;
                    html += `<td class="text-center">${no}</td>`;
                    html += `<td class="text-center">${val.tahun_masuk} <br><small style="font-size:0.85rem;;">${status}</small></td>`;
                    html += `<td class="text-center">${val.jml_mhs}</td>`;
                    html += `<td class="text-center">${jml_mhs_aktif}</td>`;
                    html += `<td class="text-center">${jml_mhs_lulus}</td>`;
                    html += `<td class="text-center">${jml_mhs_tanpa_keterangan}</td>`;
                    html += `<td class="text-center">${jml_mhs_aktif_smt_lalu}</td>`;
                    html += `<td class="text-center">${jml_mhs_lulus_smt_lalu}</td>`;
                    html += `<td class="text-center">${jml_mhs_aktif_now}</td>`;
                    html += `<td class="text-center">${total_trx}</td>`; //betul
                    html += `<td class="text-center">${val.data_dispen}</td>`; //betul
                    html += `<td class="text-center">${jml_belum_bayar_spp}</td>`; //betul
                    //   html += `<td class="text-center">${Math.ceil((val.trx / val.jml_mhs)*100) }%</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_trx / jml_mhs_aktif_now) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((val.trx / jml_mhs_aktif_now) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((total_trx-val.data_dispen) / jml_mhs_aktif_now) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((val.trx-val.data_dispen) / jml_mhs_aktif_now) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `</tr>`;
                    no++;
                });
                html += `<tr>`;
                html += `<td class="text-center" style="font-weight: 700;">Total</td>`;
                    html += `<td class="text-center"></td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_mhs}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_daftar_ulang}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_lulus}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_tanpa_keterangan}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_aktif_smtlalu}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_lulus_smt_lalu}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_mhs_aktif}</td>`;
                    html += `<td class="text-center" style="font-weight: 700;">${total_all_trx}</td>`;//betul
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_dispen}</td>`;//betul
                    html += `<td class="text-center" style="font-weight: 700;">${ttl_belum_bayar_spp}</td>`;//betul
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil((total_all_trx / ttl_mhs_aktif) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil((total_all_trx/ ttl_mhs_aktif) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `<td class="text-center">`;
                    html += `<div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ` + Math.ceil(((total_all_trx-ttl_dispen) / ttl_mhs_aktif) * 100) + `%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">` + Math.ceil(((total_all_trx-ttl_dispen)/ ttl_mhs_aktif) * 100) + `%</div></div>`;
                    html += `</td>`;
                    html += `</tr>`;
                $("#data_pembayaran_angkatan_modal").html(html);
            }
        })
    </script>
    <!-- END Example Block -->
</div>
<!-- END Page Content -->