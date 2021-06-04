<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *  File Name       : Auth.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 *  
 *  Date Created 16 Desember 2020
 *  Author @Rizky Ardiansyah
 */

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') !== null) {
            // $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url('dashboard'));
        }
    }
    public function index()
    {
        // code here...
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login Page';
            $data['content'] = 'auth/login';
            $this->load->view('template', $data);
        } else {
            // validasi sukses
            $this->_login();
        }
    }

    private function _login()
    {
        // code here...
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $user = $this->db->get_where('users', ['username' => $username])->row_array();
        // var_dump($user);
        // die;
        if ($user) {
            // code here...
            if (md5($password) == $user['password']) {
                $data = [
                    // 'nama' => $user['nama_user'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                $this->session->set_userdata($data);
                // if ($user['role'] != 1) {
                //     redirect('welcome');
                // } else {
                redirect('dashboard');
                // }
            } else {
                // password salah
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password Salah!</div>');
                redirect('auth');
            }
        } else {
            // data login tidak ada
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Tidak ada data dengan username ' . $username . '!</div>');
            redirect('auth');
        }
    }

    // sign-out
    public function logout()
    {
        // code here...
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role');
        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Berhasil Logout!</div>');
        // redirect
        redirect('auth');
    }
}
