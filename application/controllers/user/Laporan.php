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
    private $smt_aktif;
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        // $token = 'semogabahagia';
        // $this->smt_aktif = getSemesterAktif($token);
        date_default_timezone_set('Asia/Jakarta');
        $this->load->config('pdf_config');
        $this->load->library('fpdf');
        $this->load->library('terbilang');
        define('FPDF_FONTPATH', $this->config->item('fonts_path'));

        $this->load->model('M_cetak_kwitansi', 'cetak');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_transaksi', 'transaksi');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_laporan', 'laporan');
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
        $kondisi = 'id_jenis_pembayaran <> 1 AND id_jenis_pembayaran <> 6 AND id_jenis_pembayaran <> 7 AND id_jenis_pembayaran <> 8';
        $data['field'] = $this->masterdata->GetJenisPembayaran($kondisi)->result_array();
        $this->load->view('template', $data);
    }

    public function PenerimaanKasYayasan()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Penerimaan Kas Yayasan';
        $data['content'] = 'laporan/penerimaan_kas_yayasan';
        $this->load->view('template', $data);
    }

    public function PenerimaanKasSTT()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Penerimaan Kas STT';
        $data['content'] = 'laporan/penerimaan_kas_stt';
        $this->load->view('template', $data);
    }

    public function PenerimaanKasKmhs()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Penerimaan Kas Kemahasiswaan';
        $data['content'] = 'laporan/penerimaan_kas_kmhs';
        $this->load->view('template', $data);
    }

    public function getDataPenerimaanKasYayasan()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            // $input = $this->input->post('data');
            $where = [
                't.semester' => $smtAktif,
                'mjp.jenis_kas' => 1
            ];
            $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $where_DTx = [
                    't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                    'mjp.jenis_kas' => 1
                ];
                $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
            }
            $data['kas_yayasan'] = $dataHistoriTx;
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }

    public function getDataPenerimaanKasKmhs()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            // $input = $this->input->post('data');
            $where = [
                't.semester' => $smtAktif,
                'mjp.jenis_kas' => 2,
                'td.id_jenis_pembayaran >=' => 5,
                'td.id_jenis_pembayaran <=' => 6
            ];
            $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $where_DTx = [
                    't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                    'mjp.jenis_kas' => 2,
                    'td.id_jenis_pembayaran >=' => 5,
                    'td.id_jenis_pembayaran <=' => 6
                ];
                $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
            }
            $data['kas_yayasan'] = $dataHistoriTx;
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }


    public function getDataPenerimaanKasSTT()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            // $input = $this->input->post('data');
            $where = [
                't.semester' => $smtAktif,
                'mjp.jenis_kas' => 2,
                'td.id_jenis_pembayaran >=' => 10,
                'td.id_jenis_pembayaran <=' => 19
            ];
            $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $where_DTx = [
                    't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                    'mjp.jenis_kas' => 2,
                    'td.id_jenis_pembayaran >=' => 10,
                    'td.id_jenis_pembayaran <=' => 19
                ];
                $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
            }
            $data['kas_yayasan'] = $dataHistoriTx;
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }

    public function DataTunggakan()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Data Tunggakan';
        $data['content'] = 'laporan/data_tunggakan';
        $data['tunggakan'] = $this->tunggakan->getAllDataTunggakanMhs()->result_array();
        $this->load->view('template', $data);
    }
}