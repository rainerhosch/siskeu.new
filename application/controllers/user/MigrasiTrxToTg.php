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

    public function getTrxLastSmtV1()
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
                    // if ($dtx['id_jenis_pembayaran'] != '2' || $dtx['id_jenis_pembayaran'] != '3' || $dtx['id_jenis_pembayaran'] != '4') {
                    //     $data[$i]['bayar_lainya'] = true;
                    // }
                    if ($dtx['id_jenis_pembayaran'] === '2' || $dtx['id_jenis_pembayaran'] === '3' || $dtx['id_jenis_pembayaran'] === '4') {
                        $data[$i]['total_bayar_cs'] = $data[$i]['total_bayar_cs'] + $dtx['jml_bayar'];
                        $data[$i]['histori_trx'][$j]['detail_trx'][$x]['sisa_bayar_cs'] = $data[$i]['kewajiban_cs'] - $data[$i]['total_bayar_cs'];
                    }

                    if ($dtx['id_jenis_pembayaran'] === '8') {
                        // $data[$i]['bayar_lainya'] = true;
                        $data[$i]['total_bayar_ps'] = $data[$i]['total_bayar_ps'] + $dtx['jml_bayar'];
                        $data[$i]['histori_trx'][$j]['detail_trx'][$x]['sisa_bayar_ps'] = $data[$i]['kewajiban_ps'] - $dtx['jml_bayar'];
                    }
                    if ($dtx['id_jenis_pembayaran'] === '5') {
                        $data[$i]['total_bayar_kmhs'] = $data[$i]['total_bayar_kmhs'] + $dtx['jml_bayar'];
                        $data[$i]['histori_trx'][$j]['detail_trx'][$x]['sisa_bayar_kmhs'] = $data[$i]['kewajiban_kmhs'] - $dtx['jml_bayar'];
                    }
                }

            }

            if ($data[$i]['kewajiban_cs'] - $data[$i]['total_bayar_cs'] <= 0 || $data[$i]['kewajiban_ps'] - $data[$i]['total_bayar_ps'] <= 0) {
                $data[$i]['status_pembayaran'] = 'Lunas';
                $data['total_lunas'] = $data['total_lunas'] + 1;
            }
            if ($data[$i]['kewajiban_cs'] - $data[$i]['total_bayar_cs'] > 0) {
                $data['total_belum_lunas'] = $data['total_belum_lunas'] + 1;
            }

            // $dataDetailTrx = $this->transaksi->getDataDetailTransaksiOnly(['id_transaksi' => $val['id_transaksi']])->result_array();
        }
        echo json_encode($data);
    }

    public function getTrxLastSmt()
    {
        $smtAktifRes = $this->masterdata->getDataSemester()->result_array();
        $smtSebelumnya = $smtAktifRes[1];
        // var_dump($smtSebelumnya);
        // die;
        $smtAktifRes = [0 => $smtSebelumnya];

        $data = [];
        foreach ($smtAktifRes as $i => $smt) {
            // $condition = [
            //     'trx.semester =' => $smtSebelumnya['id_smt'],
            //     'mjp.jenis_kas' => 1
            // ];
            // $dataHistoriTxByNim = $this->transaksi->getDataTransaksiOnly(['semester' => $smt['id_smt']])->result_array();
            // $dataTrx = $this->getDataTransaksi(['semester' => $smt['id_smt']]);
            $condition = [
                'trx.semester =' => $smt['id_smt'],
                // 'mjp.jenis_kas' => 1
            ];
            $dataDispen = $this->aktivasi->getDataDispenMhs(['tahun_akademik' => $smt['id_smt'], 'status' => 0])->result_array();
            $data[$i]['dispen_mhs_bl'] = count($dataDispen);
            $dataTrx = $this->transaksi->getTrxByNim(['where' => $condition])->result_array();
            $data[$i]['total_lunas'] = 0;
            $data[$i]['total_belum_lunas'] = 0;
            $ResdataTrx = [];
            foreach ($dataTrx as $j => $val) {
                $ResdataTrx[$j]['status_pembayaran'] = 'Belum Lunas';
                $ResdataTrx[$j]['total_bayar_cs'] = 0;
                $ResdataTrx[$j]['total_bayar_ps'] = 0;
                $ResdataTrx[$j]['total_bayar_kmhs'] = 0;
                $ResdataTrx[$j]['total_bayar_pangkal'] = 0;
                $ResdataTrx[$j]['total_bayar_lainnya'] = 0;
                $ResdataTrx[$j]['kewajiban_lainnya'] = 0;
                // get data mhs
                $dataMhs = $this->masterdata->getDataMhs(['nipd' => $val['nim']])->row_array();
                if (!is_null($dataMhs)) {
                    $ResdataTrx[$j]['nim'] = $dataMhs['nipd'];
                    $ResdataTrx[$j]['nama'] = $dataMhs['nm_pd'];
                    $ResdataTrx[$j]['id_jurusan'] = $dataMhs['id_jur'];
                    $ResdataTrx[$j]['prodi'] = $dataMhs['nm_jur'];
                    $ResdataTrx[$j]['tahun_masuk'] = $dataMhs['tahun_masuk'];
                    $ResdataTrx[$j]['jnj_didik'] = $dataMhs['nm_jenj_didik'];

                    // get data biaya
                    $where_tahun = [
                        'angkatan' => $dataMhs['tahun_masuk']
                    ];
                    $jenjangMhs = $dataMhs['nm_jenj_didik'];
                    $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
                    $ResdataTrx[$j]['kewajiban_cs'] = (int) $dataBiaya['cicilan_semester'];
                    $ResdataTrx[$j]['kewajiban_ps'] = (int) ($dataBiaya['cicilan_semester'] / 2);
                    $ResdataTrx[$j]['kewajiban_kmhs'] = (int) $dataBiaya['kemahasiswaan'];
                    $ResdataTrx[$j]['kewajiban_pangkal'] = (int) $dataBiaya['uang_bangunan'];
                    // cek data trx mhs 
                    $histori_trx = $this->transaksi->getDataTransaksiOnly(['nim' => $val['nim'], 'semester' => $smt['id_smt']])->result_array();
                    $ResdataTrx[$j]['histori_trx'] = $histori_trx;
                    foreach ($histori_trx as $k => $trx) {
                        $dataDetailTrx = $this->transaksi->getDataDetailTransaksiOnly(['id_transaksi' => $trx['id_transaksi']])->result_array();
                        $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'] = $dataDetailTrx;
                        foreach ($dataDetailTrx as $x => $dtx) {
                            if ((int) $dtx['id_jenis_pembayaran'] > 8) {
                                $data_kewajiban = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $dtx['id_jenis_pembayaran']])->row_array();
                                $dataCekTrxBefor = $this->transaksi->getDataTransaksiSebelumnya(['t.id_transaksi <' => $histori_trx[$k]['id_transaksi'], 't.semester =' => $histori_trx[$k]['semester'], 't.nim' => $histori_trx[$k]['nim'], 'mjp.id_jenis_pembayaran' => $dtx['id_jenis_pembayaran']])->result_array();
                                if (count($dataCekTrxBefor) > 0) {
                                    foreach ($dataCekTrxBefor as $v => $dctb) {
                                        $data_kewajiban['biaya'] = $data_kewajiban['biaya'] - $dctb['jml_bayar'];
                                    }
                                }
                                $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $data_kewajiban['biaya'];
                                $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = $data_kewajiban['nm_jp'];
                                $ResdataTrx[$j]['kewajiban_lainnya'] = $ResdataTrx[$j]['kewajiban_lainnya'] + $data_kewajiban['biaya'];
                                $ResdataTrx[$j]['total_bayar_lainnya'] = $ResdataTrx[$j]['total_bayar_lainnya'] + $dtx['jml_bayar'];

                                // $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['jml_trx_befor'] = count($dataCekTrxBefor);
                                // $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['trx_befor'] = $dataCekTrxBefor;
                                $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = $data_kewajiban['biaya'] - $dtx['jml_bayar'];
                                $ResdataTrx[$j]['sisa_bayar_lainnya'] = $data_kewajiban['biaya'] - $dtx['jml_bayar'];
                            } else {
                                if ($dtx['id_jenis_pembayaran'] === '2') {
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = 'Cicilan Ke 1';
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $ResdataTrx[$j]['kewajiban_cs'] / 3;
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = ($ResdataTrx[$j]['kewajiban_cs'] / 3) - $dtx['jml_bayar'];
                                }
                                if ($dtx['id_jenis_pembayaran'] === '3') {
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = 'Cicilan Ke 2';
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $ResdataTrx[$j]['kewajiban_cs'] / 3;
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = ($ResdataTrx[$j]['kewajiban_cs'] / 3) - $dtx['jml_bayar'];
                                }
                                if ($dtx['id_jenis_pembayaran'] === '4') {
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = 'Cicilan Ke 3';
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $ResdataTrx[$j]['kewajiban_cs'] / 3;
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = ($ResdataTrx[$j]['kewajiban_cs'] / 3) - $dtx['jml_bayar'];
                                }
                                if ($dtx['id_jenis_pembayaran'] === '5') {
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = 'Kemahasiswaan';
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $ResdataTrx[$j]['kewajiban_kmhs'];
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = $ResdataTrx[$j]['kewajiban_kmhs'] - $dtx['jml_bayar'];
                                }
                                if ($dtx['id_jenis_pembayaran'] === '6') {
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = 'Tunggakan CS';
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $dtx['jml_bayar'];
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = $dtx['jml_bayar'] - $dtx['jml_bayar'];
                                }
                                if ($dtx['id_jenis_pembayaran'] === '7') {
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = 'Tunggakan Kmhs';
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $dtx['jml_bayar'];
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = $dtx['jml_bayar'] - $dtx['jml_bayar'];
                                }
                                if ($dtx['id_jenis_pembayaran'] === '8') {
                                    $dataCekTrxBefor = $this->transaksi->getDataTransaksiSebelumnya(['t.id_transaksi <' => $histori_trx[$k]['id_transaksi'], 't.semester =' => $histori_trx[$k]['semester'], 't.nim' => $histori_trx[$k]['nim'], 'mjp.id_jenis_pembayaran' => $dtx['id_jenis_pembayaran']])->result_array();

                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['nama_pembayaran'] = 'Perpanjang Semester';
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $ResdataTrx[$j]['kewajiban_ps'];
                                    $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = $ResdataTrx[$j]['kewajiban_ps'] - $dtx['jml_bayar'];
                                    if (count($dataCekTrxBefor) > 0) {
                                        foreach ($dataCekTrxBefor as $v => $dctb) {
                                            $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] = $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] - $dctb['jml_bayar'];
                                            $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_kewajiban'] = $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['biaya'] - $dtx['jml_bayar'];
                                        }
                                    }
                                }
                            }
                            // if ($dtx['id_jenis_pembayaran'] != '2' || $dtx['id_jenis_pembayaran'] != '3' || $dtx['id_jenis_pembayaran'] != '4') {
                            //     $ResdataTrx[$j]['bayar_lainya'] = true;
                            // }
                            if ($dtx['id_jenis_pembayaran'] === '2' || $dtx['id_jenis_pembayaran'] === '3' || $dtx['id_jenis_pembayaran'] === '4') {
                                $ResdataTrx[$j]['total_bayar_cs'] = $ResdataTrx[$j]['total_bayar_cs'] + $dtx['jml_bayar'];
                                $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_bayar_cs'] = $ResdataTrx[$j]['kewajiban_cs'] - $ResdataTrx[$j]['total_bayar_cs'];
                            }

                            if ($dtx['id_jenis_pembayaran'] === '8') {
                                // $ResdataTrx[$j]['bayar_lainya'] = true;
                                $ResdataTrx[$j]['total_bayar_ps'] = $ResdataTrx[$j]['total_bayar_ps'] + $dtx['jml_bayar'];
                                // $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_bayar_ps'] = $ResdataTrx[$j]['kewajiban_ps'] - $dtx['jml_bayar'];
                            }
                            if ($dtx['id_jenis_pembayaran'] === '5') {
                                $ResdataTrx[$j]['total_bayar_kmhs'] = $ResdataTrx[$j]['total_bayar_kmhs'] + $dtx['jml_bayar'];
                                $ResdataTrx[$j]['histori_trx'][$k]['detail_trx'][$x]['sisa_bayar_kmhs'] = $ResdataTrx[$j]['kewajiban_kmhs'] - $dtx['jml_bayar'];
                            }
                        }

                    }


                    if ($ResdataTrx[$j]['total_bayar_lainnya'] != 0) {
                        // if ($ResdataTrx[$j]['kewajiban_lainnya'] - $ResdataTrx[$j]['total_bayar_lainnya'] <= 0) {
                        if ($ResdataTrx[$j]['sisa_bayar_lainnya'] <= 0) {
                            $ResdataTrx[$j]['status_pembayaran'] = 'Lunas';
                            $data[$i]['total_lunas'] = $data[$i]['total_lunas'] + 1;
                        } else {
                            $data[$i]['total_belum_lunas'] = $data[$i]['total_belum_lunas'] + 1;
                        }
                    }

                    // if (($ResdataTrx[$j]['kewajiban_cs'] - $ResdataTrx[$j]['total_bayar_cs'] <= 0 && $ResdataTrx[$j]['total_bayar_cs'] != 0) || ($ResdataTrx[$j]['kewajiban_ps'] - $ResdataTrx[$j]['total_bayar_ps'] <= 0 && $ResdataTrx[$j]['total_bayar_ps'] != 0) || ($ResdataTrx[$j]['kewajiban_lainnya'] - $ResdataTrx[$j]['total_bayar_lainnya'] <= 0 && $ResdataTrx[$j]['total_bayar_lainnya'] != 0)) {
                    if (($ResdataTrx[$j]['kewajiban_cs'] - $ResdataTrx[$j]['total_bayar_cs'] <= 0 && $ResdataTrx[$j]['total_bayar_cs'] != 0) || ($ResdataTrx[$j]['kewajiban_ps'] - $ResdataTrx[$j]['total_bayar_ps'] <= 0 && $ResdataTrx[$j]['total_bayar_ps'] != 0)) {
                        $ResdataTrx[$j]['status_pembayaran'] = 'Lunas';
                        $data[$i]['total_lunas'] = $data[$i]['total_lunas'] + 1;
                    }
                    // if ($ResdataTrx[$j]['kewajiban_cs'] - $ResdataTrx[$j]['total_bayar_cs'] > 0) {
                    //     $data[$i]['total_belum_lunas'] = $data[$i]['total_belum_lunas'] + 1;
                    // }
                }
                // if($ResdataTrx[$j]['id_jurusan'])
            }
            $data[$i]['selisih_bl_dispen'] = $data[$i]['total_belum_lunas'] - count($dataDispen);
            // $data[$i][$smt['id_smt']] = $ResdataTrx;

            // $groupingData = [];
            $dataPrody = $this->masterdata->getDataMhsByPrody()->result_array();
            foreach ($ResdataTrx as $r => $rtx) {
                foreach ($dataPrody as $p => $prody) {
                    if ($rtx['id_jurusan'] == $prody['id_jur']) {
                        $data[$i][$smt['id_smt']][$prody['nm_jur']] = $rtx;
                    }
                }
            }
        }
        // $data = $smtAktifRes;
        // $dataBL = [];
        // foreach ($data as $i => $dt) {
        //     $indexBL = 0;
        //     foreach ($dt[$smtAktifRes[$i]['id_smt']] as $j => $val) {
        //         if ($val['status_pembayaran'] == 'Belum Lunas') {

        //             $dataBL[$smtAktifRes[$i]['id_smt']][$indexBL] = $val;
        //             // $dataBL[$indexBL][$smtAktifRes[$i]['id_smt']][$j] = $val;
        //             $indexBL++;
        //         }
        //     }
        // }
        echo json_encode($data);
    }

    public function getDataTransaksi($data = null)
    {
        $res = $this->transaksi->getDataTransaksiOnly($data)->result_array();
        return $res;
    }

    public function migrate_to_tg()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
    }
}