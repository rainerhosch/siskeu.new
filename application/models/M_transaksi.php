<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* file name     : M_transaksi
* file type     : models
* file packages : CodeIgniter 3
* author        : rizky ardiansyah
* date-create   : 14 Dec 2020
*/

class M_transaksi extends CI_Model
{
    public function getMahasiswa($nipd)
    {
        $url = "https://api.wastu.digital/resources/mahasiswa?token=semogabahagia&nipd=" . $nipd;
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, "$url");
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        $data = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $data;
    }

    public function getBiayaAngkatan($data, $jenjang)
    {
        if ($jenjang === 'S1') {
            $this->db->select('angkatan, PK as uang_bangunan, kmhs as kemahasiswaan, CS as cicilan_semester');
        } else {
            $this->db->select('angkatan, PK as uang_bangunan, kmhs_D3 as kemahasiswaan, CS_D3 as cicilan_semester');
        }
        $this->db->from('biaya_angkatan');
        $this->db->where($data);
        return $this->db->get();
    }
}
