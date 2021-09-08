<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : AktivasiMhs.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 07/09/2021
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */
class AktivasiMhs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_user', 'user');
    }

    public function index()
    {
        // code here...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Aktivasi Mahasiswa';
        $data['content'] = 'admin/v_aktivasi_mhs';

        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $data['tahun_akademik'] = $smtAktifRes['id_smt'];
        // ===============================
        $this->load->view('template', $data);
    }

    public function cari_mhs()
    {
        if ($this->input->is_ajax_request()) {
            $nipd = $this->input->post('nipd');
            $jenis_dispen = $this->input->post('jenis_dispen');
            $tahun_akademik = $this->input->post('tahun_akademik');
            $data = $this->masterdata->getMahasiswaByNim(['nipd' => $nipd])->row_array();
            if ($data != null) {
                $response = [
                    'status' => 200,
                    'msg'   => 'data ditemukan',
                    'data' => $data
                ];
            } else {
                $response = [
                    'status' => 203,
                    'msg'   => 'tidak ada mahasiswa dengan nim tersebut!',
                    'data' => $data
                ];
            }
        } else {
            $response = 'Invalid Request!';
        }
        echo json_encode($response);
    }

    public function aktif_manual()
    {
        if ($this->input->is_ajax_request()) {
            $dateNow = date('Y-m-d H:i:s');
            $pecah_tgl_waktu = explode(' ', $dateNow);
            $tgl = $pecah_tgl_waktu[0];
            $id_user = $this->session->userdata('id_user');


            // data input
            $dataInput = $this->input->post();
            $nipd = $dataInput['nipd'];
            $smt = $dataInput['tahun_akademik'];
            $jns_dispen = $dataInput['jenis_dispen'];
            // var_dump($this->input->post());
            // die;


            if ($jns_dispen = '1') {
                // disepen perwalian
                $dataAktifDispenKrs = [
                    'Tahun' => $smt,
                    'Identitas_ID' => '',
                    'Jurusan_ID' => '',
                    'NIM' => $nipd,
                    'tgl_reg' => $tgl,
                    'aktif' => $jns_dispen,
                    'keterangan' => 'from siskeu_new',
                    'aktif_by' => $id_user
                ];
                $active = $this->aktivasi->aktivasi_perwalian($dataAktifDispenKrs);
                if (!$active) {
                    // error
                    // $data = $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal Aktivasi!</div>');
                    $reponse = $active;
                } else {
                    // success
                    // $data = $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil Aktivasi Mahasiswa!</div>');
                    $reponse = $active;
                }
                // redirect('aktivasi-mahasiswa', $data);
            } else {
                // dispen UTS or UAS
                $dataAktifDispenUjian = [
                    'tahun' => $smt,
                    'nim' => $nipd,
                    'tgl_reg' => $tgl,
                    'aktif' => $jns_dispen,
                    'keterangan' => 'from siskeu_new',
                    'aktif_by' => $id_user
                ];
                $active = $this->aktivasi->aktivasi_ujian($dataAktifDispenUjian);
                if (!$active) {
                    // error
                    // $data = $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal Aktivasi!</div>');
                    $reponse = $active;
                } else {
                    // success
                    // $reponse = $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil Aktivasi Mahasiswa!</div>');
                    $response = $active;
                }
                // redirect('aktivasi-mahasiswa', $data);
            }
        } else {
            $reponse = 'invalid request';
        }
        echo json_encode($reponse);
    }
}
