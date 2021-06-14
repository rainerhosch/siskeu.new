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
        $this->db->select('*');
        $this->db->from('transaksi');
        $this->db->where($data);
        return $this->db->get();
    }

    // Add New Tx
    public function addNewTransaksi($data)
    {
        return $this->db->insert('transaksi', $data);
    }
}
