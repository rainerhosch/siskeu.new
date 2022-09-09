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
          $("#edit_role option[class='" + response.role + "']").attr(
            "selected",
            "selected"
          );
          $("#edit_role option[class='" + response.role + "']").trigger(
            "change"
          );
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
});
