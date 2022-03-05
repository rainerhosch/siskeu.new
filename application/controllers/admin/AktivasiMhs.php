<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : AktivasiMhs.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 07/09/2021
 *  Note                  : Sebenernya ini tuh Class Dispensasi, cuman karena terburu2 bikin jadi asal bikin nama aja, untuk kedepnnya mau di ganti jadi class dipensasiMhs silahkan enggak juga gak apa2
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */
class AktivasiMhs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('M_transaksi', 'transaksi');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_user', 'user');
    }

    public function aktif_manual()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Aktivasi Mahasiswa Manual';
        $data['content'] = 'admin/v_aktivasi_mhs_manual';
        $this->load->view('template', $data);
    }

    public function aktifkan_manual()
    {
        $dateNow = date('Y-m-d H:i:s');
        $pecah_tgl_waktu = explode(' ', $dateNow);
        $tgl = $pecah_tgl_waktu[0];

        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            $nimMhs = $this->input->post('nipd');
            $jenis_aktivasi = $this->input->post('jns_aktifasi');
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
            $dataAktifUTS = [
                'tahun' => $smtAktif,
                'nim' => $nimMhs,
                'tgl_reg' => $tgl,
                'aktif' => 1,
                'keterangan' => 'from siskeu_new',
                'aktif_by' => 0
            ];
            $dataAktifUAS = [
                'tahun' => $smtAktif,
                'nim' => $nimMhs,
                'tgl_reg' => $tgl,
                'aktif' => 2,
                'keterangan' => 'from siskeu_new',
                'aktif_by' => 0
            ];

            // ===============================  Fungsi aktifasi perwalian dan ujian ==============
            if ($jenis_aktivasi == '2') {
                // Aktifasi Perwalian, UTS, UAS
                $this->aktivasi->aktivasi_perwalian($dataAktifKRS);
            } else if ($jenis_aktivasi == '3') {
                $this->aktivasi->aktivasi_ujian($dataAktifUTS);
            } else {
                $this->aktivasi->aktivasi_ujian($dataAktifUAS);
            }
            $response = [
                'status' => 200,
                'msg' => 'Aktivasi Berhasil!'
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'Invalid Request.'
            ];
        }
        echo json_encode($response);
    }


    public function dispen()
    {
        // code here...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Aktivasi Mahasiswa Dispen';
        $data['content'] = 'admin/v_aktivasi_mhs_dispen';

        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $data['tahun_akademik'] = $smtAktifRes['id_smt'];
        // ===============================
        $this->load->view('template', $data);
    }

    public function sync_dispen()
    {
        $update_dispen =[];
        // jenis dispen (1,3,4)
        $dataCekDispen = [
            'd.id_reg_pd <>' => '6178', //Winda Ayu Melati
            'd.tahun_akademik' => '20211',
            'd.status' => 0,
            'd.jenis_dispen' => 1,
        ];
        $data_dispen = $this->aktivasi->getDataDispenMhs($dataCekDispen)->result_array();
        foreach($data_dispen as $dd){
            $id_dispen = $dd['id_dispensasi'];
            $where = [
                't.nim' => $dd['nipd'],
                't.semester' => $dd['tahun_akademik']
                
            ];
            $dataHistoriTx = $this->transaksi->getDataTransaksi($where)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
                
                foreach($resDetailTx as $rDtx){
                    if($rDtx['id_jenis_pembayaran'] = $dd['jenis_dispen'] && $rDtx['jml_bayar'] = $dd['tg_dispen']){
                        // update dispen
                        $dataUpdateDispen = [
                            'status' => 1,
                        ];
                        // $update_dispen = $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdateDispen);
                    }
                }
            }
            
        }
        echo'<pre>';
        var_dump($data_dispen);
        echo'</pre>';
        die;
    } 

    public function update_jml_pesan()
    {
        if ($this->input->is_ajax_request()) {
            $id_dispen = $this->input->post('id_dispen');
            $kondisi = [
                'd.id_dispensasi' => $id_dispen
            ];
            $dataDispen = $this->aktivasi->getDataDispenMhs($kondisi)->row_array();
            $jml_update = $dataDispen['jml_kirim_pesan'] + 1;
            $dataUpdate = [
                'jml_kirim_pesan' => $jml_update
            ];
            $update = $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdate);
            if (!$update) {
                $response = [
                    'status' => false,
                    'msg' => 'Gagal Update',
                    'data' => [
                        'id' => $id_dispen,
                        'jml_now' => $dataDispen['jml_kirim_pesan']
                    ]
                ];
            } else {
                $response = [
                    'status' => true,
                    'msg' => 'Berhasil Update',
                    'data' => [
                        'id' => $id_dispen,
                        'jml_now' => $jml_update
                    ]
                ];
            }
        } else {
            $response = [
                'status' => false,
                'msg' => 'Invalid Request.',
                'data' => null
            ];
        }
        echo json_encode($response);
    }

    public function get_data_dispen_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $id_dispen = $this->input->post('id_dispen');
            $kondisi = [
                'd.id_dispensasi' => $id_dispen
            ];
            $dataDispen = $this->aktivasi->getDataDispenMhs($kondisi)->row_array();
            $whereTG = [
                'nim' => $dataDispen['nipd'],
                'tg.jenis_tunggakan' => 6
            ];
            $dataTG = $this->tunggakan->getTunggakanMhs($whereTG)->row_array();
            if ($dataTG != null) {
                $dataDispen['tg_dispen'] = $dataDispen['tg_dispen'] + $dataTG['jml_tunggakan'];
            }
            $response = [
                'status' => true,
                'msg' => 'Data ditemukan',
                'data' => $dataDispen
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'Invalid Request.',
                'data' => null
            ];
        }
        echo json_encode($response);
    }

    public function edit_dispen()
    {
        if ($this->input->is_ajax_request()) {
            $dataInput = $this->input->post();
            $id_dispen = $dataInput['id_dispen_edit'];
            // var_dump($dataInput);
            // die;

            $dataUpdate = [
                'no_tlp' => $dataInput['no_tlp_edit']
            ];
            $update = $this->aktivasi->updateDataDispenMhs($id_dispen, $dataUpdate);
            if (!$update) {
                $response = [
                    'status' => false,
                    'msg' => 'Gagal edit data.',
                    'data' => null
                ];
            } else {
                $response = [
                    'status' => true,
                    'msg' => 'Data dispen berhasil di edit.',
                    'data' => null
                ];
            }
        } else {
            $response = [
                'status' => false,
                'msg' => 'Invalid Request.',
                'data' => null
            ];
        }
        echo json_encode($response);
    }

    public function get_data_dispen_mhs()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            $kondisi = [
                // 'd.status' => 0,
                'd.tahun_akademik' => $smtAktif

            ];
            // $dataDispen = $this->aktivasi->getDataDispenMhs($kondisi)->result_array();
            $dataDispen = $this->aktivasi->getDataDispenMhs()->result_array();
            $countData = count($dataDispen);
            $mhsLunas = [];
            $mhsBelumLunas = [];
            // var_dump($countData);
            // die;
            for ($i = 0; $i < $countData; $i++) {
                if ($dataDispen[$i]['status'] == '1') {
                    $mhsLunas[] = $dataDispen[$i]['nipd'];
                } else {
                    $mhsBelumLunas[] = $dataDispen[$i]['nipd'];
                }

                $whereTG = [
                    'nim' => $dataDispen[$i]['nipd'],
                    'tg.jenis_tunggakan' => 6
                ];
                $dataTG = $this->tunggakan->getTunggakanMhs($whereTG)->row_array();
                $tg_smt_lalu = 0;
                if ($dataTG != null) {
                    $tg_smt_lalu = $dataTG['jml_tunggakan'];
                }
                if ($dataDispen[$i]['jenis_dispen'] == '1') {
                    $dataDispen[$i]['rincian'] = [
                        0 => [
                            'label' => 'Cicilan Ke-1',
                            'jumlah' => $dataDispen[$i]['tg_dispen']
                        ],
                        1 => [
                            'label' => 'TG semester lalu',
                            'jumlah' => $tg_smt_lalu
                        ],
                    ];
                } elseif ($dataDispen[$i]['jenis_dispen'] == '3') {
                    $dataDispen[$i]['rincian'] = [
                        0 => [
                            'label' => 'Cicilan Ke-2',
                            'jumlah' => $dataDispen[$i]['tg_dispen']
                        ],
                        1 => [
                            'label' => 'TG semester lalu',
                            'jumlah' => $tg_smt_lalu
                        ],
                    ];
                } elseif ($dataDispen[$i]['jenis_dispen'] == '4') {
                    $dataDispen[$i]['rincian'] = [
                        0 => [
                            'label' => 'Cicilan Ke-3',
                            'jumlah' => $dataDispen[$i]['tg_dispen']
                        ],
                        1 => [
                            'label' => 'TG semester lalu',
                            'jumlah' => $tg_smt_lalu
                        ],
                    ];
                }
            }
            $response = [
                'satatus' => true,
                'data' => $dataDispen,
                'mhs_lunas' => count($mhsLunas),
                'mhs_belum_lunas' => count($mhsBelumLunas),
                'msg' => 'Data Ditemukan.'
            ];
        } else {
            $response = [
                'satatus' => false,
                'data' => null,
                'mhs_lunas' => null,
                'mhs_belum_lunas' => null,
                'msg' => 'Invalid Request'
            ];
        }
        echo json_encode($response);
    }

    public function cek_satus_aktif()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            $nipd = $this->input->post('nipd');
            $jenis_cek = $this->input->post('jns_aktifasi');
            $biaya_cs = $this->input->post('biaya_cs');
            $kewajiban_cicilan = $biaya_cs / 3;

            $dataMhs = $this->masterdata->getMahasiswaByNim(['nipd' => $nipd])->row_array();
            // ============================================================================
            // cek transaksi
            $dataCekNim = [
                'nim' => $nipd,
                'semester' => $smtAktif
            ];
            $dataHistoriTx = $this->transaksi->getDataTransaksi($dataCekNim)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            if ($countHistoriTx > 0) {
                for ($i = 0; $i < $countHistoriTx; $i++) {
                    $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                    $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
                    foreach ($dataHistoriTx[$i]['detail_transaksi'] as $dTX) {
                        if ($dTX['id_jenis_pembayaran'] == $jenis_cek) {
                            $kewajiban_cicilan = $kewajiban_cicilan - $dTX['jml_bayar'];
                        }
                    }
                }
            }

            // ================================ cek status reg ===============================
            $where = [
                't.nim' => $nipd,
                't.tahun' => $smtAktif,
            ];
            if ($jenis_cek == '3' || $jenis_cek == '4') {
                $table = 'reg_ujian t';
                $dataStatus = $this->aktivasi->cekStatusAktifMhs($where, $table)->row_array();
                if ($dataStatus != null) {
                    if ($dataStatus['aktif'] == '1' || $dataStatus['aktif'] == '2') {
                        $status = 'Sudah Aktif';
                        $aktif = $dataStatus['aktif'];
                    } else {
                        $status = 'Aktif by Dispen';
                        $aktif = $dataStatus['aktif'];
                    }
                } else {
                    if ($kewajiban_cicilan <= 500000) {
                        $status = 1;
                        $aktif = 0;
                    } else {
                        $status = 'Belum Melunasi Cicilan';
                        $aktif = 0;
                    }
                }
            } else {
                $table = 'reg_mhs t';
                $dataStatus = $this->aktivasi->cekStatusAktifMhs($where, $table)->row_array();
                if ($dataStatus != null) {
                    if ($dataStatus['aktif'] == '2') {
                        $status = 'Sudah Aktif';
                        $aktif = $dataStatus['aktif'];
                    } else {
                        $status = 'Aktif by Dispen';
                        $aktif = $dataStatus['aktif'];
                    }
                } else {
                    if ($kewajiban_cicilan <= 500000) {
                        $status = 1;
                        $aktif = 0;
                    } else {
                        $status = 'Belum Melunasi Cicilan';
                        $aktif = 0;
                    }
                }
            }
            $response = [
                'nipd'      => $dataMhs['nipd'],
                'nama'      => $dataMhs['nm_pd'],
                'jurusan'   => $dataMhs['nm_jur'],
                'status'    => $status,
                'aktif'     => $aktif
            ];
        } else {
            $response = 'Invalid Request!';
        }
        echo json_encode($response);
    }

    public function cari_mhs()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];

        if ($this->input->is_ajax_request()) {
            $nipd = $this->input->post('nipd');
            $jenis_dispen = $this->input->post('jenis_dispen');
            $tahun_akademik = $this->input->post('tahun_akademik');

            // var_dump($dataDispen);
            // die;
            if ($jenis_dispen == '1') {
                $jns_bayar = 2;
                $nm_jns_dispen = 'Cicilan Ke-1';
            } else if ($jenis_dispen == '3') {
                $jns_bayar = 3;
                $nm_jns_dispen = 'Cicilan Ke-2';
            } else {
                $jns_bayar = 4;
                $nm_jns_dispen = 'Cicilan Ke-3';
            }
            $data = $this->masterdata->getMahasiswaByNim(['nipd' => $nipd])->row_array();
            // cek tunggakan smt lalu
            if ($data != null) {
                $whereTG = [
                    'nim' => $nipd,
                    'tg.jenis_tunggakan' => 6
                ];
                $dataTG = $this->tunggakan->getTunggakanMhs($whereTG)->row_array();
                $data['tg_smt_lalu'] = 0;
                if ($dataTG != null) {
                    $data['tg_smt_lalu'] = $dataTG['jml_tunggakan'];
                }
                // cek biaya angkatan
                $jenjangMhs = $data['nm_jenj_didik'];
                $angkatan_mhs = '20' . substr($data['nipd'], 0, 2);
                $where_tahun = [
                    'angkatan' =>  $angkatan_mhs
                ];
                $dataBiayaAngkatan = $this->masterdata->getBiayaAngkatan($where_tahun, $jenjangMhs)->row_array();
                $biayaCS = $dataBiayaAngkatan['cicilan_semester'];
                $biayaC_ke = $biayaCS / 3;
                $dataCekNim = [
                    'nim' => $nipd,
                    'semester' => $smtAktif
                ];
                $dataHistoriTx = $this->transaksi->getDataTransaksi($dataCekNim)->result_array();
                $countHistoriTx = count($dataHistoriTx);
                if ($countHistoriTx > 0) {
                    for ($i = 0; $i < $countHistoriTx; $i++) {
                        $resDetailTx = $this->transaksi->getDataTxDetail(['t.id_transaksi' => $dataHistoriTx[$i]['id_transaksi']])->result_array();
                        $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
                        foreach ($dataHistoriTx[$i]['detail_transaksi'] as $dTX) {
                            if ($dTX['id_jenis_pembayaran'] == $jns_bayar) {
                                $biayaC_ke = $biayaC_ke - $dTX['jml_bayar'];
                            }
                            $data['pengajuan_dispen'] = $biayaC_ke;
                            $data['nm_kewajiban'] = $nm_jns_dispen;
                            $data['biaya_cs'] = $biayaCS;
                        }
                    }
                } else {
                    $data['biaya_cs'] = $biayaCS;
                    $data['pengajuan_dispen'] = $biayaC_ke;
                    $data['nm_kewajiban'] = $nm_jns_dispen;
                }
                // cek data dispen
                $dataCekDispen = [
                    'm.nipd' => $nipd,
                    'd.tahun_akademik' => $smtAktif,
                    'd.jenis_dispen' => $jenis_dispen
                ];
                $dataDispen = $this->aktivasi->getDataDispenMhs($dataCekDispen)->row_array();
                if ($dataDispen == NULL) {
                    $response = [
                        'status' => 200,
                        'msg'   => 'data ditemukan',
                        'data' => $data
                    ];
                } else {
                    $response = [
                        'status' => 203,
                        'msg'   => 'Data dispen, ' . $dataDispen['nm_pd'] . ' sudah ada!',
                        'data' => $dataDispen
                    ];
                }
            } else {
                $response = [
                    'status' => 203,
                    'msg'   => 'tidak ada mahasiswa dengan nim tersebut!',
                    'data' => $data
                ];
            }
        } else {
            $response = 'Invalid Request!';
        }
        echo json_encode($response);
    }

    public function aktif_dispen()
    {

        if ($this->input->is_ajax_request()) {
            $dateNow = date('Y-m-d H:i:s');
            $pecah_tgl_waktu = explode(' ', $dateNow);
            $tgl = $pecah_tgl_waktu[0];
            $id_user = $this->session->userdata('id_user');


            // data input
            $dataInput = $this->input->post();
            // var_dump($dataInput);
            // die;

            $no_tlp = $dataInput['no_tlp'];
            $format_no = substr($no_tlp, 1, 11);
            $dataPengajuanDispen = [
                'tanggal_input' => $tgl,
                'id_reg_pd' => $dataInput['id_reg_pd'],
                'id_jur' => $dataInput['id_jur'],
                'no_tlp' => '62' . $format_no,
                'jenis_dispen' => $dataInput['jenis_dispen'],
                'tg_dispen' => $dataInput['tg_dispen'],
                'tgl_janji_lunas' => $dataInput['tgl_pelunasan'],
                'tahun_akademik' => $dataInput['tahun_akademik'],
                'tgl_pelunasan' => NULL,
                'status' => 0,
                'jml_kirim_pesan' => 0
            ];
            $inputPengajuanDispen = $this->aktivasi->input_data_dispen_mhs($dataPengajuanDispen);
            if ($inputPengajuanDispen === true) {
                if ($dataInput['jenis_dispen'] == '1') {
                    // disepen perwalian
                    $dataAktifDispenKrs = [
                        'Tahun' => $dataInput['tahun_akademik'],
                        'Identitas_ID' => '',
                        'Jurusan_ID' => '',
                        'NIM' => $dataInput['nipd'],
                        'tgl_reg' => $tgl,
                        'aktif' => $dataInput['jenis_dispen'],
                        'keterangan' => 'from siskeu_new',
                        'aktif_by' =>  $id_user
                    ];
                    $active = $this->aktivasi->aktivasi_perwalian($dataAktifDispenKrs);
                    if ($active === true) {
                        // success
                        $reponse = [
                            'status' => true,
                            'msg'   => 'success'
                        ];
                    } else {
                        // error
                        $reponse = [
                            'status' => false,
                            'msg'   => $active
                        ];
                    }
                } else {
                    // dispen UTS or UAS
                    $dataAktifDispenUjian = [
                        'tahun' => $dataInput['tahun_akademik'],
                        'nim' => $dataInput['nipd'],
                        'tgl_reg' => $tgl,
                        'aktif' => $dataInput['jenis_dispen'],
                        'keterangan' => 'from siskeu_new',
                        'aktif_by' => $id_user
                    ];
                    // var_dump($dataAktifDispenUjian);
                    // die;
                    $active = $this->aktivasi->aktivasi_ujian($dataAktifDispenUjian);
                    if ($active === true) {
                        // success
                        $reponse = [
                            'status' => true,
                            'msg'   => 'success'
                        ];
                    } else {
                        // error
                        $reponse = [
                            'status' => false,
                            'msg'   => $active
                        ];
                    }
                }
            } else {
                $reponse = [
                    'status' => false,
                    'msg'   => 'Gagal insert data'
                ];
            }
        } else {
            $reponse = [
                'status' => false,
                'msg'   => 'invalid request'
            ];
        }
        echo json_encode($reponse);
    }
}
