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
    public function __construct()
    {
        parent::__construct();
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
        $smtAktifSimak = $this->api->mGet('TahunAkademikAktif', [
            'query' => []
        ]);
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
        $data['semester_aktif_simak'] = $smtAktifSimak['semester_aktif']['id_smt'];
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
        $result = $this->masterdata->getDataMhs()->result_array();
        $current = count($result);
        $hasilPersen = ($current / $total) * 100;
        $data['current'] = $current;
        echo json_encode($data);
    }


    public function SyncDataMhs()
    {
        // data lokal
        $result = $this->masterdata->getDataMhs()->result_array();
        $jmlMhsLokal = count($result);
        if ($jmlMhsLokal > 0) {
            // insert from simak sesuai data update terakhir
            $DataMhsSimak = $this->api->mGet('MahasiswaForSiskeu', [
                'query' => [
                    'offset' => $jmlMhsLokal
                ]
            ]);
        } else {
            // insert all from simak
            $DataMhsSimak = $this->api->mGet('MahasiswaForSiskeu', [
                'query' => [
                    // 'offset' => $jmlMhsLokal
                ]
            ]);
        }
        $dataInsert = $DataMhsSimak['mhsdata'];
        foreach ($dataInsert as $j => $d) {
            $insert[] = $this->masterdata->insertDataMhs($d);
        }

        if ($insert) {
            $DataMhsLocalNew = count($insert) + $jmlMhsLokal;
            echo json_encode([
                'data' => 'success',
                'count_mhs_local_update' => $DataMhsLocalNew
            ]);
        } else {
            echo json_encode(['data' => 'error']);
        }
    }


    public function SyncTahunAkademik()
    {
        $smtAktifSimak = $this->api->mGet('TahunAkademikAktif', [
            'query' => []
        ]);
        $dataSemesterAktifLokal = $this->masterdata->getSemesterAktif()->row_array();
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
            $insert = $this->masterdata->insertDataTahunAkademik($smtAktifSimak['semester_aktif']);
            if ($insert) {
                echo json_encode([
                    'data' => 'success',
                    'semester_aktif_local_update' => $smtAktifSimak['semester_aktif']['id_smt']
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
                if ($jmlRegSimak > 0) {
                    $dataRegMhsOffset = $this->masterdata->getRegMhs(null, null, $jmlRegSimak)->result_array();
                    for ($i = 0; $i < count($dataRegMhsOffset); $i++) {
                        $RegMhsSimakOffset = $this->api->mPost('RegMhs', [
                            'form_params' => [
                                'ID_Reg'        => $dataRegMhsOffset[$i]['ID_Reg'],
                                'Tahun'         => $dataRegMhsOffset[$i]['Tahun'],
                                "Identitas_ID"  => $dataRegMhsOffset[$i]['Identitas_ID'],
                                "Jurusan_ID"    => $dataRegMhsOffset[$i]['Jurusan_ID'],
                                'NIM'           => $dataRegMhsOffset[$i]['NIM'],
                                'tgl_reg'       => $dataRegMhsOffset[$i]['tgl_reg'],
                                'aktif'         => $dataRegMhsOffset[$i]['aktif'],
                                'keterangan'    => $dataRegMhsOffset[$i]['keterangan'],
                                'aktif_by'      => $dataRegMhsOffset[$i]['aktif_by']
                            ]
                        ]);
                        $insert[] = $RegMhsSimakOffset;
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'tipe' => 'simak',
                            'data' => null
                        ];
                    } else {
                        $countRegMhsSimakNew = count($insert) + $jmlRegSimak;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'simak',
                            'data' => $countRegMhsSimakNew
                        ];
                    }
                } else {
                    // insert All data ke simak
                    for ($i = 0; $i < count($dataRegMhs); $i++) {
                        $RegMhsSimak = $this->api->mPost('RegMhs', [
                            'query' => [
                                'ID_Reg'        => $dataRegMhs[$i]['ID_Reg'],
                                'Tahun'         => $dataRegMhs[$i]['Tahun'],
                                "Identitas_ID"  => $dataRegMhs[$i]['Identitas_ID'],
                                "Jurusan_ID"    => $dataRegMhs[$i]['Jurusan_ID'],
                                'NIM'           => $dataRegMhs[$i]['NIM'],
                                'tgl_reg'       => $dataRegMhs[$i]['tgl_reg'],
                                'aktif'         => $dataRegMhs[$i]['aktif'],
                                'keterangan'    => $dataRegMhs[$i]['keterangan'],
                                'aktif_by'      => $dataRegMhs[$i]['aktif_by']
                            ]
                        ]);
                        $insert[] = $RegMhsSimak;
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'tipe' => 'simak',
                            'data' => null
                        ];
                    } else {
                        $countRegMhsSimakNew = count($insert) + $jmlRegSimak;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'simak',
                            'data' => $countRegMhsSimakNew
                        ];
                    }
                }
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
                            'tipe' => 'lokal',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegMhs = count($insert) + $jmlRegMhsLokal;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'lokal',
                            'data' => $countLocalRegMhs
                        ];
                    }
                } else {
                    // insert semua data dari simak 
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
                            'tipe' => 'lokal',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegMhs = count($insert);
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'lokal',
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
                if ($jmlRegUjianSimak > 0) {
                    $dataRegUjianOffset = $this->masterdata->getRegUjian(null, null, $jmlRegUjianSimak)->result_array();
                    // var_dump($dataRegUjianOffset);
                    // die;
                    for ($i = 0; $i < count($dataRegUjianOffset); $i++) {
                        $RegUjianSimakOffset = $this->api->mPost('RegUjian', [
                            'form_params' => [
                                'id_reg'        => $dataRegUjianOffset[$i]['id_reg'],
                                'tahun'         => $dataRegUjianOffset[$i]['tahun'],
                                'nim'           => $dataRegUjianOffset[$i]['nim'],
                                'tgl_reg'       => $dataRegUjianOffset[$i]['tgl_reg'],
                                'aktif'         => $dataRegUjianOffset[$i]['aktif'],
                                'keterangan'    => $dataRegUjianOffset[$i]['keterangan'],
                                'aktif_by'      => $dataRegUjianOffset[$i]['aktif_by']
                            ]
                        ]);
                        $insert[] = $RegUjianSimakOffset;
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'tipe' => 'simak',
                            'data' => null
                        ];
                    } else {
                        $countRegUjianSimakNew = count($insert) + $jmlRegUjianSimak;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'simak',
                            'data' => $countRegUjianSimakNew
                        ];
                    }
                } else {
                    // insert All data ke simak
                    for ($i = 0; $i < count($dataRegUjian); $i++) {
                        $RegUjianSimak = $this->api->mPost('RegUjian', [
                            'query' => [
                                'id_reg'        => $dataRegUjian[$i]['id_reg'],
                                'tahun'         => $dataRegUjian[$i]['tahun'],
                                'nim'           => $dataRegUjian[$i]['nim'],
                                'tgl_reg'       => $dataRegUjian[$i]['tgl_reg'],
                                'aktif'         => $dataRegUjian[$i]['aktif'],
                                'keterangan'    => $dataRegUjian[$i]['keterangan'],
                                'aktif_by'      => $dataRegUjian[$i]['aktif_by']
                            ]
                        ]);
                        $insert[] = $RegUjianSimak;
                    }
                    if (!$insert) {
                        $data = [
                            'status' => 500,
                            'msg' => 'gagal insert',
                            'tipe' => 'simak',
                            'data' => null
                        ];
                    } else {
                        $countRegUjianSimakNew = count($insert) + $jmlRegUjianSimak;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'simak',
                            'data' => $countRegUjianSimakNew
                        ];
                    }
                }
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
                            'tipe' => 'lokal',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegUjian = count($insert) + $jmlRegUjianLokal;
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'lokal',
                            'data' => $countLocalRegUjian
                        ];
                    }
                } else {
                    // insert semua data dari simak 
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
                            'tipe' => 'lokal',
                            'data' => null
                        ];
                    } else {
                        $countLocalRegUjian = count($insert);
                        $data = [
                            'status' => 200,
                            'msg' => 'berhasil',
                            'tipe' => 'lokal',
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
