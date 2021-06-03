$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "get-menu",
    dataType: "json",
    success: function (response) {
      if (response.length !== 0) {
        // console.log(response);
        let html = ``;
        $.each(response, function (i, value) {
          html += `<tr>`;
          html += `<td class="text-center">${value.nama_menu}</td>`;
          html += `<td class="text-center">${value.type}</td>`;
          html += `<td class="text-center"><i class="${value.icon}"></i></td>`;
          if (value.is_active == 1) {
            html += `<td class="text-center"><label class="switch switch-primary"><input id="toggle_activate${value.id_menu}" type="checkbox" value="${value.id_menu}" status="${value.is_active}" checked><span></span></label></td>`;
          } else {
            html += `<td class="text-center"><label class="switch switch-primary"><input id="toggle_activate${value.id_menu}" type="checkbox" value="${value.id_menu}" status="${value.is_active}"><span></span></label></td>`;
          }
          html +=
            `<td class="text-center">` +
            `<a href="#" class="badge badge-warning" id="btn_edit" value="${value.id_menu}"><i class="far fa-edit"></i></a>|` +
            `<a href="#" class="badge badge-danger" id="btn_hapus" value="${value.id_menu}"><i class="fas fa-trash-alt"></i></a>` +
            `</td>`;
          html += `</tr>`;
        });
        $("#menu_tbody").html(html);
        // Toggle
        $('input[type="checkbox"]').change(function (event) {
          let id_menu = $(this).attr("value");
          let status = $(this).attr("status");
          if (status == 0) {
            is_active = 1;
          } else {
            is_active = 0;
          }
          // =============================================
          if (id_menu == "") {
            alert("Error in id user");
          } else {
            $.ajax({
              type: "post",
              url: "update-menu",
              data: {
                id_menu: id_menu,
                status: is_active,
              },
              dataType: "json",
              success: function (response) {
                location.reload();
              },
              error: function (e) {
                error_server();
              },
            });
          }
        });
        // End Toggle
      }
    },
  });
});
