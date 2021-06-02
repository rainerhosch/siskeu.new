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
                        <? php // $i = 1; 
                        ?>
                        <?php foreach ($datamenuaktif as $mn) :
                        ?>
                            <tr>
                                <!-- <td class="text-center"><?= $i; ?></td> -->
                                <td class="text-center"><?= $mn['nama_menu']; ?></td>
                                <td class="text-center"><?= $mn['type']; ?></td>
                                <td class="text-center"><?= '<i class="' . $mn['icon'] . '"></i>'; ?></td>
                                <td class="text-center">
                                    <?php if ($mn['is_active'] == 1) :  ?>
                                        <input type="checkbox" checked data-toggle="toggle" data-size="mini" class="toggle-<?= $mn['id_menu']; ?>" id="toggle_activate" value="<?= $mn['id_menu']; ?>" status="<?= $mn['is_active']; ?>">
                                    <?php else : ?>
                                        <input type="checkbox" data-toggle="toggle" data-size="mini" class="toggle-<?= $mn['id_menu']; ?>" id="toggle_activate" value="<?= $mn['id_menu']; ?>" status="<?= $mn['is_active']; ?>">
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="badge badge-warning" id="btn_edit" value="<?= $mn['id_menu']; ?>"><i class="far fa-edit"></i></a>|
                                    <!-- <a href="#" class="badge badge-danger" id="btn_non_aktifkan" value="<?= $mn['id_menu']; ?>" status="<?= $mn['is_active']; ?>">Non Aktifkan</a> -->
                                    <a href="#" class="badge badge-danger" id="btn_hapus" value="<?= $mn['id_menu']; ?>"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <? php // $i++; 
                            ?>
                        <?php endforeach; ?>

                    </tbody>
                </table>
                <!-- END Example Content -->
            </div>
            <!-- END Web Server Block -->
        </div>
    </div>
    <!-- END Block Menu -->
</div>
<!-- END Page Content -->