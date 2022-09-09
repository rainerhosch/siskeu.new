$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "get-submenu",
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
          html += `<td class="text-center">${value.nama_submenu}</td>`;
          html += `<td class="text-center">${value.url}</td>`;
          html += `<td class="text-center"><i class="${value.icon}"></i></td>`;
          if (value.is_active == 1) {
            html += `<td class="text-center"><label class="switch switch-primary"><input id="toggle_submenu|${value.id_submenu}" for-cek="submenu" type="checkbox" value="${value.id_submenu}" status="${value.is_active}" checked><span></span></label></td>`;
          } else {
            html += `<td class="text-center"><label class="switch switch-primary"><input id="toggle_submenu|${value.id_submenu}" for-cek="submenu" type="checkbox" value="${value.id_submenu}" status="${value.is_active}"><span></span></label></td>`;
          }
          html +=
            `<td class="text-center">` +
            `<a href="#" class="badge badge-warning" id="btn_edit_submenu" value="${value.id_submenu}"><i class="far fa-edit"></i></a>|` +
            `<a href="#" onclick="document.getElementById('hapusSubmenu').style.display='block'" class="badge badge-danger btn-hapus-submenu" id="btn_hapus_submenu" value="${value.id_submenu}"><i class="fas fa-trash-alt"></i></a>` +
            `</td>`;
          html += `</tr>`;
          no++;
        });
        $("#submenu_tbody").html(html);
        // Toggle
        $('input[type="checkbox"]').change(function (event) {
          // let id = $(this).attr("id");
          let define = $(this).attr("for-cek");
          if (define == "submenu") {
            let id_submenu = $(this).attr("value");
            let status = $(this).attr("status");
            if (status == 0) {
              is_active = 1;
            } else {
              is_active = 0;
            }
            if (id_submenu == "") {
              alert("Error in id_submenu");
            } else {
              $.ajax({
                type: "post",
                url: "change-status-submenu",
                data: {
                  id_submenu: id_submenu,
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
        });
        // End Toggle
        // hapus menu
        $("a.btn-hapus-submenu").click(function () {
          let id_submenu = $(this).attr("value");
          // console.log(id_submenu);
          if (id_submenu == "") {
            alert("Error in id menu");
          } else {
            $("#hapus_id_submenu").val(id_submenu);
          }
        });
        // end hapus menu
      }
    },
  });

  $(this).on("click", "#btn_edit_submenu", function (e) {
    e.preventDefault();
    let id_submenu = $(this).attr("value");
    // console.log(id_menu);
    if (id_submenu == "") {
      alert("Error in id user");
    } else {
      $.ajax({
        type: "post",
        url: "edit-submenu",
        data: {
          id_submenu: id_submenu,
        },
        dataType: "json",
        success: function (response) {
          // console.log(response);
          $("#editSubmenu").modal("show");
          $("#id_submenu_edit").val(response.id_submenu);
          $("#nama_submenu_edit").val(response.nama_submenu);
          $("#link_submenu_edit").val(response.url);
          $("#icon_submenu_edit").val(response.icon);
          $("#menu_parent_edit option[class='" + response.id_menu + "']").attr(
            "selected",
            "selected"
          );
          $(
            "#menu_parent_edit option[class='" + response.id_menu + "']"
          ).trigger("change");
        },
        error: function (e) {
          error_server();
        },
      });
    }
  });
});
