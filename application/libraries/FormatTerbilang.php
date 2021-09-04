<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class FormatTerbilang
{
    function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp =  $this->penyebut($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp =  $this->penyebut($nilai / 10) . " Puluh" .  $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" .  $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp =  $this->penyebut($nilai / 100) . " Ratus" .  $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" .  $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp =  $this->penyebut($nilai / 1000) . " Ribu" .  $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp =  $this->penyebut($nilai / 1000000) . " Juta" .  $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp =  $this->penyebut($nilai / 1000000000) . " Milyar" .  $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp =  $this->penyebut($nilai / 1000000000000) . " Trilyun" .  $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "Minus " . $this->penyebut($nilai) . ' Rupiah';
        } else {
            $hasil = $this->penyebut($nilai) . ' Rupiah';
        }
        return $hasil;
    }
}
