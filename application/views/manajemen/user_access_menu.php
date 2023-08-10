<!-- Page content -->
<style>
    .hiddenRow {
        padding: 0 !important;
    }
</style>
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>

    <!-- Example Block -->
    <div class="block">
        <div class="block-title">
            <h2><?= $page; ?></h2>
        </div>
        <div class="" style="margin-bottom:5px;">
            <a class="btn btn-sm btn-primary btn_user_add mb-5" data-toggle="modal" data-target="#modalAddUserRole">Add User Role</a>
        </div>
        <!-- Example Content -->
        <table id="menu-datatable" class="table table-vcenter table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Role</th>
                    <th class="text-center">Menu Access</th>
                    <th class="text-center">Tools</th>
                </tr>
            </thead>
            <tbody id="user_access_menu_tbody">
                <!-- <?php foreach ($menu as $i => $val): ?>
                    <tr>
                        <th class="text-center"><?= $val['nama_menu'] ?></th>
                    </tr>
                <?php endforeach; ?> -->
            </tbody>
        </table>
    </div>
    <!-- Modal Add new role -->
    <div class="modal fade" id="modalAddUserRole" tabindex="-1" role="dialog" aria-labelledby="modalAddUserRoleTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Role Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_user_role" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="role_type" class="col-sm-3 col-form-label">Type Role</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="role_type" name="role_type" placeholder="Nama / Type Role" required>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="desc" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="desc" name="desc" placeholder="Description" required>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2 float-right">
                                <button type="submit" class="btn btn-sm btn-primary btn_save_role_user">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal add menu access -->
    <div class="modal fade" id="modalAddMenuAccess" tabindex="-1" role="dialog" aria-labelledby="modalAddMenuAccessTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Akses Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_menu_access" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="type_role" class="col-sm-3 col-form-label">Type Role</label>
                            <div class="col-sm-9">
                                <input type="hidden" class="form-control" id="id_role" name="id_role">
                                <input type="text" class="form-control" id="type_role" name="type_role" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="select_menu_access" class="col-sm-3 col-form-label">Menu</label>
                            <div class="col-sm-9">
                                <select class="form-control custom-select" id="select_menu_access" name="select_menu_access">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2 float-right">
                                <button type="submit" class="btn btn-sm btn-primary btn_save_menu_access" disabled>Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: '<?= base_url() ?>/admin/manajemen/data_user_access_menu',
                dataType: "json",
                success: function(response) {
                    console.log(response)
                    let html = ``;
                    $.each(response.data, function(i, value) {
                        html += `<tr>`;
                        html += `<td class="text-center">${value.role_type}</td>`;
                        html += `<td class="text-center"><a data-toggle="collapse" data-target="#demo${value.id_role}" class="btn btn-xs btn-default btn_access_menu accordion-toggle" nama-role="${value.role_type}" value="${value.id_role}"><i class="fas fa-eye"></i></a> 
                        <a data-toggle="tooltip" data-placement="top" title="Add new access menu for this role." class="btn btn-xs btn-primary btn_add_menu_access" nama-role="${value.role_type}" value="${value.id_role}"><i class="fas fa-plus text-small"></i></a></td>`;
                        html += `<a data-toggle="tooltip" data-placement="top" title="Delete this role." class="btn btn-xs btn-danger btn_delete_role" role-type="${value.role_type}" value="${value.id_role}"><i class="fas fa-trash-alt text-small"></i></a>`;
                        html += `<td class="text-center">`;
                        if(value.role_type != 'Dev System'){
                            html += `<a data-toggle="tooltip" data-placement="top" title="Delete this role." class="btn btn-xs btn-danger btn_delete_role" role-type="${value.role_type}" value="${value.id_role}"><i class="fas fa-trash-alt text-small"></i></a>`;
                        }else{
                            html += `<i>This role can't delete!</i>`;
                        }
                        html += `</tr>`;
                        html += `<tr>`;
                        html += `<td colspan="12" class="hiddenRow">`;
                        html += `<div id="demo${value.id_role}" class="accordian-body collapse">`;
                        html += `<table class="table table-striped table-bordered">`;
                        html += `<thead>`;
                        html += `<tr>`;
                        html += `<th class="text-center">Nama Menu</th>`;
                        html += `<th class="text-center">Tools</th>`;
                        html += `</tr>`;
                        html += `</thead>`;
                        $.each(value.menu_access, function(i, role_access_menu) {
                            html += `<tbody>`;
                            html += `<tr>`;
                            html += `<td class="text-center">${role_access_menu.nama_menu}</td>`;
                            html += `<td class="text-center">`;
                            if (role_access_menu.editable != 'N/A') {
                                html += `<a class="btn btn-xs btn-danger btn_delete_uam" nama-menu-access="${role_access_menu.nama_menu}" value="${role_access_menu.id}"><i class="fas fa-trash-alt"></i></a>`;
                            } else {
                                html += `<i>Not Deletable!</i>`;
                            }
                            html += `</td>`;
                            html += `</tr>`;
                            html += `</tbody>`;
                        });
                        html += `</table>`;
                        html += `</div>`;
                        html += `</td>`;
                        html += `</tr>`;
                    });
                    $("#user_access_menu_tbody").html(html);

                    $('.btn_add_menu_access').on('click', function() {
                        let role_id = $(this).attr('value');
                        console.log(role_id)
                        $.ajax({
                            type: "POST",
                            url: "role_access/get_menu_access",
                            data: {
                                role_id: role_id,
                            },
                            dataType: "json",
                            success: function(response) {
                                console.log(response);
                                $('#modalAddMenuAccess').modal("show");
                                $("#type_role").val(response.data.role_type);
                                $("#id_role").val(response.data.id_role);
                                if (response.data.menu_can_use != null) {
                                    $('#select_menu_access').append(`<option value="x">-- Pilih Menu --</option>`);
                                    $.each(response.data.menu_can_use, function(i, menu) {
                                        $('#select_menu_access').append(`<option value="${menu.id_menu}">${menu.nama_menu}</option>`);
                                    });
                                }


                            }
                        })
                    });
                    $('#modalAddMenuAccess').on('hidden.bs.modal', function(e) {
                        $('#form_menu_access')[0].reset();
                        $('#select_menu_access').html(``);
                    });

                    $('#select_menu_access').on('change', function() {
                        let id_menu = this.value;
                        if (id_menu != 'x') {
                            $('.btn_save_menu_access').prop('disabled', false)
                        } else {
                            $('.btn_save_menu_access').prop('disabled', true)
                        }
                    });

                    $("#form_menu_access").submit(function(e) {
                        e.preventDefault();
                        let form = $(this);
                        $.ajax({
                            type: "POST",
                            url: "role_access/simpan_menu_access",
                            data: form.serializeArray(),
                            dataType: "json",
                            success: function(response) {
                                // console.log(response);
                                let icon = ``;
                                let title = ``;
                                let text = ``;
                                if (response.code === 200) {
                                    icon = `success`;
                                    title = `Success`;
                                } else {
                                    icon = `error`;
                                    title = `Error`;
                                }
                                Swal.fire({
                                    icon: icon,
                                    title: title,
                                    text: response.msg,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(isConfirm) {
                                    location.reload()
                                });
                            }
                        })
                    });

                    $("#form_user_role").submit(function(e) {
                        e.preventDefault();
                        let form = $(this);
                        $.ajax({
                            type: "POST",
                            url: "role_access/simpan_role_baru",
                            data: form.serializeArray(),
                            dataType: "json",
                            success: function(response) {
                                // console.log(response);
                                let icon = ``;
                                let title = ``;
                                let text = ``;
                                if (response.code === 200) {
                                    icon = `success`;
                                    title = `Success`;
                                } else {
                                    icon = `error`;
                                    title = `Error`;
                                }
                                Swal.fire({
                                    icon: icon,
                                    title: title,
                                    text: response.msg,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(isConfirm) {
                                    location.reload()
                                });
                            }
                        })
                    });

                    $('.btn_delete_role').on('click', function(){
                        let id = $(this).attr('value');
                        let nama = $(this).attr('role-type');
                        // console.log(nama);
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `${nama} role, will delete!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "POST",
                                    url: "role_access/delete_role_user", // where you wanna post
                                    data: {
                                        id: id
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        // console.log(response)
                                        let title = ``;
                                        let msg = ``;
                                        let icon = ``;
                                        if (response.code === 200) {
                                            title = `Deleted`;
                                            icon = `success`;
                                        } else {
                                            title = `Error!`;
                                            icon = `error`;
                                        }
                                        Swal.fire(
                                            title,
                                            response.msg,
                                            icon
                                        )
                                        location.reload();
                                    }
                                })
                            }
                        })
                    })

                    $('.btn_edit_role').on('click', function() {
                        let id = $(this).attr('value');
                        console.log(id);
                    });
                    $('.btn_delete_uam').on('click', function() {
                        let id = $(this).attr('value');
                        let nama = $(this).attr('nama-menu-access');
                        // console.log(id);
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `The access to ${nama}, will delete for this role!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "POST",
                                    url: "role_access/delete_role_access_menu", // where you wanna post
                                    data: {
                                        id: id
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        // console.log(response)
                                        let title = ``;
                                        let msg = ``;
                                        let icon = ``;
                                        if (response.code === 200) {
                                            title = `Deleted`;
                                            icon = `success`;
                                        } else {
                                            title = `Error!`;
                                            icon = `error`;
                                        }
                                        Swal.fire(
                                            title,
                                            response.msg,
                                            icon
                                        )
                                        location.reload();
                                    }
                                })
                            }
                        })
                    });
                }
            });
        });
    </script>
</div>
<!-- END Page Content -->