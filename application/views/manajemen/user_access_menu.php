<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>Page</li>
        <li><a href=""><?= $page; ?></a></li>
    </ul>

    <!-- Example Block -->
    <div class="block">
        <div class="block-title">
            <h2><?= $page; ?></h2>
        </div>
        <button type="button" class="btn btn-primary btnAdd" data-toggle="modal" data-target="#addUser">
            Add User Access
        </button>
        <!-- Example Content -->
        <table id="menu-datatable" class="table table-vcenter table-condensed table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Menu</th>
                    <th class="text-center">User</th>
                </tr>
            </thead>
            <tbody id="user_access_menu_tbody">
                <!-- <?php foreach ($menu as $i => $val) : ?>
                    <tr>
                        <th class="text-center"><?= $val['nama_menu'] ?></th>
                    </tr>
                <?php endforeach; ?> -->
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: 'get-menu',
                dataType: "json",
                success: function(response) {
                    console.log(response)
                    let html = ``;
                    $.each(response, function(i, value) {
                        html += `<tr>`;
                        html += `<td class="text-center">${value.nama_menu}</td>`;
                        html += `<td class="text-center">${value.type}</td>`;
                        html += `</tr>`;
                    });
                    $("#user_access_menu_tbody").html(html);
                }
            });
        });
    </script>
</div>
<!-- END Page Content -->