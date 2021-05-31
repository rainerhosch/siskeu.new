<!-- Page content -->
<style>
    /* Modal Content/Box */
    .modal-content2 {
        text-align: center;
        background-color: #fefefe;
        margin: 5% auto 15% auto;
        border: 1px solid #888;
        width: 80%;
    }

    /* Style the horizontal ruler */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

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
    <?php if ($this->session->flashdata('success')) {
        echo '<div class="alert alert-success" role="alert">' . $this->session->flashdata('success') . '</div>';
    } elseif ($this->session->flashdata('error')) {
        echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata('error') . '</div>';
    }
    ?>

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
                            <?php if ($u['role'] != 1) : ?>
                                <a href="#" class="badge badge-warning" id="proses_edit" value="<?= $u['id_user']; ?>"><i class="far fa-edit"></i></a>|
                                <a href="#" onclick="document.getElementById('id01').style.display='block'" class="badge badge-danger" id="hapus_user" value="<?= $u['id_user']; ?>"><i class="fas fa-trash-alt"></i></a>
                            <?php else : ?>
                                <i class="text-dark">Akun ini tidak dapat diubah.</i>
                            <?php endif; ?>
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
                    <form action="<?= base_url('admin/manajemen'); ?>/AddUser" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nama_user">Nama</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nama_user" name="nama_user" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="username">Username</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="username" name="username" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="password">Password</label>
                            </div>
                            <div class="col-md-9">
                                <input type="password" id="password" name="password" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="role">Role</label>
                            </div>
                            <div class="col-md-9">
                                <select id="role" name="role" class="select-select2 select2-hidden-accessible" style="width: 100%;" data-placeholder="Pilih Role User.." tabindex="-1" aria-hidden="true">
                                    <option></option>
                                    <option value="2">Administrator</option>
                                    <option value="3">Admin</option>
                                </select>
                            </div>
                        </div>
                </div>

                <!--Footer-->
                <div class="modal-footer justify-content-center text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
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
                    <form action="<?= base_url('admin/manajemen'); ?>/UpdateUser" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="edit_id_user" id="edit_id_user">
                        <input type="hidden" class="form-control" name="edit_username" id="edit_username">
                        <!-- <input type="text" value="" id="edit_id_user" name="edit_id_user" class="form-control validate"> -->
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="edit_nama">Nama</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="edit_nama" name="edit_nama" class="form-control validate">
                            </div>
                        </div>

                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="password">Password</label>
                            </div>
                            <div class="col-md-9">
                                <input type="password" id="password" name="password" class="form-control validate">
                            </div>
                        </div>

                        <div class="md-form row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="role">Role</label>
                            </div>
                            <div class="col-md-9">
                                <select id="role" name="role" class="select-select2 select2-hidden-accessible" style="width: 100%;" data-placeholder="Pilih Role User.." tabindex="-1" aria-hidden="true">
                                    <option></option>
                                    <option value="2">Administrator</option>
                                    <option value="3">Admin</option>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal edit -->

    <!-- Delete Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="id01">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content2">
                <div class="modal-body">
                    <form action="<?= base_url('admin/manajemen'); ?>/DeleteUser" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="hapus_id_user" id="hapus_id_user">
                        <h1>Delete Account</h1>
                        <p>Apakah anda yakin, ingin menghapus akun tersebut?</p>

                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('id01').style.display='none'" class="btn btn-warning">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete Modal -->
</div>
<!-- END Page Content -->