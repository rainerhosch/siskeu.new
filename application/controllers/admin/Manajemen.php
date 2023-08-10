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

    public function UserAccessMenu()
    {
        $data['title'] = 'SISKEU NEW';
        $data['page'] = 'User Access Menu';
        $data['content'] = 'manajemen/user_access_menu';
        // $data['menu'] = $this->menu->getMenu()->result_array();
        $this->load->view('template', $data);
    }

    public function data_user_access_menu()
    {
        if ($this->input->is_ajax_request()) {
            $data = [];
            $data_role = $this->user->roleUser()->result_array();
            foreach ($data_role as $i => $role) {
                $role['menu_access'] = $this->menu->getUserMenu(['uam.role_id' => $role['id_role']])->result_array();
                $data[$i] = $role;
            }
            $res = [
                'status' => true,
                'code' => 200,
                'data' => $data,
                'msg' => 'success.'
            ];
        } else {
            $res = [
                'status' => false,
                'code' => 403,
                'data' => null,
                'msg' => 'Invalid request.'
            ];
        }
        echo json_encode($res);
    }

    // ===================== Menu Manajemen ==================================
    public function ManajemenMenu()
    {
        $data['title'] = 'SISKEU NEW';
        $data['page'] = 'Manajemen Menu';
        $data['content'] = 'manajemen/menu';
        $this->load->view('template', $data);
    }

    public function AddNewMenu()
    {
        // code here ...
        $nama = $this->input->post('nama_menu');
        $link = $this->input->post('link_menu');
        $icon = $this->input->post('icon_menu');
        $type = $this->input->post('type_menu');
        $is_active = $this->input->post('is_active');
        $dataPost = [
            'nama_menu' => $nama,
            'link_menu' => $link,
            'type' => $type,
            'icon' => $icon,
            'is_active' => $is_active,
            'editable' => 'YES'
        ];
        $add = $this->menu->addNewMenu($dataPost);
        if (!$add) {
            // error
            $this->session->set_flashdata('error', 'Gagal menambahkan Menu!');
            redirect('manajemen/manajemen-menu');
        } else {
            $this->session->set_flashdata('success', 'Menu ' . $nama . ', berhasil ditambahkan!');
            redirect('manajemen/manajemen-menu');
        }
    }

    public function getDataMenu()
    {
        $where = [
            'editable =' => 'YES'
        ];
        $response = $this->menu->getMenu($where)->result_array();
        echo json_encode($response);
    }

    public function EditMenu()
    {
        // code here
        if ($this->input->is_ajax_request()) {
            $id_menu = $this->input->post('id_menu');
            $where = ['id_menu' => $id_menu];
            $data = $this->menu->getMenuById($where)->row_array();
        } else {
            $data = "Error di edit menu";
        }
        echo json_encode($data);
    }

    public function UpdateMenu()
    {
        // code here
        $id_menu = $this->input->post('id_menu_edit');
        $nama = $this->input->post('nama_menu_edit');
        $link = $this->input->post('link_menu_edit');
        $icon = $this->input->post('icon_menu_edit');
        $type = $this->input->post('type_menu_edit');
        $dataUpdate = [
            'nama_menu' => $nama,
            'link_menu' => $link,
            'type' => $type,
            'icon' => $icon
        ];
        $update = $this->menu->updateMenu($id_menu, $dataUpdate);
        if (!$update) {
            // error
            $this->session->set_flashdata('error', 'Gagal edit menu!');
            redirect('manajemen/manajemen-menu');
        } else {
            $this->session->set_flashdata('success', 'Sukses edit menu!');
            redirect('manajemen/manajemen-menu');
        }
    }


    public function ChangeStatusMenu()
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

    public function DeleteMenu()
    {
        $id_menu = $this->input->post('hapus_id_menu');
        $where = ['id_menu' => $id_menu];
        $deleted = $this->menu->deleteMenu($where);
        if (!$deleted) {
            // error
            $this->session->set_flashdata('error', 'Gagal hapus menu!');
            redirect('manajemen/manajemen-menu');
        } else {
            $this->session->set_flashdata('success', 'Data berhasil di hapus!');
            redirect('manajemen/manajemen-menu');
        }
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

    public function AddNewSubmenu()
    {
        $dataPost = [
            'id_menu' => $this->input->post('menu_parent'),
            'nama_submenu' => $this->input->post('nama_submenu'),
            'url' => $this->input->post('url_submenu'),
            'icon' => $this->input->post('icon_submenu'),
            'is_active' => 0,
        ];
        $add = $this->menu->addNewSubmenu($dataPost);
        if (!$add) {
            // error
            $this->session->set_flashdata('error', 'Gagal menambahkan SubMenu!');
            redirect('manajemen/manajemen-submenu');
        } else {
            $this->session->set_flashdata('success', 'SubMenu ' . $this->input->post('nama_submenu') . ', berhasil ditambahkan!');
            redirect('manajemen/manajemen-submenu');
        }
    }

    public function getDataSubMenu()
    {
        $response = $this->menu->getSubMenuAll()->result_array();
        echo json_encode($response);
    }

    // activate or non activate submenu
    public function ChangeStatusSubmenu()
    {
        if ($this->input->is_ajax_request()) {
            $id_submenu = $this->input->post('id_submenu');
            $is_active = $this->input->post('status');
            $dataUpdate = [
                // 'id_menu' => $id_menu,
                'is_active' => $is_active
            ];
            $data = $this->menu->updateSubMenu($id_submenu, $dataUpdate);
        } else {
            $data = "Error di edit Menu";
        }
        echo json_encode($data);
    }

    public function EditSubmenu()
    {
        // code here
        if ($this->input->is_ajax_request()) {
            $id_submenu = $this->input->post('id_submenu');
            $where = ['id_submenu' => $id_submenu];
            $data = $this->menu->getSubmenuById($where)->row_array();
        } else {
            $data = "Error di edit menu";
        }
        echo json_encode($data);
    }

    public function UpdateSubmenu()
    {
        // code here
        $id_submenu = $this->input->post('id_submenu_edit');
        $nama = $this->input->post('nama_submenu_edit');
        $link = $this->input->post('link_submenu_edit');
        $icon = $this->input->post('icon_submenu_edit');
        $id_menu = $this->input->post('menu_parent_edit');
        $dataUpdate = [
            'id_menu' => $id_menu,
            'nama_submenu' => $nama,
            'url' => $link,
            'icon' => $icon
        ];
        $update = $this->menu->updateSubmenu($id_submenu, $dataUpdate);
        if (!$update) {
            // error
            $this->session->set_flashdata('error', 'Gagal edit submenu!');
            redirect('manajemen/manajemen-submenu');
        } else {
            $this->session->set_flashdata('success', 'Sukses edit submenu!');
            redirect('manajemen/manajemen-submenu');
        }
    }
    // delete submenu
    public function DeleteSubmenu()
    {
        $id_submenu = $this->input->post('hapus_id_submenu');
        $where = ['id_submenu' => $id_submenu];
        $deleted = $this->menu->deleteSubmenu($where);
        if (!$deleted) {
            // error
            $this->session->set_flashdata('error', 'Gagal hapus submenu!');
            redirect('manajemen/manajemen-submenu');
        } else {
            $this->session->set_flashdata('success', 'Data berhasil di hapus!');
            redirect('manajemen/manajemen-submenu');
        }
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

    public function getUserByID()
    {
        if ($this->input->is_ajax_request()) {
            $id_user = $this->session->userdata('id_user');
            $data = $this->user->getUser(['id_user' => $id_user])->row_array();
        } else {
            $data = 'invalid request.';
        }
        echo json_encode($data);
    }

    public function AddUser()
    {
        // code here
        $nama = $this->input->post('nama_user');
        $username = $this->input->post('username');
        $pass = $this->input->post('add_password');
        $role = $this->input->post('add_role');
        $dataPost = [
            'nama_user' => $nama,
            'username' => $username,
            'password' => md5($pass),
            'role' => $role
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
        $id_user = $this->input->post('edit_id_user');
        $nama = $this->input->post('edit_nama');
        $username = $this->input->post('edit_username');
        $pass = $this->input->post('edit_password');
        $role = $this->input->post('edit_role');
        $dataUpdate = [
            'nama_user' => $nama,
            'username' => $username,
            'password' => md5($pass),
            'role' => $role
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