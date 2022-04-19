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
          let no = i + 1;
          html += `<tr>`;
          html += `<td class = "text-center" >${no}</td>`;
          html += `<td class = "text-center" >${value.tgl_trf}</td>`;
          html += `<td class = "text-center" >${value.nipd}</td>`;
          html += `<td class = "text-center" >${value.jumlah_bayar}</td>`;
          html += `<td class = "text-center" >`;
          $.each(value.pembayaran, function (i, val) {
            html += `<i>${val.nm_jenis_pembayaran}</i><br>`;
          });
          html += `</td>`;
          html += `<td class = "text-center" ><i><a href="#" class="btn btn-xs btn-info btn_show_bukti_trf" data-trf="${value.img_trf}" data-smt="${value.smt}" data-nipd="${value.nipd}" data-jns="${value.nm_jenis_pembayaran}">${value.img_trf}</a></i></td>`;
          html += `<td class = "text-center">`;
          html += `<a href="#" onclick="accTRF(${value.id_bukti_trf})" class="btn btn-xs btn-success btn_acc_trf" id="btn_acc_trf" value="${value.id_bukti_trf}">Accept</a> |`;
          html += `<a href="#" onclick="rejectTRF(${value.id_bukti_trf})" class="btn btn-xs btn-danger btn_reject_trf" id="btn_reject_trf" value="${value.id_bukti_trf}">Reject</a>`;
          html += `</td>`;
          html += `</tr>`;
        });
        $("#tbody_data_trf").html(html);
        $("#dataTransferMhs").modal("show");
        $(".btn_show_bukti_trf").click(function () {
          let img_trf = $(this).data("trf");
          let smt = $(this).data("smt");
          let nipd = $(this).data("nipd");
          let jns = $(this).data("jns");
          Swal.fire({
            title: "Bukti Transfer " + nipd,
            text: "Silahkan Cek Data Transfer Tersebut",
            imageUrl: `https://simak.wastu.digital/assets/${smt}/mahasiswa/bukti_trf/${img_trf}`,
            // imageWidth: 500,
            // imageHeight: 200,
            imageAlt: "Custom image",
            // showCancelButton: true,
            // confirmButtonColor: "#3085d6",
            // cancelButtonColor: "#d33",
            // confirmButtonText: "Yes, reset it!",
          });
        });
      }
    },
  });
});

function accTRF(id) {
  // console.log(id);
  $.ajax({
    url: "transaksi/acc_trf_online",
    type: "POST",
    data: { id: id },
    dataType: "JSON",
    success: function (response) {
      // console.log(response);
      Swal.fire({
        icon: "success",
        title: "Bukti Transfer Berhasil Di Accept",
        showConfirmButton: false,
        timer: 2000,
      });
      location.reload();
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
