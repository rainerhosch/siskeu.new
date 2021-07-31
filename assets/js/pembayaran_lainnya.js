$("#nipd_2").on("keypress", function (e) {
  if (e.which == 13) {
    $(".div_btn_row").show();
    let nipd = $("#nipd_2").val();
    $.ajax({
      type: "POST",
      url: "transaksi/cari_mhs",
      data: {
        nipd: nipd,
      },
      serverside: true,
      dataType: "json",
      success: function (response) {
        // console.log(response);
        let html_form_2 = "";
        let html_tbody_2 = "";

        $("#nama_mhs_2").val(response.nm_pd);
        $("#jurusan_2").val(response.nm_jur);
        if (response != null) {
          $("#add_rows").click(function () {
            $("#tabel_pembayaranLain").each(function () {
              $(".btn#delete_rows").prop("disabled", false);
              $(".btn#btn_proses_2").prop("disabled", false);
              let tds = "<tr>";

              tds += `<input type="hidden" id="nim_mhs_bayar" name="nim_mhs_bayar" value="${response.nipd}">`;
              tds += `<input type="hidden" id="nama_mhs_bayar" name="nama_mhs_bayar" value="${response.nm_pd}">`;
              tds += `<input type="hidden" id="jenjang_mhs_bayar" name="jenjang_mhs_bayar" value="${response.nm_jenj_didik}">`;
              tds += `<input type="hidden" id="angkatan_mhs_bayar" name="angkatan_mhs_bayar" value="${response.tahun_masuk}">`;
              (size = jQuery("#tabel_pembayaranLain >tbody >tr").length + 1),
                (tds += '<td width="60%">');
              $(".select2").select2({});
              tds += `<select name="JenisBayar[]" id="jenis_bayar${size}"  data-rowid="${size}" style="text-align: center;text-align-last: center;" class="form-control select2 select2Cus">`;
              tds += "</select>";
              tds += "</td>";
              tds += `<td width="40%" class="text-center"><input type="text" id="pembayaran_${size}" name="" class="form-control validate text-right input_" value=""></td>`;
              tds += "</tr>";
              if ($("tbody", this).length > 0) {
                $("tbody", this).append(tds);
              } else {
                $(this).append(tds);
              }
              $(".select2").select2({});
              let tdr = "";
              tdr +=
                '<option value="x" align="center">-- Pilih Pembayaran --</option>';

              $.each(response.jenis_pembayaran, function (i, item) {
                tdr += `<option value="${item.id_jp}">${item.nm_jp}</option>`;
              });
              $("#jenis_bayar" + size).append(tdr);
              $(".select2Cus").on("change", function () {
                let id_jns_bayar = this.value;
                let jnj_didik = response.nm_jenj_didik;
                let thn_masuk = response.tahun_masuk;
                let rowid = $(this).attr("data-rowid");
                // console.log($(this).attr("data-rowid"));
                $.ajax({
                  type: "POST",
                  url: "transaksi/get_biaya_pembayaran_lain",
                  data: {
                    id_jns_bayar: id_jns_bayar,
                    jnj_didik: jnj_didik,
                    thn_masuk: thn_masuk,
                  },
                  serverside: true,
                  dataType: "json",
                  success: function (response) {
                    console.log(response);
                    const jp = response.id_jp;
                    $("#pembayaran_" + rowid).attr("value", response.biaya);
                    $("#pembayaran_" + rowid).attr(
                      "name",
                      `biayaJenisPembayaran[${jp}]`
                    );
                  },
                });
              });
            });
          });

          $("#delete_rows").on("click", function () {
            let jml_trx = size;
            let last = $("#tbody_pembayaran_lain").find("tr:last");
            if (last.is(":first-child")) {
              alert("Harus ada setidaknya satu transaksi");
              $(".btn#delete_rows").prop("disabled", true);
            } else {
              last.remove();
            }
          });
        } else {
          $("#notif_search2").html(
            "<code>Tidak ada mahasiswa dengan nim : " + nipd + "</code>"
          );
          setTimeout(function () {
            $("#notif_search2").html("");
          }, 2000);
        }

        // vier table history transaksi
        if (response.dataHistoriTX != null) {
          $.each(response.dataHistoriTX, function (i, value) {
            i++;
            html_tbody_2 += `<tr>`;
            html_tbody_2 += `<td class = "text-center" >${i}</td>`;
            html_tbody_2 +=
              `<td class="text-center"><a href="<?= base_url('transaksi/cetak_kwitansi/') ?>` +
              value.id_transaksi +
              `">${value.id_transaksi}</a></td>`;
            html_tbody_2 += `<td class = "text-center" >${value.tanggal}</td>`;
            html_tbody_2 += `<td class = "text-center" >${value.jam}</td>`;
            html_tbody_2 += `<td class = "text-center" >${value.nim}</td>`;

            html_tbody_2 += `<td class = "text-center" >`;
            $.each(value.detail_transaksi, function (k, val) {
              html_tbody_2 += `<i style="font-size:1rem; font-weight: bold;">${
                val.nm_jenis_pembayaran
              }</i> : <i style="font-size:1rem;">Rp.${parseInt(
                val.jml_bayar
              ).toLocaleString()}</i><br>`;
            });
            html_tbody_2 += `</td>`;
            html_tbody_2 += `<td class = "text-center"><i>${value.total_bayar}</i></td>`;
            html_tbody_2 += `<td class = "text-center" >${value.semester}</td>`;
            html_tbody_2 += `<td class = "text-center" >${value.icon_status_tx}</td>`;
            html_tbody_2 += `</tr>`;
          });
        } else {
          html_tbody_2 += `<tr>`;
          html_tbody_2 += `<td colspan="12" class="text-center"><br>`;
          html_tbody_2 += `<div class='col-lg-12'>`;
          html_tbody_2 += `<div class='alert alert-danger alert-dismissible'>`;
          html_tbody_2 += `<h4><i class='icon fa fa-warning'></i> Belum Ada Histori Pembayaran Pada Semester Ini!</h4>`;
          html_tbody_2 += `</div>`;
          html_tbody_2 += `</div>`;
          html_tbody_2 += `</td>`;
          html_tbody_2 += `</tr>`;
        }

        html_tbody_2 += `<tr>`;
        html_tbody_2 += `<td colspan="6" class="text-center"><i>TOTAL JUMLAH PEMBAYARAN PADA SEMESTEER INI</i></td>`;
        html_tbody_2 += `<td colspan="6" class="text-center"><i id="total"></i></td>`;
        html_tbody_2 += `</tr>`;

        $("#riwayat_transaksi_modal_2").html(html_tbody_2);
        $(function () {
          $("#total").html(sumColumn(7));
        });

        function sumColumn(index) {
          var total = 0;
          $("td:nth-child(" + index + ")").each(function () {
            total += parseInt($(this).text(), 10) || 0;
            convTotal = "Rp." + total.toLocaleString();
          });
          return convTotal;
        }
      },
    });
  }
});
