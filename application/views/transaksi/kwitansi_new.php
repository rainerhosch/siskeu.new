<?php
//above line is import to define, otherwise it gives an error : Could not include font metric file
require(APPPATH . 'third_party/fpdf/fpdf.php');
class PDF extends FPDF
{

    function format_tgl($tgl)
    {
        $tanggal = explode("-", $tgl);
        $bln = $tanggal[1];
        switch ($bln) {
            case 1:
                $bulan = "Januari";
                break;
            case 2:
                $bulan = "Februari";
                break;
            case 3:
                $bulan = "Maret";
                break;
            case 4:
                $bulan = "April";
                break;
            case 5:
                $bulan = "Mei";
                break;
            case 6:
                $bulan = "Juni";
                break;
            case 7:
                $bulan = "Juli";
                break;
            case 8:
                $bulan = "Agustus";
                break;
            case 9:
                $bulan = "September";
                break;
            case 10:
                $bulan = "Oktober";
                break;
            case 11:
                $bulan = "November";
                break;
            case 12:
                $bulan = "Desember";
                break;
        }
        return $tanggal[2] . " " . $bulan . " " . $tanggal[0];
    }
    //ROTATE TEXT, IMAGE
    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }
    function RotatedImage($file, $x, $y, $w, $h, $angle)
    {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }
    //END ROTATE TEXT, IMAGE
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);

// header
$pdf->SetFont('Times', '', 11);
$pdf->Image('assets/image/logo2.jpg', 10, 8, 17);
$pdf->Cell(15, 5, '', 0, 0, 'L');

