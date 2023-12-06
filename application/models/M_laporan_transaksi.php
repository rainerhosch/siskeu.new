<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : M_laporan_transaksi.php
 *  File Type             : Model
 *  File Package          : CI_Models
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Bayu Prasetio
 *  Date Created          : 05/12/2023
 *  Quots of the code     : 'rapihkan code mu, sebagaimana kau merapihkan kumis mu'
 */
class M_laporan_transaksi extends CI_Model
{
    public function template()
    {
        $this->db->select();
        $this->db->from();
        $this->db->join();
        $this->db->where();
        return $this->db->get();
    }

    // get data transaksi detail
    public function getDetailTx($data = null)
    {
        $this->db->select('
        t.id_transaksi,
        td.id_detail_transaksi,
        t.tanggal, t.jam,
        t.nim, t.semester,
        td.id_jenis_pembayaran,
        mjp.nm_jenis_pembayaran,
        td.jml_bayar,
        mjp.jenis_kas,
        td.potongan
        ');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');
        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->order_by('t.id_transaksi desc');
        return $this->db->get();
    }

    public function get_krs_smt($data = null)
    {
        $this->db->select('kn.`id_tahun_ajaran`, kn.`nipd`, m.`nm_pd`,m.`nm_jur`, m.tahun_masuk, wk.nama_kelas');
        $this->db->from('krs_new kn');
        $this->db->join('mahasiswa m', 'm.`nipd` = kn.`nipd`');
        $this->db->join('wastu_kelas wk', 'wk.`id_kelas` = m.`id_kelas`');
        $this->db->where($data);
        $this->db->group_by('kn.`id_tahun_ajaran`, kn.`nipd`');
        $this->db->order_by('m.tahun_masuk');
        return $this->db->get();
    }

    public function get_kms_data($data = null)
    {
        $this->db->select('t.`nim`, t.`semester`, mjp.`nm_jenis_pembayaran`, td.*');
        $this->db->from('`transaksi` t');
        $this->db->join('transaksi_detail td', 'td.`id_transaksi` = t.`id_transaksi`');
        $this->db->join('master_jenis_pembayaran mjp', 'mjp.`id_jenis_pembayaran` = td.`id_jenis_pembayaran`');
        $this->db->where($data);
        return $this->db->get();
    }

    public function smt_aktif()
    {
        $this->db->select('MAX(id_tahun_ajaran) AS mak');
        $this->db->from('krs_new');
        return $this->db->get();
    }
}
