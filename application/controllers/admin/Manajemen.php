<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        $this->load->model('M_menu', 'menu');
        $this->load->model('M_user', 'user');
    }

    // ===================== Menu Manajemen ==================================
    public function ManajemenMenu()
    {
        $data['title'] = 'SISKEU NEW';
        $data['page'] = 'Manajemen Menu';
        $data['content'] = 'manajemen/menu';
        // $where = [
        //     'is_active' => 1
        // ];
        // $data['datamenuaktif'] = $this->menu->getMenu($where)->result_array();
        // // kondisi 2
        // $where2 = [
        //     'is_active' => 0
        // ];
        // $data['datamenutidakaktif'] = $this->menu->getMenu($where2)->result_array();
        $data['datamenuaktif'] = $this->menu->getMenu()->result_array();
        $this->load->view('template', $data);
    }


    public function getDataMenu()
    {
        $response = $this->menu->getMenu()->result_array();
        echo json_encode($response);
    }

    public function UpdateMenu()
    {
        if ($this->input->is_ajax_request()) {
            $id_menu = $this->input->post('id_menu');
            $is_active = $this->input->post('status');
            $dataUpdate = [
                // 'id_menu' => $id_menu,
                'is_active' => $is_active
            ];
            $data = $this->menu->updateMenu($id_menu, $dataUpdate);
        } else {
            $data = "Error di edit Menu";
        }
        echo json_encode($data);
    }


    // ===================== SubMenu Manajemen ==================================
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


    // ===================== User Manajemen ==================================
    public function ManajemenUser()
    {
        // code here
        $data['title'] = 'SISKEU NEW';
        $data['page'] = 'Manajemen User';
        $data['content'] = 'manajemen/user';
        $data['datauser'] = $this->user->getAllUser()->result_array();
        $this->load->view('template', $data);
    }

    public function AddUser()
    {
        // code here
        $nama       = $this->input->post('nama_user');
        $username   = $this->input->post('username');
        $pass       = $this->input->post('add_password');
        $role       = $this->input->post('add_role');
        $dataPost   = [
            'nama_user' => $nama,
            'username' => $username,
            'password' => md5($pass),
            'role'     => $role
        ];
        $add = $this->user->addUser($dataPost);
        if (!$add) {
            // error
            $this->session->set_flashdata('error', 'Gagal menambahkan user!');
            redirect('manajemen/manajemen-user');
        } else {
            $this->session->set_flashdata('success', 'Data ' . $username . ', berhasil ditambahkan!');
            redirect('manajemen/manajemen-user');
        }
    }

    public function EditUser()
    {
        // code here
        if ($this->input->is_ajax_request()) {
            $id_user = $this->input->post('id_user');
            $where = ['id_user' => $id_user];
            $data = $this->user->getUser($where)->row_array();
        } else {
            $data = "Error di editUser";
        }
        echo json_encode($data);
    }

    public function UpdateUser()
    {
        // code here
        $id_user    = $this->input->post('edit_id_user');
        $nama       = $this->input->post('edit_nama');
        $username   = $this->input->post('edit_username');
        $pass       = $this->input->post('edit_password');
        $role       = $this->input->post('edit_role');
        $dataUpdate   = [
            'nama_user' => $nama,
            'username' => $username,
            'password' => md5($pass),
            'role'     => $role
        ];
        $update = $this->user->updateUser($id_user, $dataUpdate);
        if (!$update) {
            // error
            $this->session->set_flashdata('error', 'Gagal edit user!');
            redirect('manajemen/manajemen-user');
        } else {
            $this->session->set_flashdata('success', 'Data ' . $username . ', berhasil di edit!');
            redirect('manajemen/manajemen-user');
        }
    }

    public function DeleteUser()
    {
        $id_user = $this->input->post('hapus_id_user');
        $where = ['id_user' => $id_user];
        $deleted = $this->user->deleteUser($where);
        if (!$deleted) {
            // error
            $this->session->set_flashdata('error', 'Gagal hapus user!');
            redirect('manajemen/manajemen-user');
        } else {
            $this->session->set_flashdata('success', 'Data berhasil di hapus!');
            redirect('manajemen/manajemen-user');
        }
    }
}
