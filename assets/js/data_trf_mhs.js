$(".btn_trf_online").click(function () {
  $.ajax({
    url: "transaksi/get_data_trf_online",
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      console.log(response);
      let html = ``;
      let jenis_bayar = ``;
      if (response != null) {
        $.each(response.data, function (i, value) {
          let bank_account = value.bank_penerima;
          let no = i + 1;
          html += `<tr>`;
          html += `<td class = "text-center" >${no}</td>`;
          html += `<td class = "text-center" >${value.nipd}</td>`;
          html += `<td class = "text-center" >`;
          $.each(value.pembayaran, function (i, val) {
            html += `<i>${val.nm_jenis_pembayaran}</i><br>`;
          });
          html += `</td>`;
          html += `<td class = "text-center" >${value.tgl_trf}</td>`;
          html += `<td class = "text-center">`;
          html += `<i style="font-size:1rem; font-weight: bold;">BANK ${bank_account.bank}</i><br>`;
          html += `<i style="font-size:1rem; font-weight: bold;">${bank_account.no_rek}</i><br>`;
          html += `<i style="font-size:1rem; font-weight: bold;">A/N ${bank_account.nama_rekening}</i>`;
          html += `</td>`;
          html += `<td class = "text-center" >Rp.${parseInt(
            value.jumlah_bayar
          ).toLocaleString()}</td>`;
          html += `<td class = "text-center" ><i><a href="#" data-id_trf="${value.id_bukti_trf}" class="btn btn-xs btn-info btn_show_bukti_trf" data-trf="${value.img_trf}" data-smt="${value.smt}" data-nipd="${value.nipd}" data-jns="${value.nm_jenis_pembayaran}">${value.img_trf}</a></i></td>`;
          html += `</tr>`;
        });
        $("#tbody_data_trf").html(html);
        $("#dataTransferMhs").modal("show");
        $(".btn_show_bukti_trf").click(function () {
          let img_trf = $(this).data("trf");
          let smt = $(this).data("smt");
          let nipd = $(this).data("nipd");
          let jns = $(this).data("jns");
          let id_bukti_trf = $(this).data("id_trf");

          Swal.fire({
            title: "Bukti Transfer " + nipd,
            text: "Silahkan Cek Data Transfer Tersebut",
            imageUrl: `https://simak.wastu.digital/assets/${smt}/mahasiswa/bukti_trf/${img_trf}`,
            // imageWidth: 500,
            imageHeight: 400,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Confirm",
            confirmButtonColor: "#8bc34a",
            denyButtonText: `Reject`,
            denyButtonColor: "#d33",
          }).then((result) => {
            if (result.isConfirmed) {
              // Swal.fire('Saved!', '', 'success')
              accTRF(id_bukti_trf);
            } else if (result.isDenied) {
              rejectTRF(id_bukti_trf);
              // Swal.fire('Changes are not saved', '', 'info')
            }
          });
        });
      }
    },
  });
});

