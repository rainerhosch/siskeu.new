<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : MigrasiTrxToTg.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 13/09/2022
 *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
 */
class MigrasiTrxToTg extends CI_Controller
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
        $this->load->library('Terbilang');
        $this->load->library('FormatTanggal');


        $this->load->model('M_cetak_kwitansi', 'cetak');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_transaksi', 'transaksi');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_user', 'user');
    }

    public function index()
    {
        $data['title'] = 'Utility';
        $data['page'] = 'Test Page (Belum Fix, harus ambil data mhs diwal jangan di akhir)';
        $data['content'] = 'layout/test_page';
        $this->load->view('template', $data);
    }

    public function getTrxLastSmtById()
    {
        $smtAktifRes = $this->masterdata->getDataSemester()->result_array();
        $smtSebelumnya = $smtAktifRes[1];

        $data = [];

        $index = 0;
        $dataBelumLunasTrx = [];

        $condition = [
            'trx.semester =' => $smtSebelumnya['id_smt'],
            'mjp.jenis_kas' => 1
        ];
        $dataHistoriTx = $this->transaksi->getIdTrx(['where' => $condition])->result_array();
        // $data = $dataHistoriTx;
        foreach ($dataHistoriTx as $i => $val) {
            $data[$i] = $this->transaksi->getDataTransaksiOnly(['id_transaksi' => $val['id_transaksi']])->row_array();
            $dataDetailTrx = $this->transaksi->getDataDetailTransaksiOnly(['id_transaksi' => $val['id_transaksi']])->result_array();
            $data[$i]['detail_trx'] = $dataDetailTrx;
            $data[$i]['total_bayar_cs'] = 0;
            $data[$i]['total_bayar_ps'] = 0;
            $data[$i]['total_bayar_kmhs'] = 0;

            // get data mhs
            $dataMhs = $this->masterdata->getDataMhs(['nipd' => $val['nim']])->row_array();
            $data[$i]['nama'] = $dataMhs['nm_pd'];
            $data[$i]['prodi'] = $dataMhs['nm_jur'];
            $data[$i]['tahun_masuk'] = $dataMhs['tahun_masuk'];
            $data[$i]['jnj_didik'] = $dataMhs['nm_jenj_didik'];

            // get data biaya
            $where_tahun = [
                'angkatan' => $dataMhs['tahun_masuk']
            ];
            $jenjangMhs = $dataMhs['nm_jenj_didik'];
            $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
            $data[$i]['kewajiban_cs'] = $dataBiaya['cicilan_semester'];
            $data[$i]['kewajiban_ps'] = ($dataBiaya['cicilan_semester'] / 2);
            $data[$i]['kewajiban_kmhs'] = $dataBiaya['kemahasiswaan'];
            $data[$i]['kewajiban_pangkal'] = $dataBiaya['uang_bangunan'];


            // get data jenis pembayaran
            foreach ($dataDetailTrx as $j => $dtx) {
                $jenis_pembayaran = $this->masterdata->GetAllJenisPembayaran(['id_jenis_pembayaran' => $dtx['id_jenis_pembayaran']])->row_array();
                $data[$i]['detail_trx'][$j]['jenis_pembayaran'] = $jenis_pembayaran['nm_jp'];
                if ($dtx['id_jenis_pembayaran'] === '2' || $dtx['id_jenis_pembayaran'] === '3' || $dtx['id_jenis_pembayaran'] === '4') {
                    $data[$i]['total_bayar_cs'] = $data[$i]['total_bayar_cs'] + $dtx['jml_bayar'];
                    $data[$i]['sisa_bayar_cs'] = $data[$i]['kewajiban_cs'] - $dtx['jml_bayar'];
                }

                if ($dtx['id_jenis_pembayaran'] === '8') {
                    $data[$i]['total_bayar_ps'] = $data[$i]['total_bayar_ps'] + $dtx['jml_bayar'];
                    $data[$i]['sisa_bayar_ps'] = $data[$i]['kewajiban_ps'] - $dtx['jml_bayar'];
                }
                if ($dtx['id_jenis_pembayaran'] === '5') {
                    $data[$i]['total_bayar_kmhs'] = $data[$i]['total_bayar_kmhs'] + $dtx['jml_bayar'];
                    $data[$i]['sisa_bayar_kmhs'] = $data[$i]['kewajiban_kmhs'] - $dtx['jml_bayar'];
                }
            }

            // if ($data[$i]['total_bayar_cs'] > 0) {
            //     $data[$i]['sisa_bayar_cs'] = $data[$i]['kewajiban_cs'] - $data[$i]['total_bayar_cs'];
            // }


            // if ($data[$i]['sisa_bayar_cs'] > 0) {
            // $dataBelumLunasTrx = $data[$i];
            // $dataBelumLunasTrx[$i]['nim'] = $data[$i]['nim'];
            // $dataBelumLunasTrx[$i]['nama'] = $data[$i]['nama'];
            // $dataBelumLunasTrx[$i]['prodi'] = $data[$i]['prodi'];
            // $dataBelumLunasTrx[$i]['sisa_bayar_cs'] = $data[$i]['sisa_bayar_cs'];
            // $index + 1;
            // }
        }

        foreach ($data as $d => $dtrx) {
            if ($dtrx['total_bayar_cs'] > 0 || $dtrx['total_bayar_ps'] > 0) {
                $dataBelumLunasTrx[$index] = $data[$d];
                // $dataBelumLunasTrx[$index]['nim'] = $data[$d]['nim'];
                // $dataBelumLunasTrx[$index]['nama'] = $data[$d]['nama'];
                // $dataBelumLunasTrx[$index]['prodi'] = $data[$d]['prodi'];
                // $dataBelumLunasTrx[$index]['prodi'] = $data[$d]['prodi'];
                // $dataBelumLunasTrx[$index]['sisa_bayar_cs'] = $data[$d]['sisa_bayar_cs'];
                $index++;
            }
        }

        // echo json_encode($dataBelumLunasTrx);
        echo json_encode($data);

    }

    public function getTrxLastSmt()
    {
        $smtAktifRes = $this->masterdata->getDataSemester()->result_array();
        $smtSebelumnya = $smtAktifRes[1];

        $data = [];
        $condition = [
            'trx.semester =' => $smtSebelumnya['id_smt'],
            'mjp.jenis_kas' => 1
        ];
        $dataHistoriTxByNim = $this->transaksi->getTrxByNim(['where' => $condition])->result_array();
        // $data = $dataHistoriTxByNim;
        $data['total_lunas'] = 0;
        $data['total_belum_lunas'] = 0;
        foreach ($dataHistoriTxByNim as $i => $val) {
            // $data[$i]['bayar_lainnya'] = true;
            $data[$i]['status_pembayaran'] = 'Belum Lunas';
            $data[$i]['total_bayar_cs'] = 0;
            $data[$i]['total_bayar_ps'] = 0;
            $data[$i]['total_bayar_kmhs'] = 0;
            $data[$i]['total_bayar_pangkal'] = 0;
            // get data mhs
            $dataMhs = $this->masterdata->getDataMhs(['nipd' => $val['nim']])->row_array();
            $data[$i]['nim'] = $dataMhs['nipd'];
            $data[$i]['nama'] = $dataMhs['nm_pd'];
            $data[$i]['prodi'] = $dataMhs['nm_jur'];
            $data[$i]['tahun_masuk'] = $dataMhs['tahun_masuk'];
            $data[$i]['jnj_didik'] = $dataMhs['nm_jenj_didik'];

            // get data biaya
            $where_tahun = [
                'angkatan' => $dataMhs['tahun_masuk']
            ];
            $jenjangMhs = $dataMhs['nm_jenj_didik'];
            $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
            $data[$i]['kewajiban_cs'] = (int) $dataBiaya['cicilan_semester'];
            $data[$i]['kewajiban_ps'] = (int) ($dataBiaya['cicilan_semester'] / 2);
            $data[$i]['kewajiban_kmhs'] = (int) $dataBiaya['kemahasiswaan'];
            $data[$i]['kewajiban_pangkal'] = (int) $dataBiaya['uang_bangunan'];

            // cek data trx mhs 
            $histori_trx = $this->transaksi->getDataTransaksiOnly(['nim' => $val['nim'], 'semester =' => $smtSebelumnya['id_smt']])->result_array();
            $data[$i]['histori_trx'] = $histori_trx;
            foreach ($histori_trx as $j => $trx) {
                $dataDetailTrx = $this->transaksi->getDataDetailTransaksiOnly(['id_transaksi' => $trx['id_transaksi']])->result_array();
                $data[$i]['histori_trx'][$j]['detail_trx'] = $dataDetailTrx;
                foreach ($dataDetailTrx as $x => $dtx) {
                    if ($dtx['id_jenis_pembayaran'] != '2' || $dtx['id_jenis_pembayaran'] != '3' || $dtx['id_jenis_pembayaran'] != '4') {
                        $data[$i]['bayar_lainya'] = false;
                    }
                    if ($dtx['id_jenis_pembayaran'] === '2' || $dtx['id_jenis_pembayaran'] === '3' || $dtx['id_jenis_pembayaran'] === '4') {
                        $data[$i]['total_bayar_cs'] = $data[$i]['total_bayar_cs'] + $dtx['jml_bayar'];
                        $data[$i]['histori_trx'][$j]['detail_trx'][$x]['sisa_bayar_cs'] = $data[$i]['kewajiban_cs'] - $data[$i]['total_bayar_cs'];
                    }

                    if ($dtx['id_jenis_pembayaran'] === '8') {
                        $data[$i]['bayar_lainya'] = true;
                        $data[$i]['total_bayar_ps'] = $data[$i]['total_bayar_ps'] + $dtx['jml_bayar'];
                        $data[$i]['histori_trx'][$j]['detail_trx'][$x]['sisa_bayar_ps'] = $data[$i]['kewajiban_ps'] - $dtx['jml_bayar'];
                    }
                    if ($dtx['id_jenis_pembayaran'] === '5') {
                        $data[$i]['total_bayar_kmhs'] = $data[$i]['total_bayar_kmhs'] + $dtx['jml_bayar'];
                        $data[$i]['histori_trx'][$j]['detail_trx'][$x]['sisa_bayar_kmhs'] = $data[$i]['kewajiban_kmhs'] - $dtx['jml_bayar'];
                    }
                }

            }
            if ($data[$i]['bayar_lainya'] === false) {
                if ($data[$i]['kewajiban_cs'] - $data[$i]['total_bayar_cs'] <= 0) {
                    $data[$i]['status_pembayaran'] = 'Lunas';
                    $data['total_lunas'] = $data['total_lunas'] + 1;
                }
                if ($data[$i]['kewajiban_cs'] - $data[$i]['total_bayar_cs'] > 0) {
                    $data['total_belum_lunas'] = $data['total_belum_lunas'] + 1;
                }
            }

            // $dataDetailTrx = $this->transaksi->getDataDetailTransaksiOnly(['id_transaksi' => $val['id_transaksi']])->result_array();
        }
        echo json_encode($data);
    }

    public function migrate_to_tg()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
    }
}