<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name             : Dispensasi.php
    *  File Type             : Controller
    *  File Package          : CI_Controller
    ** * * * * * * * * * * * * * * * * * **
    *  Author                : Rizky Ardiansyah
    *  Date Created          : 15/09/2025
    *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
*/
class Dispensasi extends CI_Controller
{
        public function __construct()
        {
            parent::__construct();
            $this->load->model('M_transaksi', 'transaksi');
            $this->load->model('M_masterdata', 'masterdata');
            $this->load->model('M_aktivasi_mhs', 'aktivasi');
            $this->load->model('M_tunggakan', 'tunggakan');
            $this->load->model('M_user', 'user');
        }

        public function index()
        {
            $data['title'] = 'SiskeuNEW';
            $data['page'] = 'Dispensasi';
            $data['content'] = 'dispensasi/v_list_dispen';
            $this->load->view('template', $data);
        }

}