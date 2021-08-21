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
    private $smt_aktif;
    private $getMhsFromApiSimak;
    private $getRegMhs;
    private $getRegUjian;
    public function __construct()
    {
        parent::__construct();
        $token = 'semogabahagia';
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_api', 'api');
        $this->smt_aktif = getSemesterAktif($token);
        $this->getMhsFromApiSimak = getDataMahasiswa($token);
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
        $token = 'semogabahagia';
        $smtAktif = $this->smt_aktif['id_smt'];
        $where_tahun = 'thn_akademik=' . $smtAktif;
        $dataRegMhs = getRegMhs($token, $where_tahun);
        $dataRegUjian = getRegUjian($token, $where_tahun);
        $dataRes = $this->getMhsFromApiSimak;
        $counApiDataMhs = count($dataRes['mhsdata']);
        $countRegMhsSimak = count($dataRegMhs['regmhs']);
        $countRegUjianSimak = count($dataRegUjian['reg_ujian']);
        $data['count_mhs_simak'] = $counApiDataMhs;
        $data['semester_aktif_simak'] = $smtAktif;
        $data['reg_mhs_simak'] = $countRegMhsSimak;
        $data['reg_ujian_simak'] = $countRegUjianSimak;
        // ===========================================================
        $dataSemesterAktifLokal = $this->masterdata->getMaxKalenderAkademik()->row_array();
        $data['semester_aktif_local'] = $dataSemesterAktifLokal['id_smt'];
        $LocalDataMhs = $this->masterdata->getDataMhs()->result_array();
        $countLocalDataMhs = count($LocalDataMhs);
        $data['count_mhs_local'] = $countLocalDataMhs;

        $dataRegMhs = $this->masterdata->getRegMhs(['Tahun=' => $smtAktif])->num_rows();
        $dataRegUjian = $this->masterdata->getRegUjian(['tahun=' => $smtAktif])->num_rows();
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
            $table = 'reg_mhs';
            $token = 'semogabahagia';
            $smtAktif = $this->smt_aktif['id_smt'];
            $where_tahun = 'thn_akademik=' . $smtAktif;
            $response = getRegMhs($token, $where_tahun);
            $dataRegMhsSimak = $response['regmhs'];

            // ========= Data lokal =============
            $dataRegUjian = $this->masterdata->getRegUjian(['tahun=' => $smtAktif])->result_array();
            $jmlRegMhsLokal = count($dataRegUjian);

            if (count($dataRegMhsSimak) < $jmlRegMhsLokal) {
            } elseif (count($dataRegMhsSimak) > $jmlRegMhsLokal) {
                // insert data ke lokal
                if ($jmlRegUjianLokal > 0) {
                } else {
                    foreach ($dataRegMhsSimak as $i => $val) {
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
            $table = 'reg_ujian';
            $token = 'semogabahagia';
            $smtAktif = $this->smt_aktif['id_smt'];
            $where_tahun = 'thn_akademik=' . $smtAktif;
            $response = getRegUjian($token, $where_tahun);
            $dataRegUjianSimak = $response['reg_ujian'];

            // ========= Data lokal =============
            $dataRegUjian = $this->masterdata->getRegUjian(['tahun=' => $smtAktif])->result_array();
            $jmlRegUjianLokal = count($dataRegUjian);

            if (count($dataRegUjianSimak) < $jmlRegUjianLokal) {
            } elseif (count($dataRegUjianSimak) > $jmlRegUjianLokal) {
                // inser data dari simak ke lokal
                if ($jmlRegUjianLokal > 0) {
                } else {
                    foreach ($dataRegUjianSimak as $i => $val) {
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
