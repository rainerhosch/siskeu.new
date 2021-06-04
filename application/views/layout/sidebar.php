<!-- Main Sidebar -->
<div id="sidebar">
    <div id="sidebar-scroll">
        <div class="sidebar-content">
            <a href="<?= base_url() ?>" class="sidebar-brand">
                <i class="fa fa-xing"></i><span class="sidebar-nav-mini-hide"><strong>Siskeu</strong>New</span>
            </a>
            <ul class="sidebar-nav">
                <?php
                if ($this->session->userdata('role') == 1) {
                    $where = [
                        'm.is_active' => 1
                    ];
                    $this->db->distinct();
                    $this->db->select('m.id_menu, m.nama_menu, m.link_menu, m.type, m.icon, m.is_active');
                    $this->db->from('menu m');
                    $this->db->where($where);
                    $this->db->order_by('m.type', 'desc');
                    $menu = $this->db->get()->result_array();
                } else {
                    $where = [
                        'uam.role_id' => $this->session->userdata('role'),
                        'm.is_active' => 1
                    ];
                    $this->db->distinct();
                    $this->db->select('m.id_menu, m.nama_menu, m.link_menu, m.type, m.icon, m.is_active');
                    $this->db->from('menu m');
                    $this->db->join('user_access_menu uam', 'm.id_menu=uam.menu_id');
                    $this->db->where($where);
                    // $this->db->order_by('m.id_menu', 'asc');
                    $this->db->order_by('m.type', 'desc');
                    $menu = $this->db->get()->result_array();
                }


                foreach ($menu as $mn) : ?>
                    <?php if ($this->uri->segment(1) == $mn['link_menu']) {
                        $class = 'active';
                    } else {
                        $class = '';
                    }; ?>

                    <?php if ($mn['type'] != 'dinamis') : ?>
                        <li class="<?= $class ?>">
                            <a href="<?= base_url('') . $mn['link_menu']; ?>">
                                <i class="<?= $mn['icon']; ?> sidebar-nav-icon"></i>
                                <span class="sidebar-nav-mini-hide"><strong><?= $mn['nama_menu'] ?></strong></span></a>
                        </li>
                    <?php else : ?>
                        <li class="<?= $class ?>">
                            <a href="javascript:void(0)" class="sidebar-nav-menu">
                                <i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                                <i class="<?= $mn['icon']; ?> sidebar-nav-icon"></i>
                                <span class="sidebar-nav-mini-hide"><strong><?= $mn['nama_menu'] ?></strong></span></a>
                            <ul>
                                <?php
                                $where = [
                                    'sm.is_active' => 1
                                ];
                                $this->db->select('*');
                                $this->db->from('submenu sm');
                                $this->db->where($where);
                                $this->db->order_by('nama_submenu', 'asc');
                                $submenu = $this->db->get()->result_array();

                                foreach ($submenu as $sm) :
                                    if ($sm['id_menu'] === $mn['id_menu']) :
                                ?>
                                        <?php if ($title === $sm['nama_submenu']) : ?>
                                            <li class="active">
                                            <?php else : ?>
                                            <li>
                                            <?php endif; ?>
                                            <a href="<?= base_url('') . $sm['url']; ?>">
                                                <i class="<?= $sm['icon'] ?>"></i></i>&nbsp;&nbsp;&nbsp;<?= $sm['nama_submenu']; ?></a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<!-- END Main Sidebar -->