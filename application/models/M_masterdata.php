<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* file name     : M_masterdata
* file type     : models
* file packages : CodeIgniter 3
* author        : rizky ardiansyah
* date-create   : 14 Dec 2020
*/

class M_masterdata extends CI_Model
{
    // get data mahasiswa from simak
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


    public function getMahasiswaByNim($nim)
    {
        $this->db->select('*');
        $this->db->from('mahasiswa');
        $this->db->where($nim);
        return $this->db->get();
    }

    public function getDataMhs()
    {
        $this->db->select('*');
        $this->db->from('mahasiswa');
        return $this->db->get();
    }

    // insert data mahasiswa
    public function insertDataMhs($data)
    {
        return $this->db->insert('mahasiswa', $data);
    }

    // get smester aktif
    public function getSemesterAktif()
    {
        $this->db->select_max('id_smt');
        $this->db->from('kalender_akademik');
        return $this->db->get();
    }
    // insert data from simak
    public function insertDataTahunAkademik($data)
    {
        return $this->db->insert('kalender_akademik', $data);
    }
    // ubah status aktif
    public function updateStatusAktif($id_smt, $data)
    {
        $this->db->where('id_smt', $id_smt);
        $this->db->update('kalender_akademik', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get data biaya per angkatan
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

    // get Master Jenis Transaksi
    public function GetJenisPembayaran($data)
    {
        $this->db->select('id_jenis_pembayaran as id_jp, nm_jenis_pembayaran as nm_jp');
        $this->db->from('master_jenis_pembayaran');
        $this->db->where($data);
        $this->db->order_by('nm_jp', 'asc');
        return $this->db->get();
    }
}
