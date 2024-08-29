<!-- Modal Pembayaran SPP-->
<style>
    a.widget {
        border-radius: 10px;
    }

    .widget-simple .widget-content {
        font-size: 15px;
    }

    .widget-simple .widget-content small {
        font-size: 11px;
        font-weight: 400;
    }
</style>
<div class="modal fade" id="formPembayaran" tabindex="-1" role="dialog" aria-labelledby="formPembayaranTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #fff;">&times;</span>
                </button>
                <h5 class="modal-title" id="formPembayaranTitle">Form Pembayaran SPP</h5>
            </div>
            <div class="modal-body" id="modal_body">
                <div class="row">
                    <div class="col-sm-4 form_invoice">
                        <div class="sm-form mb-5 row" style="margin-top: 5px;">
                            <div class="col-sm-12">
                                <input type="text" id="nipd" name="nipd" class="form-control validate"
                                    placeholder="Cari NIM / No Virtual Account">
                                <span id="notif_search"></span>
                            </div>
                        </div>
                        <div class="sm-form mb-5 row text-left">
                            <div class="col-sm-12">
                                <input type="text" id="nama_mhs" name="nama_mhs" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="sm-form mb-5 row text-left">
                            <div class="col-sm-12">
                                <input type="text" id="jurusan" name="jurusan" class="form-control validate" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <span id="notif_tg_mhs"></span>
                        </div>
                        <form id="form_pembayaran" enctype="multipart/form-data">
                            <br>
                            <table id="table_kewajiban" class="table table-vcenter table-condensed">

                                <tbody id="data_kwajiban_tbody2">
                                </tbody>
                                <tbody id="data_kwajiban_tbody">
                                </tbody>
                            </table>
                            <hr class="my-5">
                            <div class="text-right" style="margin-bottom: 5px;">
                                <button type="submit" id="btn_proses" class="btn btn-primary" disabled>Proses</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <div class="table-responsive">
                            <table id="modal_datatable" class="table table-vcenter table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nomo Transaksi</th>
                                        <th class="text-center">Tgl Transaksi</th>
                                        <th class="text-center">Jam</th>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Keterangan Bayar</th>
                                        <th class="text-center">Jumlah Storan</th>
                                        <!-- <th class="text-center">Sisa Tagihan</th> -->
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayat_transaksi_modal">
                                </tbody>
                                <tfoot id="riwayat_transaksi_tfoot"></tfoot>
                            </table>
                        </div>

                        <div class="alert alert-success" id="alert_potongan" hidden>
                            <p id="text_info_potongan"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer hidden">
            </div>
        </div>
    </div>
