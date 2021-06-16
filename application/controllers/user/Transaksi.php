<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name       : Transaksi.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author          : Rizky Ardiansyah
 *  Date Created    : 22 Desember 2020
 */
class Transaksi extends CI_Controller
{
    private $smt_aktif;
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        date_default_timezone_set('Asia/Jakarta');
        // $this->date = date('Y-m-d H:i:s');
        $this->smt_aktif = getSemesterAktif();
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_transaksi', 'transaksi');
        $this->load->model('M_tunggakan', 'tunggakan');
    }

    public function GetJenisTransaksi()
    {
        if ($this->input->is_ajax_request()) {
            // $angkatan = $this->input->post('tahun_masuk');
            $where = 'id_jenis_pembayaran BETWEEN 4 AND 13';
            $response = $this->masterdata->GetJenisPembayaran($where)->result_array();
            echo json_encode($response);
        } else {
            echo "Error";
        }
    }

    public function Cari_Mhs()
    {
        if ($this->input->is_ajax_request()) {

            $smtAktif = $this->smt_aktif['id_smt'];
            $nim = $this->input->post('nipd');
            $response = $this->masterdata->getMahasiswa($nim);
            $dataRes = json_decode($response, true);

            $dataKewajiban = [];
            $dataMhs = $dataRes['mhsdata'];
            if ($dataMhs != null) {
                $jenjang = $dataRes['mhsdata']['jenjang'];
                $where_tahun = [
                    'angkatan' => $dataRes['mhsdata']['tahun_masuk']
                ];

                // cek tunggakan
                $dataCekTG = [
                    'nim' => $nim,
                    'jenis_tunggakan' => '1'
                ];
                $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
                // var_dump($dataTG);
                // die;
                if ($dataTG != null) {
                    $dataKewajiban[] = [
                        'post_id' => 'bayar_TG',
                        'label' => 'Tunggakan',
                        'biaya' => $dataTG['jml_tunggakan']
                    ];
                } else {
                    $dataKewajiban[] = [
                        'post_id' => 'bayar_TG',
                        'label' => 'Tunggakan',
                        'biaya' => 0
                    ];
                }
                $dataCekNim = [
                    'nim' => $nim,
                    'semester' => $smtAktif
                ];
                // cek histori transaksi
                $dataHistoriTx = $this->transaksi->cekHistori($dataCekNim)->row_array();
                // var_dump($dataHistoriTx);
                // die;
                $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjang)->row_array();
                if ($dataHistoriTx != null) {
                    // ada histori transaksi
                    $C1 = [
                        'post_id' => 'bayar_C1',
                        'label' => 'Cicilan Ke-1',
                        'biaya' => $dataBiaya['cicilan_semester'] / 3
                    ];
                    $C2 = [
                        'post_id' => 'bayar_C2',
                        'label' => 'Cicilan Ke-2',
                        'biaya' => $dataBiaya['cicilan_semester'] / 3
                    ];
                    $C3 = [
                        'post_id' => 'bayar_C3',
                        'label' => 'Cicilan Ke-3',
                        'biaya' => $dataBiaya['cicilan_semester'] / 3
                    ];
                    $dataKewajiban[] = $C1;
                    $dataKewajiban[] = $C2;
                    $dataKewajiban[] = $C3;
                    $dataKewajiban[] = [
                        'post_id' => 'bayar_Kmhs',
                        'label' => 'Kemahasiswaan',
                        'biaya' => $dataBiaya['kemahasiswaan']
                    ];

                    $dataMhs['dataKewajiban'] = $dataKewajiban;
                    echo json_encode($dataMhs);
                } else {
                    // belum ada histori transaksi
                    // $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjang)->row_array();
                    $C1 = [
                        'post_id' => 'bayar_C1',
                        'label' => 'Cicilan Ke-1',
                        'biaya' => $dataBiaya['cicilan_semester'] / 3
                    ];
                    $C2 = [
                        'post_id' => 'bayar_C2',
                        'label' => 'Cicilan Ke-2',
                        'biaya' => $dataBiaya['cicilan_semester'] / 3
                    ];
                    $C3 = [
                        'post_id' => 'bayar_C3',
                        'label' => 'Cicilan Ke-3',
                        'biaya' => $dataBiaya['cicilan_semester'] / 3
                    ];
                    $dataKewajiban[] = $C1;
                    $dataKewajiban[] = $C2;
                    $dataKewajiban[] = $C3;
                    // $dataKewajiban[] = [
                    //     'post_id' => 'bayar_UB',
                    //     'label' => 'Pengembangan Kampus',
                    //     'biaya' => $dataBiaya['uang_bangunan']
                    // ];
                    // $dataKewajiban[] = [
                    //     'post_id' => 'bayar_Kmhs',
                    //     'label' => 'Kemahasiswaan',
                    //     'biaya' => $dataBiaya['kemahasiswaan']
                    // ];
                    $dataMhs['dataKewajiban'] = $dataKewajiban;
                    echo json_encode($dataMhs);
                }
            } else {
                echo json_encode($dataMhs);
            }
        } else {
            echo "Error";
        }
    }
    public function Pembayaran_Spp()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Pembayaran Spp';
        $data['content'] = 'transaksi/pembayaran_spp';
        $this->load->view('template', $data);
    }
    public function Proses_Bayar_Spp()
    {
        $smtAktif = $this->smt_aktif['id_smt'];
        $dataTxDetail = [];
        // get post()
        $nimMhs = $this->input->post('nim_mhs_bayar');
        $namaMhs = $this->input->post('nama_mhs_bayar');
        $jenjangMhs = $this->input->post('jenjang_mhs_bayar');
        $angkatanMhs = $this->input->post('angkatan_mhs_bayar');
        // =========== Data Pembayaran ========================
        $bayarTG = $this->input->post('bayar_TG');
        // $bayarUB = $this->input->post('bayar_UB');
        $bayarKMHS = $this->input->post('bayar_Kmhs');
        $bayarC1 = $this->input->post('bayar_C1');
        $bayarC2 = $this->input->post('bayar_C2');
        $bayarC3 = $this->input->post('bayar_C3');

        $All = $this->input->post();
        var_dump($All);
        die;
        $where_tahun = [
            'angkatan' => $angkatanMhs
        ];
        $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
        $biayaCS = $dataBiaya['cicilan_semester'] / 3;
        $dataBayarC1 = $biayaCS - $bayarC1;
        $dataBayarC2 = $biayaCS - $bayarC2;
        $dataBayarC3 = $biayaCS - $bayarC3;
        $dataBayarKMHS = $dataBiaya['kemahasiswaan'] - $bayarKMHS;
        // var_dump($dataBayarC1 . '/' . $dataBayarC2 . '/' . $dataBayarC3 . '/' . $dataBayarKMHS);
        // die;

        //=============== cek data transaksi =================
        // cek histori transaksi
        $dataCek = [
            'nim' => $nimMhs,
            'semester' => $smtAktif
        ];
        $dataHistoriTx = $this->transaksi->cekHistori($dataCek)->row_array();


        /*
        * *================ Fungsi genret id transaksi =================
        */
        $dateNow = date('Y-m-d H:i:s');
        $pecah_tgl_waktu = explode(' ', $dateNow);
        $tgl = $pecah_tgl_waktu[0];
        $jam = $pecah_tgl_waktu[1];
        $pecah_tgl = explode('-', $tgl);
        $tahunBerjalan = $pecah_tgl[0];
        $blnBerjalan = $pecah_tgl[1];
        // $tglBerjalan = $pecah_tgl[2];
        $cekTxId = $this->transaksi->cekTxId()->row_array();
        $ambil_id_tgl = substr($cekTxId['id_transaksi'], 4, -4);
        $id_date = $tahunBerjalan . $blnBerjalan;
        //jika belum ada id, di set id dengan format (tahun_tanggal_0001)
        $mulai_id = $this->createtxid->set(1, 4);
        if ($cekTxId['id_transaksi'] == 0) {
            $id_transaksi = $id_date . $mulai_id;
        }
        //jika tanggal di id_transaksi tidak sama dengan tanggal skrg, di set id dengan format (tahun_tanggal_0001)
        else if ($blnBerjalan != $ambil_id_tgl) {
            $id_transaksi = $id_date . $mulai_id;
        }
        //selain itu max(id_transaksi)+1
        else {
            $id_transaksi = $cekTxId['id_transaksi'] + 1;
        }
        // ============== End Fungsi genret id transaksi ===============


        /*
        * *=============== cek input tunggakan =========================
        */
        if ($bayarTG != null) {
            // $dataTxDetail['bayar_TG'] = [
            //     'jenis' => 6,
            //     'jml_bayar' => $bayarTG
            // ];
            // ambil data tunggakan
            $whereCekNim = [
                'nim' => $nimMhs,
                'jenis_tunggakan' => 1
            ];
            $dataTG_CS = $this->tunggakan->getTunggakanMhs($whereCekNim)->row_array();
            // var_dump($dataTG);
            // die;
            $dataTGBaru = $dataTG_CS['jml_tunggakan'] - $bayarTG;
            $where_id = [
                'id_tunggakan' => $dataTG_CS['id_tunggakan']
            ];
            if ($dataTGBaru === 0) {
                // hapus data tunggakan
                $tgDeleted = $this->tunggakan->deleteTunggakan($where_id);
            } else {
                // update data tunggakan
                $dataUpdate = [
                    'jml_tunggakan' => $dataTGBaru
                ];
                $tgUpdated = $this->tunggakan->updateTunggakan($where_id, $dataUpdate);
            }
        }
    }

    public function Pembayaran_Lainnya()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Pembayaran Lain';
        $data['content'] = 'transaksi/pembayaran_lainnya';
        $this->load->view('template', $data);
    }
}
