<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : Laporan transaksi.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Bayu Prasetio
 *  Date Created          : 05/12/2023
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use \PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan_transaksi extends CI_Controller
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
        $this->load->model('M_cetak_kwitansi', 'cetak');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_transaksi', 'transaksi');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_laporan_transaksi', 'laporan_transaksi');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
    }

    public function kms()
    {
        // code here ...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Rekap Data All Transaksi';
        $data['content'] = 'laporan/rekap_data_all_transaksi';

        $kondisi = [
            'kn.id_tahun_ajaran > ' => 20211,
        ];

        $kondisi2 = [
            't.semester >=' => 20211,
            'mjp.`id_jenis_pembayaran`' => 5,
        ];
        $getSemesterAktif = $this->laporan_transaksi->smt_aktif()->row_array();
        $ganjil_genap = substr($getSemesterAktif['mak'], 4);
        $smt_aktif    = substr($getSemesterAktif['mak'], 0, 4);


        $get_kms_data = $this->laporan_transaksi->get_kms_data($kondisi2)->result_array();
        $get_kms_data_array = array();
        foreach ($get_kms_data as $key => $val) {
            $get_kms_data_array[$val['semester']][$val['nim']][$val['id_jenis_pembayaran']][] = $val;
        }
        $kms_data_array = array();
        foreach ($get_kms_data_array as $smt => $val) {
            foreach ($val as $nipd => $va) {
                foreach ($va as $jenis => $v) {
                    $jumbayar = 0;
                    foreach ($v as $value) {
                        $jumbayar += $value['jml_bayar'];
                    }
                    $kms_data_array[$smt][$nipd][$jenis] = $jumbayar;
                }
            }
        }
        $get_krs_smt = $this->laporan_transaksi->get_krs_smt($kondisi)->result_array();
        $get_krs_smt_data = array();
        foreach ($get_krs_smt as $key => $val) {
            $get_krs_smt_data[$val['id_tahun_ajaran']][] = $val;
        }

        // echo '<pre>';
        // echo print_r($get_krs_smt_data);
        // echo '</pre>';
        // exit();
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

        $spreadsheet = new Spreadsheet();
        $keyin = 0;
        foreach ($get_krs_smt_data as $key => $val) {
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, '' . $key);
            $sheet       = $spreadsheet->addSheet($myWorkSheet, $keyin);
            $sheet->getColumnDimension('A')->setWidth(5.43);
            $sheet->getColumnDimension('B')->setWidth(13.00);
            $sheet->getColumnDimension('C')->setWidth(18.00);
            $sheet->getColumnDimension('D')->setWidth(12.00);
            $sheet->getColumnDimension('E')->setWidth(12.00);
            $sheet->getColumnDimension('F')->setWidth(30.00);
            $sheet->getColumnDimension('G')->setWidth(15.00);
            $sheet->getColumnDimension('H')->setWidth(25.00);
            $sheet->getColumnDimension('I')->setWidth(16.00);

            $sheet->getStyle('A1:I4')->getFont()->setSize(12);
            $sheet->getStyle('A1:I4')->getFont()->setBold(true);
            $sheet->getStyle('A1:I4')->applyFromArray($styleTitle); //styling header table

            $sheet->mergeCells('A1:I1');
            $sheet->mergeCells('A2:I2');

            $sheet->mergeCells('A' . (count($val) + 5) . ':H' . (count($val) + 5));
            $sheet->getStyle('A' . (count($val) + 5) . ':I' . (count($val) + 5))->getFont()->setSize(12);
            $sheet->getStyle('A' . (count($val) + 5) . ':I' . (count($val) + 5))->getFont()->setBold(true);
            $sheet->getStyle('A' . (count($val) + 5) . ':I' . (count($val) + 5))->applyFromArray($styleTable); //styling header table
            $sheet->setCellValue('A' . (count($val) + 5), 'TOTAL');
            $sheet->setCellValue('A1', 'LAPORAN PEMBAYARAN KEMAHASISWAAN ' . $key);
            $sheet->setCellValue('A2', 'STT WASTUKANCANA PURWAKARTA');

            $sheet->setCellValue('A4', 'NO');
            $sheet->setCellValue('B4', 'ANGKATAN');
            $sheet->setCellValue('C4', 'PRODI');
            $sheet->setCellValue('D4', 'KELAS');
            $sheet->setCellValue('E4', 'NIM');
            $sheet->setCellValue('F4', 'NAMA');
            $sheet->setCellValue('G4', 'SEMESTER');
            $sheet->setCellValue('H4', 'STATUS PEMBAYARAN');
            $sheet->setCellValue('I4', 'JUMLAH BAYAR');
            $nom    = 5;
            $TOTALB = 0;
            $sheet->getStyle('A4:I' . (count($val) + 4))->applyFromArray($styleTable); //styling header table

            foreach ($val as $k => $v) {
                $sheet->setCellValue('A' . $nom, $k + 1);
                $sheet->setCellValue('B' . $nom, $v['tahun_masuk']);
                $sheet->setCellValue('C' . $nom, $v['nm_jur']);
                $sheet->setCellValue('D' . $nom, $v['nama_kelas']);
                $sheet->setCellValue('E' . $nom, $v['nipd']);
                $sheet->setCellValue('F' . $nom, $v['nm_pd']);

                $angkatan     = '20' . substr($v['nipd'], 0, 2);
                if ($ganjil_genap == 1) {
                    $hasil = $smt_aktif - $angkatan;
                    $ang = (2 * $hasil) + 1;
                } else if ($ganjil_genap == 2) {
                    $hasil = $smt_aktif - $angkatan;
                    $ang = (2 * $hasil) + 2;
                }

                $sheet->setCellValue('G' . $nom, $ang);
                $JUMBAY = isset($kms_data_array[$key][$v['nipd']][5]) ? $kms_data_array[$key][$v['nipd']][5] : 0;
                if ($JUMBAY == 0) {
                    $sts = 'BELUM BAYAR';
                } else if ($JUMBAY >= 200000) {
                    $sts = 'LUNAS';
                } else if ($JUMBAY < 200000 && $JUMBAY > 0) {
                    $sts = 'BELUM LUNAS';
                }
                $sheet->setCellValue('H' . $nom, $sts);
                $sheet->setCellValue('I' . $nom, number_format($JUMBAY, 0));
                $TOTALB += $JUMBAY;
                $nom++;
            }
            $sheet->setCellValue('I' . (count($val) + 5), number_format($TOTALB, 0));
            $keyin++;
        }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $filename = 'report_uang_kemahasiswaan';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
