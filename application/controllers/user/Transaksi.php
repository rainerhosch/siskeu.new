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
            // cek tunggakan
            $dataCekTG = [
                'nim' => $nipd
            ];
            $dataTG = $this->transaksi->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG != null) {
                $dataMhs['tg'] = $dataTG['jml_tunggakan'];
            }
            // else {
            //     $dataMhs['tg'] = null;
            // }

            // cek biaya angkatan
            $dataBiaya = $this->transaksi->getBiayaAngkatan($where_tahun, $jenjang)->row_array();
            // $dataMhs['ub'] = $dataBiaya['uang_bangunan'];
            $dataMhs['kmhs'] = $dataBiaya['kemahasiswaan'];
            $C1 = $dataBiaya['cicilan_semester'] / 3;
            $C2 = $dataBiaya['cicilan_semester'] / 3;
            $C3 = $dataBiaya['cicilan_semester'] / 3;
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
    public function Proses_Bayar_Spp()
    {
        $nimMhs = $this->input->post('nim_mhs_bayar');
        $namaMhs = $this->input->post('nama_mhs_bayar');
        $bayarTG = $this->input->post('tunggakan');
        $bayarKMHS = $this->input->post('kmhs');
        $bayarC1 = $this->input->post('C1');
        $bayarC2 = $this->input->post('C2');
        $bayarC3 = $this->input->post('C3');
        $dataBayar = [
            'nim' => $nimMhs,
            'nama' => $namaMhs,
            'tunggakan' => $bayarTG
        ];
        var_dump($dataBayar);
        die;
    }

    public function Pembayaran_Lainnya()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Pembayaran Lain';
        $data['content'] = 'transaksi/pembayaran_lainnya';
        $this->load->view('template', $data);
    }
}
