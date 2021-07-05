<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *  File Name       : Dashboard.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 *  
 *  Date Created 16 Desember 2020
 *  Author @Rizky Ardiansyah
 */

class Dashboard extends CI_Controller
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
    public function index()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Dashboard';
        $data['content'] = 'v_dashboard';
        $this->load->view('template', $data);
        // $this->load->view('app');
    }
}
