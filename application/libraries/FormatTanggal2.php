<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class FormatTanggal2
{
    function konversi($tgl)
    {
        $tanggal = explode("-", $tgl);
        $bln = $tanggal[1];
        switch ($bln) {
            case 1:
                $bulan = "Jan";
                break;
            case 2:
                $bulan = "Feb";
                break;
            case 3:
                $bulan = "Mar";
                break;
            case 4:
                $bulan = "Apr";
                break;
            case 5:
                $bulan = "Mei";
                break;
            case 6:
                $bulan = "Jun";
                break;
            case 7:
                $bulan = "Jul";
                break;
            case 8:
                $bulan = "Aug";
                break;
            case 9:
                $bulan = "Sep";
                break;
            case 10:
                $bulan = "Okt";
                break;
            case 11:
                $bulan = "Nov";
                break;
            case 12:
                $bulan = "Des";
                break;
        }
        return $tanggal[2] . " " . $bulan . " " . $tanggal[0];
    }
}
