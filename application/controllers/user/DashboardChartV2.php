<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : DashboardChartV2.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : dd/mm/yyyy
 *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
 */
class DashboardChartV2 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_api', 'api');
        $this->load->model('krs/M_krs', 'krs');
    }

    public function index()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Dashboard Chart V2';
        $data['content'] = 'v_dashboard_chart_v2';
        $this->load->view('template', $data);
    }

    public function getDataPembayaran()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            // Ambil data semester aktif
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            $res['data'] = array();

            // Tentukan semester sebelumnya
            $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
            $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
            $smt_befor = ($cek_ganjil_genap == '1') ? ($tahun_smt_befor - 1) . '2' : $tahun_smt_befor . '1';

            // Ambil data KRS mahasiswa
            $data_krs = $this->krs->getDataKrsMhs([
                'kn.id_tahun_ajaran' => $smtAktifRes['id_smt'] - 1,
                'm.no_transkip_nilai' => null
            ])->result_array();

            // Siapkan array untuk hasil pengelompokan
            $grouped_by = [];

            // Proses pengelompokan dan hitung data dispen
            foreach ($data_krs as $mhs) {
                $tahun_masuk = $mhs['tahun_masuk'];
                if (!isset($grouped_by[$tahun_masuk])) {
                    $grouped_by[$tahun_masuk] = [
                        'tahun_masuk' => $tahun_masuk,
                        'jml_mhs' => 0,
                        'data_dispen' => 0,
                        'trx' => 0
                    ];
                }

                // Tambahkan data mahasiswa ke dalam grup
                $grouped_by[$tahun_masuk]['jml_mhs']++;

                // Hitung data dispen per mahasiswa
                if ($data_post['filter'] == '2') {
                    $jenis_dispen = '1';
                }
                if ($data_post['filter'] == '3') {
                    $jenis_dispen = '3';
                }
                if ($data_post['filter'] == '4') {
                    $jenis_dispen = '4';
                }
                $grouped_by[$tahun_masuk]['data_dispen'] += $this->aktivasi->getDataDispenMhs([
                    'd.tahun_akademik' => $smtAktifRes['id_smt'],
                    'm.nipd' => $mhs['nipd'],
                    'd.tg_dispen >' => 0,
                    'd.jenis_dispen' => $jenis_dispen
                ])->num_rows();
            }

            // Hitung data transaksi per tahun masuk
            foreach ($grouped_by as $tahun_masuk => &$data) {
                $data['trx'] = $this->masterdata->getDataPembayaranChart([
                    'm.tahun_masuk' => $tahun_masuk,
                    'td.id_jenis_pembayaran' => $data_post['filter'],
                    't.semester' => $smtAktifRes['id_smt'],
                    't.uang_masuk' => 1,
                ])->num_rows();
            }

            // Memasukkan hasil ke dalam res['data']
            $res['data'] = array_values($grouped_by);
            // Sortir data secara descending berdasarkan tahun_masuk
            usort($res['data'], function ($a, $b) {
                return $b['tahun_masuk'] - $a['tahun_masuk'];
            });

            $res['smt_aktif'] = $smtAktifRes['id_smt'];
            $res['smt_befor'] = $smt_befor;
            $res['tahun_smt_aktif'] = substr($smtAktifRes['id_smt'], 0, 4);
            echo json_encode($res);
        } else {
            show_404();
        }
    }
}