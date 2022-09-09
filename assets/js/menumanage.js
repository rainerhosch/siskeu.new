$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "get-menu",
    dataType: "json",
    success: function (response) {
      if (response.length !== 0) {
        // console.log(response);
        let html = ``;
        let no = 1;
        $.each(response, function (i, value) {
          html += `<tr>`;
          html += `<td class="text-center">${no}</td>`;
          html += `<td class="text-center">${value.nama_menu}</td>`;
          html += `<td class="text-center">${value.type}</td>`;
          html += `<td class="text-center"><i class="${value.icon}"></i></td>`;
          if (value.is_active == 1) {
            html += `<td class="text-center"><label class="switch switch-primary"><input id="toggle_menu|${value.id_menu}" for-cek="toggle_menu" type="checkbox" value="${value.id_menu}" status="${value.is_active}" checked><span></span></label></td>`;
          } else {
            html += `<td class="text-center"><label class="switch switch-primary"><input id="toggle_menu|${value.id_menu}" for-cek="toggle_menu" type="checkbox" value="${value.id_menu}" status="${value.is_active}"><span></span></label></td>`;
          }
          html +=
            `<td class="text-center">` +
            `<a href="#" class="badge badge-warning edit-menu" id="btn_edit_menu" value="${value.id_menu}"><i class="far fa-edit"></i></a>|` +
            `<a href="#" onclick="document.getElementById('hapusMenu').style.display='block'" class="badge badge-danger btn-hapus" id="btn_hapus_menu" value="${value.id_menu}"><i class="fas fa-trash-alt"></i></a>` +
            `</td>`;
          html += `</tr>`;
          no++;
        });
        $("#menu_tbody").html(html);
        // Toggle
        $('input[type="checkbox"]').change(function (event) {
          let cek = $(this).attr("for-cek");
          if (cek == "toggle_menu") {
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
                url: "change-status-menu",
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
          }
          // end cek
        });
        // End Toggle
        // =======================================
        // hapus menu
        $("a.btn-hapus").click(function () {
          let id_menu = $(this).attr("value");
          console.log(id_menu);
          if (id_menu == "") {
            alert("Error in id menu");
          } else {
            $("#hapus_id_menu").val(id_menu);
          }
        });
        // end hapus menu
      }
    },
  });

  $(this).on("click", "#btn_edit_menu", function (e) {
    e.preventDefault();
    let id_menu = $(this).attr("value");
    // console.log(id_menu);
    if (id_menu == "") {
      alert("Error in id user");
    } else {
      $.ajax({
        type: "post",
        url: "edit-menu",
        data: {
          id_menu: id_menu,
        },
        dataType: "json",
        success: function (response) {
          // console.log(response);
          $("#editMenu").modal("show");
          $("#id_menu_edit").val(response.id_menu);
          $("#nama_menu_edit").val(response.nama_menu);
          $("#link_menu_edit").val(response.link_menu);
          $("#icon_menu_edit").val(response.icon);
          $("#type_menu_edit option[class='" + response.type + "']").attr(
            "selected",
            "selected"
          );
          $("#type_menu_edit option[class='" + response.type + "']").trigger(
            "change"
          );
        },
        error: function (e) {
          error_server();
        },
      });
    }
  });
});
