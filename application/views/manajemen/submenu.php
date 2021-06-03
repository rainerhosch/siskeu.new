<!-- Page content -->
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
        <button type="button" class="btn btn-primary btnAdd" data-toggle="modal" data-target="#addSubMenu">
            Add SubMenu
        </button>
        <!-- Example Content -->
        <table id="menu-datatable" class="table table-vcenter table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Menu Parent</th>
                    <th class="text-center">Nama SubMenu</th>
                    <th class="text-center">Icon</th>
                    <th class="text-center">Status Aktif</th>
                    <th class="text-center">Option</th>
                </tr>
            </thead>
            <tbody id="submenu_tbody">
                <?php $i = 1; ?>
                <?php foreach ($datasubmenu as $sm) :
                    // if($mn['is_active'] != 0):
                ?>
                    <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td class="text-center"><?= $sm['nama_menu']; ?></td>
                        <td class="text-center"><?= $sm['nama_submenu']; ?></td>
                        <td class="text-center"><?= '<i class="' . $sm['icon'] . '"></i>'; ?></td>
                        <td class="text-center">
                            <?php if ($sm['is_active'] == 1) : ?>
                                <i class="hi hi-ok"></i>
                            <?php else : ?>
                                <i class="hi hi-remove"></i>
                            <?php endif; ?>
                        </td>
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