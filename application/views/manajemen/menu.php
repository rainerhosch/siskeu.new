<!-- Page content -->
<style>
    .block {
        block-size: 300px;
    }
</style>
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
                <!-- Example Content -->
                <table id="menu-datatable" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <!-- <th class="text-center">No</th> -->
                            <th class="text-center">Menu</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Icon</th>
                            <th class="text-center">Status</th>
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
</div>
<!-- END Page Content -->