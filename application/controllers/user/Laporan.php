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

class Laporan extends CI_Controller
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
        $this->load->library('formatterbilang');

        $this->load->model('M_cetak_kwitansi', 'cetak');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_transaksi', 'transaksi');
        $this->load->model('M_tunggakan', 'tunggakan');
        $this->load->model('M_laporan', 'laporan');
    }


    public function HistoriTransaksi()
    {
        // code here...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Histori Transaksi';
        $data['content'] = 'laporan/histori_transaksi';
        $this->load->view('template', $data);
    }

    public function RekapDataTrx()
    {
        // code here ...
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Rekap Data Transaksi';
        $data['content'] = 'laporan/rekap_data_transaksi';
        $kondisi = 'id_jenis_pembayaran <> 1 AND id_jenis_pembayaran <> 6 AND id_jenis_pembayaran <> 7 AND id_jenis_pembayaran <> 8';
        $data['field'] = $this->masterdata->GetJenisPembayaran($kondisi)->result_array();
        $this->load->view('template', $data);
    }

    public function PenerimaanKasYayasan()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Penerimaan Kas Yayasan';
        $data['content'] = 'laporan/penerimaan_kas_yayasan';
        $datenow = date("Y-m-d");
        $bnlOfYear = SUBSTR($datenow, 0, 7);
        // where '2009-01-01' <= datecolumn and datecolumn < '2009-02-01'
        // var_dump($bnlOfYear);
        // die;
        $where = [
            't.uang_masuk' => 1,
            'SUBSTR(t.tanggal, 1, 7)=' => $bnlOfYear,
            'mjp.jenis_kas' => 1,
        ];
        $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
        $countHistoriTx = count($dataHistoriTx);
        for ($i = 0; $i < $countHistoriTx; $i++) {

            $where_DTx = [
                't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                'mjp.jenis_kas' => 1
            ];
            $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
            $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
        }




        $total_kas_bln = 0;
        foreach ($dataHistoriTx as $val) {
            foreach ($val['detail_transaksi'] as $dtx) {
                $total_kas_bln = $total_kas_bln + $dtx['jml_bayar'];
            }
        }
        $data['total_uang_masuk_bulan_ini'] = $total_kas_bln;


        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d');
        // $pecah_tgl_waktu = explode(' ', $now);
        $tanggal = $this->formattanggal->konversi($now);
        $pecah_konversi = explode(' ', $tanggal);
        $bln_berjalan = $pecah_konversi[1] . ' ' . $pecah_konversi[2];
        $data['bln_berjalan'] = $bln_berjalan;

        $this->load->view('template', $data);
    }

    public function PenerimaanKasSTT()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Penerimaan Kas STT';
        $data['content'] = 'laporan/penerimaan_kas_stt';
        $this->load->view('template', $data);
    }

    public function PenerimaanKasKmhs()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Penerimaan Kas Kemahasiswaan';
        $data['content'] = 'laporan/penerimaan_kas_kmhs';
        $this->load->view('template', $data);
    }

    public function getDataPenerimaanKasYayasan()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            // $input = $this->input->post('data');
            $where = [
                // 't.uang_masuk' => 1,
                'mjp.jenis_kas' => 1
            ];
            $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $where_DTx = [
                    't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                    'mjp.jenis_kas' => 1
                ];
                $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
                if ($dataHistoriTx[$i]['uang_masuk'] == 1) {
                    $keterangan = '';
                } else {
                    $keterangan = 'Potongan SPP/Beasiswa';
                }
                $dataHistoriTx[$i]['uang_masuk'] = $keterangan;

                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
            }
            $data['kas_yayasan'] = $dataHistoriTx;
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }

    public function getDataPenerimaanKasKmhs()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            // $input = $this->input->post('data');
            $where = [
                't.semester' => $smtAktif,
                'mjp.jenis_kas' => 2,
                'td.id_jenis_pembayaran >=' => 5,
                'td.id_jenis_pembayaran <=' => 6
            ];
            $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $where_DTx = [
                    't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                    'mjp.jenis_kas' => 2,
                    'td.id_jenis_pembayaran >=' => 5,
                    'td.id_jenis_pembayaran <=' => 6
                ];
                $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
            }
            $data['kas_yayasan'] = $dataHistoriTx;
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }


    public function getDataPenerimaanKasSTT()
    {
        $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
        $smtAktif = $smtAktifRes['id_smt'];
        if ($this->input->is_ajax_request()) {
            // $input = $this->input->post('data');
            $where = [
                't.semester' => $smtAktif,
                'mjp.jenis_kas' => 2,
                'td.id_jenis_pembayaran >=' => 10,
                'td.id_jenis_pembayaran <=' => 19
            ];
            $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
            $countHistoriTx = count($dataHistoriTx);
            for ($i = 0; $i < $countHistoriTx; $i++) {
                $where_DTx = [
                    't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                    'mjp.jenis_kas' => 2,
                    'td.id_jenis_pembayaran >=' => 10,
                    'td.id_jenis_pembayaran <=' => 19
                ];
                $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
                $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
            }
            $data['kas_yayasan'] = $dataHistoriTx;
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }

    public function DataTunggakan()
    {
        $data['title'] = 'SiskeuNEW';
        $data['page'] = 'Data Tunggakan';
        $data['content'] = 'laporan/data_tunggakan';
        $this->load->view('template', $data);
    }

    public function getDataTunggakan()
    {
        if ($this->input->is_ajax_request()) {
            $data['tunggakan'] = $this->tunggakan->getAllDataTunggakanMhs()->result_array();
            $data['admin_log'] = $this->session->userdata();
        } else {
            $data = 'Invalid Request.';
        }
        echo json_encode($data);
    }

    public function hapus_tunggakan()
    {
        if ($this->input->is_ajax_request()) {
            $id_tg = $this->input->post('id_tg');
            $delete = $this->tunggakan->deleteTunggakan(['id_tunggakan' => $id_tg]);
            if (!$delete) {
                $data = [
                    'status' => false,
                    'msg' => 'gagal dihapus.'
                ];
            } else {
                $data = [
                    'status' => true,
                    'msg' => 'berhasil dihapus.'
                ];
            }
        } else {
            $data = [
                'status' => false,
                'msg' => 'Invalid Request.'
            ];
        }
        echo json_encode($data);
    }


    public function BuatLaporanBulanan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $pecah_date = explode('-', $date);
        $thn = $pecah_date[0];
        $bln = $pecah_date[1];
        $bln_lalu = $bln - 1;
        $jenis_kas = $this->input->get('jenis_laporan');
        $FormatTanggal = new FormatTanggal;

        // seting ambil bulan laporan
        // $nm_bln = $FormatTanggal->konversiBulan($bln);
        // $bulan_laporan = $nm_bln . ' ' . $thn;
        $nm_bln_lalu = $FormatTanggal->konversiBulan($bln_lalu);
        $bulan_laporan = $nm_bln_lalu . ' ' . $thn;


        $where = [
            'mjp.jenis_kas' => $jenis_kas,
            // 'SUBSTRING(t.tanggal, 1, 7) =' => $thn . '-' . $bln
            'SUBSTRING(t.tanggal, 1, 7) =' => $thn . '-0' . $bln_lalu
        ];
        $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
        $countHistoriTx = count($dataHistoriTx);
        for ($i = 0; $i < $countHistoriTx; $i++) {
            $where_DTx = [
                't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
                'mjp.jenis_kas' => 1
            ];
            $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
            if ($dataHistoriTx[$i]['uang_masuk'] == 1) {
                $keterangan = '';
            } else {
                $keterangan = 'Potongan SPP/Beasiswa';
            }
            $dataHistoriTx[$i]['uang_masuk'] = $keterangan;

            $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
        }
        $spreadsheet = new Spreadsheet();
        $Terbilang = new FormatTerbilang();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Penerimaan_Kas');


        //define width of column
        $sheet->getColumnDimension('A')->setWidth(5.00);
        $sheet->getColumnDimension('B')->setWidth(19.00);
        $sheet->getColumnDimension('C')->setWidth(15.00);
        $sheet->getColumnDimension('D')->setWidth(25.00);
        $sheet->getColumnDimension('E')->setWidth(15.00);
        $sheet->getColumnDimension('F')->setWidth(15.00);
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

        // configurasi Title
        $sheet->setCellValue('A1', 'LAPORAN PENERIMAAN KAS YAYASAN BUNGA BANGSA');
        $sheet->mergeCells('A1:I2');
        $sheet->setCellValue('A3', $bulan_laporan);
        $sheet->mergeCells('A3:I4');
        $sheet->getStyle('A1:I4')->getFont()->setBold(true);
        $sheet->getStyle('A1:I4')->getFont()->setSize(14);
        $sheet->getStyle('A1:I4')->applyFromArray($styleTitle); //styling header table

        $row_header = 6;
        $merge_header = $row_header + 1;
        $sheet->setCellValue('A' . $row_header, 'No');
        $sheet->setCellValue('B' . $row_header, 'Tgl Transaksi');
        $sheet->setCellValue('C' . $row_header, 'NIM');
        $sheet->setCellValue('D' . $row_header, 'Rincian Transaksis');
        $sheet->mergeCells('D' . $row_header . ':' . 'E' . $row_header);
        $sheet->setCellValue('D' . ($row_header + 1), 'Jenis Pembayaran');
        $sheet->setCellValue('E' . ($row_header + 1), 'Jumlah Bayar');
        $sheet->setCellValue('F' . $row_header, 'Total');
        $sheet->setCellValue('G' . $row_header, 'Semester');
        $sheet->setCellValue('H' . $row_header, 'Admin');
        $sheet->setCellValue('I' . $row_header, 'Keterangan');
        // merge all header
        $sheet->mergeCells('A' . $row_header . ':A' . $merge_header);
        $sheet->mergeCells('B' . $row_header . ':B' . $merge_header);
        $sheet->mergeCells('C' . $row_header . ':C' . $merge_header);
        $sheet->mergeCells('F' . $row_header . ':F' . $merge_header);
        $sheet->mergeCells('G' . $row_header . ':G' . $merge_header);
        $sheet->mergeCells('H' . $row_header . ':H' . $merge_header);
        $sheet->mergeCells('I' . $row_header . ':I' . $merge_header);
        //styling title
        $sheet->getStyle('A' . $row_header . ':I' . $row_header)->getAlignment()->setHorizontal(PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row_header . ':I' . $row_header)->getAlignment()->setVertical(PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row_header . ':I' . $row_header)->getFont()->setBold(true);
        $sheet->getStyle('A' . $merge_header . ':I' . $merge_header)->getFont()->setBold(true);


        $row_tbl = 8;
        $data_terbilang = [];
        foreach ($dataHistoriTx as $i => $val) {
            $i++;
            if (count($val['detail_transaksi']) > 1) {
                $row_tbl = ($row_tbl + count($val['detail_transaksi'])) - 1;
            }
            // var_dump($jml_detail);
            // die;
            $row_min = (($row_tbl + 1) - count($val['detail_transaksi']));
            $sheet->setCellValue('A' . $row_min, $i);
            $sheet->setCellValue('B' . $row_min, $val['tanggal']);
            $sheet->setCellValue('C' . $row_min, $val['nim']);
            if (count($val['detail_transaksi']) > 0) {

                $sheet->mergeCells('A' . $row_min  . ':' . 'A' . $row_tbl);
                $sheet->mergeCells('B' . $row_min . ':' . 'B' . $row_tbl);
                $sheet->mergeCells('C' . $row_min . ':' . 'C' . $row_tbl);
                $sheet->mergeCells('F' . $row_min  . ':' . 'F' . $row_tbl);
                $sheet->mergeCells('G' . $row_min . ':' . 'G' . $row_tbl);
                $sheet->mergeCells('H' . $row_min . ':' . 'H' . $row_tbl);
                $sheet->mergeCells('I' . $row_min . ':' . 'I' . $row_tbl);
            }


            $jml_bayar = [];
            foreach ($val['detail_transaksi'] as $keyls => $dtx) {
                $now = ($row_min + $keyls);
                if ($keyls > 0) {

                    $sheet->setCellValue('D' . $now, $dtx['nm_jenis_pembayaran']);
                    $sheet->setCellValue('E' . $now, $dtx['jml_bayar']);
                } else {

                    $sheet->setCellValue('D' . $row_min, $dtx['nm_jenis_pembayaran']);
                    $sheet->setCellValue('E' . $now, $dtx['jml_bayar']);
                }
                // $sheet->setCellValue('D' . ($row_tbl + $keyls), $dtx['nm_jenis_pembayaran']);
                // $sheet->setCellValue('E' . ($row_tbl + $keyls), $dtx['jml_bayar']);
                // $row_tbl_detail++;
                $jml_bayar[] = $dtx['jml_bayar'];
                $keyls++;
            }
            $total_bayar = array_sum($jml_bayar);
            $sheet->setCellValue('F' . $row_min, $total_bayar);
            $sheet->setCellValue('G' . $row_min, $val['semester']);
            $sheet->setCellValue('H' . $row_min, $val['nama_user']);
            $sheet->setCellValue('I' . $row_min, $val['uang_masuk']);
            $data_terbilang[] = $total_bayar;

            $row_tbl++;
        }
        $sum_total = array_sum($data_terbilang);
        $terbilang = $Terbilang->terbilang($sum_total);
        $sheet->getStyle('E8:F' . ($row_tbl - 1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue('A' . $row_tbl, 'TOTAL (RP)');
        $sheet->mergeCells('A' . $row_tbl . ':' . 'C' . ($row_tbl + 1));
        $sheet->setCellValue(
            'D' . $row_tbl,
            '=SUM(F8:F' . ($row_tbl - 1) . ')'
        );
        $sheet->setCellValue(
            'D' . ($row_tbl + 1),
            $terbilang
        );
        $sheet->getStyle('D' . $row_tbl)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->mergeCells('D' . $row_tbl . ':' . 'I' . $row_tbl);
        $sheet->mergeCells('D' . ($row_tbl + 1) . ':' . 'I' . ($row_tbl + 1));
        $sheet->getStyle('A' . $row_tbl . ':' . 'I' . ($row_tbl + 1))->getFont()->setBold(true);
        $sheet->getStyle('A6:I' . ($row_tbl + 1))->applyFromArray($styleTable); //styling header table

        $filename = 'Laporan_Penerimaan_Kas_Yayasan_Bunga_Bangsa(' . $bulan_laporan . ')';
        $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
