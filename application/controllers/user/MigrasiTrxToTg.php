<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : MigrasiTrxToTg.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 13/09/2022
 *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
 */
class MigrasiTrxToTg extends CI_Controller
{
    private $smt_aktif;
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('Terbilang');
        $this->load->library('FormatTanggal');


        $this->load->model('M_cetak_kwitansi', 'cetak');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_transaksi', 'transaksi');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_user', 'user');
    }

    public function index()
    {
        $data['title'] = 'Utility';
        $data['page'] = 'Test Page';
        $data['content'] = 'layout/test_page';
        $this->load->view('template', $data);
    }

    public function migrate_to_tg()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
    }
}