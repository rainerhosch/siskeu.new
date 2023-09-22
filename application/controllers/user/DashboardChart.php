<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *  File Name       : DashboardChart.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 *  
 *  Date Created 16 Desember 2020
 *  Author @Rizky Ardiansyah
 */

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use \PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DashboardChart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        $this->load->model('M_menu', 'menu');
        $this->load->model('M_user', 'user');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_api', 'api');
    }
    public function index()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Dashboard Chart';
        $data['content'] = 'v_dashboard_2';
        $data['jml_tunggakan'] = $this->tunggakan->getTunggakanMhs()->num_rows();
        $response = $this->tunggakan->getTunggakanMhs()->result_array();
        foreach ($response as $tg) {
            $total_tg[] = $tg['jml_tunggakan'];
        }
        if (count($response) > 0) {
            $data['total_tg'] = array_sum($total_tg);
        } else {
            $data['total_tg'] = 0;
        }

        $this->load->view('template', $data);
        // $this->load->view('app');
    }


    public function createExcel()
    {
        $date_now = date("Y-m-d H:i:s");
        $dataLoad = $this->getDataBelumBayaranDanDispen();
        $data_mhs_belum_bayaran = $dataLoad['data_mhs_belum_bayaran'];
        // echo json_encode($dataLoad['data']);
        // echo '<pre>';
        // var_dump($data_mhs_belum_bayaran);
        // echo '</pre>';
        // die;

        $spreadsheet = new Spreadsheet();
        $Terbilang = new FormatTerbilang();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('data_mhs_belum_bayaran');

        //define width of column
        $sheet->getColumnDimension('A')->setWidth(20.00);
        $sheet->getColumnDimension('B')->setWidth(19.00);
        $sheet->getColumnDimension('C')->setWidth(22.00);
        $sheet->getColumnDimension('D')->setWidth(15.00);
        $sheet->getColumnDimension('E')->setWidth(35.00);
        $sheet->getColumnDimension('F')->setWidth(35.00);
        $sheet->getColumnDimension('G')->setWidth(13.00);
        $sheet->getColumnDimension('H')->setWidth(10.00);
        $sheet->getColumnDimension('I')->setWidth(26.00);

        //Define Style table
        $styleTitle = [
            'alignment' => [
                'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
        ];
        $styleTable = [
            'alignment' => [
                'vertical' => PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['RGB' => '000000']
                ]
            ]
        ];

        // =========================== configurasi Title =====================================
        $sheet->setCellValue('A1', 'LAPORAN DATA MAHASISWA BELUM MELUNASI CICILAN DAN DISPEN');
        $sheet->mergeCells('A1:F2');
        $sheet->setCellValue('A3', $date_now);
        $sheet->mergeCells('A3:F4');
        $sheet->mergeCells('A5:F5');
        $sheet->getStyle('A1:F4')->getFont()->setBold(true);
        $sheet->getStyle('A1:F4')->getFont()->setSize(14);
        $sheet->getStyle('A1:F4')->applyFromArray($styleTitle); //styling header table


        $row_header = 6;
        $merge_header = $row_header + 1;
        $sheet->setCellValue('A' . $row_header, 'TAHUN ANGKATAN');
        $sheet->setCellValue('B' . $row_header, 'PRODI');
        $sheet->setCellValue('C' . $row_header, 'ROMBEL');
        $sheet->setCellValue('D' . $row_header, 'DATA MAHASISWA');
        $sheet->mergeCells('D' . $row_header . ':' . 'E' . $row_header);
        $sheet->setCellValue('D' . ($row_header + 1), 'NIM');
        $sheet->setCellValue('E' . ($row_header + 1), 'NAMA');
        $sheet->setCellValue('F' . $row_header, 'STATUS');
        // $sheet->setCellValue('G' . $row_header, 'KETERANGAN');
        // $sheet->setCellValue('H' . $row_header, 'Admin');
        // $sheet->setCellValue('I' . $row_header, 'Keterangan');

        // merge all header
        $sheet->mergeCells('A' . $row_header . ':A' . $merge_header);
        $sheet->mergeCells('B' . $row_header . ':B' . $merge_header);
        $sheet->mergeCells('C' . $row_header . ':C' . $merge_header);
        $sheet->mergeCells('F' . $row_header . ':F' . $merge_header);
        // $sheet->mergeCells('G' . $row_header . ':G' . $merge_header);
        // $sheet->mergeCells('H' . $row_header . ':H' . $merge_header);
        // $sheet->mergeCells('I' . $row_header . ':I' . $merge_header);
        //styling title
        $sheet->getStyle('A' . $row_header . ':F' . $row_header)->getAlignment()->setHorizontal(PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row_header . ':F' . $row_header)->getAlignment()->setVertical(PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row_header . ':F' . $row_header)->getFont()->setBold(true);
        $sheet->getStyle('A' . $merge_header . ':F' . $merge_header)->getFont()->setBold(true);

        $row_tbl = 7;
        foreach ($data_mhs_belum_bayaran as $i => $data_angkatan) {
            $i++;
            foreach ($data_angkatan as $angkatan => $a) {
                foreach ($a as $prodi => $p) {
                    foreach ($p as $kelas => $k) {
                        foreach ($k as $mhs => $m) {
                            $status = '';
                            $row_min = $row_tbl + 1;
                            $sheet->setCellValue('A' . $row_min, $angkatan);
                            $sheet->setCellValue('B' . $row_min, $prodi);
                            $sheet->setCellValue('C' . $row_min, $kelas);
                            $sheet->setCellValue('D' . $row_min, $m['nipd']);
                            $sheet->setCellValue('E' . $row_min, $m['nm_pd']);
                            if ($m['data_dispen'] != null) {
                                $status = 'DISPEN';
                            } else {
                                $status = 'BELUM MELAKUKAN PEMBAYARAN';
                            }
                            $sheet->setCellValue('F' . $row_min, $status);
                            $row_tbl++;
                        }
                    }

                }
            }
        }



        $filename = 'Data_mahasiswa_belum_melakukan_pembayaran_dan_dispen_(' . $date_now . ')';
        $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function getDataBelumBayaranDanDispen()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $res['data'] = array();
        $tahun_masuk = $this->masterdata->getDataAngkatan(['tahun_masuk >' => '2016'])->result_array();
        // $cek_krs = $this->aktivasi->cekKrsMhsSimak()->result_array();

        $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
        $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
        $smt_befor = '';
        if ($cek_ganjil_genap == '1') {
            $smt_befor = ($tahun_smt_befor - 1) . '2';
        } else {
            $smt_befor = ($tahun_smt_befor - 1) . '1';
        }
        $res['data_mhs_belum_bayaran'] = array();


        // $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
        foreach ($tahun_masuk as $i => $val) {
            $data_prodi = $this->masterdata->getProdi(['tahun_masuk' => $val['tahun_masuk']])->result_array();
            foreach ($data_prodi as $p => $prodi) {
                $data_kelas = $this->masterdata->getKelas(['m.tahun_masuk' => $val['tahun_masuk'], 'm.nm_jur' => $prodi['nm_jur']])->result_array();
                foreach ($data_kelas as $k => $dk) {
                    $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk'], 'nm_jur' => $prodi['nm_jur'], 'id_kelas' => $dk['id_kelas']])->result_array();
                    // $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']] = $list_mhs;

                    $n = 0;
                    foreach ($list_mhs as $x => $mhs) {
                        $cek_krs_befor = $this->aktivasi->cekKrsMhsLokal(['id_tahun_ajaran' => $smt_befor, 'nipd' => $mhs['nipd']])->row_array();

                        $cek_dispen = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();
                        $data_dispen = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->row_array();

                        // $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['krs_befor'] = null;
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x] = $mhs;
                        $param_tx = [
                            'm.tahun_masuk' => $mhs['tahun_masuk'],
                            'td.id_jenis_pembayaran <' => 5,
                            'm.nipd' => $mhs['nipd'],
                            't.semester' => $smtAktifRes['id_smt']
                        ];
                        $data_trx = $this->masterdata->getDataPembayaranChart($param_tx)->row_array();
                        if ($cek_krs_befor != null) {
                            // if ($cek_dispen > 0) {
                            //     $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                            //     $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                            //     $n++;
                            // }
                            if ($data_trx == null && $mhs['no_transkip_nilai'] == null || $cek_dispen > 0) {
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                                $n++;
                            }
                        } elseif ($mhs['tahun_masuk'] == substr($smtAktifRes['id_smt'], 0, 4) && $data_trx == null) {
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                            $n++;
                        }
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();
                    }
                }
            }
        }
        $res['smt_aktif'] = $smtAktifRes['id_smt'];
        $res['smt_befor'] = $smt_befor;
        return $res;
    }

    public function getDataBelumBayaran()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $res['data'] = array();
        $tahun_masuk = $this->masterdata->getDataAngkatan(['tahun_masuk >' => '2016'])->result_array();
        // $cek_krs = $this->aktivasi->cekKrsMhsSimak()->result_array();

        $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
        $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
        $smt_befor = '';
        if ($cek_ganjil_genap == '1') {
            $smt_befor = ($tahun_smt_befor - 1) . '2';
        } else {
            $smt_befor = ($tahun_smt_befor - 1) . '1';
        }
        $res['data_mhs_belum_bayaran'] = array();


        // $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
        foreach ($tahun_masuk as $i => $val) {
            $data_prodi = $this->masterdata->getProdi(['tahun_masuk' => $val['tahun_masuk']])->result_array();
            foreach ($data_prodi as $p => $prodi) {
                $data_kelas = $this->masterdata->getKelas(['m.tahun_masuk' => $val['tahun_masuk'], 'm.nm_jur' => $prodi['nm_jur']])->result_array();
                foreach ($data_kelas as $k => $dk) {
                    $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk'], 'nm_jur' => $prodi['nm_jur'], 'id_kelas' => $dk['id_kelas']])->result_array();
                    // $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']] = $list_mhs;

                    $n = 0;
                    foreach ($list_mhs as $x => $mhs) {
                        $cek_krs_befor = $this->aktivasi->cekKrsMhsLokal(['id_tahun_ajaran' => $smt_befor, 'nipd' => $mhs['nipd']])->row_array();

                        $cek_dispen = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();
                        $data_dispen = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->row_array();

                        // $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['krs_befor'] = null;
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x] = $mhs;
                        $param_tx = [
                            'm.tahun_masuk' => $mhs['tahun_masuk'],
                            'td.id_jenis_pembayaran <' => 5,
                            'm.nipd' => $mhs['nipd'],
                            't.semester' => $smtAktifRes['id_smt']
                        ];
                        $data_trx = $this->masterdata->getDataPembayaranChart($param_tx)->row_array();
                        if ($cek_krs_befor != null) {
                            // if ($cek_dispen > 0) {
                            //     $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                            //     $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                            //     $n++;
                            // }
                            if ($data_trx == null && $mhs['no_transkip_nilai'] == null || $cek_dispen > 0) {
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                                $n++;
                            }
                        } elseif ($mhs['tahun_masuk'] == substr($smtAktifRes['id_smt'], 0, 4) && $data_trx == null) {
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                            $n++;
                        }
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();
                    }
                }
            }
        }
        $res['smt_aktif'] = $smtAktifRes['id_smt'];
        $res['smt_befor'] = $smt_befor;
        echo json_encode($res);
    }

    public function getDataBelumBayaranV3()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $res['data'] = array();
        $tahun_masuk = $this->masterdata->getDataAngkatan(['tahun_masuk >' => '2016'])->result_array();
        $cek_krs = $this->aktivasi->cekKrsMhsSimak()->result_array();

        $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
        $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
        $smt_befor = '';
        if ($cek_ganjil_genap == '1') {
            $smt_befor = ($tahun_smt_befor - 1) . '2';
        } else {
            $smt_befor = ($tahun_smt_befor - 1) . '1';
        }

        $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
        foreach ($tahun_masuk as $i => $val) {

            // $res['data'][$i]['angkatan'] = $val['tahun_masuk'];
            $data_prodi = $this->masterdata->getProdi(['tahun_masuk' => $val['tahun_masuk']])->result_array();
            // $res['data'][$val['tahun_masuk']]['total_mhs'] = 0;
            foreach ($data_prodi as $p => $prodi) {
                $data_kelas = $this->masterdata->getKelas(['m.tahun_masuk' => $val['tahun_masuk'], 'm.nm_jur' => $prodi['nm_jur']])->result_array();
                foreach ($data_kelas as $k => $dk) {
                    $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk'], 'nm_jur' => $prodi['nm_jur'], 'id_kelas' => $dk['id_kelas']])->result_array();
                    // $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']] = $list_mhs;

                    // $x = 0;
                    foreach ($list_mhs as $x => $mhs) {
                        $cek_trx = $this->masterdata->getDataPembayaranChart(['t.nim' => $mhs['nipd'], 'td.id_jenis_pembayaran <' => 5, 't.semester' => $smtAktifRes['id_smt']])->row_array();
                        // if ($cek_trx == null) {
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x] = $mhs;
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_trx'] = $cek_trx;
                        // $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_trx'] = $this->masterdata->getDataPembayaranChart(['t.nim' => $mhs['nipd'], 'td.id_jenis_pembayaran <' => 5, 't.semester' => $smtAktifRes['id_smt']])->row_array();
                        if ($mhs['no_transkip_nilai'] == "") {
                            $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['no_transkip_nilai'] = null;
                        }
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_dispen'] = null;
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['krs_befor'] = null;
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['krs'] = null;

                        foreach ($cek_krs as $c => $krs) {
                            if ($krs['nipd'] == $mhs['nipd']) {
                                $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['krs'] = $cek_krs[$c];
                            }
                        }
                        foreach ($cek_krs_befor as $kb => $ckb) {
                            if ($ckb['nipd'] == $mhs['nipd']) {
                                $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['krs_befor'] = $cek_krs_befor[$kb];
                            }
                        }
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();

                        //     $x++;
                        // }
                    }

                } // end kelas looping
            } // end of prodi looping
        }

        $res['smt_aktif'] = $smtAktifRes['id_smt'];
        $res['smt_befor'] = $smt_befor;
        echo json_encode($res);
        // echo '<pre>';
        // var_dump($cek_krs_befor);
        // var_dump($res['data']);
        // echo '</pre>';
        // die;
    }
    public function getDataPembayaran()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            $res['data'] = $this->masterdata->getDataAngkatan()->result_array();
            // $res['data'] = $this->masterdata->getDataAngkatan(['tahun_masuk >' => '2016'])->result_array();
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            // $list_simak = $this->aktivasi->cekStatusKelulusanMhs()->result_array();
            // $cek_krs = $this->aktivasi->cekKrsMhsSimak()->result_array();


            $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
            $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
            $smt_befor = '';
            if ($cek_ganjil_genap == '1') {
                $smt_befor = ($tahun_smt_befor - 1) . '2';
            } else {
                $smt_befor = ($tahun_smt_befor - 1) . '1';
            }

            // $cek_krs_befor = $this->getDataKrs();
            // $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
            $index = 0;
            foreach ($res['data'] as $i => $val) {
                if ($data_post['filter'] != '0') {
                    if ($data_post['filter'] == '2') {
                        $jenis_dispen = '1';
                    }
                    if ($data_post['filter'] == '3') {
                        $jenis_dispen = '3';
                    }
                    if ($data_post['filter'] == '4') {
                        $jenis_dispen = '4';
                    }
                    $res['data'][$i]['jenis_dispen'] = $jenis_dispen;
                    $param_dispen = [
                        'd.tahun_akademik' => $smtAktifRes['id_smt'],
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'd.tg_dispen >' => 0,
                        'd.jenis_dispen' => $jenis_dispen
                    ];
                    $param_tx = [
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'td.id_jenis_pembayaran' => $data_post['filter'],
                        't.semester' => $smtAktifRes['id_smt']
                    ];
                } else {
                    $res['data'][$i]['jenis_dispen'] = 'all';
                    $param_dispen = [
                        'd.tahun_akademik' => $smtAktifRes['id_smt'],
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'd.tg_dispen >' => 0
                    ];

                    $param_tx = [
                        'm.tahun_masuk' => $val['tahun_masuk'],
                        'td.id_jenis_pembayaran <' => 5,
                        't.semester' => $smtAktifRes['id_smt']
                    ];
                }

                $res['data'][$i]['jml_mhs'] = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->num_rows();
                $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->result_array();
                $res['data'][$i]['list_mhs'] = $list_mhs;
                $res['data'][$i]['list_dispen'] = null;
                foreach ($list_mhs as $l => $mhs) {
                    $res['data'][$i]['list_mhs'][$l]['data_dispen'] = null;

                    $res['data'][$i]['list_mhs'][$l]['krs'] = $this->aktivasi->cekKrsMhsLokal(['nipd' => $mhs['nipd']])->result_array();
                    $res['data'][$i]['list_mhs'][$l]['krs_befor'] = $this->aktivasi->cekKrsMhsLokal(['id_tahun_ajaran' => $smt_befor, 'nipd' => $mhs['nipd']])->row_array();


                    $res['data'][$i]['list_mhs'][$l]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();

                    $data_dispen_mhs = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->row_array();
                    if ($data_dispen_mhs != null) {
                        $res['data'][$i]['list_dispen'][] = $data_dispen_mhs;
                    }

                }
                $res['data'][$i]['data_dispen'] = $this->aktivasi->getDataDispenMhs($param_dispen)->num_rows();
                $res['data'][$i]['data_trx'] = $this->masterdata->getDataPembayaranChart($param_tx)->result_array();
                $res['data'][$i]['last_query'] = $this->db->last_query();
                $res['data'][$i]['trx'] = $this->masterdata->getDataPembayaranChart($param_tx)->num_rows();
            }
            $res['smt_aktif'] = $smtAktifRes['id_smt'];
            $res['smt_befor'] = $smt_befor;
            echo json_encode($res);
        } else {
            show_404();
        }
    }

    public function getDataPembayaranV2()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            $res['data'] = $this->masterdata->getDataAngkatan()->result_array();
            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            // $list_simak = $this->aktivasi->cekStatusKelulusanMhs()->result_array();
            // $cek_krs = $this->aktivasi->cekKrsMhsSimak()->result_array();


            $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
            $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
            $smt_befor = '';
            if ($cek_ganjil_genap == '1') {
                $smt_befor = ($tahun_smt_befor - 1) . '2';
            } else {
                $smt_befor = ($tahun_smt_befor - 1) . '1';
            }

            foreach ($res['data'] as $i => $val) {
                $res['data'][$i]['jml_mhs'] = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->num_rows();
                $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->result_array();
                $res['data'][$i]['list_mhs'] = $list_mhs;

                foreach ($list_mhs as $l => $mhs) {
                    // $res['data'][$i]['list_mhs'][$l]['pin'] = null;
                    // $res['data'][$i]['list_mhs'][$l]['judul_skripsi'] = null;
                    // $res['data'][$i]['list_mhs'][$l]['no_seri_ijazah'] = null;
                    // $res['data'][$i]['list_mhs'][$l]['no_transkip_nilai'] = null;
                    // ===========================================================

                    $res['data'][$i]['list_mhs'][$l]['krs_befor'] = null;
                    $res['data'][$i]['list_mhs'][$l]['krs'] = null;
                    $cek_krs = $this->aktivasi->cekKrsMhsLokal(['nipd' => $mhs['nipd']])->row_array();
                    $cek_krs_befor = $this->aktivasi->cekKrsMhsLokal(['nipd' => $mhs['nipd'], 'id_tahun_ajaran' => $smt_befor])->row_array();
                    $res['data'][$i]['list_mhs'][$l]['krs'] = $cek_krs;
                    $res['data'][$i]['list_mhs'][$l]['krs_befor'] = $cek_krs_befor;
                    // $res['data'][$i]['list_mhs'][$l]['query'] = $this->db->last_query();

                    if ($data_post['filter'] != '0') {

                        if ($data_post['filter'] == '2') {
                            $jenis_dispen = '1';
                        }
                        if ($data_post['filter'] == '3') {
                            $jenis_dispen = '3';
                        }
                        if ($data_post['filter'] == '4') {
                            $jenis_dispen = '4';
                        }
                        $res['data'][$i]['jenis_dispen'] = $jenis_dispen;
                        $res['data'][$i]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.tahun_masuk' => $val['tahun_masuk'], 'd.tg_dispen >' => 0, 'd.jenis_dispen' => $jenis_dispen])->num_rows();
                        $where1 = [
                            'm.tahun_masuk' => $val['tahun_masuk'],
                            'td.id_jenis_pembayaran' => $data_post['filter'],
                            't.semester' => $smtAktifRes['id_smt']
                        ];
                        $res['data'][$i]['data_trx'] = $this->masterdata->getDataPembayaranChart($where1)->result_array();
                        $res['data'][$i]['last_query'] = $this->db->last_query();
                    } else {
                        $res['data'][$i]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.tahun_masuk' => $val['tahun_masuk'], 'd.tg_dispen >' => 0])->num_rows();
                        $where1 = [
                            'm.tahun_masuk' => $val['tahun_masuk'],
                            'td.id_jenis_pembayaran <' => 5,
                            't.semester' => $smtAktifRes['id_smt']
                        ];

                        $res['data'][$i]['data_trx'] = $this->masterdata->getDataPembayaranChart($where1)->result_array();

                    }
                    $res['data'][$i]['trx'] = $this->masterdata->getDataPembayaranChart($where1)->num_rows();

                }
            }
            echo json_encode($res);
        } else {
            show_404();
        }
    }

    // public function updateDataMhs()
    // {
    //     $dataUpdate = [];
    //     $list_simak = $this->aktivasi->cekStatusKelulusanMhs()->result_array();

    //     $jml_mhs_lokal = $this->masterdata->getDataListMhs()->num_rows();
    //     for ($i = 0; $i < $jml_mhs_lokal; $i++) {
    //         $id = $list_simak[$i]['id_reg_pd'];
    //         $data = [
    //             'no_transkip_nilai' => $list_simak[$i]['no_transkip_nilai']
    //         ];
    //         $dataUpdate[$i] = $this->masterdata->updateDataMhs($id, $data);
    //     }
    //     // echo '<pre>';
    //     // var_dump($dataUpdate);
    //     // echo '</pre>';
    //     // die;
    // }
}