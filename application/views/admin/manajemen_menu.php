<div class="card bg-light">
    <div class="card-header">
        <a href="" class="btn btn-outline-info" data-toggle="modal" data-target="#tambahMenu">Tambah Menu</a>
    </div>
    <div class="card-body">
        <table class="table table-sm table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Url</th>
                    <th scope="col">Type</th>
                    <th scope="col">Icon</th>
                    <th scope="col">Status Aktif</th>
                    <th scope="col">Option</th>
                </tr>
            </thead>
            <tbody id="tblMenu">
                <?php
                $i = 1;
                foreach ($menu as $mn) : ?>
                    <tr>
                        <th scope="row"><?= $i; ?></th>
                        <td><?= $mn['nama_menu']; ?></td>
                        <td><?= $mn['link_menu']; ?></td>
                        <td><?= $mn['type']; ?></td>
                        <td><?= $mn['icon']; ?></td>
                        <td><?= $mn['is_active']; ?></td>
                        <td>
                            <a class="badge badge-warning" href="">edit</a>
                            <a class="badge badge-danger" href="">hapus</a>
                        </td>
                    </tr>
                <?php
                    $i++;
                endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambahMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>