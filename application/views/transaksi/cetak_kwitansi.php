<?php
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
$pdf->SetFont('times', '', 11);
$pdf->Image('assets/image/logo2.jpg', 7, 8, 17);
$pdf->Cell(15, 5, '', 0, 0, 'L');

$pdf->Cell(104, 5, 'YAYASAN BUNGA BANGSA', 0, 0, 'C');
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(70, 4, '', 0, 1, 'L');
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(15, 5, '', 0, 0, 'L');
$pdf->Cell(107, 5, 'SEKOLAH TINGGI TEKNOLOGI', 0, 0, 'C');
$pdf->SetFont('times', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(70, 4, '', 0, 1, 'L');
$pdf->SetFont('times', 'B', 14);
$pdf->SetTextColor(255, 0, 0);
$pdf->Cell(15, 7, '', 0, 0, 'L');
$pdf->Cell(107, 7, 'STT. WASTUKANCANA PURWAKARTA', 0, 0, 'C');
$pdf->SetFont('times', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(70, 7, '', 0, 1, 'L');
$pdf->SetFont('Arial', 'BU', 11);
$pdf->SetFont('times', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(15, 6, '', 0, 0, 'L');
$pdf->Cell(106, 4, 'Jl. Raya Cikopak No. 53 Sadang - Purwakarta | Telp: (0264) 214952', 0, 0, 'C');
$pdf->SetFont('Arial', 'BU', 11);
// end headeer


// foreach ($data_transaksi as $i => $data_transaksi) {
if (substr($data_transaksi['semester'], 4) == 1) {
    $cetak_ganjil_genal = 'Ganjil';
} else {
    $cetak_ganjil_genal = 'Genap';
}

// $tglTrx = $pdf->format_tgl($data_transaksi['tanggal']);

$FormatTanggal = new FormatTanggal;
$Terbilang = new Terbilang;
$data_terbilang = $Terbilang->bilang($data_transaksi['total_bayar']);
$tglTrx = $FormatTanggal->konversi($data_transaksi['tanggal']);

$detailTX = $data_transaksi['detail_transaksi'];
$admin_log = $data_transaksi['admin_log'];

// data identitas penyetor
$pdf->Ln(3);
$pdf->Line(7, 29, 135, 29);
$pdf->Line(8, 30, 134, 30);
$pdf->Line(8, 30.5, 134, 30.5);
$pdf->Ln(6);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(130, 4, 'TANDA BUKTI SETORAN BIAYA KULIAH', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(5);
$pdf->Cell(25, 4, 'No. Transaksi', 0, 0, 'L');
$pdf->Cell(30, 4, ': ' . $data_transaksi['id_transaksi'], 0, 0, 'L');
$pdf->Cell(15, 4, 'Nama', 0, 0, 'L');
$pdf->Cell(70, 4, ': ' . substr($data_transaksi['nm_pd'], 0, 25), 0, 1, 'L');
$pdf->Cell(25, 4, 'NIM', 0, 0, 'L');
$pdf->Cell(30, 4, ': ' . $data_transaksi['nim'], 0, 0, 'L');
$pdf->Cell(15, 4, 'Jurusan', 0, 0, 'L');
$pdf->Cell(70, 4, ': ' .  $data_transaksi['nm_jur'], 0, 1, 'L');
// end


// rincian pembayaran
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(100, 5, 'Keterangan Pembayaran', 1, 0, 'C');
$pdf->Cell(30, 5, 'Jml Bayar', 1, 1, 'C');
if ($data_transaksi['data_kewajiban_cs'] != null || $data_transaksi['data_kewajiban_kmhs'] !== null) {
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(100, 5, 'Cicilan Semester : (2020/2021 ' . $cetak_ganjil_genal . ')', 1, 0, 'L');
    $pdf->SetFont('Arial', 'i', 11);
    $pdf->Cell(30, 5, number_format($data_transaksi['total_bayar'], 0, '', '.'), 1, 1, 'R');

    foreach ($detailTX as $j => $dtx) {
        $pdf->SetFont('Arial', 'I', 10);

        $pdf->Cell(100, 5, '#' . $dtx['nm_jenis_pembayaran'] . '  @Bayar Rp. ' . number_format($dtx['jml_bayar'], 0, '', '.'), 1, 0, 'L');
        // $pdf->Cell(24, 5, '', 1, 0, 'R');
        // $pdf->Cell(20, 5, '', 1, 0, 'R');
        $pdf->Cell(30, 5, '', 1, 1, 'R');

        // $pdf->Cell(100, 5, '#' . $dtx['nm_jenis_pembayaran'], 1, 0, 'L');
        // $pdf->SetFont('Arial', 'i', 10);
        // $pdf->Cell(30, 5, number_format($dtx['jml_bayar'], 0, '', '.'), 1, 1, 'R');
    }
} else {
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(100, 5, 'Semester : (2020/2021 ' . $cetak_ganjil_genal . ')', 1, 0, 'L');
    $pdf->SetFont('Arial', 'i', 11);
    $pdf->Cell(30, 5, '', 1, 1, 'R');
    foreach ($detailTX as $j => $dtx) {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(100, 5, $dtx['nm_jenis_pembayaran'], 1, 0, 'L');
        $pdf->SetFont('Arial', 'i', 10);
        $pdf->Cell(30, 5, number_format($dtx['jml_bayar'], 0, '', '.'), 1, 1, 'R');
    }
}

//===============================================================
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(100, 5, 'Total', 1, 0, 'R');
$pdf->Cell(30, 5, number_format($data_transaksi['total_bayar'], 0, '', '.'), 1, 1, 'R');


// footer
$pdf->SetY(-200);
$pdf->SetFont('Arial', 'UB', 9);
$pdf->Cell(20, 4, 'CATATAN :', 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(148, 4, '1. Syarat Bisa Perwalian Online, Harus Lunas Cicilan Ke-1 !', 0, 1, 'L');
$pdf->Cell(20, 4, '', 0, 0, 'L');
$pdf->Cell(148, 4, '2. Syarat Bisa Mengikuti UTS, Harus Lunas Cicilan Ke-2 !', 0, 1, 'L');
$pdf->Cell(20, 4, '', 0, 0, 'L');
$pdf->Cell(148, 4, '3. Syarat Bisa Mengikuti UAS, Harus Lunas Cicilan Ke-3 !', 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(75, 5, '', 0, 1, 'R');

//terbilang
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(20, 5, 'TERBILANG BAYAR :', 0, 0, 'L');
$pdf->Cell(180, 5, 'Purwakarta : ' . $tglTrx, 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(70, 19, $data_terbilang . 'Rupiah ', 1, 0, 'L');
$pdf->Cell(31, 7, 'Staf Keuangan', 0, 0, 'C');
$pdf->Cell(5, 5, '', 0, 0, 'R');
$pdf->Cell(30, 7, 'Penyetor', 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Ln(5);
$pdf->Cell(70, 4, '', 0, 0, 'R');
$pdf->Image('assets/image/ttd/' . $admin_log['ttd'], 81, 124, 17);
$pdf->Cell(31, 9, '( ______________ )', 0, 0, 'C');
$pdf->Cell(5, 4, '', 0, 0, 'R');
$pdf->Cell(30, 9, '( ______________ )', 0, 1, 'C');
$pdf->Cell(30, 1, $data_transaksi['tanggal'] . '/trx_ke-4', 0, 0, 'L');
$pdf->Cell(40, 4, '', 0, 0, 'R');
$pdf->Cell(30, 1, $admin_log['nama_user'], 0, 0, 'C');
$pdf->Cell(5, 4, '', 0, 0, 'R');
$pdf->Cell(30, 1, 'Nama Jelas', 0, 1, 'C');
$pdf->SetFont('Arial', 'I', 9);

$pdf->Line(7, 143, 140, 143);
$pdf->Cell(163, 7, '', 0, 0, 'R');
// end







// if ($data_reg['total_sisa_bayar'] == 0) {
//     $judul = 'SURAT PENGANTAR PENGAMBILAN KARTU UAS';
//     $ket = 'Telah Melunasi Cicilan Ke-3 dan berhak mengikuti Ujian Akhir Semester (UAS)';
// } else if ($data_reg['total_sisa_bayar'] <= 500000) {
//     $judul = 'SURAT PENGANTAR PENGAMBILAN KARTU UAS';
//     $ket = 'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Akhir Semester (UAS)';
// } else {
//     if ($data_reg['reg_manual'] == 1) {
//         $judul = 'SURAT PENGANTAR PENGAMBILAN KARTU UTS';
//         $ket = 'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Tengah Semester (UTS)';
//     } else if ($data_reg['reg_manual'] == 2) {
//         $judul = 'SURAT PENGANTAR PENGAMBILAN KARTU UTS';
//         $ket = 'Telah Melunasi Cicilan Ke-2 dan berhak mengikuti Ujian Tengah Semester (UTS)';
//     } else if ($data_reg['reg_manual'] == 3) {
//         $judul = 'SURAT PENGANTAR PENGAMBILAN KARTU UAS';
//         $ket = 'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Akhir Semester (UAS)';
//     }
// }

// konten bagian samping
if ($data_transaksi['data_kewajiban_cs'] != null || $data_transaksi['data_kewajiban_kmhs'] !== null) {
    // layout keterangan bag samping
    $pdf->Image('assets/image/gunting.png', 139, 4, 12);
    $pdf->Line(145, 12, 145, 143);
    $pdf->Line(150, 5, 150, 143);
    $pdf->Line(150, 5, 207, 5);
    $pdf->Line(207, 5, 207, 143);
    $pdf->Line(150, 143, 207, 143);
    $pdf->SetFont('Arial', 'i', 9);
    $pdf->RotatedText(148, 121, 'Potong atau gunting kertas disini  !', 90);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->RotatedImage('assets/image/logo2.jpg', 151, 140, 11, 11, 90);
    // end
    $judul = 'SURAT PENGANTAR PENGAMBILAN KARTU UAS';
    $ket = 'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Akhir Semester (UAS)';
    $pdf->RotatedText(155, 120, $judul, 90);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->RotatedText(160, 128, 'STT. WASTUKANCANA - TAHUN AKADEMIK 2020/2021 GANJIL', 90);
    $pdf->Line(163, 5, 163, 143);
    $pdf->SetFont('Arial', '', 10);
    $pdf->RotatedText(168, 140, 'Yang bertanda tangan di bawah ini menerangkan bahwa :', 90);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->RotatedText(173, 140, 'Nama/Kode', 90);
    $pdf->RotatedText(173, 117, ': ' . $data_transaksi['nm_pd'] . ' / ' . $data_transaksi['id_transaksi'], 90);
    $pdf->RotatedText(178, 140, 'NIM/Jurusan', 90);
    $pdf->RotatedText(178, 117, ': ' . $data_transaksi['nim'] . ' /' . $data_transaksi['nm_jur'], 90);
    $pdf->SetFont('Arial', '', 10);
    $pdf->RotatedText(185, 140, $ket, 90);
    $pdf->RotatedText(189, 140, 'Tahun Akademik 2020/2021 Ganjil.', 90);

    // date_default_timezone_set('Asia/Jakarta');
    // $pdf->RotatedText(192, 45, date('d M Y'), 90);
    $pdf->RotatedText(192, 45, $tglTrx, 90);
    $pdf->SetFont('Arial', 'U', 10);
    $pdf->RotatedText(205, 45, $data_transaksi['nama_user'], 90);
    $pdf->RotatedImage('assets/image/ttd/' . $data_transaksi['ttd'], 193, 45, 15, 9, 90);
} else {
    $pdf->Line(143, 5, 143, 143);
    $pdf->Line(143, 5, 207, 5);
    $pdf->Line(207, 5, 207, 143);
    $pdf->Line(143, 143, 207, 143);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->RotatedText(147, 120, 'HALAMAN INI SENGAJA DIKOSONGKAN !', 55);
}


//end kontent samping
// }
$pdf->Output();
