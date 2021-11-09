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

        $("#nim_mhs_bayar_hidden").val(response.nipd);
        $("#nama_mhs_bayar_hidden").val(response.nm_pd);
        $("#jenjang_mhs_bayar_hidden").val(response.nm_jenj_didik);
        $("#tg_cs_mhs").val(response.tg_CS);
        $("#angkatan_mhs_bayar_hidden").val(response.tahun_masuk);

        if (response != null) {
          $("#add_rows").prop("disabled", false);
        } else {
          $("#notif_search2").html(
            "<code>Tidak ada mahasiswa dengan nim : " + nipd + "</code>"
          );
          setTimeout(function () {
            $("#notif_search2").html("");
          }, 2000);
        }

        if (response.tg_CS != "0") {
          $("#notif_search2").html(
            "<code>Mahasiswa Tersebut Mempunyai Tunggakan Semester Lalu</code>"
          );
          setTimeout(function () {
            $("#notif_search2").html("");
          }, 5000);
        }

        // vier table history transaksi
        if (response.dataHistoriTX != null) {
          $.each(response.dataHistoriTX, function (i, value) {
            i++;
            let total_bayarTrx = 0;
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
              total_bayarTrx += parseInt(val.jml_bayar);
            });
            html_tbody_2 += `</td>`;
            html_tbody_2 += `<td class = "text-center">Rp.${parseInt(
              total_bayarTrx
            ).toLocaleString()}</td>`;
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

        let total_bayar = 0;
        $.each(response.dataHistoriTX, function (i, value) {
          i++;
          $.each(value.detail_transaksi, function (k, val) {
            total_bayar += parseInt(val.jml_bayar);
          });
        });
        html_tbody_2 += `<tr>`;
        html_tbody_2 += `<td colspan="6" class="text-center"><i>TOTAL JUMLAH PEMBAYARAN PADA SEMESTER INI</i></td>`;
        html_tbody_2 += `<td colspan="6" class="text-center"><i id="total">Rp.${total_bayar.toLocaleString()}</i></td>`;
        html_tbody_2 += `</tr>`;

        $("#riwayat_transaksi_modal_2").html(html_tbody_2);
      },
    });
  }
});