$pdf->Cell(155, 5, 'YAYASAN BUNGA BANGSA', 0, 0, 'C');
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(70, 4, '', 0, 1, 'L');
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(15, 5, '', 0, 0, 'L');
$pdf->Cell(166, 5, 'SEKOLAH TINGGI TEKNOLOGI', 0, 0, 'C');
$pdf->SetFont('Times', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(70, 4, '', 0, 1, 'L');
$pdf->SetFont('Times', 'B', 14);
$pdf->SetTextColor(255, 0, 0);
$pdf->Cell(15, 7, '', 0, 0, 'L');
$pdf->Cell(166, 7, 'STT. WASTUKANCANA PURWAKARTA', 0, 0, 'C');
$pdf->SetFont('Times', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(70, 7, '', 0, 1, 'L');
$pdf->SetFont('Arial', 'BU', 11);
$pdf->SetFont('Times', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(15, 6, '', 0, 0, 'L');
$pdf->Cell(165, 4, 'Jl. Raya Cikopak No. 53 Sadang - Purwakarta | Telp: (0264) 214952', 0, 0, 'C');
// end headeer


if ($data_transaksi['smt'] == 1) {
    $cetak_ganjil_genal = 'Ganjil';
} else {
    $cetak_ganjil_genal = 'Genap';
}

$detailTX = $data_transaksi['detail_transaksi'];
$admin_log = $data_transaksi['admin_log'];
$tahun_akademik = substr($data_transaksi['nm_smt'], 0, 9);
$FormatTanggal = new FormatTanggal;
$Terbilang = new Terbilang;
$tglTrx = $FormatTanggal->konversi($data_transaksi['tanggal']);
$tglLog = $FormatTanggal->konversi($admin_log['tanggal_log']);
// data identitas penyetor
$pdf->Ln(3);
// $pdf->Line(7, 29, 135, 29);
// $pdf->Line(8, 30, 134, 30);
// $pdf->Line(8, 30.5, 134, 30.5);
$pdf->Line(5, 29, 207, 29);
$pdf->Line(5, 30, 207, 30);
// $pdf->Line(8, 30, 206, 30);
// $pdf->Line(8, 30.5, 206, 30.5);
$pdf->Ln(4);
$pdf->SetFont('Courier', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(194, 4, 'BUKTI PEMBAYARAN BIAYA KULIAH MAHASISWA', 0, 1, 'C');
$pdf->Line(5, 37, 207, 37);
// $pdf->Line(7, 30, 207, 30);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Ln(5);
$pdf->Cell(40, 4, 'No. Transaksi', 0, 0, 'L');
$pdf->Cell(65, 4, ': ' . $data_transaksi['id_transaksi'], 0, 0, 'L');
$pdf->Cell(40, 4, 'NIM', 0, 0, 'L');
$pdf->Cell(70, 4, ': ' . $data_transaksi['nim'], 0, 1, 'L');

$pdf->Cell(40, 4, 'Tgl Transaksi', 0, 0, 'L');
$pdf->Cell(65, 4, ': ' . $tglTrx, 0, 0, 'L');
$pdf->Cell(40, 4, 'Nama', 0, 0, 'L');
$pdf->Cell(70, 4, ': ' .  $data_transaksi['nm_pd'], 0, 1, 'L');

$pdf->Cell(40, 4, 'Semester', 0, 0, 'L');
$pdf->Cell(65, 4, ': ' . $tahun_akademik . ' (' . $cetak_ganjil_genal . ')', 0, 0, 'L');
$pdf->Cell(40, 4, 'Jurusan', 0, 0, 'L');
$pdf->Cell(70, 4, ': ' .  $data_transaksi['nm_jur'], 0, 1, 'L');
// end

$pdf->Line(5, 56, 207, 56);
$pdf->Ln(5);


// rincian pembayaran
$pdf->Ln(2);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(81, 5, 'Keterangan Pembayaran', 1, 0, 'C');
$pdf->Cell(40, 5, 'Kewajiban', 1, 0, 'C');
$pdf->Cell(40, 5, 'Jml Bayar', 1, 0, 'C');
$pdf->Cell(40, 5, 'Sisa Bayar', 1, 1, 'C');

$total_kewajiban = 0;
$total_bayar_trx = 0;

if ($data_transaksi['bayar_cs'] != 0 || $data_transaksi['bayar_kmhs'] !== 0 || $data_transaksi['bayar_tg_kmhs'] !== 0) {
    foreach ($detailTX as $j => $dtx) {
        if ($dtx['id_jenis_pembayaran'] == 5 || $dtx['id_jenis_pembayaran'] == 7 || $dtx['id_jenis_pembayaran'] == 6) {
            $pdf->SetFont('Courier', 'IB', 10);
            $pdf->Cell(81, 5, $dtx['nm_jenis_pembayaran'], 1, 0, 'L');
            $pdf->SetFont('Courier', 'IB', 10);
            $pdf->Cell(40, 5, number_format($dtx['kewajiban_Bayar'], 0, '', '.'), 1, 0, 'R');
            $pdf->Cell(40, 5, number_format($dtx['jml_bayar'], 0, '', '.'), 1, 0, 'R');
            $pdf->Cell(40, 5, number_format($dtx['kewajiban_Bayar'] - $dtx['jml_bayar'], 0, '', '.'), 1, 1, 'R');
            $total_bayar_trx = $total_bayar_trx + $dtx['jml_bayar'];
            $total_kewajiban = $total_kewajiban + $dtx['kewajiban_Bayar'];
        }
    }

    if ($data_transaksi['kewajiban']['kmhs'] > 0 && $data_transaksi['bayar_kmhs'] == 0) {
        $pdf->SetFont('Courier', 'IB', 10);
        $pdf->Cell(81, 5, 'Kemahasiswaan', 1, 0, 'L');
        $pdf->SetFont('Courier', 'IB', 10);
        $pdf->Cell(40, 5, number_format($data_transaksi['kewajiban']['kmhs'], 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5, number_format($data_transaksi['bayar_kmhs'], 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5, number_format($data_transaksi['kewajiban']['kmhs'] - $data_transaksi['bayar_kmhs'], 0, '', '.'), 1, 1, 'R');
        $total_bayar_trx = $total_bayar_trx + $data_transaksi['bayar_kmhs'];
        $total_kewajiban = $total_kewajiban + $data_transaksi['kewajiban']['kmhs'];
    }
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(81, 5, 'Cicilan Semester : (' . $tahun_akademik . ' ' . $cetak_ganjil_genal . ')', 1, 0, 'L');
    $jml_bayarCSTX = [];
    foreach ($detailTX as $j => $dtx) {
        if ($dtx['id_jenis_pembayaran'] == 2) {
            $jml_bayarCSTX[] = $dtx['jml_bayar'];
        }
        if ($dtx['id_jenis_pembayaran'] == 3) {
            $jml_bayarCSTX[] = $dtx['jml_bayar'];
        }
        if ($dtx['id_jenis_pembayaran'] == 4) {
            $jml_bayarCSTX[] = $dtx['jml_bayar'];
        }
    }

    $pdf->SetFont('Courier', 'IB', 10);
    $total_bayar_cs_tx = array_sum($jml_bayarCSTX);
    $total_bayar_trx = $total_bayar_trx + $total_bayar_cs_tx;
    if ($data_transaksi['bayar_cs'] != null) {
        $total_kewajiban = $total_kewajiban + $data_transaksi['kewajiban']['cs'];
        $pdf->Cell(40, 5, number_format($data_transaksi['kewajiban']['cs'], 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5,  number_format($total_bayar_cs_tx, 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5, number_format($data_transaksi['kewajiban']['cs'] - $total_bayar_cs_tx, 0, '', '.'), 1, 1, 'R');
    } else {
        $total_kewajiban = $total_kewajiban + $data_transaksi['kewajibanCS'];
        $pdf->Cell(40, 5, number_format($data_transaksi['kewajibanCS'], 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5,  number_format($total_bayar_cs_tx, 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5, number_format($data_transaksi['kewajibanCS'] - $total_bayar_cs_tx, 0, '', '.'), 1, 1, 'R');
    }

    foreach ($detailTX as $j => $dtx) {
        if ($dtx['id_jenis_pembayaran'] == 2 || $dtx['id_jenis_pembayaran'] == 3 || $dtx['id_jenis_pembayaran'] == 4) {
            $pdf->SetFont('Courier', 'IB', 10);
            $pdf->Cell(81, 5, '#' . $dtx['nm_jenis_pembayaran'] . ' @Bayar Rp. ' . number_format($dtx['jml_bayar'], 0, '', '.'), 1, 0, 'L');
            $pdf->Cell(40, 5, '', 1, 0, 'R');
            $pdf->Cell(40, 5, '', 1, 0, 'R');
            $pdf->Cell(40, 5, '', 1, 1, 'R');
        }
    }
} else {
    foreach ($detailTX as $j => $dtx) {
        $pdf->SetFont('Courier', 'I', 10);
        $pdf->Cell(81, 5, $dtx['nm_jenis_pembayaran'], 1, 0, 'L');
        $pdf->SetFont('Courier', 'i', 10);
        $pdf->Cell(40, 5, number_format($dtx['kewajiban_Bayar'], 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5, number_format($dtx['jml_bayar'], 0, '', '.'), 1, 0, 'R');
        $pdf->Cell(40, 5, number_format($dtx['kewajiban_Bayar'] - $dtx['jml_bayar'], 0, '', '.'), 1, 1, 'R');
        $total_bayar_trx = $total_bayar_trx + $dtx['jml_bayar'];
        $total_kewajiban = $total_kewajiban + $dtx['kewajiban_Bayar'];
    }
}

//===============================================================
$pdf->SetFont('Courier', 'B', 11);
$pdf->Cell(81, 5, 'Total', 1, 0, 'R');
$pdf->SetFont('Courier', 'IB', 11);
$pdf->Cell(40, 5, number_format($total_kewajiban, 0, '', '.'), 1, 0, 'R');
$pdf->Cell(40, 5, number_format($total_bayar_trx, 0, '', '.'), 1, 0, 'R');
$pdf->Cell(40, 5, number_format($total_kewajiban - $total_bayar_trx, 0, '', '.'), 1, 1, 'R');

// footer
$pdf->SetY(-200);
$pdf->SetFont('Courier', 'UB', 9);
$pdf->Cell(20, 4, 'CATATAN', 0, 0, 'L');
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(3, 4, ':', 0, 0, 'L');
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(148, 4, '1. Syarat Bisa Perwalian Online, Harus Lunas Cicilan Ke-1 !', 0, 1, 'L');
$pdf->Cell(23, 4, '', 0, 0, 'L');
$pdf->Cell(148, 4, '2. Syarat Bisa Mengikuti UTS, Harus Lunas Cicilan Ke-2 !', 0, 1, 'L');
$pdf->Cell(23, 4, '', 0, 0, 'L');
$pdf->Cell(148, 4, '3. Syarat Bisa Mengikuti UAS, Harus Lunas Cicilan Ke-3 !', 0, 1, 'L');
$pdf->SetFont('Courier', '', 9);
$pdf->Cell(75, 5, '', 0, 1, 'R');

//terbilang
$data_terbilang = $Terbilang->bilang($total_bayar_trx);
$pdf->SetFont('Courier', 'IB', 9);
$pdf->Cell(65, 5, 'TERBILANG BAYAR :', 0, 0, 'L');
$pdf->SetFont('Courier', 'IB', 10);
$pdf->Cell(206, 5, 'Purwakarta : ' . $tglLog, 0, 1, 'C');

$pdf->SetFont('Courier', 'IB', 9);
$pdf->Cell(90, 19, $data_terbilang . 'Rupiah ', 1, 0, 'L');

$pdf->SetFont('Courier', 'IB', 8);
$pdf->Cell(57, 7, 'Staf Keuangan', 0, 0, 'C');
$pdf->Cell(5, 5, '', 0, 0, 'R');
$pdf->Cell(55, 7, 'Penyetor', 0, 1, 'C');
$pdf->SetFont('Courier', '', 9);
$pdf->Ln(5);
$pdf->Cell(70, 4, '', 0, 0, 'R');
$pdf->Image('assets/image/ttd/' . $admin_log['ttd'], 115, 124, 17);
$pdf->Cell(95, 9, '( ______________ )', 0, 0, 'C');
$pdf->Cell(5, 4, '', 0, 0, 'R');
$pdf->Cell(20, 9, '( ______________ )', 0, 1, 'C');
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(30, 1, 'tgl print : ' . $tglLog . ' | ' . $admin_log['ket_cetak'] . '/trx_ke-' . $data_transaksi['transaksi_ke'], 0, 0, 'L');

$pdf->SetFont('Courier', 'IB', 9);
$pdf->Cell(40, 4, '', 0, 0, 'R');
$pdf->Cell(93, 1, $admin_log['nama_user'], 0, 0, 'C');
$pdf->Cell(5, 4, '', 0, 0, 'R');
$pdf->Cell(25, 1, 'Nama Jelas', 0, 1, 'C');
$pdf->SetFont('Courier', 'I', 9);

$pdf->Line(7, 143, 207, 143);
$pdf->Cell(163, 7, '', 0, 0, 'R');
// end
//end kontent samping
$pdf->Output();
