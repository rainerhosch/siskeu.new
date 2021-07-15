$("#nipd").on("keyup", function () {
  let nipd = $("#nipd").val();
  $.ajax({
    type: "POST",
    url: "transaksi/cari_mhs",
    data: {
      nipd: nipd,
    },
    dataType: "json",
    success: function (response) {
      if (response != null) {
        if (response.totalKewajiban != 0) {
          $(".btn#btn_proses").prop("disabled", false);
        } else {
          $(".btn#btn_proses").prop("disabled", true);
        }
        let html = ``;
        let html_3 = ``;
        $(".data_kwajiban").show();
        $("#riwayat_transaksi").show();
        $("#nama_mhs").val(response.nm_pd);
        $("#jurusan").val(response.nm_jur);
        html += `<input type="hidden" id="nim_mhs_bayar" name="nim_mhs_bayar" value="${response.nipd}">`;
        html += `<input type="hidden" id="nama_mhs_bayar" name="nama_mhs_bayar" value="${response.nm_pd}">`;
        html += `<input type="hidden" id="jenjang_mhs_bayar" name="jenjang_mhs_bayar" value="${response.nm_jenj_didik}">`;
        html += `<input type="hidden" id="angkatan_mhs_bayar" name="angkatan_mhs_bayar" value="${response.tahun_masuk}">`;

        $.each(response.dataKewajiban, function (i, value) {
          html += `<tr>
                                        <td><label data-error="wrong" data-success="right" for="${
                                          value.label
                                        }">${value.label}</label></td>
                                        <td class="text-center"><input type="text" id="${
                                          value.post_id
                                        }" name="${
            value.post_id
          }" class="form-control validate text-right input_${i}" value="${
            value.biaya
          }" disabled></td>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" value="" id="checkcox_${i}" ${
            value.biaya == 0 ? "disabled" : ""
          }></td>
                                    </tr>`;
        });
        $("#data_kwajiban_tbody").html(html);
        $.each(response.dataKewajiban, function (i, value) {
          $("#checkcox_" + i).change(function () {
            if (this.checked === true) {
              $("#" + value.post_id).prop("disabled", false);
            } else {
              $("#" + value.post_id).prop("disabled", true);
            }
          });
        });
        if (response.dataHistoriTX != null) {
          $.each(response.dataHistoriTX, function (i, value) {
            // console.log(value);
            i++;
            html_3 += `<tr>`;
            html_3 += `<td class = "text-center" >${i}</td>`;
            html_3 +=
              `<td class="text-center"><a href="<?= base_url('transaksi/cetak_kwitansi/') ?>` +
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
            });
            html_3 += `</td>`;
            // html_3 += `<td class = "text-center"><i>Rp.${parseInt(value.total_bayar).toLocaleString()}</i></td>`;
            html_3 += `<td class = "text-center"><i>${value.total_bayar}</i></td>`;
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

        html_3 += `<tr>`;
        html_3 += `<td colspan="6" class="text-center"><i>TOTAL JUMLAH PEMBAYARAN PADA SEMESTEER INI</i></td>`;
        html_3 += `<td colspan="6" class="text-center"><i id="total"></i></td>`;
        html_3 += `</tr>`;

        $("#riwayat_transaksi_modal").html(html_3);
        $(function () {
          $("#total").html(sumColumn(7));
        });

        function sumColumn(index) {
          var total = 0;
          $("td:nth-child(" + index + ")").each(function () {
            // let dta = $(this).text();
            // console.log(dta.toLocaleString());
            total += parseInt($(this).text(), 10) || 0;
            convTotal = "Rp." + total.toLocaleString();
          });
          return convTotal;
        }

        // $(function() {
        //     TablesModalDatatables.init();
        // });
      } else {
        // $('#notif_search').html("<div class='alert alert-danger alert-dismissable'>Tidak ada mahsiswa dengan nim : " + nipd + "</div>");
        $("#notif_search").html(
          "<code>Tidak ada mahasiswa dengan nim : " + nipd + "</code>"
        );
        setTimeout(function () {
          $("#notif_search").html("");
        }, 2000);
        // alert('Data mahasiswa tersebut tidak ditemukan, pastikan NIM sudah benar!');
        // window.location.reload();
      }
    },
    error: function (e) {
      error_server();
    },
  });
});
