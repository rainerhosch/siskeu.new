<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : Laporan.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 06/08/2021
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use \PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan_cicilan extends CI_Controller
{
    private $smt_aktif;
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
        // $token = 'semogabahagia';
        // $this->smt_aktif = getSemesterAktif($token);
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('pagination');


        $this->load->model('M_laporan_cicilan', 'cicilan');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_menu', 'menu');
        $this->load->model('M_user', 'user');
    }

    public function index()
    {
        $data_post = $this->input->get();
        // var_dump($data_post);
        // die;
        $biaya_angkatan = $this->cicilan->biaya_angkatan()->result_array();
        $kls_cicilan = $this->cicilan->kls_cicilan(['kelas_kuliah.id_smt' => $data_post['smt']])->result_array();
        $id_kls = [];
        foreach ($kls_cicilan as $key => $val) {
            $id_kls[] = $val['id_kls'];
        }

        $angkatan = [];
        foreach ($biaya_angkatan as $key => $val) {
            $angkatan[$val['angkatan']]['S1'] = $val['CS'];
            $angkatan[$val['angkatan']]['D3'] = $val['CS_D3'];
        }

        $data_cicilan = $this->cicilan->data_cicilan(['semester' => $data_post['smt']])->result_array();

        $cicilan = [];
        $people_perpanjang = [];
        foreach ($data_cicilan as $key => $val) {
            if ($val['id_jenis_pembayaran'] == 2) {
                $cicilan[$val['nim']]['cicilan1'] = $val['bayar'];
            } else if ($val['id_jenis_pembayaran'] == 3) {
                $cicilan[$val['nim']]['cicilan2'] = $val['bayar'];
            } else if ($val['id_jenis_pembayaran'] == 4) {
                $cicilan[$val['nim']]['cicilan3'] = $val['bayar'];
            } else if ($val['id_jenis_pembayaran'] == 8) {
                $cicilan[$val['nim']]['perpanjangan_semester'] = $val['bayar'];

                // $cicilan[$val['nim']]['cicilan1'] = $val['bayar'];
                // $cicilan[$val['nim']]['cicilan2'] = $val['bayar'];
                // $cicilan[$val['nim']]['cicilan3'] = $val['bayar'];

                $people_perpanjang[] = $val['nim'];
            }


        }

        // echo '<pre>';
        // echo print_r($people_perpanjang);
        // echo '</pre>';
        // exit();

        $krs_cicilan = $this->cicilan->krs_cicilan(['krs_new.id_tahun_ajaran' => $data_post['smt']], $id_kls)->result_array();
        $KRS = [];
        foreach ($krs_cicilan as $key => $val) {
            $KRS[$key] = $val;

            if (in_array($val['nipd'], $people_perpanjang))
            {
                $biaya_perp = $angkatan[$val['angkatan']][$val['jenjang']] / 2;
                $KRS[$key]['cicilan1'] = $biaya_perp / 3;
                $KRS[$key]['cicilan2'] = $biaya_perp / 3;
                $KRS[$key]['cicilan3'] = $biaya_perp / 3;
                $KRS[$key]['bayar1'] = isset($cicilan[$val['nipd']]['perpanjangan_semester']) && $cicilan[$val['nipd']]['perpanjangan_semester'] >= $KRS[$key]['cicilan1'] ? $KRS[$key]['cicilan1'] : 0;
                $estim_c2  = $cicilan[$val['nipd']]['perpanjangan_semester'] - $KRS[$key]['cicilan1'];
                $estim_c22 = $estim_c2 > $KRS[$key]['cicilan1'] ? $KRS[$key]['cicilan1'] : $estim_c2;
                $KRS[$key]['bayar2'] = $estim_c22;
                $estim_c3  = $estim_c2 > $KRS[$key]['cicilan1'] ? $KRS[$key]['cicilan1'] : $estim_c2;
                // $estim_c33 = 
                $KRS[$key]['bayar3'] =  $estim_c22 >= $KRS[$key]['cicilan1'] ? $estim_c3 : 0;
            }
            else
            {
                $KRS[$key]['cicilan1'] = $angkatan[$val['angkatan']][$val['jenjang']] / 3;
                $KRS[$key]['cicilan2'] = $angkatan[$val['angkatan']][$val['jenjang']] / 3;
                $KRS[$key]['cicilan3'] = $angkatan[$val['angkatan']][$val['jenjang']] / 3;

                $KRS[$key]['bayar1'] = isset($cicilan[$val['nipd']]['cicilan1']) ? $cicilan[$val['nipd']]['cicilan1'] : 0;
                $KRS[$key]['bayar2'] = isset($cicilan[$val['nipd']]['cicilan2']) ? $cicilan[$val['nipd']]['cicilan2'] : 0;
                $KRS[$key]['bayar3'] = isset($cicilan[$val['nipd']]['cicilan3']) ? $cicilan[$val['nipd']]['cicilan3'] : 0;

            }

            
            $KRS[$key]['sisa1'] = $KRS[$key]['cicilan1'] - $KRS[$key]['bayar1'];
            $KRS[$key]['sisa2'] = $KRS[$key]['cicilan2'] - $KRS[$key]['bayar2'];
            $KRS[$key]['sisa3'] = $KRS[$key]['cicilan3'] - $KRS[$key]['bayar3'];
            $KRS[$key]['total_cicilan'] = $KRS[$key]['cicilan1'] + $KRS[$key]['cicilan2'] + $KRS[$key]['cicilan3'];
            $KRS[$key]['total_bayar'] = $KRS[$key]['bayar1'] + $KRS[$key]['bayar2'] + $KRS[$key]['bayar3'];
            $KRS[$key]['total_sisa'] = $KRS[$key]['sisa1'] + $KRS[$key]['sisa2'] + $KRS[$key]['sisa3'];
        }
        // echo '<pre>';
        // echo print_r($KRS);
        // echo '</pre>';
        // exit();
        $spreadsheet = new Spreadsheet();
        $Terbilang = new FormatTerbilang();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setTitle('Data_Dispen_Semester(' . $smtAktif . ')');
        $sheet->setTitle('Data_cicilan_' . $data_post['smt']);
        //define width of column
        $sheet->getColumnDimension('A')->setWidth(5.43);
        $sheet->getColumnDimension('B')->setWidth(13.00);
        $sheet->getColumnDimension('C')->setWidth(36.00);
        $sheet->getColumnDimension('D')->setWidth(25.00);
        $sheet->getColumnDimension('E')->setWidth(18.00);
        $sheet->getColumnDimension('F')->setWidth(18.00);
        $sheet->getColumnDimension('G')->setWidth(18.00);
        $sheet->getColumnDimension('H')->setWidth(18.00);
        $sheet->getColumnDimension('I')->setWidth(18.00);
        $sheet->getColumnDimension('J')->setWidth(18.00);
        $sheet->getColumnDimension('K')->setWidth(18.00);
        $sheet->getColumnDimension('L')->setWidth(18.00);
        $sheet->getColumnDimension('M')->setWidth(18.00);
        $sheet->getColumnDimension('N')->setWidth(18.00);
        $sheet->getColumnDimension('O')->setWidth(18.00);
        $sheet->getColumnDimension('P')->setWidth(18.00);
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
        // configurasi Title
        // $sheet->setCellValue('A1', 'DAFTAR TAGIHAN UANG KULIAH ' . $jenis_cicilan . ' SEMESTER ' . $jns_smt . ' ' . $smtAktifRes['id_thn_ajaran'] . '/' . $smtAktifRes['id_thn_ajaran'] + 1);
        $sheet->setCellValue('A1', 'DAFTAR CICILAN SEMESTER ' . $data_post['smt']);
        $sheet->mergeCells('A1:P2');
        // $sheet->setCellValue('A3', $smtAktifRes['nm_smt']);
        // $sheet->setCellValue('A3', '2021/2022');
        $sheet->mergeCells('A3:P4');
        $sheet->getStyle('A1:P4')->getFont()->setSize(12);
        $sheet->getStyle('A1:P4')->getFont()->setBold(true);
        $sheet->getStyle('A1:P4')->applyFromArray($styleTitle); //styling header table

        $row_tbl = 8;
        $no = 1;
        $row_header = 6;
        $merge_header = $row_header + 1;
        $sheet->setCellValue('A' . $row_header, 'NO');
        $sheet->setCellValue('B' . $row_header, 'NIM');
        $sheet->setCellValue('C' . $row_header, 'NAMA');
        $sheet->setCellValue('D' . $row_header, 'PRODI');
        $sheet->setCellValue('E' . $row_header, 'RINCIAN TAGIHAN C1');
        $sheet->setCellValue('F' . $row_header, 'RINCIAN TAGIHAN C2');
        $sheet->setCellValue('G' . $row_header, 'RINCIAN TAGIHAN C3');
        $sheet->setCellValue('H' . $row_header, 'BAYAR C1');
        $sheet->setCellValue('I' . $row_header, 'BAYAR C2');
        $sheet->setCellValue('J' . $row_header, 'BAYAR C3');
        $sheet->setCellValue('K' . $row_header, 'SISA C1');
        $sheet->setCellValue('L' . $row_header, 'SISA C2');
        $sheet->setCellValue('M' . $row_header, 'SISA C3');
        $sheet->setCellValue('N' . $row_header, 'TOTAL TAGIHAN');
        $sheet->setCellValue('O' . $row_header, 'TOTAL BAYAR');
        $sheet->setCellValue('P' . $row_header, 'SISA SISA');

        foreach ($KRS as $key => $va) {
            $sheet->setCellValue('A' . $row_tbl + $key, $key + 1);
            $sheet->setCellValue('B' . $row_tbl + $key, $va['nipd']);
            $sheet->setCellValue('C' . $row_tbl + $key, $va['nm_pd']);
            $sheet->setCellValue('D' . $row_tbl + $key, $va['nm_jur']);
            $sheet->setCellValue('E' . $row_tbl + $key, $va['cicilan1']);
            $sheet->setCellValue('F' . $row_tbl + $key, $va['cicilan2']);
            $sheet->setCellValue('G' . $row_tbl + $key, $va['cicilan3']);
            $sheet->setCellValue('H' . $row_tbl + $key, $va['bayar1']);
            $sheet->setCellValue('I' . $row_tbl + $key, $va['bayar2']);
            $sheet->setCellValue('J' . $row_tbl + $key, $va['bayar3']);
            $sheet->setCellValue('K' . $row_tbl + $key, $va['sisa1']);
            $sheet->setCellValue('L' . $row_tbl + $key, $va['sisa2']);
            $sheet->setCellValue('M' . $row_tbl + $key, $va['sisa3']);
            $sheet->setCellValue('N' . $row_tbl + $key, $va['total_cicilan']);
            $sheet->setCellValue('O' . $row_tbl + $key, $va['total_bayar']);
            $sheet->setCellValue('P' . $row_tbl + $key, $va['total_sisa']);
        }

        $filename = 'DATA LAPORAN CICILAN ' . $data_post['smt'];
        $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        //styling ti
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Laporan Cicilan';
        $data['content'] = 'laporan/cicilan';
        $this->load->view('template', $data);
        // echo json_encode($data);
    }

    public function chartCicilan()
    {
        $data_post = $this->input->post();
        $biaya_angkatan = $this->cicilan->biaya_angkatan()->result_array();
        $kls_cicilan = $this->cicilan->kls_cicilan(['kelas_kuliah.id_smt' => $data_post['smt']])->result_array();
        $id_kls = [];
        foreach ($kls_cicilan as $key => $val) {
            $id_kls[] = $val['id_kls'];
        }

        $angkatan = [];
        foreach ($biaya_angkatan as $key => $val) {
            $angkatan[$val['angkatan']]['S1'] = $val['CS'];
            $angkatan[$val['angkatan']]['D3'] = $val['CS_D3'];
        }

        $data_cicilan = $this->cicilan->data_cicilan(['semester' => $data_post['smt']])->result_array();
        $cicilan = [];
        $people_perpanjang = [];
        foreach ($data_cicilan as $key => $val) {
            if ($val['id_jenis_pembayaran'] == 2) {
                $cicilan[$val['nim']]['cicilan1'] = $val['bayar'];
            } else if ($val['id_jenis_pembayaran'] == 3) {
                $cicilan[$val['nim']]['cicilan2'] = $val['bayar'];
            } else if ($val['id_jenis_pembayaran'] == 4) {
                $cicilan[$val['nim']]['cicilan3'] = $val['bayar'];
            } else if ($val['id_jenis_pembayaran'] == 8) {
                $cicilan[$val['nim']]['perpanjangan_semester'] = $val['bayar'];
                $people_perpanjang[] = $val['nim'];
            }
        }

        $krs_cicilan = $this->cicilan->krs_cicilan(['krs_new.id_tahun_ajaran' => $data_post['smt']], $id_kls)->result_array();
        $KRS = [];

        $chartT1 = 0;
        $chartT2 = 0;
        $chartT3 = 0;

        $chartB1 = 0;
        $chartB2 = 0;
        $chartB3 = 0;

        $chartS1 = 0;
        $chartS2 = 0;
        $chartS3 = 0;

        foreach ($krs_cicilan as $key => $val) {
            $KRS[$key] = $val;

            if (in_array($val['nipd'], $people_perpanjang))
            {
                $biaya_perp = $angkatan[$val['angkatan']][$val['jenjang']] / 2;
                $KRS[$key]['cicilan1'] = $biaya_perp / 3;
                $KRS[$key]['cicilan2'] = $biaya_perp / 3;
                $KRS[$key]['cicilan3'] = $biaya_perp / 3;
                $KRS[$key]['bayar1'] = isset($cicilan[$val['nipd']]['perpanjangan_semester']) && $cicilan[$val['nipd']]['perpanjangan_semester'] >= $KRS[$key]['cicilan1'] ? $KRS[$key]['cicilan1'] : 0;
                $estim_c2  = $cicilan[$val['nipd']]['perpanjangan_semester'] - $KRS[$key]['cicilan1'];
                $estim_c22 = $estim_c2 > $KRS[$key]['cicilan1'] ? $KRS[$key]['cicilan1'] : $estim_c2;
                $KRS[$key]['bayar2'] = $estim_c22;
                $estim_c3  = $estim_c2 > $KRS[$key]['cicilan1'] ? $KRS[$key]['cicilan1'] : $estim_c2;
                // $estim_c33 = 
                $KRS[$key]['bayar3'] =  $estim_c22 >= $KRS[$key]['cicilan1'] ? $estim_c3 : 0;
            }
            else
            {
                $KRS[$key]['cicilan1'] = $angkatan[$val['angkatan']][$val['jenjang']] / 3;
                $KRS[$key]['cicilan2'] = $angkatan[$val['angkatan']][$val['jenjang']] / 3;
                $KRS[$key]['cicilan3'] = $angkatan[$val['angkatan']][$val['jenjang']] / 3;

                $KRS[$key]['bayar1'] = isset($cicilan[$val['nipd']]['cicilan1']) ? $cicilan[$val['nipd']]['cicilan1'] : 0;
                $KRS[$key]['bayar2'] = isset($cicilan[$val['nipd']]['cicilan2']) ? $cicilan[$val['nipd']]['cicilan2'] : 0;
                $KRS[$key]['bayar3'] = isset($cicilan[$val['nipd']]['cicilan3']) ? $cicilan[$val['nipd']]['cicilan3'] : 0;

            }

            $KRS[$key]['sisa1'] = $KRS[$key]['cicilan1'] - $KRS[$key]['bayar1'];
            $KRS[$key]['sisa2'] = $KRS[$key]['cicilan2'] - $KRS[$key]['bayar2'];
            $KRS[$key]['sisa3'] = $KRS[$key]['cicilan3'] - $KRS[$key]['bayar3'];
            $KRS[$key]['total_cicilan'] = $KRS[$key]['cicilan1'] + $KRS[$key]['cicilan2'] + $KRS[$key]['cicilan3'];
            $KRS[$key]['total_bayar'] = $KRS[$key]['bayar1'] + $KRS[$key]['bayar2'] + $KRS[$key]['bayar3'];
            $KRS[$key]['total_sisa'] = $KRS[$key]['sisa1'] + $KRS[$key]['sisa2'] + $KRS[$key]['sisa3'];

            $chartT1 += $KRS[$key]['cicilan1'];
            $chartT2 += $KRS[$key]['cicilan2'];
            $chartT3 += $KRS[$key]['cicilan3'];

            $chartB1 += $KRS[$key]['bayar1'];
            $chartB2 += $KRS[$key]['bayar2'];
            $chartB3 += $KRS[$key]['bayar3'];

            $chartS1 += $KRS[$key]['sisa1'];
            $chartS2 += $KRS[$key]['sisa2'];
            $chartS3 += $KRS[$key]['sisa3'];

            
        }

        $chartCicilan[0]['name'] = 'TAGIHAN';
        $chartCicilan[0]['data'] = [$chartT1,$chartT2,$chartT3];
        $chartCicilan[1]['name'] = 'BAYAR';
        $chartCicilan[1]['data'] = [$chartB1,$chartB2,$chartB3];
        $chartCicilan[2]['name'] = 'SISA';
        $chartCicilan[2]['data'] = [$chartS1,$chartS2,$chartS3];
        $data['chartCicilan'] = $chartCicilan;
        $data['chartT1']      = $chartT1;
        echo json_encode($data);
    }

    public function CetakLaporanDataDispenV2()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $pecah_date = explode('-', $date);
        $thn = $pecah_date[0];
        $bln = $pecah_date[1];
        $bln_lalu = $bln - 1;
        $FormatTanggal = new FormatTanggal;
        $jenis_dispen = $this->input->get('jenis_dispen');

        // echo '<pre>';
        // var_dump($smtAktifRes);
        // echo'</pre>';
        // die;

        $kondisi = [
            // 'd.jenis_dispen' => $jenis_dispen,
            'd.tahun_akademik' => $smtAktif,
            'd.status' => 0

        ];
        $dataDispen = $this->aktivasi->getDataDispenMhsV2($kondisi)->result_array();
        $dataDispenV2 = array();
        foreach ($dataDispen as $k => $v) {
            $dataDispenV2[$v['nipd']][$v['jenis_dispen']] = $v;
        }
        // echo '<pre>';
        // echo print_r($dataDispenV2);
        // echo '</pre>'; exit();
        $no0 = 0;
        $no1 = 1;
        $no2 = 0;
        $dataDispenAll = array();
        foreach ($dataDispenV2 as $key => $val) {
            // $dataDispenAll[$key]['nomor'] = count($val) + $no1 + $no0;
            // $dataDispenAll[$key]['jum'] = count($val);
            $nom = $no1 > 0 ? $no1 : 0;
            $no3 = 0;
            $dataDispenAll[$key]['nomor'] = $no1 >= 1 ? $no2 + $nom + (count($val)) : count($val) + $no2 + $no0;
            // $dataDispenAll[$key]['nomor2'] = $no1 >= 1 ? ((count($val) + $no2 + $no0) + (count($val))) : count($val) + $no2 + $no0;
            // $dataDispenAll[$key]['nomor3'] =  $no1 + $no0;
            // $dataDispenAll[$key]['nomor4'] = (count($val));

            foreach ($val as $k => $v) {
                $dataDispenAll[$key]['dispen'][$k] = $v;
                // $dataDispenAll[$key]['dispen']['uts'] = $val[$k] ? 1 : 0;
                $dataDispenAll[$key]['dispen'][$k]['nomor'] = $no2 + $nom;
                // $dataDispenAll[$key]['dispen'][$no2]['nomor1'] = $no2;
                // $dataDispenAll[$key]['dispen'][$no2]['nomor2'] = $nom;
                // id_dispensasi
                // jenis_dispen
                // tanggal_input
                // id_pd
                // nipd
                // nm_pd
                // id_jur
                // nm_jur
                // tgl_janji_lunas
                // no_tlp
                // tg_dispen
                // tahun_akademik
                // tgl_pelunasan
                // status
                $no2++;
                $no3++;
            }
            $no0++;
            $no1++;
        }
        // echo '<pre>';
        // echo print_r($dataDispenAll);
        // echo '</pre>';
        // die;
        $countData = count($dataDispen);
        for ($i = 0; $i < $countData; $i++) {
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
                $jenis_cicilan = 'CICILAN 1';
                // $dataDispen[$i]['Cicilan Ke-1'] = $dataDispen[$i]['tg_dispen'];
                $dataDispen[$i]['tg_smt_lalu'] = $tg_smt_lalu;
            } elseif ($dataDispen[$i]['jenis_dispen'] == '3') {
                $jenis_cicilan = 'CICILAN 2';
                // $dataDispen[$i]['Cicilan Ke-2'] = $dataDispen[$i]['tg_dispen'];
                $dataDispen[$i]['tg_smt_lalu'] = $tg_smt_lalu;
            } elseif ($dataDispen[$i]['jenis_dispen'] == '4') {
                $jenis_cicilan = 'CICILAN 3';
                // $dataDispen[$i]['Cicilan Ke-3'] = $dataDispen[$i]['tg_dispen'];
                $dataDispen[$i]['tg_smt_lalu'] = $tg_smt_lalu;
            }
        }
        if ($smtAktifRes['smt'] == 1) {
            $jns_smt = 'GANJIL';
        } else {
            $jns_smt = 'GENAP';
        }
        // var_dump($dataDispen);
        // die;

        $spreadsheet = new Spreadsheet();
        $Terbilang = new FormatTerbilang();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setTitle('Data_Dispen_Semester(' . $smtAktif . ')');
        $sheet->setTitle('Data_Dispen');



        //define width of column
        $sheet->getColumnDimension('A')->setWidth(5.43);
        $sheet->getColumnDimension('B')->setWidth(13.00);
        $sheet->getColumnDimension('C')->setWidth(36.00);
        $sheet->getColumnDimension('D')->setWidth(25.00);
        $sheet->getColumnDimension('E')->setWidth(18.00);
        $sheet->getColumnDimension('F')->setWidth(18.00);
        $sheet->getColumnDimension('G')->setWidth(18.00);
        $sheet->getColumnDimension('H')->setWidth(15.00);
        $sheet->getColumnDimension('I')->setWidth(22.00);
        $sheet->getColumnDimension('J')->setWidth(32.00);

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

        // configurasi Title
        // $sheet->setCellValue('A1', 'DAFTAR TAGIHAN UANG KULIAH ' . $jenis_cicilan . ' SEMESTER ' . $jns_smt . ' ' . $smtAktifRes['id_thn_ajaran'] . '/' . $smtAktifRes['id_thn_ajaran'] + 1);
        $sheet->setCellValue('A1', 'DAFTAR TAGIHAN DISPENSASI SPP SEMESTER ' . $jns_smt);
        $sheet->mergeCells('A1:J2');
        $sheet->setCellValue('A3', $smtAktifRes['nm_smt']);
        // $sheet->setCellValue('A3', '2021/2022');
        $sheet->mergeCells('A3:J4');
        $sheet->getStyle('A1:J4')->getFont()->setSize(12);
        $sheet->getStyle('A1:J4')->getFont()->setBold(true);
        $sheet->getStyle('A1:J4')->applyFromArray($styleTitle); //styling header table

        $row_tbl = 8;
        $no = 1;
        $row_header = 6;
        $merge_header = $row_header + 1;
        $sheet->setCellValue('A' . $row_header, 'NO');
        $sheet->setCellValue('B' . $row_header, 'NIM');
        $sheet->setCellValue('C' . $row_header, 'NAMA');
        $sheet->setCellValue('D' . $row_header, 'PRODI');
        $sheet->setCellValue('E' . $row_header, 'RINCIAN TAGIHAN');
        $sheet->mergeCells('E' . $row_header . ':' . 'F' . $row_header);
        $sheet->setCellValue('E' . ($row_header + 1), 'JENIS TUNGGAKAN');
        $sheet->setCellValue('F' . ($row_header + 1), 'JUMLAH');
        // $sheet->setCellValue('G' . ($row_header + 1), 'TOTAL');
        $sheet->setCellValue('G' . $row_header, 'TANGGAL PERJANJIAN PELUNASAN');
        $sheet->setCellValue('H' . $row_header, 'NO HP');
        $sheet->setCellValue('I' . $row_header, 'STATUS');
        // merge all header
        $sheet->mergeCells('A' . $row_header . ':A' . $merge_header);
        $sheet->mergeCells('B' . $row_header . ':B' . $merge_header);
        $sheet->mergeCells('C' . $row_header . ':C' . $merge_header);
        $sheet->mergeCells('D' . $row_header . ':D' . $merge_header);
        $sheet->mergeCells('H' . $row_header . ':H' . $merge_header);
        $sheet->mergeCells('I' . $row_header . ':I' . $merge_header);
        $sheet->mergeCells('J' . $row_header . ':J' . $merge_header);
        //styling title
        $sheet->getStyle('A' . $row_header . ':J' . $row_header)->getAlignment()->setHorizontal(PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row_header . ':J' . $row_header)->getAlignment()->setVertical(PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A6:J7')->getFont()->setSize(10);
        $sheet->getStyle('H6:H7')->getAlignment()->setWrapText(true);


        foreach ($dataDispenAll as $k => $v) {
            $TOTAL = 0;
            // if (isset($v['dispen'][4])) {
            foreach ($v['dispen'] as $ka => $va) {
                $row_tbl = 8 + $va['nomor'];
                if ($va['jenis_dispen'] == 1) {
                    $js = 'PERWALIAN';
                } else if ($va['jenis_dispen'] == 2) {
                    $js = 'DISPEN UAS';
                } else if ($va['jenis_dispen'] == 3) {
                    $js = 'DISPEN UTS';
                } else if ($va['jenis_dispen'] == 4) {
                    $js = 'DISPEN UAS';
                }
                // $sheet->setCellValue('A' . $row_tbl, $k);
                $sheet->setCellValue('B' . $row_tbl, $va['nipd']);
                $sheet->setCellValue('C' . $row_tbl, $va['nm_pd']);
                $sheet->setCellValue('D' . $row_tbl, $va['nm_jur']);
                $sheet->setCellValue('E' . $row_tbl, $js);
                $sheet->setCellValue('F' . $row_tbl, $va['tg_dispen']);
                // $sheet->setCellValue('G' . $row_tbl, '');
                $sheet->setCellValue('G' . $row_tbl, $va['tgl_janji_lunas']);
                $sheet->setCellValue('H' . $row_tbl, $va['no_tlp']);
                // if ($dataDispen[$i]['tgl_pelunasan'] != null) {
                //     $sheet->setCellValue('J' . $row_tbl, 'Sudah Dibayar Pada Tgl ' . $dataDispen[$i]['tgl_pelunasan']);
                // } else {
                //     $sheet->setCellValue('J' . $row_tbl, 'Belum Bayar');
                // }
                $TOTAL += $va['tg_dispen'];
                $TT[$k] = $TOTAL;
            }

            // echo '<pre>';
            // echo print_r(array_sum($TOTAL[$k]));
            // echo '</pre>';
            // die;
            $row_tbl1 = 8 + $v['nomor'];
            $sheet->setCellValue('A' . $row_tbl, $no);
            $sheet->setCellValue('B' . $row_tbl1, '');
            $sheet->setCellValue('C' . $row_tbl1, '');
            $sheet->setCellValue('D' . $row_tbl1, '');
            $sheet->setCellValue('E' . $row_tbl1, 'TOTAL');
            $sheet->setCellValue('F' . $row_tbl1, $TT[$k]);
            $sheet->setCellValue('G' . $row_tbl1, '');
            $sheet->setCellValue('H' . $row_tbl1, '');
            $sheet->setCellValue('I' . $row_tbl1, '');

            $no++;
            // }
        }


        $filename = 'LAPORAN DATA DISPEN MAHASISWA SEMESTER ' . $jns_smt . ' ' . $smtAktif . '';
        $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}