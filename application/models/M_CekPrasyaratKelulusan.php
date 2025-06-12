<?php 
defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name             : M_CekPrasyaratKelulusan.php
    *  File Type             : Model
    *  File Package          : CI_Models
    ** * * * * * * * * * * * * * * * * * **
    *  Author                : Rizky Ardiansyah
    *  Date Created          : 10/06/2025
    *  Last Modified         : 10/06/2025
    *  Quots of the code     : 'sebuah code program bukanlah sebatas perintah-perintah yang ditulis di komputer, melainkan sebuah kesempatan berkomunikasi antara komputer dan manusia. (bagi yang tidak punya teman wkwk)'
*/
class M_CekPrasyaratKelulusan extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->database();
    }

    public function get_all()
    {
        /**
         * 
         * SELECT mahasiswa.*
         * FROM mahasiswa
         * JOIN krs_new on mahasiswa.nipd=krs_new.nipd
         * WHERE mahasiswa.no_transkip_nilai IS NULL AND mahasiswa.tgl_sk_yudisium IS NULL AND krs_new.id_tahun_ajaran >= 20241
         * GROUP BY krs_new.nipd;
         */

        $this->db->select('mahasiswa.*');
        $this->db->from('mahasiswa');
        $this->db->join('krs_new', 'mahasiswa.nipd = krs_new.nipd');
        // $this->db->where('mahasiswa.no_transkip_nilai IS NULL');
        // $this->db->where('mahasiswa.tgl_sk_yudisium IS NULL');
        $this->db->where('krs_new.id_tahun_ajaran >=', 20241);
        // $this->db->where('mahasiswa.tahun_masuk >=', 2018);
        $this->db->group_by('krs_new.nipd');
        return $this->db->get();
    }

    public function get_list_trx($where = null)
    {
        $this->db->select('transaksi.id_transaksi, transaksi.semester, master_jenis_pembayaran.nm_jenis_pembayaran, transaksi_detail.jml_bayar');
        $this->db->from('transaksi');
        $this->db->join('transaksi_detail','transaksi.id_transaksi = transaksi_detail.id_transaksi');
        $this->db->join('master_jenis_pembayaran', 'transaksi_detail.id_jenis_pembayaran = master_jenis_pembayaran.id_jenis_pembayaran');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->where_in('master_jenis_pembayaran.id_jenis_pembayaran', [10, 14, 15]);
        // $this->db->where_in('master_jenis_pembayaran.id_jenis_pembayaran', [2, 3, 4, 5, 10, 14, 15]);
        return $this->db->get();
    }

    public function get_payment_status($nim)
    {
        $this->db->select('status_pembayaran');
        $this->db->from('pembayaran');
        $this->db->where('nim', $nim);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->status_pembayaran;
        } else {
            return null;
        }
    }

    public function get_prasyarat_kelulusan($nim)
    {
        $this->db->select('*');
        $this->db->from('prasyarat_kelulusan');
        $this->db->where('nim', $nim);
        $query = $this->db->get();

        return $query->result_array();
    }
}