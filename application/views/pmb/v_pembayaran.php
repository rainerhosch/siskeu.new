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
    .heder-table{
        display: flex;
    }
    .leftHeader{
        position: absolute;
        right: 35px;
        margin: 7px;
    }
    .div_loading_table{
        font-size: 20px;
        position: absolute;
        right: 95px;
        margin: 5px;
    }
    .span_loading_table{
        color: #1b598f;
        margin-right: 5px;
        font-size: 20px;
    }
    .table_grouping>thead>tr>th, .table_grouping>tbody>tr>th, .table_grouping>tfoot>tr>th, .table_grouping>thead>tr>td, .table_grouping>tbody>tr>td, .table_grouping>tfoot>tr>td {
        padding: 4px;
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
        <div class="block-title">
            <div class="heder-table">
                <h2>Data Transfer Pembayaran PMB <span class="smt_aktif"></span></h2>
                <!-- <div class="leftHeader">
                    <span class="span_loading_table"><i class="fa fa-sync icon_load_table"  hi></i></span>
                    <select class="form-select" id="single-select-field" data-placeholder="Choose one thing">
                        <option value="">-- Pilih --</option>
                        <option value="0" selected>Select All</option>
                        <option value="2">Cicilan 1</option>
                        <option value="3">Cicilan 2</option>
                        <option value="4">Cicilan 3</option>
                    </select>
                </div> -->
            </div>
            <!-- <div class="div_loading_table1">
                <i class="fa fa-sync icon_load_table"  hi></i>
            </div> -->
        </div>
        <div class="table-responsive">
            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">No</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">TAHUN</br>PENDAFTARAN</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">KODE</br>PENDAFTARAN</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">NAMA</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">NO HP</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">JURUSAN</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">BIAYA</br>PENDAFTARAN</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">TANGGAL</br>PENDAFTARAN</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">BUKTI</br>TRF</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">PENDAMPING</th>
                        <th class="text-center" style="font-size:1.5rem; font-weight: 700;">STATUS</th>
                    </tr>
                </thead>
                <tbody id="data_pembayaran_pmb_tbody">
                </tbody>
                <tfoot id="data_pembayaran_pmb_tfoot"></tfoot>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '<?= base_url() ?>pmb-gateway/getdatatrf',
                type: 'POST',
                serverSide: true,
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    let html = ``;
                    let classBtn = 'btn_show_bukti_trf_disabled';
                    if(response.data.length > 0){
                        $.each(response.data, function(i, value) {
                            let status_validasi = {};
                            let prodi='';
                            if(value.jurusan1 === '1'){
                                prodi='Mesin';
                            }
                            if(value.jurusan1 === '2'){
                                prodi='Teknik Industri';
                            }
                            if(value.jurusan1 === '3'){
                                prodi='Menejemen Industri';
                            }
                            if(value.jurusan1 === '4'){
                                prodi='Teknik Tekstil';
                            }
                            if(value.jurusan1 === '5'){
                                prodi='Teknik Informatika';
                            }

                            if(value.status === '1'){
                                classBtn = 'btn_show_bukti_trf';
                                status_validasi = {
                                    color:'#ffa700',
                                    label:'Belum divalidasi'
                                };
                            }
                            if(value.status === '4'){
                                status_validasi = {
                                    color:'#ff0000',
                                    label:'Ditolak'
                                };
                            }
                            if(value.status === '2'){
                                status_validasi = {
                                    color:'#51b904',
                                    label:'Sudah divalidasi'
                                };
                            }
                            if(value.pendamping === "" || value.pendamping === null){
                                value.pendamping = "-";
                            }
                            if(value.kode_pendaftaran === null){
                                value.kode_pendaftaran = "-";
                            }
                            
                            html +=`<tr>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${i+1}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${value.created_at.substring(0, 4)}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${value.kode_pendaftaran}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${value.nama}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${value.no_hp}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${prodi}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">Rp. ${parseInt(value.biaya_form).toLocaleString()}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${value.created_at}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;"><a href="#" data-id_trf="${value.id}" data-nama="${value.nama}" data-tahun="${value.created_at.substring(0, 4)}" data-img="${value.bukti_tf}" class="btn btn-xs btn-info ${classBtn}">${value.bukti_tf}</a></i></td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;">${value.pendamping}</td>`;
                            html +=`<td  class="text-center" style="font-size:1.2rem; font-weight: 600;"><i style="color:${status_validasi.color};">${status_validasi.label}</i></td>`;
                            html +=`</tr>`;
                        })
                    }
                    $('#data_pembayaran_pmb_tbody').html(html);
                    // $('#modal_datatable').dataTable();
                    $(function() {
                        TablesDatatables.init();
                    });
                    
                    $(".btn_show_bukti_trf").click(function () {
                        let img_trf = $(this).data("img");
                        let nama = $(this).data("nama");
                        let tahun = $(this).data("tahun");
                        let id_trf_pmb = $(this).data("id_trf");
                        Swal.fire({
                            title: "Bukti Transfer PMB",
                            html:`<p>Nama Pemndaftar : ${nama}</p></br><code>Silahkan Cek Data Transfer Tersebut</code>`,
                            // text: "Silahkan Cek Data Transfer Tersebut",
                            imageUrl: `https://pmb.wastu.digital/assets/${tahun}/bukti_tf/${img_trf}`,
                            imageWidth: 370,
                            imageHeight: 650,
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: "Confirm",
                            confirmButtonColor: "#8bc34a",
                            denyButtonText: `Reject`,
                            denyButtonColor: "#d33",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                accTRF(id_trf_pmb);
                            } else if (result.isDenied) {
                                rejectTRF(id_trf_pmb);
                            }
                        });
                    });
                    $(".btn_show_bukti_trf_disabled").click(function () {
                        let img_trf = $(this).data("img");
                        let nama = $(this).data("nama");
                        let tahun = $(this).data("tahun");
                        let id_trf_pmb = $(this).data("id_trf");
                        Swal.fire({
                            title: "Bukti Transfer PMB",
                            html:`<p>Nama Pemndaftar : ${nama}</p></br><code>Pembayaran sudah konfirmasi.</code>`,
                            // text: "Silahkan Cek Data Transfer Tersebut",
                            imageUrl: `https://pmb.wastu.digital/assets/${tahun}/bukti_tf/${img_trf}`,
                            imageWidth: 370,
                            imageHeight: 650,
                            showConfirmButton: false,
                            showCancelButton: true,
                        });
                    });


                }
            });
            function accTRF(id) {
                $.ajax({
                    url: '<?= base_url() ?>pmb-gateway/acc_payment',
                    type: 'POST',
                    data: { id: id },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if(response.status === true){
                            Swal.fire({
                                icon: "success",
                                title: "Pembayaran Registrasi PMB berhasil divalidasi",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            }).then((result) => {
                                // Reload the window after the timer expires
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        }else{
                            Swal.fire({
                                icon: "error",
                                title: "Aksi Acc Gagal.",
                                showConfirmButton: false,
                                timer: 3000,
                            });
                            location.reload();
                        }
                    }
                })
                
            }
            function rejectTRF(id) {
                $.ajax({
                    url: '<?= base_url() ?>pmb-gateway/reject_payment',
                    type: 'POST',
                    data: { id: id },
                    serverSide: true,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if(response.status === true){
                            Swal.fire({
                                icon: "info",
                                title: "Pembayran telah ditolak.",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            }).then((result) => {
                                // Reload the window after the timer expires
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        }else{
                            Swal.fire({
                                icon: "error",
                                title: "Aksi reject gagal.",
                                showConfirmButton: false,
                                timer: 15000,
                            });
                            location.reload();
                        }
                    }
                })
            }
        });
    </script>
</div>