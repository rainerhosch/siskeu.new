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
        date_default_timezone_set('Asia/Jakarta');
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
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
            $res = $this->getDataProgressPembayaranSPP($data_post);
            echo json_encode($res);
        } else {
            show_404();
        }
    }

    private function getDataProgressPembayaranSPP($data_post)
    {
        // Ambil data semester aktif
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        // $smtAktifRes['id_smt'] = '20222';
        $res['data'] = array();

        // Tentukan semester sebelumnya
        $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
        $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
        $smt_befor = ($cek_ganjil_genap == '1') ? ($tahun_smt_befor - 1) . '2' : $tahun_smt_befor . '1';

        // var_dump($tahun_smt_befor);
        // var_dump($data_post['filter']);
        // var_dump($smtAktifRes['id_smt']);
        // var_dump(date('Y').'1' == $smtAktifRes['id_smt']);
        // die;

        // Ambil data KRS mahasiswa
        if ($data_post['filter'] == '2') {
            $filter_smt = $smt_befor;
        } else {
            $filter_smt = $smtAktifRes['id_smt'];
        }


        // Ambil data base KRS sebelumnya jika pembayaran Cicilan 1 -> done 
        // Ambil data base KRS aktif jika pembayaran Cicilan 2 -> done
        // Ambil data base UTS jika pembayarn cicilan 3 -> belum
        if ($data_post['filter'] == '4') {
            $data_reg_ujian = $this->aktivasi->getRegUjian([
                'tahun'=>$filter_smt,
                'aktif'=> '1'
            ])->result_array();
            $data_krs = [];
            $no = 0;
            // echo '<pre>';
            // var_dump($this->db->last_query());
            // // var_dump($data_krs);
            // echo '</pre>';
            // die;

            foreach($data_reg_ujian as $i => $val){
                // $data_get = $this->krs->getDataKrsMhs([
                //     'kn.id_tahun_ajaran' => $filter_smt,
                //     'm.no_transkip_nilai' => null,
                //     'm.tgl_sk_yudisium' => null,
                //     'm.tahun_masuk >' => '2016',
                //     'm.nipd' => $val['nim']
                // ])->row_array();
                $data_get = $this->masterdata->getMahasiswaByNim([
                    'nipd' => $val['nim'],
                    'tahun_masuk >' => '2016',
                    ])->row_array();
                if($data_get != null){
                    $data_krs[$no] = $data_get;
                    $no++;
                }
            }
        }else{
            $data_krs = $this->krs->getDataKrsMhs([
                'kn.id_tahun_ajaran' => $filter_smt,
                'm.no_transkip_nilai' => null,
                'm.tgl_sk_yudisium' => null,
                'm.tahun_masuk >' => '2016'
            ])->result_array();
        }
        // echo '<pre>';
        // var_dump($this->db->last_query());
        // var_dump($data_krs);
        // echo '</pre>';
        // die;
        if ($data_post['filter'] == '2') {
            if (date('Y') . '1' == $smtAktifRes['id_smt']) {
                $data_new = $this->masterdata->getDataMhs(['tahun_masuk' => date('Y')])->result_array();
                $data_krs = array_merge($data_krs, $data_new);
            }
        }

        // Siapkan array untuk hasil pengelompokan
        $grouped_by = [];

        // Proses pengelompokan dan hitung data dispen
        foreach ($data_krs as $mhs) {
            $tahun_masuk = $mhs['tahun_masuk'];
            if (!isset($grouped_by[$tahun_masuk])) {
                $grouped_by[$tahun_masuk] = [
                    'tahun_masuk' => $tahun_masuk,
                    'list_mhs' => [],
                    'jml_mhs' => 0,
                    'data_dispen' => 0,
                    'trx' => 0
                ];
            }

            $data_kelas = $this->masterdata->getDataKelas(['id_kelas' => $mhs['id_kelas']])->row_array();
            $mhs['nama_kelas'] = $data_kelas['nama_kelas'];



            $grouped_by[$tahun_masuk]['list_mhs'][] = $mhs;

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
            $grouped_by[$tahun_masuk]['data_dispen'] += $this->aktivasi->getDataDispenMhsV2([
                'd.tahun_akademik' => $smtAktifRes['id_smt'],
                'm.nipd' => $mhs['nipd'],
                'd.tg_dispen >' => 0,
                'd.status' => 0,
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
        return $res;

    }

    public function getDataPembayaranYear()
    {
        if ($this->input->is_ajax_request()) {
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            // $smtAktifRes['id_smt'] = '20222';
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
                        'list_mhs' => [],
                        'jml_mhs' => 0,
                        'data_dispen' => 0,
                        'trx' => 0
                    ];
                }

                $grouped_by[$tahun_masuk]['list_mhs'][] = $mhs;

                // Tambahkan data mahasiswa ke dalam grup
                $grouped_by[$tahun_masuk]['jml_mhs']++;

                // Hitung data dispen per mahasiswa
                // if ($data_post['filter'] == '2') {
                //     $jenis_dispen = '1';
                // }
                // if ($data_post['filter'] == '3') {
                //     $jenis_dispen = '3';
                // }
                // if ($data_post['filter'] == '4') {
                //     $jenis_dispen = '4';
                // }
                // $grouped_by[$tahun_masuk]['data_dispen'] += $this->aktivasi->getDataDispenMhsV2([
                //     'd.tahun_akademik' => $smtAktifRes['id_smt'],
                //     'm.nipd' => $mhs['nipd'],
                //     'd.tg_dispen >' => 0,
                //     'd.status' => 0,
                //     // 'd.jenis_dispen' => $jenis_dispen
                // ])->num_rows();
            }

            // Hitung data transaksi per tahun masuk
            foreach ($grouped_by as $tahun_masuk => &$data) {
                $data['trx'] = $this->masterdata->getDataPembayaranChart([
                    'm.tahun_masuk' => $tahun_masuk,
                    'td.id_jenis_pembayaran <' => 5,
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

    public function getDataPembayaranChart()
    {
        if ($this->input->is_ajax_request()) {
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            // Tentukan semester sebelumnya
            $tahun_smt = substr($smtAktifRes['id_smt'], 0, 4);
            $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
            $smt_befor = ($cek_ganjil_genap == '1') ? ($tahun_smt - 1) . '2' : $tahun_smt . '1';

            $end_smt = [];
            for ($i = 0; $i < 3; $i++) {
                $end_smt = ($tahun_smt * 1) . 1;
                $tahun_smt--;
            }

            $dataSmt = $this->masterdata->getDataSemester(['id_smt >=' => $end_smt, 'id_smt <=' => $smtAktifRes['id_smt']])->result_array();
            // $res['data_smt'] = $dataSmt;

            $dataChart = [];
            foreach ($dataSmt as $i => $val) {
                $res['trx']['C1'][$val['id_smt']] = $this->masterdata->getDataPembayaranChart([
                    'td.id_jenis_pembayaran' => 2,
                    't.semester' => $val['id_smt'],
                    't.uang_masuk' => 1,
                ])->num_rows();
                $res['trx']['C2'][$val['id_smt']] = $this->masterdata->getDataPembayaranChart([
                    'td.id_jenis_pembayaran' => 3,
                    't.semester' => $val['id_smt'],
                    't.uang_masuk' => 1,
                ])->num_rows();
                $res['trx']['C3'][$val['id_smt']] = $this->masterdata->getDataPembayaranChart([
                    'td.id_jenis_pembayaran' => 4,
                    't.semester' => $val['id_smt'],
                    't.uang_masuk' => 1,
                ])->num_rows();



                // $where = [
                //     't.tahun' => $val['id_smt'],
                // ];
                // $res['trx'][$i][$val['id_smt']]['C1']=$this->aktivasi->cekStatusAktifMhs($where, 'reg_mhs t')->num_rows();
                // $res['trx'][$i][$val['id_smt']]['C2']=$this->aktivasi->cekStatusAktifMhs($where, 'reg_ujian t', ('1, 3'))->num_rows();
                // $res['trx'][$i][$val['id_smt']]['C3']=$this->aktivasi->cekStatusAktifMhs($where, 'reg_ujian t', ('2, 4'))->num_rows();
                // $res['last_query']=$this->db->last_query();
                $res['dataChart']['labels'][$i] = $val['id_smt'];
            }
            foreach ($res['trx'] as $key => $value) {
                $i = 0;
                foreach ($value as $k => $v) {
                    $dataChart[$key][$i] = $v;
                    $i++;
                }
            }
            $res['dataChart']['datasets'] = $dataChart;
            $res['smt_aktif'] = $smtAktifRes['id_smt'];
            $res['tahun_smt_aktif'] = substr($smtAktifRes['id_smt'], 0, 4);
            echo json_encode($res);
        } else {
            show_404();
        }
    }
}