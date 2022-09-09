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
<!-- Page content -->
<script src="<?= base_url() ?>assets/js/submenumanage.js"></script>
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>

    <!-- Example Block -->
    <div class="block">
        <div class="block-title">
            <h2>Data SubMenu</h2>
        </div>
        <button type="button" class="btn btn-primary btnAdd" data-toggle="modal" data-target="#addSubmenu">
            Add New
        </button>
        <!-- Example Content -->
        <table id="menu-datatable" class="table table-vcenter table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Menu Parent</th>
                    <th class="text-center">Nama Submenu</th>
                    <th class="text-center">Url</th>
                    <th class="text-center">Icon</th>
                    <th class="text-center">Status Aktif</th>
                    <th class="text-center">Option</th>
                </tr>
            </thead>
            <tbody id="submenu_tbody">

            </tbody>
        </table>
        <!-- END Example Content -->
    </div>
    <!-- END Example Block -->


    <!-- modal add -->
    <div class="modal fade" id="addSubmenu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-warning" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title white-text w-100 font-weight-bold py-2">Tambah Submenu</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/manajemen'); ?>/AddNewSubmenu" method="post" enctype="multipart/form-data">
                        <div class="md-form row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="menu_parent">Menu Parent</label>
                            </div>
                            <div class="col-md-9">
                                <select id="menu_parent" name="menu_parent" class="select-select2 select2-hidden-accessible" style="width: 100%;" data-placeholder="Pilih Parent Menu.." tabindex="-1" aria-hidden="true">
                                    <option></option>
                                    <?php
                                    $where = [
                                        'editable =' => 'YES',
                                        'type' => 'dinamis'
                                    ];
                                    $menu =  $this->menu->getMenu($where)->result_array();
                                    foreach ($menu as $mn) : ?>
                                        <option value="<?= $mn['id_menu'] ?>"><?= $mn['nama_menu'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nama_submenu">Nama Submenu</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nama_submenu" name="nama_submenu" class="form-control validate">
                                <input type="hidden" id="is_active" name="is_active" value="0">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="url_submenu">Url</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="url_submenu" name="url_submenu" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="icon_submenu">Icon</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="icon_submenu" name="icon_submenu" class="form-control validate">
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-center text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal add -->


    <!-- modal edit -->
    <div class="modal" tabindex="-1" role="dialog" id="editSubmenu">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Submenu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('manajemen'); ?>/update-submenu" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="id_submenu_edit" id="id_submenu_edit">
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nama_submenu_edit">Nama submenu</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nama_submenu_edit" name="nama_submenu_edit" class="form-control validate">
                            </div>
                        </div>

                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="link_submenu_edit">Link submenu</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="link_submenu_edit" name="link_submenu_edit" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="icon_submenu_edit">Icon</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="icon_submenu_edit" name="icon_submenu_edit" class="form-control validate">
                            </div>
                        </div>

                        <div class="md-form row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="menu_parent_edit">Menu Parent</label>
                            </div>
                            <div class="col-md-9">
                                <select id="menu_parent_edit" name="menu_parent_edit" class="select-select2 select2-hidden-accessible" style="width: 100%;">
                                    <option></option>
                                    <?php
                                    $where = [
                                        'editable =' => 'YES',
                                        'type' => 'dinamis'
                                    ];
                                    $menu =  $this->menu->getMenu($where)->result_array();
                                    foreach ($menu as $mn) : ?>
                                        <option class="<?= $mn['id_menu'] ?>" value="<?= $mn['id_menu'] ?>"><?= $mn['nama_menu'] ?></option>
                                    <?php endforeach; ?>
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
    <div class="modal" tabindex="-1" role="dialog" id="hapusSubmenu">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content2">
                <div class="modal-body">
                    <form action="<?= base_url('admin/manajemen'); ?>/DeleteSubmenu" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="hapus_id_submenu" id="hapus_id_submenu">
                        <h1>Delete Submenu</h1>
                        <p>Apakah anda yakin, ingin menghapus submenu tersebut?</p>

                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('hapusSubmenu').style.display='none'" class="btn btn-warning">Cancel</button>
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