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
        $this->load->model('M_menu', 'menu');
        $this->load->model('M_user', 'user');
    }
    public function index()
    {
        $data['title'] = 'SiskeuNEW';
        $data['slug'] = 'Dashboard';
        $data['content'] = 'page_test';
        $this->load->view('template', $data);
        // $this->load->view('app');
    }
}
