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

    public function Cari_Mhs()
    {
        if ($this->input->is_ajax_request()) {
            $nim = $this->input->post('nipd');
            $response = $this->masterdata->getMahasiswa($nim);
            $dataRes = json_decode($response, true);
            $dataMhs = $dataRes['mhsdata'];
            if ($dataMhs != null) {
                $jenjang = $dataRes['mhsdata']['jenjang'];
                $where_tahun = [
                    'angkatan' => $dataRes['mhsdata']['tahun_masuk']
                ];
                // cek tunggakan
                $dataCekNim = [
                    'nim' => $nim
                ];
                $dataTG = $this->tunggakan->getTunggakanMhs($dataCekNim)->row_array();
                if ($dataTG != null) {
                    $dataMhs['tg'] = $dataTG['jml_tunggakan'];
                }
                // cek histori transaksi
                $dataHistoriTx = $this->transaksi->cekHistori($dataCekNim)->row_array();
                // var_dump($dataHistoriTx);
                // die;
                if ($dataHistoriTx != null) {
                    // belum fix
                    $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjang)->row_array();
                    $dataMhs['kmhs'] = $dataBiaya['kemahasiswaan'];
                    $C1 = $dataBiaya['cicilan_semester'] / 3;
                    $C2 = $dataBiaya['cicilan_semester'] / 3;
                    $C3 = $dataBiaya['cicilan_semester'] / 3;
                    $dataMhs['c1'] = $C1;
                    $dataMhs['c2'] = $C2;
                    $dataMhs['c3'] = $C3;
                    echo json_encode($dataMhs);
                } else {
                    // cek biaya angkatan
                    $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjang)->row_array();
                    $dataMhs['ub'] = $dataBiaya['uang_bangunan'];
                    $dataMhs['kmhs'] = $dataBiaya['kemahasiswaan'];
                    $C1 = $dataBiaya['cicilan_semester'] / 3;
                    $C2 = $dataBiaya['cicilan_semester'] / 3;
                    $C3 = $dataBiaya['cicilan_semester'] / 3;
                    $dataMhs['c1'] = $C1;
                    $dataMhs['c2'] = $C2;
                    $dataMhs['c3'] = $C3;
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
        // get post()
        $nimMhs = $this->input->post('nim_mhs_bayar');
        $namaMhs = $this->input->post('nama_mhs_bayar');
        $jenjangMhs = $this->input->post('jenjang_mhs_bayar');
        $angkatanMhs = $this->input->post('angkatan_mhs_bayar');
        $bayarUB = $this->input->post('UB');
        $bayarTG = $this->input->post('tunggakan');
        $bayarKMHS = $this->input->post('kmhs');
        $bayarC1 = $this->input->post('C1');
        $bayarC2 = $this->input->post('C2');
        $bayarC3 = $this->input->post('C3');

        //=============== cek input tunggakan ===============
        if ($bayarTG != null) {
            // ambil data tunggakan
            $dataCekNim = [
                'nim' => $nimMhs
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekNim)->row_array();
            $dataTGBaru = $dataTG['jml_tunggakan'] - $bayarTG;
            $where_id = [
                'id_tunggakan' => $dataTG['id_tunggakan']
            ];
            if ($dataTGBaru === 0) {
                // hapus data tunggakan
                $tgDeleted = $this->tunggakan->deleteTunggakan($where_id);
            } else {
                // update data tunggakan
                $dataUpdate = [
                    'jml_tunggakan' => $dataTGBaru
                ];
                $tgUpdated = $this->tunggakan->updateTunggakan($dataUpdate);
            }
        }

        //=============== cek data transaksi =================
        // cek histori transaksi
        $dataCek = [
            'nim' => $nimMhs,
            'semester' => $smtAktif
        ];
        $dataHistoriTx = $this->transaksi->cekHistori($dataCek)->row_array();

        // ===============================================
        $dateNow = date('Y-m-d H:i:s');
        $pecah_tgl_waktu = explode(' ', $dateNow);
        $tgl = $pecah_tgl_waktu[0];
        $jam = $pecah_tgl_waktu[1];
        $pecah_tgl = explode('-', $tgl);
        $tahunBerjalan = $pecah_tgl[0];
        $blnBerjalan = $pecah_tgl[1];
        $tglBerjalan = $pecah_tgl[2];

        if ($dataHistoriTx === null) {
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

            $dataInsertTx = [
                'id_transaksi' => $id_transaksi,
                'tanggal' => $tgl,
                'jam' => $jam,
                'nim' => $nimMhs,
                'semester' => $smtAktif
            ];

            $insertTx = $this->transaksi->addNewTransaksi($dataInsertTx);
            if (!$insertTx) {
                // error
                echo 'error';
            } else {
                redirect('transaksi/pembayaran_spp');
            }
        } else {
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

            $dataInsertTx = [
                'id_transaksi' => $id_transaksi,
                'tanggal' => $tgl,
                'jam' => $jam,
                'nim' => $nimMhs,
                'semester' => $smtAktif
            ];
            $insertTx = $this->transaksi->addNewTransaksi($dataInsertTx);
            if (!$insertTx) {
                // error
                echo 'error';
            } else {
                redirect('transaksi/pembayaran_spp');
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
