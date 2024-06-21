<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name             : CreateVA.php
    *  File Type             : Controller
    *  File Package          : CI_Controller
    ** * * * * * * * * * * * * * * * * * **
    *  Author                : Rizky Ardiansyah
    *  Date Created          : 19/06/2024
    *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
*/
class CreateVA extends CI_Controller
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
            $this->load->model('M_tunggakan', 'tunggakan');
        }

        public function index()
        {
            $data['title'] = 'SiskeuNEW';
            $data['page'] = 'Create VA';
            $data['content'] = 'va/v_virtual_account';
    
            $this->load->view('template', $data);
        }

}