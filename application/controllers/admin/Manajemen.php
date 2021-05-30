<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_menu', 'menu');
        $this->load->model('M_user', 'user');
    }
    public function ManajemenMenu()
    {
        $data['menumanage'] = $this->menu->getMenu()->result_array();
        $data['content'] = 'menu/menu_manajemen';
        $data['title'] = 'Manajemen Menu';
        $this->load->view('template', $data);
    }
    public function getDataMenu()
    {
        $data['message'] = [
            'status' => true,
            'kode' => 200
        ];
        $data['menumanage'] = $this->menu->getMenu()->result_array();
        $json = json_encode($data);
    }


    public function ManajemenSubMenu()
    {
        // code here
        echo 'manajemen submenu';
    }
}
