<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>

    <!-- Block Menu -->
    <div class="row">
        <div class="col-md-6">
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
        </div>
    </div>
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
</div>
<!-- END Page Content -->