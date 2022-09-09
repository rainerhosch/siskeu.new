<?php

function login_check()
{
    $ci = get_instance();
    if (!$ci->session->userdata('role')) {
        redirect('auth');
    } else {
        $user_id = $ci->session->userdata('role');
        $akses_menu = $ci->uri->segment(1);
        $getMenu = $ci->db->get_where('menu', ['nama_menu' => $akses_menu])->row_array();
        $menu_id = $getMenu['id_menu'];
        $hakAccessMenu = $ci->db->get_where('user_access_menu', [
            'role_id' => $user_id,
            'menu_id' => $menu_id
        ]);

        if ($hakAccessMenu->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}
