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

    public function AddUser()
    {
        // code here
        $nama       = $this->input->post('nama_user');
        $username   = $this->input->post('username');
        $pass       = $this->input->post('password');
        $role       = $this->input->post('role');
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
