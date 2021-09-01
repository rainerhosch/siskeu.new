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
        $this->load->library('terbilang');
        define('FPDF_FONTPATH', $this->config->item('fonts_path'));

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


        $where = [
            't.uang_masuk' => 1,
            'SUBSTR(t.tanggal, 1, 7)=' => $bnlOfYear,
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
        $pecah_tgl_waktu = explode(' ', $now);
        $tanggal = $this->formattanggal->konversi($pecah_tgl_waktu[0]);
        $pecah_konversi = explode(' ', $tanggal);
        $bln_transaksi = $pecah_konversi[1] . ' ' . $pecah_konversi[2];
        $data['bln_transaksi'] = $bln_transaksi;

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
                    $keterangan = 'Potongan/Subsidi SPP';
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
        $bln_thn = SUBSTR($date, 0, 7);
        // var_dump($bln_thn);
        // die;
        $jenis_kas = $this->input->post('jenis_kas');


        $where = [
            'mjp.jenis_kas' => $jenis_kas,
            'SUBSTRING(t.tanggal, 1, 7) =' => $bln_thn
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
                $keterangan = 'Potongan/Subsidi SPP';
            }
            $dataHistoriTx[$i]['uang_masuk'] = $keterangan;

            $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
        }


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tgl Transaksi');
        $sheet->setCellValue('C1', 'NIM');
        $sheet->setCellValue('D1', 'Rincian');
        $sheet->setCellValue('E1', 'Total');
        $sheet->setCellValue('F1', 'Semester');
        $sheet->setCellValue('F1', 'Keterangan');
        $sheet->setCellValue('F1', 'Admin');
        $rows = 0;
        foreach ($dataHistoriTx as $val) {
            $sheet->setCellValue('A' . $rows, $val['id']);
            $sheet->setCellValue('B' . $rows, $val['name']);
            $sheet->setCellValue('C' . $rows, $val['skills']);
            $sheet->setCellValue('D' . $rows, $val['address']);
            $sheet->setCellValue('E' . $rows, $val['age']);
            $sheet->setCellValue('F' . $rows, $val['designation']);
            $rows++;
        }
        $writer = new Xlsx($spreadsheet);
        // $sheet = $spreadsheet->setActiveSheetIndex((int) $indexSheet);
        // $sheet = $spreadsheet->setActiveSheetIndex((int) $indexSheet);

        // //define width of column
        // $sheet->getColumnDimension('A')->setWidth(8.43 + 0.72);
        // $sheet->getColumnDimension('B')->setWidth(8.43 + 0.72);
        // $sheet->getColumnDimension('C')->setWidth(4.43 + 0.72);
        // $sheet->getColumnDimension('D')->setWidth(8.43 + 0.72);
        // $sheet->getColumnDimension('E')->setWidth(3.86 + 0.72);
        // $sheet->getColumnDimension('F')->setWidth(3.86 + 0.72);
        // $sheet->getColumnDimension('G')->setWidth(3.71 + 0.72);
        // $sheet->getColumnDimension('H')->setWidth(3.86 + 0.72);
        // $sheet->getColumnDimension('I')->setWidth(12.29 + 0.72);
        // $sheet->getColumnDimension('J')->setWidth(8.43 + 0.72);
        // $sheet->getColumnDimension('K')->setWidth(8.43 + 0.72);
        // $sheet->getColumnDimension('L')->setWidth(3.57 + 0.72);
        // $sheet->getColumnDimension('M')->setWidth(3.57 + 0.72);
        // $sheet->getColumnDimension('N')->setWidth(1.86 + 0.72);
        // $sheet->getColumnDimension('O')->setWidth(1.86 + 0.72);
        // $sheet->getColumnDimension('P')->setWidth(1.86 + 0.72);
        // $sheet->getColumnDimension('Q')->setWidth(1.86 + 0.72);

        // //define height of row
        // $sheet->getRowDimension('3')->setRowHeight(27.75);
        // $sheet->getRowDimension('4')->setRowHeight(27.75);
        // $sheet->getRowDimension('5')->setRowHeight(27.75);
        // $sheet->getRowDimension('6')->setRowHeight(27.85);

        // //merge cell
        // $sheet->mergeCells('A1:Q1');
        // $sheet->mergeCells('D3:I3');
        // $sheet->mergeCells('D4:I4');
        // $sheet->mergeCells('D5:I5');
        // $sheet->mergeCells('D6:I6');
        // $sheet->mergeCells('M3:Q3');
        // $sheet->mergeCells('M4:Q4');
        // $sheet->mergeCells('M5:Q5');
        // $sheet->mergeCells('M8:Q8');
        // $sheet->mergeCells('B8:C8');
        // $sheet->mergeCells('D8:L8');

        // $sheet->setCellValue('A1', "Rekap Nilai " . $data);
        // $sheet->setCellValue('A1', "Rekap Nilai ");

        // if ($this->input->is_ajax_request()) {
        //     $jenis_kas = $this->input->post('jenis_kas');

        //     $where = [
        //         'mjp.jenis_kas' => $jenis_kas,
        //         'SUBSTRING(t.tanggal, 1, 7) =' => $bln_thn
        //     ];
        //     $dataHistoriTx = $this->laporan->getDataTx($where)->result_array();
        //     $countHistoriTx = count($dataHistoriTx);
        //     for ($i = 0; $i < $countHistoriTx; $i++) {
        //         $where_DTx = [
        //             't.id_transaksi' => $dataHistoriTx[$i]['id_transaksi'],
        //             'mjp.jenis_kas' => 1
        //         ];
        //         $resDetailTx = $this->laporan->getDetailTx($where_DTx)->result_array();
        //         if ($dataHistoriTx[$i]['uang_masuk'] == 1) {
        //             $keterangan = '';
        //         } else {
        //             $keterangan = 'Potongan/Subsidi SPP';
        //         }
        //         $dataHistoriTx[$i]['uang_masuk'] = $keterangan;

        //         $dataHistoriTx[$i]['detail_transaksi'] = $resDetailTx;
        //     }
        //     $data['trx_bulan_ini'] = $dataHistoriTx;
        // } else {
        //     $data = [
        //         'status' => false,
        //         'msg' => 'Invalid Request.'
        //     ];
        // }
        // echo json_encode($data);


        $writer->save('php://output');
    }
}
