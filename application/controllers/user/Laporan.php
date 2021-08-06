<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : Laporan.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 06/08/2021
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */
class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        date_default_timezone_set('Asia/Jakarta');
        $this->smt_aktif = getSemesterAktif();
        $this->load->config('pdf_config');
        $this->load->library('fpdf');
        $this->load->library('terbilang');
        define('FPDF_FONTPATH', $this->config->item('fonts_path'));

        $this->load->model('M_cetak_kwitansi', 'cetak');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_transaksi', 'transaksi');
    }


    public function HistoriTransaksi()
    {
        // code here...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Histori Transaksi';
        $data['content'] = 'laporan/histori_transaksi';
        $this->load->view('template', $data);
    }

    public function RekapDataTrx()
    {
        // code here ...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Rekap Data Transaksi';
        $data['content'] = 'laporan/rekap_data_transaksi';
        $this->load->view('template', $data);
    }
}