</div>
<script>
    $("#nipd").on("keypress", function (e) {
        if (e.which == 13) {
            let nipd = $("#nipd").val();
            if (nipd.length > 9) {
                nipd = nipd.substring(nipd.length - 9);
                // nipd = nipd.substring(nipd,nipd.length - 9, 9);
            }

            console.log(nipd);
            let stts_radio = ``;
            $.ajax({
                type: "POST",
                url: "transaksi/cari_mhs",
                data: {
                    nipd: nipd,
                },
                dataType: "json",
                success: function (response) {
                    if (response != null) {
                        if ((response.totalKewajiban = 0)) {
                            $(".btn#btn_proses").prop("disabled", true);
                        }

                        // if(response.jns_smt != "1"){
                        //   stts_radio=`disabled`;
                        // }
                        let html = ``;
                        let html_3 = ``;
                        $("#nama_mhs").val(response.nm_pd);
                        $("#jurusan").val(response.nm_jur);
                        if (response.tg_CS != 0) {
                            $("#notif_tg_mhs").html(
                                "<code>Ada tunggakan CS Rp." + response.tg_CS + "</code>"
                            );
                        }
                        if (response.data_tg.length > 0) {
                            $("#notif_tg_mhs").html(
                                "<code>Ada tunggakan Kemahasiswaan Rp." +
                                response.tg_Kmhs +
                                "</code>"
                            );
                        }

                        html += `<tr>`;
                        html += `<td><label for="input_smt">Semester</label></td>`;
                        html += `<td class="text-center">`;
                        html += `<select class="form-control input_smt" name="smt" id="smt_1" ${stts_radio}><br>`;
                        html += `<option value="${response.thn_smt}1"> ( ${response.thn_smt}1 - Ganjil)</option>`;
                        html += `<option value="${response.thn_smt}2"> ( ${response.thn_smt}2 - Genap)</option>`;
                        if (response.data_tg.length === 0) {
                            html += `<hr>`
                            html += `<option value="${(response.thn_smt * 1) + 1}1"> ( ${(response.thn_smt * 1) + 1}1 - Ganjil)</option>`;
                            html += `<option value="${(response.thn_smt * 1) + 1}2"> ( ${(response.thn_smt * 1) + 1}2 - Genap)</option>`;
                        } else {
                            let check_tg = true;
                            $.each(response.data_tg, function (i, value) {
                                if (value.jenis_tunggakan != 1 || value.jenis_tunggakan != 2 || value.jenis_tunggakan != 3 || value.jenis_tunggakan != 4) {
                                    check_tg = false;
                                }
                            })
                            if (check_tg === false) {
                                html += `<hr>`
                                html += `<option value="${(response.thn_smt * 1) + 1}1"> ( ${(response.thn_smt * 1) + 1}1 - Ganjil)</option>`;
                                html += `<option value="${(response.thn_smt * 1) + 1}2"> ( ${(response.thn_smt * 1) + 1}2 - Genap)</option>`;
                            }
                        }
                        html += `</select>`;
                        html += `</td>`;
                        // html += `<td class="text-center">`;
                        // html += `<select class="form-control input_smt" name="smt" id="smt_2"><br>`;
                        // html += `<option value="${response.thn_smt}2"> ( ${response.thn_smt}2 )</option>`;
                        // html += `</select>`;
                        // html += `</td>`;
                        html += `</tr>`;
                        // line jenis transaksi (transfer atau langsung)
                        html += `<tr>`;
                        html += `<td><label for="bayar_via">Jenis Bayar</label></td>`;
                        html += `<td class="text-center">`;
                        html += `<select class="form-control form-control-sm bayar_via" id="bayar_via" name="bayar_via">
                    <option value="x">-- Pilih --</option>
                    <option value="1">Cash</option>
                    <option value="2">Transfer</option>
                  </select>`;
                        html += `</td>`;
                        html += `</tr>`;
                        $("#data_kwajiban_tbody2").html(html);
                        $(".bayar_via").on("change", function () {
                            let bayar_via = $(".bayar_via").val();
                            let tds = ``;
                            if (bayar_via === "2") {
                                tds += `<tr class="detail_trf">`;
                                tds += `<td><label for="rek_tujuan">Rekening Tujuan</label></td>`;
                                tds += `<td class="text-center"><select class="form-control form-control-sm rek_tujuan" id="rek_tujuan" name="rek_tujuan">`;
                                tds += `<option value="1">BANK BSI</option>
                <option value="2">BANK MANDIRI</option>
                <option value="3">BANK BNI</option>`;
                                //  $.ajax({
                                //     type: "POST",
                                //     url: "transaksi/get_data_rekening",
                                //     dataType: "json",
                                //     success: function (res) {
                                //       console.log(res)
                                //       $.each(res.data, function (i, value) {
                                //         tds += `<option value="${value.id_rek}">${value.bank}</option>`;
                                //       });
                                //     }
                                //   });
                                tds += `</select></td>`;
                                tds += `</tr>`;
                                tds += `<tr class="detail_trf">`;
                                tds += `<td><label for="tgl_trf">Tgl Transfer</label></td>`;
                                tds += `<td><input type="date" id="tgl_trf" name="tgl_trf" class="form-control validate text-right input_" value=""></td>`;
                                tds += `</tr>`;
                                tds += `<tr class="detail_trf">`;
                                tds += `<td><label for="jam_trf">Jam Transfer</label></td>`;
                                tds += `<td><input type="time" step="1" id="jam_trf" name="jam_trf" class="form-control validate text-right input_" value=""></td>`;
                                tds += `</tr>`;
                                $("#data_kwajiban_tbody2").append(tds);
                            } else {
                                $(".detail_trf").remove();
                            }
                        });
                        $(".input_smt").on('change', function () {
                            let smt = $(this).val();
                            let nipd = $("#nipd").val();
                            // console.log(nipd)
                            if (smt) {
                                // alert("Your are a - " + smt);

                                $.ajax({
                                    type: "POST",
                                    url: "transaksi/Cek_Pembayaran_SPP",
                                    data: {
                                        smt: smt,
                                        nipd: nipd,
                                    },
                                    dataType: "json",
                                    success: function (response) {
                                        // console.log(response);
                                        if ((response.totalKewajiban = 0)) {
                                            $(".btn#btn_proses").prop("disabled", true);
                                        }
                                        let htmlx = ``;
                                        $(".data_kwajiban").show();
                                        $("#riwayat_transaksi").show();
                                        $("#nama_mhs").val(response.nm_pd);
                                        $("#jurusan").val(response.nm_jur);
                                        htmlx += `<input type="hidden" id="nim_mhs_bayar" name="nim_mhs_bayar" value="${response.nipd}">`;
                                        htmlx += `<input type="hidden" id="nama_mhs_bayar" name="nama_mhs_bayar" value="${response.nm_pd}">`;
                                        htmlx += `<input type="hidden" id="jenjang_mhs_bayar" name="jenjang_mhs_bayar" value="${response.nm_jenj_didik}">`;
                                        htmlx += `<input type="hidden" id="angkatan_mhs_bayar" name="angkatan_mhs_bayar" value="${response.tahun_masuk}">`;
                                        $.each(response.dataKewajibanSmt, function (i, value) {
                                            i++;
                                            htmlx += `<tr>`;
                                            htmlx += `<td><label data-error="wrong" data-success="right" for="${value.label}">${value.label}</label></td>`;
                                            htmlx += `<td class="text-center"><input type="text" id="${value.post_id}" name="${value.post_id}" class="form-control validate text-right input_${i}" value="${value.biaya}" disabled></td>`;
                                            htmlx += `<td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_${i}" ${value.biaya == 0 ? "disabled" : ""
                                                }></td>`;
                                            htmlx += `</tr>`;
                                        });
                                        // line potongan spp
                                        htmlx += `<tr>`;
                                        htmlx += `<td><label data-error="wrong" data-success="right" for="uang_masuk">Potongan SPP</label></td>`;
                                        htmlx += `<td class="text-center"><input type="hidden" id="uang_masuk" name="uang_masuk" class="form-control validate text-right uang_masuk" value="1" readonly></td>`;
                                        htmlx += `<td class="text-center"><input class="form-check-input" type="checkbox" value="" id="check_uang_masuk"></td>`;
                                        htmlx += `</tr>`;
                                        $("#data_kwajiban_tbody").html(htmlx);
                                        $.each(response.dataKewajibanSmt, function (i, value) {
                                            i++;
                                            $("#checkcox_" + i).change(function () {
                                                let bayar_via = $(".bayar_via").val();
                                                // console.log(bayar_via);
                                                var numberOfChecked = $("input:checkbox:checked").length;
                                                if (bayar_via != "x") {
                                                    if (numberOfChecked <= 0) {
                                                        $("#btn_proses").prop("disabled", true);
                                                    } else {
                                                        $("#btn_proses").prop("disabled", false);
                                                    }
                                                }
                                                if (this.checked === true) {
                                                    $("#" + value.post_id).prop("disabled", false);
                                                } else {
                                                    $("#" + value.post_id).prop("disabled", true);
                                                }
                                            });
                                        });

                                        $("#check_uang_masuk").change(function () {
                                            if (this.checked === true) {
                                                $("#uang_masuk").val(0);
                                            } else {
                                                $("#uang_masuk").val(1);
                                            }
                                        });
                                    },
                                });
                            }
                        });

                        if (response.dataHistoriTX != 0) {
                            $.each(response.dataHistoriTX, function (i, value) {
                                i++;
                                let total_bayarTrx = 0;
                                html_3 += `<tr>`;
                                html_3 += `<td class = "text-center" >${i}</td>`;
                                html_3 +=
                                    `<td class="text-center"><a target="_blank" href="transaksi/cetak_ulang_kwitansi/` +
                                    value.id_transaksi +
                                    `">${value.id_transaksi}</a></td>`;
                                html_3 += `<td class = "text-center" >${value.tanggal}</td>`;
                                html_3 += `<td class = "text-center" >${value.jam}</td>`;
                                html_3 += `<td class = "text-center" >${value.nim}</td>`;

                                html_3 += `<td class = "text-center" >`;
                                $.each(value.detail_transaksi, function (k, val) {
                                    html_3 += `<i style="font-size:1rem; font-weight: bold;">${val.nm_jenis_pembayaran
                                        }</i> : <i style="font-size:1rem;">Rp.${parseInt(
                                            val.jml_bayar
                                        ).toLocaleString()}</i><br>`;
                                    total_bayarTrx += parseInt(val.jml_bayar);
                                });
                                html_3 += `</td>`;
                                html_3 += `<td class = "text-center">Rp.${parseInt(
                                    total_bayarTrx
                                ).toLocaleString()}</td>`;
                                html_3 += `<td class = "text-center" >${value.semester}</td>`;
                                html_3 += `<td class = "text-center" >${value.icon_status_tx}</td>`;
                                html_3 += `</tr>`;
                            });
                        } else {
                            html_3 += `<tr>`;
                            html_3 += `<td colspan="12" class="text-center"><br>`;
                            html_3 += `<div class='col-lg-12'>`;
                            html_3 += `<div class='alert alert-danger alert-dismissible'>`;
                            html_3 += `<h4><i class='icon fa fa-warning'></i> Belum Ada Histori Pembayaran Pada Semester Ini!</h4>`;
                            html_3 += `</div>`;
                            html_3 += `</div>`;
                            html_3 += `</td>`;
                            html_3 += `</tr>`;
                        }

                        let total_bayar = 0;
                        $.each(response.dataHistoriTX, function (i, value) {
                            // console.log(response);
                            i++;
                            $.each(value.detail_transaksi, function (k, val) {
                                total_bayar += parseInt(val.jml_bayar);
                            });
                        });
                        html_3 += `<tr>`;
                        html_3 += `<td colspan="6" class="text-center"><i>TOTAL JUMLAH PEMBAYARAN PADA SEMESTER INI</i></td>`;
                        html_3 += `<td colspan="6" class="text-center"><i id="total">Rp.${total_bayar.toLocaleString()}</i></td>`;
                        html_3 += `</tr>`;

                        $("#riwayat_transaksi_modal").html(html_3);
                    } else {
                        $("#notif_search").html(
                            "<code>Tidak ada mahasiswa dengan nim : " + nipd + "</code>"
                        );
                        setTimeout(function () {
                            $("#notif_search").html("");
                        }, 2000);
                    }
                },
                error: function (e) {
                    error_server();
                },
            });
        }
    });
    // $("#btn_proses").on("click", function () {
    //   $("#btn_proses").prop("disabled", true);
    // });
    $("#form_pembayaran").submit(function (e) {
        $("#btn_proses").prop("disabled", true);
        e.preventDefault();
        let form = $(this);
        // let url = form.attr('action');
        $.ajax({
            type: "POST",
            url: "transaksi/proses_bayar_spp", // where you wanna post
            data: form.serialize(), // serializes form input,
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                let id_transaksi = response.data;
                let title = ``;
                let text = ``;
                let icon = ``;
                if (response != 0) {
                    if (response.status === true) {
                        title = `Transaksi Berhasil!`;
                        text = `Transaksi ${response.data} telah berhasil di input, apakah ingin mencetak kwitansi?`;
                        icon = `info`;
                    } else {
                        title = `Transaksi Gagal!`;
                        text = `${response.data}`;
                        icon = "warning";
                    }
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: "##d33",
                        confirmButtonText: "Cetak",
                        cancelButtonText: "Tutup",
                        closeOnConfirm: false,
                        closeOnCancel: false,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            // cetak
                            window.open(`transaksi/cetak_kwitansi/${id_transaksi}`, "_blank");
                            window.focus();

                            location.reload();
                        } else {
                            // refresh page
                            window.focus();
                            location.reload();
                        }
                    });
                } else {
                    window.location.replace(`transaksi/session_msg`);
                }
            },
        });
    });

</script>