function accTRF(id) {
  $("#dataTransferMhs").modal("hide");
  // $('#formPembayaran').modal('hide');
  $.ajax({
    url: "transaksi/acc_trf_online",
    type: "POST",
    data: { id: id },
    dataType: "JSON",
    success: function (response) {
      let data_mhs = response.data;
      let data_trf = data_mhs.data_trf;
      let rek_tujuan = data_trf.rek_tujuan_trf;
      // console.log(data_mhs);
      let nipd = data_mhs.nipd;
      let smt = data_mhs.smt;
      let radio_active = ``;
      $.ajax({
        type: "POST",
        url: "transaksi/cari_mhs",
        data: {
          nipd: nipd,
        },
        dataType: "json",
        success: function (response) {
          // console.log(response);
          if (response != null) {
            $("#formPembayaran").modal("show");
            if ((response.totalKewajiban = 0)) {
              $(".btn#btn_proses").prop("disabled", true);
            }
            let html = ``;
            let html_3 = ``;
            $("#nipd").val(response.nipd);
            $("#nipd").prop("readonly", true);

            html += `<tr>`;
            html += `<td class="text-center">`;
            html += `<input class="form-check-input input_smt" type="radio" name="smt" id="radio_${response.thn_smt}1" value="${response.thn_smt}1"><br>`;
            html += `<label class="form-check-label" for="smt_1"> ( ${response.thn_smt}1 )</label>`;
            html += `</td>`;
            html += `<td class="text-center">`;
            html += `<input class="form-check-input input_smt" type="radio" name="smt" id="radio_${response.thn_smt}2" value="${response.thn_smt}2"><br>`;
            html += `<label class="form-check-label" for="smt_2"> ( ${response.thn_smt}2 )</label>`;
            html += `</td>`;
            html += `</tr>`;
            // line jenis transaksi (transfer atau langsung)
            html += `<tr>`;
            html += `<td><label for="bayar_via">Jenis Bayar</label></td>`;
            html += `<td class="text-center">`;
            html += `<select class="form-control form-control-sm bayar_via" id="bayar_via" name="bayar_via">
                      <option value="x">-- Pilih --</option>
                      <option value="1">Cash</option>
                      <option value="2" selected>Transfer</option>
                    </select>`;
            html += `</td>`;
            html += `</tr>`;
            $("#data_kwajiban_tbody2").html(html);
            $("input[name=smt][value=" + smt + "]").prop("checked", true);
            let bayar_via = $(".bayar_via").val();
            let tds = ``;
            if (bayar_via === "2") {
              tds += `<tr class="detail_trf">`;
              tds += `<td><label for="rek_tujuan">Rekening Tujuan</label></td>`;
              tds += `<td class="text-center"><select class="form-control form-control-sm rek_tujuan" id="rek_tujuan" name="rek_tujuan">`;
              tds += `<option value="1">BANK BSI</option>
                <option value="2">BANK MANDIRI</option>PP
                <option value="3">BANK BNI</option>`;
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
              $(`.rek_tujuan option[value="${rek_tujuan}"]`).attr(
                "selected",
                "selected"
              );

              $(`#tgl_trf`).val(data_trf.tgl_trf);
              $(`#jam_trf`).val(data_trf.jam_konfir);
            } else {
              $(".detail_trf").remove();
            }
            if ($("input[name='smt']").is(":checked")) {
              let smt = $("input[name='smt']:checked").val();
              let nipd = $("#nipd").val();
              // console.log(smt)
              if (smt) {
                $.ajax({
                  type: "POST",
                  url: "transaksi/Cek_Pembayaran_SPP",
                  data: {
                    smt: smt,
                    nipd: nipd,
                  },
                  dataType: "json",
                  success: function (response) {
                    console.log(response);

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
                    let jml_trf = data_trf.jumlah_bayar;
                    let sisa_bayar = 0;
                    let attr_disable = ``;
                    let attr_checked = ``;
                    let vBiaya = 0;
                    $.each(response.dataKewajibanSmt, function (i, value) {
                      let jml_bayar = jml_trf;

                      if (value.biaya > 0) {
                        vBiaya = value.biaya;
                      }
                      if (jml_trf < value.biaya) {
                        vBiaya = jml_trf;
                        jml_trf = 0;
                      } else {
                        jml_trf = jml_trf - value.biaya;
                        vBiaya = value.biaya;
                      }
                      // console.log(`${i}_` + vBiaya + " - " + jml_trf);
                      if (jml_trf >= 0 && vBiaya != 0) {
                        attr_disable = ``;
                        attr_checked = `checked`;
                      } else {
                        attr_disable = `disabled`;
                        attr_checked = ``;
                      }
                      htmlx += `<tr>`;
                      htmlx += `<td><label data-error="wrong" data-success="right" for="${value.label}">${value.label}</label></td>`;
                      htmlx += `<td class="text-center"><input type="text" id="${value.post_id}" name="${value.post_id}" class="form-control validate text-right input_${i}" value="${vBiaya}" ${attr_disable}></td>`;
                      htmlx += `<td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_${i}" ${
                        vBiaya == 0 ? "disabled" : ""
                      } ${attr_checked}></td>`;
                      htmlx += `</tr>`;
                      i++;
                    });
                    // line potongan spp
                    htmlx += `<tr>`;
                    htmlx += `<td><label data-error="wrong" data-success="right" for="uang_masuk">Potongan SPP</label></td>`;
                    htmlx += `<td class="text-center"><input type="hidden" id="uang_masuk" name="uang_masuk" class="form-control validate text-right uang_masuk" value="1" readonly></td>`;
                    htmlx += `<td class="text-center"><input class="form-check-input" type="checkbox" value="" id="check_uang_masuk"></td>`;
                    htmlx += `</tr>`;
                    $("#data_kwajiban_tbody").html(htmlx);

                    var numberOfChecked = $("input:checkbox:checked").length;
                    if (bayar_via != "x") {
                      if (numberOfChecked <= 0) {
                        $("#btn_proses").prop("disabled", true);
                      } else {
                        $("#btn_proses").prop("disabled", false);
                      }
                    }
                    // if(data_trf.jumlah_bayar > 0){
                    $.each(response.dataKewajibanSmt, function (i, value) {
                      $("#checkcox_" + i).change(function () {
                        let bayar_via = $(".bayar_via").val();
                        var numberOfChecked = $(
                          "input:checkbox:checked"
                        ).length;
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
                      i++;
                    });
                    // }

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
            }
            // });

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
                  html_3 += `<i style="font-size:1rem; font-weight: bold;">${
                    val.nm_jenis_pembayaran
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
    },
  });
}

function rejectTRF(id) {
  // console.log(id);
  $.ajax({
    url: "transaksi/reject_trf_online",
    type: "POST",
    data: { id: id },
    dataType: "JSON",
    success: function (response) {
      // console.log(response);
      Swal.fire({
        icon: "success",
        title: "Bukti Transfer Telah Di Reject",
        showConfirmButton: false,
        timer: 2000,
      });
      location.reload();
    },
  });
}

function changeImage(img_name) {
  $(".img_trf")[0].src = img_name;
}