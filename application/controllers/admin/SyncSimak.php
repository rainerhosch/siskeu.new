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

        // $data['mhs_simak'] = $dataRes['mhsdata'];
        $ApiDataMhs =  $dataRes['mhsdata'];
        $counApiDataMhs = count($ApiDataMhs);
        $data['count_mhs_simak'] = $counApiDataMhs;
        $data['semester_aktif_simak'] = $smtAktif;
        // ===========================================================
        $dataSemesterAktifLokal = $this->masterdata->getSemesterAktif()->row_array();
        $data['semester_aktif_local'] = $dataSemesterAktifLokal['idtahun'];
        $LocalDataMhs = $this->masterdata->getDataMhs()->result_array();
        $countLocalDataMhs = count($LocalDataMhs);
        $data['count_mhs_local'] = $countLocalDataMhs;
        // ===========================================================
        echo json_encode($data);
    }
    public function SyncDataMhs()
    {
        // code here..
        $dataRes = $this->getMhsFromApiSimak;
        $dataMhs = $dataRes['mhsdata'];
        $jml_data = count($dataMhs);
        foreach ($dataMhs as $j => $d) {
            $insert = $this->masterdata->insertDataMhs($d);
            // loading($j, $jml_data);
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
    // public function loading()
    // {
    //     echo json_encode(['data' => 'success']);
    // }
}
