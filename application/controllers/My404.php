<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name       : My404.php
    *  File Type       : Controller
    *  File Package    : CI_Controller
    ** * * * * * * * * * * * * * * * * * **
    *  Author          : Rizky Ardiansyah
    *  Date Created    : 11 Januari 2021
*/
class My404 extends CI_Controller
{
        public function __construct()
        {
            parent::__construct();
        }

        public function index()
        {
            $data['title'] = '404';
            $data['content'] = 'layout/my_error';
            $this->output->set_status_header('404');
            $this->load->view('template', $data);
        }
}