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
                                    </tr>
                                </thead>
                                <tbody id="tbody_data_trf">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "<?= base_url() ?>transaksi/get_data_trf_online",
                type: "POST",
                dataType: "JSON",
                data:{
                    filter:null
                },
                success: function (response) {
                console.log(response);
                let html = ``;
                let jenis_bayar = ``;
                let status=``;
                let colorBG=``;
                if (response != null) {
                    $.each(response.data, function (i, value) {
                    let bank_account = value.bank_penerima;
                    let no = i + 1;
                    if(value.status != 0){
                        if(value.status != 1){
                            status = 'DITOLAK';
                            colorBG = 'bg-danger';
                        }else{
                            status = 'SUDAH DIVALIDASI';
                            colorBG = 'bg-success';
                        }
                    }else{
                        status = 'BELUM DIVALIDASI';
                        colorBG = 'bg-info';
                    }
                    html += `<tr>`;
                    html += `<td class="text-center ${colorBG}" >${no}</td>`;
                    html += `<td class="text-center ${colorBG}" >${value.nipd}</td>`;
                    html += `<td class="text-center ${colorBG}" >`;
                    $.each(value.pembayaran, function (i, val) {
                        html += `<i>${val.nm_jenis_pembayaran}</i><br>`;
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
                    html += `<td class="text-center ${colorBG} text-white" style="font-size:1rem; font-weight: bold;">${status}</td>`;
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
                    
                    $(function() {
                        TablesDatatables.init();
                    });
                }
                },
            });
        });
    </script>
</div>
<!-- END Page Content -->