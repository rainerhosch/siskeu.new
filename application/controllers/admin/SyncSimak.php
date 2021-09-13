<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name       : SyncSimak.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                  : Rizky Ardiansyah
 *  Date Created            : 19/06/2021
 *  Quots of the code       : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */
class SyncSimak extends CI_Controller
{
    private $getMhsFromApiSimak;
    private $getRegMhs;
    private $getRegUjian;
    public function __construct()
    {
        parent::__construct();
        $token = 'semogabahagia';
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_api', 'api');
        // $this->getMhsFromApiSimak = reqData('MahasiswaForSiskeu');
    }

    public function index()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Sync Data Simak';
        $data['content'] = 'v_sync_data';
        // ===============================
        $this->load->view('template', $data);
    }
    public function getCountData()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        $counApiDataMhs = $this->api->mGet('MahasiswaForSiskeu', [
            'query' => [
                'type' => 'get_count'
            ]
        ]);
        $countRegMhsSimak = $this->api->mGet('RegMhs', [
            'query' => [
                'type' => 'get_count',
                // 'thn_akademik' => $smtAktif,
            ]
        ]);
        $countRegUjianSimak = $this->api->mGet('RegUjian', [
            'query' => [
                'type' => 'get_count',
                // 'thn_akademik' => $smtAktif,
            ]
        ]);
        $data['count_mhs_simak'] = $counApiDataMhs['mhsdata'];
        $data['semester_aktif_simak'] = $smtAktif;
        $data['reg_mhs_simak'] = $countRegMhsSimak['reg_mhs'];
        $data['reg_ujian_simak'] = $countRegUjianSimak['reg_ujian'];
        // ======================= Lokal ====================================
        $LocalDataMhs = $this->masterdata->getDataMhs()->num_rows();
        $dataRegMhs = $this->masterdata->getRegMhs()->num_rows();
        $dataRegUjian = $this->masterdata->getRegUjian()->num_rows();
        $data['semester_aktif_local'] = $smtAktif;
        $data['count_mhs_local'] = $LocalDataMhs;
        $data['reg_mhs_local'] = $dataRegMhs;
        $data['reg_ujian_local'] = $dataRegUjian;
        // ===========================================================
        echo json_encode($data);
    }

    public function CountRowDataMhsLocal()
    {
        $dataRes = $this->getMhsFromApiSimak;
        $dataMhs = $dataRes['mhsdata'];
        $total = count($dataMhs);
        // for ($i = 0; $i < $total; $i++) {
        $result = $this->masterdata->getDataMhs()->result_array();
        $current = count($result);
        $hasilPersen = ($current / $total) * 100;
        // $data['valuenow'] = round($hasilPersen, 2);
        $data['current'] = $current;
        echo json_encode($data);
        // }
    }
    public function SyncDataMhs()
    {
        // get data from simak
        $dataRes = $this->getMhsFromApiSimak;
        $dataMhs = $dataRes['mhsdata'];
        $jml_data = count($dataMhs);

        // data lokal
        $result = $this->masterdata->getDataMhs()->result_array();
        $current = count($result);
        if ($current != 0) {
            // insert all from simak sesuai data update terakhir
            $res = $this->api->getGetDataTerbaru($current);
            $dataInsert = $res['mhsdata'];
        } else {
            // insert all from simak
            $dataInsert = $dataMhs;
        }
        foreach ($dataInsert as $j => $d) {
            $insert = $this->masterdata->insertDataMhs($d);
        }

        if ($insert) {
            $LocalDataMhs = $this->masterdata->getDataMhs()->result_array();
            $countLocalDataMhs = count($LocalDataMhs);
            echo json_encode([
                'data' => 'success',
                'count_mhs_local_update' => $countLocalDataMhs
            ]);
        } else {
            echo json_encode(['data' => 'error']);
        }
    }


    public function SyncTahunAkademik()
    {
        $smtAktif = $this->smt_aktif;
        $dataSemesterAktifLokal = $this->masterdata->getMaxKalenderAkademik()->row_array();
        $id_smt = $dataSemesterAktifLokal['id_smt'];
        $data = [
            'a_periode_aktif' => 0
        ];
        $hapusStatusAktifLocal = $this->masterdata->updateStatusAktif($id_smt, $data);
        if (!$hapusStatusAktifLocal) {
            echo json_encode([
                'data' => 'error',
                'msg' => 'gagal ubah status aktif'
            ]);
        } else {
            // var_dump($insert);
            // die;
            $insert = $this->masterdata->insertDataTahunAkademik($smtAktif);
            if ($insert) {
                echo json_encode([
                    'data' => 'success',
                    'semester_aktif_local_update' => $smtAktif['id_smt']
                ]);
            } else {
                echo json_encode([
                    'data' => 'error',
                    'msg' => 'gagal insert'
                ]);
            }
        }
    }


    public function SyncRegMhs()
    {
        // code here...
        if ($this->input->is_ajax_request()) {

            $countRegMhsSimak = $this->api->mGet('RegMhs', [
                'query' => [
                    'type' => 'get_count',
                ]
            ]);
            $jmlRegSimak = $countRegMhsSimak['reg_mhs'];
            // ========= Data lokal =============
            $table = 'reg_mhs';
            $dataRegMhs = $this->masterdata->getRegMhs()->result_array();
            $jmlRegMhsLokal = count($dataRegMhs);

            if ($jmlRegSimak < $jmlRegMhsLokal) {
                // inser data dari lokal ke simak
            } elseif ($jmlRegSimak > $jmlRegMhsLokal) {
                // insert simak ke lokal
                if ($jmlRegMhsLokal > 0) {
                    $dataRegMhsSimakOffset = $this->api->mGet('RegMhs', [
                        'query' => [
                            'offset' =>  $jmlRegMhsLokal
                        ]
                    ]);
                    $dataRegMhsSimakOffset = $dataRegMhsSimakOffset['reg_mhs'];
                    foreach ($dataRegMhsSimakOffset as $i => $val) {
                        $insert[] = $this->masterdata->insertData($table, $val);
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegMhs = count($insert) + $jmlRegMhsLokal;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'data' => $countLocalRegMhs
                        ];
                    }
                } else {
                    // insert semua data dari simak berdasarkan tahun akademik aktif
                    $dataRegMhsSimak = $this->api->mGet('RegMhs', [
                        'query' => [
                            // 'type' => 'get_count',
                        ]
                    ]);
                    $dataRegMhsSimakAll = $dataRegMhsSimak['reg_mhs'];
                    foreach ($dataRegMhsSimakAll as $i => $val) {
                        $insert[] = $this->masterdata->insertData($table, $val);
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegMhs = count($insert);
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'data' => $countLocalRegMhs
                        ];
                    }
                }
            }
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }

    public function SyncRegUjian()
    {
        // code here...
        if ($this->input->is_ajax_request()) {
            $countRegUjianSimak = $this->api->mGet('RegUjian', [
                'query' => [
                    'type' => 'get_count',
                ]
            ]);
            $jmlRegUjianSimak = $countRegUjianSimak['reg_ujian'];

            // ========= Data lokal =============
            $table = 'reg_ujian';
            $dataRegUjian = $this->masterdata->getRegUjian()->result_array();
            $jmlRegUjianLokal = count($dataRegUjian);

            if ($jmlRegUjianSimak < $jmlRegUjianLokal) {
                // inser data dari lokal ke simak
            } elseif ($jmlRegUjianSimak > $jmlRegUjianLokal) {
                // inser data dari simak ke lokal
                if ($jmlRegUjianLokal > 0) {
                    $RegUjianSimakOffset = $this->api->mGet('RegUjian', [
                        'query' => [
                            'offset' =>  $jmlRegUjianLokal
                        ]
                    ]);
                    $dataRegUjianSimakOffset = $RegUjianSimakOffset['reg_ujian'];
                    foreach ($dataRegUjianSimakOffset as $i => $val) {
                        $insert[] = $this->masterdata->insertData($table, $val);
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegUjian = count($insert) + $jmlRegUjianLokal;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'data' => $countLocalRegUjian
                        ];
                    }
                } else {
                    // insert semua data dari simak berdasarkan tahun akademik aktif
                    $dataRegUjianSimak = $this->api->mGet('RegUjian', [
                        'query' => [
                            // 'type' => 'get_count',
                        ]
                    ]);
                    $dataRegUjianSimakAll = $dataRegUjianSimak['reg_ujian'];
                    foreach ($dataRegUjianSimakAll as $i => $val) {
                        $insert[] = $this->masterdata->insertData($table, $val);
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegUjian = count($insert);
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'data' => $countLocalRegUjian
                        ];
                    }
                }
            }
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }
}
