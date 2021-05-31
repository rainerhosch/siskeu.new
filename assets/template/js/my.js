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
          //   $("#editUser").modal("show");
          //   $("#edit_id_menu").val(response.id_menu);
          //   $("#edit_nama").val(response.nama_user);
          //   $("#edit_username").val(response.username);
          // $('#edit_password').val(response.password);
          // $('#edit_role').val(response.role);
        },
        error: function (e) {
          error_server();
        },
      });
    }
  });
});