$("#add_rows").click(function () {
  $("#tabel_pembayaranLain").each(function () {
    let tg_cs = $("#tg_cs_mhs").val();
    // console.log(tg_cs);
    $(".btn#delete_rows").prop("disabled", false);
    // $("tbody", this).empty();
    let tds = "<tr>";
    (size = jQuery("#tabel_pembayaranLain >tbody >tr").length + 1),
      (tds += '<td width="45%">');
    $(".select2").select2({});
    tds += `<select name="JenisBayar[]" id="jenis_bayar${size}"  data-rowid="${size}" style="text-align: center;text-align-last: center;" class="form-control select2 select2Cus">`;
    tds += "</select>";
    tds += "</td>";
    tds += `<td width="15%" class="text-center td_for_jml${size}"></td>`;
    tds += `<td width="40%" class="text-center td_${size}"><input type="text" id="pembayaran_${size}" name="" class="form-control validate text-right input_" value="" readonly></td>`;
    tds += "</tr>";
    if ($("tbody", this).length > 0) {
      $("tbody", this).append(tds);
    } else {
      $(this).append(tds);
    }
    $(".select2").select2({});
    let tdr = "";
    tdr += '<option value="x" align="center">-- Pilih Pembayaran --</option>';
    let jnj_didikku = $("#jenjang_mhs_bayar_hidden").val();
    $.ajax({
      type: "POST",
      url: "transaksi/Cari_Pembayaran_lain",
      data: {
        nm_jenj_didik: jnj_didikku,
        tg_cs: tg_cs,
      },
      serverside: true,
      dataType: "json",
      success: function (response) {
        // console.log(response);
        $.each(response.jenis_pembayaran, function (i, item) {
          tdr += `<option value="${item.id_jp}">${item.nm_jp}</option>`;
        });

        $("#jenis_bayar" + size).append(tdr);
      },
    });

    $(".select2Cus").on("change", function () {
      let id_jns_bayar = this.value;
      let nim_mhs = $("#nim_mhs_bayar_hidden").val();
      let jnj_didik = $("#jenjang_mhs_bayar_hidden").val();
      let thn_masuk = $("#angkatan_mhs_bayar_hidden").val();
      let rowid = $(this).attr("data-rowid");

      if (id_jns_bayar == "17") {
        let td = `<input id="jml_mk${size}" type="text" class="form-control validate text-right jml_mk${size}" data-slider-id='ex1Slider' data-slider-min="1" data-slider-max="60" data-slider-step="1" data-slider-value="14">`;
        $(".td_for_jml" + size).html(td);
        $(".div_btn_row").prop("disabled", true);
        $(".td_for_jml" + size).prop("hidden", false);
        $("#pembayaran_" + size).prop("readonly", true);
        $("#jml_mk" + size).slider({
          // tooltip: "always",
          formatter: function (value) {
            return "Jml Matakuliah: " + value;
          },
        });
      } else if (id_jns_bayar == "16") {
        let td = `<input id="jml_cuti${size}" type="text" class="form-control validate text-right jml_cuti${size}" data-slider-id='ex1Slider' data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="2">`;
        $(".td_for_jml" + size).html(td);
        $(".div_btn_row").prop("disabled", true);
        $(".td_for_jml" + size).prop("hidden", false);
        $("#pembayaran_" + size).prop("readonly", true);
        $("#jml_cuti" + size).slider({
          // tooltip: "always",
          formatter: function (value) {
            return "Jml Cuti: " + value;
          },
        });
      } else {
        $("#pembayaran_" + size).prop("readonly", false);
        $(".div_btn_row").prop("disabled", false);
        $(".td_for_jml" + size).empty();
        $(".slider#jml_mk" + size).remove();
        $(".slider#jml_cuti" + size).remove();
      }
      $.ajax({
        type: "POST",
        url: "transaksi/get_biaya_pembayaran_lain",
        data: {
          nim_mhs: nim_mhs,
          id_jns_bayar: id_jns_bayar,
          jnj_didik: jnj_didik,
          thn_masuk: thn_masuk,
        },
        serverside: true,
        dataType: "json",
        success: function (response) {
          const jp = response.id_jp;
          const kewajiban = response.biaya;
          console.log(response);
          // console.log(kewajiban);
          $("#pembayaran_" + rowid).attr("value", kewajiban);
          $("#pembayaran_" + rowid).attr("name", `biayaJenisPembayaran[${jp}]`);
          if (tg_cs === "0") {
            $(".btn#btn_proses_2").prop("disabled", false);
          } else {
            if (jp === "6") {
              $(".btn#btn_proses_2").prop("disabled", false);
            }
          }

          $("#jml_mk" + size).on("change", function () {
            let jml = this.value;
            let kewajiban = response.biaya;
            $("#pembayaran_" + rowid).attr("value", kewajiban * jml);
            // console.log(kewajiban * jml);
          });
          $("#jml_cuti" + size).on("change", function () {
            let jml = this.value;
            let kewajiban = response.biaya;
            $("#pembayaran_" + rowid).attr("value", kewajiban * jml);
            // console.log(kewajiban * jml);
          });
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

$("#form_pembayaran_lain").submit(function (e) {
  e.preventDefault();
  let form = $(this);

  $.ajax({
    type: "POST",
    url: "transaksi/proses_bayar_lainnya", // where you wanna post
    data: form.serialize(), // serializes form input,
    success: function (response) {
      console.log(response);
      let id_transaksi = response;
      Swal.fire({
        title: "Transaksi Berhasil!",
        text: `Transaksi ${id_transaksi} telah berhasil di input, apakah ingin mencetak kwitansi?`,
        icon: "info",
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
          location.reload();
        }
      });
    },
  });
});
