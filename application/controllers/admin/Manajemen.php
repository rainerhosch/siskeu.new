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
        $data['title'] = 'SISKEU NEW';
        $data['page'] = 'Manajemen Menu';
        $data['content'] = 'manajemen/menu';
        $data['menumanage'] = $this->menu->getMenu()->result_array();
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
        $data['title'] = 'SISKEU NEW';
        $data['page'] = 'Manajemen SubMenu';
        $data['content'] = 'manajemen/submenu';
        $data['datasubmenu'] = $this->menu->getSubMenuAll()->result_array();
        // var_dump($data['submenumanage']);
        // die;
        $this->load->view('template', $data);
    }

    public function ManajemenUser()
    {
        // code here
        $data['title'] = 'SISKEU NEW';
        $data['page'] = 'Manajemen User';
        $data['content'] = 'manajemen/user';
        $data['datauser'] = $this->user->getAllUser()->result_array();
        $this->load->view('template', $data);
    }
}
