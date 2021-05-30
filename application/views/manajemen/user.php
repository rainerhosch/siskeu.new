<!-- Page content -->
<style>
    .modal-notify .modal-header {
        border-radius: 3px 3px 0 0;
    }

    .modal-notify .modal-content {
        border-radius: 3px;
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
            <h2>Data User</h2>
        </div>
        <button type="button" class="btn btn-primary btnAdd" data-toggle="modal" data-target="#addUser">
            Add User
        </button>
        <!-- Example Content -->
        <table id="menu-datatable" class="table table-vcenter table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Tools</th>
                </tr>
            </thead>
            <tbody id="menu_tbody">
                <?php $i = 1; ?>
                <?php foreach ($datauser as $u) :
                    // if($mn['is_active'] != 0):
                ?>
                    <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td class="text-center"><?= $u['nama_user']; ?></td>
                        <td class="text-center">
                            <a href="#" class="badge badge-warning" data-user_id="<?= $u['id_user']; ?>" data-toggle="modal" data-target="#editUser"><i class="far fa-edit"></i></a>|
                            <a href="#" class="badge badge-danger"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>

            </tbody>
        </table>
        <!-- END Example Content -->
    </div>
    <!-- END Example Block -->

    <!-- modal add -->
    <!-- <div class="modal" tabindex="-1" role="dialog" id="addUser"> -->
    <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-warning" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header text-center">
                    <h4 class="modal-title white-text w-100 font-weight-bold py-2">Tambah User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <div class="md-form mb-5 row">
                        <!-- <i class="fas fa-user prefix grey-text"></i> -->
                        <!-- <div class="row"> -->
                        <div class="col-md-3">
                            <label data-error="wrong" data-success="right" for="nama_user">Nama</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="nama_user" class="form-control validate">
                        </div>
                        <!-- </div> -->
                    </div>
                    <div class="md-form mb-5 row">
                        <!-- <i class="fas fa-envelope prefix grey-text"></i> -->
                        <div class="col-md-3">
                            <label data-error="wrong" data-success="right" for="username">Username</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="username" class="form-control validate">
                        </div>
                    </div>

                    <div class="md-form mb-5 row">
                        <!-- <i class="fas fa-envelope prefix grey-text"></i> -->
                        <div class="col-md-3">
                            <label data-error="wrong" data-success="right" for="password">Password</label>
                        </div>
                        <div class="col-md-9">
                            <input type="password" id="password" class="form-control validate">
                        </div>
                    </div>

                    <div class="md-form row">
                        <!-- <i class="fas fa-envelope prefix grey-text"></i> -->
                        <div class="col-md-3">
                            <label data-error="wrong" data-success="right" for="role">Role</label>
                        </div>
                        <div class="col-md-9">
                            <select id="example-select2" name="example-select2" class="select-select2 select2-hidden-accessible" style="width: 100%;" data-placeholder="Choose one.." tabindex="-1" aria-hidden="true">
                                <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                <option value="2">Administrator</option>
                                <option value="3">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!--Footer-->
                <div class="modal-footer justify-content-center text-center">
                    <a type="button" class="btn btn-outline-warning waves-effect">Send <i class="fas fa-paper-plane-o ml-1"></i></a>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!-- end modal add -->

    <!-- modal edit -->
    <div class="modal" tabindex="-1" role="dialog" id="editUser">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal edit -->
</div>
<!-- END Page Content -->