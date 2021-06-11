<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name       : Transaksi.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author          : Rizky Ardiansyah
 *  Date Created    : 22 Desember 2020
 */
class Transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        $this->load->model('M_transaksi', 'transaksi');
    }

    public function Cari_Mhs()
    {
        if ($this->input->is_ajax_request()) {
            $nipd = $this->input->post('nipd');
            $response = $this->transaksi->getMahasiswa($nipd);
            $dataRes = json_decode($response, true);
            $dataMhs = $dataRes['mhsdata'];

            $jenjang = $dataRes['mhsdata']['jenjang'];
            $where_tahun = [
                'angkatan' => $dataRes['mhsdata']['tahun_masuk']
            ];
            $dataBiaya = $this->transaksi->getBiayaAngkatan($where_tahun, $jenjang)->row_array();
            $C1 = $dataBiaya['cicilan_semester'] / 3;
            $C2 = $dataBiaya['cicilan_semester'] / 3;
            $C3 = $dataBiaya['cicilan_semester'] / 3;
            // $dataMhs['tg'] = '1000000';
            $dataMhs['ub'] = $dataBiaya['uang_bangunan'];
            $dataMhs['kmhs'] = $dataBiaya['kemahasiswaan'];
            $dataMhs['c1'] = $C1;
            $dataMhs['c2'] = $C2;
            $dataMhs['c3'] = $C3;
            $data =  json_encode($dataMhs);
        } else {
            $data = "Error di edit menu";
        }
        // echo json_encode($response);
        echo $data;
    }
    public function Pembayaran_Spp()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Pembayaran Spp';
        $data['content'] = 'transaksi/pembayaran_spp';
        $this->load->view('template', $data);
    }

    public function Pembayaran_Lainnya()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Pembayaran Lain';
        $data['content'] = 'transaksi/pembayaran_lainnya';
        $this->load->view('template', $data);
    }
}
