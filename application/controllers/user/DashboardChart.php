<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *  File Name       : DashboardChart.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 *  
 *  Date Created 16 Desember 2020
 *  Author @Rizky Ardiansyah
 */

class DashboardChart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        $this->load->model('M_menu', 'menu');
        $this->load->model('M_user', 'user');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_api', 'api');
    }
    public function index()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Dashboard Chart';
        $data['content'] = 'v_dashboard_2';
        $data['jml_tunggakan'] = $this->tunggakan->getTunggakanMhs()->num_rows();
        $response = $this->tunggakan->getTunggakanMhs()->result_array();
        foreach ($response as $tg) {
            $total_tg[] = $tg['jml_tunggakan'];
        }
        if (count($response) > 0) {
            $data['total_tg'] = array_sum($total_tg);
        } else {
            $data['total_tg'] = 0;
        }

        $this->load->view('template', $data);
        // $this->load->view('app');
    }

    public function getDataPembayaran()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            $res['data'] = $this->masterdata->getDataAngkatan()->result_array();
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            $list_simak = $this->aktivasi->cekStatusKelulusanMhs()->result_array();
            $cek_krs = $this->aktivasi->cekKrsMhsSimak()->result_array();


            $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
            $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
            $smt_befor = '';
            if ($cek_ganjil_genap == '1') {
                $smt_befor = ($tahun_smt_befor - 1) . '2';
            } else {
                $smt_befor = ($tahun_smt_befor - 1) . '1';
            }

            // $cek_krs_befor = $this->getDataKrs();
            $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
            $index = 0;
            foreach ($res['data'] as $i => $val) {
                if ($data_post['filter'] != '0') {
                    if ($data_post['filter'] == '2') {
                        $jenis_dispen = '1';
                    }
                    if ($data_post['filter'] == '3') {
                        $jenis_dispen = '3';
                    }
                    if ($data_post['filter'] == '4') {
                        $jenis_dispen = '4';
                    }
                    $res['data'][$i]['jenis_dispen'] = $jenis_dispen;
                    $param_dispen = [
                        'd.tahun_akademik' => $smtAktifRes['id_smt'],
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'd.tg_dispen >' => 0,
                        'd.jenis_dispen' => $jenis_dispen
                    ];
                    $param_tx = [
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'td.id_jenis_pembayaran' => $data_post['filter'],
                        't.semester' => $smtAktifRes['id_smt']
                    ];
                } else {
                    $res['data'][$i]['jenis_dispen'] = 'all';
                    $param_dispen = [
                        'd.tahun_akademik' => $smtAktifRes['id_smt'],
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'd.tg_dispen >' => 0
                    ];

                    $param_tx = [
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'td.id_jenis_pembayaran <' => 5,
                        't.semester' => $smtAktifRes['id_smt']
                    ];
                }

                $res['data'][$i]['jml_mhs'] = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->num_rows();
                $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->result_array();
                $res['data'][$i]['list_mhs'] = $list_mhs;

                foreach ($list_mhs as $l => $mhs) {
                    $res['data'][$i]['list_mhs'][$l]['krs_befor'] = null;
                    $res['data'][$i]['list_mhs'][$l]['krs'] = null;
                    foreach ($list_simak as $ls => $simak) {
                        if ($list_simak[$ls]['pin'] == '') {
                            $list_simak[$ls]['pin'] = null;
                        }
                        if ($list_simak[$ls]['judul_skripsi'] == '') {
                            $list_simak[$ls]['judul_skripsi'] = null;
                        }
                        if ($list_simak[$ls]['no_seri_ijazah'] == '') {
                            $list_simak[$ls]['no_seri_ijazah'] = null;
                        }
                        if ($list_simak[$ls]['no_transkip_nilai'] == '') {
                            $list_simak[$ls]['no_transkip_nilai'] = null;
                        }
                        if ($simak['id_pd'] == $mhs['id_pd']) {
                            // $res['data'][$i]['list_mhs'][$l]['data_mhs_pt'] = $list_simak[$ls];
                            $res['data'][$i]['list_mhs'][$l]['pin'] = $list_simak[$ls]['pin'];
                            $res['data'][$i]['list_mhs'][$l]['judul_skripsi'] = $list_simak[$ls]['judul_skripsi'];
                            $res['data'][$i]['list_mhs'][$l]['no_seri_ijazah'] = $list_simak[$ls]['no_seri_ijazah'];
                            $res['data'][$i]['list_mhs'][$l]['no_transkip_nilai'] = $list_simak[$ls]['no_transkip_nilai'];
                        }
                    }

                    foreach ($cek_krs as $c => $krs) {
                        if ($krs['nipd'] == $mhs['nipd']) {
                            $res['data'][$i]['list_mhs'][$l]['krs'] = $cek_krs[$c];
                            // $res['data'][$i]['list_mhs'][$l]['pernah_krs'] = 1;
                        }
                    }
                    foreach ($cek_krs_befor as $kb => $ckb) {
                        if ($ckb['nipd'] == $mhs['nipd']) {
                            $res['data'][$i]['list_mhs'][$l]['krs_befor'] = $cek_krs_befor[$kb];
                        }
                    }


                    $res['data'][$i]['list_mhs'][$l]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0])->num_rows();

                }
                // foreach ($list_mhs as $m => $mhs) {
                //     $res['data'][$i]['list_mhs'][$m]['simak_data'] = $this->aktivasi->cekStatusKelulusanMhs(['id_pd' => $mhs['id_pd']])->row_array();
                // }
                // if ($data_post['filter'] != '0') {

                //     if ($data_post['filter'] == '2') {
                //         $jenis_dispen = '1';
                //     }
                //     if ($data_post['filter'] == '3') {
                //         $jenis_dispen = '3';
                //     }
                //     if ($data_post['filter'] == '4') {
                //         $jenis_dispen = '4';
                //     }
                //     $res['data'][$i]['jenis_dispen'] = $jenis_dispen;
                $res['data'][$i]['data_dispen'] = $this->aktivasi->getDataDispenMhs($param_dispen)->num_rows();
                // $where1 = [
                //     'm.tahun_masuk' => $val['tahun_masuk'],
                //     'td.id_jenis_pembayaran' => $data_post['filter'],
                //     't.semester' => $smtAktifRes['id_smt']
                // ];
                $res['data'][$i]['data_trx'] = $this->masterdata->getDataPembayaranChart($param_tx)->result_array();
                $res['data'][$i]['last_query'] = $this->db->last_query();
                // } else {
                // $res['data'][$i]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.tahun_masuk' => $val['tahun_masuk'], 'd.tg_dispen >' => 0])->num_rows();
                // $where1 = [
                //     'm.tahun_masuk' => $val['tahun_masuk'],
                //     'td.id_jenis_pembayaran <' => 5,
                //     't.semester' => $smtAktifRes['id_smt']
                // ];

                // $res['data'][$i]['data_trx'] = $this->masterdata->getDataPembayaranChart($where1)->result_array();

                // }
                $res['data'][$i]['trx'] = $this->masterdata->getDataPembayaranChart($param_tx)->num_rows();
            }
            $res['smt_aktif'] = $smtAktifRes['id_smt'];
            $res['smt_befor'] = $smt_befor;
            echo json_encode($res);
        } else {
            show_404();
        }
    }

    public function getDataPembayaranV2()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            $res['data'] = $this->masterdata->getDataAngkatan()->result_array();
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            // $list_simak = $this->aktivasi->cekStatusKelulusanMhs()->result_array();
            // $cek_krs = $this->aktivasi->cekKrsMhsSimak()->result_array();


            $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
            $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
            $smt_befor = '';
            if ($cek_ganjil_genap == '1') {
                $smt_befor = ($tahun_smt_befor - 1) . '2';
            } else {
                $smt_befor = ($tahun_smt_befor - 1) . '1';
            }

            foreach ($res['data'] as $i => $val) {
                $res['data'][$i]['jml_mhs'] = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->num_rows();
                $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->result_array();
                $res['data'][$i]['list_mhs'] = $list_mhs;

                foreach ($list_mhs as $l => $mhs) {
                    // $res['data'][$i]['list_mhs'][$l]['pin'] = null;
                    // $res['data'][$i]['list_mhs'][$l]['judul_skripsi'] = null;
                    // $res['data'][$i]['list_mhs'][$l]['no_seri_ijazah'] = null;
                    // $res['data'][$i]['list_mhs'][$l]['no_transkip_nilai'] = null;
                    // ===========================================================

                    $res['data'][$i]['list_mhs'][$l]['krs_befor'] = null;
                    $res['data'][$i]['list_mhs'][$l]['krs'] = null;
                    $cek_krs = $this->aktivasi->cekKrsMhsLokal(['nipd' => $mhs['nipd']])->row_array();
                    $cek_krs_befor = $this->aktivasi->cekKrsMhsLokal(['nipd' => $mhs['nipd'], 'id_tahun_ajaran' => $smt_befor])->row_array();
                    $res['data'][$i]['list_mhs'][$l]['krs'] = $cek_krs;
                    $res['data'][$i]['list_mhs'][$l]['krs_befor'] = $cek_krs_befor;
                    // $res['data'][$i]['list_mhs'][$l]['query'] = $this->db->last_query();

                    if ($data_post['filter'] != '0') {

                        if ($data_post['filter'] == '2') {
                            $jenis_dispen = '1';
                        }
                        if ($data_post['filter'] == '3') {
                            $jenis_dispen = '3';
                        }
                        if ($data_post['filter'] == '4') {
                            $jenis_dispen = '4';
                        }
                        $res['data'][$i]['jenis_dispen'] = $jenis_dispen;
                        $res['data'][$i]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.tahun_masuk' => $val['tahun_masuk'], 'd.tg_dispen >' => 0, 'd.jenis_dispen' => $jenis_dispen])->num_rows();
                        $where1 = [
                            'm.tahun_masuk' => $val['tahun_masuk'],
                            'td.id_jenis_pembayaran' => $data_post['filter'],
                            't.semester' => $smtAktifRes['id_smt']
                        ];
                        $res['data'][$i]['data_trx'] = $this->masterdata->getDataPembayaranChart($where1)->result_array();
                        $res['data'][$i]['last_query'] = $this->db->last_query();
                    } else {
                        $res['data'][$i]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.tahun_masuk' => $val['tahun_masuk'], 'd.tg_dispen >' => 0])->num_rows();
                        $where1 = [
                            'm.tahun_masuk' => $val['tahun_masuk'],
                            'td.id_jenis_pembayaran <' => 5,
                            't.semester' => $smtAktifRes['id_smt']
                        ];

                        $res['data'][$i]['data_trx'] = $this->masterdata->getDataPembayaranChart($where1)->result_array();

                    }
                    $res['data'][$i]['trx'] = $this->masterdata->getDataPembayaranChart($where1)->num_rows();

                }
            }
            echo json_encode($res);
        } else {
            show_404();
        }
    }

    // public function updateDataMhs()
    // {
    //     $dataUpdate = [];
    //     $list_simak = $this->aktivasi->cekStatusKelulusanMhs()->result_array();

    //     $jml_mhs_lokal = $this->masterdata->getDataListMhs()->num_rows();
    //     for ($i = 0; $i < $jml_mhs_lokal; $i++) {
    //         $id = $list_simak[$i]['id_reg_pd'];
    //         $data = [
    //             'no_transkip_nilai' => $list_simak[$i]['no_transkip_nilai']
    //         ];
    //         $dataUpdate[$i] = $this->masterdata->updateDataMhs($id, $data);
    //     }
    //     // echo '<pre>';
    //     // var_dump($dataUpdate);
    //     // echo '</pre>';
    //     // die;
    // }
}