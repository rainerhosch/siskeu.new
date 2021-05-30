<!-- Page content -->
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
                            <a href="#" class="badge badge-warning"><i class="far fa-edit"></i></a>|
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
</div>
<!-- END Page Content -->