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
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_masterdata', 'masterdata');
        $this->smt_aktif = getSemesterAktif();
        $this->getMhsFromApiSimak = getDataMahasiswa();
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
        $smtAktif = $this->smt_aktif['id_smt'];
        $dataRes = $this->getMhsFromApiSimak;
        $ApiDataMhs =  $dataRes['mhsdata'];
        $counApiDataMhs = count($ApiDataMhs);
        $data['count_mhs_simak'] = $counApiDataMhs;
        $data['semester_aktif_simak'] = $smtAktif;
        // ===========================================================
        $dataSemesterAktifLokal = $this->masterdata->getMaxKalenderAkademik()->row_array();
        $data['semester_aktif_local'] = $dataSemesterAktifLokal['id_smt'];
        $LocalDataMhs = $this->masterdata->getDataMhs()->result_array();
        $countLocalDataMhs = count($LocalDataMhs);
        $data['count_mhs_local'] = $countLocalDataMhs;
        // $data['count_mhs_local'] = '5911';
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
        // code here..
        $dataRes = $this->getMhsFromApiSimak;
        $dataMhs = $dataRes['mhsdata'];
        $jml_data = count($dataMhs);
        foreach ($dataMhs as $j => $d) {
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
}
