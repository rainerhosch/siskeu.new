$(document).ready(function () {
  $(this).on("click", "#proses_edit", function (e) {
    e.preventDefault();
    let id_user = $(this).attr("value");
    if (id_user == "") {
      alert("Error in id user");
    } else {
      $.ajax({
        type: "post",
        url: "edit-user",
        data: {
          id_user: id_user,
        },
        dataType: "json",
        success: function (response) {
          console.log(response);
          $("#editUser").modal("show");
          $("#edit_id_user").val(response.id_user);
          $("#edit_nama").val(response.nama_user);
          $("#edit_username").val(response.username);
          // $('#edit_password').val(response.password);
          // $('#edit_role').val(response.role);
        },
        error: function (e) {
          error_server();
        },
      });
    }
  });

  $(this).on("click", "#hapus_user", function (e) {
    e.preventDefault();
    let id_user = $(this).attr("value");
    console.log(id_user);
    if (id_user == "") {
      alert("Error in id user");
    } else {
      $("#hapus_id_user").val(id_user);
    }
  });
  // end modul user

  // ============== modul menu =================

  $.ajax({
    type: "GET",
    url: "get-menu",
    dataType: "json",
    success: function (response) {
      if (response.length !== 0) {
        console.log(response);
        let html = ``;
        $.each(response, function (i, value) {
          html += `<tr>`;
          html += `<td class="text-center">${value.nama_menu}</td>`;
          html += `<td class="text-center">${value.type}</td>`;
          html += `<td class="text-center"><i class="${value.icon}"></i></td>`;
          if (value.is_active !== 1) {
            // console.log(value.is_active);
            // $('input[type="checkbox"]').prop("checked", true);
            // $('input[type="checkbox"]').attr("checked", "checked");
            html += `<td class="text-center"><input class="toggle-${value.id_menu}" type="checkbox" checked data-toggle="toggle" data-size="mini" id="toggle_activate" value="${value.id_menu}" status="${value.id_menu}"></td>`;
          } else {
            html += `<td class="text-center"><input class="toggle-${value.id_menu}" type="checkbox" data-toggle="toggle" data-size="mini" id="toggle_activate" value="${value.id_menu}" status="${value.id_menu}"></td>`;
          }
          html +=
            `<td class="text-center">` +
            `<a href="#" class="badge badge-warning" id="btn_edit" value="${value.id_menu}"><i class="far fa-edit"></i></a>|` +
            `<a href="#" class="badge badge-danger" id="btn_hapus" value="${value.id_menu}"><i class="fas fa-trash-alt"></i></a>` +
            `</td>`;
          html += `</tr>`;
        });
        // $("#menu_tbody").html(html);
      }
    },
  });

  // $("#toggle_activate").change(function () {
  $(this).on("click", "#btn_non_aktifkan", function (e) {
    e.preventDefault();
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
          console.log(response);
          // ...
          location.reload();
        },
        error: function (e) {
          error_server();
        },
      });
    }
  });
});
