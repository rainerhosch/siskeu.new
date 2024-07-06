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
        $this->load->model('krs/M_krs', 'krs');
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
        $data = $this->input->get('type');
        $dataRes = array();
        $title = '';
        $title_header = '';
        $nameoffile = '';
        $ker = '';
        $dataLoad = $this->getDataBelumBayaranDanDispen();
        // echo '<pre>';
        // var_dump($dataLoad['data_mhs_belum_bayaran']);
        // echo '</pre>';
        // die;
        if ($data != '1') {
            $title = 'data_mhs_belum_bayaran';
            $title_header = 'LAPORAN DATA MAHASISWA BELUM MELUNASI CICILAN DAN DISPEN';
            $nameoffile = 'Data_mahasiswa_belum_melakukan_pembayaran_dan_dispen_';
            $dataRes = $dataLoad['data_mhs_belum_bayaran'];
        } else {
            $title = 'data_mhs_sudah_bayaran';
            $title_header = 'LAPORAN DATA MAHASISWA SUDAH MELAKUKAN PEMBAYARAN DAN DISPEN';
            $nameoffile = 'Data_mahasiswa_sudah_melakukan_pembayaran_dan_dispen_';
            $dataRes = $dataLoad['data_mhs_sudah_bayaran'];

        }
        // echo json_encode($dataLoad['data']);
        // echo '<pre>';
        // var_dump($dataRes);
        // echo '</pre>';
        // die;

        $spreadsheet = new Spreadsheet();
        $Terbilang = new FormatTerbilang();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($title);

        //define width of column
        $sheet->getColumnDimension('A')->setWidth(20.00);
        $sheet->getColumnDimension('B')->setWidth(19.00);
        $sheet->getColumnDimension('C')->setWidth(22.00);
        $sheet->getColumnDimension('D')->setWidth(15.00);
        $sheet->getColumnDimension('E')->setWidth(35.00);
        $sheet->getColumnDimension('F')->setWidth(47.29);
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
        $sheet->setCellValue('A1', $title_header);
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
        foreach ($dataRes as $i => $data_angkatan) {
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
                                if ($m['jml_bayar_cs'] > 0) {
                                    $status = 'SISA PEMBAYARAN CS, Rp.' . $m['sisa_bayar_cs'];
                                } else {
                                    $status = 'BELUM MELAKUKAN PEMBAYARAN DARI CICILAN 1';
                                }
                            }
                            // if ($m['data_trx'] != null) {
                            //     foreach ($m['data_trx']['detail_trx'] as $trx => $dtx) {

                            //     }
                            // } else {
                            //     $status = 'BELUM MELAKUKAN PEMBAYARAN';
                            // }

                            $sheet->setCellValue('F' . $row_min, $status);
                            $row_tbl++;
                        }
                    }

                }
            }
        }



        $filename = $nameoffile . '(' . $date_now . ')';
        $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function createExcelPayudi()
    {
        $date_now = date("Y-m-d H:i:s");
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $data = $this->input->get('type');
        $dataLoad = $this->getDataBelumBayaranDanDispen();
        $dataRes = $dataLoad['data_mhs_sudah_bayaran'];
        // echo '<pre>';
        // var_dump($dataLoad);
        // echo '</pre>';
        // die;

        // ==================================== CONFIGURATION ==========================================
        $title = 'data_mhs_sudah_bayaran';
        $title_header = 'RENCANA PEMASUKAN UANG KULIAH';
        $title_header_2 = 'STT WASTUKANCANA PURWAKARTA';
        $title_header_smt = '';
        if ($smtAktifRes['smt'] == '1') {
            $title_header_smt = 'SEMESTER GANJIL TAHUN AKADEMIK' . $smtAktifRes['nm_smt'];
        } else {
            $title_header_smt = 'SEMESTER GENAP TAHUN AKADEMIK' . $smtAktifRes['nm_smt'];
        }
        $nameoffile = 'Data_mahasiswa_sudah_melakukan_pembayaran_dan_dispen_';
        // =============================================================================================

        $spreadsheet = new Spreadsheet();
        $Terbilang = new FormatTerbilang();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($title);

        //define width of column
        $sheet->getColumnDimension('A')->setWidth(20.00);
        $sheet->getColumnDimension('B')->setWidth(19.00);
        $sheet->getColumnDimension('C')->setWidth(22.00);
        $sheet->getColumnDimension('D')->setWidth(22.00);
        $sheet->getColumnDimension('E')->setWidth(15.00);
        $sheet->getColumnDimension('F')->setWidth(15.00);
        $sheet->getColumnDimension('G')->setWidth(15.00);
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
        $sheet->setCellValue('A1', $title_header);
        $sheet->mergeCells('A1:H2');
        $sheet->setCellValue('A3', $title_header_2);
        $sheet->mergeCells('A3:H4');
        $sheet->setCellValue('A5', $title_header_smt);
        $sheet->mergeCells('A5:H6');
        $sheet->mergeCells('A7:H7');
        $sheet->getStyle('A1:H6')->getFont()->setBold(true);
        $sheet->getStyle('A1:H6')->getFont()->setSize(14);
        $sheet->getStyle('A1:H6')->applyFromArray($styleTitle); //styling header table

        $row_header = 8;
        $merge_header = $row_header + 1;
        $sheet->setCellValue('A' . $row_header, 'ANGKATAN');
        $sheet->setCellValue('B' . $row_header, 'PRODI');
        $sheet->setCellValue('C' . $row_header, 'KELAS');
        $sheet->setCellValue('D' . $row_header, 'JUMLAH MHS AKTIF');
        $sheet->setCellValue('E' . $row_header, 'DATA PEMBAYARAN');
        $sheet->mergeCells('E' . $row_header . ':' . 'G' . $row_header);
        $sheet->setCellValue('E' . ($row_header + 1), 'Cicilan 1');
        $sheet->setCellValue('F' . ($row_header + 1), 'Cicilan 2');
        $sheet->setCellValue('G' . ($row_header + 1), 'Cicilan 3');
        $sheet->setCellValue('H' . $row_header, 'DISPEN');
        // $sheet->setCellValue('G' . $row_header, 'KETERANGAN');
        // $sheet->setCellValue('H' . $row_header, 'Admin');
        // $sheet->setCellValue('I' . $row_header, 'Keterangan');

        // merge all header
        $sheet->mergeCells('A' . $row_header . ':A' . $merge_header);
        $sheet->mergeCells('B' . $row_header . ':B' . $merge_header);
        $sheet->mergeCells('C' . $row_header . ':C' . $merge_header);
        $sheet->mergeCells('D' . $row_header . ':D' . $merge_header);
        // $sheet->mergeCells('F' . $row_header . ':F' . $merge_header);
        // $sheet->mergeCells('G' . $row_header . ':G' . $merge_header);
        $sheet->mergeCells('H' . $row_header . ':H' . $merge_header);
        // $sheet->mergeCells('I' . $row_header . ':I' . $merge_header);
        //styling title
        $sheet->getStyle('A' . $row_header . ':H' . $row_header)->getAlignment()->setHorizontal(PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row_header . ':H' . $row_header)->getAlignment()->setVertical(PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row_header . ':H' . $row_header)->getFont()->setBold(true);
        $sheet->getStyle('A' . $merge_header . ':H' . $merge_header)->getFont()->setBold(true);

        $row_tbl = 9;
        $row_min = 0;
        $row_start = 10;
        foreach ($dataRes as $i => $data_angkatan) {
            $i++;
            foreach ($data_angkatan as $angkatan => $a) {
                foreach ($a as $prodi => $p) {
                    foreach ($p as $kelas => $k) {
                        // $row_max = $row_tbl + 1;
                        $row_min = $row_tbl + 1;
                        $sheet->setCellValue('A' . $row_min, $angkatan);
                        $sheet->setCellValue('B' . $row_min, $prodi);
                        $sheet->setCellValue('C' . $row_min, $kelas);

                        $sheet->setCellValue('D' . $row_min, count($k));
                        $jml_pembayaran_c1 = 0;
                        $jml_pembayaran_c2 = 0;
                        $jml_pembayaran_c3 = 0;
                        $jml_dispen = 0;
                        foreach ($k as $mhs => $m) {
                            $data_trx = $m['data_trx'];
                            $data_dispen = $m['data_dispen'];
                            if ($data_trx != null) {
                                // $data_trx_detail = $m['data_trx']['detail_trx'];
                                foreach ($data_trx['detail_trx'] as $x => $detail_trx) {
                                    if ($detail_trx['id_jenis_pembayaran'] == '2') {
                                        $jml_pembayaran_c1 = $jml_pembayaran_c1 + $detail_trx['jml_bayar'];
                                    }
                                    if ($detail_trx['id_jenis_pembayaran'] == '3') {
                                        $jml_pembayaran_c2 = $jml_pembayaran_c2 + $detail_trx['jml_bayar'];
                                    }
                                    if ($detail_trx['id_jenis_pembayaran'] == '4') {
                                        $jml_pembayaran_c3 = $jml_pembayaran_c3 + $detail_trx['jml_bayar'];
                                    }
                                }
                            } elseif ($data_dispen != null) {
                                $jml_dispen = $jml_dispen + $data_dispen['tg_dispen'];
                            }
                            // echo '<pre>';
                            // var_dump($data_trx);
                            // echo '</pre>';
                            // die;
                            // foreach (data_trx_detail as $dt => $data_trx) {
                            //         if ($detail_trx['id_jenis_pembayaran'] == '2') {
                            //             $jml_pembayaran_c1 = $jml_pembayaran_c1 + $detail_trx['jml_bayar'];
                            //         }
                            //         if ($detail_trx['id_jenis_pembayaran'] == '3') {
                            //             $jml_pembayaran_c2 = $jml_pembayaran_c2 + $detail_trx['jml_bayar'];
                            //         }
                            //         if ($detail_trx['id_jenis_pembayaran'] == '4') {
                            //             $jml_pembayaran_c3 = $jml_pembayaran_c3 + $detail_trx['jml_bayar'];
                            //         }
                            // }
                        }
                        $sheet->setCellValue('E' . $row_min, $jml_pembayaran_c1);
                        $sheet->setCellValue('F' . $row_min, $jml_pembayaran_c2);
                        $sheet->setCellValue('G' . $row_min, $jml_pembayaran_c3);
                        $sheet->setCellValue('H' . $row_min, $jml_dispen);
                        $sheet->getStyle('A' . $row_min . ':H' . $row_min)->getAlignment()->setHorizontal(PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('A' . $row_min . ':H' . $row_min)->getAlignment()->setVertical(PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                        $row_tbl++;
                        // foreach ($k as $mhs => $m) {
                        //     $status = '';
                        //     $row_min = $row_tbl + 1;
                        //     $sheet->setCellValue('A' . $row_min, $angkatan);
                        //     $sheet->setCellValue('B' . $row_min, $prodi);
                        //     $sheet->setCellValue('C' . $row_min, $kelas);
                        //     $sheet->setCellValue('D' . $row_min, $m['nipd']);
                        //     $sheet->setCellValue('E' . $row_min, $m['nm_pd']);
                        //     if ($m['data_dispen'] != null) {
                        //         $status = 'DISPEN';
                        //     } else {
                        //         $status = 'SUDAH MELAKUKAN PEMBAYARAN';
                        //     }
                        //     $sheet->setCellValue('H' . $row_min, $status);
                        //     $row_tbl++;
                        // }
                    }

                }
            }
        }


        $sheet->setCellValue('A' . $row_min + 1, 'TOTAL');
        $sheet->mergeCells('A' . $row_min + 1 . ':C' . $row_min + 1);
        $sheet->setCellValue('D' . $row_min + 1, '=SUM(D' . $row_start . ':D' . $row_min . ')');
        $sheet->setCellValue('E' . $row_min + 1, '=SUM(E' . $row_start . ':E' . $row_min . ')');
        $sheet->setCellValue('F' . $row_min + 1, '=SUM(F' . $row_start . ':F' . $row_min . ')');
        $sheet->setCellValue('G' . $row_min + 1, '=SUM(G' . $row_start . ':G' . $row_min . ')');
        $sheet->setCellValue('H' . $row_min + 1, '=SUM(H' . $row_start . ':H' . $row_min . ')');

        $sheet->getStyle('A' . $row_min + 1 . ':H' . $row_min + 1)->getAlignment()->setHorizontal(PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row_min + 1 . ':H' . $row_min + 1)->getAlignment()->setVertical(PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row_min + 1 . ':H' . $row_min + 1)->getFont()->setBold(true);

        $filename = $nameoffile . '(' . $date_now . ')';
        $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

    }

    public function getDataTestV2()
    {
        // $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        // $res['data'] = array();

        // $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
        // $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
        // $smt_befor = '';
        // if ($cek_ganjil_genap == '1') {
        //     $smt_befor = ($tahun_smt_befor - 1) . '2';
        // } else {
        //     $smt_befor = ($tahun_smt_befor) . '1';
        // }


        // $data_krs = $this->krs->getDataKrsMhs(['kn.id_tahun_ajaran' => $smtAktifRes['id_smt'] - 1, 'm.no_transkip_nilai' => null])->result_array();
        // $grouped_by = [];
        // foreach ($data_krs as $i => $mhs) {
        //     $tahun_masuk = $mhs['tahun_masuk'];
        //     if (!isset($grouped_by[$tahun_masuk])) {
        //         $grouped_by[$tahun_masuk] = [];
        //     }
        //     $mhs['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();

        //     $grouped_by[$tahun_masuk][] = $mhs;
        //     // $res['data']=$grouped_by;
        // }
        // foreach ($grouped_by as $g => $thn) {
        //     $tahun_masuk2[] = $g;
        // }

        // foreach ($tahun_masuk2 as $tm => $val) {
        //     $param_dispen = [
        //         'd.tahun_akademik' => $smtAktifRes['id_smt'],
        //         'm.tahun_masuk' => $val,
        //         'd.tg_dispen >' => 0
        //     ];

        //     $param_tx = [
        //         'm.tahun_masuk' => $val,
        //         'td.id_jenis_pembayaran <' => 5,
        //         't.semester' => $smtAktifRes['id_smt'],
        //         // filter untuk tidak mengambil data trx beasiswa seperti KIP, dll. (dengan set uang masuk = 1)
        //         't.uang_masuk' => 1,
        //     ];
        //     $res['data'][$tm]['tahun_masuk'] = $val;
        //     $res['data'][$tm]['total_mhs'] = count($grouped_by[$val]);
        //     // $res['data'][$tm]['list_mhs'] = $grouped_by[$val];
        //     $res['data'][$tm]['data_dispen'] = $this->aktivasi->getDataDispenMhs($param_dispen)->num_rows();
        //     $res['data'][$tm]['trx'] = $this->masterdata->getDataPembayaranChart($param_tx)->num_rows();
        // }
        // Ambil data semester aktif
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $res['data'] = array();

        // Tentukan semester sebelumnya
        $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
        $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
        $smt_befor = ($cek_ganjil_genap == '1') ? ($tahun_smt_befor - 1) . '2' : $tahun_smt_befor . '1';

        // Ambil data KRS mahasiswa
        $data_krs = $this->krs->getDataKrsMhs([
            'kn.id_tahun_ajaran' => $smtAktifRes['id_smt'] - 1,
            'm.no_transkip_nilai' => null
        ])->result_array();

        // Siapkan array untuk hasil pengelompokan
        $grouped_by = [];

        // Proses pengelompokan dan hitung data dispen
        foreach ($data_krs as $mhs) {
            $tahun_masuk = $mhs['tahun_masuk'];
            if (!isset($grouped_by[$tahun_masuk])) {
                $grouped_by[$tahun_masuk] = [
                    'tahun_masuk' => $tahun_masuk,
                    'total_mhs' => 0,
                    'data_dispen' => 0,
                    'trx' => 0
                ];
            }

            // Tambahkan data mahasiswa ke dalam grup
            $grouped_by[$tahun_masuk]['total_mhs']++;

            // Hitung data dispen per mahasiswa
            $grouped_by[$tahun_masuk]['data_dispen'] += $this->aktivasi->getDataDispenMhs([
                'd.tahun_akademik' => $smtAktifRes['id_smt'],
                'm.nipd' => $mhs['nipd'],
                'd.tg_dispen >' => 0,
                'status' => 0
            ])->num_rows();
        }

        // Hitung data transaksi per tahun masuk
        foreach ($grouped_by as $tahun_masuk => &$data) {
            $data['trx'] = $this->masterdata->getDataPembayaranChart([
                'm.tahun_masuk' => $tahun_masuk,
                'td.id_jenis_pembayaran <' => 5,
                't.semester' => $smtAktifRes['id_smt'],
                't.uang_masuk' => 1,
            ])->num_rows();
        }

        // Memasukkan hasil ke dalam res['data']
        $res['data'] = array_values($grouped_by);

        $res['smt_aktif'] = $smtAktifRes['id_smt'];
        $res['smt_befor'] = $smt_befor;
        $res['tahun_smt_aktif'] = substr($smtAktifRes['id_smt'], 0, 4);
        echo json_encode($res);
    }

    public function getDataTest()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $res['data'] = array();
        $tahun_masuk = $this->masterdata->getDataAngkatan(['tahun_masuk >' => '2016'])->result_array();

        $tahun_smt_befor = substr($smtAktifRes['id_smt'], 0, 4);
        $cek_ganjil_genap = substr($smtAktifRes['id_smt'], 4);
        $smt_befor = '';
        if ($cek_ganjil_genap == '1') {
            $smt_befor = ($tahun_smt_befor - 1) . '2';
        } else {
            $smt_befor = ($tahun_smt_befor - 1) . '1';
        }

        foreach ($tahun_masuk as $i => $val) {
            $data_prodi = $this->masterdata->getProdi(['tahun_masuk' => $val['tahun_masuk'], 'id_jur <>' => '3'])->result_array();
            $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->result_array();
            foreach ($list_mhs as $x => $mhs) {
                $res['data'][$i][$val['tahun_masuk']][$x] = $mhs;
            }
        }

        echo '<pre>';
        print_r($res);
        echo '</pre>';
        die;

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
        $res['data_mhs_sudah_bayaran'] = array();


        // $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
        foreach ($tahun_masuk as $i => $val) {
            $data_prodi = $this->masterdata->getProdi(['tahun_masuk' => $val['tahun_masuk'], 'id_jur <>' => '3'])->result_array();
            foreach ($data_prodi as $p => $prodi) {
                $data_kelas = $this->masterdata->getKelas(['m.tahun_masuk' => $val['tahun_masuk'], 'm.nm_jur' => $prodi['nm_jur']])->result_array();
                foreach ($data_kelas as $k => $dk) {
                    $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk'], 'nm_jur' => $prodi['nm_jur'], 'id_kelas' => $dk['id_kelas']])->result_array();
                    // $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']] = $list_mhs;

                    $n = 0;
                    $m = 0;
                    $jml_bayar_cs = 0;
                    foreach ($list_mhs as $x => $mhs) {
                        $mhs['jml_bayar_cs'] = 0;
                        $mhs['sisa_bayar_cs'] = 0;
                        $cek_krs_befor = $this->aktivasi->cekKrsMhsLokal(['id_tahun_ajaran' => $smt_befor, 'nipd' => $mhs['nipd']])->row_array();

                        $cek_dispen = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();
                        $data_dispen = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->row_array();

                        // $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['krs_befor'] = null;
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x] = $mhs;
                        $param_ps = [
                            'm.tahun_masuk' => $mhs['tahun_masuk'],
                            'td.id_jenis_pembayaran' => 8,
                            'm.nipd' => $mhs['nipd'],
                            't.semester' => $smtAktifRes['id_smt']
                        ];
                        $cek_perpanjang_smt = $this->masterdata->getDataPembayaranChart($param_ps)->num_rows();
                        $mhs['perpanjang_smt'] = $cek_perpanjang_smt;
                        $biayaCS = $this->masterdata->getBiayaAngkatan(['angkatan' => $mhs['tahun_masuk']], $mhs['nm_jenj_didik'])->row_array();
                        if ($cek_perpanjang_smt > 0) {
                            $mhs['kewajiban_cs'] = $biayaCS['cicilan_semester'] / 2;
                            $param_tx = [
                                'm.tahun_masuk' => $mhs['tahun_masuk'],
                                'td.id_jenis_pembayaran' => 8,
                                'm.nipd' => $mhs['nipd'],
                                't.semester' => $smtAktifRes['id_smt']
                            ];
                        } else {
                            $param_tx = [
                                'm.tahun_masuk' => $mhs['tahun_masuk'],
                                'td.id_jenis_pembayaran <' => 5,
                                'm.nipd' => $mhs['nipd'],
                                't.semester' => $smtAktifRes['id_smt']
                            ];
                            $mhs['kewajiban_cs'] = $biayaCS['cicilan_semester'];
                        }

                        $cek_trx = $this->masterdata->getDataPembayaranChart($param_tx)->num_rows();
                        $data_trx = $this->masterdata->getDataPembayaranChart($param_tx)->result_array();

                        if ($cek_trx > 0) {
                            foreach ($data_trx as $x => $tx) {
                                $data_trx[$x]['detail_trx'] = $this->masterdata->getDataDetailPembayaranChart(['id_transaksi' => $tx['id_transaksi']])->result_array();
                                foreach ($data_trx[$x]['detail_trx'] as $dt => $dtx) {
                                    if ($dtx['id_jenis_pembayaran'] === '2') {
                                        $mhs['jml_bayar_cs'] = $mhs['jml_bayar_cs'] + $dtx['jml_bayar'];
                                    }
                                    if ($dtx['id_jenis_pembayaran'] === '3') {
                                        $mhs['jml_bayar_cs'] = $mhs['jml_bayar_cs'] + $dtx['jml_bayar'];
                                    }
                                    if ($dtx['id_jenis_pembayaran'] === '4') {
                                        $mhs['jml_bayar_cs'] = $mhs['jml_bayar_cs'] + $dtx['jml_bayar'];
                                    }
                                    if ($dtx['id_jenis_pembayaran'] === '8') {
                                        $mhs['jml_bayar_cs'] = $mhs['jml_bayar_cs'] + $dtx['jml_bayar'];
                                    }
                                }
                            }
                        }

                        $mhs['sisa_bayar_cs'] = $mhs['kewajiban_cs'] - $mhs['jml_bayar_cs'];
                        if ($cek_krs_befor != null) {
                            // if ($cek_dispen > 0) {
                            //     $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                            //     $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                            //     $n++;
                            // }
                            if ($mhs['sisa_bayar_cs'] > 0 && $mhs['no_transkip_nilai'] == null || $cek_dispen > 0) {
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                                $n++;
                            } else {
                                // if ($cek_trx > 0 && $mhs['no_transkip_nilai'] == null || $cek_dispen > 0) {
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m] = $mhs;
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_dispen'] = $data_dispen;
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_trx'] = $data_trx;
                                $m++;
                            }
                        } else {
                            if ($mhs['tahun_masuk'] == substr($smtAktifRes['id_smt'], 0, 4) && $mhs['sisa_bayar_cs'] > 0) {
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                                $n++;
                            } else {
                                // if ($mhs['tahun_masuk'] == substr($smtAktifRes['id_smt'], 0, 4) && $cek_trx > 0) {
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m] = $mhs;
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_dispen'] = $data_dispen;
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_trx'] = $data_trx;
                                $m++;
                            }

                        }
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();
                    }
                }
            }
        }
        $res['smt_aktif'] = $smtAktifRes['id_smt'];
        $res['smt_befor'] = $smt_befor;
        $res['tahun_smt_aktif'] = substr($smtAktifRes['id_smt'], 0, 4);
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
            $smt_befor = ($tahun_smt_befor) . '1';
        }
        $res['data_mhs_belum_bayaran'] = array();
        $res['data_mhs_sudah_bayaran'] = array();


        // $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
        foreach ($tahun_masuk as $i => $val) {
            $data_prodi = $this->masterdata->getProdi(['tahun_masuk' => $val['tahun_masuk']])->result_array();
            foreach ($data_prodi as $p => $prodi) {
                $data_kelas = $this->masterdata->getKelas(['m.tahun_masuk' => $val['tahun_masuk'], 'm.nm_jur' => $prodi['nm_jur']])->result_array();
                foreach ($data_kelas as $k => $dk) {
                    $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk'], 'nm_jur' => $prodi['nm_jur'], 'id_kelas' => $dk['id_kelas']])->result_array();
                    // $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']] = $list_mhs;

                    $n = 0;
                    $m = 0;
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
                        $cek_trx = $this->masterdata->getDataPembayaranChart($param_tx)->num_rows();
                        $data_trx = $this->masterdata->getDataPembayaranChart($param_tx)->result_array();
                        if ($cek_krs_befor != null) {
                            if ($cek_trx == 0 && $mhs['no_transkip_nilai'] == null || $cek_dispen > 0) {
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                                $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                                $n++;
                            }
                            if ($data_trx > 0 && $mhs['no_transkip_nilai'] == null || $cek_dispen > 0) {
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m] = $mhs;
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_dispen'] = $data_dispen;
                                $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_trx'] = $data_trx;
                                $m++;
                            }
                        } elseif ($mhs['tahun_masuk'] == substr($smtAktifRes['id_smt'], 0, 4) && $cek_trx == 0) {
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n] = $mhs;
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_dispen'] = $data_dispen;
                            $res['data_mhs_belum_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$n]['data_trx'] = $data_trx;
                            $n++;
                        } elseif ($mhs['tahun_masuk'] == substr($smtAktifRes['id_smt'], 0, 4) && $cek_trx > 0) {
                            $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m] = $mhs;
                            $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_dispen'] = $data_dispen;
                            $res['data_mhs_sudah_bayaran'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$m]['data_trx'] = $data_trx;
                            $m++;
                        }
                        $res['data'][$i][$val['tahun_masuk']][$prodi['nm_jur']][$dk['nama_kelas']][$x]['data_dispen'] = $this->aktivasi->getDataDispenMhs(['d.tahun_akademik' => $smtAktifRes['id_smt'], 'm.nipd' => $mhs['nipd'], 'd.tg_dispen >' => 0, 'status' => 0])->num_rows();
                    }
                }
            }
        }
        $res['smt_aktif'] = $smtAktifRes['id_smt'];
        $res['smt_befor'] = $smt_befor;
        $res['tahun_smt_aktif'] = substr($smtAktifRes['id_smt'], 0, 4);
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
        $res['tahun_smt_aktif'] = substr($smtAktifRes['id_smt'], 0, 4);
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
            $res['data'] = $this->masterdata->getDataAngkatan(['tahun_masuk >' => '2016'])->result_array();
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
                $smt_befor = ($tahun_smt_befor) . '1';
            }

            // var_dump($smt_befor);
            // die;


            // $cek_krs_befor = $this->getDataKrs();
            // $cek_krs_befor = $this->aktivasi->cekKrsMhsSimakBefor(['id_tahun_ajaran' => $smt_befor])->result_array();
            $index = 0;
            foreach ($res['data'] as $i => $val) {
                $res['data'][$i]['trx_befor'] = null;
                if ($data_post['filter'] != '0') {
                    if ($data_post['filter'] == '2') {
                        $jenis_dispen = '1';
                    }
                    if ($data_post['filter'] == '3') {
                        $jenis_dispen = '3';
                        $res['data'][$i]['trx_befor'] = $this->masterdata->getDataPembayaranChart([
                            'm.tahun_masuk' => $val['tahun_masuk'],
                            'td.id_jenis_pembayaran' => '2',
                            't.semester' => $smtAktifRes['id_smt']
                        ])->num_rows();
                    }
                    if ($data_post['filter'] == '4') {
                        $jenis_dispen = '4';
                        $res['data'][$i]['trx_befor'] = $this->masterdata->getDataPembayaranChart([
                            'm.tahun_masuk' => $val['tahun_masuk'],
                            'td.id_jenis_pembayaran' => '2',
                            't.semester' => $smtAktifRes['id_smt']
                        ])->num_rows();
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
                        't.semester' => $smtAktifRes['id_smt'],
                        // filter untuk tidak mengambil data trx beasiswa seperti KIP, dll. (dengan set uang masuk = 1)
                        't.uang_masuk' => 1,
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
                        't.semester' => $smtAktifRes['id_smt'],
                        // filter untuk tidak mengambil data trx beasiswa seperti KIP, dll. (dengan set uang masuk = 1)
                        't.uang_masuk' => 1,
                    ];
                }


                $res['data'][$i]['jml_mhs'] = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->num_rows();
                $list_mhs = $this->masterdata->getDataListMhs(['tahun_masuk' => $val['tahun_masuk']])->result_array();
                $res['data'][$i]['list_mhs'] = $list_mhs;
                $res['data'][$i]['list_dispen'] = null;
                // var_dump($res['data']);
                // die;
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
            $res['tahun_smt_aktif'] = substr($smtAktifRes['id_smt'], 0, 4);
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
                $smt_befor = ($tahun_smt_befor) . '1';
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