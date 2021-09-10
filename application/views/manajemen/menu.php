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

<script src="<?= base_url() ?>assets/template/js/menumanage.js"></script>
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

    <!-- Block Menu -->
    <!-- <div class="row">
        <div class="col-md-6"> -->
    <div class="block">
        <div class="block-title">
            <h2>Menu Aktif</h2>
        </div>
        <button type="button" class="btn btn-primary btnAdd" data-toggle="modal" data-target="#addMenu">
            Add Menu
        </button>
        <!-- Example Content -->
        <table id="menu-datatable" class="table table-vcenter table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Menu</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-center">Icon</th>
                    <th class="text-center">Status Aktif</th>
                    <th class="text-center">Option</th>
                </tr>
            </thead>
            <tbody id="menu_tbody">
                <!-- Load Data by Ajax -->
            </tbody>
        </table>
        <!-- END Example Content -->
    </div>
    <!-- </div>
    </div> -->
    <!-- END Block Menu -->

    <!-- modal add -->
    <div class="modal fade" id="addMenu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-warning" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header text-center">
                    <h4 class="modal-title white-text w-100 font-weight-bold py-2">Tambah Menu</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <form action="<?= base_url('admin/manajemen'); ?>/AddNewMenu" method="post" enctype="multipart/form-data">
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nama_menu">Nama</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nama_menu" name="nama_menu" class="form-control validate">
                                <input type="hidden" id="is_active" name="is_active" value="0">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="link_menu">Link Menu</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="link_menu" name="link_menu" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="icon_menu">Icon</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="icon_menu" name="icon_menu" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="type_menu">Type</label>
                            </div>
                            <div class="col-md-9">
                                <select id="type_menu" name="type_menu" class="select-select2 select2-hidden-accessible" style="width: 100%;" data-placeholder="Pilih Type Menu.." tabindex="-1" aria-hidden="true">
                                    <option></option>
                                    <option value="statis">Statis</option>
                                    <option value="dinamis">Dinamis</option>
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
    <div class="modal" tabindex="-1" role="dialog" id="editMenu">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('manajemen'); ?>/update-menu" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="id_menu_edit" id="id_menu_edit">
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="nama_menu_edit">Nama Menu</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="nama_menu_edit" name="nama_menu_edit" class="form-control validate">
                            </div>
                        </div>

                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="link_menu_edit">Link Menu</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="link_menu_edit" name="link_menu_edit" class="form-control validate">
                            </div>
                        </div>
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="icon_menu_edit">Icon</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="icon_menu_edit" name="icon_menu_edit" class="form-control validate">
                            </div>
                        </div>

                        <div class="md-form row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="type_menu_edit">Type</label>
                            </div>
                            <div class="col-md-9">
                                <select id="type_menu_edit" name="type_menu_edit" class="select-select2 select2-hidden-accessible" style="width: 100%;">
                                    <option></option>
                                    <?php $type =  $this->menu->typeMenu()->result_array();
                                    foreach ($type as $t) :
                                    ?>
                                        <option class="<?= $t['type']; ?>" value="<?= $t['type']; ?>"><?= strtoupper($t['type']); ?></option>
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
    <div class="modal" tabindex="-1" role="dialog" id="hapusMenu">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content2">
                <div class="modal-body">
                    <form action="<?= base_url('admin/manajemen'); ?>/DeleteMenu" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="hapus_id_menu" id="hapus_id_menu">
                        <h1>Delete Menu</h1>
                        <p>Apakah anda yakin, ingin menghapus menu tersebut?</p>

                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('hapusMenu').style.display='none'" class="btn btn-warning">Cancel</button>
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