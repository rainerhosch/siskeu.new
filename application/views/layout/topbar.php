<header class="navbar navbar-default">
    <ul class="nav navbar-nav-custom">
        <li>
            <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                <i class="fa fa-bars fa-fw"></i>
            </a>
        </li>
    </ul>

    <ul class="nav navbar-nav-custom">
        <li>
            <?php
            date_default_timezone_set('Asia/Jakarta');
            $now = date('Y-m-d H:i:s');
            $pecah_tgl_waktu = explode(' ', $now);
            $tanggal = $this->formattanggal->konversi($pecah_tgl_waktu[0]);
            $jam = $pecah_tgl_waktu[1];

            echo '<a>' . $tanggal . '&nbsp;|&nbsp; Jam ' . $jam . '</a>';
            ?>
            <!-- <a id="time"></a> -->
        </li>
    </ul>
    <!-- Right Header Navigation -->
    <ul class="nav navbar-nav-custom pull-right">
        <!-- User Dropdown -->
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                <?php
                $usrname = $this->session->userdata('username');
                $where = [
                    'username' => $usrname
                ];
                $user = $this->db->get_where('users', $where)->row_array();
                ?>
                <strong><?= $user['nama_user']; ?></strong>
                <img src="<?= base_url() ?>assets/proui/img/placeholders/avatars/avatar.jpg" alt="avatar"> <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                <li>
                    <a href="javascript:void(0)" id="edit_profile">
                        <i class="fa fa-cog fa-fw pull-right"></i>
                        Edit Profile
                    </a>
                    <a href="<?= base_url('auth/logout'); ?>">
                        <i class="fa fa-sign-out fa-fw pull-right"></i>
                        Keluar
                    </a>
                </li>
            </ul>
        </li>
        <!-- END User Dropdown -->
    </ul>


    <!-- modal edit -->
    <div class="modal" tabindex="-1" role="dialog" id="editProfile">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/manajemen'); ?>/UpdateUser" method="post" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" value="" name="edit_id_user" id="edit_id_user_profile">
                        <input type="hidden" class="form-control" name="edit_username" id="edit_username_profile">
                        <input type="hidden" class="form-control validate" name="edit_role" id="edit_role_profile">
                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="edit_nama_profile">Nama</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="edit_nama_profile" name="edit_nama" class="form-control validate">
                            </div>
                        </div>

                        <div class="md-form mb-5 row">
                            <div class="col-md-3">
                                <label data-error="wrong" data-success="right" for="edit_password_profile">Password</label>
                            </div>
                            <div class="col-md-9">
                                <input type="password" id="edit_password_profile" name="edit_password" class="form-control validate">
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
</header>
<script>
    $(document).ready(function() {
        $('#edit_profile').on('click', function() {
            $.ajax({
                type: "GET",
                url: `<?= base_url('manajemen/getUserByID') ?>`,
                dataType: "json",
                success: function(response) {
                    $('#editProfile').modal('show');
                    $('#edit_id_user_profile').val(response.id_user);
                    $('#edit_username_profile').val(response.username);
                    $('#edit_nama_profile').val(response.nama_user);
                    $('#edit_password_profile').val(response.password);
                    $('#edit_role_profile').val(response.role);
                }
            });
        });
    });
</script>