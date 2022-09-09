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
        // code here...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Transaksi';
        $data['content'] = 'transaksi/pembayaran';
        $where_date = [
            'tanggal' => date('Y-m-d')
        ];
        $data['jumlah_tx_hari_ini'] = $this->transaksi->getTxDateNow($where_date);
        $this->load->view('template', $data);
    }

    public function getDataTrfMahasiswa()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->transaksi->getBuktiTransfer(['status' => 0])->num_rows();
            $data = [
                'status'    => true,
                'code'      => 200,
                'msg'       => 'Ok!',
                'data'      => $res
            ];
        } else {
            $data = [
                'status'    => false,
                'code'      => 500,
                'msg'       => 'Invalid Request!',
                'data'      => null
            ];
        }
        echo json_encode($data);
    }

    public function get_data_trf_online()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->transaksi->getDataBuktiPembayaran(['status' => 0])->result_array();
            foreach ($res as $i => $val) {
                $data_rek_tujuan = $this->transaksi->get_data_rekening(['id_rek' => $val['rek_tujuan_trf']])->row_array();
                $res[$i]['bank_penerima'] = $data_rek_tujuan;
                // $res[$i]['nm_bank'] = $data_rek_tujuan['bank'];
                // $res[$i]['an_bank'] = $data_rek_tujuan['nama_rekening'];

                $jnsBayar = explode(',', $val['id_jenis_bayar']);
                $pembayaran = [];
                foreach ($jnsBayar as $j => $value) {
                    $filter = ['id_jenis_pembayaran' => $value];
                    $pembayaran[$j] = $this->masterdata->GetAllJenisTrx($filter)->row_array();
                }
                $res[$i]['pembayaran'] = $pembayaran;
            }
            $data = [
                'status'    => true,
                'code'      => 200,
                'msg'       => 'Ok!',
                'data'      => $res
            ];
        } else {
            $data = [
                'status'    => false,
                'code'      => 500,
                'msg'       => 'Invalid Request!',
                'data'      => null
            ];
        }
        echo json_encode($data);
    }

    public function insert_trx_from_trf()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            $data = [
                'status'    => true,
                'code'      => 200,
                'msg'       => 'OK!',
                'data'      => $data_post
            ];
        } else {
            $data = [
                'status'    => false,
                'code'      => 500,
                'msg'       => 'Invalid Request!',
                'data'      => null
            ];
        }
        echo json_encode($data);
    }

    public function acc_trf_online()
    {
        if ($this->input->is_ajax_request()) {
            $where = [
                'id_bukti_trf' => $this->input->post('id'),
            ];

            $res = $this->transaksi->getDataBuktiPembayaran($where)->row_array();
            $jnsBayar = explode(',', $res['id_jenis_bayar']);
            $totalBayar = $res['jumlah_bayar'];
            $jml_bayar = 0;
            $bayarKMHS = 0;



            $response = $this->masterdata->getMahasiswaByNim(['nipd' => $res['nipd']])->row_array();
            $dataMhs = $response;
            $dataMhs['smt'] = $res['smt'];
            $dataMhs['data_trf'] = $res;

            $where_tahun = [
                'angkatan' => $dataMhs['tahun_masuk']
            ];
            $jenjangMhs = $dataMhs['nm_jenj_didik'];
            $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
            $biayaCS = $dataBiaya['cicilan_semester'] / 3;
            $biayaKMHS = $dataBiaya['kemahasiswaan'];
            // $dataMhs['data_biaya'] = $dataBiaya;
            $data = [
                'status'    => true,
                'code'      => 200,
                'msg'       => 'OK!',
                'data'      => $dataMhs
            ];
        } else {
            $data = [
                'status'    => false,
                'code'      => 500,
                'msg'       => 'Invalid Request!',
                'data'      => null
            ];
        }
        echo json_encode($data);
    }
    public function reject_trf_online()
    {
        if ($this->input->is_ajax_request()) {
            $where = [
                'id_bukti_trf' => $this->input->post('id'),
            ];
            $reject = $this->transaksi->updateBuktiPembayaran($where, ['status' => 2]);
            $data = [
                'status'    => true,
                'code'      => 200,
                'msg'       => 'OK!',
                'data'      => $reject
            ];
        } else {
            $data = [
                'status'    => false,
                'code'      => 500,
                'msg'       => 'Invalid Request!',
                'data'      => null
            ];
        }
        echo json_encode($data);
    }

    public function get_jenis_pembayaran()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post('jns_bayar');
            $jnsBayar = explode(',', $data_post);
            foreach ($jnsBayar as $i => $val) {
                $where = [
                    'id_jenis_pembayaran' => $val
                ];
                $res[] = $this->masterdata->GetAllJenisTrx($where)->row_array();
            }
            $data = [
                'status'    => true,
                'code'      => 200,
                'msg'       => 'Ok!',
                'data'      => $res
            ];
        } else {
            $data = [
                'status'    => false,
                'code'      => 500,
                'msg'       => 'Invalid Request!',
                'data'      => null
            ];
        }
        echo json_encode($data);
    }

    public function get_data_rekening()
    {
        if ($this->input->is_ajax_request()) {
            $get_rek = $this->transaksi->get_data_rekening()->result_array();
            $data = [
                'status'    => true,
                'code'      => 200,
                'msg'       => 'Ok!',
                'data'      => $get_rek
            ];
        } else {
            $data = [
                'status'    => false,
                'code'      => 500,
                'msg'       => 'Invalid Request!',
                'data'      => null
            ];
        }
        echo json_encode($data);
    }


    public function getDataTrxServerSide()
    {
        $list = $this->transaksi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $dataTrx) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $dataTrx->id_transaksi;
            $row[] = $dataTrx->tanggal;
            $row[] = $dataTrx->jam;
            $row[] = $dataTrx->semester;
            $row[] = $dataTrx->nim;
            $row[] = $dataTrx->user_id;
            $row[] = $dataTrx->status_transaksi;
            $row[] = $dataTrx->transaksi_ke;
            $row[] = $dataTrx->nm_pd;
            $row[] = $dataTrx->nm_jur;
            $row[] = $dataTrx->nm_jenj_didik;
            $row[] = $dataTrx->icon_status_tx;
            $row[] = $dataTrx->nama_user;
            $row[] = $dataTrx->ttd;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->transaksi->count_all(),
            "recordsFiltered" => $this->transaksi->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function getDataTransaksi()
    {
        // code here...
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            $input = $this->input->post('data');

            if ($input != null) {
                if ($input === '1') {
                    $where = [
                        't.semester <=' => $smtAktif + 1
                    ];
                } else {
                    $where = [
                        't.id_transaksi' => $input
                    ];
                }

                $dataHistoriTx = $this->transaksi->getDataTransaksi($where)->result_array();
                $countHistoriTx = count($dataHistoriTx);
                for ($i = 0; $i < $countHistoriTx; $i++) {

                    $jenjangMhs = $dataHistoriTx[$i]['nm_jenj_didik'];
                    $dataHistoriTx[$i]['angkatan_mhs'] = '20' . substr($dataHistoriTx[$i]['nim'], 0, 2);
                    $where_tahun = [
                        'angkatan' => $dataHistoriTx[$i]['angkatan_mhs']
                    ];
                    $dataBiayaAngkatan = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
                    // var_dump($dataBiayaAngkatan);
                    // die;
                    $kewajiban_Semester_ini = 0;
                    $biayaCS = $dataBiayaAngkatan['cicilan_semester'];
                    $biayaKMHS = $dataBiayaAngkatan['kemahasiswaan'];
                    $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                    $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
                    foreach ($resDetailTx as $dTX) {
                        if ($dTX['id_jenis_pembayaran'] == 8) {
                            $kewajiban_Semester_ini = ($biayaCS / 2);
                        } else {
                            $kewajiban_Semester_ini = $biayaCS + $biayaKMHS;
                        }
                    }

                    $dataHistoriTx[$i]['kewajiban_Semester_ini'] = $kewajiban_Semester_ini;
                }
            } else {
                $dataHistoriTx = $this->transaksi->getDataTransaksi()->result_array();
                $countHistoriTx = count($dataHistoriTx);
                for ($i = 0; $i < $countHistoriTx; $i++) {
                    $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                    $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
                }
                // $data['data_transaksi'] = $dataHistoriTx;
                // $data['user_loged'] = $this->session->userdata('id_user');
            }

            $data['data_transaksi'] = $dataHistoriTx;
            $data['user_loged'] = $this->session->userdata('id_user');
            echo json_encode($data);
        } else {
            echo "Error";
        }
    }

    public function getDataForRekap()
    {
        if ($this->input->is_ajax_request()) {
            $res_bulan = $this->transaksi->getMonthTX()->result_array();
            // $data['data_bulan'] = $res_bulan;
            foreach ($res_bulan as $i => $val) {
                $kondisi = ['SUBSTRING(t.tanggal, 1, 7)=' => $val['bulan_tx']];
                $tx_perbulan[] = $this->transaksi->getTxPerMonth($kondisi)->result_array();
            }
            $data['tx_perbulan'] = $tx_perbulan;
            $kondisi = 'id_jenis_pembayaran <> 1 AND id_jenis_pembayaran <> 6 AND id_jenis_pembayaran <> 7 AND id_jenis_pembayaran <> 8';
            $data['pembayaran'] = $this->masterdata->GetJenisPembayaran($kondisi)->result_array();
            echo json_encode($data);
        } else {
            echo 'invalid request!';
        }
    }


    // Function Cari Data Mahasiswa
    public function Cari_Mhs()
    {
        if ($this->input->is_ajax_request()) {
            $nim = $this->input->post('nipd');
            $response = $this->masterdata->getMahasiswaByNim(['nipd' => $nim])->row_array();
            $dataMhs = $response;

            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            $smtAktif = $smtAktifRes['id_smt'];
            $cekTahunSmt = substr($smtAktif, 0, 4);
            $smt_jns = substr($smtAktif, 4, 1);


            // cek tunggakan
            $dataCekTG = [
                'nim' => $nim
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->result_array();
            $TG_CS = 0;
            if ($dataTG != null) {
                foreach ($dataTG as $i => $val) {
                    if ($val['jenis_tunggakan'] == '6') {
                        $TG_CS = $val['jml_tunggakan'];
                    }
                }
            }

            $numb = '7';
            if ((int)$TG_CS > 0) {
                $numb = '5';
            }

            if ($dataMhs['nm_jenj_didik'] == 'S1') {
                $where = [
                    'id_jenis_pembayaran >' => $numb,
                    'id_jenis_pembayaran <>' => '18',
                    // 'id_jenis_pembayaran <>' => '19'
                ];
            } else {
                $where = [
                    'id_jenis_pembayaran >' => $numb,
                    'id_jenis_pembayaran <>' => '13',
                    // 'id_jenis_pembayaran <>' => '14'
                ];
            }
            $resJnsPembayaran = $this->masterdata->GetJenisPembayaran($where, $dataMhs['nm_jenj_didik'])->result_array();

            if ($dataMhs != null) {

                $dataCekNim = [
                    'nim' => $nim,
                    'semester' => $smtAktif
                ];
                // cek histori transaksi
                $dataHistoriTx = $this->transaksi->getDataTransaksi($dataCekNim)->result_array();
                $countHistoriTx = count($dataHistoriTx);
                for ($i = 0; $i < $countHistoriTx; $i++) {
                    $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                    $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
                }
                $dataMhs['tg_CS'] = $TG_CS;
                $dataMhs['data_tg'] = $dataTG;
                $dataMhs['dataHistoriTX'] = $dataHistoriTx;
                $dataMhs['jenis_pembayaran'] = $resJnsPembayaran;
                $dataMhs['thn_smt'] = $cekTahunSmt;
                $dataMhs['jns_smt'] = $smt_jns;
            } else {
                $dataMhs;
            }
            echo json_encode($dataMhs);
        } else {
            echo "Error";
        }
    }

    public function Cari_Pembayaran_lain()
    {
        if ($this->input->is_ajax_request()) {
            $dataMhs['nm_jenj_didik'] = $this->input->post('nm_jenj_didik');
            $tg_CS = $this->input->post('tg_cs');
            $numb = '7';
            if ((int)$tg_CS > 0) {
                $numb = '5';
            }

            if ($dataMhs['nm_jenj_didik'] == 'S1') {
                $where = [
                    'id_jenis_pembayaran >' => $numb,
                    'id_jenis_pembayaran <>' => '18',
                    // 'id_jenis_pembayaran <>' => '19'
                ];
            } else {
                $where = [
                    'id_jenis_pembayaran >' => $numb,
                    'id_jenis_pembayaran <>' => '13',
                    // 'id_jenis_pembayaran <>' => '14'
                ];
            }
            $resJnsPembayaran = $this->masterdata->GetJenisPembayaran($where, $dataMhs['nm_jenj_didik'])->result_array();

            $dataMhs['jenis_pembayaran'] = $resJnsPembayaran;
            echo json_encode($dataMhs);
        } else {
            echo "Error";
        }
    }


    public function Cek_Pembayaran_SPP()
    {
        if ($this->input->is_ajax_request()) {
            // data input post
            $nim = $this->input->post('nipd');
            $smtBayar = $this->input->post('smt');
            $cek_smt = substr($smtBayar, 4);
            // get data mahasiswa
            $response = $this->masterdata->getMahasiswaByNim(['nipd' => $nim])->row_array();
            $dataMhs = $response;

            $dataKewajiban = [];


            if ($dataMhs != null) {
                $jenjang = $dataMhs['nm_jenj_didik'];
                $where_tahun = [
                    'angkatan' => $dataMhs['tahun_masuk']
                ];

                // cek tunggakan CS
                $dataCekTG = [
                    'nim' => $nim,
                    'jenis_tunggakan' => '6'
                ];
                $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
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

                // cek tunggakan KMHS
                $dataCekTGKMHS = [
                    'nim' => $nim,
                    'jenis_tunggakan' => '7'
                ];
                $dataTG_KMHS = $this->tunggakan->getTunggakanMhs($dataCekTGKMHS)->row_array();
                if ($dataTG_KMHS != null) {
                    $dataKewajiban[] = [
                        'post_id' => 'bayar_TG_KMHS',
                        'label' => 'TG Kmhs',
                        'biaya' => $dataTG_KMHS['jml_tunggakan']
                    ];
                } else {
                    $dataKewajiban[] = [
                        'post_id' => 'bayar_TG_KMHS',
                        'label' => 'TG Kmhs',
                        'biaya' => 0
                    ];
                }

                $dataCekNim = [
                    'nim' => $nim,
                    'semester' => $smtBayar
                ];

                // cek histori transaksi
                $dataHistoriTx = $this->transaksi->getDataTransaksi($dataCekNim)->result_array();
                $countHistoriTx = count($dataHistoriTx);
                for ($i = 0; $i < $countHistoriTx; $i++) {
                    $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                    $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
                }
                // var_dump($dataHistoriTx);
                // die;
                $cekBayarSppdanKmhs = $this->transaksi->cekBayarSppdanKmhs($dataCekNim)->row_array();
                $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjang)->row_array();
                // $allJnsPembayaran = $this->masterdata->GetAllJenisPembayaran()->result_array();
                // var_dump($cekBayarSppdanKmhs);
                // die;

                $biayaCS = $dataBiaya['cicilan_semester'] / 3;
                $biayaKMHS = (int)$dataBiaya['kemahasiswaan'];
                if ($cekBayarSppdanKmhs != null) {
                    // ada histori transaksi
                    $biayaC1 = $biayaCS;
                    $biayaC2 = $biayaCS;
                    $biayaC3 = $biayaCS;

                    foreach ($dataHistoriTx as $a => $val) {
                        foreach ($val['detail_transaksi'] as $b => $dDetail) {
                            if ($dDetail['id_jenis_pembayaran'] == 2) {
                                $biayaC1 = $biayaC1 - $dDetail['jml_bayar'];
                            }
                            if ($dDetail['id_jenis_pembayaran'] == 3) {
                                $biayaC2 = $biayaC2 - $dDetail['jml_bayar'];
                            }
                            if ($dDetail['id_jenis_pembayaran'] == 4) {
                                $biayaC3 = $biayaC3 - $dDetail['jml_bayar'];
                            }
                            if ($dDetail['id_jenis_pembayaran'] == 5) {
                                $biayaKMHS = $biayaKMHS - $dDetail['jml_bayar'];
                            }
                        }
                    }


                    $C1 = [
                        'post_id' => 'bayar_C1',
                        'label' => 'Cicilan Ke-1',
                        'biaya' => $biayaC1
                    ];
                    $C2 = [
                        'post_id' => 'bayar_C2',
                        'label' => 'Cicilan Ke-2',
                        'biaya' => $biayaC2
                    ];
                    $C3 = [
                        'post_id' => 'bayar_C3',
                        'label' => 'Cicilan Ke-3',
                        'biaya' => $biayaC3
                    ];
                    $kmhs = [
                        'post_id' => 'bayar_kmhs',
                        'label' => 'Kemahasiswaan',
                        'biaya' => $biayaKMHS
                    ];
                } else {
                    // belum ada histori transaksi

                    $kmhs = [
                        'post_id' => 'bayar_kmhs',
                        'label' => 'Kemahasiswaan',
                        'biaya' => $biayaKMHS
                    ];

                    $C1 = [
                        'post_id' => 'bayar_C1',
                        'label' => 'Cicilan Ke-1',
                        'biaya' => $biayaCS
                    ];
                    $C2 = [
                        'post_id' => 'bayar_C2',
                        'label' => 'Cicilan Ke-2',
                        'biaya' => $biayaCS
                    ];
                    $C3 = [
                        'post_id' => 'bayar_C3',
                        'label' => 'Cicilan Ke-3',
                        'biaya' => $biayaCS
                    ];
                }
                $dataKewajiban[] = $kmhs;
                $dataKewajiban[] = $C1;
                $dataKewajiban[] = $C2;
                $dataKewajiban[] = $C3;

                $countTotal = $dataKewajiban[0]['biaya'] + $dataKewajiban[1]['biaya'] + $dataKewajiban[2]['biaya'] + $dataKewajiban[3]['biaya'] + $dataKewajiban[4]['biaya'];
                $dataMhs['totalKewajiban'] = $countTotal;
                $dataMhs['dataKewajibanSmt'] = $dataKewajiban;
            } else {
                $dataMhs;
            }
            echo json_encode($dataMhs);
        } else {
            echo "Error";
        }
    }


    public function Proses_Bayar_Spp()
    {
        if ($this->input->is_ajax_request()) {
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            $smtAktif = $smtAktifRes['id_smt'];
            $dataTxDetail = [];
            // get post()
            $nimMhs = $this->input->post('nim_mhs_bayar');
            $namaMhs = $this->input->post('nama_mhs_bayar');
            $jenjangMhs = $this->input->post('jenjang_mhs_bayar');
            $angkatanMhs = $this->input->post('angkatan_mhs_bayar');
            $smtBayar = $this->input->post('smt');
            // =========== Data Pembayaran ========================
            $bayarTG = $this->input->post('bayar_TG');
            $bayarTG_KMHS = $this->input->post('bayar_TG_KMHS');
            // $bayarUB = $this->input->post('bayar_UB');
            $bayarKMHS = $this->input->post('bayar_kmhs');
            $bayarC1 = $this->input->post('bayar_C1');
            $bayarC2 = $this->input->post('bayar_C2');
            $bayarC3 = $this->input->post('bayar_C3');
            $uang_masuk = $this->input->post('uang_masuk');

            $bayar_via = $this->input->post('bayar_via');
            $rekening_trf = '';
            $tgl_trf = '0000-00-00';
            $jam_trf = '00:00:00';
            if ($bayar_via == 2) {
                $rekening_trf = $this->input->post('rek_tujuan');
                $tgl_trf = $this->input->post('tgl_trf');
                $jam_trf = $this->input->post('jam_trf') . ':00';

                // validasi data bukti transfer mahasiswa

                $id_bukti_trf = $this->input->post('id_konfirm_trf');
                if (!empty($id_bukti_trf)) {
                    $where = [
                        'id_bukti_trf' => $id_bukti_trf
                    ];
                    $update = $this->transaksi->updateBuktiPembayaran($where, ['status' => 1]);
                    if ($update) {
                        $res = $this->transaksi->getDataBuktiPembayaran($where)->row_array();
                        if ($res != null) {
                            $data_insert = [
                                'id_bukti_trf' => $res['id_bukti_trf'],
                                'smt' => $res['smt'],
                                'jenis_bayar' => $res['jenis_bayar'],
                                'tgl_trf' => $res['tgl_trf'],
                                'jam_trf' => $res['jam_trf'],
                                'rek_tujuan_trf' => $res['rek_tujuan_trf'],
                                'tgl_konfir' => $res['tgl_konfir'],
                                'jam_konfir' => $res['jam_konfir'],
                                'nipd' => $res['nipd'],
                                'id_jenis_bayar' => $res['id_jenis_bayar'],
                                'jumlah_bayar' => $res['jumlah_bayar'],
                                'img_trf' => $res['img_trf'],
                                'status' => $res['status'],
                            ];
                            $this->transaksi->insertBuktiTrfToSiskeu($data_insert);
                        }
                    }
                }
            }
            $totalBayar = $bayarTG + $bayarTG_KMHS + $bayarC1 + $bayarC2 + $bayarC3 + $bayarKMHS;
            // $All = $this->input->post();
            // var_dump($All);
            // die;
            $where_tahun = [
                'angkatan' => $angkatanMhs
            ];
            $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
            $biayaCS = $dataBiaya['cicilan_semester'] / 3;
            $biayaKMHS = $dataBiaya['kemahasiswaan'];

            //=============== cek data transaksi =================
            // cek histori transaksi
            $dataCek = [
                'nim' => $nimMhs,
                'semester' => $smtBayar
            ];
            $cekBayarSppdanKmhs = $this->transaksi->cekBayarSppdanKmhs($dataCek)->row_array();

            $dataHistoriTx = $this->transaksi->getDataTransaksi($dataCek)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            $trx_ke = $countHistoriTx + 1;
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
            }

            $kewajibanC1 = $biayaCS;
            $kewajibanC2 = $biayaCS;
            $kewajibanC3 = $biayaCS;
            $kewajibanKMHS = $biayaKMHS;

            foreach ($dataHistoriTx as $a => $val) {
                foreach ($val['detail_transaksi'] as $b => $dDetail) {
                    if ($dDetail['id_jenis_pembayaran'] == 2) {
                        $kewajibanC1 = $kewajibanC1 - $dDetail['jml_bayar'];
                    }
                    if ($dDetail['id_jenis_pembayaran'] == 3) {
                        $kewajibanC2 = $kewajibanC2 - $dDetail['jml_bayar'];
                    }
                    if ($dDetail['id_jenis_pembayaran'] == 4) {
                        $kewajibanC3 = $kewajibanC3 - $dDetail['jml_bayar'];
                    }
                    if ($dDetail['id_jenis_pembayaran'] == 5) {
                        $kewajibanKMHS = $kewajibanKMHS - $dDetail['jml_bayar'];
                    }
                }
            }



            $sisa_BayarC1 = $kewajibanC1 - $bayarC1;
            $sisa_BayarC2 = $kewajibanC2 - $bayarC2;
            $sisa_BayarC3 = $kewajibanC3 - $bayarC3;
            $sisa_BayarKMHS = $kewajibanKMHS - $bayarKMHS;
            // var_dump($sisa_BayarC1, $sisa_BayarC2, $sisa_BayarC3);
            // die;


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
            // ambil data tunggakan CS
            $whereCekNim = [
                'nim' => $nimMhs,
                'jenis_tunggakan' => 6
            ];
            $dataTG_CS = $this->tunggakan->getTunggakanMhs($whereCekNim)->row_array();
            if ($bayarTG != null) {
                $dataTGBaru = 0;
                if ($dataTG_CS != null) {
                    $id_TGCS = [
                        'id_tunggakan' => $dataTG_CS['id_tunggakan']
                    ];
                    $dataTGBaru = $dataTG_CS['jml_tunggakan'] - $bayarTG;
                }
                // var_dump($dataTGBaru);
                // die;
                if ($dataTGBaru != 0) {
                    // update data tunggakan
                    $dataUpdateTG = [
                        'jml_tunggakan' => $dataTGBaru
                    ];
                    $this->tunggakan->updateTunggakan($id_TGCS, $dataUpdateTG);
                } else {
                    // hapus data tunggakan
                    $this->tunggakan->deleteTunggakan($id_TGCS);
                }
                // add transaksi
                $dataTxDetail[] = [
                    'id_transaksi' => $id_transaksi,
                    'id_jenis_pembayaran' => 6,
                    'jml_bayar' => $bayarTG,
                    'potongan' => 0
                ];
            }



            // ambil data tunggakan KMHS
            $CekTGKMHS = [
                'nim' => $nimMhs,
                'jenis_tunggakan' => 7
            ];
            $dataTG_KMHS = $this->tunggakan->getTunggakanMhs($CekTGKMHS)->row_array();
            if ($bayarTG_KMHS != null) {
                $dataTGKMHSBaru = 0;
                if ($dataTG_KMHS != null) {
                    $id_tgKMHS = [
                        'id_tunggakan' => $dataTG_KMHS['id_tunggakan']
                    ];
                    $dataTGKMHSBaru = $dataTG_KMHS['jml_tunggakan'] - $bayarTG_KMHS;
                }
                if ($dataTGKMHSBaru != 0) {
                    // update data tunggakan
                    $dataUpdateTGKMHS = [
                        'jml_tunggakan' => $dataTGKMHSBaru
                    ];
                    $this->tunggakan->updateTunggakan($id_tgKMHS, $dataUpdateTGKMHS);
                } else {
                    // hapus data tunggakan
                    $this->tunggakan->deleteTunggakan($id_tgKMHS);
                }
                // add transaksi
                $dataTxDetail[] = [
                    'id_transaksi' => $id_transaksi,
                    'id_jenis_pembayaran' => 7,
                    'jml_bayar' => $bayarTG_KMHS,
                    'potongan' => 0
                ];
            }

            // insert reg_mhs dan reg_ujian
            $dataAktifKRS = [
                'Tahun' => $smtBayar,
                'Identitas_ID' => '',
                'Jurusan_ID' => '',
                'NIM' => $nimMhs,
                'tgl_reg' => $tgl,
                'aktif' => 2,
                'keterangan' => 'from siskeu_new',
                'aktif_by' => 0
            ];
            $dataAktifUTS = [
                'tahun' => $smtBayar,
                'nim' => $nimMhs,
                'tgl_reg' => $tgl,
                'aktif' => 1,
                'keterangan' => 'from siskeu_new',
                'aktif_by' => 0
            ];
            $dataAktifUAS = [
                'tahun' => $smtBayar,
                'nim' => $nimMhs,
                'tgl_reg' => $tgl,
                'aktif' => 2,
                'keterangan' => 'from siskeu_new',
                'aktif_by' => 0
            ];

            // ===============================  Fungsi aktifasi perwalian dan ujian ==============
            if ($sisa_BayarC1 < 500000 && $sisa_BayarC2 < 500000 && $sisa_BayarC3 < 500000) {
                // Aktifasi Perwalian, UTS, UAS
                $this->aktivasi->aktivasi_perwalian($dataAktifKRS);
                $this->aktivasi->aktivasi_ujian($dataAktifUTS);
                $this->aktivasi->aktivasi_ujian($dataAktifUAS);
            } else if ($sisa_BayarC1 < 500000 && $sisa_BayarC2 < 500000) {
                // Aktifasi Perwalian, UTS
                $this->aktivasi->aktivasi_perwalian($dataAktifKRS);
                $this->aktivasi->aktivasi_ujian($dataAktifUTS);
            } else if ($sisa_BayarC1 < 500000) {
                // Aktifasi Perwalian
                $this->aktivasi->aktivasi_perwalian($dataAktifKRS);
            } else {
                if ($sisa_BayarC2 < 500000) {
                    // Aktifasi UTS
                    $this->aktivasi->aktivasi_ujian($dataAktifUTS);
                } else if ($sisa_BayarC3 < 500000) {
                    // Aktifasi UAS
                    $this->aktivasi->aktivasi_ujian($dataAktifUAS);
                }
            }


            if ($cekBayarSppdanKmhs['id_transaksi'] != null) {
                // ada data transaksi
                if ($bayarKMHS != null) {
                    // bayar full
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 5,
                        'jml_bayar' => $bayarKMHS,
                        'potongan' => 0
                    ];
                }
                if ($bayarC1 != null) {
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 2,
                        'jml_bayar' => $bayarC1,
                        'potongan' => 0
                    ];

                    // cek dispen
                    $dataCekDispen = [
                        'd.tahun_akademik' => $smtAktif,
                        'm.nipd' => $nimMhs,
                        'd.jenis_dispen' => 1
                    ];
                    $dispen_C1 = $this->aktivasi->getDataDispenMhs($dataCekDispen)->row_array();
                    if ($dispen_C1 != null) {
                        $id_dispen = $dispen_C1['id_dispensasi'];
                        $dataUpdateDispen = [
                            'status' => 1,
                            'tgl_pelunasan' => $tgl
                        ];
                        $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdateDispen);
                    }
                }

                /*
            * C2
            */
                if ($bayarC2 != null) {
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 3,
                        'jml_bayar' => $bayarC2,
                        'potongan' => 0
                    ];

                    // cek dispen
                    $dataCekDispen = [
                        'd.tahun_akademik' => $smtBayar,
                        'm.nipd' => $nimMhs,
                        'd.jenis_dispen' => 3
                    ];
                    $dispen_C2 = $this->aktivasi->getDataDispenMhs($dataCekDispen)->row_array();
                    if ($dispen_C2 != null) {
                        $id_dispen = $dispen_C2['id_dispensasi'];
                        $dataUpdateDispen = [
                            'status' => 1,
                            'tgl_pelunasan' => $tgl
                        ];
                        $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdateDispen);
                    }
                }

                /*
            * C3
            */
                if ($bayarC3 != null) {
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 4,
                        'jml_bayar' => $bayarC3,
                        'potongan' => 0
                    ];

                    // cek dispen
                    $dataCekDispen = [
                        'd.tahun_akademik' => $smtBayar,
                        'm.nipd' => $nimMhs,
                        'd.jenis_dispen' => 4
                    ];
                    $dispen_C3 = $this->aktivasi->getDataDispenMhs($dataCekDispen)->row_array();
                    if ($dispen_C3 != null) {
                        $id_dispen = $dispen_C3['id_dispensasi'];
                        $dataUpdateDispen = [
                            'status' => 1,
                            'tgl_pelunasan' => $tgl
                        ];
                        $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdateDispen);
                    }
                }
            } else {
                // tidak ada data transaksi
                if ($bayarKMHS != null) {
                    // bayar full
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 5,
                        'jml_bayar' => $bayarKMHS,
                        'potongan' => 0
                    ];
                }

                if ($bayarC1 != null) {
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 2,
                        'jml_bayar' => $bayarC1,
                        'potongan' => 0
                    ];

                    // cek dispen
                    $dataCekDispen = [
                        'd.tahun_akademik' => $smtBayar,
                        'm.nipd' => $nimMhs,
                        'd.jenis_dispen' => 1
                    ];
                    $dispen_C1 = $this->aktivasi->getDataDispenMhs($dataCekDispen)->row_array();
                    if ($dispen_C1 != null) {
                        $id_dispen = $dispen_C1['id_dispensasi'];
                        $dataUpdateDispen = [
                            'status' => 1,
                            'tgl_pelunasan' => $tgl
                        ];
                        $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdateDispen);
                    }
                }

                /*
            * C2
            */
                if ($bayarC2 != null) {
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 3,
                        'jml_bayar' => $bayarC2,
                        'potongan' => 0
                    ];

                    // cek dispen
                    $dataCekDispen = [
                        'd.tahun_akademik' => $smtBayar,
                        'm.nipd' => $nimMhs,
                        'd.jenis_dispen' => 3
                    ];
                    $dispen_C2 = $this->aktivasi->getDataDispenMhs($dataCekDispen)->row_array();
                    if ($dispen_C2 != null) {
                        $id_dispen = $dispen_C2['id_dispensasi'];
                        $dataUpdateDispen = [
                            'status' => 1,
                            'tgl_pelunasan' => $tgl
                        ];
                        $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdateDispen);
                    }
                }

                /*
            * C3
            */
                if ($bayarC3 != null) {
                    $dataTxDetail[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => 4,
                        'jml_bayar' => $bayarC3,
                        'potongan' => 0
                    ];

                    // cek dispen
                    $dataCekDispen = [
                        'd.tahun_akademik' => $smtBayar,
                        'm.nipd' => $nimMhs,
                        'd.jenis_dispen' => 4
                    ];
                    $dispen_C3 = $this->aktivasi->getDataDispenMhs($dataCekDispen)->row_array();
                    if ($dispen_C3 != null) {
                        $id_dispen = $dispen_C3['id_dispensasi'];
                        $dataUpdateDispen = [
                            'status' => 1,
                            'tgl_pelunasan' => $tgl
                        ];
                        $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdateDispen);
                    }
                }
            }



            $dataInsertTx = [
                'id_transaksi' => $id_transaksi,
                'tanggal' => $tgl,
                'jam' => $jam,
                'semester' => $smtBayar,
                'nim' => $nimMhs,
                // 'total_bayar' => $totalBayar,
                'user_id' => $this->session->userdata('id_user'),
                'status_transaksi' => 1,
                'transaksi_ke' => $trx_ke,
                'uang_masuk' => $uang_masuk,
                'bayar_via' => $bayar_via,
                'rekening_trf' => $rekening_trf,
                'tgl_trf' => $tgl_trf,
                'jam_trf' => $jam_trf
            ];
            $insertTx = $this->transaksi->addNewTransaksi($dataInsertTx);
            // $insert = true;
            if (!$insertTx) {
                $response = 'gagal insert transaksi!';
            } else {
                $inputDetailTx = count($dataTxDetail);
                for ($i = 0; $i < $inputDetailTx; $i++) {
                    $this->transaksi->addNewDetailTransaksi($dataTxDetail[$i]);
                }
                $response = $id_transaksi;
            }
            if ($uang_masuk == 1) {
                echo json_encode($response);
            } else {
                $data = 0;
                echo json_encode($data);
            }
        } else {
            echo "Invalid request!";
        }
    }

    public function session_msg()
    {
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil Input Potongan!</div>');
        redirect('transaksi');
    }

    public function get_biaya_pembayaran_lain()
    {
        if ($this->input->is_ajax_request()) {
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            $smtAktif = $smtAktifRes['id_smt'];
            $nim = $this->input->post('nim_mhs');
            $id_pembayaran =  $this->input->post('id_jns_bayar');
            $jenjangMhs =  $this->input->post('jnj_didik');
            $angkatanMhs =  $this->input->post('thn_masuk');

            $cekTG = [
                'nim' => $nim,
                'jenis_tunggakan' => $id_pembayaran
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($cekTG)->row_array();
            if ($id_pembayaran != null) {
                $where_tahun = [
                    'angkatan' => $angkatanMhs
                ];
                $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
                if ($id_pembayaran == 6) {
                    $data = [
                        'id_jp' => $dataTG['jenis_tunggakan'],
                        'nm_jp' => $dataTG['nm_jenis_pembayaran'],
                        'biaya' => $dataTG['jml_tunggakan'],
                        // 'potongan' => 0
                    ];
                } else if ($id_pembayaran == 8) {
                    $biayaPerpanjang = $dataBiaya['cicilan_semester'] / 2;
                    $where = [
                        'id_jenis_pembayaran' => $id_pembayaran
                    ];
                    $resJnsPembayaran = $this->masterdata->GetJenisPembayaran($where)->row_array();
                    $where_sudah_bayar = [
                        'nim' => $nim,
                        'semester' => $smtAktif,
                        'id_jenis_pembayaran' => $id_pembayaran
                    ];
                    $sudah_bayar = $this->masterdata->GetPembayaranPS($where_sudah_bayar)->result_array();
                    $sumJ = 0;
                    foreach ($sudah_bayar as $i => $val) {
                        $sumJ += $val['jml_bayar'];
                    }
                    $data = [
                        'id_jp' => $id_pembayaran,
                        'nm_jp' => $resJnsPembayaran['nm_jp'],
                        'biaya' => $biayaPerpanjang - $sumJ,
                        'tambahan' => ($dataBiaya['cicilan_semester'] / 2)
                    ];
                } else if ($id_pembayaran == 9) {
                    $where = [
                        'id_jenis_pembayaran' => $id_pembayaran
                    ];
                    $resJnsPembayaran = $this->masterdata->GetJenisPembayaran($where)->row_array();
                    $data = [
                        'id_jp' => $id_pembayaran,
                        'nm_jp' => $resJnsPembayaran['nm_jp'],
                        'biaya' => $dataBiaya['uang_bangunan']
                    ];
                } else {
                    $data = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $id_pembayaran])->row_array();
                }
            } else {
                $data = $this->masterdata->getBiayaPembayaranLain()->result_array();
            }
        } else {
            $data = false;
        }
        echo json_encode($data);
    }

    public function proses_bayar_lainnya()
    {
        if ($this->input->is_ajax_request()) {
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            $smtAktif = $smtAktifRes['id_smt'];

            $dateNow = date('Y-m-d H:i:s');
            $pecah_tgl_waktu = explode(' ', $dateNow);
            $tgl = $pecah_tgl_waktu[0];
            $jam = $pecah_tgl_waktu[1];
            $pecah_tgl = explode('-', $tgl);
            $tahunBerjalan = $pecah_tgl[0];
            $blnBerjalan = $pecah_tgl[1];
            // $tglBerjalan = $pecah_tgl[2];

            // ========================= Data Input ==========================
            $nimMhs = $this->input->post('nim_mhs_bayar_hidden');
            $namaMhs = $this->input->post('nama_mhs_bayar_hidden');
            $jenjangMhs = $this->input->post('jenjang_mhs_bayar_hidden');
            $angkatanMhs = $this->input->post('angkatan_mhs_bayar_hidden');

            $bayar_via = $this->input->post('bayar_via');
            $rekening_trf = '';
            $tgl_trf = '0000-00-00';
            $jam_trf = '00:00:00';
            if ($bayar_via == 2) {
                $rekening_trf = $this->input->post('rek_tujuan');
                $tgl_trf = $this->input->post('tgl_trf');
                $jam_trf = $this->input->post('jam_trf') . ':00';

                // validasi data bukti transfer mahasiswa
                $id_bukti_trf = $this->input->post('id_konfirm_trf');
                if (!empty($id_bukti_trf)) {
                    $where = [
                        'id_bukti_trf' => $id_bukti_trf
                    ];
                    $update = $this->transaksi->updateBuktiPembayaran($where, ['status' => 1]);
                    if ($update) {
                        $res = $this->transaksi->getDataBuktiPembayaran($where)->row_array();
                        if ($res != null) {
                            $data_insert = [
                                'id_bukti_trf' => $res['id_bukti_trf'],
                                'smt' => $res['smt'],
                                'jenis_bayar' => $res['jenis_bayar'],
                                'tgl_trf' => $res['tgl_trf'],
                                'jam_trf' => $res['jam_trf'],
                                'rek_tujuan_trf' => $res['rek_tujuan_trf'],
                                'tgl_konfir' => $res['tgl_konfir'],
                                'jam_konfir' => $res['jam_konfir'],
                                'nipd' => $res['nipd'],
                                'id_jenis_bayar' => $res['id_jenis_bayar'],
                                'jumlah_bayar' => $res['jumlah_bayar'],
                                'img_trf' => $res['img_trf'],
                                'status' => $res['status'],
                            ];
                            $this->transaksi->insertBuktiTrfToSiskeu($data_insert);
                        }
                    }
                }
            }

            $pembayaran = $this->input->post('JenisBayar');
            $dataBiayaPembayaran = $this->input->post('biayaJenisPembayaran');

            // var_dump($this->input->post());
            // die;
            $jml_bayar = count($pembayaran);
            $dataCek = [
                'nim' => $nimMhs,
                'semester' => $smtAktif
            ];
            $dataHistoriTx = $this->transaksi->getDataTransaksi($dataCek)->result_array();
            $trx_ke = count($dataHistoriTx) + 1;

            for ($i = 0; $i < $jml_bayar; $i++) {
                $biaya[] = $dataBiayaPembayaran[$pembayaran[$i]];
                // if ($dataTG != null) {
                //     $id_TG = [
                //         'id_tunggakan' => $dataTG['id_tunggakan']
                //     ];
                //     $sisaBayarTG = $dataTG['jml_tunggakan'] - $dataBiayaPembayaran[$pembayaran[$i]];
                //     if ($sisaBayarTG != 0) {
                //         // update tg
                //         $dataUpdate = [
                //             'jml_tunggakan' => $sisaBayarTG
                //         ];
                //         $this->tunggakan->updateTunggakan($id_TG, $dataUpdate);
                //     } else {
                //         // delete TG
                //         $this->tunggakan->deleteTunggakan($id_TG);
                //     }
                // } else {
                if ($pembayaran[$i] == '6') {
                    $cekTG = [
                        'nim' => $nimMhs,
                        'jenis_tunggakan' => $pembayaran[$i]
                    ];
                    $dataTG = $this->tunggakan->getTunggakanMhs($cekTG)->row_array();
                    if ($dataTG != null) {
                        $id_TG = [
                            'id_tunggakan' => $dataTG['id_tunggakan']
                        ];
                        $sisaBayarTG = $dataTG['jml_tunggakan'] - $dataBiayaPembayaran[$pembayaran[$i]];
                        if ((int)$sisaBayarTG > 0) {
                            // update tg
                            $dataUpdate = [
                                'jml_tunggakan' => $sisaBayarTG
                            ];
                            $this->tunggakan->updateTunggakan($id_TG, $dataUpdate);
                        } else {
                            // delete TG
                            $this->tunggakan->deleteTunggakan($id_TG);
                        }
                    }
                } else if ($pembayaran[$i] == '8') {
                    $cekTG = [
                        'nim' => $nimMhs,
                        'jenis_tunggakan' => $pembayaran[$i]
                    ];
                    $dataTG = $this->tunggakan->getTunggakanMhs($cekTG)->row_array();
                    if ($dataTG != null) {
                        $id_TG = [
                            'id_tunggakan' => $dataTG['id_tunggakan']
                        ];
                        $sisaBayarTG = $dataTG['jml_tunggakan'] - $dataBiayaPembayaran[$pembayaran[$i]];
                        if ((int)$sisaBayarTG > 0) {
                            // update tg
                            $dataUpdate = [
                                'jml_tunggakan' => $sisaBayarTG
                            ];
                            $this->tunggakan->updateTunggakan($id_TG, $dataUpdate);
                        } else {
                            // delete TG
                            $this->tunggakan->deleteTunggakan($id_TG);
                        }
                    } else {
                        // cek baiay perangkatan
                        $where_tahun = [
                            'angkatan' => $angkatanMhs
                        ];
                        $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
                        $biayaPerpanjangAwal = $dataBiaya['cicilan_semester'] / 2;
                        $biayaPerpanjang = (int)$biayaPerpanjangAwal / 3;
                        $where = [
                            't.nim' => $nimMhs,
                            't.semester' => $smtAktif,
                            'mjp.id_jenis_pembayaran' => $pembayaran[$i],

                        ];
                        $dataTxSebelumnya = $this->transaksi->getDataTransaksiSebelumnya($where)->result_array();
                        if (count($dataTxSebelumnya) != 0) {
                            foreach ($dataTxSebelumnya as $val) {
                                $sisabayarPerpanjang = ($biayaPerpanjang - $dataBiayaPembayaran[8]) - $val['jml_bayar'];
                            }
                        } else {
                            $sisabayarPerpanjang = $biayaPerpanjang - $dataBiayaPembayaran[8];
                        }

                        $dataAktifKRS = [
                            'Tahun' => $smtAktif,
                            'Identitas_ID' => '',
                            'Jurusan_ID' => '',
                            'NIM' => $nimMhs,
                            'tgl_reg' => $tgl,
                            'aktif' => 2,
                            'keterangan' => 'from siskeu_new',
                            'aktif_by' => 0
                        ];
                        if ((int)$sisabayarPerpanjang > 0) {
                            // add data tunggakan
                            $dataAddTG = [
                                'nim' => $nimMhs,
                                'jenis_tunggakan' => 8,
                                'jml_tunggakan' => $sisabayarPerpanjang,
                            ];
                            $this->tunggakan->addNewTunggakan($dataAddTG);
                            if ($sisabayarPerpanjang < 500000) {
                                $this->aktivasi->aktivasi_perwalian($dataAktifKRS);
                            }
                        } else {
                            // bayar lunas
                            $this->aktivasi->aktivasi_perwalian($dataAktifKRS);
                        }
                    }
                } else if ($pembayaran[$i] == '9') {
                    $cekTG = [
                        'nim' => $nimMhs,
                        'jenis_tunggakan' => $pembayaran[$i]
                    ];
                    $dataTG = $this->tunggakan->getTunggakanMhs($cekTG)->row_array();
                    if ($dataTG != null) {
                        $id_TG = [
                            'id_tunggakan' => $dataTG['id_tunggakan']
                        ];
                        $sisaBayarTG = $dataTG['jml_tunggakan'] - $dataBiayaPembayaran[$pembayaran[$i]];
                        if ((int)$sisaBayarTG > 0) {
                            // update tg
                            $dataUpdate = [
                                'jml_tunggakan' => $sisaBayarTG
                            ];
                            $this->tunggakan->updateTunggakan($id_TG, $dataUpdate);
                        } else {
                            // delete TG
                            $this->tunggakan->deleteTunggakan($id_TG);
                        }
                    } else {
                        $where_tahun = [
                            'angkatan' => $angkatanMhs
                        ];
                        $dataBiaya = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
                        $sisaBayarPK = $dataBiaya['uang_bangunan'] - $dataBiayaPembayaran[$pembayaran[$i]];
                        if ((int)$sisaBayarPK > 0) {
                            // add data tunggakan
                            $dataAddTG = [
                                'nim' => $nimMhs,
                                'jenis_tunggakan' => $pembayaran[$i],
                                'jml_tunggakan' => $sisaBayarPK,
                            ];
                            $this->tunggakan->addNewTunggakan($dataAddTG);
                        }
                    }
                } else if ($pembayaran[$i] == '17') {
                    // bayar konversi
                } else {
                    $dataBiayaLain = $this->masterdata->getBiayaPembayaranLain()->result_array();
                    foreach ($dataBiayaLain as $y => $val) {
                        if ($pembayaran[$i] === $val['id_jp']) {
                            $sisaBayar = $val['biaya'] - $dataBiayaPembayaran[$pembayaran[$i]];
                            if ($sisaBayar != 0) {
                                // add data tunggakan
                                $dataAddTG = [
                                    'nim' => $nimMhs,
                                    'jenis_tunggakan' => $pembayaran[$i],
                                    'jml_tunggakan' => $sisaBayar,
                                ];
                                $this->tunggakan->addNewTunggakan($dataAddTG);
                            }
                        }
                    }
                }
                // }
            }
            $totalBayar = array_sum($biaya);

            // ================== Fungsi genret id transaksi ====================
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
            // ================= End Fungsi genret id transaksi ==================



            $dataInsertTx = [
                'id_transaksi' => $id_transaksi,
                'tanggal' => $tgl,
                'jam' => $jam,
                'nim' => $nimMhs,
                // 'total_bayar' => $totalBayar,
                'semester' => $smtAktif,
                'user_id' => $this->session->userdata('id_user'),
                'status_transaksi' => 1,
                'transaksi_ke' => $trx_ke,
                'uang_masuk' => 1,
                'bayar_via' => $bayar_via,
                'rekening_trf' => $rekening_trf,
                'tgl_trf' => $tgl_trf,
                'jam_trf' => $jam_trf
            ];
            $insertTx = $this->transaksi->addNewTransaksi($dataInsertTx);

            if (!$insertTx) {
                $response = 'gagal insert transaksi!';
            } else {
                foreach ($pembayaran as $i => $v) {
                    $dataDetailTX = [
                        'id_transaksi' => $id_transaksi,
                        'id_jenis_pembayaran' => $v,
                        'jml_bayar' => $dataBiayaPembayaran[$v],
                        'potongan' => 0,
                        // 'sisa_bayar' => $sisaBayar
                    ];
                    $this->transaksi->addNewDetailTransaksi($dataDetailTX);
                }
                $response = $id_transaksi;
            }
            echo json_encode($response);
        } else {
            echo "Invalid request!";
        }
    }

    public function hapus_transaksi($id_transaksi)
    {
        // code here...
        // $where = ['id_transaksi' => $id_transaksi];
        $where = [
            'id_transaksi' => $id_transaksi,
        ];
        $dataTx = $this->transaksi->getDataTransaksi($where)->row_array();
        $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataTx['id_transaksi']])->result_array();
        foreach ($resDetailTx as $i => $val) {
            if ($val['id_jenis_pembayaran'] == 6) {
                $kondisi = [
                    'nim' => $dataTx['nim'],
                    'jenis_tunggakan' => '6'
                ];
                $dataTGCS = $this->tunggakan->getTunggakanMhs($kondisi)->row_array();
                if ($dataTGCS == null) {
                    // insert
                    $dataInsert = [
                        'nim' => $dataTx['nim'],
                        'jenis_tunggakan' => '6',
                        'jml_tunggakan' => $val['jml_bayar']
                    ];
                    $this->tunggakan->addNewTunggakan($dataInsert);
                } else {
                    // update
                    $id_tgCS = [
                        'id_tunggakan' => $dataTGCS['id_tunggakan']
                    ];
                    $jmlTG_CSBaru = $dataTGCS['jml_tunggakan'] + $val['jml_bayar'];
                    $dataUpdateTGCS = [
                        'jml_tunggakan' => $jmlTG_CSBaru
                    ];
                    $this->tunggakan->updateTunggakan($id_tgCS, $dataUpdateTGCS);
                }
            }

            if ($val['id_jenis_pembayaran'] == 7) {
                $kondisi = [
                    'nim' => $dataTx['nim'],
                    'jenis_tunggakan' => '7'
                ];
                $dataTGKMHS = $this->tunggakan->getTunggakanMhs($kondisi)->row_array();
                if ($dataTGKMHS == null) {
                    // insert
                    $dataInsert = [
                        'nim' => $dataTx['nim'],
                        'jenis_tunggakan' => '7',
                        'jml_tunggakan' => $val['jml_bayar']
                    ];
                    $this->tunggakan->addNewTunggakan($dataInsert);
                } else {
                    // update
                    $id_tgKMHS = [
                        'id_tunggakan' => $dataTGKMHS['id_tunggakan']
                    ];
                    $jmlTG_kmhsBaru = $dataTGKMHS['jml_tunggakan'] + $val['jml_bayar'];
                    $dataUpdateTGKMHS = [
                        'jml_tunggakan' => $jmlTG_kmhsBaru
                    ];
                    $this->tunggakan->updateTunggakan($id_tgKMHS, $dataUpdateTGKMHS);
                }
            }
        }
        // var_dump($resDetailTx);
        // die;
        $deleted = $this->transaksi->deleteTransaksi($where);
        if (!$deleted) {
            // error
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal hapus transaksi!</div>');
            redirect('transaksi');
        } else {
            $delete_detailTx = $this->transaksi->deleteTransaksiDetail($where);
            if (!$delete_detailTx) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal hapus transaksi detail!</div>');
                redirect('transaksi');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Transaksi berhasi dihapus!</div>');
                redirect('transaksi');
            }
        }
    }

    public function cetak_kwitansi($id_transaksi)
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        $bayarCS = false;
        $bayarTG_CS = false;
        $bayarLainnya = false;
        $bayarKMHS = false;
        $bayarTG_KMHS = false;

        $where = [
            'id_transaksi' => $id_transaksi,
        ];
        // ambil data tunggakan KMHS
        $dataTx = $this->transaksi->getDataTransaksi($where)->row_array();
        $tahun_bayar = substr($dataTx['semester'], 0, 4);
        $smt_bayar = substr($dataTx['semester'], 4);
        $dataTx['nm_smt'] = $tahun_bayar . '/' . ($tahun_bayar + 1) . ' S' . $smt_bayar;;
        $dataTx['smt'] = $smt_bayar;

        $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataTx['id_transaksi']])->result_array();
        $countDetailTX = count($resDetailTx);
        $jenjangMhs = $dataTx['nm_jenj_didik'];
        $dataTx['angkatan_mhs'] = '20' . substr($dataTx['nim'], 0, 2);
        $where_tahun = [
            'angkatan' => $dataTx['angkatan_mhs']
        ];
        $dataBiayaAngkatan = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
        $biayaCS = $dataBiayaAngkatan['cicilan_semester'] / 3;
        $biayaKMHS = $dataBiayaAngkatan['kemahasiswaan'];
        for ($x = 0; $x < $countDetailTX; $x++) {
            $biaya_Lainnya = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $resDetailTx[$x]['id_jenis_pembayaran']])->row_array();
            $kewajibanLain[] = isset($biaya_Lainnya['biaya']);
        }
        foreach ($resDetailTx as $i => $Dtx) {
            $resBiayaLain[] = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $Dtx['id_jenis_pembayaran']])->row_array();
            
            if($Dtx['id_jenis_pembayaran'] == 9){
                $resBiayaLain[$i]['biaya']=$dataBiayaAngkatan['uang_bangunan'];
            }
            if ($Dtx['id_jenis_pembayaran'] == 2 || $Dtx['id_jenis_pembayaran'] == 3 || $Dtx['id_jenis_pembayaran'] == 4) {
                $bayarCS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 5) {
                $bayarKMHS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 6) {
                $bayarTG_CS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 7) {
                $bayarTG_KMHS = true;
            }
            if ($resBiayaLain[$i] !== null) {
                $bayarLainnya = true;
            }
        }
        $where = [
            't.nim' => $dataTx['nim'],
            't.semester' => $dataTx['semester'],
            't.id_transaksi <' => $id_transaksi,

        ];
        $dataTxSebelumnya = $this->transaksi->getDataTransaksiSebelumnya($where)->result_array();

        // echo '<pre>';
        // var_dump($dataTxSebelumnya);
        // echo'</pre>';
        // die;
        $kewajibanCS = $dataBiayaAngkatan['cicilan_semester'];
        $kewajibanPerpanjangSemester = $kewajibanCS / 2;
        $kewajibanC1 = $biayaCS;
        $kewajibanC2 = $biayaCS;
        $kewajibanC3 = $biayaCS;
        $kewajibanKMHS = $biayaKMHS;
        $kewajibanTGCS = 0;
        $kewajibanTGKMHS = 0;


        if ($bayarCS == true) {
            // cek tunggakan CS
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                $whereXX = [
                    't.nim' => $dataTx['nim'],
                    't.semester' => $dataTx['semester'],
                    't.id_transaksi >' => $id_transaksi,

                ];
                $dataTxSetelahnya = $this->transaksi->getDataTransaksiSebelumnya($whereXX)->result_array();
                foreach ($dataTxSetelahnya as $a => $value) {
                    $kewajibanTGCS = $kewajibanTGCS + $value['jml_bayar'];
                }

                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 2) {
                    $kewajibanC1 = $kewajibanC1 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 3) {
                    $kewajibanC2 = $kewajibanC2 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 4) {
                    $kewajibanC3 = $kewajibanC3 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
            }
            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
            $dataTx['kewajiban']['cs'] = $kewajibanCS;
            $dataTx['bayar_cs'] = 1;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 2) {
                    $kewajibanC1 = $kewajibanC1 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 3) {
                    $kewajibanC2 = $kewajibanC2 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 4) {
                    $kewajibanC3 = $kewajibanC3 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 6) {
                    $kewajibanTGCS = $kewajibanTGCS - $val['jml_bayar'];
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
            $dataTx['kewajiban']['cs'] = $kewajibanCS;
            $dataTx['bayar_cs'] = 0;
            $dataTx['kewajibanCS'] = $kewajibanCS;
        }

        if ($bayarTG_CS == true) {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                $whereXX = [
                    't.nim' => $dataTx['nim'],
                    't.semester' => $dataTx['semester'],
                    't.id_transaksi >' => $id_transaksi,

                ];
                $dataTxSetelahnya = $this->transaksi->getDataTransaksiSebelumnya($whereXX)->result_array();
                foreach ($dataTxSetelahnya as $a => $value) {
                    $kewajibanTGCS = $kewajibanTGCS + $value['jml_bayar'];
                }

                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 6) {
                    $kewajibanTGCS = $kewajibanTGCS - $val['jml_bayar'];
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
        }


        
        if ($bayarTG_KMHS == true) {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '7'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGKMHS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = $kewajibanTGKMHS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            
            // echo'<pre>'; 
            // var_dump($dataTxSebelumnya);
            // echo'</pre>';
            // die;


            // foreach ($dataTxSebelumnya as $a => $val) {
            //     if ($val['id_jenis_pembayaran'] == 5) {
            //         $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
            //     }
            //     if ($val['id_jenis_pembayaran'] == 7) {
            //         $kewajibanTGKMHS = $kewajibanTGKMHS - $val['jml_bayar'];
            //     }
            // }
            $dataTx['kewajiban']['tg_kmhs'] = $kewajibanTGKMHS;
            $dataTx['bayar_tg_kmhs'] = 1;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '7'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGKMHS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = $kewajibanTGKMHS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            $dataTx['kewajiban']['tg_kmhs'] = $kewajibanTGKMHS;
            $dataTx['bayar_tg_kmhs'] = 0;
            $dataTx['dataTx'] = 0;
        }

        // var_dump( $dataTx['bayar_tg_kmhs']);die;


        if ($bayarKMHS == true) {
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 5) {
                    $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
                }
            }
            $dataTx['kewajiban']['kmhs'] = $kewajibanKMHS;
            $dataTx['bayar_kmhs'] = 1;
        } else {
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 5) {
                    $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
                }
            }
            $dataTx['kewajiban']['kmhs'] = $kewajibanKMHS;
            $dataTx['bayar_kmhs'] = 0;
        }

        // var_dump($resDetailTx);
        // die;
        if ($bayarLainnya == true) {
            foreach ($dataTxSebelumnya as $a => $value) {
                if ($value['id_jenis_pembayaran'] == 8) {
                    $kewajibanPerpanjangSemester = $kewajibanPerpanjangSemester - $value['jml_bayar'];
                } else if ($value['id_jenis_pembayaran'] == 16) {
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        $resBiayaLain[$j]['biaya'] = $resBiayaLain[$j]['biaya'] - $value['jml_bayar'];
                    }
                } else {
                    foreach ($resBiayaLain as $key => $val) {
                        if (isset($val['id_jp']) && isset($value['id_jenis_pembayaran'])) { //update 18/10/2021
                            if ($value['id_jenis_pembayaran'] == $val['id_jp']) {
                                $resBiayaLain[$key]['biaya'] = $val['biaya'] - $value['jml_bayar'];
                            } else {
                                $resBiayaLain[$key]['biaya'] = $val['biaya'];
                            }
                        }
                    }
                }
            }
            // var_dump($resBiayaLain);
            // die;

            foreach ($resDetailTx as $i => $dtx) {
                // cuti
                if ($dtx['id_jenis_pembayaran'] == '16') {
                    $resDetailTx[$i]['jml_cuti'] = $dtx['jml_bayar'] / $resBiayaLain[$i]['biaya'];
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j]['id_jp'] == '16') {
                            $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                        }
                    }
                }
                // konversi
                if ($dtx['id_jenis_pembayaran'] == '17') {
                    $resDetailTx[$i]['jml_mk'] = $dtx['jml_bayar'] / 100000;
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j]['id_jp'] == '17') {
                            $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                        }
                    }
                }
                if ($dtx['id_jenis_pembayaran'] == '6') {
                    $dataCekTG = [
                        'nim' => $dataTx['nim'],
                        'jenis_tunggakan' => '6'
                    ];
                    $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j] == null) {
                            $resBiayaLain[$j]['id_jp'] = '6';
                            $resBiayaLain[$j]['nm_jp'] = 'Tunggakan CS';
                            if ($dataTG != null) {
                                $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'] + $dataTG['jml_tunggakan'];
                            } else {
                                $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                            }
                            $resBiayaLain[$j]['potongan_biaya'] = '0';
                        }
                    }
                }
            }
            $dataTx['data_kewajiban_lain'] = $resBiayaLain;

            // var_dump($resBiayaLain);
            // die;
        } else {
            $dataTx['data_kewajiban_lain'] = null;
        }

        for ($x = 0; $x < $countDetailTX; $x++) {
            if ($resDetailTx[$x]['id_jenis_pembayaran'] == 2 || $resDetailTx[$x]['id_jenis_pembayaran'] == 3 || $resDetailTx[$x]['id_jenis_pembayaran'] == 4 || $resDetailTx[$x]['id_jenis_pembayaran'] == 5 || $resDetailTx[$x]['id_jenis_pembayaran'] == 6 || $resDetailTx[$x]['id_jenis_pembayaran'] == 7) {
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 2) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC1;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 3) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC2;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 4) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC3;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 5) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = (int)$kewajibanKMHS;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 6) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanTGCS;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 7) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = (int)$kewajibanTGKMHS;
                }
            } else {
                for ($j = 0; $j < count($resBiayaLain); $j++) {
                    if ($resDetailTx[$x]['id_jenis_pembayaran'] == 8) {
                        $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanPerpanjangSemester;
                    } else if ($resDetailTx[$x]['id_jenis_pembayaran'] == 9 && $resDetailTx[$x]['id_jenis_pembayaran'] == $dataTxSebelumnya[$x]['id_jenis_pembayaran']) {
                        if (isset($dataTxSebelumnya[$x]['id_jenis_pembayaran']) != null) {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $dataBiayaAngkatan['uang_bangunan'] - $dataTxSebelumnya[$x]['jml_bayar'];
                        } else {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $dataBiayaAngkatan['uang_bangunan'];
                        }
                    } else {
                        if ($resDetailTx[$x]['id_jenis_pembayaran'] == $resBiayaLain[$j]['id_jp']) {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $resBiayaLain[$j]['biaya'];
                        }
                    }
                }
            }
        }

        // var_dump($resBiayaLain);
        // die;

        $dataTx['detail_transaksi'] = $resDetailTx;
        $dataTx['admin_log'] = $this->user->getUser(['id_user' => $this->session->userdata('id_user')])->row_array();
        $tgl_str = '%Y-%m-%d';
        $tgl_now = time();
        $dataTx['admin_log']['tanggal_log'] = mdate($tgl_str, $tgl_now);
        $dataTx['admin_log']['ket_cetak'] = 'print_ulang';
        $data['data_transaksi'] = $dataTx;
        // echo'<pre>';
        // var_dump($dataTx);
        // echo'</pre>';
        // die;
        $this->load->view('transaksi/kwitansi_new', $data);
    }

    public function cetak_ulang_kwitansi($id_transaksi)
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        $bayarCS = false;
        $bayarTG_CS = false;
        $bayarLainnya = false;
        $bayarKMHS = false;
        $bayarTG_KMHS = false;

        $where = [
            'id_transaksi' => $id_transaksi,
        ];
        // ambil data tunggakan KMHS
        $dataTx = $this->transaksi->getDataTransaksi($where)->row_array();
        $tahun_bayar = substr($dataTx['semester'], 0, 4);
        $smt_bayar = substr($dataTx['semester'], 4);
        $dataTx['nm_smt'] = $tahun_bayar . '/' . ($tahun_bayar + 1) . ' S' . $smt_bayar;;
        $dataTx['smt'] = $smt_bayar;

        $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataTx['id_transaksi']])->result_array();
        $countDetailTX = count($resDetailTx);
        $jenjangMhs = $dataTx['nm_jenj_didik'];
        $dataTx['angkatan_mhs'] = '20' . substr($dataTx['nim'], 0, 2);
        $where_tahun = [
            'angkatan' => $dataTx['angkatan_mhs']
        ];
        $dataBiayaAngkatan = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
        $biayaCS = $dataBiayaAngkatan['cicilan_semester'] / 3;
        $biayaKMHS = $dataBiayaAngkatan['kemahasiswaan'];
        for ($x = 0; $x < $countDetailTX; $x++) {
            $biaya_Lainnya = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $resDetailTx[$x]['id_jenis_pembayaran']])->row_array();
            $kewajibanLain[] = isset($biaya_Lainnya['biaya']);
        }
        foreach ($resDetailTx as $i => $Dtx) {
            $resBiayaLain[] = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $Dtx['id_jenis_pembayaran']])->row_array();
            
            if($Dtx['id_jenis_pembayaran'] == 9){
                $resBiayaLain[$i]['biaya']=$dataBiayaAngkatan['uang_bangunan'];
            }
            if ($Dtx['id_jenis_pembayaran'] == 2 || $Dtx['id_jenis_pembayaran'] == 3 || $Dtx['id_jenis_pembayaran'] == 4) {
                $bayarCS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 5) {
                $bayarKMHS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 6) {
                $bayarTG_CS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 7) {
                $bayarTG_KMHS = true;
            }
            if ($resBiayaLain[$i] !== null) {
                $bayarLainnya = true;
            }
        }
        $where = [
            't.nim' => $dataTx['nim'],
            't.semester' => $dataTx['semester'],
            't.id_transaksi <' => $id_transaksi,

        ];
        $dataTxSebelumnya = $this->transaksi->getDataTransaksiSebelumnya($where)->result_array();

        // echo '<pre>';
        // var_dump($dataTxSebelumnya);
        // echo'</pre>';
        // die;
        $kewajibanCS = $dataBiayaAngkatan['cicilan_semester'];
        $kewajibanPerpanjangSemester = $kewajibanCS / 2;
        $kewajibanC1 = $biayaCS;
        $kewajibanC2 = $biayaCS;
        $kewajibanC3 = $biayaCS;
        $kewajibanKMHS = $biayaKMHS;
        $kewajibanTGCS = 0;
        $kewajibanTGKMHS = 0;


        if ($bayarCS == true) {
            // cek tunggakan CS
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                $whereXX = [
                    't.nim' => $dataTx['nim'],
                    't.semester' => $dataTx['semester'],
                    't.id_transaksi >' => $id_transaksi,

                ];
                $dataTxSetelahnya = $this->transaksi->getDataTransaksiSebelumnya($whereXX)->result_array();
                foreach ($dataTxSetelahnya as $a => $value) {
                    $kewajibanTGCS = $kewajibanTGCS + $value['jml_bayar'];
                }

                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 2) {
                    $kewajibanC1 = $kewajibanC1 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 3) {
                    $kewajibanC2 = $kewajibanC2 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 4) {
                    $kewajibanC3 = $kewajibanC3 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
            }
            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
            $dataTx['kewajiban']['cs'] = $kewajibanCS;
            $dataTx['bayar_cs'] = 1;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 2) {
                    $kewajibanC1 = $kewajibanC1 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 3) {
                    $kewajibanC2 = $kewajibanC2 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 4) {
                    $kewajibanC3 = $kewajibanC3 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 6) {
                    $kewajibanTGCS = $kewajibanTGCS - $val['jml_bayar'];
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
            $dataTx['kewajiban']['cs'] = $kewajibanCS;
            $dataTx['bayar_cs'] = 0;
            $dataTx['kewajibanCS'] = $kewajibanCS;
        }

        if ($bayarTG_CS == true) {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                $whereXX = [
                    't.nim' => $dataTx['nim'],
                    't.semester' => $dataTx['semester'],
                    't.id_transaksi >' => $id_transaksi,

                ];
                $dataTxSetelahnya = $this->transaksi->getDataTransaksiSebelumnya($whereXX)->result_array();
                foreach ($dataTxSetelahnya as $a => $value) {
                    $kewajibanTGCS = $kewajibanTGCS + $value['jml_bayar'];
                }

                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 6) {
                    $kewajibanTGCS = $kewajibanTGCS - $val['jml_bayar'];
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
        }


        
        if ($bayarTG_KMHS == true) {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '7'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGKMHS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = $kewajibanTGKMHS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            
            // echo'<pre>'; 
            // var_dump($dataTxSebelumnya);
            // echo'</pre>';
            // die;


            // foreach ($dataTxSebelumnya as $a => $val) {
            //     if ($val['id_jenis_pembayaran'] == 5) {
            //         $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
            //     }
            //     if ($val['id_jenis_pembayaran'] == 7) {
            //         $kewajibanTGKMHS = $kewajibanTGKMHS - $val['jml_bayar'];
            //     }
            // }
            $dataTx['kewajiban']['tg_kmhs'] = $kewajibanTGKMHS;
            $dataTx['bayar_tg_kmhs'] = 1;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '7'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGKMHS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = $kewajibanTGKMHS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            $dataTx['kewajiban']['tg_kmhs'] = $kewajibanTGKMHS;
            $dataTx['bayar_tg_kmhs'] = 0;
            $dataTx['dataTx'] = 0;
        }

        // var_dump( $dataTx['kewajiban']['tg_kmhs']);die;


        if ($bayarKMHS == true) {
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 5) {
                    $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
                }
            }
            $dataTx['kewajiban']['kmhs'] = $kewajibanKMHS;
            $dataTx['bayar_kmhs'] = 1;
        } else {
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 5) {
                    $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
                }
            }
            $dataTx['kewajiban']['kmhs'] = $kewajibanKMHS;
            $dataTx['bayar_kmhs'] = 0;
        }

        // var_dump($resDetailTx);
        // die;
        if ($bayarLainnya == true) {
            foreach ($dataTxSebelumnya as $a => $value) {
                if ($value['id_jenis_pembayaran'] == 8) {
                    $kewajibanPerpanjangSemester = $kewajibanPerpanjangSemester - $value['jml_bayar'];
                } else if ($value['id_jenis_pembayaran'] == 16) {
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        $resBiayaLain[$j]['biaya'] = $resBiayaLain[$j]['biaya'] - $value['jml_bayar'];
                    }
                } else {
                    foreach ($resBiayaLain as $key => $val) {
                        if (isset($val['id_jp']) && isset($value['id_jenis_pembayaran'])) { //update 18/10/2021
                            if ($value['id_jenis_pembayaran'] == $val['id_jp']) {
                                $resBiayaLain[$key]['biaya'] = $val['biaya'] - $value['jml_bayar'];
                            } else {
                                $resBiayaLain[$key]['biaya'] = $val['biaya'];
                            }
                        }
                    }
                }
            }
            // var_dump($resBiayaLain);
            // die;

            foreach ($resDetailTx as $i => $dtx) {
                // cuti
                if ($dtx['id_jenis_pembayaran'] == '16') {
                    $resDetailTx[$i]['jml_cuti'] = $dtx['jml_bayar'] / $resBiayaLain[$i]['biaya'];
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j]['id_jp'] == '16') {
                            $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                        }
                    }
                }
                // konversi
                if ($dtx['id_jenis_pembayaran'] == '17') {
                    $resDetailTx[$i]['jml_mk'] = $dtx['jml_bayar'] / 100000;
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j]['id_jp'] == '17') {
                            $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                        }
                    }
                }
                if ($dtx['id_jenis_pembayaran'] == '6') {
                    $dataCekTG = [
                        'nim' => $dataTx['nim'],
                        'jenis_tunggakan' => '6'
                    ];
                    $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j] == null) {
                            $resBiayaLain[$j]['id_jp'] = '6';
                            $resBiayaLain[$j]['nm_jp'] = 'Tunggakan CS';
                            if ($dataTG != null) {
                                $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'] + $dataTG['jml_tunggakan'];
                            } else {
                                $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                            }
                            $resBiayaLain[$j]['potongan_biaya'] = '0';
                        }
                    }
                }
            }
            $dataTx['data_kewajiban_lain'] = $resBiayaLain;

            // var_dump($resBiayaLain);
            // die;
        } else {
            $dataTx['data_kewajiban_lain'] = null;
        }

        for ($x = 0; $x < $countDetailTX; $x++) {
            if ($resDetailTx[$x]['id_jenis_pembayaran'] == 2 || $resDetailTx[$x]['id_jenis_pembayaran'] == 3 || $resDetailTx[$x]['id_jenis_pembayaran'] == 4 || $resDetailTx[$x]['id_jenis_pembayaran'] == 5 || $resDetailTx[$x]['id_jenis_pembayaran'] == 6 || $resDetailTx[$x]['id_jenis_pembayaran'] == 7) {
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 2) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC1;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 3) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC2;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 4) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC3;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 5) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = (int)$kewajibanKMHS;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 6) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanTGCS;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 7) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = (int)$kewajibanTGKMHS;
                }
            } else {
                for ($j = 0; $j < count($resBiayaLain); $j++) {
                    if ($resDetailTx[$x]['id_jenis_pembayaran'] == 8) {
                        $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanPerpanjangSemester;
                    } else if ($resDetailTx[$x]['id_jenis_pembayaran'] == 9 && $resDetailTx[$x]['id_jenis_pembayaran'] == $dataTxSebelumnya[$x]['id_jenis_pembayaran']) {
                        if (isset($dataTxSebelumnya[$x]['id_jenis_pembayaran']) != null) {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $dataBiayaAngkatan['uang_bangunan'] - $dataTxSebelumnya[$x]['jml_bayar'];
                        } else {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $dataBiayaAngkatan['uang_bangunan'];
                        }
                    } else {
                        if ($resDetailTx[$x]['id_jenis_pembayaran'] == $resBiayaLain[$j]['id_jp']) {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $resBiayaLain[$j]['biaya'];
                        }
                    }
                }
            }
        }

        // var_dump($resBiayaLain);
        // die;

        $dataTx['detail_transaksi'] = $resDetailTx;
        $dataTx['admin_log'] = $this->user->getUser(['id_user' => $this->session->userdata('id_user')])->row_array();
        $tgl_str = '%Y-%m-%d';
        $tgl_now = time();
        $dataTx['admin_log']['tanggal_log'] = mdate($tgl_str, $tgl_now);
        $dataTx['admin_log']['ket_cetak'] = 'print_ulang';
        $data['data_transaksi'] = $dataTx;
        // echo'<pre>';
        // var_dump($dataTx);
        // echo'</pre>';
        // die;
        $this->load->view('transaksi/kwitansi_new', $data);
    }
    
    public function cetak_kwitansi_dev($id_transaksi)
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        $bayarCS = false;
        $bayarTG_CS = false;
        $bayarLainnya = false;
        $bayarKMHS = false;
        $bayarTG_KMHS = false;

        $where = [
            'id_transaksi' => $id_transaksi,
        ];
        // ambil data tunggakan KMHS
        $dataTx = $this->transaksi->getDataTransaksi($where)->row_array();
        $tahun_bayar = substr($dataTx['semester'], 0, 4);
        $smt_bayar = substr($dataTx['semester'], 4);
        $dataTx['nm_smt'] = $tahun_bayar . '/' . ($tahun_bayar + 1) . ' S' . $smt_bayar;;
        $dataTx['smt'] = $smt_bayar;

        $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataTx['id_transaksi']])->result_array();
        $countDetailTX = count($resDetailTx);
        $jenjangMhs = $dataTx['nm_jenj_didik'];
        $dataTx['angkatan_mhs'] = '20' . substr($dataTx['nim'], 0, 2);
        $where_tahun = [
            'angkatan' => $dataTx['angkatan_mhs']
        ];
        $dataBiayaAngkatan = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
        $biayaCS = $dataBiayaAngkatan['cicilan_semester'] / 3;
        $biayaKMHS = $dataBiayaAngkatan['kemahasiswaan'];
        for ($x = 0; $x < $countDetailTX; $x++) {
            $biaya_Lainnya = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $resDetailTx[$x]['id_jenis_pembayaran']])->row_array();
            $kewajibanLain[] = isset($biaya_Lainnya['biaya']);
        }
        foreach ($resDetailTx as $i => $Dtx) {
            $resBiayaLain[] = $this->masterdata->getBiayaPembayaranLain(['mjp.id_jenis_pembayaran' => $Dtx['id_jenis_pembayaran']])->row_array();
            
            if($Dtx['id_jenis_pembayaran'] == 9){
                $resBiayaLain[$i]['biaya']=$dataBiayaAngkatan['uang_bangunan'];
            }
            if ($Dtx['id_jenis_pembayaran'] == 2 || $Dtx['id_jenis_pembayaran'] == 3 || $Dtx['id_jenis_pembayaran'] == 4) {
                $bayarCS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 5) {
                $bayarKMHS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 6) {
                $bayarTG_CS = true;
            }
            if ($Dtx['id_jenis_pembayaran'] == 7) {
                $bayarTG_KMHS = true;
            }
            if ($resBiayaLain[$i] !== null) {
                $bayarLainnya = true;
            }
        }
        
        
        // echo '<pre>';
        // var_dump($resBiayaLain);
        // echo'</pre>';
        // die;
        $where = [
            't.nim' => $dataTx['nim'],
            't.semester' => $dataTx['semester'],
            't.id_transaksi <' => $id_transaksi,

        ];
        $dataTxSebelumnya = $this->transaksi->getDataTransaksiSebelumnya($where)->result_array();

        // echo '<pre>';
        // var_dump($dataTxSebelumnya);
        // echo'</pre>';
        // die;
        $kewajibanCS = $dataBiayaAngkatan['cicilan_semester'];
        $kewajibanPerpanjangSemester = $kewajibanCS / 2;
        $kewajibanC1 = $biayaCS;
        $kewajibanC2 = $biayaCS;
        $kewajibanC3 = $biayaCS;
        $kewajibanKMHS = $biayaKMHS;
        $kewajibanTGCS = 0;
        $kewajibanTGKMHS = 0;


        if ($bayarCS == true) {
            // cek tunggakan CS
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                $whereXX = [
                    't.nim' => $dataTx['nim'],
                    't.semester' => $dataTx['semester'],
                    't.id_transaksi >' => $id_transaksi,

                ];
                $dataTxSetelahnya = $this->transaksi->getDataTransaksiSebelumnya($whereXX)->result_array();
                foreach ($dataTxSetelahnya as $a => $value) {
                    $kewajibanTGCS = $kewajibanTGCS + $value['jml_bayar'];
                }

                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 2) {
                    $kewajibanC1 = $kewajibanC1 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 3) {
                    $kewajibanC2 = $kewajibanC2 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 4) {
                    $kewajibanC3 = $kewajibanC3 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
            }
            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
            $dataTx['kewajiban']['cs'] = $kewajibanCS;
            $dataTx['bayar_cs'] = 1;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 2) {
                    $kewajibanC1 = $kewajibanC1 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 3) {
                    $kewajibanC2 = $kewajibanC2 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 4) {
                    $kewajibanC3 = $kewajibanC3 - $val['jml_bayar'];
                    $kewajibanCS = $kewajibanCS - $val['jml_bayar'];
                }
                if ($val['id_jenis_pembayaran'] == 6) {
                    $kewajibanTGCS = $kewajibanTGCS - $val['jml_bayar'];
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
            $dataTx['kewajiban']['cs'] = $kewajibanCS;
            $dataTx['bayar_cs'] = 0;
            $dataTx['kewajibanCS'] = $kewajibanCS;
        }

        if ($bayarTG_CS == true) {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                $whereXX = [
                    't.nim' => $dataTx['nim'],
                    't.semester' => $dataTx['semester'],
                    't.id_transaksi >' => $id_transaksi,

                ];
                $dataTxSetelahnya = $this->transaksi->getDataTransaksiSebelumnya($whereXX)->result_array();
                foreach ($dataTxSetelahnya as $a => $value) {
                    $kewajibanTGCS = $kewajibanTGCS + $value['jml_bayar'];
                }

                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '6'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGCS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 6) {
                        $kewajibanTGCS = $kewajibanTGCS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 6) {
                    $kewajibanTGCS = $kewajibanTGCS - $val['jml_bayar'];
                }
            }

            $bayar_tg_cs = 0;
            foreach ($resDetailTx as $i => $Dtx) {
                if ($Dtx['id_jenis_pembayaran'] == 6) {
                    $bayar_tg_cs = 1;
                }
            }
            $dataTx['bayar_tg_cs'] = $bayar_tg_cs;
            $dataTx['kewajiban']['tg_cs'] = $kewajibanTGCS;
        }


        
        if ($bayarTG_KMHS == true) {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '7'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGKMHS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = $kewajibanTGKMHS + (int)$Dtx['jml_bayar'];
                    }
                }
            }
            
            // echo'<pre>'; 
            // var_dump($dataTxSebelumnya);
            // echo'</pre>';
            // die;


            // foreach ($dataTxSebelumnya as $a => $val) {
            //     if ($val['id_jenis_pembayaran'] == 5) {
            //         $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
            //     }
            //     if ($val['id_jenis_pembayaran'] == 7) {
            //         $kewajibanTGKMHS = $kewajibanTGKMHS - $val['jml_bayar'];
            //     }
            // }
            $dataTx['kewajiban']['tg_kmhs'] = $kewajibanTGKMHS;
            $dataTx['bayar_tg_kmhs'] = 1;
        } else {
            $dataCekTG = [
                'nim' => $dataTx['nim'],
                'jenis_tunggakan' => '7'
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
            if ($dataTG == null) {
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = (int)$Dtx['jml_bayar'];
                    }
                }
            } else {
                $kewajibanTGKMHS = $dataTG['jml_tunggakan'];
                foreach ($resDetailTx as $i => $Dtx) {
                    if ($Dtx['id_jenis_pembayaran'] == 7) {
                        $kewajibanTGKMHS = $kewajibanTGKMHS + (int)$Dtx['jml_bayar'];
                    }
                }
            }

            $dataTx['kewajiban']['tg_kmhs'] = $kewajibanTGKMHS;
            $dataTx['bayar_tg_kmhs'] = 0;
            $dataTx['dataTx'] = 0;
        }

        // var_dump( $dataTx['kewajiban']['tg_kmhs']);die;


        if ($bayarKMHS == true) {
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 5) {
                    $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
                }
            }
            $dataTx['kewajiban']['kmhs'] = $kewajibanKMHS;
            $dataTx['bayar_kmhs'] = 1;
        } else {
            foreach ($dataTxSebelumnya as $a => $val) {
                if ($val['id_jenis_pembayaran'] == 5) {
                    $kewajibanKMHS = $kewajibanKMHS - $val['jml_bayar'];
                }
            }
            $dataTx['kewajiban']['kmhs'] = $kewajibanKMHS;
            $dataTx['bayar_kmhs'] = 0;
        }

        // echo'<pre>';
        // var_dump($resBiayaLain);
        // echo'</pre>';
        // die;
        if ($bayarLainnya == true) {
            foreach ($dataTxSebelumnya as $a => $value) {
                if ($value['id_jenis_pembayaran'] == 8) {
                    $kewajibanPerpanjangSemester = $kewajibanPerpanjangSemester - $value['jml_bayar'];
                } else if ($value['id_jenis_pembayaran'] == 16) {
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        $resBiayaLain[$j]['biaya'] = $resBiayaLain[$j]['biaya'] - $value['jml_bayar'];
                    }
                } else {
                    foreach ($resBiayaLain as $key => $val) {
                        if (isset($val['id_jp']) && isset($value['id_jenis_pembayaran'])) { //update 18/10/2021
                            if ($value['id_jenis_pembayaran'] == $val['id_jp']) {
                                $resBiayaLain[$key]['biaya'] = $val['biaya'] - $value['jml_bayar'];
                            } else {
                                $resBiayaLain[$key]['biaya'] = $val['biaya'];
                            }
                        }
                    }
                }
            }
            
            // echo'<pre>';
            // var_dump($resBiayaLain);
            // echo'</pre>';
            // die;
            foreach ($resDetailTx as $i => $dtx) {
                // cuti
                if ($dtx['id_jenis_pembayaran'] == '16') {
                    $resDetailTx[$i]['jml_cuti'] = $dtx['jml_bayar'] / $resBiayaLain[$i]['biaya'];
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j]['id_jp'] == '16') {
                            $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                        }
                    }
                }
                // konversi
                if ($dtx['id_jenis_pembayaran'] == '17') {
                    $resDetailTx[$i]['jml_mk'] = $dtx['jml_bayar'] / 100000;
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j]['id_jp'] == '17') {
                            $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                        }
                    }
                }
                if ($dtx['id_jenis_pembayaran'] == '6') {
                    $dataCekTG = [
                        'nim' => $dataTx['nim'],
                        'jenis_tunggakan' => '6'
                    ];
                    $dataTG = $this->tunggakan->getTunggakanMhs($dataCekTG)->row_array();
                    for ($j = 0; $j < count($resBiayaLain); $j++) {
                        if ($resBiayaLain[$j] == null) {
                            $resBiayaLain[$j]['id_jp'] = '6';
                            $resBiayaLain[$j]['nm_jp'] = 'Tunggakan CS';
                            if ($dataTG != null) {
                                $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'] + $dataTG['jml_tunggakan'];
                            } else {
                                $resBiayaLain[$j]['biaya'] = $dtx['jml_bayar'];
                            }
                            $resBiayaLain[$j]['potongan_biaya'] = '0';
                        }
                    }
                }
            }
            $dataTx['data_kewajiban_lain'] = $resBiayaLain;

            // var_dump($resBiayaLain);
            // die;
        } else {
            $dataTx['data_kewajiban_lain'] = null;
        }
        for ($x = 0; $x < $countDetailTX; $x++) {
            if ($resDetailTx[$x]['id_jenis_pembayaran'] == 2 || $resDetailTx[$x]['id_jenis_pembayaran'] == 3 || $resDetailTx[$x]['id_jenis_pembayaran'] == 4 || $resDetailTx[$x]['id_jenis_pembayaran'] == 5 || $resDetailTx[$x]['id_jenis_pembayaran'] == 6 || $resDetailTx[$x]['id_jenis_pembayaran'] == 7) {
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 2) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC1;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 3) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC2;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 4) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanC3;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 5) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = (int)$kewajibanKMHS;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 6) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanTGCS;
                }
                if ($resDetailTx[$x]['id_jenis_pembayaran'] == 7) {
                    $resDetailTx[$x]['kewajiban_Bayar'] = (int)$kewajibanTGKMHS;
                }
            } else {
                for ($j = 0; $j < count($resBiayaLain); $j++) {
                    if ($resDetailTx[$x]['id_jenis_pembayaran'] == 8) {
                        $resDetailTx[$x]['kewajiban_Bayar'] = $kewajibanPerpanjangSemester;
                    } else if ($resDetailTx[$x]['id_jenis_pembayaran'] == 9 && $resDetailTx[$x]['id_jenis_pembayaran'] == $dataTxSebelumnya[$x]['id_jenis_pembayaran']) {
                        if (isset($dataTxSebelumnya[$x]['id_jenis_pembayaran']) != null) {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $dataBiayaAngkatan['uang_bangunan'] - $dataTxSebelumnya[$x]['jml_bayar'];
                        } else {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $dataBiayaAngkatan['uang_bangunan'];
                        }
                    } else {
                        if ($resDetailTx[$x]['id_jenis_pembayaran'] == $resBiayaLain[$j]['id_jp']) {
                            $resDetailTx[$x]['kewajiban_Bayar'] = $resBiayaLain[$j]['biaya'];
                        }
                    }
                }
            }
        }

        // var_dump($resBiayaLain);
        // die;

        $dataTx['detail_transaksi'] = $resDetailTx;
        $dataTx['admin_log'] = $this->user->getUser(['id_user' => $this->session->userdata('id_user')])->row_array();
        $tgl_str = '%Y-%m-%d';
        $tgl_now = time();
        $dataTx['admin_log']['tanggal_log'] = mdate($tgl_str, $tgl_now);
        $dataTx['admin_log']['ket_cetak'] = 'print_ulang';
        $data['data_transaksi'] = $dataTx;
        // echo'<pre>';
        // var_dump($dataTx);
        // echo'</pre>';
        // die;
        $this->load->view('transaksi/kwitansi_new', $data);
    }
}
