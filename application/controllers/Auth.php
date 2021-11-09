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
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $pecah_tgl_waktu = explode(' ', $now);
        $this->tgl = $this->formattanggal->konversi($pecah_tgl_waktu[0]);
        $this->time = $pecah_tgl_waktu[1];
    }
    public function index()
    {
        if ($this->session->userdata('username') !== null) {
            // $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url('dashboard'));
        }
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
                    'id_user' => $user['id_user'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    // 'login_date' =>  $this->tgl,
                    // 'login_time' => $this->time
                ];
                $this->session->set_userdata($data);
                if ($user['role'] != 4) {
                    redirect('dashboard');
                } else {
                    redirect('transaksi');
                }
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
