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
    // cex id tx
    public function cekTxId()
    {
        $this->db->select('MAX(id_transaksi) AS id_transaksi');
        $this->db->from('transaksi');
        return $this->db->get();
    }
    public function cekHistori($data)
    {
        $this->db->select('t.id_transaksi, td.id_detail_transaksi, t.tanggal, t.jam, t.nim, t.semester, td.id_jenis_pembayaran, mjp.nm_jenis_pembayaran, td.jml_bayar');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');
        $this->db->where($data);
        return $this->db->get();
    }

    // Add New Tx
    public function addNewTransaksi($data)
    {
        return $this->db->insert('transaksi', $data);
    }

    // add Detail TX
    public function addNewTXDetail($data)
    {
        return $this->db->insert('transaksi_detail', $data);
    }
}
