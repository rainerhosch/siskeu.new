<?php
class PDF extends FPDF
{

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

    function Footer()
    {
        //Terbilang
        function satuan($inp)
        {
            if ($inp == 1) {
                return "Satu ";
            } else if ($inp == 2) {
                return "Dua ";
            } else if ($inp == 3) {
                return "Tiga ";
            } else if ($inp == 4) {
                return "Empat ";
            } else if ($inp == 5) {
                return "Lima ";
            } else if ($inp == 6) {
                return "Enam ";
            } else if ($inp == 7) {
                return "Tujuh ";
            } else if ($inp == 8) {
                return "Delapan ";
            } else if ($inp == 9) {
                return "Sembilan ";
            } else {
                return "";
            }
        }

        function belasan($inp)
        {
            $proses = $inp; //substr($inp, -1);
            if ($proses == '11') {
                return "Sebelas ";
            } else {
                $proses = substr($proses, 1, 1);
                return satuan($proses) . "Belas ";
            }
        }

        function puluhan($inp)
        {
            $proses = $inp; //substr($inp, 0, -1);
            if ($proses == 1) {
                return "Sepuluh ";
            } else if ($proses == 0) {
                return '';
            } else {
                return satuan($proses) . "Puluh ";
            }
        }

        function ratusan($inp)
        {
            $proses = $inp; //substr($inp, 0, -2);
            if ($proses == 1) {
                return "Seratus ";
            } else if ($proses == 0) {
                return '';
            } else {
                return satuan($proses) . "Ratus ";
            }
        }

        function ribuan($inp)
        {
            $proses = $inp; //substr($inp, 0, -3);
            if ($proses == 1) {
                return "Seribu ";
            } else if ($proses == 0) {
                return '';
            } else {
                return satuan($proses) . "Ribu ";
            }
        }

        function jutaan($inp)
        {
            $proses = $inp; //substr($inp, 0, -6);
            if ($proses == 0) {
                return '';
            } else {
                return satuan($proses) . "Juta ";
            }
        }

        function milyaran($inp)
        {
            $proses = $inp; //substr($inp, 0, -9);
            if ($proses == 0) {
                return '';
            } else {
                return satuan($proses) . "Milyar ";
            }
        }

        function bilang($rp)
        {
            $kata = "";
            $rp = trim($rp);
            if (strlen($rp) >= 10) {
                $angka = substr($rp, strlen($rp) - 10, -9);
                $kata = $kata . milyaran($angka);
            }
            $tambahan = "";
            if (strlen($rp) >= 9) {
                $angka = substr($rp, strlen($rp) - 9, -8);
                $kata = $kata . ratusan($angka);
                if ($angka > 0) {
                    $tambahan = "Juta ";
                }
            }
            if (strlen($rp) >= 8) {
                $angka = substr($rp, strlen($rp) - 8, -7);
                $angka1 = substr($rp, strlen($rp) - 7, -6);
                if (($angka == 1) && ($angka1 > 0)) {
                    $angka = substr($rp, strlen($rp) - 8, -6);
                    //echo " belasan".($angka)." ";
                    $kata = $kata . belasan($angka) . "Juta ";
                } else {
                    $angka = substr($rp, strlen($rp) - 8, -7);
                    //echo " puluhan".($angka)." ";
                    $kata = $kata . puluhan($angka);
                    if ($angka > 0) {
                        $tambahan = "Juta ";
                    }

                    $angka = substr($rp, strlen($rp) - 7, -6);
                    //echo " ribuan".($angka)." ";
                    $kata = $kata . ribuan($angka);
                    if ($angka == 0) {
                        $kata = $kata . $tambahan;
                    }
                }
            }
            if (strlen($rp) == 7) {
                $angka = substr($rp, strlen($rp) - 7, -6);
                $kata = $kata . jutaan($angka);
                if ($angka == 0) {
                    $kata = $kata . $tambahan;
                }
            }
            $tambahan = "";
            if (strlen($rp) >= 6) {
                $angka = substr($rp, strlen($rp) - 6, -5);
                $kata = $kata . ratusan($angka);
                if ($angka > 0) {
                    $tambahan = "Ribu ";
                }
            }
            if (strlen($rp) >= 5) {
                $angka = substr($rp, strlen($rp) - 5, -4);
                $angka1 = substr($rp, strlen($rp) - 4, -3);
                if (($angka == 1) && ($angka1 > 0)) {
                    $angka = substr($rp, strlen($rp) - 5, -3);
                    //echo " belasan".($angka)." ";
                    $kata = $kata . belasan($angka) . "Ribu ";
                } else {
                    $angka = substr($rp, strlen($rp) - 5, -4);
                    //echo " puluhan".($angka)." ";
                    $kata = $kata . puluhan($angka);
                    if ($angka > 0) {
                        $tambahan = "Ribu ";
                    }

                    $angka = substr($rp, strlen($rp) - 4, -3);
                    //echo " ribuan".($angka)." ";
                    $kata = $kata . ribuan($angka);
                    if ($angka == 0) {
                        $kata = $kata . $tambahan;
                    }
                }
            }
            if (strlen($rp) == 4) {
                $angka = substr($rp, strlen($rp) - 4, -3);
                //echo " ribuan".($angka)." ";
                $kata = $kata . ribuan($angka);
                if ($angka == 0) {
                    $kata = $kata . $tambahan;
                }
            }
            if (strlen($rp) >= 3) {
                $angka = substr($rp, strlen($rp) - 3, -2);
                //echo " ratusan".($angka)." ";
                $kata = $kata . ratusan($angka);
            }
            if (strlen($rp) >= 2) {
                $angka = substr($rp, strlen($rp) - 2, -1);
                $angka1 = substr($rp, strlen($rp) - 1);
                if (($angka == 1) && ($angka1 > 0)) {
                    $angka = substr($rp, strlen($rp) - 2);
                    //echo " belasan".($angka)." ";
                    $kata = $kata . belasan($angka);
                } else {
                    //echo " puluhan".($angka)." ";
                    $kata = $kata . puluhan($angka);

                    $angka = substr($rp, strlen($rp) - 1);
                    //echo " satuan".($angka)." ";
                    $kata = $kata . satuan($angka);
                }
            }
            if (strlen($rp) == 1) {
                $angka = substr($rp, strlen($rp) - 1);
                //echo " satuan".($angka)." ";
                $kata = $kata . satuan($angka);
            }
            return $kata;
        }
        // end Terbilang

        $this->SetY(-200);
        //$this->SetY(-40);
        //Tanggal
        //Tanggal
        $this->SetFont('Arial', 'UB', 9);
        $this->Cell(20, 4, 'CATATAN :', 0, 0, 'L');
        $this->SetFont('Arial', '', 9);
        $this->Cell(148, 4, '1. Syarat Bisa Perwalian Online, Harus Lunas Cicilan Ke-1 !', 0, 1, 'L');
        $this->Cell(20, 4, '', 0, 0, 'L');
        $this->Cell(148, 4, '2. Syarat Bisa Mengikuti UTS, Harus Lunas Cicilan Ke-2 !', 0, 1, 'L');
        $this->Cell(20, 4, '', 0, 0, 'L');
        $this->Cell(148, 4, '3. Syarat Bisa Mengikuti UAS, Harus Lunas Cicilan Ke-3 !', 0, 1, 'L');
        $this->SetFont('Arial', '', 9);
        $this->Cell(75, 5, '', 0, 0, 'R');
        // $this->Cell(60, 6, 'Tanggal : ' . $_GET['tgl'], 0, 1, 'C');

        $this->Cell(60, 6, 'Tanggal : 26  Agustus 1995', 0, 1, 'C');
        //Paraf
        $this->Cell(70, 5, '', 0, 0, 'R');
        $this->Cell(30, 5, 'Staf Keuangan', 0, 0, 'C');
        $this->Cell(5, 5, '', 0, 0, 'R');
        $this->Cell(30, 5, 'Penyetor', 0, 1, 'C');

        //terbilang
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(70, 5, 'TERBILANG BAYAR :', 0, 1, 'L');
        $this->Cell(70, 5, bilang(2000000) . 'Rupiah', 1, 0, 'L');

        $this->SetFont('Arial', '', 9);
        $this->Ln(5);
        $this->Cell(70, 4, '', 0, 0, 'R');
        $this->Cell(30, 4, '( ______________ )', 0, 0, 'C');
        $this->Cell(5, 4, '', 0, 0, 'R');
        $this->Cell(30, 4, '( ______________ )', 0, 1, 'C');

        $this->Cell(70, 4, '', 0, 0, 'R');



        $this->Cell(30, 4, 'Yudi', 0, 0, 'C');
        $this->Image('assets/image/ttd/adm1.jpg', 80, 120, 17);

        // $this->Cell(30, 4, $tmpl['nama_user'], 0, 0, 'C');
        // $this->Image('assets/image/ttd/' . $tmpl['ttd'], 80, 120, 17);
        $this->Cell(5, 4, '', 0, 0, 'R');
        $this->Cell(30, 4, 'Nama Jelas', 0, 1, 'C');
        $this->SetFont('Arial', 'I', 9);
        $this->Line(7, 140, 140, 138);
        $this->Cell(30, 7, '26-08-20202' . '/trx_ke-4', 0, 0, 'L');
        $this->Cell(163, 7, '', 0, 0, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);

foreach ($data_transaksi as $i => $tmpl) {
    $id_transaksi = $tmpl['id_transaksi'];
    $detailTX = $tmpl['detail_transaksi'];
    // $q_reg = mysqli_query("SELECT reg_manual,kewajiban_C2,C2, kewajiban_C3, C3, total_sisa_bayar FROM transaksi WHERE id_transaksi='$id_transaksi'");
    // $data_reg = mysqli_fetch_array($q_reg);
    //PERSIAPAN PERTIMBANGAN IF UNTUK UTS
    foreach ($detailTX as $j => $dtx) {
        if ($dtx['id_jenis_pembayaran'] == 2) {
            $dataIf[] = 'C1';
        } elseif ($dtx['id_jenis_pembayaran'] == 3) {
            $dataIf[] = 'C2';
        } elseif ($dtx['id_jenis_pembayaran'] == 4) {
            $dataIf[] = 'C3';
        }

        // if ($dtx['id_jenis_pembayaran'] == 2 && $dtx['id_jenis_pembayaran'] == 3 && $dtx['id_jenis_pembayaran'] == 4) {
        //     $dataIf = 'Bisa mengikuti Uas';
        // } else {
        //     if ($dtx['id_jenis_pembayaran'] == 2 && $dtx['id_jenis_pembayaran'] == 3) {
        //         $dataIf = 'Bisa Mengikuti Uts';
        //     } else {
        //         if ($dtx['id_jenis_pembayaran'] == 2) {
        //             $dataIf = 'Aktif Perwalian';
        //         }
        //     }
        // }
    }
    // var_dump(123); 
    // foreach($dataIf as $da){
    //     if($da = 'C1' && $da)
    // }
    // die;
    // if (($data_reg['reg_manual'] == 1) or ($data_reg['reg_manual'] == 2 and $data_reg['kewajiban_C2'] == $data_reg['C2'])) {
    //IF UNTUK UAS
    //(tidak dipake) if(($data_reg['total_sisa_bayar']==0 ) or ($data_reg['reg_manual']==3) ){
    //if(($data_reg['total_sisa_bayar']==0) or ($data_reg['reg_manual']==3) or ($data_reg['total_sisa_bayar']<=500000) ){
    //Surat Keterangan Bisa Mengikuti UTS/UAS
    $pdf->Image('assets/image/gunting.png', 139, 4, 12);
    $pdf->Line(145, 12, 145, 143);

    $pdf->Line(150, 5, 150, 143);
    $pdf->Line(150, 5, 207, 5);
    $pdf->Line(207, 5, 207, 143);
    $pdf->Line(150, 143, 207, 143);
    $pdf->SetFont('Arial', '', 9);
    $pdf->RotatedText(148, 121, 'Potong kertas disini untuk ditukar dengan kartu Ujian ke bagian akademik !', 90);
    //$pdf->RotatedText(148,121,'Potong kertas disini untuk ditukar dengan kartu UAS ke bagian akademik !',90);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->RotatedImage('assets/image/logo2.jpg', 151, 140, 11, 11, 90);


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
    $judul = 'SURAT PENGANTAR PENGAMBILAN KARTU UAS';
    $ket = 'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Akhir Semester (UAS)';
    // new update
    $pdf->RotatedText(155, 120, $judul, 90);

    // $pdf->RotatedText(155,120,'SURAT PENGANTAR PENGAMBILAN KARTU UTS',90);
    // $pdf->RotatedText(155,120,'SURAT PENGANTAR PENGAMBILAN KARTU UAS',90);


    $pdf->SetFont('Arial', 'B', 10);
    $pdf->RotatedText(160, 128, 'STT. WASTUKANCANA - TAHUN AKADEMIK 2020/2021 GANJIL', 90);
    $pdf->Line(163, 5, 163, 143);
    $pdf->SetFont('Arial', '', 10);
    $pdf->RotatedText(168, 140, 'Yang bertanda tangan di bawah ini menerangkan bahwa :', 90);
    $pdf->SetFont('Arial', 'B', 10);

    $pdf->RotatedText(173, 140, 'Nama/Kode', 90);
    $pdf->RotatedText(173, 117, ': ' . $tmpl['nim'] . ' / ' . $tmpl['id_transaksi'], 90);

    $pdf->RotatedText(178, 140, 'NIM/Jurusan', 90);
    // $pdf->RotatedText(178, 117, ': ' . $tmpl['nim'] . ' / ' . $_GET['jur'], 90);

    $pdf->RotatedText(178, 117, ': ' . $tmpl['nim'] . ' / Informatika', 90);

    $pdf->SetFont('Arial', '', 10);

    // new update
    $pdf->RotatedText(185, 140, $ket, 90);
    // //UNTUK UTS
    // if($data_reg['reg_manual']==1){
    // $pdf->RotatedText(185,140,'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Tengah Semester (UTS)',90);
    // // UNTUK UAS
    // if($data_reg['reg_manual']==3){
    // 	$pdf->RotatedText(185,140,'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Akhir Semester (UAS)',90);
    // }
    // // UNTUK UTS
    // else if($data_reg['reg_manual']==2){
    // $pdf->RotatedText(185,140,'Telah Melunasi Cicilan Ke-2 dan berhak mengikuti Ujian Tengah Semester (UTS)',90);
    // // UNTUK UAS

    // else if($data_reg['total_sisa_bayar']==0){
    // 	$pdf->RotatedText(185,140,'Telah Melunasi Cicilan Ke-3 dan berhak mengikuti Ujian Akhir Semester (UAS)',90);

    // }
    // else if($data_reg['total_sisa_bayar']<=500000){
    // 	$pdf->RotatedText(185,140,'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Akhir Semester (UAS)',90);

    // }
    $pdf->RotatedText(185, 140, 'Telah mendapat persetujuan PK-2 & berhak mengikuti Ujian Akhir Semester (UAS)', 90);
    $pdf->RotatedText(189, 140, 'Tahun Akademik 2020/2021 Ganjil.', 90);
    date_default_timezone_set('Asia/Jakarta');
    $pdf->RotatedText(192, 45, date('d M Y'), 90);
    $pdf->SetFont('Arial', 'U', 10);
    // $pdf->RotatedText(205, 45, $_GET['user'], 90);
    $pdf->RotatedText(205, 45, 'Yudi', 90);
    $pdf->RotatedImage('assets/image/ttd/adm1.jpg', 193, 45, 15, 9, 90);
    //end Surat Keterangan Bisa Mengikuti UTS/UAS
    // } 
    // else {
    //     $pdf->Line(140, 5, 140, 143);
    //     $pdf->Line(140, 5, 207, 5);
    //     $pdf->Line(207, 5, 207, 143);
    //     $pdf->Line(140, 143, 207, 143);
    //     $pdf->SetFont('Arial', 'B', 14);
    //     $pdf->RotatedText(145, 120, 'HALAMAN INI SENGAJA DIKOSONGKAN !', 55);
    // }
}

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

//$pdf->SetTextColor(255,255,255);
//$pdf->SetFillColor(0,0,255);
$pdf->Ln(3);
//$pdf->Cell(190,5,'MANAJEMEN INDUSTRI  |  TEKNIK TEKSTIL  |  TEKNIK INFORMATIKA  |  TEKNIK MESIN  |  TEKNIK INDUSTRI',1,1,'C');
$pdf->Line(7, 29, 135, 29);
$pdf->Line(8, 30, 134, 30);
$pdf->Line(8, 30.5, 134, 30.5);
//$pdf->Line(10,30,199,30);
$pdf->Ln(6);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(130, 4, 'TANDA BUKTI SETORAN BIAYA KULIAH', 0, 1, 'C');
//$pdf->Line(11,32,198,32);
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(5);
$pdf->Cell(25, 4, 'No. Transaksi', 0, 0, 'L');
$pdf->Cell(30, 4, ': ' . $tmpl['id_transaksi'], 0, 0, 'L');
$pdf->Cell(15, 4, 'Nama', 0, 0, 'L');
$pdf->Cell(70, 4, ': ' . substr($tmpl['nim'], 0, 25), 0, 1, 'L');
$pdf->Cell(25, 4, 'NIM', 0, 0, 'L');
$pdf->Cell(30, 4, ': ' . $tmpl['nim'], 0, 0, 'L');
$pdf->Cell(15, 4, 'Jurusan', 0, 0, 'L');

// $pdf->Cell(70, 4, ': ' . $_GET['jur'], 0, 1, 'L');
$pdf->Cell(70, 4, ': Informatika', 0, 1, 'L');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(66, 5, 'Jenis Biaya', 1, 0, 'C');
$pdf->Cell(24, 5, 'Kewajiban', 1, 0, 'C');
$pdf->Cell(20, 5, 'Jml Bayar', 1, 0, 'C');
$pdf->Cell(20, 5, 'Sisa Bayar', 1, 1, 'C');

//TUNGGAKAN
$pdf->SetFont('Arial', '', 11);

// if ($tmpl['kewajiban_TG'] != 0) {
//     //$pdf->Cell(66,5,'Tunggakan',1,0,'L');
//     $pdf->Cell(66, 5, 'Sisa Pembayaran Semester', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_TG'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['TG'], 0, '', '.'), 1, 0, 'R');

//     $pdf->Cell(20, 5, number_format($tmpl['sisa _TG'], 0, '', '.'), 1, 1, 'R');
// }
// //UANG BANGUNAN
// if ($tmpl['kewajiban_UB'] != 0) {
//     $pdf->Cell(66, 5, 'Uang Bangunan', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_UB'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['UB'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_UB'], 0, '', '.'), 1, 1, 'R');
// }
// //KEMAHASISWAAN
// if ($tmpl['kewajiban_kmhs'] != 0) {
//     $pdf->Cell(66, 5, 'Kemahasiswaan', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_kmhs'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['kmhs'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_kmhs'], 0, '', '.'), 1, 1, 'R');
// }

$pdf->Cell(66, 5, 'Sisa Pembayaran Semester', 1, 0, 'L');
$pdf->Cell(24, 5, number_format(2000000, 0, '', '.'), 1, 0, 'R');
$pdf->Cell(20, 5, number_format(2000000, 0, '', '.'), 1, 0, 'R');
//CICILAN SEMESTER

// if ($tmpl['kewajiban_CS'] != 0) {
//     //Jika Bayaran dilunasi
//     /*
// 		if($tmpl['lunasi_CS']!=0){
// 			$jml_byr_cs = $tmpl['lunasi_CS'];
// 			$pdf->Cell(90,4,'Cicilan Semester : (DILUNASI)',1,0,'L');
// 			$pdf->Cell(40,4,number_format($tmpl['kewajiban_CS'],0,'','.'),1,0,'R');
// 			$pdf->Cell(30,4,number_format($jml_byr_cs,0,'','.'),1,0,'R');
// 			$pdf->Cell(30,4,number_format($tmpl['sisa_CS'],0,'','.'),1,1,'R');
// 		}
// 		*/
//     //end Jika Bayaran dilunasi
//     //Jika Bayaran dicicil
//     //else{
//     $akademikAktif_tmpl = substr($tmpl['idtahun'], 0, -1);
//     $akademikAktif_tmplNext = $akademikAktif_tmpl + 1;
//     $akademikAktif_cek = substr($tmpl['idtahun'], 4);
//     if ($akademikAktif_cek == 1) {
//         $smt = "Ganjil";
//     } else {
//         $smt = "Genap";
//     }
//     $t_akad = $akademikAktif_tmpl . "/$akademikAktif_tmplNext " . $smt;
//     $jml_byr_cs = $tmpl['C1'] + $tmpl['C2'] + $tmpl['C3'];
//     $pdf->Cell(66, 5, 'Cicilan Smstr : (' . $t_akad . ')', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_CS'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($jml_byr_cs, 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_CS'], 0, '', '.'), 1, 1, 'R');
//     $pdf->SetFont('Arial', 'I', 10);
//     if ($tmpl['C1'] != 0) {
//         $pdf->Cell(66, 5, '#Cicilan Ke-1 @Bayar Rp. ' . number_format($tmpl['C1'], 0, '', '.'), 1, 0, 'L');
//         $pdf->Cell(24, 5, '', 1, 0, 'R');
//         $pdf->Cell(20, 5, '', 1, 0, 'R');
//         $pdf->Cell(20, 5, '', 1, 1, 'R');
//     }
//     if ($tmpl['C2'] != 0) {
//         $pdf->Cell(66, 5, '#Cicilan Ke-2 @Bayar Rp. ' . number_format($tmpl['C2'], 0, '', '.'), 1, 0, 'L');
//         $pdf->Cell(24, 5, '', 1, 0, 'R');
//         $pdf->Cell(20, 5, '', 1, 0, 'R');
//         $pdf->Cell(20, 5, '', 1, 1, 'R');
//     }
//     if ($tmpl['C3'] != 0) {
//         $pdf->Cell(66, 5, '#Cicilan Ke-3 @Bayar Rp. ' . number_format($tmpl['C3'], 0, '', '.'), 1, 0, 'L');
//         $pdf->Cell(24, 5, '', 1, 0, 'R');
//         $pdf->Cell(20, 5, '', 1, 0, 'R');
//         $pdf->Cell(20, 5, '', 1, 1, 'R');
//     }
//     //}
//     //end Jika Bayaran dicicil
// }

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(66, 5, 'Judul TA', 1, 0, 'L');
$pdf->Cell(24, 5, number_format(2000000, 0, '', '.'), 1, 0, 'R');
$pdf->Cell(20, 5, number_format(1000000, 0, '', '.'), 1, 0, 'R');
$pdf->Cell(20, 5, number_format(1000000, 0, '', '.'), 1, 1, 'R');
//SEMINAR KP
// if ($tmpl['kewajiban_EK'] != 0) {
//     $pdf->Cell(66, 5, 'Seminar KP', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_EK'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['EK'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_EK'], 0, '', '.'), 1, 1, 'R');
// }
// //JUDUL PROPOSAL TA
// if ($tmpl['kewajiban_PT'] != 0) {
//     $pdf->Cell(66, 5, 'Judul TA', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_PT'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['PT'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_PT'], 0, '', '.'), 1, 1, 'R');
// }
// //SEMINAR TA
// if ($tmpl['kewajiban_ET'] != 0) {
//     $pdf->Cell(66, 5, 'Seminar TA', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_ET'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['ET'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_ET'], 0, '', '.'), 1, 1, 'R');
// }
// //SIDANG TA
// if ($tmpl['kewajiban_IT'] != 0) {
//     $pdf->Cell(66, 5, 'Sidang TA', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_IT'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['IT'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_IT'], 0, '', '.'), 1, 1, 'R');
// }
// //BIAYA LAINNYA
// if ($tmpl['kewajiban_BL'] != 0) {
//     $pdf->Cell(66, 5, 'Biaya Lainnya (' . $tmpl['keterangan_bl'] . ')', 1, 0, 'L');
//     $pdf->Cell(24, 5, number_format($tmpl['kewajiban_BL'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['BL'], 0, '', '.'), 1, 0, 'R');
//     $pdf->Cell(20, 5, number_format($tmpl['sisa_BL'], 0, '', '.'), 1, 1, 'R');
// }
// //Total
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(66, 5, 'Total', 1, 0, 'R');
$pdf->Cell(24, 5, number_format(3000000, 0, '', '.'), 1, 0, 'R');
$pdf->Cell(20, 5, number_format(2000000, 0, '', '.'), 1, 0, 'R');
$pdf->Cell(20, 5, number_format(1000000, 0, '', '.'), 1, 1, 'R');

// $pdf->Cell(24, 5, number_format($tmpl['total_kewajiban'], 0, '', '.'), 1, 0, 'R');
// $pdf->Cell(20, 5, number_format($tmpl['total_bayar'], 0, '', '.'), 1, 0, 'R');
// $pdf->Cell(20, 5, number_format($tmpl['total_sisa_bayar'], 0, '', '.'), 1, 1, 'R');


//$this->fpdf->Output('output.pdf','D');
//$this->fpdf->Output();
$pdf->Output();
