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
                    <a href="javascript:void(0)">
                        <i class="fa fa-cog fa-fw pull-right"></i>
                        Pengaturan
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
    <!-- END Right Header Navigation -->
</header>
<!-- <script type="text/javascript">
    var timestamp = '<?= time(); ?>';

    function updateTime() {
        $('#time').html(Date(timestamp));
        timestamp++;
    }
    $(function() {
        setInterval(updateTime, 1000);
    });
</script> -->